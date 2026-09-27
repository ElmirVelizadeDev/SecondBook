<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class CookiePolicyController extends Controller
{
    public function index()
    {
        return view('Frontend.cookies');
    }
}