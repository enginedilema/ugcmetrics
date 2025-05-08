<x-layouts.app :title="__('Detalles del Perfil de Reddit')">
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('reddit.index') }}" class="text-purple-600 dark:text-purple-400 hover:underline">&larr; Volver a perfiles</a>
    </div>

    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center mb-4">
            <div class="w-16 h-16 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center mr-4 border-2 border-purple-500">
                <span class="text-2xl text-purple-600 dark:text-purple-400">
                    {{ strtoupper(substr($metric->socialProfile->username, 0, 1)) }}
                </span>
            </div>
            <div>
                <h1 class="text-2xl font-bold dark:text-white">{{ $metric->socialProfile->name }}</h1>
                <p class="text-purple-600 dark:text-purple-400">u/{{ $metric->socialProfile->username }}</p>
                <p class="text-gray-500 dark:text-gray-400">{{ $metric->date->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-50 dark:bg-zinc-700 p-4 rounded">
                <span class="text-sm text-gray-500 dark:text-gray-400">Seguidores</span>
                <p class="text-xl font-semibold dark:text-white">{{ number_format($metric->followers) }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-zinc-700 p-4 rounded">
                <span class="text-sm text-gray-500 dark:text-gray-400">Posts</span>
                <p class="text-xl font-semibold dark:text-white">{{ number_format($metric->posts_count) }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-zinc-700 p-4 rounded">
                <span class="text-sm text-gray-500 dark:text-gray-400">Comentarios</span>
                <p class="text-xl font-semibold dark:text-white">{{ number_format($metric->comments_count) }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-zinc-700 p-4 rounded">
                <span class="text-sm text-gray-500 dark:text-gray-400">Upvotes totales</span>
                <p class="text-xl font-semibold dark:text-white">{{ number_format($metric->upvotes_count) }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-zinc-700 p-4 rounded col-span-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">Rátion de interacción (Comentarios/Upvotes)</span>
                <p class="text-xl font-semibold dark:text-white">{{ $metric->interaction_ratio }}</p>
            </div>
        </div>

        <div class="mt-4">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-white mb-2">Top Post</h2>
            <p class="font-medium text-gray-800 dark:text-gray-200">"{{ $metric->top_post_title }}"</p>
            <span class="text-sm text-gray-500 dark:text-gray-400">Upvotes: {{ number_format($metric->top_post_upvotes) }}</span>
        </div>
    </div>
</div>
</x-layouts.app>
