<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tambah Barang Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('items.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Barang -->
                            <div class="md:col-span-2">
                                <x-input-label for="nama_barang" value="Nama Barang" />
                                <x-text-input id="nama_barang" class="block mt-1 w-full" type="text" name="nama_barang" :value="old('nama_barang')" required autofocus />
                            </div>

                            <!-- Jumlah -->
                            <div>
                                <x-input-label for="jumlah" value="Jumlah Awal" />
                                <x-text-input id="jumlah" class="block mt-1 w-full" type="number" name="jumlah" :value="old('jumlah')" required />
                            </div>

                            <!-- Satuan -->
                            <div>
                                <x-input-label for="satuan" value="Satuan (e.g., unit, pack, box)" />
                                <x-text-input id="satuan" class="block mt-1 w-full" type="text" name="satuan" :value="old('satuan', 'unit')" />
                            </div>

                            <!-- Lokasi -->
                            <div class="md:col-span-2">
                                <x-input-label for="lokasi" value="Lokasi Penyimpanan" />
                                <x-text-input id="lokasi" class="block mt-1 w-full" type="text" name="lokasi" :value="old('lokasi')" required />
                            </div>

                            <!-- Keterangan -->
                            <div class="md:col-span-2">
                                <x-input-label for="keterangan" value="Keterangan (Opsional)" />
                                <textarea id="keterangan" name="keterangan" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                             <a href="{{ route('items.index') }}" class="text-sm text-gray-600 dark:text-gray-400 underline hover:text-gray-900 dark:hover:text-gray-100 mr-4">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Barang') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
