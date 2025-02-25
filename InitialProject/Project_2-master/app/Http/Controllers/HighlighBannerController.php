<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HighlighBannerController extends Controller
{
    public function index()
    {
        return view('highlight');
    }
}