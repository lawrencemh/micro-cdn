<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    /**
     * Return the index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('index');
    }
}
