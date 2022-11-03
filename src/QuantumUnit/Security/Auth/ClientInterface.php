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
 * Time: 12:33 AM
 */

namespace QuantumUnit\Security\Auth;


interface ClientInterface
{

    public function setPassword(string $password):void;

    public function setRoles(array $roles):void;

    public function setCredentials(array $credentials):void;

    public function setIpAddress(string $ipAddress):void;

    public function getPassword(): string;

    public function getRoles(): array;

    public function getCredentials(): string;

    public function getIpAddress(): string;

    public function setStatus(string $status):void;

    public function getStatus(): string;

    public function getId(): string;

}