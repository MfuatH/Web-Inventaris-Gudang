<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;

class BidangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bidang = Bidang::latest()->paginate(10);
        // Nanti kita buat view-nya di: resources/views/bidang/index.blade.php
        return view('bidang.index', compact('bidang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Nanti kita buat view-nya di: resources/views/bidang/create.blade.php
        return view('bidang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_bidang' => 'required|string|max:255|unique:bidang,nama_bidang',
            'deskripsi' => 'nullable|string',
        ]);

        Bidang::create($request->all());

        return redirect()->route('bidang.index')
                         ->with('success', 'Bidang baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bidang $bidang)
    {
        // Nanti kita buat view-nya di: resources/views/bidang/edit.blade.php
        return view('bidang.edit', compact('bidang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bidang $bidang)
    {
        $request->validate([
            'nama_bidang' => 'required|string|max:255|unique:bidang,nama_bidang,' . $bidang->id,
            'deskripsi' => 'nullable|string',
        ]);

        $bidang->update($request->all());

        return redirect()->route('bidang.index')
                         ->with('success', 'Data bidang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bidang $bidang)
    {
        // Tambahkan pengecekan jika bidang masih memiliki user
        if ($bidang->users()->count() > 0) {
            return back()->withErrors(['error' => 'Bidang tidak dapat dihapus karena masih memiliki user.']);
        }
        
        $bidang->delete();

        return redirect()->route('bidang.index')
                         ->with('success', 'Bidang berhasil dihapus.');
    }
}