<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostsController extends Controller
{
    //
    public function index()
    {
        return view('admin.home');
    }

    public function show()
    {
        return view('posts');
    }
}
