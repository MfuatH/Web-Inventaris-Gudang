<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit User: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- Menampilkan error validasi jika ada --}}
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

                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Nama --}}
                        <div>
                            <label for="name">Nama</label>
                            <input id="name" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" name="name" value="{{ old('name', $user->name) }}" required />
                        </div>

                        {{-- Email --}}
                        <div class="mt-4">
                            <label for="email">Email</label>
                            <input id="email" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="email" name="email" value="{{ old('email', $user->email) }}" required />
                        </div>

                        {{-- =================== KODE BARU DITAMBAHKAN =================== --}}
                        {{-- Nomor HP --}}
                        <div class="mt-4">
                            <label for="no_hp">Nomor HP</label>
                            <input id="no_hp" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" />
                        </div>
                        {{-- ============================================================= --}}

                        {{-- Role --}}
                        <div class="mt-4">
                            <label for="role">Role</label>
                            <select name="role" id="role" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                {{-- Opsi 'User' dihapus sesuai permintaan --}}
                                <option value="admin_barang" @if(old('role', $user->role) == 'admin_barang') selected @endif>Admin Barang</option>
                                <option value="super_admin" @if(old('role', $user->role) == 'super_admin') selected @endif>Super Admin</option>
                            </select>
                        </div>

                        {{-- Bidang --}}
                        <div class="mt-4">
                            <label for="bidang">Bidang (Hanya jika role 'Admin Barang')</label>
                            {{-- Dropdown ini dibuat dinamis --}}
                            <select name="bidang" id="bidang" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">-- Tidak Ada --</option>
                                @foreach($bidang as $b)
                                    <option value="{{ $b->nama }}" @if(old('bidang', $user->bidang) == $b->nama) selected @endif>
                                        {{ $b->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Password --}}
                        <div class="mt-4">
                            <label for="password">Password Baru (Kosongkan jika tidak diubah)</label>
                            <input id="password" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="password" name="password" />
                        </div>
                        <div class="mt-4">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input id="password_confirmation" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="password" name="password_confirmation" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
