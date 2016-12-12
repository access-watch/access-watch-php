<?php

/*
 * This file is part of the Access Watch package.
 *
 * (c) François Hodierne <francois@access.watch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AccessWatch\Profile;

use Bouncer\Bouncer;
use Bouncer\Profile\DefaultProfile;
use AccessWatch\Analyzer\IdentityAnalyzer;
use AccessWatch\Logger\HttpLogger;
use AccessWatch\Logger\UdpLogger;

/**
 * Set Up base configuration for the Access Watch class
 *
 * @author François Hodierne <francois@access.watch>
 */
class BaseProfile extends DefaultProfile
{

    /**
     * @var array
     */
    protected $params;

    /**
     * Constructor.
     *
     * @param array $params
     */
    public function __construct($params = array())
    {
        $this->params = $params;
    }

    /**
     * {@inheritDoc}
     */
    public function loadAnalyzers(Bouncer $instance)
    {
        parent::loadAnalyzers($instance);

        // Load Access Watch analyzer
        $analyzer = new IdentityAnalyzer($this->params);
        $instance->registerAnalyzer('identity', array($analyzer, 'identityAnalyzer'));
    }

    /**
     * {@inheritDoc}
     */
    public function initLogger(Bouncer $instance)
    {
        // If no logger available, try to setup Access Watch Logger
        $logger = $instance->getLogger();
        if (empty($logger)) {
            $logger = new HttpLogger($this->params);
            $instance->setOptions(array('logger' => $logger));
        }
    }
}
