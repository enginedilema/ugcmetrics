<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Platform;
use App\Models\Influencer;
use App\Models\SocialProfile;
use App\Models\YouTubeMetric;
use App\Models\YouTubeMetrics;
use Carbon\Carbon;

class YouTubeInfluencersSeeder extends Seeder
{
    public function run()
    {
        $youtube = Platform::firstOrCreate(['name' => 'YouTube']);

        $influencer = Influencer::create([
            'name' => 'GadgetMan',
            'email' => 'gadget@example.com',
        ]);

        $profile = SocialProfile::create([
            'influencer_id' => $influencer->id,
            'platform_id' => $youtube->id,
            'username' => 'GadgetManYT',
            'profile_url' => 'https://youtube.com/@GadgetMan',
        ]);

        YouTubeMetrics::create([
            'social_profile_id' => $profile->id,
            'date' => Carbon::today(),
            'subscribers' => 1200,
            'views' => 50000,
            'likes' => 3500,
            'comments' => 250,
            'video_count' => 120,
            'average_watch_time' => 6.3, // minutos
            'channel_quality_score' => 8.7,
        ]);
    }
}
