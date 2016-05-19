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

use Bouncer\Logger\LogstashLogger;

/**
 * Log Requests to the Access Watch Cloud Service using the UDP protocol
 *
 * @author François Hodierne <francois@access.watch>
 */
class UdpLogger extends LogstashLogger
{

    /**
     * @var string
     */
    protected $host = 'access.watch';

    /**
     * @var int
     */
    protected $port = 5354;

    /**
     * @var string
     */
    protected $protocol = 'udp';

    /**
     * @var string
     */
    protected $channel = 'access_watch';

    /**
     * @var string
     */
    protected $type = 'access_log';

    /**
     * @var string
     */
    protected $key;

    /**
     * Constructor.
     *
     * @param array $params
     */
    public function __construct($params = array())
    {
        if (isset($params['apiKey'])) {
            $this->key = $params['apiKey'];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function log(array $logEntry)
    {
        if ($this->key) {
            $logEntry['key'] = $this->key;
        }

        parent::log($logEntry);
    }

}
