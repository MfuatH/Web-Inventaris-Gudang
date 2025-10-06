<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buat Permintaan Barang Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form method="POST" action="{{ route('requests.store') }}">
                        @csrf

                        <div x-data="{ fields: [{ item_id: '', jumlah_request: 1 }] }">
                            
                            <template x-for="(field, index) in fields" :key="index">
                                <div class="grid grid-cols-12 gap-4 items-end mb-4 p-4 border rounded-lg">
                                    <div class="col-span-6">
                                        <label :for="'item_id_' + index" class="block font-medium text-sm text-gray-700">Pilih Barang</label>
                                        <select x-model="field.item_id" :name="'items[' + index + '][item_id]'" :id="'item_id_' + index + ''" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required>
                                            <option value="">-- Pilih Barang --</option>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->nama_barang }} (Stok: {{ $item->jumlah }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-span-4">
                                        <label :for="'jumlah_request_' + index" class="block font-medium text-sm text-gray-700">Jumlah</label>
                                        <input x-model="field.jumlah_request" :name="'items[' + index + '][jumlah_request]'" :id="'jumlah_request_' + index + ''" type="number" min="1" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required />
                                    </div>

                                    <div class="col-span-2">
                                        <button type="button" @click="fields.splice(index, 1)" x-show="fields.length > 1" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <div class="mt-4">
                                <button type="button" @click="fields.push({ item_id: '', jumlah_request: 1 })" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    + Tambah Barang
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 border-t pt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Kirim Semua Permintaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>