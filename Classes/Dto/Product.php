<?php
namespace PunktDe\Sylius\Api\Dto;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Flow\ResourceManagement\PersistentResource;
use PunktDe\Sylius\Api\Exception\SyliusApiException;
use PunktDe\Sylius\Api\Resource\ProductResource;
use PunktDe\Sylius\Api\Resource\ProductVariantResource;
use PunktDe\Sylius\Api\ResultCollection;
use PunktDe\Sylius\Api\Service\DefaultConfiguration;

class Product implements ApiDtoInterface, FileTransferringInterface
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string[]
     */
    protected $options = [];

    /**
     * @var string[][]
     */
    protected $translations = [];

    /**
     * @var string[]
     */
    protected $channels = [];

    /**
     * @var string[]
     */
    protected $mainTaxon = [];

    /**
     * @var bool
     */
    protected $enabled = true;

    /**
     * @var string[]
     */
    protected $imageTypes = [];

    /**
     * @var string[][]
     */
    protected $images = [];

    /**
     * @var PersistentResource[]
     */
    protected $uploadResources = [];

    /**
     * @var string
     */
    protected $defaultLocale = '';

    /**
     * Collection of products associated with the created product (for example accessories to this product)
     *
     *
     * @var string[]
     */
    protected $associations;

    public function __construct()
    {
        $defaultConfiguration = new DefaultConfiguration();
        $this->defaultLocale = $defaultConfiguration->getDefaultLocale();
    }

    /**
     * @param array $criteria
     * @param int $limit
     * @param array $sorting
     * @return ResultCollection
     * @throws SyliusApiException
     */
    public function getVariants(array $criteria = [], int $limit = 100, array $sorting = []): ResultCollection
    {
        return (new ProductVariantResource())->getAll($criteria, $limit, $sorting, $this->getIdentifier());
    }

    /**
     * @param string $associationType
     * @return ResultCollection
     * @throws SyliusApiException
     */
    public function getAssociatedProducts(string $associationType): ResultCollection
    {
        $productResource = new ProductResource();
        $associatedProducts = new ResultCollection();
        foreach ($this->getAssociations() as $association) {
            $associationCode = $association['type']['code'] ?? '';
            if ($associationCode === $associationType) {
                foreach ($association['associatedProducts'] as $associatedProductData) {
                    $product = $productResource->get($associatedProductData['code']);
                    if (!$product instanceof Product) {
                        throw new SyliusApiException(sprintf('No product with code "%s" was found while fetching associatedProducts', $associatedProductData['code']), 1584565022);
                    }
                    $associatedProducts->add($product);
                }
            }
        }

        return $associatedProducts;
    }

    /**
     * @return string
     */
    public function getDefaultLocale(): string
    {
        return $this->defaultLocale;
    }

    /**
     * @return string[]
     */
    public function getAssociations(): array
    {
        return $this->associations;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return string[]
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->code;
    }

    /**
     * @return string[]
     */
    public function getMainTaxon(): array
    {
        return $this->mainTaxon;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getMainTaxonName(string $locale = ''): string
    {
        $effectiveLocale = $locale ?: $this->defaultLocale;
        return $this->mainTaxon['translations'][$effectiveLocale]['name'] ?? '--mainTaxon not named in locale' . $effectiveLocale;
    }

    /**
     * @param string[] $mainTaxon
     * @return Product
     */
    public function setMainTaxon(array $mainTaxon): Product
    {
        $this->mainTaxon = $mainTaxon;
        return $this;
    }

    /**
     * @param string $slug
     * @param string $locale
     * @return Product
     */
    public function setSlug(string $slug, ?string $locale = null): Product
    {
        $this->translations[$locale ?: $this->defaultLocale]['slug'] = $slug;
        return $this;
    }

    /**
     * @param string|null $locale
     * @return string
     */
    public function getSlug(?string $locale = null): string
    {
        return $this->translations[$locale ?: $this->defaultLocale]['slug'] ?? '';
    }

    /**
     * @param string $name
     * @param string $locale
     * @return Product
     */
    public function setName(string $name, ?string $locale = null): Product
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
        return $this->translations[$effectiveLocale]['name'] ?? '--Product not named in locale' . $effectiveLocale;
    }

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setEntityName(string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getDescription(string $locale = ''): string
    {
        $effectiveLocale = $locale ?: $this->defaultLocale;
        return $this->translations[$effectiveLocale]['description'] ?? '--Product not named in locale' . $effectiveLocale;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getShortDescription(string $locale = ''): string
    {
        $effectiveLocale = $locale ?: $this->defaultLocale;
        return $this->translations[$effectiveLocale]['shortDescription'] ?? '--Product not named in locale' . $effectiveLocale;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return Product
     */
    public function setEnabled(bool $enabled): Product
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @param string $channel
     * @return Product
     */
    public function addChannel(string $channel): Product
    {
        $this->channels[] = $channel;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getChannels(): array
    {
        return $this->channels;
    }

    /**
     * @param PersistentResource $persistentResource
     * @param string $type
     * @return Product
     */
    public function addImage(PersistentResource $persistentResource, string $type): Product
    {
        $this->imageTypes[] = [
            'type' => $type
        ];
        $this->uploadResources[] = $persistentResource;

        return $this;
    }

    /**
     * @return PersistentResource[]
     */
    public function getUploadResources(): array
    {
        return $this->uploadResources;
    }

    /**
     * @return string[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @param string $type
     * @return string
     */
    public function getImagePathByType(string $type = 'main'): string
    {
        if ($this->images === []) {
            return '';
        }

        $defaultImagePath = '';
        $searchedImagePath = null;
        foreach (array_reverse($this->images) as $image) {
            $imagePath = $image['path'] ?? '';
            if ($imagePath === '') {
                continue;
            }

            $defaultImagePath = $imagePath;
            $imageType = $image['type'] ?? '';
            if ($type !== '' && $type === $imageType) {
                $searchedImagePath = $imagePath;
            }
        }

        return $searchedImagePath ?: $defaultImagePath;
    }

    /**
     * @param string $code
     * @return Product
     */
    public function setCode(string $code): Product
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param string[] $associations
     * @return Product
     */
    public function setAssociations(array $associations): Product
    {
        $this->associations = $associations;
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
