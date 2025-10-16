<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon"/>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>INVPUSDA</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>

        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest" defer></script>
    </head>

    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex">
            
            {{-- Sidebar --}}
            @include('layouts.sidebar')

            <div class="flex-1 flex flex-col justify-between min-h-screen ml-64">
                <div>
                    {{-- Navigation Bar --}}
                    @include('layouts.navigation')

                    {{-- Header Section --}}
                    @if (isset($header))
                        <header class="py-6 px-4 sm:px-6 lg:px-8">
                            <div class="max-w-7xl mx-auto">
                                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                                    {{ $header }}
                                </div>
                            </div>
                        </header>
                    @endif
                    
                    {{-- Main Content --}}
                    <main>
                        <div class="px-4 sm:px-6 lg:px-8 py-6">
                            {{ $slot }}
                        </div>
                    </main>
                </div>

                {{-- Footer --}}
                <footer class="text-center p-4 text-sm text-gray-500">
                    &copy; {{ date('Y') }} Dinas PU Sumber Daya Air Jawa Timur. All Rights Reserved.
                </footer>
            </div>
        </div>

        {{-- Inisialisasi Lucide Icons --}}
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                if (window.lucide) {
                    lucide.createIcons();
                }
            });
        </script>
    </body>
</html>
