<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Tests\Functional;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use PunktDe\Sylius\Api\Dto\CartItem;
use PunktDe\Sylius\Api\Dto\CartItemResponse;
use PunktDe\Sylius\Api\Dto\Cart;
use PunktDe\Sylius\Api\Dto\CartResponse;
use PunktDe\Sylius\Api\Dto\Customer;
use PunktDe\Sylius\Api\Exception\SyliusApiException;
use PunktDe\Sylius\Api\Resource\CartItemResource;
use PunktDe\Sylius\Api\Resource\CartResource;
use PunktDe\Sylius\Api\Resource\CustomerResource;
use PunktDe\Sylius\Api\ResultCollection;

class CartsTest extends AbstractSyliusApiTest
{

    /**
     * @var CartResource
     */
    protected $cartResource;

    /**
     * @var CartItemResource
     */
    protected $cartItems;

    /**
     * @var CustomerResource
     */
    protected $customers;

    /**
     * @var string
     */
    protected $customerEmail = 'sylius-api@test.punkt.de';

    /**
     * @var string
     */
    protected $secondCustomerEmail = 'additionalTestUser@test.punkt.de';

    /**
     * @var string
     */
    protected $customerIdentifier;

    /**
     * @var string
     */
    protected $secondCustomerIdentifier;

    /**
     * @var string[]
     */
    protected $generatedCartIdsToCleanup = [];

    /**
     * @throws SyliusApiException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->customers = $this->objectManager->get(CustomerResource::class);
        $this->inject($this->customers, 'logger', $this->logger);

        $this->cartResource = $this->objectManager->get(CartResource::class);
        $this->inject($this->cartResource, 'logger', $this->logger);

        $this->cartItems = $this->objectManager->get(CartItemResource::class);
        $this->inject($this->cartItems, 'logger', $this->logger);

        $customer = (new Customer())
            ->setEmail($this->customerEmail)
            ->setUserPlainPassword('password')
            ->setFirstName('Api')
            ->setLastName('Test');
        $customer = $this->customers->add($customer);
        $this->customerIdentifier = (string)($customer->getIdentifier() ?? '8');

        $secondCustomer = (new Customer())
            ->setEmail($this->secondCustomerEmail)
            ->setUserPlainPassword('password')
            ->setFirstName('Api2')
            ->setLastName('Test2');
        $secondCustomer = $this->customers->add($secondCustomer);
        $this->secondCustomerIdentifier = (string)($secondCustomer->getIdentifier() ?? '8');
    }

    public function tearDown(): void
    {
        if ($this->customerIdentifier !== '') {
            $this->customers->delete($this->customerIdentifier);
        }

        if ($this->secondCustomerIdentifier !== '') {
            $this->customers->delete($this->secondCustomerIdentifier);
        }

        foreach ($this->generatedCartIdsToCleanup as $generatedCartId) {
            $this->cartResource->delete((string)$generatedCartId);
        }

        parent::tearDown();
    }

    /**
     * @test
     * @throws SyliusApiException
     */
    public function createCart(): void
    {
        $cart = $this->addCart($this->customerEmail);
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertGreaterThan(0, $cart->getId());
    }

    /**
     * @test
     * @throws SyliusApiException
     */
    public function createCartWithExtendedFields(): void
    {
        $cart = (new Cart())
            ->setCustomer($this->customerEmail);

        $cart->setTokenValue('tvtest');

        $fetchedCart = $this->cartResource->add($cart);
        $this->generatedCartIdsToCleanup[] = $fetchedCart->getId();

        /** @var Cart $cart */

        $this->assertInstanceOf(Cart::class, $fetchedCart);
        $this->assertGreaterThan(0, $fetchedCart->getId());
        $this->assertEquals('tvtest', $fetchedCart->getTokenValue());
    }

    /**
     * @test
     */
    public function twoCartsCanBeCreated(): void
    {
        $response = $this->addCart($this->customerEmail);
        $this->assertInstanceOf(Cart::class, $response);
        $this->assertGreaterThan(0, $response->getIdentifier());
        $id1 = $response->getIdentifier();

        $response = $this->addCart($this->customerEmail);
        $this->assertInstanceOf(Cart::class, $response);
        $this->assertGreaterThan(0, $response->getIdentifier());
        $id2 = $response->getIdentifier();

        $this->assertNotEquals($id1, $id2);
    }

