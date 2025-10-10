<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stok Barang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto py-12 px-4">
        <div class="mb-6">
            <a href="{{ route('guest.dashboard') }}" class="text-blue-600 hover:underline">&larr; Kembali</a>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h1 class="text-xl font-bold mb-4">Stok Barang Tersedia</h1>
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Nama Barang</th>
                        <th class="px-4 py-2 text-left">Satuan</th>
                        <th class="px-4 py-2 text-left">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($items as $item)
                        <tr>
                            <td class="px-4 py-2">{{ $item->nama_barang }}</td>
                            <td class="px-4 py-2">{{ $item->satuan ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $item->jumlah }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</body>
</html>


