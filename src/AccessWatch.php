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
     * {@inheritDoc}
     */
    public function __construct(array $options = array())
    {
        if (empty($options['apiClient'])) {
            // Filter Api Client options
            $apiClientKeys = array('baseUrl', 'apiKey', 'siteUrl', 'httpClient');
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
    public function start()
    {
        parent::start();

        $identity = $this->getIdentity();

        if ($identity) {

            $session  = $identity->getSession();
            if ($session && $session->isBlocked()) {
                $this->block();
            }

            if ($identity->getAgentName() == 'accesswatch' && $identity->isNice()) {
                $this->feedback();
            }
        }
    }

    public function feedback()
    {
        $request = $this->getRequest();
        $action = $request->headers->get('Access-Watch-Action');
        if ($action) {
            switch ($action) {
                case 'session-update':
                    $sessionId = $request->headers->get('Access-Watch-Session');
                    $identities = $this->getApiClient()->getSessionIdentities($sessionId);
                    foreach ($identities as $identity) {
                        $this->getCache()->deleteIdentity($identity['id']);
                    }
                    break;
            }
        }
    }

}
