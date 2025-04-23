<?php

namespace App\Http\Controllers;

use App\Models\InstagramPost;
use App\Models\InstagramReports;
use App\Models\Platform;
use App\Models\SocialProfile;
use Illuminate\Http\Request;

class InstagramGetInstagramData extends Controller
{
    //invoke
    public function __invoke(Request $request)
    {
        $forupdateInstagramAcount = SocialProfile::where('platform_id', Platform::where('name', 'Instagram')->first()->id)
            ->orderBy('last_updated', 'desc')
            ->where('username', 'ironpanda_fitness')
            ->first();

        $forupdateInstagramAcount->last_updated = now();
        //$forupdateInstagramAcount->save();
        $url = 'https://i.instagram.com/api/v1/users/web_profile_info/?username=' . $forupdateInstagramAcount->username;

        // Configurar los encabezados
        $headers = [
            "User-Agent: iphone_ua",
            "x-ig-app-id: 936619743392459"
        ];

        // Inicializar cURL
        $ch = curl_init();

        // Establecer la URL y los encabezados
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Para obtener la respuesta

        // Ejecutar la solicitud
        $response = curl_exec($ch);
        // Verificar si hubo un error
        if($response === false) {
            return response()->json(['error' => 'Error en la solicitud cURL: ' . curl_error($ch)], 500);
        }

        // Cerrar la sesión cURL
        curl_close($ch);
        // Decodificar la respuesta JSON
        $response = json_decode($response);
        $forupdateInstagramAcount->followers_count = $response->data->user->edge_followed_by->count;

        $instagramReport = InstagramReports::UpdateOrCreate([
            'social_profile_id' => $forupdateInstagramAcount->id,
            'year' => now()->year,
            'month' => now()->month,
        ]);
        if($instagramReport->followers_start == null){
            $instagramReport->followers_start = $forupdateInstagramAcount->followers_count;
        }
        $instagramReport->save();
        $postsNumber = $response->data->user->edge_owner_to_timeline_media->count;
        
        foreach($response->data->user->edge_owner_to_timeline_media->edges as $data){
            $instagramPost = InstagramPost::updateOrCreate(
                ['post_id' => $data['post_id']], // Buscar por el `post_id`
                [
                    'social_profile_id' => $data['social_profile_id'],
                    'shortcode' => $data['shortcode'],
                    'media_type' => $data['media_type'],
                    'caption' => $data['caption'],
                    'published_at' => isset($data['published_at']) ? $data['published_at'] : null,
                    'likes' => $data['likes'],
                    'comments' => $data['comments'],
                    'views' => $data['views'],
                    'engagement_rate' => $data['engagement_rate'],
                    'image_url' => $data['image_url'],
                    'video_url' => isset($data['video_url']) ? $data['video_url'] : null,
                    'owner_username' => $data['owner_username'],
                    'is_video' => $data['is_video'],
                    'tags' => isset($data['tags']) ? $data['tags'] : null,
                    'location' => isset($data['location']) ? $data['location'] : null,
                    'is_sponsored' => $data['is_sponsored'],
                    'comments_disabled' => $data['comments_disabled'],
                ]
            );
        }

        // Devolver la respuesta como JSON
        return response()->json(json_decode($response));
    }
}
