<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @vite(['resources/css/app.css'])
    @livewireStyles
    @filamentStyles
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="">
                <div class="flex items-center justify-between px-4 pt-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="py-8">
            {{ $slot }}
        </main>
    </div>
    @vite(['resources/js/app.js'])
    @livewire('notifications')
    @livewireScripts
    @filamentScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('swal', (data) => {
                console.log("SweetAlert Data Received:", data[0].toast); // Debugging

                if (typeof data !== 'object' || data === null) {
                    console.error("Invalid data format received for SweetAlert:", data);
                    return;
                }

                if (data[0].toast === true) {
                    // Toast Notification
                    Swal.fire({
                        position: data[0].position || 'top-end',
                        icon: data[0].icon || 'success',
                        title: data[0].title || 'Success!',
                        text: data[0].text || '',
                        showConfirmButton: data[0].showConfirmButton ?? false,
                        timer: data[0].timer || 3000,
                        toast: true
                    });
                } else {
                    // Regular Alert
                    Swal.fire({
                        title: data[0].title || 'Notification',
                        text: data[0].text || '',
                        icon: data[0].icon || 'info',
                        confirmButtonText: data[0].confirmButtonText || 'OK'
                    });
                }
            });
        });
    </script>


</body>

</html>
