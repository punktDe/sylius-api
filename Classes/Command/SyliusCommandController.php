<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Command;

/*
 *  (c) 2019 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\ObjectManagement\ObjectManager;
use Neos\Flow\Reflection\ReflectionService;
use PunktDe\Sylius\Api\Client;
use PunktDe\Sylius\Api\Exception\SyliusApiConfigurationException;
use PunktDe\Sylius\Api\Resource\AbstractResource;
use PunktDe\Sylius\Api\Resource\UserResource;
use PunktDe\Sylius\Api\Resource\ProductResource;

class SyliusCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @Flow\Inject
     * @var ReflectionService
     */
    protected $reflectionService;

    public function testCommand()
    {
        $this->outputLine('<b>Testing Sylius Admin API</b>');

        try {
            $this->output(str_pad('Testing if the API is properly configured ... ', 50, ' '));
            $client = $this->objectManager->get(Client::class);
            $this->outputLine('<success>OK</success>');
        } catch (SyliusApiConfigurationException $exception) {
            $this->outputLine('<failed>FAILED</failed> (' . $exception->getMessage() . ')');
        }

        $resourceClasses = $this->reflectionService->getAllSubClassNamesForClass(AbstractResource::class);

        foreach ($resourceClasses as $resourceClass) {

            $arrayParts = explode('\\', $resourceClass);
            $resourceName = array_pop($arrayParts);

            try {
                $message = sprintf('Testing %s API ... ', $resourceName);
                $this->output(str_pad($message, 50, ' '));
                $adminUser = $this->objectManager->get($resourceClass)->getAll();
                $this->outputLine('<success>OK</success> Found %s items', [$adminUser->count()]);
            } catch (\Exception $exception) {
                $this->outputLine('<error>FAILED</error> (' . str_replace("\n", '', $exception->getMessage()) . ')');
            }
        }
    }
}
