<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Dto;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

class CartItem implements ApiDtoInterface
{

    /**
     * Id of the cart item
     *
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $cartId = 0;

    /**
     * The product variant object serialized with the default data
     *
     * @var string
     */
    protected $variant = '';

    /**
     * Quantity of item units
     *
     * @var int
     */
    protected $quantity;

    /**
     * Price of each item unit
     *
     * @var int
     */
    protected $unitPrice;

    /**
     * Sum of units total and adjustments total of that cart item
     *
     * @var int
     */
    protected $total;

    /**
     * Sum of all units prices of the cart item
     *
     * @var int
     */
    protected $unitsTotal;

    /**
     * @var array
     */
    protected $rawDataFromApi;

    /**
     * @return array
     */
    public function getRawDataFromApi(): array
    {
        return $this->rawDataFromApi;
    }

    /**
     * @param array $rawDataFromApi
     * @return CartItem
     */
    public function setRawDataFromApi(array $rawDataFromApi): CartItem
    {
        $this->rawDataFromApi = $rawDataFromApi;
        return $this;
    }

    /**
     * Returns the unique identifier used for put operations
     * @return string
     */
    public function getIdentifier(): string
    {
        return (string)$this->id;
    }

    /**
     * @return string
     */
    public function getCartIdentifier(): string
    {
        return (string)$this->cartId;
    }

    /**
     * @return string
     */
    public function getVariant(): string
    {
        return $this->variant;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $id
     * @return CartItem
     */
    public function setId(int $id): CartItem
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param int $cartId
     * @return CartItem
     */
    public function setCartId(int $cartId): CartItem
    {
        $this->cartId = $cartId;
        return $this;
    }

    /**
     * @param int $quantity
     * @return CartItem
     */
    public function setQuantity(int $quantity): CartItem
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @param string $variant
     * @return CartItem
     */
    public function setVariant(string $variant): CartItem
    {
        $this->variant = $variant;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUnitPrice(): int
    {
        return $this->unitPrice;
    }

    /**
     * @param int $unitPrice
     * @return CartItem
     */
    public function setUnitPrice(int $unitPrice): CartItem
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     * @return CartItem
     */
    public function setTotal(int $total): CartItem
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return int
     */
    public function getUnitsTotal(): int
    {
        return $this->unitsTotal;
    }

    /**
     * @param int $unitsTotal
     * @return CartItem
     */
    public function setUnitsTotal(int $unitsTotal): CartItem
    {
        $this->unitsTotal = $unitsTotal;
        return $this;
    }
}
