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
     * @param string $parentResourceIdentifier
     * @return ApiDtoInterface|null
     * @throws SyliusApiException
     */
    public function get(string $identifier, string $parentResourceIdentifier = ''): ?ApiDtoInterface
    {
        throw new SyliusApiException('Get requests on a single item is not possible. Get the cart instead', 1542733038);
    }
    // phpcs:enable SlevomatCodingStandard.Functions.UnusedParameter

    /**
     * @param string $identifier
     * @param string $parentResourceIdentifier
     * @return bool
     * @throws SyliusApiException
     */
    public function delete(string $identifier, string $parentResourceIdentifier = ''): bool
    {
        if ($parentResourceIdentifier === '') {
            throw new SyliusApiException('The parentResourceIdentifier (Identifier of the cart) needs to be set', 1542899581);
        }
        return parent::delete($identifier, $parentResourceIdentifier);
    }

    /**
     * @return string
     */
    protected function determineResourceName(): string
    {
        return 'items';
    }

    /**
     * @return string
     */
    protected function determineParentResourceName(): string
    {
        return 'carts';
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
