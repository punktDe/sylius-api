# Sylius Shop Admin API Client for the Flow Framework

[![Latest Stable Version](https://poser.pugx.org/punktde/sylius-api/v/stable)](https://packagist.org/packages/punktde/sylius-api) [![Total Downloads](https://poser.pugx.org/punktde/sylius-api/downloads)](https://packagist.org/packages/punktde/sylius-api)

This [Flow](https://flow.neos.io) package provides a programmable interface to the admin part of the [Sylius Shop](https://sylius.com/) unified [API](https://master.demo.sylius.com/api/v2/docs).

Version compatibility:

|PunktDe.Sylius.Api| Sylius API                |
|------------------|---------------------------|
| 1.x              | Admin API of Sylius < 1.9 |
| 2.x              | API Platform unified API  |

## Implemented Endpoints

The following Endpoints are currently implemented, see the [admin API documentation](https://master.demo.sylius.com/api/v2/docs) for details:

* Cart
* CartItem
* Checkout
* Country
* Customer
* Product
* ProductVariant
* Order
* User
* Zone


# Setup

## Installation

The installation is done with composer:

	composer require punktde/sylius-api
	
## Configuration

* Create a new API user in Sylius.
* Configure URL and client credentials in your settings.

# Usage Examples

#### Find a single product by its identifier

	/**
     * @Flow\Inject
     * @var PunktDe\Sylius\Api\Resource\ProductResource
     */
    protected $products;

    /**
     * @param string $identifier
     * @return PunktDe\Sylius\Api\Dto\Product
     */
    private function findOneProductByIdentifier(string $identifier): PunktDe\Sylius\Api\Dto\Product {
        $this->products->get($identifier);
    }
    
#### Find an existing cart of the current logged in user

    /**
     * @Flow\Inject
     * @var PunktDe\Sylius\Api\Resource\CartResource
     */
    protected $cartResource;

    /**
     * @return Cart|null
     */
    private function retrieveExistingCartByCustomerMail(): ?PunktDe\Sylius\Api\Dto\Cart
    {
        $cartCollection = $this->getCartResource()->getAll([
            'customer' => [
                'searchOption' => 'equal',
                'searchPhrase' => $this->getLoggedInUserEmail()
            ]
        ]);
        
        return current($cartCollection);
     }
