<?php

/*
 * This file is part of the Access Watch package.
 *
 * (c) François Hodierne <francois@access.watch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AccessWatch\Api;

/**
 * ApiClient class
 *
 * @author François Hodierne <francois@access.watch>
 */
class ApiClient
{

    /**
     * @var string
     */
    protected $baseUrl = 'https://api.access.watch/1.1';

    /**
     * @var string
     */
    protected $baseLogUrl = 'https://log.access.watch/1.1';

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $siteUrl;

    /**
     * @var object
     */
    protected $httpClient;

    /**
     * Constructor.
     *
     * @param array $params
     */
    public function __construct($params = array())
    {
        if (isset($params['baseUrl'])) {
            $this->baseUrl = $params['baseUrl'];
        }
        if (isset($params['baseLogUrl'])) {
            $this->baseLogUrl = $params['baseLogUrl'];
        }
        if (isset($params['apiKey'])) {
            $this->apiKey = $params['apiKey'];
        }
        if (isset($params['siteUrl'])) {
            $this->siteUrl = $params['siteUrl'];
        }
        if (isset($params['httpClient'])) {
            $this->httpClient = $params['httpClient'];
        }
    }

    /*
     * @return object
     */
    public function getHttpClient()
    {
        if (empty($this->httpClient)) {
            $this->httpClient = new \Bouncer\Http\SimpleClient();
        }

        if ($this->apiKey) {
            $this->httpClient->setApiKey($this->apiKey);
        }

        if ($this->siteUrl) {
            $this->httpClient->setSiteUrl($this->siteUrl);
        }

        return $this->httpClient;
    }

    /**
     * @param array $entry
     *
     * @return object
     */
    public function log(array $entry)
    {
        $result = $this->getHttpClient()->post("{$this->baseLogUrl}/log", $entry);

        if (!$result) {
            error_log("Error while logging to Http endpoint: {$this->baseLogUrl}/log");
        }
    }

    /**
     * @param array $params
     *
     * @return object
     */
    public function session(array $params)
    {
        return $this->getHttpClient()->post("{$this->baseUrl}/session", $params);
    }

    /**
     * @param string $sessionId
     *
     * @return array|null
     */
    public function getSessionIdentities($sessionId)
    {
        $result = $this->getHttpClient()->get("{$this->baseUrl}/session/{$sessionId}/identities");
        if (isset($result['identities'])) {
            return $result['identities'];
        }
    }

    /**
     * @return array|null
     */
    public function getRobots()
    {
        $result = $this->getHttpClient()->get("{$this->baseUrl}/robots");
        if (isset($result['robots'])) {
            return $result['robots'];
        }
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->getHttpClient()->get("{$this->baseUrl}/configuration");
    }

    /**
     * @return array
     */
    public function getReferers($status = null)
    {
        $url = $status ? "{$this->baseUrl}/referers/{$status}" : "{$this->baseUrl}/referers";

        $result = $this->getHttpClient()->get($url);

        return isset($result['referers']) ? $result['referers'] : array();
    }

}
