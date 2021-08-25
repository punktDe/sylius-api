<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Resource;

/*
 *  (c) 2021 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */


use PunktDe\Sylius\Api\Dto\Taxon;

class TaxonResource extends AbstractResource
{

    /**
     * @var string[]
     */
    protected $ignoredPropertiesOnSerialize = ['identifier', 'type'];

    /**
     * Returns the fields that should be sent when creating
     * a new entity.
     *
     * format:
     *  ['fieldName' => required]
     *
     * @return bool[]
     */
    protected function getPostFields(): array
    {
        return [
            'code' => true,
        ];
    }

    /**
     * Returns the fields that should be sent when updating
     * an entity.
     *
     * format:
     *  ['fieldName' => required]
     *
     * @return bool[]
     */
    protected function getPatchFields(): array
    {
        return [
            'code' => true,
        ];
    }

    /**
     * @return string
     */
    protected function getDtoClass(): string
    {
        return Taxon::class;
    }
}
