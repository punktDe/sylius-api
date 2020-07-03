<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Dto;

/*
 *  (c) 2020 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

class Order implements ApiDtoInterface
{
    /**
     * Id of the order
     * @var int
     */
    protected $id;

    /**
     * List of items related to the order
     * @var array
     */
    protected $items = [];

    /**
     * Sum of all items prices
     * @var int
     */
    protected $itemsTotal;

    /**
     * List of adjustments related to the order
     * @var array
     */
    protected $adjustments = [];

    /**
     * Sum of all order adjustments
     * @var int
     */
    protected $adjustmentsTotal;

    /**
     * Sum of items total and adjustments total
     * @var int
     */
    protected $total;

    /**
     * Customer detailed serialization for order
     * @var customer
     */
    protected $customer;

    /**
     * Default channel serialization
     * @var mixed
     */
    protected $channel;

    /**
     * Currency of the order
     * @var mixed
     */
    protected $currencyCode;

    /**
     * State of the checkout process
     * @var mixed
     */
    protected $checkoutState;

    /**
     * State of the order
     * @var mixed
     */
    protected $state;

    /**
     * Date when the checkout has been completed
     * @var mixed
     */
    protected $checkoutCompletedAt;

    /**
     * Serial number of the order
     * @var mixed
     */
    protected $number;

    /**
     * Detailed address serialization
     * @var mixed
     */
    protected $shippingAddress;

    /**
     * Detailed address serialization
     * @var mixed
     */
    protected $billingAddress;

    /**
     * Detailed serialization of all related shipments
     * @var mixed
     */
    protected $shipments;

    /**
     * Detailed serialization of all related payments
     * @var mixed
     */
    protected $payments;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getItemsTotal(): int
    {
        return $this->itemsTotal;
    }

    /**
     * @return array
     */
    public function getAdjustments(): array
    {
        return $this->adjustments;
    }

    /**
     * @return int
     */
    public function getAdjustmentsTotal(): int
    {
        return $this->adjustmentsTotal;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return customer
     */
    public function getCustomer(): customer
    {
        return $this->customer;
    }

    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return mixed
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * @return mixed
     */
    public function getCheckoutState()
    {
        return $this->checkoutState;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return mixed
     */
    public function getCheckoutCompletedAt()
    {
        return $this->checkoutCompletedAt;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return mixed
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * @return mixed
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @return mixed
     */
    public function getShipments()
    {
        return $this->shipments;
    }

    /**
     * @return mixed
     */
    public function getPayments()
    {
        return $this->payments;
    }

    public function getIdentifier(): string
    {
        return (string)$this->id;
    }
}
