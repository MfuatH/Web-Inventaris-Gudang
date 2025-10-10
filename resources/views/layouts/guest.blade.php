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

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="https://dpuair.jatimprov.go.id/" target="_blank" rel="noopener noreferrer">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-auto h-20 mb-4">
                </a>
            </div>

            <div class="w-full sm:max-w-md px-6 py-8 bg-gray-800/20 backdrop-blur-xl shadow-lg overflow-hidden sm:rounded-2xl border border-white/20">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>