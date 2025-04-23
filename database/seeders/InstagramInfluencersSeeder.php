<?php

namespace Database\Seeders;

use App\Models\Influencer;
use App\Models\Platform;
use App\Models\SocialProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\InstagramMetric;
use App\Models\InstagramMetrics;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class InstagramInfluencersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->insertInfluencers();
        // Check if instagram_users.txt exists
        $filePath = database_path('instagram_users.txt');
        
        if (!File::exists($filePath)) {
            $this->command->error('The file instagram_users.txt does not exist in the database directory.');
            return;
        }

        // Get or create the Instagram platform
        $platform = Platform::firstOrCreate(
            ['name' => 'Instagram'],
            [
                'description' => 'Instagram social media platform',
                'base_url' => 'https://www.instagram.com/'
            ]
        );

        // Read the file contents
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Parse each line (format: username;full_name;followers;bio)
            $data = explode(';', $line);
            
            if (count($data) < 4) {
                $this->command->warn("Skipping invalid line: $line");
                continue;
            }

            [$username, $fullName, $followersCount, $bio] = $data;

            // Convert followers count (e.g., '164M' to 164000000)
            $followersCount = $this->parseFollowersCount($followersCount);

            // Create the influencer
            $influencer = Influencer::create([
                'name' => $fullName,
                'bio' => $bio,
                'profile_picture_url' => "" // Placeholder URL
            ]);

            // Create the social profile for Instagram
            SocialProfile::create([
                'influencer_id' => $influencer->id,
                'platform_id' => $platform->id,
                'username' => $username,
                'profile_url' => "https://www.instagram.com/{$username}/",
                'followers_count' => $followersCount,
                'engagement_rate' => rand(1, 10) / 100, // Random engagement rate between 0.01 and 0.1
                'extra_data' => [
                    'verified' => rand(0, 1) == 1,
                    'post_count' => rand(50, 5000)
                ]
            ]);

            $this->command->info("Created influencer: {$fullName} with Instagram username: {$username}");
        }

        $this->command->info('Instagram influencers seeding completed successfully.');
   
    }
    /**
     * Parse followers count from format like '164M' to actual number
     */
    private function parseFollowersCount(string $followersCount): int
    {
        $lastChar = strtoupper(substr($followersCount, -1));
        $number = (float) substr($followersCount, 0, -1);
        
        return match($lastChar) {
            'K' => (int)($number * 1000),
            'M' => (int)($number * 1000000),
            'B' => (int)($number * 1000000000),
            default => (int)$followersCount
        };
    }

    protected function insertInfluencers(){
        $instagram = Platform::firstOrCreate(
            ['name' => 'Instagram'],
        );
        $img_url= 'https://instagram.fbcn13-1.fna.fbcdn.net/v/t51.2885-19/327419342_625121782752099_6040016047331074072_n.jpg?_nc_ht=instagram.fbcn13-1.fna.fbcdn.net&_nc_cat=103&_nc_oc=Q6cZ2QGo9jMLKl8xnUc2Fzj0uIWGxGm65foOxcQgZPLQ0ulmvZhCfCLMgKpmdpRftTY-Fig&_nc_ohc=6IgUNLYcPBIQ7kNvwF9gI4V&_nc_gid=HfYnUOdK0VlaeOumt13FeQ&edm=APoiHPcBAAAA&ccb=7-5&oh=00_AfFOH-q7TwnpfMx7bpQhEJcQl49Gavea29q9xpP1pmmW4w&oe=680A9F06&_nc_sid=22de04';
        Storage::disk('public')->put('images/ironpanda.jpg', file_get_contents($img_url));

        $influencer = Influencer::create([
            'name' => 'IronpandaFitness',
            'bio' => "It's not about muscle, not about time. It's about healthy.",
            'location' => 'Desconocido',
            'profile_picture_url' => 'images/ironpanda.jpg',
        ]);
        $profile = $influencer->socialProfiles()->create([
            'platform_id' => $instagram->id,
            'username' => 'ironpanda_fitness',
            'profile_url' => 'https://www.instagram.com/ironpanda_fitness/',
            'followers_count' => 291000, // Según información pública
            'engagement_rate' => 3.5, // Estimación
            'extra_data' => ['verified' => false],
        ]);
        $followers = 281000;
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dailyGrowth = rand(-10, 50);
            $followers += $dailyGrowth;

            InstagramMetrics::updateOrCreate([
                'social_profile_id' => $profile->id,
                'date' => $date->toDateString(),
            ], [
                'followers' => $followers,
                'engagement_rate' => round(rand(150, 500) / 100, 2),
                'avg_likes' => rand(1000, 5000),
                'avg_comments' => rand(50, 300),
                'avg_views' => rand(5000, 20000),
            ]);
        }

        $this->command->info("✔️ Datos generados para el influencer: Ironpanda");

        $img_url= 'https://instagram.fbcn13-1.fna.fbcdn.net/v/t51.2885-19/489889153_532837402886271_8055938276496771217_n.jpg?_nc_ht=instagram.fbcn13-1.fna.fbcdn.net&_nc_cat=103&_nc_oc=Q6cZ2QF8V01Md-rmkzBaeKuXD-kZyqo4ghqQ19Uckjo41SGgcK_tqbHcZWdd3dMSVBXr6Mc&_nc_ohc=DlFEY7nCfIMQ7kNvwGXjXD7&_nc_gid=XTbQnwUDG3smE7UdbtVmIQ&edm=AP4sbd4BAAAA&ccb=7-5&oh=00_AfGlUFLhToxDW0hRwLJpXdS5Uw8nzdD2U6NsdQbohFZ-kA&oe=680BB874&_nc_sid=7a9f4b';
        Storage::disk('public')->put('images/ironpanda._.jpg', file_get_contents($img_url));

        $influencer = Influencer::create([
            'name' => 'Ironpanda',
            'bio' => "Raid de orientación y aventura por Marruecos",
            'location' => 'Spain',
            'profile_picture_url' => 'images/ironpanda._.jpg', // URL simulada
        ]);
        $profile = $influencer->socialProfiles()->create([
            'platform_id' => $instagram->id,
            'username' => 'ironpanda._',
            'profile_url' => 'https://www.instagram.com/ironpanda._/',
            'followers_count' => 1974, // Según información pública
            'engagement_rate' => 3.5, // Estimación
            'extra_data' => ['verified' => false],
        ]);
        $followers = 1974;
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dailyGrowth = rand(-10, 50);
            $followers += $dailyGrowth;

            InstagramMetrics::updateOrCreate([
                'social_profile_id' => $profile->id,
                'date' => $date->toDateString(),
            ], [
                'followers' => $followers,
                'engagement_rate' => round(rand(150, 500) / 100, 2),
                'avg_likes' => rand(50, 200),
                'avg_comments' => rand(50, 300),
                'avg_views' => rand(500, 2000),
            ]);
        }

        $this->command->info("✔️ Datos generados para el influencer: Ironpanda");

    }
}


