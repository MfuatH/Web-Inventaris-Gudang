<x-app-layout>
        <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <i data-lucide="boxes" class="w-5 h-5 text-blue-600"></i>
            Manajemen Barang
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('items.update', $item->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <x-input-label for="nama_barang" :value="__('Nama Barang')" />
                            <x-text-input id="nama_barang" class="block mt-1 w-full" type="text" name="nama_barang" value="{{ old('nama_barang', $item->nama_barang) }}" required autofocus />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="satuan" :value="__('Satuan')" />
                            <select id="satuan" name="satuan" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Satuan --</option>
                                <option value="Unit" @if(old('satuan', $item->satuan) == 'Unit') selected @endif>Unit</option>
                                <option value="Buah" @if(old('satuan', $item->satuan) == 'Buah') selected @endif>Buah</option>
                                <option value="Pcs" @if(old('satuan', $item->satuan) == 'Pcs') selected @endif>Pcs</option>
                                <option value="Box" @if(old('satuan', $item->satuan) == 'Box') selected @endif>Box</option>
                                <option value="Rim" @if(old('satuan', $item->satuan) == 'Rim') selected @endif>Rim</option>
                                <option value="Set" @if(old('satuan', $item->satuan) == 'Set') selected @endif>Set</option>
                                <option value="Roll" @if(old('satuan', $item->satuan) == 'Roll') selected @endif>Roll</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="jumlah" :value="__('Jumlah')" />
                            <x-text-input id="jumlah" class="block mt-1 w-full" type="number" name="jumlah" value="{{ old('jumlah', $item->jumlah) }}" required />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="lokasi" :value="__('Lokasi')" />
                            <x-text-input id="lokasi" class="block mt-1 w-full" type="text" name="lokasi" value="{{ old('lokasi', $item->lokasi) }}" required />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="keterangan" :value="__('Keterangan (Opsional)')" />
                            <textarea id="keterangan" name="keterangan" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('keterangan', $item->keterangan) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>