<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Master Pesan Link Zoom
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                    @endif

                    <h3 class="text-lg font-semibold mb-4">Kelola Master Pesan per Bidang</h3>
                    
                    <form method="POST" action="{{ route('zoom.master_pesan.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pilih Bidang</label>
                                <select name="bidang_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Bidang --</option>
                                    @foreach($bidang as $b)
                                        <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Master Pesan</label>
                                <textarea name="pesan" rows="6" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan template pesan untuk Link Zoom..."></textarea>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Simpan Master Pesan
                            </button>
                        </div>
                    </form>

                    <div class="mt-8">
                        <h4 class="text-md font-semibold mb-4">Master Pesan yang Tersimpan</h4>
                        <div class="space-y-4">
                            @foreach($bidang as $b)
                                @php
                                    $pesan = session('master_pesan_' . $b->id);
                                @endphp
                                @if($pesan)
                                <div class="border rounded-lg p-4">
                                    <h5 class="font-semibold text-gray-800">{{ $b->nama }}</h5>
                                    <p class="text-gray-600 mt-2">{{ $pesan }}</p>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
