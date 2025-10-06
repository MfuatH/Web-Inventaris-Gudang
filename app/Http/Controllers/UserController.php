<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bidang; // Diubah: Tambahkan model Bidang
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        // Eager load relasi 'bidang' untuk efisiensi query
        $users = User::with('bidang')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        // Diubah: Ambil semua data bidang untuk ditampilkan di form
        $bidang = Bidang::all();
        return view('users.create', compact('bidang'));
    }

    public function store(Request $request)
    {
        // Diubah: Validasi disesuaikan dengan skema baru
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['super_admin', 'admin_barang', 'user'])],
            // id_bidang wajib diisi jika role BUKAN super_admin & harus ada di tabel bidang
            'id_bidang' => ['required_if:role,admin_barang', 'required_if:role,user', 'nullable', 'exists:bidang,id'],
        ]);

        // Diubah: Logika penyimpanan disesuaikan
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            // Jika role adalah super_admin, id_bidang = null
            'id_bidang' => $request->role === 'super_admin' ? null : $request->id_bidang,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        if ($user->role == 'super_admin' && auth()->id() != $user->id) {
            abort(403, 'Super Admin tidak bisa diedit.');
        }
        
        // Diubah: Ambil semua data bidang untuk form edit
        $bidang = Bidang::all();
        return view('users.edit', compact('user', 'bidang'));
    }

    public function update(Request $request, User $user)
    {
        // Diubah: Validasi disesuaikan dengan skema baru
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['super_admin', 'admin_barang', 'user'])],
            'id_bidang' => ['required_if:role,admin_barang', 'required_if:role,user', 'nullable', 'exists:bidang,id'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        // Diubah: Logika update data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'id_bidang' => $request->role === 'super_admin' ? null : $request->id_bidang,
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