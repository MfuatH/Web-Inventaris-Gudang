<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Selamat Datang - INVPUSDA</title>
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png"/>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
        
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="{{ mix('js/app.js') }}" defer></script>
        <style>
            .font-natural {
                font-family: 'Pacifico', cursive;
            }
        </style>
    </head>
    <body class="antialiased font-sans text-gray-900 transition-all duration-500"
          x-data="{
              themes: {{ json_encode(config('themes.list')) }},
              activeThemeIndex: 0,
              init() {
                  const savedThemePath = localStorage.getItem('login_theme');
                  if (savedThemePath) {
                      const savedIndex = this.themes.findIndex(theme => '{{ asset('') }}' + theme.path === savedThemePath);
                      if (savedIndex !== -1) {
                          this.activeThemeIndex = savedIndex;
                      }
                  }
                  this.updateBackground();
              },
              changeTheme() {
                  this.activeThemeIndex = (this.activeThemeIndex + 1) % this.themes.length;
                  this.updateBackground();
              },
              updateBackground() {
                  let newPath = '{{ asset('') }}' + this.themes[this.activeThemeIndex].path;
                  document.body.style.backgroundImage = `url('${newPath}')`;
                  localStorage.setItem('login_theme', newPath);
              }
          }"
          x-init="init()"
          style="background-size: cover; background-position: center;">

        <div class="fixed top-4 right-4 z-10">
            <button @click="changeTheme()" 
                    title="Ganti Tema"
                    class="w-12 h-12 flex items-center justify-center rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-white shadow-md hover:bg-white/40 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
            </button>
                </div>

        <div class="min-h-screen flex flex-col justify-center items-center text-center p-6 bg-black/30">

            <div class="mb-8">
                <a href="https://dpuair.jatimprov.go.id/" target="_blank" rel="noopener noreferrer">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo PUSDA" style="width: 420px; height: auto;">
                </a>
                <h1 class="font-natural text-white text-3xl tracking-wide mt-20">
                    Selamat Datang
                </h1>
                        </div>

            <div class="w-full max-w-2xl grid grid-cols-1 md:grid-cols-2 gap-6 ">
                
                <a href="{{route('guest.requests.create')}}" class="block p-6 bg-gray-800/20 backdrop-blur-xl shadow-lg rounded-2xl border border-white/20 text-white font-semibold hover:bg-gray-400/40 transition duration-300">
                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span>Permintaan Barang</span>
                </a>
        
                <a href="{{route('guest.linkzoom.create')}}" class="block p-6 bg-gray-800/20 backdrop-blur-xl shadow-lg rounded-2xl border border-white/20 text-white font-semibold hover:bg-gray-800/40 transition duration-300">
                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <span>Permintaan Link Zoom</span>
                </a>
                    </div>

        </div>
    </body>
</html>
