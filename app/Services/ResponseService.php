<?php

namespace App\Services;

use App\Services\Response\JsonService;

class ResponseService
{
    /**
     * Create a json api response.
     *
     * @return \App\Services\Response\JsonService
     */
    public function json()
    {
        return new JsonService();
    }
}
