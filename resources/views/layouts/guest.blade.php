<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>INVPUSDA</title>
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png"/>

        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="antialiased" style="background-image: url('{{ asset('images/background.jpeg') }}'); background-size: cover; background-position: center;">
        <div class="font-sans text-gray-900">
            <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm-pt-0">
                <div>
                    <a href="https://dpuair.jatimprov.go.id/" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-auto h-20 mb-4">
                    </a>
                </div>

                <div class="w-full sm:max-w-md px-6 py-8 bg-white/20 backdrop-blur-lg shadow-md overflow-hidden sm:rounded-xl border border-white/30">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>