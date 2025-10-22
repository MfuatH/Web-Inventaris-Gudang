<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <i data-lucide="video" class="w-5 h-5 text-blue-600"></i>
            Approval Request Link Zoom
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- Alert Messages --}}
                    @foreach (['success' => 'green', 'info' => 'blue', 'error' => 'red'] as $type => $color)
                        @if(session($type))
                            <div class="mb-4 p-3 bg-{{ $color }}-100 text-{{ $color }}-800 rounded">
                                {{ session($type) }}
                            </div>
                        @endif
                    @endforeach

                    {{-- Tabel --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 text-sm">
                            <thead class="bg-gray-800 text-white text-xs uppercase">
                                <tr>
                                    <th class="py-3 px-4 text-left">Peminta</th>
                                    <th class="py-3 px-4 text-left">No HP</th>
                                    <th class="py-3 px-4 text-left">Bidang</th>
                                    <th class="py-3 px-4 text-left">Nama Rapat</th>
                                    <th class="py-3 px-4 text-left">Jadwal Mulai</th>
                                    <th class="py-3 px-4 text-left">Link Zoom</th>
                                    <th class="py-3 px-4 text-left">Status</th>
                                    <th class="py-3 px-4 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($requests as $request)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-3 px-4">{{ $request->nama_pemohon }}</td>
                                        <td class="py-3 px-4">{{ $request->no_hp }}</td>
                                        <td class="py-3 px-4">{{ optional($request->bidang)->nama ?? '-' }}</td>
                                        <td class="py-3 px-4">{{ $request->nama_rapat ?? '-' }}</td>
                                        <td class="py-3 px-4">{{ $request->jadwal_mulai->format('d-m-Y H:i') }}</td>
                                        <td class="py-3 px-4">
                                            @if($request->link_zoom)
                                                <a href="{{ $request->link_zoom }}" target="_blank"
                                                   class="text-blue-600 hover:underline font-medium">
                                                    Link Tersedia
                                                </a>
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($request->status == 'approved')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                            @elseif($request->status == 'pending')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            @if($request->status == 'pending')
                                                <div class="flex gap-2">
                                                    @if($request->link_zoom)
                                                        <form action="{{ route('zoom.approve', $request->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit"
                                                                class="flex items-center gap-1 px-2.5 py-1 bg-green-100 text-green-700 text-xs rounded-md hover:bg-green-200 transition">
                                                                <i data-lucide="check" class="w-3.5 h-3.5"></i> Approve
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button onclick="openLinkModal({{ $request->id }})"
                                                            class="flex items-center gap-1 px-2.5 py-1 bg-blue-100 text-blue-700 text-xs rounded-md hover:bg-blue-200 transition">
                                                            <i data-lucide="link" class="w-3.5 h-3.5"></i> Masukkan Link
                                                        </button>
                                                    @endif

                                                    <form action="{{ route('zoom.reject', $request->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="button" onclick="confirmReject(this)"
                                                            class="flex items-center gap-1 px-2.5 py-1 bg-red-100 text-red-700 text-xs rounded-md hover:bg-red-200 transition">
                                                            <i data-lucide="x" class="w-3.5 h-3.5"></i> Reject
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-sm">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-gray-500">Tidak ada permintaan Link Zoom.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $requests->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal untuk Masukkan Link Zoom --}}
    <div id="linkModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full mx-4">
                <h3 class="text-lg font-semibold mb-4">Masukkan Link Zoom</h3>
                <form id="linkForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Link Zoom</label>
                        <input type="url" name="link_zoom" id="linkZoomInput"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                               placeholder="https://zoom.us/j/..." required>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeLinkModal()"
                            class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm">Batal</button>
                        <button type="submit"
                            class="px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function openLinkModal(id) {
            const modal = document.getElementById('linkModal');
            modal.classList.remove('hidden');
            document.getElementById('linkForm').action = `{{ url('/zoom') }}/${id}/add-link`;
        }

        function closeLinkModal() {
            document.getElementById('linkModal').classList.add('hidden');
            document.getElementById('linkZoomInput').value = '';
        }

        function confirmReject(btn) {
            if (confirm('Yakin ingin menolak permintaan ini?')) {
                btn.closest('form').submit();
            }
        }

        document.getElementById('linkModal').addEventListener('click', e => {
            if (e.target === e.currentTarget) closeLinkModal();
        });
    </script>
</x-app-layout>
