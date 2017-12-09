<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    /**
     * Return the index page.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(['status' => true], 200);
    }
}
