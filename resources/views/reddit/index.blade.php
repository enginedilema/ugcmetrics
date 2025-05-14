<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Influencers de Reddit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'IBM Plex Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-4 sm:p-6">
        <h1 class="text-2xl sm:text-3xl font-semibold text-center text-[#FF4500] mb-6">Influencers de Reddit</h1>

        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-6 max-w-2xl mx-auto">
            {{ session('error') }}
        </div>
        @endif

        <ul class="space-y-3 max-w-2xl mx-auto">
            @foreach($usernames as $username)
            <li>
                <a href="{{ route('reddit.show', $username) }}"
                    class="block bg-white rounded-lg shadow-sm hover:bg-[#FFF5F5] hover:shadow-md transition-all p-4 text-lg font-medium text-[#0079D3] hover:text-[#FF4500]">
                    u/{{ $username }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</body>

</html>