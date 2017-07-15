<?php

namespace App\Services;

use App\Services\Response\JsonService;

class ResponseService
{
    public function json()
    {
        return new JsonService;
    }
}