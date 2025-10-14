<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Request Link Zoom</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-image: url('{{ asset('images/air.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Poppins', sans-serif;
        }
        /* Custom style to show date picker icon */
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 24 24"><path fill="%236B7280" d="M20 3h-1V1h-2v2H7V1H5v2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H4V8h16v13z"/></svg>');
            opacity: 0.7;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    @if(session('success'))
        <div id="success-alert" class="fixed top-5 right-5 z-50 bg-green-500 text-white py-2 px-4 rounded-xl shadow-lg flex items-center transition-transform transform translate-x-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
            <button id="close-alert" class="ml-4 text-white font-bold">&times;</button>
        </div>
    @endif

    <div style="backdrop-filter: blur(12px);" class="bg-blur rounded-2xl shadow-2xl overflow-hidden max-w-6xl w-full">
        
        <div class="p-6 relative min-h-[600px] md:grid md:grid-cols-12 md:gap-8 md:items-center">

            <div class="hidden md:block md:col-span-5 text-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 mx-auto mb-6"> 
                <h2 class="text-3xl font-bold text-gray-800 mb-8">Selamat Datang</h2>
                <img src="{{ asset('images/meet.png') }}" class="max-w-xs w-full mx-auto">
            </div>

            <div class="w-full md:col-span-7">
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h1 class="text-lg font-bold mb-4 text-gray-700 text-center">Form Request Link Zoom</h1>

                    @if($errors->any())
                        <div class="mb-3 p-2 bg-red-100 text-red-700 text-sm rounded">
                            <ul class="list-disc ml-4">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('guest.linkzoom.store') }}">
                        @csrf
                        <div class="space-y-3 mb-4">
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg></span>
                                <input name="nama_pemohon" value="{{ old('nama_pemohon') }}" class="w-full border border-gray-300 rounded-md px-10 py-1.5 text-sm" placeholder="Nama Pemohon" required />
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm_V6h12v8H4V6z" clip-rule="evenodd" /></svg></span>
                                <select name="bidang_id" class="w-full border border-gray-300 rounded-md px-10 py-1.5 text-sm appearance-none" required>
                                    <option value="">-- Pilih Bidang --</option>
                                    @foreach($bidang as $b)
                                        <option value="{{ $b->id }}" @if(old('bidang_id')===$b->id) selected @endif>{{ $b->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h6" /></svg></span>
                                    <input name="nip" value="{{ old('nip') }}" class="w-full border border-gray-300 rounded-md px-10 py-1.5 text-sm" placeholder="NIP (opsional)" />
                                </div>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" /></svg></span>
                                    <input name="no_hp" value="{{ old('no_hp') }}" class="w-full border border-gray-300 rounded-md px-10 py-1.5 text-sm" placeholder="Nomor Hp" required />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" /></svg></span>
                                    <input type="datetime-local" name="jadwal_mulai" value="{{ old('jadwal_mulai') }}" class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-1.5 text-sm" placeholder="Jadwal Mulai" required />
                                </div>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" /></svg></span>
                                    <input type="datetime-local" name="jadwal_selesai" value="{{ old('jadwal_selesai') }}" class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-1.5 text-sm" placeholder="Jadwal Selesai" />
                                </div>
                            </div>
                            <div>
                                <textarea name="keterangan" class="w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm" rows="2" placeholder="Keterangan Rapat (opsional)">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                        
                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <a href="{{ route('welcome') }}" class="w-full text-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-md hover:bg-gray-100 transition-colors">Kembali</a>
                            <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 transition-colors">Kirim Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alert = document.getElementById('success-alert');
            if (alert) {
                // Tampilkan notifikasi dengan animasi slide-in
                setTimeout(() => {
                    alert.style.transform = 'translateX(0)';
                }, 100);

                // Sembunyikan notifikasi secara otomatis setelah 5 detik
                setTimeout(() => {
                    alert.style.transform = 'translateX(100%)';
                }, 5000);

                // Fungsi untuk menutup notifikasi jika tombol 'x' diklik
                const closeButton = document.getElementById('close-alert');
                closeButton.addEventListener('click', function () {
                    alert.style.transform = 'translateX(100%)';
                });
            }
        });
    </script>
</body>
</html>