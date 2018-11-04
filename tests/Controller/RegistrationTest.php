<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;

class RegistrationTest extends TestCase
{
    public function testPostRegister()
    {
        $data = [
            'username' => 'Supernick',
            'email' => 'Supernick@gmail.com',
            'plainPassword' => [
                'first' => 'test123', 'second' => 'test123'
            ]
        ];
        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', 'http://www.planner.api/register',['body' => json_encode($data)]);

        $this->assertEquals(201, $res->getStatusCode());
    }



}