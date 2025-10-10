<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Request Barang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script>
        function addRow() {
            const container = document.getElementById('itemsContainer');
            const template = document.getElementById('rowTemplate');
            const clone = template.content.cloneNode(true);
            container.appendChild(clone);
        }
        function removeRow(button) {
            const row = button.closest('.item-row');
            row.remove();
        }
    </script>
    <style>
        .hidden { display: none; }
    </style>
    </head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-3xl mx-auto py-12 px-4">
        <div class="mb-6">
            <a href="{{ route('guest.dashboard') }}" class="text-blue-600 hover:underline">&larr; Kembali</a>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h1 class="text-xl font-bold mb-4">Form Request Barang</h1>
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('guest.requests.store') }}">
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
                </div>

                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-semibold">Barang yang diminta</h2>
                    <button type="button" onclick="addRow()" class="px-3 py-1 bg-blue-600 text-white rounded">Tambah</button>
                </div>

                <div id="itemsContainer" class="space-y-3">
                    <div class="item-row grid grid-cols-12 gap-2">
                        <div class="col-span-8">
                            <select name="items[0][item_id]" class="w-full border rounded px-3 py-2" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_barang }} (stok: {{ $item->jumlah }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-3">
                            <input type="number" min="1" name="items[0][jumlah_request]" class="w-full border rounded px-3 py-2" placeholder="Jumlah" required />
                        </div>
                        <div class="col-span-1 flex items-center">
                            <button type="button" class="px-3 py-2 border rounded" onclick="removeRow(this)">-</button>
                        </div>
                    </div>
                </div>

                <template id="rowTemplate">
                    <div class="item-row grid grid-cols-12 gap-2">
                        <div class="col-span-8">
                            <select name="items[][item_id]" class="w-full border rounded px-3 py-2" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_barang }} (stok: {{ $item->jumlah }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-3">
                            <input type="number" min="1" name="items[][jumlah_request]" class="w-full border rounded px-3 py-2" placeholder="Jumlah" required />
                        </div>
                        <div class="col-span-1 flex items-center">
                            <button type="button" class="px-3 py-2 border rounded" onclick="removeRow(this)">-</button>
                        </div>
                    </div>
                </template>

                <div class="mt-6">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Kirim Request</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>


