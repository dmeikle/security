<?php
/*
 *  This file is part of the Quantum Unit Solutions development package.
 *
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/9/2017
 * Time: 8:50 PM
 */

namespace QuantumUnit\Security\Auth\Providers;
use QuantumUnit\Security\Auth\ClientInterface;


/**
 * AuthenticationProviderInterface
 *
 * @author Dave Meikle
 */
interface AuthenticationProviderInterface {

    public function loadClientByCredentials(array $credentials);

    public function refreshClient(ClientInterface $client);

    public function supportsClass(string $class);

    public function getRoles(ClientInterface $client);
}
