<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Dto;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

class Customer implements ApiDtoInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string One of "m", "f" or "u"
     */
    protected $gender = 'u';

    /**
     * @var string[]
     */
    protected $user = [];

    /**
     * (optional) Customer group code
     *
     * @var string
     */
    protected $group;

    /**
     * (optional) Customers birthday
     *
     * @var string
     */
    protected $birthday;

    /**
     * @var string[]
     */
    protected $userAuthorizationRoles = ['ROLE_USER'];

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * Returns the unique identifier used for put operations
     * @return string
     */
    public function getIdentifier(): string
    {
        return (string)$this->id;
    }

    /**
     * @param string $userPlainPassword
     * @return Customer
     */
    public function setUserPlainPassword(string $userPlainPassword): Customer
    {
        $this->user['plainPassword'] = $userPlainPassword;
        return $this;
    }

    /**
     * @param bool $enabled
     * @return Customer
     */
    public function setUserEnabled(bool $enabled): Customer
    {
        $this->user['enabled'] = $enabled;
        return $this;
    }

    /**
     * @param string[] $userAuthorizationRoles
     * @return Customer
     */
    public function setUserAuthorizationRoles(array $userAuthorizationRoles): Customer
    {
        $this->userAuthorizationRoles = $userAuthorizationRoles;
        return $this;
    }

    /**
     * @param int $id
     * @return Customer
     */
    public function setId(int $id): Customer
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $firstName
     * @return Customer
     */
    public function setFirstName(string $firstName): Customer
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @param string $email
     * @return Customer
     */
    public function setEmail(string $email): Customer
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $gender
     * @return Customer
     */
    public function setGender(string $gender): Customer
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @param string $lastName
     * @return Customer
     */
    public function setLastName(string $lastName): Customer
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * @param string $group
     * @return Customer
     */
    public function setGroup(string $group): Customer
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return string
     */
    public function getBirthday(): string
    {
        return $this->birthday;
    }

    /**
     * @param string $birthday
     * @return Customer
     */
    public function setBirthday(string $birthday): Customer
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
