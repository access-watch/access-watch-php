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

class IdentityTest extends \PHPUnit_Framework_TestCase
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

    public function getAccessWatch($request)
    {
        $accessWatch = new AccessWatch(array(
            'request' => $request,
            'apiKey'  => 'b3bb90d61e80e96259bf354fd7cb03d7',
        ));

        return $accessWatch;
    }

    public function testGetIdentity()
    {
        $request = $this->getRequest();

        $accessWatch = $this->getAccessWatch($request);

        $identity = $accessWatch->getIdentity();

        $this->assertEquals('688926c9994666d5d5d4cf7e2429aabd', $identity->getId());
        $this->assertEquals('5a8433a81c1290cc5399eb60f26172d4', $identity->getSignature()->getId());
    }

    public function testGetIdentityAddress()
    {
        $request = $this->getRequest();

        $accessWatch = $this->getAccessWatch($request);

        $identity = $accessWatch->getIdentity();

        $address = $identity->getAddress();

        $this->assertEquals('e90d9f20cce9c203f439129b0943a8bb', $address->getId());
        $this->assertEquals('DE', $address->getCountryCode());
    }

    public function testGetIdentityUserAgent()
    {
        $request = $this->getRequest();

        $accessWatch = $this->getAccessWatch($request);

        $identity = $accessWatch->getIdentity();

        $userAgent = $identity->getUserAgent();

        $this->assertEquals('b516786e573a426eb842ec2132ed35e2', $userAgent->getId());
        $this->assertEquals('browser', $userAgent->getType());
        $this->assertEquals('chrome', $userAgent->getAgent()->getName());
    }

}
