<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Dto;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

interface ApiDtoInterface
{
    /**
     * Returns the unique identifier used for put operations
     * @return string
     */
    public function getIdentifier(): string;
}
