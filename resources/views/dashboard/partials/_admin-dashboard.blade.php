<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    @can('super_admin')
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-semibold text-gray-600">Total Pengguna</h3>
        <p class="text-3xl font-bold mt-2">{{ $totalUsers }}</p>
    </div>
    @endcan
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-semibold text-gray-600">Total Jenis Barang</h3>
        <p class="text-3xl font-bold mt-2">{{ $totalItems }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-semibold text-gray-600">Request Pending</h3>
        <p class="text-3xl font-bold mt-2">{{ $pendingRequests }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Histori Transaksi Hari Ini</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($todayTransactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->item->nama_barang }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $transaction->jumlah }}</td>
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
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada transaksi hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Tren Transaksi (7 Hari)</h3>
            <canvas id="transactionChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Top 5 Barang Masuk (7 Hari)</h3>
            <ul class="space-y-2">
                @forelse ($topItemsIn as $transaction)
                    <li class="flex justify-between items-center text-sm">
                        <span>{{ $transaction->item->nama_barang }}</span>
                        <span class="font-bold px-2 py-1 bg-green-100 text-green-800 rounded-full">{{ $transaction->total_jumlah }}</span>
                    </li>
                @empty
                    <li class="text-center text-gray-500">Tidak ada barang masuk.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Top 5 Barang Keluar (7 Hari)</h3>
            <ul class="space-y-2">
                @forelse ($topItemsOut as $transaction)
                    <li class="flex justify-between items-center text-sm">
                        <span>{{ $transaction->item->nama_barang }}</span>
                        <span class="font-bold px-2 py-1 bg-red-100 text-red-800 rounded-full">{{ $transaction->total_jumlah }}</span>
                    </li>
                @empty
                    <li class="text-center text-gray-500">Tidak ada barang keluar.</li>
                @endforelse
            </ul>
        </div>
    </div>
    
</div>