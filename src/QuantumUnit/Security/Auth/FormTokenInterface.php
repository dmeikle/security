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
 * Date: 2/3/2018
 * Time: 9:27 PM
 */

namespace QuantumUnit\Security\Auth;


/**
 * FormTokenInterface
 *
 * @author Dave Meikle
 */
interface FormTokenInterface
{
    public function setIPAddress($ipAddress): void;

    public function setTokenString($token): void;

    public function toString(): string;

    public function getTimestamp(): string;

    public function setCredentials(array $credentials): void;

    public function setClientId(string $id): void;

    public function generateTokenString(): string;

    public function getTokenString(): string;
} 