<?php

namespace App\Factory;

use Symfony\Component\HttpFoundation\Response;

class ErrorResponseFactory implements ResponseFactoryInterface
{
    public function create($data,$code)
    {
        $result =  [
            'code' => $code,
            'messages' => $data
        ];
        $data = json_encode($result);
        return new Response($data, $code, [
            'Content-Type' => 'application/problem+json'
        ]);
    }

}