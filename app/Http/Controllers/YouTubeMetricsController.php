<?php

namespace App\Http\Controllers;

use App\Models\YouTubeMetrics;
use App\Http\Requests\StoreYouTubeMetricsRequest;
use App\Http\Requests\UpdateYouTubeMetricsRequest;
use Illuminate\Http\Request;

class YouTubeMetricsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $metrics = YouTubeMetrics::all();
        return response()->json($metrics);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Normalmente usado para mostrar un formulario en apps web.
        return response()->json(['message' => 'Mostrar formulario de creación.']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreYouTubeMetricsRequest $request)
    {
        $metric = YouTubeMetrics::create($request->validated());
        return response()->json($metric, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(YouTubeMetrics $youTubeMetrics)
    {
        return response()->json($youTubeMetrics);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(YouTubeMetrics $youTubeMetrics)
    {
        // Normalmente usado para mostrar un formulario en apps web.
        return response()->json(['message' => 'Mostrar formulario de edición.', 'data' => $youTubeMetrics]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateYouTubeMetricsRequest $request, YouTubeMetrics $youTubeMetrics)
    {
        $youTubeMetrics->update($request->validated());
        return response()->json($youTubeMetrics);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(YouTubeMetrics $youTubeMetrics)
    {
        $youTubeMetrics->delete();
        return response()->json(['message' => 'Métrica eliminada con éxito.']);
    }
}
