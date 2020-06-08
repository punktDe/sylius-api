<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Dto;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use PunktDe\Sylius\Api\Service\DefaultConfiguration;

class ProductVariant implements ApiDtoInterface
{
    /**
     * @var string
     */
    protected $productCode;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var int
     */
    protected $onHand = 0;

    /**
     * @var string[]
     */
    protected $optionValues = [];

    /**
     * @var string
     */
    protected $taxCategory = '';

    /**
     * @var int[][]
     */
    protected $channelPricings = [];

    /**
     * @var string
     */
    protected $defaultChannel = '';

    /**
     * @var string
     */
    protected $defaultLocale = '';

    /**
     * Name of the product variant
     *
     * @var string[][]
     */
    protected $translations;

    /**
     * The information if the variant is tracked by inventory (true or false)
     *
     * @var bool
     */
    protected $tracked = true;

    /**
     * Code of object which provides information about shipping category to which variant is assigned
     *
     * @var string
     */
    protected $shippingCategory = '';

    /**
     * @var bool
     */
    protected $shippingRequired = false;

    /**
     * The physical weight of variant
     *
     * @var int
     */
    protected $weight = 0;

    /**
     * The physical width of variant
     *
     * @var int
     */
    protected $width = 0;

    /**
     * The physical height of variant
     *
     * @var int
     */
    protected $height = 0;

    /**
     * The physical depth of variant
     *
     * @var int
     */
    protected $depth = 0;

    /**
     * ProductVariant constructor.
     */
    public function __construct()
    {
        $defaultConfiguration = new DefaultConfiguration();
        $this->defaultChannel = $defaultConfiguration->getDefaultChannel();
        $this->defaultLocale = $defaultConfiguration->getDefaultLocale();
    }

    /**
     * @return string[][]
     */
    public function getChannelPricings(): array
    {
        return $this->channelPricings;
    }

    /**
     * @param null|string $channel
     * @return int
     */
    public function getPrice(?string $channel = null): int
    {
        return (int)($this->channelPricings[$channel ?? $this->defaultChannel]['price'] ?? 0);
    }

    /**
     * @param string $price
     * @param null|string $channel
     * @return ProductVariant
     */
    public function setPrice(string $price, ?string $channel = null): ProductVariant
    {
        $this->channelPricings[$channel ?? $this->defaultChannel]['price'] = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductCode(): string
    {
        return $this->productCode;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getOnHand(): int
    {
        return $this->onHand;
    }

    /**
     * @return string[]
     */
    public function getOptionValues(): array
    {
        return $this->optionValues;
    }

    /**
     * @param int $onHand
     * @return ProductVariant
     */
    public function setOnHand(int $onHand): ProductVariant
    {
        $this->onHand = $onHand;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaxCategory(): string
    {
        return $this->taxCategory;
    }

    /**
     * @param string $taxCategory
     * @return ProductVariant
     */
    public function setTaxCategory(string $taxCategory): ProductVariant
    {
        $this->taxCategory = $taxCategory;
        return $this;
    }

    /**
     * @param string $productCode
     * @return ProductVariant
     */
    public function setProductCode(string $productCode): ProductVariant
    {
        $this->productCode = $productCode;
        return $this;
    }

    /**
     * @param string $code
     * @return ProductVariant
     */
    public function setCode(string $code): ProductVariant
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * @param string $name
     * @param string $locale
     * @return Product
     */
    public function setName(string $name, ?string $locale = null): ProductVariant
    {
        $locale = $locale ?: $this->defaultLocale;
        $this->resetTranslation($locale);
        $this->translations[$locale]['name'] = $name;
        return $this;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getName(string $locale = ''): string
    {
        $effectiveLocale = $locale ?: $this->defaultLocale;
        return $this->translations[$effectiveLocale]['name'] ?? $this->getCode();
    }

    /**
     * @return bool
     */
    public function isTracked(): bool
    {
        return $this->tracked;
    }

    /**
     * @param bool $tracked
     * @return ProductVariant
     */
    public function setTracked(bool $tracked): ProductVariant
    {
        $this->tracked = $tracked;
        return $this;
    }

    /**
     * @return string
     */
    public function getShippingCategory(): string
    {
        return $this->shippingCategory;
    }

    /**
     * @param string $shippingCategory
     * @return ProductVariant
     */
    public function setShippingCategory(string $shippingCategory): ProductVariant
    {
        $this->shippingCategory = $shippingCategory;
        return $this;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     * @return ProductVariant
     */
    public function setWeight(int $weight): ProductVariant
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return ProductVariant
     */
    public function setWidth(int $width): ProductVariant
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return ProductVariant
     */
    public function setHeight(int $height): ProductVariant
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * @param int $depth
     * @return ProductVariant
     */
    public function setDepth(int $depth): ProductVariant
    {
        $this->depth = $depth;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShippingRequired(): bool
    {
        return $this->shippingRequired;
    }

    /**
     * @param bool $shippingRequired
     * @return ProductVariant
     */
    public function setShippingRequired(bool $shippingRequired): ProductVariant
    {
        $this->shippingRequired = $shippingRequired;
        return $this;
    }

    /**
     * @param string $locale
     */
    private function resetTranslation(string $locale): void
    {
        if (isset($this->translations[$locale]['locale'])) {
            unset($this->translations[$locale]['locale']);
        }
        if (isset($this->translations[$locale]['id'])) {
            unset($this->translations[$locale]['id']);
        }
    }
}
