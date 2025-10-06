<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Stok untuk: {{ $item->nama_barang }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <p class="mb-2">Stok saat ini: <span class="font-bold">{{ $item->jumlah }}</span></p>
                    <p class="mb-4">Kode Barang: <span class="font-mono">{{ $item->kode_barang }}</span></p>

                    <form method="POST" action="{{ route('items.storeStock', $item) }}">
                        @csrf
                        
                        <div>
                            <x-input-label for="jumlah_masuk" value="Jumlah Stok Masuk" />
                            <x-text-input id="jumlah_masuk" class="block mt-1 w-full" type="number" name="jumlah_masuk" min="1" required autofocus />
                            <x-input-error :messages="$errors->get('jumlah_masuk')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('items.index') }}" class="text-sm text-gray-600 underline hover:text-gray-900 mr-4">
                                Batal
                            </a>

                            <x-primary-button>
                                {{ __('Simpan Stok') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>