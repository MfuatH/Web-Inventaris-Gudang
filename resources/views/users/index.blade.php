<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
            Manajemen User
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    {{-- Tombol Tambah User --}}
                    <div class="mb-4">
                        <a href="{{ route('users.create') }}" 
                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah User
                        </a>
                    </div>

                    {{-- Tabel Data User --}}
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Email</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nomor HP</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Role</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse ($users as $user)
                            <tr>
                                <td class="py-3 px-4">{{ $user->name }}</td>
                                <td class="py-3 px-4">{{ $user->email }}</td>
                                <td class="py-3 px-4">{{ $user->no_hp ?? '-' }}</td> {{-- disesuaikan --}}
                                <td class="py-3 px-4">{{ $user->role }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('users.edit', $user->id) }}" class="text-yellow-500 hover:text-yellow-700">Edit</a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus pengguna ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Tidak ada data pengguna.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
