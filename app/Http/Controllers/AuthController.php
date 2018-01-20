<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    /**
     * Return the account information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'api-token'    => 'required',
        ]);

        try {
            User::where('api_token', $request->get('api-token'))->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response("Unauthorized",401);
        }

        return response()->json(['valid' => true], 200);
    }
}
