@csrf
<div>
    <label for="nama_bidang" class="block font-medium text-sm text-gray-700">Nama Bidang</label>
    <input type="text" name="nama_bidang" id="nama_bidang" value="{{ old('nama_bidang', $bidang->nama_bidang ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
    @error('nama_bidang')
        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
    @enderror
</div>

<div class="mt-4">
    <label for="deskripsi" class="block font-medium text-sm text-gray-700">Deskripsi (Opsional)</label>
    <textarea name="deskripsi" id="deskripsi" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('deskripsi', $bidang->deskripsi ?? '') }}</textarea>
    @error('deskripsi')
        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
    @enderror
</div>

<div class="flex items-center justify-end mt-4">
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Simpan
    </button>
</div>