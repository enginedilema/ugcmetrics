<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Platform;
use App\Models\Influencer;
use App\Models\SocialProfile;
use App\Models\YouTubeMetrics;
use Carbon\Carbon;

class YouTubeInfluencersSeeder extends Seeder
{
    public function run()
    {
        $platform = Platform::factory()->create([
            'name' => 'YouTube',
            'description' => 'Video sharing platform',
            'base_url' => 'https://www.youtube.com'
        ]);

        Influencer::factory(3)->create()->each(function ($influencer) use ($platform) {
            $profile = SocialProfile::factory()->create([
                'influencer_id' => $influencer->id,
                'platform_id' => $platform->id,
                'username' => strtolower($influencer->username),
                'profile_url' => 'https://www.youtube.com/@' . strtolower($influencer->username),
                'profile_picture' => $influencer->profile_picture_url,
                'followers_count' => rand(10000, 1000000),
                'views_count' => rand(100000, 50000000),
                'engagement_rate' => rand(10, 60) / 10,
                'extra_data' => json_encode(['category' => 'tech']),
                'last_updated' => now(),
            ]);

            foreach (range(1, 5) as $i) {
                YouTubeMetrics::factory()->create([
                    'social_profile_id' => $profile->id,
                    'date' => now()->subDays($i),
                    'subscribers' => rand(10000, 1000000),
                    'views' => rand(100000, 10000000),
                    'likes' => rand(1000, 500000),
                    'comments' => rand(500, 100000),
                    'video_count' => rand(50, 500),
                    'average_watch_time' => rand(60, 600), // en segundos
                    'channel_quality_score' => rand(60, 100),
                ]);
            }
        });
    }
}
