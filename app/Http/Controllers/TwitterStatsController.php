<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwitterStatsService;

class TwitterStatsController extends Controller
{
    public function show($username)
    {
        $service = new TwitterStatsService();
        $stats = $service->getInfluencerStats($username);
        
        if (!$stats) {
            return response()->json([
                'error' => 'Could not fetch stats for this user',
                'suggestion' => 'Try again later or check if the username is correct'
            ], 404);
        }
        
        return response()->json($stats);
    }
}