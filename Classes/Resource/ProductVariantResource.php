<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Resource;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use GuzzleHttp\Promise\PromiseInterface;
use PunktDe\Sylius\Api\Dto\ApiDtoInterface;
use PunktDe\Sylius\Api\Dto\ProductVariant;
use PunktDe\Sylius\Api\Exception\SyliusApiException;
use PunktDe\Sylius\Api\ResultCollection;

class ProductVariantResource extends AbstractResource
{
    /**
     * @var string[]
     */
    protected $ignoredPropertiesOnSerialize = ['identifier', 'productCode'];


    /**
     * @param array $criteria
     * @param int $limit
     * @param array $sorting
     * @param string $parentResourceIdentifier
     * @return ResultCollection
     * @throws SyliusApiException
     */
    public function getAll(array $criteria = [], int $limit = 100, array $sorting = [], string $parentResourceIdentifier = ''): ResultCollection
    {
        if ($parentResourceIdentifier === '') {
            throw new SyliusApiException('The parentResourceIdentifier needs to be given to get all variants of a product', 1563788213);
        }

        return parent::getAll($criteria, $limit, $sorting, $parentResourceIdentifier);
    }

    /**
     * @param string $identifier
     * @param string $parentResourceIdentifier
     * @return mixed
     * @throws SyliusApiException
     */
    public function get(string $identifier, string $parentResourceIdentifier = ''): ?ApiDtoInterface
    {
        if ($parentResourceIdentifier === '') {
            throw new SyliusApiException('The parentResourceIdentifier needs to be given to get the variant of a product', 1585815953);
        }

        return parent::getAsync($identifier, $parentResourceIdentifier)->wait();
    }

    /**
     * @param ApiDtoInterface $dto
     * @param mixed[] $files
     * @return PromiseInterface
     * @throws SyliusApiException
     */
    protected function addAsync(ApiDtoInterface $dto, array $files = []): PromiseInterface
    {
        /** @var ProductVariant $dto */
        return $this->apiClient->postAsync($this->getResourceUri($dto->getProductCode()), $this->convertDtoToArray($dto, $this->getPostFields()), $files)
            ->then($this->responseToDto($dto));
    }

    /**
     * @param ApiDtoInterface $dto
     * @return PromiseInterface
     * @throws SyliusApiException
     */
    protected function updateAsync(ApiDtoInterface $dto): PromiseInterface
    {
        /** @var ProductVariant $dto */
        return $this->apiClient->patchAsync($this->getSingleEntityUri($dto->getIdentifier(), $dto->getProductCode()), $this->convertDtoToArray($dto, $this->getPatchFields()))
            ->then($this->responseSucceeded());
    }

    /**
     * @return string
     */
    protected function determineResourceName(): string
    {
        return 'variants';
    }

    /**
     * @return string
     */
    protected function determineParentResourceName(): string
    {
        return 'products';
    }

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
            'onHand' => false,
            'taxCategory' => false,
            'channelPricings' => false,
            'translations' => false,
            'tracked' => false,
            'shippingCategory' => false,
            'shippingRequired' => false,
            'width' => false,
            'height' => false,
            'depth' => false,
            'weight' => false,
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
        return $this->getPostFields();
    }

    /**
     * @return string
     */
    protected function getDtoClass(): string
    {
        return ProductVariant::class;
    }
}
