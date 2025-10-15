<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Barang
        </h2>
    </x-slot>

    <div x-data="{ open: false, selectedItem: null }" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4 flex justify-between items-center">
                        <div class="flex gap-4">
                            <a href="{{ route('items.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                + Tambah Barang Baru
                            </a>
                            
                            <a href="{{ route('items.export') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export Excel
                            </a>
                        </div>
                        <form method="GET" action="{{ route('items.index') }}">
                            <input type="text" name="search" placeholder="Cari barang..." class="form-input rounded-md shadow-sm" value="{{ request('search') }}">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Cari</button>
                        </form>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Kode</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Barang</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Satuan</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Jumlah</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Lokasi</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Keterangan</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($items as $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ $item->kode_barang }}</td>
                                    <td class="py-3 px-4">{{ $item->nama_barang }}</td>
                                    <td class="py-3 px-4">{{ $item->satuan }}</td>
                                    <td class="py-3 px-4">{{ $item->jumlah }}</td>
                                    <td class="py-3 px-4">{{ $item->lokasi }}</td>
                                    <td class="py-3 px-4">{{ $item->keterangan ?? '-' }}</td>
                                    
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <button @click="open = true; selectedItem = {{ json_encode($item) }}" class="text-blue-500 hover:text-blue-700 font-semibold">+ Stok</button>
                                        <a href="{{ route('items.edit', $item->id) }}" class="text-yellow-500 hover:text-yellow-700 font-semibold ml-2">Edit</a>
                                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Yakin hapus?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">Tidak ada data barang.</td>
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

        <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
            <div @click.away="open = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                <h3 class="text-lg font-bold mb-4">Tambah Stok untuk <span x-text="selectedItem ? selectedItem.nama_barang : ''"></span></h3>
                <form :action="selectedItem ? '/items/' + selectedItem.id + '/add-stock' : ''" method="POST">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="jumlah_tambahan" class="block font-medium text-sm text-gray-700">Jumlah Tambahan</label>
                        <input id="jumlah_tambahan" name="jumlah_tambahan" type="number" min="1" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required autofocus>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="button" @click="open = false" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                            Batal
                        </button>
                        <x-primary-button>
                            Simpan
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>