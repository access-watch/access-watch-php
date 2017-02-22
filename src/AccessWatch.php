<?php

/*
 * This file is part of the Access Watch package.
 *
 * (c) François Hodierne <francois@access.watch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AccessWatch;

use Bouncer\Bouncer;

/**
 * Access Watch class
 *
 * @author François Hodierne <francois@access.watch>
 */
class AccessWatch extends Bouncer
{

    /**
     * @var object
     */
    protected $apiClient;

    /**
     * @var array
     */
    protected $configuration;

    /**
     * {@inheritDoc}
     */
    public function __construct(array $options = array())
    {
        if (empty($options['apiClient'])) {
            // Filter Api Client options
            $apiClientKeys = array('baseUrl', 'baseLogUrl', 'apiKey', 'siteUrl', 'httpClient');
            $apiClientOptions = array_intersect_key($options, array_flip($apiClientKeys));
            $options['apiClient'] = new \AccessWatch\Api\ApiClient($apiClientOptions);
        }

        $this->apiClient = $options['apiClient'];

        if (empty($options['profile'])) {
            // Filter Profile options
            $profileKeys = array('baseUrl', 'apiKey', 'siteUrl', 'apiClient');
            $profileOptions = array_intersect_key($options, array_flip($profileKeys));
            $options['profile'] = new \AccessWatch\Profile\BaseProfile($profileOptions);
        }

        parent::__construct($options);
    }

    /*
     * @return object
     */
    public function getApiClient()
    {
        if (empty($this->apiClient)) {
            throw new Exception('No Api Client available.');
        }

        return $this->apiClient;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequest()
    {
        $request = parent::getRequest();

        $configuration = $this->getConfiguration();
        if (isset($configuration['trusted_proxies']) && is_array($configuration['trusted_proxies'])) {
            $request->setTrustedProxies(array_merge(array('127.0.0.1'), $configuration['trusted_proxies']));
        }

        return $request;
    }

    /**
     * @return array|null
     */
    public function getConfiguration()
    {
        // From local Cache
        if (isset($this->configuration)) {
            return $this->configuration;
        }

        // From Cache
        $cache = $this->getCache();
        if ($cache) {
            $configuration = $this->getCache()->get('configuration');
            if ($configuration) {
                return $this->configuration = $configuration;
            }
        }

        // From API
        if ($this->ended === false) {
            $configuration = $this->getApiClient()->getConfiguration();
            if ($cache) {
                $this->getCache()->set('configuration', $configuration, 86400);
            } else {
                $this->error('No cache available. Caching configuration is needed to keep performances acceptable.');
            }
            return $this->configuration = $configuration;
        }
    }

    public function feedback()
    {
        $request = $this->getRequest();
        $action = $request->headers->get('Access-Watch-Action');
        if ($action) {
            switch ($action) {
                case 'identity-update':
                    $identityId = $request->headers->get('Access-Watch-Identity');
                    $this->getCache()->deleteIdentity($identityId);
                    break;
                case 'session-update':
                    $sessionId = $request->headers->get('Access-Watch-Session');
                    $identities = $this->getApiClient()->getSessionIdentities($sessionId);
                    foreach ($identities as $identity) {
                        $this->getCache()->deleteIdentity($identity['id']);
                    }
                    break;
                case 'configuration-update':
                    $this->getCache()->delete('access_watch_configuration');
                    break;
                case 'cache-clean':
                    $this->getCache()->clean();
                    break;
                case 'cache-flush':
                    $this->getCache()->flush();
                    break;
            }
        }
    }

    /**
     * @param string $status
     *
     * @return array
     */
    public function getReferers($status = null)
    {
        $cache = $this->getCache();
        $cacheKey = $status ? "access_watch_referers_{$status}" : "access_watch_referers";

        if ($cache) {
            $referers = $this->getCache()->get($cacheKey);
        }

        if (!isset($referers) || !is_array($referers)) {
            $referers = $this->getApiClient()->getReferers($status);
            if ($cache) {
                $this->getCache()->set($cacheKey, $referers, 3600);
            }
        }

        return $referers;
    }

    public function blockBadReferers()
    {
        $referer = (string) $this->getRequest()->getHeader('Referer');

        if (!empty($referer)) {
            $badReferers = $this->getReferers('bad');
            if (!empty($badReferers) && in_array($referer, $badReferers)) {
                $this->block('referer_spam_blocked');
            }
        }
    }

    public function blockBadSessions()
    {
        $identity = $this->getIdentity();

        if ($identity) {
            $session = $identity->getSession();

            if ($session && $session->isBlocked()) {
                $this->block('session_blocked');
            }
        }
    }

}
