@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if(session('info'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('info') }}</span>
        </div>
    @endif
    
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center">
            <a href="{{ route('twitch.index') }}" class="mr-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver
            </a>
            <h1 class="text-3xl font-bold text-purple-700">Perfil de Twitch: {{ $profile->username }}</h1>
        </div>
        <a href="{{ route('twitch.fetch', $profile->username) }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Actualizar datos
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-8">
        <!-- Tarjeta de perfil -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <div class="flex flex-col items-center text-center mb-4">
                    @if($profile->influencer->profile_picture_url)
                        <img src="{{ asset('storage/' . $profile->influencer->profile_picture_url) }}" class="w-32 h-32 rounded-full object-cover border-4 border-purple-500 mb-4" alt="{{ $profile->username }}">
                    @else
                        <div class="w-32 h-32 rounded-full bg-purple-100 flex items-center justify-center border-4 border-purple-500 mb-4">
                            <span class="text-4xl text-purple-600">{{ substr($profile->username, 0, 1) }}</span>
                        </div>
                    @endif
                    <h2 class="text-2xl font-semibold">{{ $profile->influencer->name }}</h2>
                    <p class="text-purple-600">@{{ $profile->username }}</p>
                    
                    <div class="mt-2">
                        <a href="{{ $profile->profile_url }}" target="_blank" class="text-blue-600 hover:underline inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Perfil de Twitch
                        </a>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4">
                    <p class="text-gray-700 mb-4">{{ $profile->influencer->bio }}</p>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-gray-50 p-3 rounded text-center">
                            <span class="block text-sm text-gray-500">Seguidores</span>
                            <p class="text-xl font-bold">{{ number_format($profile->followers_count) }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded text-center">
                            <span class="block text-sm text-gray-500">Engagement</span>
                            <p class="text-xl font-bold">{{ number_format($profile->engagement_rate, 1) }}%</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="flex items-center text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $profile->influencer->location }}
                        </p>
                    </div>
                    
                    @if(!empty($profile->extra_data) && is_array($profile->extra_data))
                        <div class="flex flex-wrap gap-2">
                            @if(isset($profile->extra_data['verified']) && $profile->extra_data['verified'])
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Verificado</span>
                            @endif
                            @if(isset($profile->extra_data['partner']) && $profile->extra_data['partner'])
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">Partner</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Estadísticas clave -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <h3 class="text-xl font-semibold mb-4">Estadísticas clave de los últimos 30 días</h3>
                
                @if(count($metrics) > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        @php
                            $lastMetric = $metrics->first();
                            $firstMetric = $metrics->last();
                            
                            // Calcular promedios
                            $avgViewers = $metrics->avg('average_viewers');
                            $totalHoursStreamed = $metrics->sum('hours_streamed');
                            $totalStreamCount = $metrics->sum('stream_count');
                            $peakViewers = $metrics->max('peak_viewers');
                        @endphp
                        
                        <div class="text-center">
                            <span class="text-gray-500 text-sm">Crecimiento de seguidores</span>
                            @if($firstMetric && $lastMetric)
                                @php
                                    $growth = $lastMetric->followers - $firstMetric->followers;
                                    $growthPercent = $firstMetric->followers > 0 ? 
                                        ($growth / $firstMetric->followers) * 100 : 0;
                                @endphp
                                <p class="text-2xl font-bold {{ $growth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $growth >= 0 ? '+' : '' }}{{ number_format($growth) }}
                                </p>
                                <p class="text-sm {{ $growth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $growth >= 0 ? '+' : '' }}{{ number_format($growthPercent, 2) }}%
                                </p>
                            @else
                                <p class="text-2xl font-bold">N/A</p>
                            @endif
                        </div>
                        
                        <div class="text-center">
                            <span class="text-gray-500 text-sm">Espectadores promedio</span>
                            <p class="text-2xl font-bold">{{ number_format($avgViewers) }}</p>
                        </div>
                        
                        <div class="text-center">
                            <span class="text-gray-500 text-sm">Horas emitidas</span>
                            <p class="text-2xl font-bold">{{ number_format($totalHoursStreamed) }}</p>
                        </div>
                        
                        <div class="text-center">
                            <span class="text-gray-500 text-sm">Pico de espectadores</span>
                            <p class="text-2xl font-bold">{{ number_format($peakViewers) }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h4 class="text-lg font-medium mb-3">Últimos 30 días de actividad</h4>
                        <div class="aspect-w-16 aspect-h-6 bg-gray-50 p-4 rounded-lg">
                            <!-- Aquí iría un gráfico de métricas diarias -->
                            <div class="flex items-center justify-center h-full">
                                <p class="text-gray-500 text-center">
                                    [Gráfico con datos diarios de espectadores y seguidores]
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 p-6 rounded-lg text-center">
                        <p class="text-gray-500">No hay métricas disponibles para este perfil.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Streams recientes -->
    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 mb-8">
        <h3 class="text-xl font-semibold mb-4">Streams recientes</h3>
        
        @if(count($streams) > 0)
            <div class="overflow-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left">Fecha</th>
                            <th class="py-3 px-4 text-left">Título</th>
                            <th class="py-3 px-4 text-left">Juego/Categoría</th>
                            <th class="py-3 px-4 text-right">Duración</th>
                            <th class="py-3 px-4 text-right">Espectadores pico</th>
                            <th class="py-3 px-4 text-right">Espectadores promedio</th>
                            <th class="py-3 px-4 text-right">Seguidores ganados</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($streams as $stream)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    {{ $stream->started_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3 px-4 max-w-xs truncate">
                                    <a href="{{ $stream->stream_url }}" target="_blank" class="text-blue-600 hover:underline">
                                        {{ $stream->title }}
                                    </a>
                                </td>
                                <td class="py-3 px-4">{{ $stream->game_name }}</td>
                                <td class="py-3 px-4 text-right">
                                    {{ floor($stream->duration_minutes / 60) }}h {{ $stream->duration_minutes % 60 }}m
                                </td>
                                <td class="py-3 px-4 text-right">{{ number_format($stream->peak_viewers) }}</td>
                                <td class="py-3 px-4 text-right">{{ number_format($stream->average_viewers) }}</td>
                                <td class="py-3 px-4 text-right {{ $stream->followers_gained > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $stream->followers_gained > 0 ? '+' : '' }}{{ number_format($stream->followers_gained) }}
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
            <div class="bg-gray-50 p-6 rounded-lg text-center">
                <p class="text-gray-500">No hay streams disponibles para este perfil.</p>
            </div>
        @endif
    </div>
    
    <!-- Reportes mensuales -->
    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 mb-8">
        <h3 class="text-xl font-semibold mb-4">Reportes mensuales</h3>
        
        @if(count($reports) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left">Período</th>
                            <th class="py-3 px-4 text-right">Seguidores inicio</th>
                            <th class="py-3 px-4 text-right">Seguidores fin</th>
                            <th class="py-3 px-4 text-right">Crecimiento</th>
                            <th class="py-3 px-4 text-right">Subs promedio</th>
                            <th class="py-3 px-4 text-right">Viewers promedio</th>
                            <th class="py-3 px-4 text-right">Viewers pico</th>
                            <th class="py-3 px-4 text-right">Horas emitidas</th>
                            <th class="py-3 px-4 text-right">Streams/semana</th>
                            <th class="py-3 px-4 text-right">Valor patrocinio</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($reports as $report)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $report->month }}/{{ $report->year }}</td>
                                <td class="py-3 px-4 text-right">{{ number_format($report->followers_start) }}</td>
                                <td class="py-3 px-4 text-right">{{ number_format($report->followers_end) }}</td>
                                <td class="py-3 px-4 text-right {{ $report->growth_rate >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $report->growth_rate >= 0 ? '+' : '' }}{{ number_format($report->growth_rate, 2) }}%
                                </td>
                                <td class="py-3 px-4 text-right">{{ number_format($report->subscribers_average) }}</td>
                                <td class="py-3 px-4 text-right">{{ number_format($report->average_viewers) }}</td>
                                <td class="py-3 px-4 text-right">{{ number_format($report->peak_viewers) }}</td>
                                <td class="py-3 px-4 text-right">{{ number_format($report->hours_streamed) }}</td>
                                <td class="py-3 px-4 text-right">{{ number_format($report->streams_per_week, 1) }}</td>
                                <td class="py-3 px-4 text-right">
                                    ${{ number_format($report->estimated_sponsor_value_min) }} - ${{ number_format($report->estimated_sponsor_value_max) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Categorías más usadas -->
            @if($reports->count() > 0 && $reports->first()->top_categories)
                <div class="mt-6">
                    <h4 class="text-lg font-medium mb-3">Categorías más usadas (último reporte)</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($reports->first()->top_categories as $category => $count)
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                                {{ $category }} ({{ $count }})
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            <div class="bg-gray-50 p-6 rounded-lg text-center">
                <p class="text-gray-500">No hay reportes disponibles para este perfil.</p>
            </div>
        @endif
    </div>
    
    <!-- Valoración financiera -->
    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
        <h3 class="text-xl font-semibold mb-4">Valoración financiera estimada</h3>
        
        @if(count($reports) > 0)
            @php
                $latestReport = $reports->first();
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h4 class="font-medium mb-2">Ingresos mensuales estimados</h4>
                    <p class="text-2xl font-bold">${{ number_format(($latestReport->estimated_monthly_revenue_min + $latestReport->estimated_monthly_revenue_max) / 2) }}</p>
                    <p class="text-sm text-gray-500">
                        Rango: ${{ number_format($latestReport->estimated_monthly_revenue_min) }} - ${{ number_format($latestReport->estimated_monthly_revenue_max) }}
                    </p>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h4 class="font-medium mb-2">Valor de patrocinio por stream</h4>
                    <p class="text-2xl font-bold">${{ number_format($latestReport->estimated_sponsor_value_optimal) }}</p>
                    <p class="text-sm text-gray-500">
                        Rango: ${{ number_format($latestReport->estimated_sponsor_value_min) }} - ${{ number_format($latestReport->estimated_sponsor_value_max) }}
                    </p>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h4 class="font-medium mb-2">Métricas clave de monetización</h4>
                    <div class="grid grid-cols-2 gap-2 mt-2">
                        <div>
                            <p class="text-sm text-gray-500">Subs promedio</p>
                            <p class="font-medium">{{ number_format($latestReport->subscribers_average) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Engagement</p>
                            <p class="font-medium">{{ number_format($latestReport->chat_engagement, 2) }} msg/min</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 text-sm text-gray-500">
                <p>* Estas estimaciones se basan en métricas públicas y comportamientos promedio de la industria.</p>
                <p>* Los ingresos reales pueden variar según acuerdos comerciales específicos, donaciones y otras fuentes de ingresos.</p>
            </div>
        @else
            <div class="bg-gray-50 p-6 rounded-lg text-center">
                <p class="text-gray-500">No hay suficientes datos para estimar la valoración financiera.</p>
            </div>
        @endif
    </div>
</div>
@endsection