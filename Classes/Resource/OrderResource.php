<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Resource;

/*
 *  (c) 2020 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use PunktDe\Sylius\Api\Dto\Order;

class OrderResource extends AbstractResource
{

    /**
     * @return array
     */
    protected function getPostFields(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getPatchFields(): array
    {
        return [];
    }

    /**
     * @return string
     */
    protected function getDtoClass(): string
    {
        return Order::class;
    }
}
