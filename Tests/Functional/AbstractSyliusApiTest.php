<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Tests\Functional;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Flow\Tests\FunctionalTestCase;
use Psr\Log\LoggerInterface;
use PunktDe\Sylius\Api\Dto\Product;
use PunktDe\Sylius\Api\Dto\ProductVariant;
use PunktDe\Sylius\Api\Resource\ProductResource;
use PunktDe\Sylius\Api\Resource\ProductVariantResource;

class AbstractSyliusApiTest extends FunctionalTestCase
{

    /**
     * @var ProductResource
     */
    protected $products;

    /**
     * @var ProductResource
     */
    protected $productVariants;

    /**
     * @var string
     */
    protected $testProductCode = 'functionalTestProduct';

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $testProductVariantCode = 'functionalTestProductVariant';

    public function setUp(): void
    {
        parent::setUp();

        $this->logger = $this->objectManager->get(LoggerInterface::class);

        $this->products = $this->objectManager->get(ProductResource::class);
        $this->inject($this->products, 'logger', $this->logger);

        $this->productVariants = $this->objectManager->get(ProductVariantResource::class);
        $this->inject($this->productVariants, 'logger', $this->logger);
    }

    public function tearDown(): void
    {

        try {
            $this->productVariants->delete($this->testProductVariantCode, $this->testProductCode);
        } catch (\Exception $e) {
        }

        try {
            $this->products->delete($this->testProductCode);
        } catch (\Exception $e) {
        }

        parent::tearDown();
    }

    /**
     * PhpUnit should not complain about an empty test class
     *
     * @test
     */
    public function fakeTest(): void
    {
        self::assertTrue(true);
    }

    /**
     * @return Product
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    protected function createProduct(): ?Product
    {
        try {
            $product = (new Product())
                ->setCode($this->testProductCode)
                ->setName('Functional Test Product')
                ->setSlug('functional-test-product-slug')
                ->setMainTaxon('Buch');

            $product = $this->products->add($product);

            self::assertInstanceOf(Product::class, $product);
            self::assertEquals($this->testProductCode, $product->getIdentifier());
            return $product;
        } catch (\Exception $exception) {
            self::fail($exception->getMessage());
        }
    }

    /**
     * @return ProductVariant
     */
    protected function createProductVariant(): ?ProductVariant
    {
        try {
            $productVariant = (new ProductVariant())
                ->setProductCode($this->testProductCode)
                ->setCode($this->testProductVariantCode)
                ->setPrice('12')
                ->setOnHand(30)
                ->setWeight(100)
                ->setWidth(10)
                ->setHeight(20)
                ->setDepth(30)
                ->setShippingRequired(true);

            return $this->productVariants->add($productVariant);
        } catch (\Exception $exception) {
            self::fail($exception->getMessage());
        }
    }
}
