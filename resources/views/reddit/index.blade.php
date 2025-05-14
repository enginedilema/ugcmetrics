<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Influencers de Reddit</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-center text-red-600 mb-6">Influencers de Reddit</h1>

        @if(session('error'))
            <div class="bg-red-200 text-red-800 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <ul class="space-y-4 max-w-xl mx-auto">
            @foreach($usernames as $username)
                <li>
                    <a href="{{ route('reddit.show', $username) }}" 
                       class="block bg-white rounded-lg shadow hover:bg-red-100 transition p-4 text-xl font-medium text-red-700">
                        {{ $username }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</body>
</html>
