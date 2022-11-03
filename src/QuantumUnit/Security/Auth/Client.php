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
 * Time: 12:32 AM
 */

namespace QuantumUnit\Security\Auth;


class Client implements ClientInterface {

    protected $id;
    protected $password = null;
    protected $roles = array();
    protected $credentials = 'anonymous';
    protected $ipAddress = null;
    protected $status = null;
    protected $email = null;
    protected $firstname = null;
    protected $lastname = null;
    protected $emappings_id = null;

    /**
     *
     * @param array $params
     */
    public function __construct(array $params = array()) {

        foreach($params as $key => $value) {
            if(property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * accessor
     *
     * @param string $password
     */
    public function setPassword(string $password):void {
        $this->password = $password;
    }

    /**
     * accessor
     *
     * @param array $roles
     */
    public function setRoles(array $roles):void {
        $this->roles = $roles;
    }

    /**
     * accessor
     *
     * @param string $credentials
     */
    public function setCredentials(array $credentials):void {
        $this->credentials = $credentials;
    }

    /**
     * accessor
     *
     * @param string $ipAddress
     */
    public function setIpAddress(string $ipAddress):void {
        $this->ipAddress = $ipAddress;
    }

    /**
     * accessor
     *
     * @param string $email
     */
    public function setEmail(string $email):void {
        $this->email = $email;
    }

    /**
     * accessor
     *
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * accessor
     *
     * @return array
     */
    public function getRoles(): array {
        if (is_array($this->roles)) {
            return $this->roles;
        }

        return explode('|', $this->roles);
    }

    /**
     * accessor
     *
     * @return string
     */
    public function getCredentials(): string {
        return $this->credentials;
    }

    /**
     * accessor
     *
     * @return string
     */
    public function getIpAddress():string {
        return $this->ipAddress;
    }

    /**
     * accessor
     *
     * @return string
     */
    public function setStatus(string $status):void {
        $this->status = $status;
    }

    /**
     * accessor
     *
     * @return string
     */
    public function getStatus():string {
        return $this->status;
    }

    /**
     * accessor
     *
     * @return int
     */
    public function getId():string {
        return $this->id;
    }

    public function setId(string $id) {
        $this->id = $id;
        return $this;
    }
    /**
     * accessor
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    public function toArray(): array {
        $retval = array();
        foreach(get_object_vars($this) as $key => $property) {

            $retval[$key] = $property;
        }

        return $retval;
    }

    /**
     * @return null
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param null $firstname
     */
    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return null
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param null $lastname
     */
    public function setLastname(string $lastname):void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return null
     */
    public function getEmappingsId(): string
    {
        return $this->emappings_id;
    }

    /**
     * @param null $emappings_id
     */
    public function setEmappingsId(string $emappings_id): void
    {
        $this->emappings_id = $emappings_id;
    }

}