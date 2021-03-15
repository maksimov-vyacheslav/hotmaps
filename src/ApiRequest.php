<?php
declare(strict_types=1);

namespace Library;

/**
 * Interface HttpRequest
 */
interface ApiRequest
{
    /**
     * @param string $requestType
     * @param string $apiMethod
     * @param array $queryParams
     * @param array $postParams
     *
     * @return array
     *
     * @throws ApiRequestException
     */
    public function sendRequest(string $requestType, string $apiMethod, array $queryParams, array $postParams = []): array;
}