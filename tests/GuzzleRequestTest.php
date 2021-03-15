<?php
declare(strict_types=1);

use Library\ApiRequestException;
use Library\GuzzleRequest;
use PHPUnit\Framework\TestCase;

class GuzzleRequestTest extends TestCase
{
    /**
     * @throws ApiRequestException
     */
    public function testWrongUrlFail(): void
    {
        $request = $this->createGuzzleRequest('wrong api url');

        $this->expectException(Library\ApiRequestException::class);

        $request->sendRequest(
            'GET',
            'auth',
            [
                'login' => $_ENV['API_LOGIN'],
                'pass' => $_ENV['API_PASSWORD'],
            ]
        );
    }

    /**
     * @dataProvider dataProviderFail
     *
     * @param string $apiUrl
     * @param string $login
     * @param string $password
     * @param string $status
     * @param string $message
     * @throws ApiRequestException
     */
    public function testSendRequestFail(string $apiUrl, string $login, string $password, string $status, string $message): void
    {
        $request = $this->createGuzzleRequest($apiUrl);

        $result = $request->sendRequest(
            'GET',
            'auth',
            [
                'login' => $login,
                'pass' => $password,
            ]
        );

        self::assertEquals($status, $result['status']);

        self::assertEquals($message, $result['message']);
    }

    /**
     * @param string $apiUrl
     *
     * @return GuzzleRequest
     */
    private function createGuzzleRequest(string $apiUrl): GuzzleRequest
    {
        return new GuzzleRequest($apiUrl);
    }

    /**
     * @return array[]
     */
    public function dataProviderFail(): array
    {
        return [
            'wrong login' => [
                'api_url' => $_ENV['API_URL'],
                'login' => 'wrong login',
                'password' => $_ENV['API_PASSWORD'],
                'status' => 'Error',
                'message' => 'Invalid credentials',
            ],
            'wrong password' => [
                'api_url' => $_ENV['API_URL'],
                'login' => $_ENV['API_LOGIN'],
                'password' => 'wrong password',
                'status' => 'Error',
                'message' => 'Invalid credentials',
            ],
        ];
    }
}

require_once 'testSettings.php';
