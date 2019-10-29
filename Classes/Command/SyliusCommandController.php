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
use PunktDe\Sylius\Api\Client;
use PunktDe\Sylius\Api\Exception\SyliusApiConfigurationException;
use PunktDe\Sylius\Api\Resource\AdminUserResource;
use PunktDe\Sylius\Api\Resource\ProductResource;

class SyliusCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     * @Flow\InjectConfiguration(path="apiUser")
     */
    protected $apiUserName;

    public function testCommand()
    {
        $this->outputLine('<b>Testing Sylius Admin API</b>');

        try {
            $this->output('Testing if the API is properly configured ... ');
            $client = $this->objectManager->get(Client::class);
            $this->outputLine('<success>OK</success>');
        } catch (SyliusApiConfigurationException $exception) {
            $this->outputLine('<failed>FAILED</failed> (' . $exception->getMessage() . ')');
        }

        try {
            $this->output('Testing Login and API user ... ');
            $adminUser = $this->objectManager->get(AdminUserResource::class)->get($this->apiUserName);
            $this->outputLine('Found User %s <success>OK</success>', [$adminUser->getIdentifier()]);
        } catch (\Exception $exception) {
            $this->outputLine('<error>FAILED</error> (' . str_replace("\n", '', $exception->getMessage()) . ')');
        }

//        $this->output('Testing Products API ... ');
//        $productCollection = $this->productResource->getAll();
//        $this->outputLine('found ' . (string)$productCollection->count() . ' products. <green>OK</green>');
    }

}
