<?php

namespace App\Console\Commands;

use App\Models\Influencer;
use App\Models\Platform;
use App\Models\SocialProfile;
use Illuminate\Console\Command;
use App\Services\TwitterStatsService;

class FetchTwitterStats extends Command
{
    protected $signature = 'twitter:fetch {username}';
    protected $description = 'Fetch Twitter stats for a given username';

    public function handle()
    {
        $username = $this->argument('username');
        $service = new TwitterStatsService();

//        $stats = $service->getInfluencerStats($username);
        $stats = $service->getInfluencerStatsFromAPI($username);

        if (!$stats) {
            $this->error('Failed to fetch stats for ' . $username);
            return;
        }

        $this->showUserStats($stats);
        $platform = Platform::where('name', 'Twitter')->first();
        Platform::fins('name', 'Twitter')->first();

//         getInfluencer
//        $socialProfile = $service->getSocialProfile($username);
        SocialProfile::updateOrCreate(
            [
                'influencer_id' => $influencerId ?? Influencer::where('username', $username)->first()->id,
                'platform_id' => Platform::TWITTER_ID,
                'username' => $username
            ],
            [
                'profile_url' => 'https://twitter.com/' . $username,
                'profile_picture' => $stats['avatar'],
                'followers_count' => 0,
                'engagement_rate' => 0,
                'extra_data' => [
                    'link_karma' => $stats['link_karma'],
                    'comment_karma' => $stats['comment_karma'],
                    'total_karma' => $stats['total_karma'],
                    'created_utc' => $stats['created_utc']
                ],
                'last_updated' => now()
            ]
        );


        $this->info('Stats fetched successfully.');
        $this->info('Stats saved to the database.');

    }

    private function showUserStats(array $stats): void
    {
        $this->table(
            ['Metric', 'Value'],
            [
                ['Name', $stats['name']],
                ['Handle', $stats['handle']],
                ['Followers', $stats['followers']],
                ['Following', $stats['following']],
                ['Tweets', $stats['tweets']],
                ['Profile Image', $stats['profile_image']],
                ['Last Retrieved', $stats['retrieved_at']],
            ]
        );

        $this->info("\nLast 5 Tweets:");
        foreach ($stats['last_tweets'] ?? [] as $tweet) {
            $this->line("📅 {$tweet['time']}");
            $this->line("💬 {$tweet['text']}");
            $this->line("❤️ {$tweet['likes']}  🔄 {$tweet['retweets']}  💬 {$tweet['replies']}\n");
        }
    }
}
