<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Resource;

/*
 *  (c) 2019 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use GuzzleHttp\Promise\PromiseInterface;
use Neos\Utility\Files;
use PunktDe\Sylius\Api\Dto\ApiDtoInterface;
use PunktDe\Sylius\Api\Dto\Country;
use PunktDe\Sylius\Api\Dto\Zone;
use PunktDe\Sylius\Api\Exception\SyliusApiException;

class ZoneResource extends AbstractResource
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
            'name' => true,
            'scope' => true,
            'members' => true
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
            'name' => false,
            'scope' => false,
            'members' => false
        ];
    }

    /**
     * @param ApiDtoInterface $dto
     * @param mixed[] $files
     * @return PromiseInterface
     * @throws SyliusApiException
     */
    protected function addAsync(ApiDtoInterface $dto, array $files = []): PromiseInterface
    {
        /** @var Zone $dto */
        return $this->apiClient->postAsync(Files::concatenatePaths([$this->getBaseUri(), $this->determineResourceName(), $dto->getType()]) . '/', $this->convertDtoToArray($dto, $this->getPostFields()), $files)
            ->then($this->responseToDto($dto));
    }

    /**
     * @return string
     */
    protected function getDtoClass(): string
    {
        return Zone::class;
    }
}
