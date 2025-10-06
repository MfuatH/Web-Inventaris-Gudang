<div class="fixed top-0 left-0 w-64 h-full bg-gradient-to-r from-blue-500 to-cyan-400 text-white p-4">
    
    <a href="{{ route('dashboard') }}" class="inline-block mb-2">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Aplikasi" class="h-16 w-auto">
    </a>

    <div class="mb-4 px-2">
        <p class="font-semibold">Welcome, {{ Auth::user()->name }}</p>
    </div>

    <hr class="border-blue-400 my-4">
    
    <nav>
        <ul>
            <li class="mb-2">
                <a href="{{ route('dashboard') }}" class="menu-link flex items-center p-2 rounded font-semibold hover:bg-blue-600">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Dashboard</span>
                </a>
            </li>

            @if(auth()->user()->role == 'user')
                <li class="mb-2">
                    <a href="{{ route('requests.my') }}" class="menu-link flex items-center p-2 rounded font-semibold hover:bg-blue-600">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span>Request Saya</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('requests.create') }}" class="menu-link flex items-center p-2 rounded font-semibold hover:bg-blue-600">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Buat Request</span>
                    </a>
                </li>
            @endif

            @if(in_array(auth()->user()->role, ['admin_barang', 'super_admin']))
                <li class="mb-2">
                    <a href="{{ route('items.index') }}" class="menu-link flex items-center p-2 rounded font-semibold hover:bg-blue-600">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        <span>Manajemen Barang</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('requests.index') }}" class="menu-link flex items-center p-2 rounded font-semibold hover:bg-blue-600">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Approval Request</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->role == 'super_admin')
                <li class="mb-2">
                    <a href="{{ route('users.index') }}" class="menu-link flex items-center p-2 rounded font-semibold hover:bg-blue-600">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197..."></path></svg>
                        <span>Manajemen User</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('transactions.index') }}" class="menu-link flex items-center p-2 rounded font-semibold hover:bg-blue-600">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span>Riwayat Transaksi</span>
                    </a>
                </li>
            @endif

            <li class="mb-2 mt-4 border-t border-blue-400 pt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="menu-link flex items-center p-2 rounded font-semibold text-red-300 hover:bg-red-500 hover:text-white">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Log Out</span>
                    </a>
                </form>
            </li>

        </ul>
    </nav>
</div>