<?php
declare(strict_types=1);

use Library\ApiClient;
use Library\FileLogger;
use Library\GuzzleRequest;
use PHPUnit\Framework\TestCase;

class ApiClientTest extends TestCase
{
    public function testAuthSuccess(): void
    {
        $client = $this->createApiClient();

        $authResult = $client->auth($_ENV['API_LOGIN'], $_ENV['API_PASSWORD']);

        self::assertEquals(true, $authResult);
    }

    /**
     * @dataProvider dataProviderFail
     */
    public function testGetUserData(string $userName, array $result): void
    {
        $client = $this->createApiClient();

        $client->auth($_ENV['API_LOGIN'], $_ENV['API_PASSWORD']);

        $userData = $client->getUserData($userName);

        self::assertEquals($result['status'], $userData['status']);

        self::assertEquals($result['data'], $userData['data']);

        self::assertEquals($result['message'], $userData['message']);


//        var_dump($result);
//        var_dump($userData);
//        exit();
    }

    /**
     * @return ApiClient
     */
    private function createApiClient(): ApiClient
    {
        $httpClient = new GuzzleRequest($_ENV['API_URL']);

        $logger = new FileLogger('log.log');

        return new ApiClient($httpClient, $logger);
    }

    /**
     * @return array[]
     */
    public function dataProviderFail(): array
    {
        return [
            'valid user' => [
                'userName' => 'Ivanov',
                'result' => [
                    'status' => 'OK',
                    'data' => [
                        'active' => 1,
                        'blocked' => false,
                        'created_at' => 1587457590,
                        'id' => 23,
                        'name' => 'Ivanov Ivan',
                        'permissions' => [
                            [
                                'id' => 1,
                                'permission' => 'comment',
                            ],
                            [
                                'id' => 2,
                                'permission' => 'upload photo',
                            ],
                            [
                                'id' => 3,
                                'permission' => 'add event',
                            ],
                        ],
                    ],
                    'message' => null,
                ],
            ],
            'invalid user' => [
                'userName' => 'wrong username',
                'result' => [
                    'status' => 'Not found',
                    'data' => null,
                    'message' => 'Invalid user data'
                ],
            ],
        ];
    }
}

require_once 'testSettings.php';
