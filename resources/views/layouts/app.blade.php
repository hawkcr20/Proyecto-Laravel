<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Proyecto Laravel')</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-white shadow px-8 py-4 flex justify-between">
        <a href="/" class="font-bold text-xl text-blue-700">Proyecto Laravel</a>

        <div class="space-x-4">
            <a href="/clases" class="text-gray-700 hover:text-blue-700">Clases</a>
            <a href="/reservas" class="text-gray-700 hover:text-blue-700">Reservas</a>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto mt-8 bg-white p-6 rounded shadow">
        @yield('content')
    </main>

</body>
</html>