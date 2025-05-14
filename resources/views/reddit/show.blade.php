<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas de {{ $user['username'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="container mx-auto p-6 max-w-2xl">
        <a href="{{ route('reddit.index') }}" class="text-red-500 hover:underline mb-4 inline-block">← Volver al listado</a>

<<<<<<< HEAD
        <div class="bg-white p-6 rounded-xl shadow-md"> 
=======
        <div class="bg-white p-6 rounded-xl shadow-md">
>>>>>>> 63905473affdddb772661a9c1bf621167494a9b0
            <div class="flex items-center space-x-4 mb-6">
                @if($user['avatar'])
                    <img src="{{ $user['avatar'] }}" alt="Avatar" class="w-16 h-16 rounded-full border">
                @endif
                <h1 class="text-3xl font-bold text-red-700">{{ $user['username'] }}</h1>
            </div>

            <div class="grid grid-cols-2 gap-4 text-lg">
<<<<<<< HEAD
                <div><strong>Links:</strong> {{ $user['link_karma'] }}</div>
                <div><strong>Comentarios:</strong> {{ $user['comment_karma'] }}</div>
                <div><strong>Total:</strong> {{ $user['total_karma'] }}</div>
=======
                <div><strong>Karma (Links):</strong> {{ $user['link_karma'] }}</div>
                <div><strong>Karma (Comentarios):</strong> {{ $user['comment_karma'] }}</div>
                <div><strong>Karma Total:</strong> {{ $user['total_karma'] }}</div>
>>>>>>> 63905473affdddb772661a9c1bf621167494a9b0
                <div><strong>Creado el:</strong> {{ $user['created_utc'] }}</div>
            </div>
        </div>
    </div>
</body>
</html>
