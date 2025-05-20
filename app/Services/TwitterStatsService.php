<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Log;

class TwitterStatsService
{
    protected Client $client;
    protected $headers;

    protected Client $apiClient;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://twitter.com/',
            'timeout' => 15.0,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
            ]
        ]);

        $this->apiClient = new Client([
            'base_uri' => 'https://api.twitter.com/2/',
            'timeout' => 15.0,
            'headers' => [
                'Authorization' => 'Bearer AAAAAAAAAAAAAAAAAAAAAJKI1wEAAAAAMSBql%2BQXAj6951TsOlUZlhgdGKA%3DtZLvTF5xCaf4P1hfOs5t9lPrleJJPTF2An0iHnQaQ7SdZJChd0',
                'User-Agent' => 'YourApp/1.0'
            ]
        ]);
    }

    public function getInfluencerStatsFromAPI(string $username): ?array
    {
        try {
            $response = $this->apiClient->get("users/by/username/{$username}", [
                'query' => [
                    'user.fields' => 'name,profile_image_url,public_metrics'
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            return [
                'name' => $data['data']['name'],
                'handle' => $username,
                'followers' => $data['data']['public_metrics']['followers_count'],
                'following' => $data['data']['public_metrics']['following_count'],
                'tweets' => $data['data']['public_metrics']['tweet_count'],
                'profile_image' => $data['data']['profile_image_url'],
                // last_tweets
                'retrieved_at' => now()->toDateTimeString()
            ];

        } catch (RequestException $e) {
            Log::error("Error fetching Twitter stats from API: " . $e->getMessage());
            return null;
        }
    }

    public function getInfluencerStats($username)
    {
        try {
            $response = $this->client->get($username);
            $html = (string) $response->getBody();

            $crawler = new Crawler($html);

            // Extraer datos (estos selectores pueden cambiar)
            $stats = [
                'name' => $this->extractName($crawler),
                'handle' => $username,
                'followers' => $this->extractFollowers($crawler),
                'following' => $this->extractFollowing($crawler),
                'tweets' => $this->extractTweets($crawler),
                'profile_image' => $this->extractProfileImage($crawler),
                'last_tweets' => $this->extractLastTweets($crawler),
                'retrieved_at' => now()->toDateTimeString()
            ];

            return $stats;

        } catch (RequestException $e) {
            Log::error("Error fetching Twitter stats: " . $e->getMessage());
            return null;
        }
    }

    protected function extractName(Crawler $crawler): string
    {
        try {
            return $crawler->filter('[data-testid="UserName"]')->text();
        } catch (\Exception $e) {
            return '';
        }
    }

    protected function extractFollowers(Crawler $crawler): string
    {
        try {
            return $crawler->filter('[href*="/followers"][role="link"]')->text();
        } catch (\Exception $e) {
            return '0';
        }
    }

    protected function extractFollowing(Crawler $crawler): string
    {
        try {
            return $crawler->filter('[href*="/following"][role="link"]')->text();
        } catch (\Exception $e) {
            return '0';
        }
    }

    protected function extractTweets(Crawler $crawler): string
    {
        try {
            return $crawler->filter('[href*="/status"][role="link"]')->first()->text();
        } catch (\Exception $e) {
            return '0';
        }
    }

    protected function extractProfileImage(Crawler $crawler): string
    {
        try {
            return $crawler->filter('[data-testid="UserAvatar-Container-Img"]')->attr('src');
        } catch (\Exception $e) {
            return '';
        }
    }

    protected function extractLastTweets(Crawler $crawler): array
    {
        $tweets = [];
        try {
            $crawler->filter('[data-testid="tweet"]')->each(function (Crawler $node) use (&$tweets) {
                $tweets[] = [
                    'text' => $node->filter('[data-testid="tweetText"]')->text(),
                    'time' => $node->filter('time')->attr('datetime'),
                    'likes' => $node->filter('[data-testid="like"]')->text(),
                    'retweets' => $node->filter('[data-testid="retweet"]')->text(),
                    'replies' => $node->filter('[data-testid="reply"]')->text(),
                ];
            });
        } catch (\Exception $e) {
            Log::error("Error extracting tweets: " . $e->getMessage());
        }

        return array_slice($tweets, 0, 5); // Retorna los últimos 5 tweets
    }
}
