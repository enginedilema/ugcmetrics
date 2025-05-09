<x-layouts.app :title="__('Perfil de Twitch: ' . $profile->username)">
<div class="container mx-auto px-4 py-8">
    @if(session('info'))
        <div class="bg-blue-100 dark:bg-blue-900 border border-blue-400 dark:border-blue-700 text-blue-700 dark:text-blue-300 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('info') }}</span>
        </div>
    @endif
    
    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div class="flex items-center">
            <a href="{{ route('twitch.index') }}" class="mr-4 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-2 px-4 rounded inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver
            </a>
            <h1 class="text-xl md:text-3xl font-bold text-purple-700 dark:text-purple-400">Perfil de Twitch: {{ $profile->username }}</h1>
        </div>
        <a href="{{ route('twitch.fetch', ['username' => $profile->username]) }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded inline-flex items-center w-full md:w-auto justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Actualizar datos
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-8">
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col items-center text-center mb-4">
                    @php
                        $profile_picture = null;
                        if (isset($profile->extra_data['profile_image_url'])) {
                            $profile_picture = $profile->extra_data['profile_image_url'];
                        } elseif ($profile->influencer && $profile->influencer->profile_picture_url) {
                            $profile_picture = asset('storage/' . $profile->influencer->profile_picture_url);
                        }
                    @endphp
                    
                    @if($profile_picture)
                        <img src="{{ $profile_picture }}" class="w-32 h-32 rounded-full object-cover border-4 border-purple-500 mb-4" alt="{{ $profile->username }}">
                    @else
                        <div class="w-32 h-32 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center border-4 border-purple-500 mb-4">
                            <span class="text-4xl text-purple-600 dark:text-purple-400">{{ substr($profile->username, 0, 1) }}</span>
                        </div>
                    @endif
                    
                    @php
                        $display_name = $profile->username;
                        if (isset($profile->extra_data['display_name'])) {
                            $display_name = $profile->extra_data['display_name'];
                        } elseif ($profile->influencer && $profile->influencer->name) {
                            $display_name = $profile->influencer->name;
                        }
                    @endphp
                    
                    <h2 class="text-2xl font-semibold dark:text-white">{{ $display_name }}</h2>
                    <p class="text-purple-600 dark:text-purple-400">@{{ $profile->username }}</p>
                    
                    <div class="mt-2">
                        <a href="{{ $profile->profile_url ?? 'https://twitch.tv/'.$profile->username }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Perfil de Twitch
                        </a>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    @php
                        $description = null;
                        if (isset($profile->extra_data['description'])) {
                            $description = $profile->extra_data['description'];
                        } elseif ($profile->influencer && $profile->influencer->bio) {
                            $description = $profile->influencer->bio;
                        }
                    @endphp
                    
                    @if($description)
                        <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $description }}</p>
                    @endif
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-gray-50 dark:bg-zinc-700 p-3 rounded text-center">
                            <span class="block text-sm text-gray-500 dark:text-gray-400">Seguidores</span>
                            <p class="text-xl font-bold dark:text-white">{{ number_format($profile->followers_count ?? 0) }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-zinc-700 p-3 rounded text-center">
                            <span class="block text-sm text-gray-500 dark:text-gray-400">Vistas</span>
                            <p class="text-xl font-bold dark:text-white">{{ number_format($profile->extra_data['view_count'] ?? 0) }}</p>
                        </div>
                    </div>
                    
                    @if($profile->influencer && $profile->influencer->location)
                        <div class="mb-4">
                            <p class="flex items-center text-gray-700 dark:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $profile->influencer->location }}
                            </p>
                        </div>
                    @endif
                    
                    @if(!empty($profile->extra_data) && is_array($profile->extra_data))
                        <div class="flex flex-wrap gap-2">
                            @if(isset($profile->extra_data['verified']) && $profile->extra_data['verified'])
                                <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 text-xs px-2 py-1 rounded-full">Verificado</span>
                            @endif
                            @if(isset($profile->extra_data['partner']) && $profile->extra_data['partner'])
                                <span class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-300 text-xs px-2 py-1 rounded-full">Partner</span>
                            @endif
                            @if(isset($profile->extra_data['broadcaster_type']) && $profile->extra_data['broadcaster_type'] === 'affiliate')
                                <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-300 text-xs px-2 py-1 rounded-full">Affiliate</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold mb-4 dark:text-white">Estadísticas principales</h3>
                
                @if(isset($metrics) && count($metrics) > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        @php
                            $lastMetric = $metrics->first();
                            $firstMetric = $metrics->last();
                            
                            // Valores predeterminados o calculados cuando sea necesario
                            $avgViewers = isset($metrics) ? $metrics->avg('average_viewers') : 0;
                            $avgViewers = $avgViewers ?: 0;
                            
                            $totalHoursStreamed = isset($metrics) ? $metrics->sum('hours_streamed') : 0;
                            $totalHoursStreamed = $totalHoursStreamed ?: 0;
                            
                            $peakViewers = isset($metrics) ? $metrics->max('peak_viewers') : 0;
                            $peakViewers = $peakViewers ?: 0;
                            
                            $followers = isset($lastMetric->followers) ? $lastMetric->followers : ($profile->followers_count ?? 0);
                        @endphp
                        
                        <div class="text-center">
                            <span class="text-gray-500 dark:text-gray-400 text-sm">Seguidores</span>
                            <p class="text-2xl font-bold dark:text-white">{{ number_format($followers) }}</p>
                        </div>
                        
                        <div class="text-center">
                            <span class="text-gray-500 dark:text-gray-400 text-sm">Espectadores promedio</span>
                            <p class="text-2xl font-bold dark:text-white">{{ number_format($avgViewers) }}</p>
                        </div>
                        
                        <div class="text-center">
                            <span class="text-gray-500 dark:text-gray-400 text-sm">Horas emitidas</span>
                            <p class="text-2xl font-bold dark:text-white">{{ number_format($totalHoursStreamed) }}</p>
                        </div>
                        
                        <div class="text-center">
                            <span class="text-gray-500 dark:text-gray-400 text-sm">Pico de espectadores</span>
                            <p class="text-2xl font-bold dark:text-white">{{ number_format($peakViewers) }}</p>
                        </div>
                    </div>
                    
                    @if($lastMetric && $firstMetric && isset($lastMetric->followers) && isset($firstMetric->followers) && $lastMetric->followers != $firstMetric->followers)
                    <div class="mt-6">
                        <h4 class="text-lg font-medium mb-3 dark:text-white">Crecimiento de seguidores</h4>
                        <div class="bg-gray-50 dark:bg-zinc-700 p-4 rounded-lg">
                            @php
                                $growth = $lastMetric->followers - $firstMetric->followers;
                                $growthPercent = $firstMetric->followers > 0 ? 
                                    ($growth / $firstMetric->followers) * 100 : 0;
                            @endphp
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Inicial</p>
                                    <p class="text-xl font-bold dark:text-white">{{ number_format($firstMetric->followers) }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Cambio</p>
                                    <p class="text-xl font-bold {{ $growth >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $growth >= 0 ? '+' : '' }}{{ number_format($growth) }} ({{ number_format($growthPercent, 2) }}%)
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Actual</p>
                                    <p class="text-xl font-bold dark:text-white">{{ number_format($lastMetric->followers) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @else
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <span class="text-gray-500 dark:text-gray-400 text-sm">Seguidores</span>
                            <p class="text-2xl font-bold dark:text-white">{{ number_format($profile->followers_count ?? 0) }}</p>
                        </div>
                        
                        <div class="text-center">
                            <span class="text-gray-500 dark:text-gray-400 text-sm">Espectadores promedio</span>
                            <p class="text-2xl font-bold dark:text-white">--</p>
                        </div>
                        
                        <div class="text-center">
                            <span class="text-gray-500 dark:text-gray-400 text-sm">Horas emitidas</span>
                            <p class="text-2xl font-bold dark:text-white">--</p>
                        </div>
                        
                        <div class="text-center">
                            <span class="text-gray-500 dark:text-gray-400 text-sm">Pico de espectadores</span>
                            <p class="text-2xl font-bold dark:text-white">--</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 bg-gray-50 dark:bg-zinc-700 p-6 rounded-lg text-center">
                        <p class="text-gray-500 dark:text-gray-400">
                            No hay datos históricos disponibles. Haz clic en "Actualizar datos" para obtener las métricas más recientes.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700 mb-8">
        <h3 class="text-xl font-semibold mb-4 dark:text-white">Streams recientes</h3>
        
        @if(isset($streams) && count($streams) > 0)
            <div class="overflow-auto">
                <table class="min-w-full bg-white dark:bg-zinc-800">
                    <thead class="bg-gray-100 dark:bg-zinc-700">
                        <tr>
                            <th class="py-3 px-4 text-left dark:text-gray-300">Fecha</th>
                            <th class="py-3 px-4 text-left dark:text-gray-300">Título</th>
                            <th class="py-3 px-4 text-left dark:text-gray-300">Juego/Categoría</th>
                            <th class="py-3 px-4 text-right dark:text-gray-300">Duración</th>
                            <th class="py-3 px-4 text-right dark:text-gray-300">Espectadores</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($streams as $stream)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700">
                                <td class="py-3 px-4 dark:text-gray-300">
                                    @php
                                        $streamDate = $stream->started_at;
                                        if (is_string($streamDate)) {
                                            $streamDate = new \DateTime($streamDate);
                                        }
                                    @endphp
                                    {{ $streamDate->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3 px-4 max-w-xs truncate dark:text-gray-300">
                                    <a href="{{ $stream->stream_url ?? '#' }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $stream->title ?? 'Sin título' }}
                                    </a>
                                </td>
                                <td class="py-3 px-4 dark:text-gray-300">{{ $stream->game_name ?? 'Sin categoría' }}</td>
                                <td class="py-3 px-4 text-right dark:text-gray-300">
                                    @php
                                        $hours = floor(($stream->duration_minutes ?? 0) / 60);
                                        $minutes = ($stream->duration_minutes ?? 0) % 60;
                                    @endphp
                                    {{ $hours }}h {{ $minutes }}m
                                </td>
                                <td class="py-3 px-4 text-right dark:text-gray-300">
                                    {{ number_format($stream->peak_viewers ?? 0) }} pico / 
                                    {{ number_format($stream->average_viewers ?? 0) }} promedio
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6">
                {{ $streams->links() }}
            </div>
        @else
            <div class="bg-gray-50 dark:bg-zinc-700 p-6 rounded-lg text-center">
                <p class="text-gray-500 dark:text-gray-400">No hay streams disponibles para este perfil.</p>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Haz clic en "Actualizar datos" para obtener información reciente.</p>
            </div>
        @endif
    </div>
    
    @if(isset($reports) && count($reports) > 0)
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700 mb-8">
        <h3 class="text-xl font-semibold mb-4 dark:text-white">Reportes mensuales</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-zinc-800">
                <thead class="bg-gray-100 dark:bg-zinc-700">
                    <tr>
                        <th class="py-3 px-4 text-left dark:text-gray-300">Período</th>
                        <th class="py-3 px-4 text-right dark:text-gray-300">Seguidores</th>
                        <th class="py-3 px-4 text-right dark:text-gray-300">Crecimiento</th>
                        <th class="py-3 px-4 text-right dark:text-gray-300">Viewers promedio</th>
                        <th class="py-3 px-4 text-right dark:text-gray-300">Horas emitidas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($reports as $report)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700">
                            <td class="py-3 px-4 dark:text-gray-300">{{ $report->month }}/{{ $report->year }}</td>
                            <td class="py-3 px-4 text-right dark:text-gray-300">{{ number_format($report->followers_end ?? 0) }}</td>
                            <td class="py-3 px-4 text-right {{ ($report->growth_rate ?? 0) >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ ($report->growth_rate ?? 0) >= 0 ? '+' : '' }}{{ number_format(($report->growth_rate ?? 0), 2) }}%
                            </td>
                            <td class="py-3 px-4 text-right dark:text-gray-300">{{ number_format($report->average_viewers ?? 0) }}</td>
                            <td class="py-3 px-4 text-right dark:text-gray-300">{{ number_format($report->hours_streamed ?? 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    
    <div class="mt-8 text-center">
        <a href="{{ route('twitch.index') }}" class="inline-block bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-2 px-6 rounded">
            Volver al listado de perfiles
        </a>
    </div>
</div>
</x-layouts.app>