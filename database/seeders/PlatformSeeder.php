<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Platform;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Platform::updateorcreate([
            'name'        => 'Instagram',
            'description' => 'Instagram is a photo and video sharing social networking service owned by Facebook, Inc.',
            'base_url'    => 'https://www.instagram.com/'
        ]);

        Platform::updateorcreate([
            'name'        => 'TikTok',
            'description' => 'TikTok is a social media platform for creating, sharing and discovering short music videos.',
            'base_url'    => 'https://www.tiktok.com/'
        ]);

        Platform::updateorcreate([
            'name'        => 'YouTube',
            'description' => 'YouTube is a video sharing platform where users can watch, like, share, comment, and upload their own videos.',
            'base_url'    => 'https://www.youtube.com/'
        ]);

        Platform::updateorcreate([
            'name'        => 'Twitch',
            'description' => 'Twitch is a live streaming platform for gamers and content creators.',
            'base_url'    => 'https://www.twitch.tv/'
        ]);

        Platform::updateorcreate([
            'name'        => 'LinkedIn',
            'description' => 'LinkedIn is a social network for professionals to connect, share, and learn.',
            'base_url'    => 'https://www.linkedin.com/'
        ]);

        Platform::updateorcreate([
            'name'        => 'Facebook',
            'description' => 'Facebook is a social media platform that allows users to connect with friends and family, share content, and discover news and information.',
            'base_url'    => 'https://www.facebook.com/'
        ]);

        Platform::updateorcreate([
            'name'        => 'Twitter',
            'description' => 'Twitter is a microblogging platform that allows users to post and interact with messages known as "tweets".',
            'base_url'    => 'https://www.twitter.com/'
        ]);
    }
}
