<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RedditMetrics;

class RedditController extends Controller
{
    public function index()
{
    $profiles = RedditMetrics::latest('date')->paginate(10);
    return view('reddit.index', compact('profiles'));
}

    public function show($id)
    {
        $metric = RedditMetrics::findOrFail($id);
        return view('reddit.show', compact('metric'));
    }
}
