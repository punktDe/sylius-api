<?php
namespace PunktDe\Sylius\Api\Tests\Unit;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Flow\Tests\UnitTestCase;
use PunktDe\Sylius\Api\Resource\AbstractResource;
use PunktDe\Sylius\Api\Resource\ProductResource;

class ProductsTest extends UnitTestCase
{
    /**
     * @var ProductResource
     */
    protected $products;

    public function setUp(): void
    {
        $this->products = $this->getMockBuilder(ProductResource::class)->setMethods(['getBaseUri'])->getMock();
        $this->products->expects($this->any())->method('getBaseUri')->will($this->returnValue('https://shop.punkt.de/api/v1'));
    }

    /**
     * @test
     */
    public function getResourceUri()
    {
        $this->assertRegExp('/https:\/\/shop.punkt.de\/api\/v1\/mock_product_.*\//', $this->products->getResourceUri());
    }

    /**
     * @test
     */
    public function getSingleEntityUri()
    {
        $this->assertRegExp('/https:\/\/shop.punkt.de\/api\/v1\/mock_product.*\/identity/', $this->products->getSingleEntityUri('identity'));
    }
}
