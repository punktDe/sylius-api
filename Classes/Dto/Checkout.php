<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Dto;

/*
 *  (c) 2019 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

class Checkout implements ApiDtoInterface
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var array
     */
    protected $shippingAddress = [
        'firstName' => '',
        'lastName' => '',
        'street' => '',
        'countryCode' => '',
        'city' => '',
        'postcode' => '',
    ];

    /**
     * @var bool
     */
    protected $differentBillingAddress = false;

    /**
     * @var array
     */
    protected $shipments = [
        'method' => 'no-shipment'
    ];

    /**
     * @var array
     */
    protected $payments = [
            'method' => 'bill'
    ];

    /**
     * @var string
     */
    protected $notes = '';


    /**
     * @param string $identifier
     * @return Checkout
     */
    public function setCartIdentifier(string $identifier): Checkout
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $city
     * @param string $postcode
     * @param string $street
     * @param string $country
     * @return Checkout
     */
    public function setShippingAddress(string $firstName, string $lastName, string $city, string $postcode, string $street, string $country): Checkout
    {
        $this->shippingAddress = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'street' => $street,
            'countryCode' => $country,
            'city' => $city,
            'postcode' => $postcode,
        ];
        return $this;
    }


    /**
     * @param string $shipmentMethod
     */
    public function setShipmentMethod(string $shipmentMethod)
    {
        $this->shipments = [
            'method' => $shipmentMethod
        ];
    }

    /**
     * @param string $paymentMethod
     */
    public function setPaymentMethod(string $paymentMethod)
    {
        $this->payments = [
            'method' => $paymentMethod
        ];
    }

    /**
     * @param string $notes
     * @return Checkout
     */
    public function setNotes(string $notes): Checkout
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * Returns the cart identifier used for put operations
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return array
     */
    public function getShippingAddress(): array
    {
        return $this->shippingAddress;
    }

    /**
     * @return array
     */
    public function getShippingAddressData(): array
    {
        return [
            'shippingAddress' => $this->getShippingAddress(),
            'differentBillingAddress' => false
        ];
    }

    /**
     * @return array
     */
    public function getShippingMethodData(): array
    {
        return [
            'shipments' => array($this->shipments)
        ];
    }

    /**
     * @return array
     */
    public function getPaymentData(): array
    {
        return [
            'payments' => array($this->payments)
        ];
    }

    /**
     * @return array
     */
    public function getNoteData(): array
    {
        return [
            'notes' => $this->notes
        ];
    }
}
