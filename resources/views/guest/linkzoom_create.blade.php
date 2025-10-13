<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Request Link Zoom</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-3xl mx-auto py-12 px-4">
        <div class="mb-6">
            <a href="{{ route('welcome') }}" class="text-blue-600 hover:underline">&larr; Kembali</a>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h1 class="text-xl font-bold mb-4">Form Request Link Zoom</h1>
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('guest.linkzoom.store') }}">
                @csrf
                <div class="grid grid-cols-1 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium">Nama</label>
                        <input name="nama_pemohon" value="{{ old('nama_pemohon') }}" class="mt-1 w-full border rounded px-3 py-2" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Bidang</label>
                        <select name="bidang_id" class="mt-1 w-full border rounded px-3 py-2" required>
                            <option value="">-- Pilih Bidang --</option>
                            @foreach($bidang as $b)
                                <option value="{{ $b->id }}" @if(old('bidang_id')===$b->id) selected @endif>{{ $b->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">NIP (opsional)</label>
                        <input name="nip" value="{{ old('nip') }}" class="mt-1 w-full border rounded px-3 py-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium">No HP</label>
                        <input name="no_hp" value="{{ old('no_hp') }}" class="mt-1 w-full border rounded px-3 py-2" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Jadwal Mulai</label>
                        <input type="datetime-local" name="jadwal_mulai" value="{{ old('jadwal_mulai') }}" class="mt-1 w-full border rounded px-3 py-2" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Jadwal Selesai (opsional)</label>
                        <input type="datetime-local" name="jadwal_selesai" value="{{ old('jadwal_selesai') }}" class="mt-1 w-full border rounded px-3 py-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Keterangan (opsional)</label>
                        <textarea name="keterangan" class="mt-1 w-full border rounded px-3 py-2" rows="3">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Kirim Request</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>


