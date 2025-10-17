<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            <i data-lucide="layout-dashboard" class="w-6 h-6 text-blue-600"></i>
            Dashboard
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- === Statistik Utama === --}}
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-8">

                @if(auth()->user()->role == 'super_admin')
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="bg-blue-100 p-4 rounded-full">
                            <i data-lucide="users" class="w-8 h-8 text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase">Total Pengguna</h3>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="bg-green-100 p-4 rounded-full">
                            <i data-lucide="package" class="w-8 h-8 text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase">Total Jenis Barang</h3>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalItems }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="bg-yellow-100 p-4 rounded-full">
                            <i data-lucide="clock" class="w-8 h-8 text-yellow-600"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase">Request Barang Pending</h3>
                            <p class="text-3xl font-bold text-gray-900">{{ $pendingRequests }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="bg-indigo-100 p-4 rounded-full">
                            <i data-lucide="video" class="w-8 h-8 text-indigo-600"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase">Request Zoom Pending</h3>
                            <p class="text-3xl font-bold text-gray-900">{{ $pendingZoomRequests }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="bg-emerald-100 p-4 rounded-full">
                            <i data-lucide="arrow-down-circle" class="w-8 h-8 text-emerald-600"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase">Total Transaksi Masuk</h3>
                            <p class="text-3xl font-bold text-emerald-600">{{ $totalBarangMasuk ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="bg-rose-100 p-4 rounded-full">
                            <i data-lucide="arrow-up-circle" class="w-8 h-8 text-rose-600"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase">Jumlah Transaksi Keluar</h3>
                            <p class="text-3xl font-bold text-rose-600">{{ $totalBarangKeluar ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- === Konten Utama === --}}
            <div class="grid grid-cols-1 gap-6">

                {{-- Admin dan Super Admin --}}
                @if(in_array(auth()->user()->role, ['super_admin', 'admin_barang']))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Tabel Stok Barang Menipis (Stok < 10)</h3>

                        {{-- Search Bar --}}
                        <div class="mb-4 flex gap-2">
                            <div class="flex-1 relative">
                                <input type="text" id="stockSearch" placeholder="Cari nama barang..." 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <div id="stockLoading" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                                </div>
                            </div>
                            <button id="stockReset"
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:ring-2 focus:ring-gray-400 hidden">
                                Reset
                            </button>
                        </div>

                        {{-- Tabel --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Stok</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Satuan</th>
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
                                            <tr>
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $label }}</td>
                                                <td class="px-6 py-4 text-sm {{ $isCriticalStock ? 'text-red-600 font-bold' : ($isLowStock ? 'text-yellow-600 font-semibold' : 'text-gray-900') }}">
                                                    {{ $stok }}
                                                    @if($isCriticalStock)
                                                        <span class="ml-1 text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">KRITIS</span>
                                                    @elseif($isLowStock)
                                                        <span class="ml-1 text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">RENDAH</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $stockChartData['satuan'][$index] ?? 'Unit' }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                {{-- User --}}
                @if(auth()->user()->role == 'user')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Grafik Stok Barang Tersedia</h3>

                        {{-- Search --}}
                        <div class="mb-4 flex gap-2">
                            <div class="flex-1 relative">
                                <input type="text" id="userStockSearch" placeholder="Cari nama barang..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <div id="userStockLoading" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                                </div>
                            </div>
                            <button id="userStockReset"
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:ring-2 focus:ring-gray-400 hidden">
                                Reset
                            </button>
                        </div>

                        <canvas id="availableItemsChart"></canvas>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- === Script Section === --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>

    @if(in_array(auth()->user()->role, ['super_admin', 'admin_barang']))
        @if(isset($stockChartData))
            <script>
                const stockSearchInput = document.getElementById('stockSearch');
                const stockLoading = document.getElementById('stockLoading');
                const stockResetBtn = document.getElementById('stockReset');
                const stockTableBody = document.getElementById('stockTableBody');
                let searchTimeout;

                stockSearchInput.addEventListener('input', () => {
                    clearTimeout(searchTimeout);
                    const term = stockSearchInput.value.trim();
                    if (term) {
                        stockResetBtn.classList.remove('hidden');
                        searchTimeout = setTimeout(() => performSearch(term), 300);
                    } else {
                        stockResetBtn.classList.add('hidden');
                        performSearch('');
                    }
                });

                stockResetBtn.addEventListener('click', () => {
                    stockSearchInput.value = '';
                    stockResetBtn.classList.add('hidden');
                    performSearch('');
                });

                function performSearch(term) {
                    stockLoading.classList.remove('hidden');
                    fetch(`{{ route('dashboard.search') }}?search=${encodeURIComponent(term)}`)
                        .then(res => res.json())
                        .then(updateTable)
                        .finally(() => stockLoading.classList.add('hidden'));
                }

                function updateTable(data) {
                    let html = '';
                    if (data.labels?.length) {
                        data.labels.forEach((label, i) => {
                            const stok = data.data[i];
                            const low = stok < 5;
                            const critical = stok < 2;
                            html += `
                                <tr>
                                    <td class="px-6 py-4 text-sm">${i + 1}</td>
                                    <td class="px-6 py-4 text-sm">${label}</td>
                                    <td class="px-6 py-4 text-sm ${critical ? 'text-red-600 font-bold' : low ? 'text-yellow-600 font-semibold' : ''}">
                                        ${stok} ${critical ? '<span class="ml-1 text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">KRITIS</span>' :
                                        low ? '<span class="ml-1 text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">RENDAH</span>' : ''}
                                    </td>
                                    <td class="px-6 py-4 text-sm">${data.satuan[i] || 'Unit'}</td>
                                </tr>`;
                        });
                    } else {
                        html = `<tr><td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data ditemukan</td></tr>`;
                    }
                    stockTableBody.innerHTML = html;
                }
            </script>
        @endif
    @endif

    @if(auth()->user()->role == 'user' && isset($chartDataUser))
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('availableItemsChart');
        const dataUser = @json($chartDataUser);
        let userChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dataUser.labels,
                datasets: [{
                    label: 'Jumlah Stok',
                    data: dataUser.data,
                    backgroundColor: 'rgba(54, 162, 235, 0.3)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: { indexAxis: 'y', scales: { x: { beginAtZero: true } } }
        });

        const userStockSearchInput = document.getElementById('userStockSearch');
        const userStockLoading = document.getElementById('userStockLoading');
        const userStockResetBtn = document.getElementById('userStockReset');
        let userSearchTimeout;

        userStockSearchInput.addEventListener('input', () => {
            clearTimeout(userSearchTimeout);
            const term = userStockSearchInput.value.trim();
            if (term) {
                userStockResetBtn.classList.remove('hidden');
                userSearchTimeout = setTimeout(() => performUserSearch(term), 300);
            } else {
                userStockResetBtn.classList.add('hidden');
                performUserSearch('');
            }
        });

        userStockResetBtn.addEventListener('click', () => {
            userStockSearchInput.value = '';
            userStockResetBtn.classList.add('hidden');
            performUserSearch('');
        });

        function performUserSearch(term) {
            userStockLoading.classList.remove('hidden');
            fetch(`{{ route('dashboard.search') }}?search=${encodeURIComponent(term)}`)
                .then(res => res.json())
                .then(data => {
                    userChart.data.labels = data.labels;
                    userChart.data.datasets[0].data = data.data;
                    userChart.update();
                })
                .finally(() => userStockLoading.classList.add('hidden'));
        }
    </script>
    @endif
</x-app-layout>
