<?php

namespace App\Tests\Controller;

use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    public function testLogin()
    {
        $data = [
            'username' => 'smati',
            'password' => 'test123',
        ];
        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', 'http://www.planner.api/login', ['body' => json_encode($data)]);

        $this->assertEquals(200, $res->getStatusCode());
        //$this->assertArrayHasKey('token', json_decode($res->getBody()));
    }

    public function testLoginWithBadCredentials()
    {
        $data = [
            'username' => 'bad_nick',
            'password' => 'test123',
        ];
        $client = new \GuzzleHttp\Client();
        try {
            $client->request('POST', 'http://www.planner.api/login', ['body' => json_encode($data)]);
        } catch (ClientException $e) {
            $res = $e->getResponse();
        }

        $this->assertEquals(400, $res->getStatusCode());
    }

}