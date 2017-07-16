<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    /**
     * Return the validation api json error response.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $errors
     * @return \App\Http\Controllers\JsonResponse|mixed
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        if (isset(static::$responseBuilder)) {
            return call_user_func(static::$responseBuilder, $request, $errors);
        }

        return $this->responseService()
            ->json()
            ->setErrors($errors)
            ->setResponseCode(422)
            ->render();
    }
}
