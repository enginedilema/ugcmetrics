<?php

namespace App\Console\Commands;

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
        
        $stats = $service->getInfluencerStats($username);
        
        if ($stats) {
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
            foreach ($stats['last_tweets'] as $tweet) {
                $this->line("📅 {$tweet['time']}");
                $this->line("💬 {$tweet['text']}");
                $this->line("❤️ {$tweet['likes']}  🔄 {$tweet['retweets']}  💬 {$tweet['replies']}\n");
            }
        } else {
            $this->error('Failed to fetch stats for ' . $username);
        }
    }
}