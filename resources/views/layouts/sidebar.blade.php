<div class="fixed top-0 left-0 h-full text-white z-30 transition-all duration-300 ease-in-out" 
     :class="sidebarOpen ? 'w-64' : 'w-20'">

    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-cyan-500 to-blue-600 shadow-lg"></div>

    <div class="relative h-full flex flex-col p-4">
        
        <div class="flex items-center justify-between flex-shrink-0 h-16">
            <a href="{{ route('dashboard') }}" class="inline-block transition-opacity duration-200" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Aplikasi" class="h-16 w-auto">
            </a>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md hover:bg-blue-700">
                <svg x-show="sidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                <svg x-show="!sidebarOpen" xmlns="http://www.w.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
        </div>

        <div class="mt-2 mb-4 px-2 transition-opacity duration-200" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'">
            <hr class="border-blue-400 my-2">
            <p class="font-semibold">Welcome, {{ Auth::user()->name }}</p>
        </div>
        
        <nav class="flex-grow overflow-y-auto overflow-x-hidden">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('dashboard') }}" title="Dashboard" class="menu-link flex items-center p-2 rounded font-semibold hover:bg-blue-700">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span class="ml-3 transition-opacity duration-200 whitespace-nowrap" :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">Dashboard</span>
                    </a>
                </li>

                @if(auth()->user()->role == 'user')
                    <li>
                        <a href="{{ route('requests.my') }}" title="Request Saya" class="menu-link flex items-center p-2 rounded font-semibold hover:bg-blue-700">
                            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <span class="ml-3 transition-opacity duration-200 whitespace-nowrap" :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">Request Saya</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('requests.create') }}" title="Buat Request" class="menu-link flex items-center p-2 rounded font-semibold hover:bg-blue-700">
                           <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="ml-3 transition-opacity duration-200 whitespace-nowrap" :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">Buat Request</span>
                        </a>
                    </li>
                @endif

                @can('manage_items')
                    <li>
                        <a href="{{ route('items.index') }}" title="Manajemen Barang" class="menu-link flex items-center p-2 rounded font-semibold hover:bg-blue-700">
                            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                            <span class="ml-3 transition-opacity duration-200 whitespace-nowrap" :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">Manajemen Barang</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('requests.index') }}" title="Approval Request" class="menu-link flex items-center p-2 rounded font-semibold hover:bg-blue-700">
                           <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="ml-3 transition-opacity duration-200 whitespace-nowrap" :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">Approval Request</span>
                        </a>
                    </li>
                @endcan

                @can('super_admin')
                    <li>
                        <a href="{{ route('users.index') }}" title="Manajemen User" class="menu-link flex items-center p-2 rounded font-semibold hover:bg-blue-700">
                           <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m9 5.197a6 6 0 01-3.465-1.125M3 14.803c0-3.314 2.686-6 6-6s6 2.686 6 6-2.686 6-6 6-6-2.686-6-6z" /></svg>
                            <span class="ml-3 transition-opacity duration-200 whitespace-nowrap" :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">Manajemen User</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('bidang.index') }}" title="Manajemen Bidang" class="menu-link flex items-center p-2 rounded font-semibold hover:bg-blue-700">
                            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A2 2 0 013 8V5a2 2 0 012-2z"></path></svg>
                            <span class="ml-3 transition-opacity duration-200 whitespace-nowrap" :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">Manajemen Bidang</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transactions.index') }}" title="Riwayat Transaksi" class="menu-link flex items-center p-2 rounded font-semibold hover:bg-blue-700">
                            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="ml-3 transition-opacity duration-200 whitespace-nowrap" :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">Riwayat Transaksi</span>
                        </a>
                    </li>
                @endcan
            </ul>
        </nav>

        <div class="mt-auto flex-shrink-0">
             <hr class="border-blue-400 my-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" title="Log Out" onclick="event.preventDefault(); this.closest('form').submit();"
                   class="w-full menu-link flex items-center p-2 rounded font-semibold text-cyan-200 hover:bg-red-500 hover:text-white">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span class="ml-3 transition-opacity duration-200 whitespace-nowrap" :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">Log Out</span>
                </a>
            </form>
        </div>
    </div>
</div>