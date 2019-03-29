<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Tests\Functional;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use PunktDe\Sylius\Api\Dto\ProductVariant;
use PunktDe\Sylius\Api\Exception\SyliusApiException;

class ProductVariantsTest extends AbstractSyliusApiTest
{

    /**
     * @test
     * @throws SyliusApiException
     */
    public function add()
    {
        $this->createProduct();
        $productVariant = $this->createProductVariant();
        $this->assertInstanceOf(ProductVariant::class, $productVariant);
        $this->assertEquals($this->testProductVariantCode, $productVariant->getCode());
    }

    /**
     * @test
     * @throws SyliusApiException
     */
    public function has()
    {
        $this->createProduct();

        $this->assertFalse($this->productVariants->has($this->testProductVariantCode));
        $result = $this->createProductVariant();

        $this->assertInstanceOf(ProductVariant::class, $result);
        $this->assertTrue($this->productVariants->has($this->testProductVariantCode, $this->testProductCode));
    }

}
