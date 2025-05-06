@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-purple-700">Perfiles de Twitch</h1>
        <div class="text-gray-600">
            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                {{ count($profiles) }} perfiles encontrados
            </span>
        </div>
    </div>

    @if(isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error:</strong>
            <span class="block sm:inline">{{ $error }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('info') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($profiles as $profile)
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                <div class="p-5">
                    <div class="flex items-center">
                        @if($profile->influencer->profile_picture_url)
                            <img src="{{ asset('storage/' . $profile->influencer->profile_picture_url) }}" alt="{{ $profile->username }}" 
                                class="w-16 h-16 rounded-full object-cover mr-4 border-2 border-purple-500">
                        @else
                            <div class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center mr-4 border-2 border-purple-500">
                                <span class="text-2xl text-purple-600">{{ substr($profile->username, 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <h2 class="text-xl font-semibold">{{ $profile->influencer->name }}</h2>
                            <a href="{{ route('twitch.show', $profile->username) }}" class="text-purple-600 hover:text-purple-800">
                                @{{ $profile->username }}
                            </a>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-2 rounded">
                            <span class="text-sm text-gray-500">Seguidores</span>
                            <p class="text-lg font-semibold">{{ number_format($profile->followers_count) }}</p>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <span class="text-sm text-gray-500">Engagement</span>
                            <p class="text-lg font-semibold">{{ number_format($profile->engagement_rate, 1) }}%</p>
                        </div>
                    </div>

                    @if(isset($reports[$profile->id]) && count($reports[$profile->id]) > 0)
                        @php
                            $latestReport = $reports[$profile->id]->first();
                        @endphp
                        <div class="mt-4">
                            <h3 class="text-sm font-medium text-gray-600 mb-2">Último reporte ({{ $latestReport->month }}/{{ $latestReport->year }})</h3>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div>
                                    <span class="text-gray-500">Avg. viewers</span>
                                    <p class="font-medium">{{ number_format($latestReport->average_viewers) }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Peak viewers</span>
                                    <p class="font-medium">{{ number_format($latestReport->peak_viewers) }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Streams/week</span>
                                    <p class="font-medium">{{ number_format($latestReport->streams_per_week, 1) }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Crecimiento</span>
                                    <p class="font-medium {{ $latestReport->growth_rate >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($latestReport->growth_rate, 1) }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('twitch.show', $profile->username) }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded transition-colors duration-300">
                            Ver análisis detallado
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection