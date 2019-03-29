<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Service;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class DefaultConfiguration
{
    /**
     * @Flow\InjectConfiguration(path="defaults.locale")
     * @var string
     */
    protected $defaultLocale = '';

    /**
     * @Flow\InjectConfiguration(path="defaults.channel")
     * @var string
     */
    protected $defaultChannel = '';

    /**
     * @return string
     */
    public function getDefaultLocale(): string
    {
        return $this->defaultLocale;
    }

    /**
     * @return string
     */
    public function getDefaultChannel(): string
    {
        return $this->defaultChannel;
    }
}
