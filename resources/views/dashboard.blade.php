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
            
            <div class="grid grid-cols-1 gap-6">
                @if(in_array(auth()->user()->role, ['super_admin', 'admin_barang']))
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold mb-4">Grafik Transaksi Barang</h3>
                            <canvas id="transactionChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold mb-4">Grafik Stok Barang Saat Ini</h3>
                            
                            <!-- Search Bar -->
                            <div class="mb-4">
                                <div class="flex gap-2">
                                    <div class="flex-1 relative">
                                        <input type="text" 
                                               id="stockSearch" 
                                               placeholder="Cari nama barang..." 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <div id="stockLoading" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                                        </div>
                                    </div>
                                    <button id="stockReset" 
                                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 hidden">
                                        Reset
                                    </button>
                                </div>
                            </div>
                            
                            <canvas id="stockChart"></canvas>
                        </div>
                    </div>
                
                @elseif(auth()->user()->role == 'user')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold mb-4">Grafik Stok Barang Tersedia</h3>
                            
                            <!-- Search Bar -->
                            <div class="mb-4">
                                <div class="flex gap-2">
                                    <div class="flex-1 relative">
                                        <input type="text" 
                                               id="userStockSearch" 
                                               placeholder="Cari nama barang..." 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <div id="userStockLoading" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                                        </div>
                                    </div>
                                    <button id="userStockReset" 
                                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 hidden">
                                        Reset
                                    </button>
                                </div>
                            </div>
                            
                            <canvas id="availableItemsChart"></canvas>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @if(in_array(auth()->user()->role, ['super_admin', 'admin_barang']))
        @if(isset($chartData))
        <script>
            // Grafik Transaksi (Bar Chart)
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

        @if(isset($stockChartData))
        <script>
            // Grafik Stok Barang (Horizontal Bar Chart)
            const stockCtx = document.getElementById('stockChart').getContext('2d');
            const stockChartData = @json($stockChartData);
            let stockChart = new Chart(stockCtx, {
                type: 'bar',
                data: {
                    labels: stockChartData.labels,
                    datasets: [{
                        label: 'Jumlah Stok',
                        data: stockChartData.data,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    scales: { x: { beginAtZero: true } },
                    plugins: { legend: { display: false } }
                }
            });

            // Real-time search functionality
            let searchTimeout;
            const stockSearchInput = document.getElementById('stockSearch');
            const stockLoading = document.getElementById('stockLoading');
            const stockResetBtn = document.getElementById('stockReset');

            stockSearchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchTerm = this.value.trim();
                
                if (searchTerm.length > 0) {
                    stockResetBtn.classList.remove('hidden');
                    searchTimeout = setTimeout(() => {
                        performSearch(searchTerm);
                    }, 300); // 300ms delay
                } else {
                    stockResetBtn.classList.add('hidden');
                    // When search is empty, fetch 15 items from server
                    searchTimeout = setTimeout(() => {
                        performSearch('');
                    }, 100); // Shorter delay for empty search
                }
            });

            stockResetBtn.addEventListener('click', function() {
                stockSearchInput.value = '';
                stockResetBtn.classList.add('hidden');
                // Fetch fresh 15 items from server
                performSearch('');
            });

            function performSearch(searchTerm) {
                stockLoading.classList.remove('hidden');
                
                fetch(`{{ route('dashboard.search') }}?search=${encodeURIComponent(searchTerm)}`)
                    .then(response => response.json())
                    .then(data => {
                        stockChart.data.labels = data.labels;
                        stockChart.data.datasets[0].data = data.data;
                        stockChart.update();
                        stockLoading.classList.add('hidden');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        stockLoading.classList.add('hidden');
                    });
            }

            function resetChart() {
                stockChart.data.labels = stockChartData.labels;
                stockChart.data.datasets[0].data = stockChartData.data;
                stockChart.update();
            }
        </script>
        @endif
    @endif

    @if(auth()->user()->role == 'user' && isset($chartDataUser))
    <script>
        const userCtx = document.getElementById('availableItemsChart').getContext('2d');
        const chartDataUser = @json($chartDataUser);
        let userChart = new Chart(userCtx, {
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

        // Real-time search functionality for user chart
        let userSearchTimeout;
        const userStockSearchInput = document.getElementById('userStockSearch');
        const userStockLoading = document.getElementById('userStockLoading');
        const userStockResetBtn = document.getElementById('userStockReset');

        userStockSearchInput.addEventListener('input', function() {
            clearTimeout(userSearchTimeout);
            const searchTerm = this.value.trim();
            
            if (searchTerm.length > 0) {
                userStockResetBtn.classList.remove('hidden');
                userSearchTimeout = setTimeout(() => {
                    performUserSearch(searchTerm);
                }, 300); // 300ms delay
            } else {
                userStockResetBtn.classList.add('hidden');
                // When search is empty, fetch 15 items from server
                userSearchTimeout = setTimeout(() => {
                    performUserSearch('');
                }, 100); // Shorter delay for empty search
            }
        });

        userStockResetBtn.addEventListener('click', function() {
            userStockSearchInput.value = '';
            userStockResetBtn.classList.add('hidden');
            // Fetch fresh 15 items from server
            performUserSearch('');
        });

        function performUserSearch(searchTerm) {
            userStockLoading.classList.remove('hidden');
            
            fetch(`{{ route('dashboard.search') }}?search=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    userChart.data.labels = data.labels;
                    userChart.data.datasets[0].data = data.data;
                    userChart.update();
                    userStockLoading.classList.add('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    userStockLoading.classList.add('hidden');
                });
        }

        function resetUserChart() {
            userChart.data.labels = chartDataUser.labels;
            userChart.data.datasets[0].data = chartDataUser.data;
            userChart.update();
        }
    </script>
    @endif
</x-app-layout>