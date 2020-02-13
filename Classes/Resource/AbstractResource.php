<?php
namespace PunktDe\Sylius\Api\Resource;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Flow\Annotations as Flow;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Promise\PromiseInterface;
use Neos\Flow\Log\PsrSystemLoggerInterface;
use Neos\Flow\ResourceManagement\Exception as ResourceManagementException;
use Neos\Utility\Files;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LogLevel;
use Neos\Flow\Log\Utility\LogEnvironment;
use PunktDe\Sylius\Api\Client;
use PunktDe\Sylius\Api\Dto\ApiDtoInterface;
use PunktDe\Sylius\Api\Dto\FileTransferringInterface;
use PunktDe\Sylius\Api\Exception\SyliusApiException;
use PunktDe\Sylius\Api\ResultCollection;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractResource
{
    /**
     * @Flow\Inject
     * @var Client
     */
    protected $apiClient;

    /**
     * @var JsonDecode
     */
    protected $jsonDecoder;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var string[]
     */
    protected $ignoredPropertiesOnSerialize = ['identifier'];

    /**
     * @Flow\Inject
     * @var PsrSystemLoggerInterface
     */
    protected $logger;

    public function __construct()
    {
        $this->jsonDecoder = new JsonDecode(true);
        $this->serializer = new Serializer([new PropertyNormalizer()], [new JsonEncoder()]);
    }

    /**
     * @param ApiDtoInterface $dto
     * @return ApiDtoInterface
     * @throws SyliusApiException
     */
    public function add(ApiDtoInterface $dto): ?ApiDtoInterface
    {
        return $this->addAsync($dto)->wait();
    }

    /**
     * @param ApiDtoInterface $dto
     * @return PromiseInterface
     * @throws SyliusApiException
     */
    protected function addAsync(ApiDtoInterface $dto): PromiseInterface
    {
        return $this->apiClient->postAsync($this->getResourceUri(), $this->convertDtoToArray($dto, $this->getPostFields()), $this->getFileTransferArray($dto))
            ->then($this->responseToDto($dto));
    }

    /**
     * @param ApiDtoInterface $dto
     * @return bool
     * @throws SyliusApiException
     */
    public function update(ApiDtoInterface $dto): bool
    {
        return $this->updateAsync($dto)->wait();
    }

    /**
     * @param ApiDtoInterface $dto
     * @return PromiseInterface
     * @throws SyliusApiException
     */
    protected function updateAsync(ApiDtoInterface $dto): PromiseInterface
    {
//        if ($dto instanceof FileTransferringInterface && count($dto->getUploadResources()) > 0) {
//            return $this->apiClient->putAsync(
//                $this->getSingleEntityUri($dto->getIdentifier()),
//                $this->convertDtoToArray($dto, $this->getPatchFields()),
//                $this->getFileTransferArray($dto)
//            )
//                ->then($this->responseSucceeded());
//        }

        return $this->apiClient->patchAsync($this->getSingleEntityUri($dto->getIdentifier()), $this->convertDtoToArray($dto, $this->getPatchFields()))
            ->then($this->responseSucceeded());
    }

    /**
     * @param string $identifier
     * @param string $parentResourceIdentifier
     * @return bool
     */
    public function has(string $identifier, string $parentResourceIdentifier = ''): bool
    {
        try {
            $result = $this->apiClient->getAsync($this->getSingleEntityUri($identifier, $parentResourceIdentifier))->then($this->responseSucceeded(LogLevel::DEBUG))->wait();
        } catch (ClientException $exception) {
            return false;
        }

        return $result;
    }

    /**
     * @param string $identifier
     * @param string $parentResourceIdentifier
     * @return mixed
     */
    public function get(string $identifier, string $parentResourceIdentifier = ''): ?ApiDtoInterface
    {
        return $this->getAsync($identifier, $parentResourceIdentifier)->wait();
    }

    /**
     * @param string $identifier
     * @param string $parentResourceIdentifier
     * @return PromiseInterface
     */
    protected function getAsync(string $identifier, string $parentResourceIdentifier = ''): PromiseInterface
    {
        return $this->apiClient->getAsync($this->getSingleEntityUri($identifier, $parentResourceIdentifier))->then($this->responseToDto());
    }

    /**
     * @param array $criteria an array of criterion like:
     *
     * [
     *   <fieldName> => [
     *      'searchOption' => <option of type “contains”, “equal”>
     *      'searchPhrase' => <the search phrase>
     *   ]
     * ]
     *
     * @param int $limit
     * @param string[] $sorting an array of sorting configurations like:
     * [
     *   <fieldName> => <direction>
     * ]
     *
     * @param string $parentResourceIdentifier
     * @return ResultCollection
     */
    public function getAll(array $criteria = [], int $limit = 100, array $sorting = [], string $parentResourceIdentifier = ''): ResultCollection
    {
        return $this->getAllAsync($criteria, $limit, $sorting, $parentResourceIdentifier)->wait();
    }

    /**
     * @param string[] $criteria
     * @param int $limit
     * @param string[] $sorting
     * @param string $parentResourceIdentifier
     * @return PromiseInterface
     */
    protected function getAllAsync(array $criteria, int $limit, array $sorting, string $parentResourceIdentifier = ''): PromiseInterface
    {
        $queryParameters = [];
        $queryParameters['limit'] = (string)$limit;

        foreach ($sorting as $fieldName => $direction) {
            $queryParameters['sorting'][$fieldName] = $direction;
        }

        foreach ($criteria as $fieldName => $criterion) {
            $queryParameters['criteria'][$fieldName] = [
                'type' => $criterion['searchOption'],
                'value' => $criterion['searchPhrase'],
            ];
        }

        return $this->apiClient->getAsync($this->getResourceUri($parentResourceIdentifier), $queryParameters)->then($this->responseToCollection());
    }

    /**
     * @param string $identifier
     * @param string $parentResourceIdentifier
     * @return bool
     */
    public function delete(string $identifier, string $parentResourceIdentifier = ''): bool
    {
        return $this->deleteAsync($identifier, $parentResourceIdentifier)->wait();
    }

    /**
     * @param string $identifier
     * @param string $parentResourceIdentifier
     * @return PromiseInterface
     */
    protected function deleteAsync(string $identifier, string $parentResourceIdentifier = ''): PromiseInterface
    {
        return $this->apiClient->deleteAsync($this->getSingleEntityUri($identifier, $parentResourceIdentifier))->then($this->responseSucceeded());
    }

    /**
     * @param string $identifier
     * @param string $parentResourceIdentifier
     * @return string
     */
    public function getSingleEntityUri(string $identifier, string $parentResourceIdentifier = ''): string
    {
        if ($parentResourceIdentifier !== '' && $this->determineParentResourceName() !== '') {
            return Files::concatenatePaths([
                $this->getBaseUri(),
                $this->determineParentResourceName(), $parentResourceIdentifier,
                $this->determineResourceName(), $identifier
            ]);
        } else {
            return Files::concatenatePaths([$this->getBaseUri(), $this->determineResourceName(), $identifier]);
        }
    }

    /**
     * @param string $parentResourceIdentifier
     * @return string
     */
    public function getResourceUri(string $parentResourceIdentifier = ''): string
    {
        if ($parentResourceIdentifier !== '' && $this->determineParentResourceName() !== '') {
            return Files::concatenatePaths([
                    $this->getBaseUri(),
                    $this->determineParentResourceName(), $parentResourceIdentifier,
                    $this->determineResourceName()
                ]) . '/';
        } else {
            return Files::concatenatePaths([$this->getBaseUri(), $this->determineResourceName()]) . '/';
        }
    }

    /**
     * @return string
     */
    protected function determineResourceName(): string
    {
        $classParts = explode('\\', get_class($this));
        return str_replace('resource', '', strtolower(array_pop($classParts))) . 's';
    }

    /**
     * @return string
     */
    protected function determineParentResourceName(): string
    {
        return '';
    }

    /**
     * @return string
     */
    protected function getBaseUri(): string
    {
        return $this->apiClient->getConfig('base_uri');
    }

    /**
     * @param ApiDtoInterface $dto
     * @param array $propertyDefinition
     * @return mixed[]
     * @throws SyliusApiException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    protected function convertDtoToArray(ApiDtoInterface $dto, array $propertyDefinition): array
    {
        $data = $this->serializer->normalize($dto, null, ['attributes' => array_keys($propertyDefinition)]);

        /*
         * Manual filtering should not be needed as the normalizer only should select given attributes
         * Seems not to work though.
         */
        $filteredData = [];

        foreach ($propertyDefinition as $propertyName => $required) {
            if ($required === true && (!isset($data[$propertyName]) || empty($data[$propertyName]))) {
                throw new SyliusApiException(sprintf('The property "%s" is marked as required but was not set', $propertyName), 1542953647);
            }
            $filteredData[$propertyName] = $data[$propertyName];
        }

        return $filteredData;
    }

    /**
     * @return \Closure
     */
    protected function responseToArray(): \Closure
    {
        return function (ResponseInterface $response) {
            return $this->jsonDecoder->decode((string)$response->getBody(), JsonEncoder::FORMAT);
        };
    }

    /**
     * @return \Closure
     */
    protected function responseToCollection(): \Closure
    {
        return function (ResponseInterface $response): ResultCollection {
            $responseArray = $this->jsonDecoder->decode((string)$response->getBody(), JsonEncoder::FORMAT);

            $resultCollection = new ResultCollection();
            $resultCollection->setCurrentPage($responseArray['page'] ?? 0);
            $resultCollection->setPagesTotal($responseArray['pages'] ?? 0);
            $resultCollection->setElementsTotal($responseArray['itemsTotal'] ?? 0);

            if (!isset($responseArray['_embedded']['items']) || !is_array($responseArray['_embedded']['items'])) {
                return $resultCollection;
            }

            foreach ($responseArray['_embedded']['items'] as $itemData) {

                $resultCollection->add($this->serializer->denormalize($itemData, $this->getDtoClass()));
            }

            return $resultCollection;
        };
    }

    /**
     * @param ApiDtoInterface $requestDto
     * @return \Closure
     */
    protected function responseToDto(?ApiDtoInterface $requestDto = null): \Closure
    {
        return function (ResponseInterface $response) use ($requestDto) {
            if ($response->getStatusCode() < 200 || $response->getStatusCode() > 300) {

                $debugData = [];

                if ($requestDto instanceof ApiDtoInterface) {
                    $debugData['request'] = $this->convertDtoToArray($requestDto, $this->getPostFields());
                }

                $this->logger->warning(sprintf('Sylius API Request for %s did not succeed. Status: %s Message %s', get_class($this), $response->getStatusCode(), $response->getBody()->getContents()), array_merge($debugData, LogEnvironment::fromMethodName(__METHOD__)));
                return null;
            }
            return $this->serializer->deserialize((string)$response->getBody(), $this->getDtoClass(), 'json');
        };
    }

    /**
     * @param string $logLevel
     * @return \Closure
     */
    protected function responseSucceeded($logLevel = LogLevel::WARNING): \Closure
    {
        return function (ResponseInterface $response) use ($logLevel) {
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() <= 300) {
                return true;
            }

            $errorMessage = \json_decode($response->getBody()->getContents(), true)['message'] ?? '';
            $this->logger->log($logLevel, sprintf('Sylius API Request for %s did not succeed. Status: %s Message %s', get_class($this), $response->getStatusCode(), $errorMessage), LogEnvironment::fromMethodName(__METHOD__));
            return false;
        };
    }

    /**
     * @param ApiDtoInterface $dto
     * @return mixed[]
     */
    protected function getFileTransferArray(ApiDtoInterface $dto): array
    {
        if (!$dto instanceof FileTransferringInterface) {
            return [];
        }

        $fileTransferArray = [];
        $fileCounter = 0;
        try {
            foreach ($dto->getUploadResources() as $persistentResource) {
                $formFilePath = sprintf('images[%s][file]', $fileCounter);
                $fileTransferArray[$formFilePath] = fopen($persistentResource->createTemporaryLocalCopy(), 'r');
            }
        } catch (ResourceManagementException $e) {
        } finally {
            return $fileTransferArray;
        }
    }

    /**
     * Returns the fields that should be sent when creating
     * a new entity.
     *
     * format:
     *  ['fieldName' => required]
     *
     * @return bool[]
     */
    abstract protected function getPostFields(): array;

    /**
     * Returns the fields that should be sent when updating
     * an entity.
     *
     * format:
     *  ['fieldName' => required]
     *
     * @return bool[]
     */
    abstract protected function getPatchFields(): array;

    /**
     * @return string
     */
    abstract protected function getDtoClass(): string;
}
