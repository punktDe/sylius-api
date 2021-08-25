<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Auth;

/*
 *  (c) 2021 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Eljam\GuzzleJwt\Strategy\Auth\AuthStrategyInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JsonAuthStrategy implements AuthStrategyInterface
{
    /**
     * $options.
     *
     * @var array
     */
    protected $options;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    /**
     * configureOptions.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'email' => '',
            'password' => '',
            'json_fields' => ['_email', '_password'],
        ]);

        $resolver->setRequired(['email', 'password']);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestOptions()
    {
        return [
            \GuzzleHttp\RequestOptions::JSON => [
                $this->options['json_fields'][0] => $this->options['email'],
                $this->options['json_fields'][1] => $this->options['password'],
            ],
        ];
    }
}
