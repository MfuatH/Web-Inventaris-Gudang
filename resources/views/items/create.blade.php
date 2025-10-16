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
                    <form method="POST" action="{{ route('items.store') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <x-input-label for="nama_barang" :value="__('Nama Barang')" />
                            <x-text-input id="nama_barang" class="block mt-1 w-full" type="text" name="nama_barang" :value="old('nama_barang')" required autofocus />
                        </div>

                        <div class="mb-4" x-data="{ satuan: '{{ old('satuan', 'Unit') }}', customSatuan: {{ old('satuan') === 'Lainnya' ? 'true' : 'false' }} }">
                            <x-input-label for="satuan_select" :value="__('Satuan')" />
                            <select id="satuan_select" name="satuan" x-model="satuan" @change="customSatuan = (satuan === 'Lainnya')"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option>Unit</option>
                                <option>Buah</option>
                                <option>Pcs</option>
                                <option>Box</option>
                                <option>Rim</option>
                                <option>Set</option>
                                <option>Roll</option>
                                <option value="Lainnya">Lainnya...</option>
                            </select>

                            <div x-show="customSatuan" class="mt-2">
                                <x-input-label for="satuan_custom" :value="__('Masukkan Satuan Kustom')" />
                                <x-text-input id="satuan_custom" name="satuan_custom" class="block mt-1 w-full" type="text" :value="old('satuan_custom')" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="jumlah" :value="__('Jumlah')" />
                            <x-text-input id="jumlah" class="block mt-1 w-full" type="number" name="jumlah" :value="old('jumlah')" required />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="lokasi" :value="__('Lokasi')" />
                            <x-text-input id="lokasi" class="block mt-1 w-full" type="text" name="lokasi" :value="old('lokasi')" required />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="keterangan" :value="__('Keterangan (Opsional)')" />
                            <textarea id="keterangan" name="keterangan" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('keterangan') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>