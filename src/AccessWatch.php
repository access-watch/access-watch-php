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

use Bouncer\Bouncer;

/**
 * Access Watch class
 *
 * @author François Hodierne <francois@access.watch>
 */
class AccessWatch extends Bouncer
{

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $profileKeys = array('baseUrl', 'apiKey', 'httpClient');
        $profileOptions = array_intersect_key($options, array_flip($profileKeys));

        $options = array_diff_key($options, $profileOptions);

        if (empty($options['profile'])) {
            $options['profile'] = new \AccessWatch\Profile\BaseProfile($profileOptions);
        }

        parent::__construct($options);
    }

}
