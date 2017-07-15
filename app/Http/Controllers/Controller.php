<?php

namespace App\Http\Controllers;

use App\Services\ResponseService;
use Laravel\Lumen\Routing\Controller as BaseController;
class Controller extends BaseController
{
    /**
     * The response service instance.
     *
     * @var \App\Services\ResponseService
     */
    protected $responseService;

    /**
     * Controller parent constructor.
     *
     * @return void
     */
    protected function boot()
    {
        $this->responseService = new ResponseService;
    }
}
