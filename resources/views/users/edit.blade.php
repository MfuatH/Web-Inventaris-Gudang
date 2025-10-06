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
                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name">Nama</label>
                            <input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ old('name', $user->name) }}" required />
                        </div>

                        <div class="mt-4">
                            <label for="email">Email</label>
                            <input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ old('email', $user->email) }}" required />
                        </div>

                        <div class="mt-4">
                            <label for="role">Role</label>
                            <select name="role" id="role" class="block mt-1 w-full">
                                <option value="user" @if(old('role', $user->role) == 'user') selected @endif>User</option>
                                <option value="admin_barang" @if(old('role', $user->role) == 'admin_barang') selected @endif>Admin Barang</option>
                                <option value="super_admin" @if(old('role', $user->role) == 'super_admin') selected @endif>Super Admin</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="bidang">Bidang (Hanya jika role 'User')</label>
                            <select name="bidang" id="bidang" class="block mt-1 w-full">
                                <option value="">-- Pilih Bidang --</option>
                                <option value="sekretariat" @if(old('bidang', $user->bidang) == 'sekretariat') selected @endif>Sekretariat</option>
                                <option value="psda" @if(old('bidang', $user->bidang) == 'psda') selected @endif>PSDA</option>
                                <option value="irigasi" @if(old('bidang', $user->bidang) == 'irigasi') selected @endif>Irigasi</option>
                                <option value="swp" @if(old('bidang', $user->bidang) == 'swp') selected @endif>SWP</option>
                                <option value="binfat" @if(old('bidang', $user->bidang) == 'binfat') selected @endif>BINFAT</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="password">Password Baru (Kosongkan jika tidak diubah)</label>
                            <input id="password" class="block mt-1 w-full" type="password" name="password" />
                        </div>
                        <div class="mt-4">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
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