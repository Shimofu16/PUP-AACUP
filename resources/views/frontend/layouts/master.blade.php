<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/PUP-LOGO.png') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --color-maroon-50: #fdf2f3;
            --color-maroon-100: #f9e6e8;
            --color-maroon-200: #f2bfc5;
            --color-maroon-300: #eb99a1;
            --color-maroon-400: #dd4d5a;
            --color-maroon-500: #870014;
            --color-maroon-600: #7a0012;
            --color-maroon-700: #66000f;
            --color-maroon-800: #51000c;
            --color-maroon-900: #43000a;
        }

        @layer base {
            body {
                font-family: 'Noto Sans', sans-serif;
            }
        }
    </style>
</head>

<body class="nato-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <main>
            {{-- nav --}}
            @include('frontend.layouts.navbar')
            {{-- contents --}}
            @yield('contents')
        </main>
    </div>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>
