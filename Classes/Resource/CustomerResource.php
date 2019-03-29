<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Resource;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use PunktDe\Sylius\Api\Dto\Customer;

class CustomerResource extends AbstractResource
{
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
            'firstName' => true,
            'lastName' => true,
            'email' => true,
            'user' => true,
            'gender' => true,
            'group' => false,
            'birthday' => false,
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
            'firstName' => false,
            'lastName' => false,
            'email' => false,
            'gender' => false,
            'group' => false,
            'birthday' => false,
            'user' => false,
        ];
    }

    /**
     * @return string
     */
    protected function getDtoClass(): string
    {
        return Customer::class;
    }
}
