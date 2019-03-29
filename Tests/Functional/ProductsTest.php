<?php
namespace PunktDe\Sylius\Api\Tests\Functional;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */


use PunktDe\Sylius\Api\Dto\Product;
use PunktDe\Sylius\Api\Exception\SyliusApiException;

class ProductsTest extends AbstractSyliusApiTest
{

    /**
     * @test
     * @throws SyliusApiException
     */
    public function add()
    {
        $this->createProduct();
    }

    /**
     * @test
     */
    public function has()
    {
        $this->assertFalse($this->products->has($this->testProductCode));
        $this->createProduct();
        $this->assertTrue($this->products->has($this->testProductCode));
    }

    /**
     * @test
     */
    public function update()
    {
        $product = $this->createProduct();
        $this->assertEquals('Functional Test Product', $product->getName());

        $product->setMainTaxon('Buch')->setName('New Name');
        $this->products->update($product);

        $product = $this->products->get($this->testProductCode);
        $this->assertEquals('New Name', $product->getName());
    }

    /**
     * @test
     */
    public function delete()
    {
        $this->assertFalse($this->products->has($this->testProductCode));
        $this->createProduct();

        $this->assertTrue($this->products->has($this->testProductCode));
        $this->products->delete($this->testProductCode);
        $this->assertFalse($this->products->has($this->testProductCode));
    }
}
