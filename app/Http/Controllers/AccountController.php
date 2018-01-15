<?php

namespace App\Http\Controllers;

class AccountController extends Controller
{
    /**
     * Return the account page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('account');
    }
}
