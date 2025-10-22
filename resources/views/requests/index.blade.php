<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5 text-blue-600"></i>
            Approval Request Barang
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Peminta</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">NIP</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">No HP</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Barang</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Jumlah</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tanggal</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="text-gray-700">
                                @forelse ($requests as $request)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ optional($request->user)->name ?? $request->nama_pemohon }}</td>
                                    <td class="py-3 px-4">{{ $request->nip ?? '-' }}</td>
                                    <td class="py-3 px-4">{{ $request->no_hp ?? '-' }}</td>
                                    <td class="py-3 px-4">{{ $request->item->nama_barang }}</td>
                                    <td class="py-3 px-4">{{ $request->jumlah_request }}</td>
                                    <td class="py-3 px-4">{{ $request->created_at->format('d-m-Y') }}</td>

                                    <td class="py-3 px-4">
                                        @if($request->status == 'pending')
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">Pending</span>
                                        @elseif($request->status == 'approved')
                                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">Approved</span>
                                        @elseif($request->status == 'rejected')
                                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">Rejected</span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap">
                                        @if($request->status == 'pending')
                                        <div class="flex space-x-2">
                                            <!-- Tombol Approve -->
                                            <form action="{{ route('requests.approve', $request->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" 
                                                    class="flex items-center gap-1 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-md font-medium text-xs hover:bg-blue-200 active:bg-blue-300 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Approve
                                                </button>
                                            </form>

                                            <!-- Tombol Reject -->
                                            <form action="{{ route('requests.reject', $request->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menolak request ini?')">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" 
                                                    class="flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-700 rounded-md font-medium text-xs hover:bg-red-200 active:bg-red-300 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">Tidak ada permintaan barang yang masuk.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
