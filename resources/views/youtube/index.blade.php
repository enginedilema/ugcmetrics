<x-layouts.app :title="__('YouTube Metrics')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- Total de influencers con YouTube -->
            <div
                class="flex items-center justify-center aspect-video rounded-xl border border-red-300 bg-white shadow-lg dark:bg-neutral-900 dark:border-neutral-700 hover:shadow-xl transition-shadow duration-300">
                <div class="text-center space-y-2">
                    <p class="text-base font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wide">
                        YouTube Influencers
                    </p>
                    <p class="text-5xl font-extrabold text-red-600 dark:text-red-400">
                        {{ $influencers->filter(fn($i) => $i->socialProfiles->contains('platform.name', 'YouTube'))->count() }}
                    </p>
                    <div class="h-1 w-10 mx-auto bg-red-300 rounded-full"></div>
                </div>
            </div>

            <!-- Total de suscriptores en YouTube -->
            <div
                class="flex items-center justify-center aspect-video rounded-xl border border-red-300 bg-white shadow-lg dark:bg-neutral-900 dark:border-neutral-700 hover:shadow-xl transition-shadow duration-300">
                <div class="text-center space-y-2">
                    <p class="text-base font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wide">
                        Suscriptores Totales
                    </p>
                    <p class="text-5xl font-extrabold text-red-600 dark:text-red-400">
                        {{
                            $influencers->flatMap->socialProfiles
                                ->filter(fn($p) => $p->platform->name === 'YouTube')
                                ->sum('followers_count')
                        }}
                    </p>
                    <div class="h-1 w-10 mx-auto bg-red-300 rounded-full"></div>
                </div>
            </div>

            <!-- Total de visualizaciones -->
            <div
                class="flex items-center justify-center aspect-video rounded-xl border border-red-300 bg-white shadow-lg dark:bg-neutral-900 dark:border-neutral-700 hover:shadow-xl transition-shadow duration-300">
                <div class="text-center space-y-2">
                    <p class="text-base font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wide">
                        Visualizaciones Totales
                    </p>
                    <p class="text-5xl font-extrabold text-red-600 dark:text-red-400">
                        {{
                            $influencers->flatMap->socialProfiles
                                ->filter(fn($p) => $p->platform->name === 'YouTube')
                                ->sum('views_count')
                        }}
                    </p>
                    <div class="h-1 w-10 mx-auto bg-red-300 rounded-full"></div>
                </div>
            </div>
        </div>

        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-white mb-4">Listado de YouTubers</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-separate border-spacing-y-2">
                    <thead>
                        <tr class="text-neutral-600 dark:text-neutral-300">
                            <th class="py-3 px-4">Foto</th>
                            <th class="py-3 px-4">Nombre</th>
                            <th class="py-3 px-4">Canal</th>
                            <th class="py-3 px-4">Suscriptores</th>
                            <th class="py-3 px-4">Visualizaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($influencers as $influencer)
                            @php
                                $youtube = $influencer->socialProfiles->firstWhere('platform.name', 'YouTube');
                            @endphp
                            @if ($youtube)
                                <tr class="bg-white dark:bg-neutral-800 shadow-sm rounded-md">
                                    <td class="py-2 px-4">
                                        <div class="w-[50px] h-[50px]">
                                            <a href="{{route('youtube.show', $influencer->id)}}"
                                                class="w-full h-full rounded-full overflow-hidden">
                                                <img src="{{ 
                                                    !empty($influencer->profile_picture_url)
                                                        ? asset('storage/' . $influencer->profile_picture_url)
                                                        : ($youtube->profile_picture ?? asset('storage/images/placeholder-profile.png'))
                                                }}"
                                                    alt="Foto de {{ $influencer->name }}"
                                                    class="w-full h-full rounded-full object-cover">
                                            </a>
                                        </div>
                                    </td>
                                    <td class="py-2 px-4 font-medium text-neutral-800 dark:text-white">
                                        {{ $influencer->name }}
                                    </td>
                                    <td class="py-2 px-4">
                                        <a href="{{ $youtube->profile_url }}" target="_blank"
                                            class="text-indigo-600 hover:underline">
                                            {{ $youtube->platform->name }}
                                        </a>
                                    </td>
                                    <td class="py-2 px-4 text-neutral-700 dark:text-neutral-300">
                                        {{ number_format($youtube->followers_count) }}
                                    </td>
                                    <td class="py-2 px-4 text-neutral-700 dark:text-neutral-300">
                                        {{ number_format($youtube->views_count) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
