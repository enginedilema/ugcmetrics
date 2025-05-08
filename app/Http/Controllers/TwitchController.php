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
     * This is a demonstration method that would integrate with Twitch API
     */
    public function fetchData($username)
    {
        // Aquí implementaríamos la lógica para obtener datos reales de la API de Twitch
        // Por ahora, solo redirigimos con un mensaje informativo

        return redirect()->route('twitch.show', $username)
            ->with('info', 'En un entorno de producción, este método obtendría datos actualizados desde la API de Twitch.');
    }
}
