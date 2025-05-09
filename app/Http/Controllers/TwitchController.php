<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Models\SocialProfile;
use App\Models\TwitchMetrics;
use App\Models\TwitchReports;
use App\Models\TwitchStream;
use Illuminate\Http\Request;

class TwitchController extends Controller
{
    /**
     * Display a listing of Twitch profiles.
     */
    public function index()
{
    // Obtener la plataforma de Twitch
    $platform = Platform::where('name', 'Twitch')->first();

    if (!$platform) {
        return view('twitch.index', ['error' => 'Plataforma Twitch no encontrada']);
    }

    // Obtener perfiles de Twitch con sus métricas y streams
    $profiles = SocialProfile::where('platform_id', $platform->id)
        ->with(['influencer', 'twitchMetrics' => function($query) {
            $query->orderBy('date', 'desc')->take(30);
        }, 'twitchStreams' => function($query) {
            $query->orderBy('started_at', 'desc')->take(10);
        }])
        ->get();

    // Obtener reportes mensuales si existen
    $reports = TwitchReports::whereIn('social_profile_id', $profiles->pluck('id'))
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get()
        ->groupBy('social_profile_id');

    // Obtener influencers únicos asociados a los perfiles
    $influencers = $profiles->pluck('influencer')->unique('id');

    return view('twitch.index', compact('profiles', 'reports', 'influencers'));
}

