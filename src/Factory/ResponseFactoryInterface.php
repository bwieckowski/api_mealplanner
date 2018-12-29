<?php

namespace App\Factory;


interface ResponseFactoryInterface
{
    public function create($data,$code);
}