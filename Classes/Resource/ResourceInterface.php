<?php
namespace PunktDe\Sylius\Api\Resource;

/*
 *  (c) 2020 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use PunktDe\Sylius\Api\Dto\ApiDtoInterface;
use PunktDe\Sylius\Api\ResultCollection;

interface ResourceInterface
{
    /**
     * @param ApiDtoInterface $dto
     * @return ApiDtoInterface
     */
    public function add(ApiDtoInterface $dto): ?ApiDtoInterface;

    /**
     * @param ApiDtoInterface $dto
     * @return bool
     */
    public function update(ApiDtoInterface $dto): bool;

    /**
     * @param string $identifier
     * @return bool
     */
    public function has(string $identifier): bool;

    /**
     * @param string $identifier
     * @return mixed
     */
    public function get(string $identifier): ?ApiDtoInterface;

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
     * @return ResultCollection
     */
    public function getAll(array $criteria = [], int $limit = 100, array $sorting = []): ResultCollection;

    /**
     * @param string $identifier
     * @return bool
     */
    public function delete(string $identifier): bool;
}
