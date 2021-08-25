<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Dto;

/*
 *  (c) 2019 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

class Taxon implements ApiDtoInterface
{
    /**
     * @var string Id of the taxon
     */
    protected $id;

    /**
     * @var string Unique taxon identifier
     */
    protected $code;

    /**
     * @var string Name of the taxon
     */
    protected $name;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Taxon
     */
    public function setId(string $id): Taxon
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
     * @return Taxon
     */
    public function setCode(string $code): Taxon
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
     * @return Taxon
     */
    public function setName(string $name): Taxon
    {
        $this->name = $name;
        return $this;
    }

    public function getIdentifier(): string
    {
        return $this->code;
    }
}
