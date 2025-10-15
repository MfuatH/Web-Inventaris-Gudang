<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-6">
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
                    <h3 class="text-lg font-semibold">Request Barang Pending</h3>
                    <p class="text-3xl font-bold">{{ $pendingRequests }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold">Request Zoom Pending</h3>
                    <p class="text-3xl font-bold">{{ $pendingZoomRequests }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold">Total Transaksi Masuk</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $totalBarangMasuk ?? 0 }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold">Jumlah Transaksi Keluar</h3>
                    <p class="text-3xl font-bold text-red-600">{{ $totalBarangKeluar ?? 0 }}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 gap-6">
                @if(in_array(auth()->user()->role, ['super_admin', 'admin_barang']))
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold mb-4">Tabel Stok Barang Menipis (Stok < 10)</h3>
                            
                            <!-- Search Bar -->
                            <div class="mb-4">
                                <div class="flex gap-2">
                                    <div class="flex-1 relative">
                                        <input type="text" 
                                               id="stockSearch" 
                                               placeholder="Cari nama barang dengan stok menipis..." 
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
                            
                            <!-- Tabel Stok Barang -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Stok</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="stockTableBody" class="bg-white divide-y divide-gray-200">
                                        @if(isset($stockChartData))
                                            @foreach($stockChartData['labels'] as $index => $label)
                                                @php
                                                    $stok = $stockChartData['data'][$index];
                                                    $isLowStock = $stok < 5;
                                                    $isCriticalStock = $stok < 2;
                                                @endphp
                                                <tr class="{{ $isCriticalStock ? 'bg-red-50' : ($isLowStock ? 'bg-yellow-50' : '') }}">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $label }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm {{ $isCriticalStock ? 'text-red-600 font-bold' : ($isLowStock ? 'text-yellow-600 font-semibold' : 'text-gray-900') }}">
                                                        {{ $stok }}
                                                        @if($isCriticalStock)
                                                            <span class="ml-1 text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">KRITIS</span>
                                                        @elseif($isLowStock)
                                                            <span class="ml-1 text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">RENDAH</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stockChartData['satuan'][$index] ?? 'Unit' }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
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

    @if(in_array(auth()->user()->role, ['super_admin', 'admin_barang']))

        @if(isset($stockChartData))
        <script>
            // Real-time search functionality untuk tabel
            let searchTimeout;
            const stockSearchInput = document.getElementById('stockSearch');
            const stockLoading = document.getElementById('stockLoading');
            const stockResetBtn = document.getElementById('stockReset');
            const stockTableBody = document.getElementById('stockTableBody');

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
                        // Update tabel dengan data baru
                        updateTable(data);
                        stockLoading.classList.add('hidden');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        stockLoading.classList.add('hidden');
                    });
            }

            function updateTable(data) {
                let tableHTML = '';
                if (data.labels && data.labels.length > 0) {
                    data.labels.forEach((label, index) => {
                        const stok = data.data[index];
                        const isLowStock = stok < 5;
                        const isCriticalStock = stok < 2;
                        
                        let rowClass = '';
                        let stokClass = 'text-gray-900';
                        let badge = '';
                        
                        if (isCriticalStock) {
                            rowClass = 'bg-red-50';
                            stokClass = 'text-red-600 font-bold';
                            badge = '<span class="ml-1 text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">KRITIS</span>';
                        } else if (isLowStock) {
                            rowClass = 'bg-yellow-50';
                            stokClass = 'text-yellow-600 font-semibold';
                            badge = '<span class="ml-1 text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">RENDAH</span>';
                        }
                        
                        tableHTML += `
                            <tr class="${rowClass}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${label}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm ${stokClass}">
                                    ${stok}${badge}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${data.satuan[index] || 'Unit'}</td>
                            </tr>
                        `;
                    });
                } else {
                    tableHTML = `
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Tidak ada data ditemukan
                            </td>
                        </tr>
                    `;
                }
                stockTableBody.innerHTML = tableHTML;
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