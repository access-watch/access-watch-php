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

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{

    public function getAccessWatch()
    {
        $accessWatch = new AccessWatch(array(
            'apiKey'  => 'b3bb90d61e80e96259bf354fd7cb03d7',
        ));

        return $accessWatch;
    }

    public function testGetConfiguration()
    {
        $accessWatch = $this->getAccessWatch();

        $configuration = $accessWatch->getConfiguration();

        $this->assertArrayHasKey('logger', $configuration);
        $this->assertArrayHasKey('trusted_proxies', $configuration);
    }

    public function testGetLogger()
    {
        $accessWatch = $this->getAccessWatch();

        $logger = $accessWatch->getLogger();

        $this->assertInstanceOf('\AccessWatch\Logger\HttpLogger', $logger);
    }

}
