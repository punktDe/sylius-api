<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Service;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use PunktDe\Sylius\Api\Dto\Cart;
use PunktDe\Sylius\Api\Dto\CartItem;

class CartManagementService
{

    /**
     * @param Cart $cart
     * @return CartItem[]
     */
    public function getCartItems(Cart $cart): array
    {
        $cartItems = $cart->getItems();
        $cartItemsBySlug = [];

        /** @var string $productVariant */

        foreach ($cartItems as $cartItem) {
            $productVariant = $cartItem['variant']['code'] ?? null;

            if ($productVariant === null) {
                continue;
            }

            $cartItemsBySlug[$productVariant] = (new CartItem())
                ->setId($cartItem['id'] ?? 0)
                ->setCartId($cart->getId())
                ->setQuantity($cartItem['quantity'] ?? 0)
                ->setVariant($productVariant)
                ->setCartId($cart->getId())
                ->setTotal($cartItem['total'] ?? 0)
                ->setUnitPrice($cartItem['unitPrice'] ?? 0)
                ->setUnitsTotal($cartItem['unitsTotal'] ?? 0)
                ->setRawDataFromApi($cartItem);
        }

        return $cartItemsBySlug;
    }
}
