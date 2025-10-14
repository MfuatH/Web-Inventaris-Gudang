<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Approval Request Barang
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
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
                            <tr>
                                <td class="py-3 px-4">{{ optional($request->user)->name ?? $request->nama_pemohon }}</td>
                                <td class="py-3 px-4">{{ $request->nip ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $request->no_hp ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $request->item->nama_barang }}</td>
                                <td class="py-3 px-4">{{ $request->jumlah_request }}</td>
                                <td class="py-3 px-4">{{ $request->created_at->format('d-m-Y') }}</td>
                                <td class="py-3 px-4">{{ ucfirst($request->status) }}</td>
                                <td class="py-3 px-4">
                                    @if($request->status == 'pending')
                                    <form action="{{ route('requests.approve', $request->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-green-500 hover:text-green-700">Approve</button>
                                    </form>
                                    @else
                                    -
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
                    <div class="mt-4">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>