<?php

/*
 * This file is part of the Access Watch package.
 *
 * (c) François Hodierne <francois@access.watch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AccessWatch\Logger;

use Bouncer\Logger\BaseLogger;

/**
 * Log Requests to the Access Watch Cloud Service using the HTTP protocol
 *
 * @author François Hodierne <francois@access.watch>
 */
class HttpLogger extends BaseLogger
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

    /**
     * {@inheritDoc}
     */
    public function log(array $logEntry)
    {
        $entry = $this->format($logEntry);

        $result = $this->getHttpClient()->post("{$this->baseUrl}/log", $entry);

        if (!$result) {
            error_log("Error while logging to Http endpoint: {$this->baseUrl}/log");
        }
    }
}
