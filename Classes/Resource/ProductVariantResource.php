<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Resource;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use GuzzleHttp\Promise\PromiseInterface;
use PunktDe\Sylius\Api\Dto\ApiDtoInterface;
use PunktDe\Sylius\Api\Dto\Product;
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
     * @param Product $product
     * @param array $criteria
     * @param int $limit
     * @param array $sorting
     * @return ResultCollection
     */
    public function getAllByProduct(Product $product, array $criteria = [], int $limit = 100, array $sorting = []):ResultCollection
    {
        return parent::getAll($criteria, $limit, $sorting, $product->getIdentifier());
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
