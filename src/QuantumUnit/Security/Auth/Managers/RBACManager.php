<?php

/**
 * Elentra ME [https://elentra.org]
 *
 * Copyright 2019 Queen's University or its assignee ("Queen's"). All Rights Reserved.
 *
 * This work is subject to Community Licenses ("CL(s)") between Queen's and its various licensee's,
 * respectively, and may only be viewed, accessed, used, reproduced, compiled, modified, copied or
 * exploited (together "Used") in accordance with a CL. Only Queen's or its licensees and their
 * respective Authorized Developers may Use this work in accordance with a CL. If you are not an
 * Authorized Developer, please contact Queen's University (at cla@elentra.org) or its applicable
 * licensee to review the rights and obligations under the applicable CL and become an Authorized
 * Developer before Using this work.
 *
 * @author Organization: Elentra Corp
 * @author Developer: Dave Meikle <dave.meikle@elentra.com>
 * @copyright Copyright 2022 Elentra Corporation. All Rights Reserved.
 */

namespace QuantumUnit\Security\Auth\Managers;


use QuantumUnit\Http\Traits\ClientIPAddressTrait;
use QuantumUnit\Http\HttpInterface;
use QuantumUnit\Security\Auth\Authorization\Voters\VoterInterface;
use QuantumUnit\Security\Auth\Client;
use QuantumUnit\Security\Auth\ClientInterface;
use QuantumUnit\Security\Auth\Exceptions\UnauthorizedAccessException;
use QuantumUnit\Security\Auth\Roles\Role;
use QuantumUnit\Security\Auth\SecurityToken;

/**
 * RBACManager
 *
 * @author Organization: Elentra Corp
 * @author Developer: David Meikle <david.meikle@elentra.com>
 */
abstract class RBACManager
{
    use ClientIPAddressTrait;

    /**
     * @param $request
     * @param array $permissionsConfig
     * @return void
     * @throws UnauthorizedAccessException
     */
    public function execute(HttpInterface $request, array $permissionsConfig)
    {
        $roles = $this->buildRoles($permissionsConfig['roles']);
        $token = $this->generateNewToken($this->getClient($request));

        foreach($permissionsConfig['rules'] as $rule ) {
            $voterName = $rule['class'];
            $voter = new $voterName($rule, $request);

            if($voter->vote($token, $this->getSubject($request, $permissionsConfig['subject']), $roles) == VoterInterface::ACCESS_DENIED) {
                throw new UnauthorizedAccessException();
            }
        }
    }

    /**
     * @param array $roles
     * @return array
     */
    private function buildRoles(array $roles): array {
        $retval = array();

        foreach($roles as $roleName) {
            $retval[] = new Role($roleName);
        }

        return $retval;
    }

    /**
     * generates a default token
     *
     * @return SecurityToken
     */
    public function generateEmptyToken($request) {

        $token = $request->getProperty('Client');

        if (!$token) {
            return $this->generateNewToken($request);
        }

        return $token;
    }

    /**
     * generates a new token based on current client.
     *
     * can pass an optional client in, in case we just logged in and need to update the token
     * with new client details
     *
     * @param Client|null $client
     * @return SecurityToken
     */
    public function generateNewToken($request, Client $client = null) {

        if (is_null($client)) {
            $client = $this->getClient($request);
        }

        $token = new SecurityToken($client, $request->getProperty('ymlKey'), $client->getRoles());

        return $token;
    }

    /**
     * @param $request - mixed
     * @return ClientInterface
     */
    protected function getClient(HttpInterface $request): ClientInterface {
        $token = $request->getAttribute('token');
        $client = null;
        if (is_null($token) || !$token) {
            $client = new Client();
            $client->setIpAddress($this->getClientIPAddress());
            $client->setCredentials('ANONYMOUS_USER');
            $client->setRoles(['ANONYMOUS_USER']);
        } else {
            $client = $token->getClient();
        }

        return $client;
    }

    /**
     * @param $request
     * @param array $subject
     * @return mixed|null
     */
    protected function getSubject(HttpInterface $request, array $subject) {
        if($subject['method'] == 'query') {
            return $request->getRequestParams()->getQuerystringParameter($subject['param']);
        }
        if($subject['method'] == 'uri') {
            return $request->getRequestParams()->getUriParameter($subject['param']);
        }
        if($subject['method'] == 'post') {
            return $request->getRequestParams()->getPostParameter($subject['param']);
        }

        return null;
    }
}