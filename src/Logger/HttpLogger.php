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

use AccessWatch\Exception;

use Bouncer\Logger\BaseLogger;

/**
 * Log Requests to the Access Watch Cloud Service using the HTTP protocol
 *
 * @author François Hodierne <francois@access.watch>
 */
class HttpLogger extends BaseLogger
{

    /**
     * @var object
     */
    protected $apiClient;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        if (isset($options['apiClient'])) {
            $this->apiClient = $options['apiClient'];
        } elseif (isset($options['apiKey'])) {
            $this->apiClient = new \AccessWatch\Api\ApiClient($options);
        }
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
    public function log(array $logEntry)
    {
        $entry = $this->format($logEntry);

        $this->getApiClient()->log($entry);
    }
}
