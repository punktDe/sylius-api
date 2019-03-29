<?php
namespace PunktDe\Sylius\Api\Tests\Unit;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Flow\Tests\UnitTestCase;
use PunktDe\Sylius\Api\Resource\ProductResource;
use PunktDe\Sylius\Api\Resource\ProductVariantResource;

class ProductVariantsTest extends UnitTestCase
{
    /**
     * @var ProductResource
     */
    protected $products;

    public function setUp() {
        $this->products = $this->getMockBuilder(ProductVariantResource::class)->setMethods(['getBaseUri'])->getMock();
        $this->products->expects($this->any())->method('getBaseUri')->will($this->returnValue('https://shop.punkt.de/api/v1'));
    }

    /**
     * @test
     */
    public function getResourceUri()
    {
        $this->assertRegExp('/https:\/\/shop.punkt.de\/api\/v1\/products\/productCode\/variants.*\//', $this->products->getResourceUri('productCode'));
    }

    /**
     * @test
     */
    public function getSingleEntityUri()
    {
        $this->assertRegExp('/https:\/\/shop.punkt.de\/api\/v1\/products\/productCode\/variants\/identity/', $this->products->getSingleEntityUri('identity', 'productCode'));
    }
}
