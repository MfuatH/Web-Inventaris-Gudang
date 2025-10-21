<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule; // <-- Pastikan ini ada
use Illuminate\Validation\Rules; // <-- Dan ini juga

class UserController extends Controller
{
    // ... method index(), create() Anda ...
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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin_barang,super_admin'],
            'bidang_id' => ['required', 'exists:bidang,id'],
        ]);

        // Ambil nama bidang dari ID
        $bidang = Bidang::find($request->bidang_id);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'bidang' => $bidang->nama, // Simpan nama bidang
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }


    public function edit(User $user)
    {
        // Jangan biarkan orang mengedit Super Admin lain
        if ($user->role == 'super_admin' && auth()->id() != $user->id) {
            abort(403, 'Super Admin tidak bisa diedit.');
        }
        $bidang = Bidang::orderBy('nama')->get();
        return view('users.edit', compact('user', 'bidang'));
    }

    /**
     * =========================================================
     * KODE YANG DIPERBAIKI ADA DI METHOD UPDATE DI BAWAH INI
     * =========================================================
     */
    public function update(Request $request, User $user)
    {
        // 1. Validasi Input yang Disesuaikan
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            // Role 'user' dihapus dari validasi
            'role' => 'required|in:admin_barang,super_admin',
            'bidang' => ['nullable', 'string', 'exists:bidang,nama'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Siapkan data untuk diupdate (dengan logika role)
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'no_hp' => $validated['no_hp'],
            // Logika disesuaikan: hanya admin_barang yang punya bidang
            'bidang' => ($validated['role'] === 'admin_barang') ? $validated['bidang'] : null,
        ];

        // 3. Hanya update password jika diisi
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // 4. Lakukan update
        $user->update($updateData);

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
