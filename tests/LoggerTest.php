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

class LoggerTest extends \PHPUnit_Framework_TestCase
{

    public function getRequest()
    {
        $ip = '92.78.176.182';
        $ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36';

        $server = array();
        $server['REMOTE_ADDR'] = $ip;
        $server['HTTP_USER_AGENT'] = $ua;
        $server['HTTP_HOST'] = 'francois.hodierne.net';
        $server['REQUEST_URI'] = '/resume';
        $server['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $server['HTTP_ACCEPT'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
        $server['HTTP_ACCEPT_LANGUAGE'] = 'en-US,en;q=0.8';
        $server['HTTP_ACCEPT_ENCODING'] = 'gzip, deflate, sdch';

        $request = new \Bouncer\Request;
        $request->initialize(array(), array(), array(), array(), array(), $server);

        return $request;
    }

    public function getBouncer($request, $logger)
    {
        $bouncer = new AccessWatch(array(
            'request' => $request,
            'logger'  => $logger,
        ));

        return $bouncer;
    }

    public function testLogAccessWatchLogger()
    {
        $request = $this->getRequest();

        $logger = new \AccessWatch\Logger\UdpLogger(array(
            'apiKey'  => 'b3bb90d61e80e96259bf354fd7cb03d7',
            'siteUrl' => 'https://github.com/access-watch/access-watch-php',
        ));

        $bouncer = $this->getBouncer($request, $logger);

        $bouncer->log();
    }

    public function testLogAccessWatchHttpLogger()
    {
        $request = $this->getRequest();

        $logger = new \AccessWatch\Logger\HttpLogger(array(
            'apiKey'  => 'b3bb90d61e80e96259bf354fd7cb03d7',
            'siteUrl' => 'https://github.com/access-watch/access-watch-php',
        ));

        $bouncer = $this->getBouncer($request, $logger);

        $bouncer->log();
    }
}
