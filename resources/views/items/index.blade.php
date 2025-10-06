<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="mb-4 flex justify-between items-center">
                        <a href="{{ route('items.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah Barang
                        </a>
                        <form method="GET" action="{{ route('items.index') }}">
                            <input type="text" name="search" placeholder="Cari barang..." class="form-input rounded-md shadow-sm" value="{{ request('search') }}">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">Cari</button>
                        </form>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="w-1/12 text-left py-3 px-4 uppercase font-semibold text-sm">Kode</th>
                                    <th class="w-3/12 text-left py-3 px-4 uppercase font-semibold text-sm">Nama Barang</th>
                                    <th class="w-1/12 text-left py-3 px-4 uppercase font-semibold text-sm">Jumlah</th>
                                    <th class="w-2/12 text-left py-3 px-4 uppercase font-semibold text-sm">Lokasi</th>
                                    <th class="w-3/12 text-left py-3 px-4 uppercase font-semibold text-sm">Keterangan</th> <th class="w-2/12 text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($items as $item)
                                <tr class="border-b">
                                    <td class="text-left py-3 px-4">{{ $item->kode_barang }}</td>
                                    <td class="text-left py-3 px-4">{{ $item->nama_barang }}</td>
                                    <td class="text-left py-3 px-4">{{ $item->jumlah }}</td>
                                    <td class="text-left py-3 px-4">{{ $item->lokasi }}</td>
                                    <td class="text-left py-3 px-4">{{ $item->keterangan ?? '-' }}</td>
                                    <td class="text-left py-3 px-4">
                                        <a href="{{ route('items.edit', $item->id) }}" class="text-blue-500 hover:text-blue-700 font-bold">Edit</a>
                                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Yakin hapus?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 font-bold">Hapus</button>
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