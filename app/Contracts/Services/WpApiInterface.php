<?php

namespace App\Contracts\Services;

interface WpApiInterface
{
    public function request($method, $url, $params = []);
}