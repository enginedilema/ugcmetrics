<?php

namespace Database\Seeders;

use App\Models\Influencer;
use App\Models\Platform;
use App\Models\SocialProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TwitchInfluencersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Recupera o crea la plataforma Twitch
        $platform = Platform::firstOrCreate(
            ['name' => 'Twitch'],
            [
                'description' => 'Twitch is a live streaming platform for gamers and content creators.',
                'base_url'    => 'https://www.twitch.tv/'
            ]
        );

        // 2. Define los usuarios de Twitch y sus URLs de avatar
        $twitchUsers = [
            [
                'username' => 'ibai',
                'avatar'   => 'https://static-cdn.jtvnw.net/jtv_user_pictures/574228be-01ef-4eab-b43e-651e6b0877b5-profile_image-300x300.png'
            ],
            [
                'username' => 'auronplay',
                'avatar'   => 'https://static-cdn.jtvnw.net/jtv_user_pictures/7c5a8ea3-dba3-4b7f-8a55-b26b4c6a284b-profile_image-300x300.png'
            ],
            // Añade aquí más influencers de Twitch...
        ];

        foreach ($twitchUsers as $user) {
            // Verificar si el influencer ya existe por username
            $influencer = Influencer::where('username', $user['username'])->first();
            
            // Si no existe, crearlo
            if (!$influencer) {
                $influencer = Influencer::create([
                    'name'     => ucfirst($user['username']),
                    'username' => $user['username'],
                    'bio'      => '',  // Ajusta si quieres una biografía
                    // 'profile_picture_url' => '' // Lo podrías rellenar tras descargar la imagen
                ]);
            }

            // 3b. Descarga y guarda el avatar usando Laravel HTTP Client
            $response = Http::withHeaders([
                'User-Agent' => 'ugcmetrics-bot/1.0'
            ])->get($user['avatar']);

            if ($response->successful()) {
                $path = "images/{$user['username']}.png";
                Storage::disk('public')->put($path, $response->body());
                // Si tu modelo Influencer tiene la propiedad, guárdalo:
                // $influencer->profile_picture_url = $path;
                // $influencer->save();
            } else {
                $this->command->warn("No se pudo descargar avatar de {$user['username']}: HTTP {$response->status()}");
            }

            // Verificar si ya existe un perfil social para este influencer y esta plataforma
            $socialProfile = SocialProfile::where('influencer_id', $influencer->id)
                                         ->where('platform_id', $platform->id)
                                         ->first();
            
            // Solo crear el perfil social si no existe
            if (!$socialProfile) {
                SocialProfile::create([
                    'influencer_id' => $influencer->id,
                    'platform_id'   => $platform->id,
                    'username'      => $user['username'],
                    'profile_url'   => "https://www.twitch.tv/{$user['username']}",
                ]);
            }

            $this->command->info("Created/Updated Twitch influencer: {$user['username']}");
        }
    }
}