    /**
     * Display detailed information for a specific Twitch profile.
     */
    public function show($username)
    {
        // Obtener la plataforma de Twitch
        $platform = Platform::where('name', 'Twitch')->first();

        if (!$platform) {
            return redirect()->route('twitch.index')->with('error', 'Plataforma Twitch no encontrada');
        }

        // Buscar el perfil por nombre de usuario
        $profile = SocialProfile::where('platform_id', $platform->id)
            ->where('username', $username)
            ->with('influencer')
            ->first();

        if (!$profile) {
            return redirect()->route('twitch.index')->with('error', 'Perfil no encontrado');
        }

        // Obtener métricas ordenadas por fecha
        $metrics = TwitchMetrics::where('social_profile_id', $profile->id)
            ->orderBy('date', 'desc')
            ->get();

        // Obtener streams ordenados por fecha
        $streams = TwitchStream::where('social_profile_id', $profile->id)
            ->orderBy('started_at', 'desc')
            ->paginate(15);

        // Obtener reportes mensuales
        $reports = TwitchReports::where('social_profile_id', $profile->id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('twitch.show', compact('profile', 'metrics', 'streams', 'reports'));
    }

    /**
     * Fetch data from Twitch API for a specific profile.
     */
    public function fetchData($username)
    {
        try {
            $clientId = env('TWITCH_CLIENT_ID');
            $accessToken = env('TWITCH_ACCESS_TOKEN');

            if (!$clientId || !$accessToken) {
                return redirect()->route('twitch.index')
                    ->with('error', 'Faltan las credenciales de la API de Twitch en el archivo .env');
            }

            $headers = [
                'Authorization: Bearer ' . $accessToken,
                'Client-Id: ' . $clientId,
            ];

            // Función para realizar peticiones cURL
            $performCurlRequest = function($url, $headers) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                
                curl_setopt($ch, CURLOPT_VERBOSE, true);
                $verbose = fopen('php://temp', 'w+');
                curl_setopt($ch, CURLOPT_STDERR, $verbose);
                
                $response = curl_exec($ch);
                
                if (curl_errno($ch)) {
                    rewind($verbose);
                    $verboseLog = stream_get_contents($verbose);
                    $error = 'Error cURL: ' . curl_error($ch) . "\nDetalles de la petición: " . $verboseLog;
                    curl_close($ch);
                    throw new \Exception($error);
                }
                
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($httpCode >= 400) {
                    rewind($verbose);
                    $verboseLog = stream_get_contents($verbose);
                    $error = "Error HTTP $httpCode: " . $response . "\nDetalles de la petición: " . $verboseLog;
                    curl_close($ch);
                    throw new \Exception($error);
                }
                
                curl_close($ch);
                return json_decode($response, true);
            };

            // 1. Obtener información del usuario
            try {
                $urlUser = 'https://api.twitch.tv/helix/users?login=' . urlencode($username);
                $userInfo = $performCurlRequest($urlUser, $headers);
                
                if (empty($userInfo['data'][0]['id'])) {
                    return redirect()->route('twitch.index')
                        ->with('error', 'No se ha encontrado el usuario de Twitch: ' . $username);
                }
                
                $broadcasterId = $userInfo['data'][0]['id'];
                $userData = $userInfo['data'][0];
                
            } catch (\Exception $e) {
                throw new \Exception('Error al obtener información del usuario: ' . $e->getMessage());
            }

            // 2. Obtener información del canal
            try {
                $urlChannel = 'https://api.twitch.tv/helix/channels?broadcaster_id=' . $broadcasterId;
                $channelInfo = $performCurlRequest($urlChannel, $headers);
                $channelData = $channelInfo['data'][0] ?? null;
            } catch (\Exception $e) {
                throw new \Exception('Error al obtener información del canal: ' . $e->getMessage());
            }

            // 3. Obtener estadísticas de seguidores
            try {
                $urlFollowers = 'https://api.twitch.tv/helix/channels/followers?broadcaster_id=' . $broadcasterId;
                $followersInfo = $performCurlRequest($urlFollowers, $headers);
                
                session()->flash('api_debug', json_encode($followersInfo, JSON_PRETTY_PRINT));
                
                $totalFollowers = 0;
                
                if (isset($followersInfo['total'])) {
                    $totalFollowers = (int) $followersInfo['total'];
                } elseif (isset($followersInfo['data']) && isset($followersInfo['data']['total'])) {
                    $totalFollowers = (int) $followersInfo['data']['total'];
                } elseif (isset($followersInfo['total_count'])) {
                    $totalFollowers = (int) $followersInfo['total_count'];
                } else if (isset($followersInfo['data']) && is_array($followersInfo['data'])) {
                    $totalFollowers = count($followersInfo['data']);
                }
            } catch (\Exception $e) {
                session()->flash('api_error', "Error al obtener seguidores: " . $e->getMessage());
                $totalFollowers = 0;
            }

            // 4. Verificar si el streamer está en directo
            try {
                $urlStream = 'https://api.twitch.tv/helix/streams?user_id=' . $broadcasterId;
                $streamInfo = $performCurlRequest($urlStream, $headers);
                $isLive = !empty($streamInfo['data']);
                $streamData = $isLive ? $streamInfo['data'][0] : null;
                
                if ($isLive) {
                    session()->flash('stream_info', 'El streamer está en directo con ' . ($streamData['viewer_count'] ?? '0') . ' espectadores');
                }
            } catch (\Exception $e) {
                session()->flash('api_error', "Error al verificar stream: " . $e->getMessage());
                $isLive = false;
                $streamData = null;
            }
            
            // 5. Obtener videos para calcular horas emitidas
            $hoursStreamed = 0;
            $streamCount = 0;
            try {
                $urlVideos = 'https://api.twitch.tv/helix/videos?user_id=' . $broadcasterId . '&type=archive&first=10';
                $videosInfo = $performCurlRequest($urlVideos, $headers);
                
                if (!empty($videosInfo['data'])) {
                    foreach ($videosInfo['data'] as $video) {
                        if (isset($video['duration'])) {
                            $duration = $video['duration'];
                            $hours = 0;
                            $minutes = 0;
                            $seconds = 0;
                            
                            if (preg_match('/(\d+)h/', $duration, $matches)) {
                                $hours = intval($matches[1]);
                            }
                            if (preg_match('/(\d+)m/', $duration, $matches)) {
                                $minutes = intval($matches[1]);
                            }
                            if (preg_match('/(\d+)s/', $duration, $matches)) {
                                $seconds = intval($matches[1]);
                            }
                            
                            $hoursStreamed += $hours + ($minutes / 60) + ($seconds / 3600);
                            $streamCount++;
                        }
                    }
                }
            } catch (\Exception $e) {
                session()->flash('api_error', session()->get('api_error') . "\nError al obtener videos: " . $e->getMessage());
            }
            
            // Calcular métricas
            $peakViewers = $streamData['viewer_count'] ?? 0;
            $averageViewers = $streamData['viewer_count'] ?? 0;
            $chatMessages = 0; 
            $subscribers = 0; 
            
            if (!$isLive && !empty($videosInfo['data'])) {
                $totalViewers = 0;
                $videoCount = 0;
                $maxViewers = 0;
                
                foreach ($videosInfo['data'] as $video) {
                    if (isset($video['view_count'])) {
                        $totalViewers += $video['view_count'];
                        $videoCount++;
                        $maxViewers = max($maxViewers, $video['view_count']);
                    }
                }
                
                if ($videoCount > 0) {
                    $averageViewers = $totalViewers / $videoCount;
                    $peakViewers = $maxViewers;
                }
            }
            
            // Buscar o crear perfil
            $platform = Platform::where('name', 'Twitch')->first();
            
            if (!$platform) {
                $platform = Platform::create([
                    'name' => 'Twitch',
                    'icon' => 'fab fa-twitch',
                    'color' => '#6441a5'
                ]);
            }
            
            $profile = SocialProfile::firstOrNew([
                'platform_id' => $platform->id,
                'username' => $username
            ]);
            
            if (!$profile->exists && !$profile->influencer_id) {
                $influencer = \App\Models\Influencer::first();
                if ($influencer) {
                    $profile->influencer_id = $influencer->id;
                } else {
                    session()->flash('api_error', session()->get('api_error') . "\nNo se encontró un influencer para asociar al perfil de Twitch: $username");
                }
            }
            
            // Actualizar datos del perfil
            $profile->followers_count = $totalFollowers;
            $profile->profile_url = 'https://twitch.tv/' . $username;
            $profile->profile_picture = $userData['profile_image_url'] ?? null;
            $profile->last_updated = now();
            
            if (!isset($profile->engagement_rate)) {
                $profile->engagement_rate = 0;
            }
            
            session()->flash('profile_info', "Guardando perfil con {$totalFollowers} seguidores");
            
            // Almacenar datos adicionales
            $extraData = $profile->extra_data ?: [];
            $extraData['display_name'] = $userData['display_name'] ?? $username;
            $extraData['description'] = $userData['description'] ?? '';
            $extraData['view_count'] = $userData['view_count'] ?? 0;
            $extraData['broadcaster_type'] = $userData['broadcaster_type'] ?? '';
            $extraData['is_partner'] = $userData['broadcaster_type'] === 'partner';
            $extraData['is_affiliate'] = $userData['broadcaster_type'] === 'affiliate';
            if ($channelData) {
                $extraData['language'] = $channelData['broadcaster_language'] ?? null;
                $extraData['category'] = $channelData['game_name'] ?? null;
            }
            $profile->extra_data = $extraData;
            
            $profile->save();

            // Guardar métricas
            $metrics = new TwitchMetrics([
                'social_profile_id' => $profile->id,
                'date' => now(),
                'followers' => $totalFollowers,
                'subscribers' => $subscribers,
                'average_viewers' => $averageViewers,
                'peak_viewers' => $peakViewers,
                'hours_streamed' => $hoursStreamed,
                'stream_count' => $streamCount,
                'chat_messages' => $chatMessages
            ]);
            
            session()->flash('metrics_info', "Guardando métricas: {$totalFollowers} seguidores, {$averageViewers} espectadores promedio");
            
            $metrics->save();

            // Guardar información del stream si está en directo
            if ($isLive && $streamData) {
                $stream = new TwitchStream([
                    'social_profile_id' => $profile->id,
                    'stream_id' => $streamData['id'],
                    'title' => $streamData['title'],
                    'game_name' => $streamData['game_name'],
                    'viewer_count' => $streamData['viewer_count'],
                    'started_at' => date('Y-m-d H:i:s', strtotime($streamData['started_at'])),
                    'language' => $streamData['language'],
                    'thumbnail_url' => $streamData['thumbnail_url'],
                    'is_mature' => $streamData['is_mature'] ?? false,
                ]);
                $stream->save();
            }

            return redirect()->route('twitch.show', $username)
                ->with('success', 'Datos actualizados correctamente desde Twitch.');
            
        } catch (\Exception $e) {
            return redirect()->route('twitch.show', $username)
                ->with('error', 'Ocurrió un error al obtener los datos: ' . $e->getMessage());
        }
    }
}
