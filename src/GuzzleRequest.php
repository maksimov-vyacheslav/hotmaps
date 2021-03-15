<?php
declare(strict_types=1);

namespace Library;

use JsonException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GuzzleRequest implements ApiRequest
{
    private string $apiUrl;

    /**
     * GuzzleApiClient constructor.
     *
     * @param string $apiUrl
     */
    public function __construct(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

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
    public function sendRequest(
        string $requestType,
        string $apiMethod,
        array $queryParams,
        array $postParams = []
    ): array
    {
        $json = $this->tryToSendRequest(
            $requestType,
            $this->apiUrl . $apiMethod . '?' . http_build_query($queryParams),
            $postParams
        );

        return $this->tryToDecode($json);
    }

    /**
     * @param string $requestType
     * @param string $requestUrl
     * @param array $postParams
     *
     * @return string
     *
     * @throws ApiRequestException
     */
    private function tryToSendRequest(
        string $requestType,
        string $requestUrl,
        array $postParams
    ): string
    {
        $client = new Client();

        try {
            $res = $client->request(
                $requestType,
                $requestUrl,
                $postParams
            );

            return $res->getBody()->getContents();
        } catch (GuzzleException $e) {
            throw new ApiRequestException($e->getMessage());
        }
    }

    /**
     * @param string $json
     *
     * @return array
     *
     * @throws ApiRequestException
     */
    private function tryToDecode(string $json): array
    {
        try {
            return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new ApiRequestException($e->getMessage());
        }
    }
}