    /**
     * @test
     */
    public function itemCanBeAddedUpdatedAndDeleted()
    {
        $product = $this->createProduct();
        $variant = $this->createProductVariant();
        $cart = $this->addCart($this->customerEmail);

        $this->assertInstanceOf(Cart::class, $cart);

        /*
         * Add an item
         */

        $cartItem = (new CartItem)
            ->setQuantity(5)
            ->setVariant($variant->getCode())
            ->setCartId($cart->getId());

        /** @var CartItem $cartItem */
        $cartItem = $this->cartItems->add($cartItem);
        $this->assertInstanceOf(CartItem::class, $cartItem);
        $this->assertEquals(5, $cartItem->getQuantity());

        /*
         * Update an item
         */

        $cartItem->setQuantity(10);
        $updateSucceeded = $this->cartItems->update($cartItem);

        $this->assertTrue($updateSucceeded);

        /** @var Cart $updatedCart */
        $updatedCart = $this->cartResource->get($cart->getIdentifier());

        $this->assertTrue(isset($updatedCart->getItems()[0]['quantity']));
        $this->assertEquals(10, $updatedCart->getItems()[0]['quantity']);

        /*
         * Delete an item
         */

        $this->cartItems->delete((string)$cartItem->getId(), $cart->getIdentifier());
        /** @var Cart $deletedItemCartResponse */
        $deletedItemCartResponse = $this->cartResource->get($cart->getIdentifier());
        $this->assertCount(0, $deletedItemCartResponse->getItems());
    }

    /**
     * @test
     */
    public function addItemTwiceAddsTwoEntries()
    {
        $product = $this->createProduct();
        $variant = $this->createProductVariant();
        $cart = $this->addCart($this->customerEmail);

        $cartItem = (new CartItem)
            ->setQuantity(1)
            ->setVariant($variant->getCode())
            ->setCartId($cart->getId());
        $this->cartItems->add($cartItem);

        $secondCartItem = (new CartItem)
            ->setQuantity(1)
            ->setVariant($variant->getCode())
            ->setCartId($cart->getId());
        $this->cartItems->add($secondCartItem);

        $updatedCart = $this->cartResource->get($cart->getIdentifier());

        $this->assertTrue(isset($updatedCart->getItems()[0]['quantity']));
        $this->assertEquals(1, $updatedCart->getItems()[0]['quantity']);

        $this->assertTrue(isset($updatedCart->getItems()[1]['quantity']));
        $this->assertEquals(1, $updatedCart->getItems()[1]['quantity']);
    }

    /**
     * @test
     * @throws SyliusApiException
     */
    public function getAllCartsWithoutParameter()
    {
        $cart1 = $this->addCart($this->customerEmail);
        $cart2 = $this->addCart($this->secondCustomerEmail);

        $cartResponse = $this->cartResource->getAll();
        $this->assertInstanceOf(ResultCollection::class, $cartResponse);
        $this->assertGreaterThanOrEqual(2, $cartResponse->count());
    }

    /**
     * @test
     * @throws SyliusApiException
     */
    public function getAllCartsWithLimit()
    {
        $cart1 = $this->addCart($this->customerEmail);
        $cart2 = $this->addCart($this->secondCustomerEmail);

        $cartResponse = $this->cartResource->getAll([], 1);
        $this->assertInstanceOf(ResultCollection::class, $cartResponse);
        $this->assertCount(1, $cartResponse);
    }

    /**
     * @test
     * @throws SyliusApiException
     */
    public function getFilteredCarts()
    {
        $cart1 = $this->addCart($this->customerEmail);
        $cart2 = $this->addCart($this->secondCustomerEmail);

        $cartResponse = $this->cartResource->getAll([
            'customer' => [
                'searchOption' => 'equal',
                'searchPhrase' => $this->customerEmail
            ]
        ]);

        $this->assertInstanceOf(ResultCollection::class, $cartResponse);
        $this->assertCount(1, $cartResponse);

        /** @var Cart $cart */
        $cart = $cartResponse->current();
        $this->assertInstanceOf(Cart::class, $cart);
        $customer = $cart->getCustomer();
        $this->assertEquals($this->customerEmail, $customer['email']);
    }

    /**
     * @param string $customerMail
     * @return Cart
     * @throws SyliusApiException
     */
    protected function addCart(string $customerMail): Cart
    {
        $cart = (new Cart())
            ->setCustomer($customerMail);
        $cart = $this->cartResource->add($cart);

        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertGreaterThan(0, $cart->getId());

        $this->generatedCartIdsToCleanup[] = $cart->getId();

        return $cart;
    }
}
