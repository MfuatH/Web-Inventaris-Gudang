<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Request Barang</title>
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
    </style>

    <script>
        let itemIndex = 1;

        function addRow() {
            const container = document.getElementById('itemsContainer');

            const newRow = document.createElement('div');
            newRow.classList.add('item-row', 'grid', 'grid-cols-12', 'gap-2');
            newRow.innerHTML = `
                <div class="col-span-5">
                    <select name="items[${itemIndex}][item_id]" class="w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm" required onchange="updateSatuan(this)">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" data-satuan="{{ $item->satuan }}">{{ $item->nama_barang }} (stok: {{ $item->jumlah }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-3">
                    <input type="number" min="1" name="items[${itemIndex}][jumlah_request]" class="w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm" placeholder="Jumlah" required />
                </div>
                <div class="col-span-3 flex items-center">
                    <span class="text-sm text-gray-600 satuan-display">-</span>
                </div>
                <div class="col-span-1 flex items-center">
                    <button type="button" class="w-full py-1.5 border rounded-md hover:bg-red-500 hover:text-white" onclick="removeRow(this)">-</button>
                </div>
            `;
            container.appendChild(newRow);
            itemIndex++;
        }

        function removeRow(button) {
            const row = button.closest('.item-row');
            row.remove();
            resetRowIndexes();
        }

        function resetRowIndexes() {
            const rows = document.querySelectorAll('#itemsContainer .item-row');
            rows.forEach((row, index) => {
                const selects = row.querySelectorAll('select');
                const inputs = row.querySelectorAll('input[type="number"]');
                if (selects.length > 0) selects[0].setAttribute('name', `items[${index}][item_id]`);
                if (inputs.length > 0) inputs[0].setAttribute('name', `items[${index}][jumlah_request]`);
            });
            itemIndex = rows.length;
        }

        function updateSatuan(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const satuan = selectedOption.getAttribute('data-satuan');
            const satuanDisplay = selectElement.closest('.item-row').querySelector('.satuan-display');
            satuanDisplay.textContent = satuan || '-';
        }
    </script>
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
        <div class="p-6 relative min-h-[600px]">

            {{-- LOGO DIPINDAH KE ATAS --}}
            <div class="w-full flex justify-center mb-4 md:hidden">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12">
            </div>

            {{-- Logo di kiri untuk tampilan desktop --}}
            
            <div class="hidden md:block absolute left-12 top-12 w-5/12 text-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 mx-auto mb-2"> 
                <h2 class="text-3xl font-bold text-gray-800 mb-1">Selamat Datang</h2>
                <img src="{{ asset('images/ils.png') }}" class="max-w-xs w-full mx-auto">
            </div>

            <div class="w-full md:w-6/12 md:ml-auto">
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h1 class="text-lg font-bold mb-4 text-gray-700 text-center">Form Request Barang</h1>

                    @if($errors->any())
                        <div class="mb-3 p-2 bg-red-100 text-red-700 text-sm rounded">
                            <ul class="list-disc ml-4">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('guest.requests.store') }}">
                        @csrf
                        <div class="space-y-3 mb-4">
                            <div class="relative">
                                <input name="nama_pemohon" value="{{ old('nama_pemohon') }}" class="w-full border border-gray-300 rounded-md px-10 py-1.5 text-sm" placeholder="Nama" required />
                            </div>
                            <div class="relative">
                                <select name="bidang_id" class="w-full border border-gray-300 rounded-md px-10 py-1.5 text-sm appearance-none" required>
                                    <option value="">-- Pilih Bidang --</option>
                                    @foreach($bidang as $b)
                                        <option value="{{ $b->id }}" @if(old('bidang_id')===$b->id) selected @endif>{{ $b->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="relative">
                                <input name="nip" value="{{ old('nip') }}" class="w-full border border-gray-300 rounded-md px-10 py-1.5 text-sm" placeholder="NIP (opsional)" />
                            </div>
                            <div class="relative">
                                <input name="no_hp" value="{{ old('no_hp') }}" class="w-full border border-gray-300 rounded-md px-10 py-1.5 text-sm" placeholder="Nomor Hp" required />
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <div class="mb-2 flex items-center justify-between">
                                <h2 class="font-semibold text-gray-700 text-sm">Barang yang diminta</h2>
                                <button type="button" onclick="addRow()" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-xs shadow">Tambah</button>
                            </div>
                            <div class="grid grid-cols-12 gap-2 mb-2 text-xs font-semibold text-gray-600">
                                <div class="col-span-5">Nama Barang</div>
                                <div class="col-span-3">Jumlah</div>
                                <div class="col-span-3">Satuan</div>
                                <div class="col-span-1">Aksi</div>
                            </div>
                            <div id="itemsContainer" class="space-y-2">
                                <div class="item-row grid grid-cols-12 gap-2">
                                    <div class="col-span-5">
                                        <select name="items[0][item_id]" class="w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm" required onchange="updateSatuan(this)">
                                            <option value="">-- Pilih Barang --</option>
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}" data-satuan="{{ $item->satuan }}">{{ $item->nama_barang }} (stok: {{ $item->jumlah }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-3">
                                        <input type="number" min="1" name="items[0][jumlah_request]" class="w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm" placeholder="Jumlah" required />
                                    </div>
                                    <div class="col-span-3 flex items-center">
                                        <span class="text-sm text-gray-600 satuan-display">-</span>
                                    </div>
                                    <div class="col-span-1 flex items-center">
                                        <button type="button" class="w-full py-1.5 border rounded-md hover:bg-red-500 hover:text-white" onclick="removeRow(this)">-</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <a href="{{ route('welcome') }}"  class="w-full text-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-md hover:bg-gray-100 transition-colors">Kembali</a>
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
                setTimeout(() => { alert.style.transform = 'translateX(0)'; }, 100);
                setTimeout(() => { alert.style.transform = 'translateX(100%)'; }, 5000);
                document.getElementById('close-alert').addEventListener('click', function () {
                    alert.style.transform = 'translateX(100%)';
                });
            }
        });
    </script>
</body>
</html>
