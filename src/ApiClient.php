<?php
declare(strict_types=1);

namespace Library;

use Library\ApiRequest;

class ApiClient
{
    private ApiRequest $apiClient;

    private Logger $logger;

    private string $token;

    /**
     * ApiClient constructor.
     *
     * @param ApiRequest $apiClient
     * @param Logger $logger
     */
    public function __construct(
        ApiRequest $apiClient,
        Logger $logger
    )
    {
        $this->apiClient = $apiClient;

        $this->logger = $logger;
    }

    /**
     * @param string $login
     * @param string $password
     *
     * @return bool
     */
    public function auth(string $login, string $password): bool
    {
        $authData = $this->tryToSendRequest(
            'GET',
            'auth',
            [
                'login' => $login,
                'pass' => $password,
            ]
        );

        if (!isset($authData['token'])) {
            return false;
        }

        $this->token = $authData['token'];

        return true;
    }

    /**
     * @param string $clientName
     *
     * @return array
     */
    public function getUserData(string $clientName): array
    {
        return $this->tryToSendRequest(
            'GET',
            'get-user/' . $clientName,
            [
                'token' => $this->token,
            ]
        );
    }

    /**
     * @param int $userId
     * @param array $userData
     *
     * @return bool
     */
    public function setUserData(int $userId, array $userData): bool
    {
        $result = $this->tryToSendRequest(
            'POST',
            'user/' . $userId,
            [
                'token' => $this->token,
            ],
            $userData
        );

        return $result['status'] === 'Ok';
    }

    /**
     * @param string $requestType
     * @param string $requestUrl
     * @param array $queryParams
     * @param array $postParams
     *
     * @return array
     */
    private function tryToSendRequest(
        string $requestType,
        string $requestUrl,
        array $queryParams,
        array $postParams = []
    ): array
    {
        try {
            return $this->apiClient->sendRequest(
                $requestType,
                $requestUrl,
                $queryParams,
                $postParams
            );
        } catch (ApiRequestException $e) {
            $this->tryToLog($e->getMessage());
        }
    }

    /**
     * @param string $message
     *
     * @return bool
     */
    private function tryToLog(string $message): bool
    {
        try {
            return $this->logger->info($message);
        } catch (LoggerException $e) {
            // если не получается писать в лог просто выводим
            echo $e->getMessage();
        }
    }
}