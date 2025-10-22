<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <i data-lucide="video" class="w-5 h-5 text-blue-600"></i>
            Approval Request Link Zoom
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                    @endif
                    @if(session('info'))
                        <div class="mb-4 p-3 bg-blue-100 text-blue-800 rounded whitespace-pre-wrap">{{ session('info') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
                    @endif

                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Peminta</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">No HP</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Bidang</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Rapat</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Jadwal Mulai</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Link Zoom</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Status</th> {{-- <-- KOLOM BARU --}}
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse ($requests as $request)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $request->nama_pemohon }}</td>
                                <td class="py-3 px-4">{{ $request->no_hp }}</td>
                                <td class="py-3 px-4">{{ optional($request->bidang)->nama ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $request->nama_rapat ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $request->jadwal_mulai->format('d-m-Y H:i') }}</td>
                                <td class="py-3 px-4">
                                    @if($request->link_zoom)
                                        <a href="{{ $request->link_zoom }}" target="_blank" class="text-blue-600 hover:underline">Link Tersedia</a>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                                {{-- =================== KODE BARU UNTUK MENAMPILKAN STATUS =================== --}}
                                <td class="py-3 px-4">
                                    @if($request->status == 'approved')
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                            Approved
                                        </span>
                                    @elseif($request->status == 'pending')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                            Pending
                                        </span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    @endif
                                </td>
                                {{-- =================== AKHIR KODE BARU =================== --}}
                                <td class="py-3 px-4">
                                    {{-- Aksi hanya ditampilkan jika status masih pending --}}
                                    @if($request->status == 'pending')
                                        <div class="flex space-x-2">
                                            @if($request->link_zoom)
                                                <form action="{{ route('zoom.approve', $request->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="font-semibold text-green-600 hover:text-green-800">Approve</button>
                                                </form>
                                            @else
                                                <button onclick="openLinkModal({{ $request->id }})" class="font-semibold text-blue-600 hover:text-blue-800">Masukkan Link</button>
                                            @endif
                                            <form action="{{ route('zoom.reject', $request->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="font-semibold text-red-600 hover:text-red-800" onclick="return confirm('Yakin ingin menolak request ini?')">Reject</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-gray-400">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Tidak ada permintaan Link Zoom yang masuk.</td> {{-- <-- Colspan diubah --}}
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk memasukkan Link Zoom -->
    <div id="linkModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full mx-4">
                <h3 class="text-lg font-semibold mb-4">Masukkan Link Zoom</h3>
                <form id="linkForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="linkZoomInput" class="block text-sm font-medium text-gray-700 mb-2">Link Zoom</label>
                        <input type="url" name="link_zoom" id="linkZoomInput" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="https://zoom.us/j/..." required>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeLinkModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openLinkModal(requestId) {
            document.getElementById('linkModal').classList.remove('hidden');
            // Pastikan URL yang dibuat benar, sesuai dengan route Anda
            document.getElementById('linkForm').action = `{{ url('/zoom') }}/${requestId}/add-link`;
        }

        function closeLinkModal() {
            document.getElementById('linkModal').classList.add('hidden');
            document.getElementById('linkZoomInput').value = '';
        }

        // Close modal when clicking outside
        document.getElementById('linkModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLinkModal();
            }
        });
    </script>
</x-app-layout>
