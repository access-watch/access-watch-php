<?php

/*
 * This file is part of the Access Watch package.
 *
 * (c) François Hodierne <francois@access.watch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AccessWatch\Analyzer;

/**
 * Analyze Identity with the Access Watch Cloud Service
 *
 * @author François Hodierne <francois@access.watch>
 */
class IdentityAnalyzer
{

    /**
     * @var string
     */
    protected $baseUrl = 'https://access.watch/api/1.0';

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

    /*
     *
     * @param object $identity
     *
     * @return object
     */
    public function identityAnalyzer($identity)
    {
        $request = array(
            'address' => $identity->getAddress()->getValue(),
            'headers' => $identity->getHeaders(),
        );

        $session = $identity->getSession();
        if ($session) {
            $request['session'] = $session->getId();
        }

        $result = $this->getHttpClient()->post(
            "{$this->baseUrl}/session",
            $request
        );

        if (isset($result['identity']) && is_array($result['identity'])) {
            $identity->setAttributes($result['identity']);
        }

        if (isset($result['session']) && is_array($result['session'])) {
            $identity->setAttribute('session', $result['session']);
        }

        return $identity;
    }

}
