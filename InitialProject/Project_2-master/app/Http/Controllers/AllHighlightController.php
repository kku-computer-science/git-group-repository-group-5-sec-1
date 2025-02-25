<?php

namespace App\Http\Controllers;

use App\Models\Highlight;
use Illuminate\Http\Request;

class AllHighlightController extends Controller
{
    public function index()
    {
        $highlights = Highlight::orderBy('created_at', 'desc')->get();

        return view('highlight.all', compact('highlights'));
    }

    public function create()
    {
        return view('highlight.create');
    }
}
