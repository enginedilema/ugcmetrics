<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SocialProfile;
use App\Models\YouTubeMetrics;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class GenerateYouTubeReports extends Command
{
    protected $signature = 'youtube:generate-reports';
    protected $description = 'Genera informes mensuals de YouTube i actualitza les mètriques';

    public function handle(): void
    {
        $apiKey = config('services.youtube.api_key');

        $profiles = SocialProfile::whereHas('platform', fn($q) => $q->where('name', 'YouTube'))->get();

        foreach ($profiles as $profile) {
            $channelId = $this->extractChannelId($profile);
            if (!$channelId) {
                $this->warn("No s'ha pogut obtenir el channelId per a {$profile->profile_url}");
                continue;
            }

            $response = Http::get("https://www.googleapis.com/youtube/v3/channels", [
                'part' => 'snippet,statistics',
                'id' => $channelId,
                'key' => $apiKey,
            ]);

            if (!$response->ok() || !$response['items']) {
                $this->error("Error accedint al canal $channelId");
                continue;
            }

            $data = $response['items'][0];
            $snippet = $data['snippet'];
            $stats = $data['statistics'];

            $profile->update([
                'followers_count' => $stats['subscriberCount'] ?? null,
                'views_count' => $stats['viewCount'] ?? null, // AFEGIT
                'profile_picture' => $snippet['thumbnails']['default']['url'] ?? null,
                'extra_data' => $data,
                'last_updated' => now(),
            ]);
            

            YouTubeMetrics::updateOrCreate(
                [
                    'social_profile_id' => $profile->id,
                    'date' => Carbon::now()->startOfMonth(),
                ],
                [
                    'subscribers' => $stats['subscriberCount'] ?? null,
                    'views' => $stats['viewCount'] ?? null,
                    'video_count' => $stats['videoCount'] ?? null,
                ]
            );

            $this->info("Actualitzat canal: {$channelId}");
        }
    }

    private function extractChannelId(SocialProfile $profile): ?string
    {
        $url = $profile->profile_url ?? '';
        $apiKey = config('services.youtube.api_key');
    
        if (preg_match('/youtube\.com\/channel\/([^\/]+)/', $url, $matches)) {
            return $matches[1];
        } elseif (preg_match('/youtube\.com\/user\/([^\/]+)/', $url, $matches)) {
            $response = Http::get('https://www.googleapis.com/youtube/v3/channels', [
                'part' => 'id',
                'forUsername' => $matches[1],
                'key' => $apiKey,
            ]);
            return $response['items'][0]['id'] ?? null;
        } elseif (preg_match('/youtube\.com\/@([^\/]+)/', $url, $matches)) {
            $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
                'part' => 'snippet',
                'q' => $matches[1],
                'type' => 'channel',
                'maxResults' => 1,
                'key' => $apiKey,
            ]);
            return $response['items'][0]['snippet']['channelId'] ?? null;
        }
    
        return null;
    }
    
}
