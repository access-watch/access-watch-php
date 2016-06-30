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

class HttpClientTest extends \PHPUnit_Framework_TestCase
{

    public function testGetUserAgent()
    {
        $options = array(
          'apiKey'  => 'b3bb90d61e80e96259bf354fd7cb03d7',
          'siteUrl' => 'https://github.com/access-watch/access-watch-php',
        );

        $accessWatch = new AccessWatch($options);

        $this->assertEquals(
          'Bouncer Http; https://github.com/access-watch/access-watch-php',
          $accessWatch->getApiClient()->getHttpClient()->getUserAgent()
        );
    }

}
