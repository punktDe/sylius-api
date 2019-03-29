<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Dto;

/*
 *  (c) 2019 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

class Zone implements ApiDtoInterface
{
    /**
     * @var string Id of the zoneprotected $
     */
    protected $id;

    /**
     * @var string Unique zone identifier
     */
    protected $code;

    /**
     * @var string Name of the zone
     */
    protected $name;

    /**
     * @var string Type of the zone
     */
    protected $type;

    /**
     * @var string Scope of the zone
     */
    protected $scope;

    /**
     * @var mixed[] Members of the zone
     */
    protected $members;

    /**
     * Returns the unique identifier used for put operations
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Zone
     */
    public function setId(string $id): Zone
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Zone
     */
    public function setCode(string $code): Zone
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Zone
     */
    public function setName(string $name): Zone
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Zone
     */
    public function setType(string $type): Zone
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     * @return Zone
     */
    public function setScope(string $scope): Zone
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * @return mixed[]
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param mixed[] $members
     * @return Zone
     */
    public function setMembers(array $members): Zone
    {
        $this->members = $members;
        return $this;
    }
}
