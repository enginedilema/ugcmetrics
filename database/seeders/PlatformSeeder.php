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

        Platform::create(['name' => 'Instagram', 'description' => 'Instagram is a photo and video sharing social networking service owned by Facebook, Inc.']);
        Platform::create(['name' => 'TikTok', 'description' => 'TikTok is a social media platform for creating, sharing and discovering short music videos.']);
        Platform::create(['name' => 'YouTube', 'description' => 'YouTube is a video sharing platform where users can watch, like, share, comment, and upload their own videos']);
        Platform::create(['name' => 'Twitch', 'description' => 'Twitch is a live streaming platform for gamers and content creators.']);
        Platform::create(['name' => 'LinkedIn','description' => 'LinkedIn is a social network for professionals to connect, share, and learn.']);
        Platform::create(['name' => 'Facebook', 'description' => 'Facebook is a social media platform that allows users to connect with friends and family, share content, and discover news and information.']);
        Platform::create(['name' => 'Twitter', 'description' => 'Twitter is a microblogging platform that allows users to post and interact with messages known as "tweets".']);
    }
}