<?php
namespace PunktDe\Sylius\Api\Dto;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Flow\ResourceManagement\PersistentResource;

interface FileTransferringInterface
{
    /**
     * @return PersistentResource[]
     */
    public function getUploadResources(): array;
}
