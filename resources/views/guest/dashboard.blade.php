<!DOCTYPE html>
<html>
<head>
<link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Guest Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-3xl mx-auto py-12 px-4">
        <div class="bg-white p-8 rounded shadow">
            <h1 class="text-2xl font-bold mb-6">Selamat Datang</h1>
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            <p class="mb-6">Silakan pilih aksi di bawah ini:</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('guest.stock') }}" class="block text-center p-6 border rounded hover:bg-gray-50">
                    <div class="text-lg font-semibold">Lihat Stok Barang</div>
                    <div class="text-sm text-gray-600 mt-2">Melihat ketersediaan stok saat ini</div>
                </a>
                <a href="{{ route('guest.requests.create') }}" class="block text-center p-6 border rounded hover:bg-gray-50">
                    <div class="text-lg font-semibold">Buat Request Barang</div>
                    <div class="text-sm text-gray-600 mt-2">Ajukan permintaan barang tanpa login</div>
                </a>
            </div>
            <div class="mt-8 text-center text-sm">
                <a class="text-blue-600 hover:underline" href="{{ url('/login') }}">Login Admin/User</a>
            </div>
        </div>
    </div>
</body>
</html>


