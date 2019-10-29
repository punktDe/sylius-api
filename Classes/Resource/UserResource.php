<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Resource;

/*
 *  (c) 2019 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use PunktDe\Sylius\Api\Dto\User;

class UserResource extends AbstractResource
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
            'username' => true,
            'email' => true,
            'plainPassword' => true,
            'localeCode' => true
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
        return $this->getPatchFields();
    }

    /**
     * @return string
     */
    protected function getDtoClass(): string
    {
        return User::class;
    }
}
