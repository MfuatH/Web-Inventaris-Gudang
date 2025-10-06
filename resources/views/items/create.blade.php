<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Barang Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('items.store') }}">
                        @csrf
                        <div>
                            <label for="nama_barang">Nama Barang</label>
                            <input id="nama_barang" class="block mt-1 w-full" type="text" name="nama_barang" required autofocus />
                        </div>

                        <div class="mt-4">
                            <label for="jumlah">Jumlah</label>
                            <input id="jumlah" class="block mt-1 w-full" type="number" name="jumlah" required />
                        </div>

                        <div class="mt-4">
                            <label for="lokasi">Lokasi</label>
                            <input id="lokasi" class="block mt-1 w-full" type="text" name="lokasi" required />
                        </div>

                        <div class="mt-4">
                            <label for="keterangan">Keterangan (Opsional)</label>
                            <textarea id="keterangan" name="keterangan" class="block mt-1 w-full"></textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>