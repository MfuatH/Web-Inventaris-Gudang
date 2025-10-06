<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                @if(auth()->user()->role == 'super_admin')
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold">Total Pengguna</h3>
                    <p class="text-3xl font-bold">{{ $totalUsers }}</p>
                </div>
                @endif
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold">Total Jenis Barang</h3>
                    <p class="text-3xl font-bold">{{ $totalItems }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold">Request Pending</h3>
                    <p class="text-3xl font-bold">{{ $pendingRequests }}</p>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if(in_array(auth()->user()->role, ['super_admin', 'admin_barang']))
                        <h3 class="text-lg font-semibold mb-4">Grafik Transaksi Barang</h3>
                        <canvas id="transactionChart"></canvas>
                    @elseif(auth()->user()->role == 'user')
                        <h3 class="text-lg font-semibold mb-4">Grafik Stok Barang Tersedia</h3>
                        <canvas id="availableItemsChart"></canvas>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @if(in_array(auth()->user()->role, ['super_admin', 'admin_barang']) && isset($chartData))
    <script>
        const ctx = document.getElementById('transactionChart').getContext('2d');
        const chartData = @json($chartData);
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Barang Masuk', 'Barang Keluar'],
                datasets: [{
                    label: '# of Transactions', data: [chartData.in, chartData.out],
                    backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                    borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                    borderWidth: 1
                }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });
    </script>
    @endif

    @if(auth()->user()->role == 'user' && isset($chartDataUser))
    <script>
        const userCtx = document.getElementById('availableItemsChart').getContext('2d');
        const chartDataUser = @json($chartDataUser);
        new Chart(userCtx, {
            type: 'bar',
            data: {
                labels: chartDataUser.labels,
                datasets: [{
                    label: 'Jumlah Stok', data: chartDataUser.data,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: { indexAxis: 'y', scales: { x: { beginAtZero: true } } }
        });
    </script>
    @endif
</x-app-layout>