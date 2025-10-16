<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <i data-lucide="video" class="w-5 h-5 text-blue-600"></i>
            Approval Request Link Zoom
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    {{-- Bagian Notifikasi dan Petunjuk (tidak diubah) --}}
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg shadow">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-800 rounded-lg shadow">
                            <strong class="font-bold">Oops! Terjadi kesalahan.</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="font-semibold text-blue-800">💡 Cara Menggunakan Template</h4>
                        <p class="text-sm text-blue-700 mt-1">
                            Gunakan placeholder di bawah ini di dalam pesan Anda. Sistem akan otomatis menggantinya dengan data yang sesuai saat request disetujui.
                        </p>
                        <div class="mt-2 text-sm space-y-1">
                            <div><code class="bg-gray-200 text-gray-800 px-2 py-1 rounded">@nama</code>: Nama pemohon.</div>
                            <div><code class="bg-gray-200 text-gray-800 px-2 py-1 rounded">@kegiatan</code>: Nama kegiatan/keterangan.</div>
                            <div><code class="bg-gray-200 text-gray-800 px-2 py-1 rounded">@tanggal</code>: Tanggal pelaksanaan.</div>
                            <div><code class="bg-gray-200 text-gray-800 px-2 py-1 rounded">@link</code>: Link Zoom yang disetujui.</div>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Kelola Master Pesan</h3>
                    
                    {{-- Bagian Form (tidak diubah) --}}
                    <form method="POST" action="{{ route('zoom.master_pesan.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 gap-6 mb-6">
                            @if(Auth::user()->role === 'super_admin')
                                <div>
                                    <label for="bidang_id" class="block text-sm font-medium text-gray-700">Pilih Bidang</label>
                                    <select id="bidang_id" name="bidang_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                        <option value="" disabled selected>-- Pilih Bidang --</option>
                                        @foreach($bidang as $b)
                                            <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="bidang_id" value="{{ Auth::user()->getBidang->id ?? '' }}">
                            @endif
                            
                            <div>
                                <label for="pesan" class="block text-sm font-medium text-gray-700">Master Pesan</label>
                                <textarea id="pesan" name="pesan" rows="6" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                                placeholder="Contoh: Halo @nama, permintaan link Zoom untuk kegiatan '@kegiatan' pada tanggal @tanggal telah disetujui. Berikut linknya: @link. Terima kasih." required></textarea>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Simpan Master Pesan
                            </button>
                        </div>
                    </form>

                    {{-- =================== KUNCI PERUBAHAN ADA DI SINI =================== --}}
                    <div class="mt-10 pt-6 border-t border-gray-200">
                        <h4 class="text-md font-semibold mb-4 text-gray-800">Master Pesan yang Tersimpan</h4>
                        <div class="space-y-4">
                            
                            @if(Auth::user()->role === 'super_admin')
                                {{-- Jika SUPER ADMIN, tampilkan semua template dari semua bidang --}}
                                @forelse($bidang->whereNotNull('pesan_template') as $b)
                                    <div class="border rounded-lg p-4 bg-gray-50">
                                        <h5 class="font-semibold text-gray-900">{{ $b->nama }}</h5>
                                        <p class="text-gray-600 mt-2 whitespace-pre-wrap">{{ $b->pesan_template }}</p>
                                    </div>
                                @empty
                                    <div class="text-center text-gray-500 py-4">
                                        Belum ada master pesan yang disimpan.
                                    </div>
                                @endforelse

                            @else
                                {{-- Jika ADMIN BIASA, hanya tampilkan template miliknya --}}
                                @if(Auth::user()->getBidang && Auth::user()->getBidang->pesan_template)
                                    <div class="border rounded-lg p-4 bg-gray-50">
                                        <h5 class="font-semibold text-gray-900">{{ Auth::user()->getBidang->nama }}</h5>
                                        <p class="text-gray-600 mt-2 whitespace-pre-wrap">{{ Auth::user()->getBidang->pesan_template }}</p>
                                    </div>
                                @else
                                    <div class="text-center text-gray-500 py-4">
                                        Belum ada master pesan yang disimpan untuk bidang Anda.
                                    </div>
                                @endif
                            @endif

                        </div>
                    </div>
                    {{-- =================== AKHIR PERUBAHAN =================== --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

