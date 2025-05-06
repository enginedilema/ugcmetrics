<?php

namespace Database\Seeders;

use App\Models\Influencer;
use App\Models\Platform;
use App\Models\SocialProfile;
use App\Models\TwitchMetrics;
use App\Models\TwitchStream;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TwitchInfluencersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->insertTwitchInfluencers();
        $this->command->info('Twitch influencers seeding completed successfully.');
    }

    /**
     * Insert predefined Twitch influencers with their metrics and streams
     */
    protected function insertTwitchInfluencers()
    {
        // Obtener la plataforma Twitch
        $twitch = Platform::firstOrCreate(
            ['name' => 'Twitch'],
            ['description' => 'Twitch is a live streaming platform for gamers and content creators.']
        );

        // Primer influencer de Twitch: Ibai
        $img_url = 'https://static-cdn.jtvnw.net/jtv_user_pictures/574228be-01ef-4eab-b43e-651e6b0877b5-profile_image-300x300.png';
        Storage::disk('public')->put('images/ibai.png', file_get_contents($img_url));

        $influencer = Influencer::create([
            'name' => 'Ibai Llanos',
            'bio' => "Creador de contenido, caster y propietario de Porcinos FC en la Kings League.",
            'location' => 'España',
            'profile_picture_url' => 'images/ibai.png',
        ]);

        $profile = $influencer->socialProfiles()->create([
            'platform_id' => $twitch->id,
            'username' => 'ibai',
            'profile_url' => 'https://www.twitch.tv/ibai',
            'followers_count' => 11300000, // Aproximado
            'engagement_rate' => 4.2,
            'extra_data' => ['verified' => true, 'partner' => true],
        ]);

        $this->generateMetricsForProfile($profile, 200000, 220000);
        $this->generateStreamsForProfile($profile);
        $this->command->info("✓ Datos generados para el streamer: Ibai");

        // Segundo influencer de Twitch: TheGrefg
        $img_url = 'https://static-cdn.jtvnw.net/jtv_user_pictures/f83e260a-418d-4bff-a5a3-74d4e42ac9be-profile_image-300x300.png';
        Storage::disk('public')->put('images/thegrefg.png', file_get_contents($img_url));

        $influencer = Influencer::create([
            'name' => 'David Cánovas',
            'bio' => "Streamer español. Creador del Team Heretics y propietario de Saiyans FC en la Kings League.",
            'location' => 'Andorra',
            'profile_picture_url' => 'images/thegrefg.png',
        ]);

        $profile = $influencer->socialProfiles()->create([
            'platform_id' => $twitch->id,
            'username' => 'thegrefg',
            'profile_url' => 'https://www.twitch.tv/thegrefg',
            'followers_count' => 10100000, // Aproximado
            'engagement_rate' => 3.8,
            'extra_data' => ['verified' => true, 'partner' => true],
        ]);

        $this->generateMetricsForProfile($profile, 150000, 180000);
        $this->generateStreamsForProfile($profile);
        $this->command->info("✓ Datos generados para el streamer: TheGrefg");

        // Tercer influencer de Twitch: Auronplay
        $img_url = 'https://static-cdn.jtvnw.net/jtv_user_pictures/ec898976-8ae2-4ab7-9c0c-a7f25d9eae9b-profile_image-300x300.png';
        Storage::disk('public')->put('images/auronplay.png', file_get_contents($img_url));

        $influencer = Influencer::create([
            'name' => 'Raúl Álvarez',
            'bio' => "Streamer y youtuber español. Propietario de Los Troncos FC en la Kings League.",
            'location' => 'Andorra',
            'profile_picture_url' => 'images/auronplay.png',
        ]);

        $profile = $influencer->socialProfiles()->create([
            'platform_id' => $twitch->id,
            'username' => 'auronplay',
            'profile_url' => 'https://www.twitch.tv/auronplay',
            'followers_count' => 14200000, // Aproximado
            'engagement_rate' => 4.5,
            'extra_data' => ['verified' => true, 'partner' => true],
        ]);

        $this->generateMetricsForProfile($profile, 180000, 220000);
        $this->generateStreamsForProfile($profile);
        $this->command->info("✓ Datos generados para el streamer: Auronplay");
    }

    /**
     * Generate daily metrics for a Twitch profile
     */
    private function generateMetricsForProfile(SocialProfile $profile, int $minViewers, int $maxViewers)
    {
        $followers = $profile->followers_count;
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dailyGrowth = rand(-500, 2000);
            $followers += $dailyGrowth;
            $subscribers = round($followers * rand(1, 3) / 100); // 1-3% de followers
            $averageViewers = rand($minViewers, $maxViewers);
            $peakViewers = $averageViewers * rand(120, 150) / 100; // 20-50% más que promedio
            $hoursStreamed = rand(4, 8);
            $streamCount = rand(1, 2); // 1 o 2 streams por día
            $chatMessages = $averageViewers * rand(5, 15); // 5-15 mensajes por viewer

            TwitchMetrics::updateOrCreate([
                'social_profile_id' => $profile->id,
                'date' => $date->toDateString(),
            ], [
                'followers' => $followers,
                'subscribers' => $subscribers,
                'average_viewers' => $averageViewers,
                'peak_viewers' => $peakViewers,
                'hours_streamed' => $hoursStreamed,
                'stream_count' => $streamCount,
                'chat_messages' => $chatMessages,
            ]);
        }
    }

    /**
     * Generate sample streams for a Twitch profile
     */
    private function generateStreamsForProfile(SocialProfile $profile)
    {
        $games = [
            'Just Chatting' => ['Charlando con la comunidad', 'Q&A y novedades', 'Reaccionando a videos'],
            'Minecraft' => ['Survival con la comunidad', 'Construyendo mega base', 'Evento especial'],
            'Fortnite' => ['Partidas con subs', 'Torneo competitivo', 'Nuevo pase de batalla'],
            'League of Legends' => ['Subiendo de rango', 'Jugando con viewers', 'Meta actual'],
            'Grand Theft Auto V' => ['Roleplay en el servidor', 'Misiones con la banda', 'Carreras ilegales'],
            'FIFA 23' => ['Ultimate Team', 'Modo carrera', 'Partido final torneo'],
            'Among Us' => ['Partidas con amigos', 'Detective profesional', 'Impostor siempre'],
            'Valorant' => ['Ranked con el team', 'Nuevo agente', 'Torneo amateur'],
        ];

        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        // Crear streams en diferentes días
        for ($day = 0; $day < 20; $day++) {
            $streamDate = Carbon::now()->subDays(rand(0, 29));
            $gameName = array_rand($games);
            $titleOptions = $games[$gameName];
            $title = $titleOptions[array_rand($titleOptions)];
            
            $duration = rand(180, 480); // 3-8 horas en minutos
            $startTime = $streamDate->copy()->setTime(rand(12, 20), 0, 0); // Inicio entre 12:00 y 20:00
            $endTime = $startTime->copy()->addMinutes($duration);

            $viewerCount = rand(5000, 50000);
            $peakViewers = $viewerCount * rand(120, 150) / 100;
            $avgViewers = $viewerCount * rand(80, 95) / 100;
            $followersGained = rand(100, 1000);
            $chatMessages = $avgViewers * rand(5, 15);
            $tags = $this->generateRandomTags();
            
            TwitchStream::create([
                'social_profile_id' => $profile->id,
                'stream_id' => 'twitch-' . $profile->username . '-' . $streamDate->format('Ymd') . '-' . rand(1000, 9999),
                'title' => $title,
                'game_name' => $gameName,
                'stream_url' => "https://twitch.tv/{$profile->username}/videos/" . rand(1000000000, 9999999999),
                'thumbnail_url' => "https://via.placeholder.com/1280x720.png?text={$gameName}",
                'started_at' => $startTime,
                'ended_at' => $endTime,
                'duration_minutes' => $duration,
                'viewer_count' => $viewerCount,
                'peak_viewers' => $peakViewers,
                'average_viewers' => $avgViewers,
                'followers_gained' => $followersGained,
                'chat_messages' => $chatMessages,
                'is_mature' => (rand(0, 1) == 1),
                'language' => 'es',
                'tags' => $tags,
                'is_sponsored' => (rand(0, 10) > 8), // 20% de probabilidad de ser patrocinado
            ]);
        }
    }

    /**
     * Generate random tags for streams
     */
    private function generateRandomTags(): array
    {
        $possibleTags = [
            'español', 'castellano', 'gaming', 'variety', 'entertainer', 'casual',
            'competitive', 'funny', 'ranked', 'drops', 'tournament', 'esports',
            'chatty', 'chill', 'music', 'friendly', 'skill', 'charity'
        ];

        $numTags = rand(2, 5);
        shuffle($possibleTags);
        return array_slice($possibleTags, 0, $numTags);
    }
}