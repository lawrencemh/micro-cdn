<?php

namespace App\Http\Controllers;

use App\Services\ResponseService;
use Laravel\Lumen\Routing\Controller as BaseController;
class Controller extends BaseController
{
    /**
     * Return and instantiate the response service.
     *
     * @return \App\Services\ResponseService
     */
    protected function responseService()
    {
        return new ResponseService();
    }
}
