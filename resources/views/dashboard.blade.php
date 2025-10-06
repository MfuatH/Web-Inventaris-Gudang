<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin_barang')
                {{-- Tampilkan dashboard untuk admin --}}
                @include('dashboard.partials._admin-dashboard')
            @else
                {{-- Tampilkan dashboard untuk user biasa --}}
                @include('dashboard.partials._user-dashboard')
            @endif
        </div>
    </div>

    {{-- Pastikan ada @stack('scripts') di layout utama Anda (app.blade.php) --}}
    @push('scripts')
        {{-- Script untuk chart hanya akan di-load jika dashboard admin yang tampil --}}
        @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin_barang')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const ctx = document.getElementById('transactionChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($labels),
                        datasets: [{
                            label: 'Barang Masuk',
                            data: @json($dataMasuk),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            tension: 0.3
                        }, {
                            label: 'Barang Keluar',
                            data: @json($dataKeluar),
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            tension: 0.3
                        }]
                    },
                    options: { scales: { y: { beginAtZero: true } } }
                });
            </script>
        @endif
    @endpush
</x-app-layout>