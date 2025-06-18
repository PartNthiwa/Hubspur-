<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shareholder Dashboard - Hubspur</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- If using Vite --}}
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Top Navbar -->
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold">Hubspur Shareholder Portal</h1>
            <a href="" class="text-blue-600 hover:underline">Back to Site</a>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="py-8">
        <div class="container mx-auto px-4">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-12 py-4 bg-white text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} Hubspur. All rights reserved.
    </footer>

</body>
</html>
