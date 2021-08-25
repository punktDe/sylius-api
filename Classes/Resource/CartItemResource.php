<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Resource;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use GuzzleHttp\Promise\PromiseInterface;
use PunktDe\Sylius\Api\Dto\ApiDtoInterface;
use PunktDe\Sylius\Api\Dto\CartItem;
use PunktDe\Sylius\Api\Exception\SyliusApiException;

class CartItemResource extends AbstractResource
{

    /**
     * @param ApiDtoInterface $dto
     * @param mixed[] $files
     * @return PromiseInterface
     * @throws SyliusApiException
     */
    protected function addAsync(ApiDtoInterface $dto, array $files = []): PromiseInterface
    {
        /** @var CartItem $dto */
        return $this->apiClient->postAsync($this->getResourceUri($dto->getCartIdentifier()), $this->convertDtoToArray($dto, $this->getPostFields()), $files)
            ->then($this->responseToDto($dto));
    }

    /**
     * @param ApiDtoInterface $dto
     * @return PromiseInterface
     * @throws SyliusApiException
     */
    protected function updateAsync(ApiDtoInterface $dto): PromiseInterface
    {
        /** @var CartItem $dto */
        return $this->apiClient->patchAsync($this->getSingleEntityUri($dto->getIdentifier(), $dto->getCartIdentifier()), $this->convertDtoToArray($dto, $this->getPatchFields()))
            ->then($this->responseSucceeded());
    }

    // phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter
    /**
     * @param string $identifier
     * @return ApiDtoInterface|null
     * @throws SyliusApiException
     */
    public function get(string $identifier): ?ApiDtoInterface
    {
        throw new SyliusApiException('Get requests on a single item is not possible. Get the cart instead', 1542733038);
    }

    /**
     * @param string $identifier
     * @return bool
     * @throws SyliusApiException
     */
    public function delete(string $identifier): bool
    {
        return parent::delete($identifier);
    }

    /**
     * @return string
     */
    protected function determineResourceName(): string
    {
        return 'items';
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
            'variant' => true,
            'quantity' => true
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
            'quantity' => true
        ];
    }

    /**
     * @return string
     */
    protected function getDtoClass(): string
    {
        return CartItem::class;
    }
}
