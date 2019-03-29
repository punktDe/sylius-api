<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Dto;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Flow\Utility\Algorithms;
use PunktDe\Sylius\Api\Service\DefaultConfiguration;

class Cart implements ApiDtoInterface
{

    /**
     * @var int
     */
    protected $id;

    /**
     * Email of the customer when created
     * The response the contains the full customer information
     *
     * @var mixed
     */
    protected $customer;

    /**
     * @var string
     */
    protected $channel;

    /**
     * @var string
     */
    protected $localeCode;

    /**
     * List of items in the cart
     *
     * @var string[]
     */
    protected $items = [];

    /**
     * Sum of all items prices
     *
     * @var int
     */
    protected $itemsTotal;

    /**
     * List of adjustments related to the cart
     *
     * @var string[]
     */
    protected $adjustments;

    /**
     * Sum of all cart adjustments values
     *
     * @var int
     */
    protected $adjustmentsTotal;

    /**
     * Sum of items total and adjustments total
     *
     * @var int
     */
    protected $total;

    /**
     * Currency of the cart
     *
     * @var string
     */
    protected $currencyCode;

    /**
     * Locale of the cart
     *
     * @var string
     */
    protected $localCode;

    /**
     * State of the checkout process of the cart
     *
     * @var string
     */
    protected $checkoutState;

    /**
     * @var string
     */
    protected $tokenValue = '';

    /**
     * @var string
     */
    protected $defaultLocale = '';

    /**
     * @var string
     */
    protected $defaultChannel = '';


    public function __construct()
    {
        $defaultConfiguration = new DefaultConfiguration();
        $this->defaultLocale = $defaultConfiguration->getDefaultLocale();
        $this->defaultChannel = $defaultConfiguration->getDefaultChannel();
        $this->localeCode = $this->defaultLocale;
        $this->channel = $this->defaultChannel;

        $this->tokenValue = Algorithms::generateUUID();
    }

    /**
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param string $customer
     * @return Cart
     */
    public function setCustomer(string $customer): Cart
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @return string
     */
    public function getLocaleCode(): string
    {
        return $this->localeCode;
    }

    /**
     * @param int $id
     * @return Cart
     */
    public function setIdentifier(int $id): Cart
    {
        $this->id = $id;
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
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param string[] $items
     * @return Cart
     */
    public function setItems(array $items): Cart
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @return int
     */
    public function getItemsTotal(): int
    {
        return $this->itemsTotal;
    }

    /**
     * @param int $itemsTotal
     * @return Cart
     */
    public function setItemsTotal(int $itemsTotal): Cart
    {
        $this->itemsTotal = $itemsTotal;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getAdjustments(): array
    {
        return $this->adjustments;
    }

    /**
     * @param string[] $adjustments
     * @return Cart
     */
    public function setAdjustments(array $adjustments): Cart
    {
        $this->adjustments = $adjustments;
        return $this;
    }

    /**
     * @return int
     */
    public function getAdjustmentsTotal(): int
    {
        return $this->adjustmentsTotal;
    }

    /**
     * @param int $adjustmentsTotal
     * @return Cart
     */
    public function setAdjustmentsTotal(int $adjustmentsTotal): Cart
    {
        $this->adjustmentsTotal = $adjustmentsTotal;
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
     * @return Cart
     */
    public function setTotal(int $total): Cart
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     * @return Cart
     */
    public function setCurrencyCode(string $currencyCode): Cart
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocalCode(): string
    {
        return $this->localCode;
    }

    /**
     * @param string $localCode
     * @return Cart
     */
    public function setLocalCode(string $localCode): Cart
    {
        $this->localCode = $localCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutState(): string
    {
        return $this->checkoutState;
    }

    /**
     * @param string $checkoutState
     * @return Cart
     */
    public function setCheckoutState(string $checkoutState): Cart
    {
        $this->checkoutState = $checkoutState;
        return $this;
    }

    /**
     * @param string $channel
     * @return Cart
     */
    public function setChannel(string $channel): Cart
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * @param string $localeCode
     * @return Cart
     */
    public function setLocaleCode(string $localeCode): Cart
    {
        $this->localeCode = $localeCode;
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
     * @return string
     */
    public function getTokenValue(): string
    {
        return $this->tokenValue;
    }

    /**
     * @param string $tokenValue
     * @return Cart
     */
    public function setTokenValue(string $tokenValue): Cart
    {
        $this->tokenValue = $tokenValue;
        return $this;
    }
}
