<x-layouts.app :title="__('YouTube Channel Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Button para volver a la lista de canales -->
        <div class="flex items-center mb-4">
            <a href="{{ route('youtube.index') }}" class="inline-flex items-center text-sm font-medium text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver a la lista
            </a>
        </div>

        <!-- Header -->
        <div class="flex items-center gap-4 p-6 bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="w-24 h-24 rounded-full overflow-hidden">
                <img src="{{ $youtubeProfile->profile_picture ?? asset('storage/images/placeholder-profile.png') }}"
                    alt="Profile picture of {{ $influencer->name }}"
                    class="w-full h-full object-cover">
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-neutral-800 dark:text-white">{{ $influencer->name }}</h1>
                <a href="{{ $youtubeProfile->profile_url }}" target="_blank" 
                    class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                    {{ $youtubeProfile->username }}
                </a>
            </div>
        </div>

        <!-- Metricas -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="flex items-center justify-center aspect-video rounded-xl border border-red-300 bg-white shadow-lg dark:bg-neutral-900 dark:border-neutral-700">
                <div class="text-center space-y-2">
                    <p class="text-base font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wide">
                        Suscriptores
                    </p>
                    <p class="text-4xl font-extrabold text-red-600 dark:text-red-400">
                        {{ number_format($youtubeProfile->followers_count) }}
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-center aspect-video rounded-xl border border-red-300 bg-white shadow-lg dark:bg-neutral-900 dark:border-neutral-700">
                <div class="text-center space-y-2">
                    <p class="text-base font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wide">
                        Visualizaciones
                    </p>
                    <p class="text-4xl font-extrabold text-red-600 dark:text-red-400">
                        {{ number_format($youtubeProfile->views_count) }}
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-center aspect-video rounded-xl border border-red-300 bg-white shadow-lg dark:bg-neutral-900 dark:border-neutral-700">
                <div class="text-center space-y-2">
                    <p class="text-base font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wide">
                        Videos
                    </p>
                    <p class="text-4xl font-extrabold text-red-600 dark:text-red-400">
                        {{ number_format($latestMetrics->video_count ?? 0) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
