<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Dto;

/*
 *  (c) 2019 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

class Country implements ApiDtoInterface
{

    /**
     * @var string Id of the country
     */
    protected $id;

    /**
     * @var string Unique country identifier
     */
    protected $code;

    /**
     * @var bool Information says if the country is enabled (default: false)
     */
    protected $enabled;

    /**
     * @var mixed[] Collection of the country’s provinces
     */
    protected $provinces;

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
     * @return Country
     */
    public function setId(string $id): Country
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
     * @return Country
     */
    public function setCode(string $code): Country
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return Country
     */
    public function setEnabled(bool $enabled): Country
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return mixed[]
     */
    public function getProvinces(): array
    {
        return $this->provinces;
    }

    /**
     * @param mixed[] $provinces
     * @return Country
     */
    public function setProvinces(array $provinces): Country
    {
        $this->provinces = $provinces;
        return $this;
    }
}
