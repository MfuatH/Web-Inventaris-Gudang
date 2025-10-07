<x-guest-layout>
    <h2 class="text-blue-700 text-2xl font-bold text-center mb-6">
        Selamat Datang
    </h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="email" class="block font-medium text-sm text-black">Email</label>
            <input id="email" class="bg-transparent border-0 border-b-2 border-black/50 text-black placeholder-grey-200 block mt-1 w-full focus:border-indigo-300 focus:ring-0"
                   type="email" name="email" :value="old('email')" required autofocus />
        </div>

        <div class="mt-6" x-data="{ show: false }">
            <label for="password" class="block font-medium text-sm text-black">Password</label>
            <div class="relative">
                <input id="password" 
                       :type="show ? 'text' : 'password'"
                       class="bg-transparent border-0 border-b-2 border-black/50 text-black placeholder-grey-200 block mt-1 w-full focus:border-indigo-300 focus:ring-0"
                       name="password" required autocomplete="current-password" />
                
                <div @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                    <svg x-show="!show" class="h-6 w-6 text-black/70" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <svg x-show="show" class="h-6 w-6 text-black/70" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 .847 0 1.673.124 2.468.352M10.582 10.582a3 3 0 112.836 2.836M18.27 18.27A10.016 10.016 0 0112 19c-4.478 0-8.268-2.943-9.542-7C3.732 7.943 7.523 5 12 5c2.478 0 4.75.817 6.474 2.218M6.2 6.2l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mt-6">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-gray-900/50 border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-black">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex justify-center mt-8">
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-2 bg-white/90 border border-transparent rounded-full font-semibold text-xs text-blue-600 uppercase tracking-widest hover:bg-white active:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Log In
            </button>
        </div>
        <div class="text-center mt-6 border-t pt-4">
            <p class="text-xs text-gray-800 font-semibold">
                Dinas Pekerjaan Umum Sumber Daya Air Provinsi Jawa Timur
            </p>
        </div>
    </form>
</x-guest-layout>