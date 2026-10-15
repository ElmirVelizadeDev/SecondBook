<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
class HomeController extends Controller
{
    public function index()
    {
        return view('Frontend.home');
    }

    public function featuredBooks()
    {
        return redirect()->to(route('frontend.home') . '#featured-books');
    }
}
