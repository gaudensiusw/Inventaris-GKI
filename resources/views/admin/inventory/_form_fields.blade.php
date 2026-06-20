<div class="flex flex-col gap-8">
    <!-- Informasi Dasar -->
    <div class="flex flex-col gap-4">
        <h3 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] border-b border-blue-50 pb-2">Informasi Dasar</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-500 ml-1">Nama Barang <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Proyektor Epson EB-X41" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-500 ml-1">Kategori Utama <span class="text-red-500">*</span></label>
                <select name="category_id" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- Field Upload Gambar -->
        <div class="flex flex-col gap-2 mt-1">
            <label class="text-xs font-bold text-slate-500 ml-1">Foto Barang</label>
            <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                <div class="flex-1 w-full">
                    <input type="file" name="image" accept="image/*" onchange="previewImage(this, '{{ $isEdit ? 'edit' : 'add' }}')"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                    <p class="text-[10px] text-slate-400 mt-1 ml-1">Format: JPG, JPEG, PNG, WEBP. Maks: 2MB.</p>
                </div>
                <div id="image_preview_container_{{ $isEdit ? 'edit' : 'add' }}" class="hidden w-20 h-20 rounded-2xl border border-slate-200 overflow-hidden relative shrink-0">
                    <img id="image_preview_{{ $isEdit ? 'edit' : 'add' }}" src="" class="w-full h-full object-cover">
                </div>
            </div>
            @if($isEdit)
                <!-- Existing Image Preview -->
                <div id="current_image_container_edit" class="hidden flex items-center gap-3 mt-2 p-3 bg-slate-50 rounded-2xl border border-slate-100">
                    <img id="current_image_preview_edit" src="" class="w-14 h-14 rounded-xl object-cover border border-slate-200">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Gambar Saat Ini</span>
                        <span class="text-xs font-medium text-slate-600">Terbaca dari database</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Lokasi dan Jumlah -->
    <div class="flex flex-col gap-4">
        <h3 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] border-b border-blue-50 pb-2">Lokasi dan Jumlah</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-500 ml-1">Lokasi Penyimpanan <span class="text-red-500">*</span></label>
                <select name="room_id" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
                    <option value="">Pilih Lokasi</option>
                    @foreach($rooms as $roomOption)
                        <option value="{{ $roomOption->id }}" {{ old('room_id', $activeRoomId ?? '') == $roomOption->id ? 'selected' : '' }}>{{ $roomOption->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-500 ml-1">Total Jumlah Barang <span class="text-red-500">*</span></label>
                <input type="number" id="qty_{{ $isEdit ? 'edit' : 'add' }}" name="quantity" value="{{ old('quantity', 1) }}" required onchange="updateLimits('{{ $isEdit ? 'edit' : 'add' }}')"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-bold text-blue-600">
            </div>
        </div>
    </div>

    <!-- Breakdown Kondisi -->
    <div class="flex flex-col gap-4 p-5 bg-slate-50/50 rounded-3xl border border-slate-100">
        <div class="flex justify-between items-center">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Breakdown Kondisi Fisik</h3>
            <span id="cond-count-{{ $isEdit ? 'edit' : 'add' }}" class="px-2 py-0.5 bg-blue-100 text-blue-600 rounded-md text-[10px] font-black">0/1</span>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Baik</label>
                <input type="number" name="qty_baik" value="{{ old('qty_baik', 0) }}" onchange="validateForm('{{ $isEdit ? 'edit' : 'add' }}')" class="breakdown-cond-{{ $isEdit ? 'edit' : 'add' }} w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400 text-sm font-bold">
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Rsk Ringan</label>
                <input type="number" name="qty_rusak_ringan" value="{{ old('qty_rusak_ringan', 0) }}" onchange="validateForm('{{ $isEdit ? 'edit' : 'add' }}')" class="breakdown-cond-{{ $isEdit ? 'edit' : 'add' }} w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400 text-sm font-bold">
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Rsk Berat</label>
                <input type="number" name="qty_rusak_berat" value="{{ old('qty_rusak_berat', 0) }}" onchange="validateForm('{{ $isEdit ? 'edit' : 'add' }}')" class="breakdown-cond-{{ $isEdit ? 'edit' : 'add' }} w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400 text-sm font-bold">
            </div>
        </div>
    </div>

    <!-- Breakdown Status -->
    <div class="flex flex-col gap-4 p-5 bg-slate-50/50 rounded-3xl border border-slate-100">
        <div class="flex justify-between items-center">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Breakdown Status Operasional</h3>
            <span id="stat-count-{{ $isEdit ? 'edit' : 'add' }}" class="px-2 py-0.5 bg-blue-100 text-blue-600 rounded-md text-[10px] font-black">0/1</span>
        </div>
        <div class="grid grid-cols-3 gap-x-4 gap-y-6">
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Tersedia</label>
                <input type="number" id="qty_tersedia_{{ $isEdit ? 'edit' : 'add' }}" name="qty_tersedia" value="{{ old('qty_tersedia', 0) }}" onchange="validateForm('{{ $isEdit ? 'edit' : 'add' }}')" class="breakdown-stat-{{ $isEdit ? 'edit' : 'add' }} w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400 text-sm font-bold">
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Dipinjam</label>
                <input type="number" name="qty_dipinjam" value="{{ old('qty_dipinjam', 0) }}" onchange="validateForm('{{ $isEdit ? 'edit' : 'add' }}')" class="breakdown-stat-{{ $isEdit ? 'edit' : 'add' }} w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400 text-sm font-bold">
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Diperbaiki</label>
                <input type="number" name="qty_diperbaiki" value="{{ old('qty_diperbaiki', 0) }}" onchange="validateForm('{{ $isEdit ? 'edit' : 'add' }}')" class="breakdown-stat-{{ $isEdit ? 'edit' : 'add' }} w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400 text-sm font-bold">
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Hilang</label>
                <input type="number" name="qty_hilang" value="{{ old('qty_hilang', 0) }}" onchange="validateForm('{{ $isEdit ? 'edit' : 'add' }}')" class="breakdown-stat-{{ $isEdit ? 'edit' : 'add' }} w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400 text-sm font-bold">
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Tak Dipakai</label>
                <input type="number" name="qty_tidak_digunakan" value="{{ old('qty_tidak_digunakan', 0) }}" onchange="validateForm('{{ $isEdit ? 'edit' : 'add' }}')" class="breakdown-stat-{{ $isEdit ? 'edit' : 'add' }} w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400 text-sm font-bold">
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase ml-1">Pengadaan</label>
                <input type="number" name="qty_pengadaan" value="{{ old('qty_pengadaan', 0) }}" onchange="validateForm('{{ $isEdit ? 'edit' : 'add' }}')" class="breakdown-stat-{{ $isEdit ? 'edit' : 'add' }} w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-400 text-sm font-bold">
            </div>
        </div>
        <div class="mt-4 p-4 bg-blue-50 rounded-2xl flex justify-between items-center">
            <p class="text-[10px] text-blue-600 font-bold leading-tight">💡 Klik tombol untuk mengisi "Tersedia" otomatis.</p>
            <button type="button" onclick="autoFill('{{ $isEdit ? 'edit' : 'add' }}')" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase shadow-lg shadow-blue-200 active:scale-95 transition-all">Auto-fill</button>
        </div>
    </div>

    <!-- Informasi Keuangan -->
    <div class="flex flex-col gap-4">
        <h3 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] border-b border-blue-50 pb-2">Informasi Keuangan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-500 ml-1">Tanggal Pembelian</label>
                <input type="date" name="purchase_date" value="{{ old('purchase_date') }}"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">
            </div>
        </div>
    </div>

    <!-- Deskripsi -->
    <div class="flex flex-col gap-1.5">
        <label class="text-xs font-bold text-slate-500 ml-1">Deskripsi</label>
        <textarea name="description" rows="3" placeholder="Tambahkan catatan atau deskripsi tambahan..." 
            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all text-sm font-medium">{{ old('description') }}</textarea>
    </div>
</div>

<script>
    if (typeof validateForm !== 'function') {
        window.previewImage = function(input, type) {
            const container = document.getElementById('image_preview_container_' + type);
            const preview = document.getElementById('image_preview_' + type);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '';
                container.classList.add('hidden');
            }
        }

        window.updateLimits = function(type) {
            validateForm(type);
        }

        window.validateForm = function(type) {
            const total = parseInt(document.getElementById('qty_' + type).value) || 0;
            
            // Condition
            let sumCond = 0;
            document.querySelectorAll('.breakdown-cond-' + type).forEach(input => sumCond += parseInt(input.value) || 0);
            const condCounter = document.getElementById('cond-count-' + type);
            condCounter.innerText = `${sumCond}/${total}`;
            condCounter.className = sumCond === total ? 'px-2 py-0.5 bg-green-100 text-green-600 rounded-md text-[10px] font-black' : 'px-2 py-0.5 bg-red-100 text-red-600 rounded-md text-[10px] font-black';

            // Status
            let sumStat = 0;
            document.querySelectorAll('.breakdown-stat-' + type).forEach(input => sumStat += parseInt(input.value) || 0);
            const statCounter = document.getElementById('stat-count-' + type);
            statCounter.innerText = `${sumStat}/${total}`;
            statCounter.className = sumStat === total ? 'px-2 py-0.5 bg-green-100 text-green-600 rounded-md text-[10px] font-black' : 'px-2 py-0.5 bg-red-100 text-red-600 rounded-md text-[10px] font-black';
        }

        window.autoFill = function(type) {
            const total = parseInt(document.getElementById('qty_' + type).value) || 0;
            let sumOthers = 0;
            document.querySelectorAll('.breakdown-stat-' + type).forEach(input => {
                if (input.id !== 'qty_tersedia_' + type) sumOthers += parseInt(input.value) || 0;
            });
            const tersedia = Math.max(0, total - sumOthers);
            document.getElementById('qty_tersedia_' + type).value = tersedia;
            validateForm(type);
        }
    }
</script>
