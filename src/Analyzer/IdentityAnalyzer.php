<?php

/*
 * This file is part of the Access Watch package.
 *
 * (c) François Hodierne <francois@access.watch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AccessWatch\Analyzer;

/**
 * Analyze Identity with the Access Watch Cloud Service
 *
 * @author François Hodierne <francois@access.watch>
 */
class IdentityAnalyzer
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

    /*
     *
     * @param object $identity
     *
     * @return object
     */
    public function identityAnalyzer($identity)
    {
        $parameters = array(
            'address' => $identity->getAddress()->getValue(),
            'headers' => $identity->getHeaders(),
        );

        $session = $identity->getSession();
        if ($session) {
            $parameters['session'] = $session->getId();
        }

        $result = $this->getApiClient()->session($parameters);

        if (isset($result['identity']) && is_array($result['identity'])) {
            $identity->setAttributes($result['identity']);
        }

        if (isset($result['session']) && is_array($result['session'])) {
            $identity->setAttribute('session', $result['session']);
        }

        return $identity;
    }

}
