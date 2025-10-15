<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Riwayat Transaksi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    
                    <div class="mb-4">
                        <a href="{{ route('transactions.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Export ke Excel
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-800 dark:bg-gray-700 text-white">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tanggal</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Kode Barang</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Barang</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Jumlah</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tipe</th>
                                    <!-- <th class="text-left py-3 px-4 uppercase font-semibold text-sm">User Perequest</th> -->
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-300">
                                @forelse ($transactions as $transaction)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="py-3 px-4">{{ $transaction->tanggal }}</td>
                                    <td class="py-3 px-4">{{ $transaction->item->kode_barang }}</td>
                                    <td class="py-3 px-4">{{ $transaction->item->nama_barang }}</td>
                                    <td class="py-3 px-4">{{ $transaction->jumlah }}</td>
                                    <td class="py-3 px-4">
                                        @if($transaction->tipe == 'masuk')
                                            <span class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs">Masuk</span>
                                        @else
                                            <span class="bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs">Keluar</span>
                                        @endif
                                    </td>
                                    <!-- <td class="py-3 px-4">
                                        {{ $transaction->request->user->name ?? '-' }}
                                    </td> -->
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">Tidak ada riwayat transaksi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>