<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-4 flex justify-between items-center">
                        <a href="{{ route('items.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-colors duration-200">
                            + Tambah Barang Baru
                        </a>
                        <form method="GET" action="{{ route('items.index') }}">
                            <input type="text" name="search" placeholder="Cari barang..." class="form-input rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600" value="{{ request('search') }}">
                            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md ml-2 transition-colors duration-200">Cari</button>
                        </form>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                <tr>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Kode</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Nama Barang</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Jumlah</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Lokasi</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Keterangan</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-200">
                                @forelse ($items as $item)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="py-3 px-4">{{ $item->kode_barang }}</td>
                                    <td class="py-3 px-4">{{ $item->nama_barang }}</td>
                                    <td class="py-3 px-4 font-bold">{{ $item->jumlah }}</td>
                                    <td class="py-3 px-4">{{ $item->lokasi }}</td>
                                    <td class="py-3 px-4">{{ $item->keterangan ?? '-' }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        
                                        <a href="{{ route('items.addStockForm', $item->id) }}" class="text-green-600 hover:text-green-800 font-bold">
                                            Stok+
                                        </a>
                                        
                                        <a href="{{ route('items.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-800 font-bold ml-4">
                                            Edit
                                        </a>
                                        
                                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Yakin hapus?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-bold">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">Tidak ada data barang.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $items->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>