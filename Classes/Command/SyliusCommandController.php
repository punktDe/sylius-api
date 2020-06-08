<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Command;

/*
 *  (c) 2019 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Exception;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\Configuration\Exception\InvalidConfigurationTypeException;
use Neos\Flow\ObjectManagement\Exception\CannotBuildObjectException;
use Neos\Flow\ObjectManagement\Exception\UnknownObjectException;
use Neos\Flow\ObjectManagement\ObjectManager;
use Neos\Flow\Reflection\ReflectionService;
use PunktDe\Sylius\Api\Client;
use PunktDe\Sylius\Api\Exception\SyliusApiConfigurationException;
use PunktDe\Sylius\Api\Resource\AbstractResource;
use PunktDe\Sylius\Api\Resource\ResourceInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

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

    /**
     * Automatically call a getAll on all resources and display the count
     *
     * @throws InvalidConfigurationTypeException
     * @throws CannotBuildObjectException
     * @throws UnknownObjectException
     */
    public function testCommand(): void
    {
        $this->outputLine('<b>Testing Sylius Admin API</b>');

        try {
            $this->output(str_pad('Testing if the API is properly configured ... ', 50, ' '));
            $client = $this->objectManager->get(Client::class);
            $this->outputLine('<success>OK</success> %s', [$client->getBaseUri()]);
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
            } catch (Exception $exception) {
                $this->outputLine('<error>FAILED</error> (' . str_replace("\n", '', $exception->getMessage()) . ')');
            }
        }
    }

    /**
     * Shows the raw result of a given resourceType and identifier
     *
     * @param string $resourceType
     * @param string $identifier
     * @throws InvalidConfigurationTypeException
     * @throws CannotBuildObjectException
     * @throws UnknownObjectException
     * @throws ExceptionInterface
     */
    public function showCommand(string $resourceType, string $identifier): void
    {
        $resourceClassName = str_replace('Abstract', ucfirst($resourceType), AbstractResource::class);
        if (!class_exists($resourceClassName)) {
            $this->outputLine('No Resource with name %s was found', [ucfirst($resourceType)]);
            $this->sendAndExit(1);
        }

        /** @var ResourceInterface $resource */
        $resource = $this->objectManager->get($resourceClassName);
        $object = $resource->get($identifier);

        $serializer = new Serializer([new PropertyNormalizer()], [new JsonEncoder()]);
        $this->output(print_r($serializer->normalize($object), true));
    }
}
