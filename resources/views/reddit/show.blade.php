<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Estadísticas de {{ $user['username'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'IBM Plex Sans', sans-serif;
        }
    </style>
</head>

<<<<<<< HEAD
        <div class="bg-white p-6 rounded-xl shadow-md"> 
=======
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-4 sm:p-6 max-w-2xl">
        <a href="{{ route('reddit.index') }}"
            class="text-[#0079D3] hover:text-[#FF4500] font-medium text-sm sm:text-base mb-4 inline-block transition-colors">
            ← Volver al listado
        </a>

        <div class="bg-white p-5 sm:p-6 rounded-lg shadow-sm">
>>>>>>> reddit
            <div class="flex items-center space-x-4 mb-6">
                @if($user['avatar'])
                <img src="{{ $user['avatar'] }}" alt="Avatar" class="w-12 h-12 sm:w-16 sm:h-16 rounded-full border border-gray-200">
                @endif
                <h1 class="text-2xl sm:text-3xl font-semibold text-[#FF4500]">u/{{ $user['username'] }}</h1>
            </div>

<<<<<<< HEAD
            <div class="grid grid-cols-2 gap-4 text-lg">
                <div><strong>Links:</strong> {{ $user['link_karma'] }}</div>
                <div><strong>Comentarios:</strong> {{ $user['comment_karma'] }}</div>
                <div><strong>Total:</strong> {{ $user['total_karma'] }}</div>
                <div><strong>Creado el:</strong> {{ $user['created_utc'] }}</div>
=======
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-base sm:text-lg">
                <div><strong class="text-gray-700">Karma (Links):</strong> {{ $user['link_karma'] }}</div>
                <div><strong class="text-gray-700">Karma (Comentarios):</strong> {{ $user['comment_karma'] }}</div>
                <div><strong class="text-gray-700">Karma Total:</strong> {{ $user['total_karma'] }}</div>
                <div><strong class="text-gray-700">Creado el:</strong> {{ $user['created_utc'] }}</div>
>>>>>>> reddit
            </div>
        </div>
    </div>
</body>

</html>