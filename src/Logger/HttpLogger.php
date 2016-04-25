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
 * @author François Hodierne <francois@hodierne.net>
 */
class HttpLogger extends BaseLogger
{

    protected $baseUrl = 'https://access.watch/api/1.0';

    protected $key;

    protected $httpClient;

    public function __construct($params = array())
    {
        if (isset($params['baseUrl'])) {
            $this->baseUrl = $params['baseUrl'];
        }
        if (isset($params['apiKey'])) {
            $this->key = $params['apiKey'];
        }
        if (isset($params['httpClient'])) {
            $this->httpClient = $params['httpClient'];
        }
    }

    public function getHttpClient($apiKey)
    {
        if (empty($this->httpClient)) {
            $this->httpClient = new \Bouncer\Http\SimpleClient();
        }
        if ($apiKey) {
            $this->httpClient->setApiKey($apiKey);
        }
        return $this->httpClient;
    }

    /**
     * {@inheritDoc}
     */
    public function log(array $logEntry)
    {
        $entry = $this->format($logEntry);

        $result = $this->getHttpClient($this->key)->post("{$this->baseUrl}/log", $entry);

        if (!$result) {
            error_log("Error while logging to Http endpoint: {$this->baseUrl}/log");
        }
    }
}
