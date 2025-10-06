<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-g">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        
        <div x-data="{ sidebarOpen: true }" class="min-h-screen bg-gray-100 dark:bg-gray-900">
            
            @include('layouts.sidebar')

            <div class="flex flex-col min-h-screen transition-all duration-300 ease-in-out" 
                 :class="sidebarOpen ? 'ml-64' : 'ml-20'">
                
                @include('layouts.navigation')

                @if (isset($header))
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif
                
                <main class="flex-grow pb-16">
                    {{ $slot }}
                </main>

                @include('layouts.partials.footer')
                
            </div>
        </div>

        @stack('scripts')
        
    </body>
</html>