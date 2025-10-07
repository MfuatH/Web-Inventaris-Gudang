<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link rel="icon" href="images/logo.png" type="image/x-icon"/>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>INVPUSDA</title>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            
            @include('layouts.sidebar')

            <div class="ml-64 flex flex-col justify-between min-h-screen">
                <div>
                    @include('layouts.navigation')

                    @if (isset($header))
                        <header class="py-6 px-4 sm:px-6 lg:px-8">
                            <div class="max-w-7xl mx-auto">
                                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                                    {{ $header }}
                                </div>
                            </div>
                        </header>
                    @endif
                    
                    <main>
                        <div class="px-4 sm:px-6 lg:px-8 py-6">
                             {{ $slot }}
                        </div>
                    </main>
                </div>
                
                <footer class="text-center p-4 text-sm text-gray-500">
                    Copyright &copy; {{ date('Y') }} Dinas PU Sumber Daya Air Jawa Timur. All Rights Reserved.
                </footer>
                </div>
        </div>
    </body>
</html>