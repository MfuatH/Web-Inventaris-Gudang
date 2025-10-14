<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // ... fungsi index() dan create() tidak berubah ...
    public function index(Request $request)
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $bidang = Bidang::orderBy('nama')->get();
        return view('users.create', compact('bidang'));
    }

    public function store(Request $request)
    {
        // Validasi: bidang diambil dari tabel bidang (bidang_id)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin_barang,user'], // Super Admin dihapus dari validasi
            'bidang_id' => ['required', 'exists:bidang,id'],
        ]);

        $bidang = Bidang::find($request->bidang_id);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'bidang' => $bidang?->nama,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    // ... fungsi edit() dan update() juga perlu disesuaikan ...
    public function edit(User $user)
    {
        // Jangan biarkan orang mengedit Super Admin
        if ($user->role == 'super_admin' && auth()->id() != $user->id) {
            abort(403, 'Super Admin tidak bisa diedit.');
        }
        $bidang = Bidang::orderBy('nama')->get();
        return view('users.edit', compact('user', 'bidang'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:admin_barang,user'],
            'bidang_id' => ['required', 'exists:bidang,id'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $bidang = Bidang::find($request->bidang_id);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'bidang' => $bidang?->nama,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'super_admin') {
            return back()->withErrors(['error' => 'Super Admin tidak dapat dihapus.']);
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}