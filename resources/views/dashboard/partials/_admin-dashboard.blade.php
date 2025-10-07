<!-- Card Statistik -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    @can('super_admin')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-400">Total Pengguna</h3>
        <p class="text-3xl font-bold mt-2 text-gray-900 dark:text-gray-100">{{ $totalUsers }}</p>
    </div>
    @endcan
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-400">Total Jenis Barang</h3>
        <p class="text-3xl font-bold mt-2 text-gray-900 dark:text-gray-100">{{ $totalItems }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-400">Request Pending</h3>
        <p class="text-3xl font-bold mt-2 text-gray-900 dark:text-gray-100">{{ $pendingRequests }}</p>
    </div>
</div>

<!-- Konten Utama: Histori Hari Ini & Grafik -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Histori Transaksi Hari Ini</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nama Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tipe</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($todayTransactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $transaction->item->nama_barang }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100">{{ $transaction->jumlah }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($transaction->tipe == 'masuk')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Masuk</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Keluar</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Belum ada transaksi hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Tren Transaksi (7 Hari)</h3>
            <canvas id="transactionChart"></canvas>
        </div>
    </div>
</div>

<!-- DIUBAH: Menampilkan Notifikasi Stok Tipis -->
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="flex items-center text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
            <svg class="w-6 h-6 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            Daftar Stok Item Yang Tipis
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nama Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Sisa Stok</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($lowStockItems as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $item->nama_barang }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $item->lokasi }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-red-600">{{ $item->jumlah }} {{ $item->satuan }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                <span class="text-green-600 font-semibold">Semua stok barang dalam kondisi aman.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

