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
 * Time: 8:45 PM
 */

namespace QuantumUnit\Security\Auth;


/**
 * TokenInterface
 *
 * @author Dave Meikle
 */
interface TokenInterface {

    public function toString(): string;

    public function getRoles(): array;

    public function getClient(): Client;

    public function setClient(ClientInterface $client): void;

    public function getIdentity(): array;

    public function isAuthenticated(): bool;

    public function setAuthenticated(bool $isAuthenticated): void;

    public function setAttribute(string $name, mixed $value): void;

    public function getAttributes(): array;

    public function setAttributes(array $attributes): void;

    public function eraseCredentials(): void;
}
