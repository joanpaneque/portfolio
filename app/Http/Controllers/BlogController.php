<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        return Inertia::render('Blog/Index');
    }

    public function show($slug)
    {
        return Inertia::render('Blog/Show');
    }
}
