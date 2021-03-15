<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';

use Library\ApiClient;
use Library\GuzzleRequest;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

$dotenv->load();


$httpClient = new GuzzleRequest($_ENV['API_URL']);

$client = new ApiClient($httpClient);

$result = $client->auth($_ENV['API_LOGIN'], $_ENV['API_PASSWORD']);

$userData = $client->getUserData('Ivanov');

$newUserData = [
    'active' => 1,
    'blocked' => false,
    'created_at' => 1587457590,
    'id' => 23,
    'name' => 'Petr Petrov',
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
];

$result = $client->setUserData(23, $newUserData);




var_dump($result);