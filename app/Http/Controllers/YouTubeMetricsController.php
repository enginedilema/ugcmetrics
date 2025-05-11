<?php

namespace App\Http\Controllers;

use App\Models\YouTubeMetrics;
use App\Http\Requests\StoreYouTubeMetricsRequest;
use App\Http\Requests\UpdateYouTubeMetricsRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\InfluencerController;
use App\Models\Influencer;



class YouTubeMetricsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $influencers = Influencer::with('socialProfiles.platform')->get();
        return view('youtube.index', compact('influencers'));
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
    public function show($id)
    {
        $influencer = Influencer::with(['socialProfiles' => function($query) {
            $query->whereHas('platform', function($q) {
                $q->where('name', 'YouTube');
            });
        }])->findOrFail($id);

        $youtubeProfile = $influencer->socialProfiles->firstWhere('platform.name', 'YouTube');
        
        if (!$youtubeProfile) {
            return redirect()->route('youtube.index')
                ->with('error', 'No se encontró un perfil de YouTube para este influencer.');
        }

        $metrics = YouTubeMetrics::where('social_profile_id', $youtubeProfile->id)
            ->orderBy('date', 'desc')
            ->get();
            
        $latestMetrics = $metrics->first();

        return view('youtube.show', compact('influencer', 'youtubeProfile', 'metrics', 'latestMetrics'));
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
