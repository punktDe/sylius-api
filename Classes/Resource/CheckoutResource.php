<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Resource;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use PunktDe\Sylius\Api\Dto\ApiDtoInterface;
use PunktDe\Sylius\Api\Dto\Checkout;

class CheckoutResource extends AbstractResource
{

    /**
     * Returns the fields that should be sent when creating
     * a new entity.
     *
     * format:
     *  ['fieldName' => required]
     *
     * @return bool[]
     */
    protected function getPostFields(): array
    {
        return [
            'differentBillingAddress' => true,
            'shippingAddress[firstName]' => true,
            'shippingAddress[lastName]' => true,
            'shippingAddress[city]' => true,
            'shippingAddress[postcode]' => true,
            'shippingAddress[street]' => true,
            'shippingAddress[country]' => true,
            'notes' => true,
            'payments[method]' => true,
            'shipments[method]' => true
        ];
    }

    protected function getPutFields(): array
    {
        return $this->getPostFields();
    }

    /**
     * Returns the fields that should be sent when updating
     * an entity.
     *
     * format:
     *  ['fieldName' => required]
     *
     * @return bool[]
     */
    protected function getPatchFields(): array
    {
        return $this->getPostFields();
    }

    function addAsync(ApiDtoInterface $dto): PromiseInterface
    {
        $id = $dto->getIdentifier();
        $shipmentArray = $dto->getShippingAddressData();
        $shipmentMethodArray = $dto->getShippingMethodData();
        $paymentArray = $dto->getPaymentData();
        $noteArray = $dto->getNoteData();

        return $this->apiClient->putAsync($this->getResourceUri() . 'addressing/' . $id, $shipmentArray)->then(
            function () use ($id, $shipmentMethodArray) {
                return $this->apiClient->putAsync($this->getResourceUri() . 'select-shipping/' . $id, $shipmentMethodArray);
            },
            function($reject) {
                $promise = new Promise(function () use (&$promise, $reject) {
                    $promise->reject($reject->getReason());
                });
                return $promise;
            })
            ->then(
                function () use ($id, $paymentArray) {
                    return $this->apiClient->putAsync($this->getResourceUri() . 'select-payment/' . $id, $paymentArray);
                },
                function($reject) {
                    $promise = new Promise(function () use (&$promise, $reject) {
                        $promise->reject($reject->getReason());
                    });
                    return $promise;
                }
            )
            ->then(
                function () use ($id, $noteArray) {
                    return $this->apiClient->putAsync($this->getResourceUri() . 'complete/' . $id, $noteArray);
                },
                function($reject) {
                    $promise = new Promise(function () use (&$promise, $reject) {
                        $promise->reject($reject->getReason());
                    });
                    return $promise;
                }
            )
            ->then(
                function() use ($id) {
                    $promise = new Promise(function () use (&$promise, $id) {
                        $promise->resolve((string)$id);
                    });
                    return $promise;
                },
                function($reject) {
                    $promise = new Promise(function () use (&$promise, $reject) {
                        $promise->reject($reject->getReason());
                    });
                    return $promise;
                }
            );
    }

    /**
     * @return string
     */
    protected function getDtoClass(): string
    {
        return Checkout::class;
    }
}

