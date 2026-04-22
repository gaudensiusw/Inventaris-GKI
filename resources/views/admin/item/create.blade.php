@extends('layouts.master', ['title' => 'Tambah Barang Baru'])

@section('content')
<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="{{ route('admin.item.index') }}" class="btn-back">←</a>
        <div>
            <h1>Tambah Barang Baru</h1>
            <p>Masukkan detail barang inventaris</p>
        </div>
    </div>
</div>

<form action="{{ route('admin.item.store') }}" method="POST" enctype="multipart/form-data" id="itemForm">
    @csrf

    <!-- Informasi Dasar -->
    <div class="form-section">
        <div class="form-section-title">Informasi Dasar</div>

        <div class="form-group">
            <label>Nama Barang <span class="required">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Contoh: Proyektor Epson EB-X41" value="{{ old('name') }}" required>
            @error('name') <p style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label>Kategori Utama <span class="required">*</span></label>
            <select name="category_id" class="form-control" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Lokasi dan Jumlah -->
    <div class="form-section">
        <div class="form-section-title">Lokasi dan Jumlah</div>

        <div class="form-row">
            <div class="form-group">
                <label>Lokasi Penyimpanan <span class="required">*</span></label>
                <select name="room_id" class="form-control" required>
                    <option value="">Contoh: Gudang Lt. 2</option>
                    @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                    @endforeach
                </select>
                @error('room_id') <p style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label>Total Jumlah Barang <span class="required">*</span></label>
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 1) }}" min="1" id="totalQuantity" required>
            </div>
        </div>
    </div>

    <!-- Breakdown Kondisi Fisik -->
    <div class="form-section">
        <div class="breakdown-header">
            <h4>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Breakdown Kondisi Fisik
            </h4>
            <span class="breakdown-counter" id="conditionCounter">0/1</span>
        </div>
        <p style="font-size:12px;color:#64748b;margin-bottom:12px;">Distribusi barang berdasarkan kondisi fisik. Total harus sama dengan jumlah barang.</p>

        <div class="form-row-3">
            <div class="form-group">
                <label>Baik</label>
                <input type="number" name="qty_baik" class="form-control condition-input" value="{{ old('qty_baik', 0) }}" min="0">
            </div>
            <div class="form-group">
                <label>Rusak Ringan</label>
                <input type="number" name="qty_rusak_ringan" class="form-control condition-input" value="{{ old('qty_rusak_ringan', 0) }}" min="0">
            </div>
            <div class="form-group">
                <label>Rusak Berat</label>
                <input type="number" name="qty_rusak_berat" class="form-control condition-input" value="{{ old('qty_rusak_berat', 0) }}" min="0">
            </div>
        </div>
    </div>

    <!-- Breakdown Status Operasional -->
    <div class="form-section">
        <div class="breakdown-header">
            <h4>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Breakdown Status Operasional
            </h4>
            <span class="breakdown-counter" id="statusCounter">0/1</span>
        </div>
        <p style="font-size:12px;color:#64748b;margin-bottom:12px;">Distribusi barang berdasarkan status operasional. Total harus sama dengan jumlah barang.</p>

        <div class="form-row-3">
            <div class="form-group">
                <label>Tersedia</label>
                <input type="number" name="qty_tersedia" class="form-control status-input" value="{{ old('qty_tersedia', 0) }}" min="0">
            </div>
            <div class="form-group">
                <label>Dipinjam</label>
                <input type="number" name="qty_dipinjam" class="form-control status-input" value="{{ old('qty_dipinjam', 0) }}" min="0">
            </div>
            <div class="form-group">
                <label>Sedang Diperbaiki</label>
                <input type="number" name="qty_diperbaiki" class="form-control status-input" value="{{ old('qty_diperbaiki', 0) }}" min="0">
            </div>
        </div>
        <div class="form-row-3">
            <div class="form-group">
                <label>Hilang</label>
                <input type="number" name="qty_hilang" class="form-control status-input" value="{{ old('qty_hilang', 0) }}" min="0">
            </div>
            <div class="form-group">
                <label>Tidak Digunakan</label>
                <input type="number" name="qty_tidak_digunakan" class="form-control status-input" value="{{ old('qty_tidak_digunakan', 0) }}" min="0">
            </div>
            <div class="form-group">
                <label>Dalam Pengadaan</label>
                <input type="number" name="qty_pengadaan" class="form-control status-input" value="{{ old('qty_pengadaan', 0) }}" min="0">
            </div>
        </div>

        <div class="form-tip">
            💡 Tip: Isi status lainnya terlebih dahulu, lalu klik tombol ini untuk mengisi "Tersedia" secara otomatis.
            <button type="button" class="btn btn-primary btn-sm" onclick="autoFillTersedia()" style="margin-left:auto;">Auto Isi Tersedia</button>
        </div>
    </div>

    <!-- Informasi Keuangan -->
    <div class="form-section">
        <div class="form-section-title">Informasi Keuangan</div>

        <div class="form-row">
            <div class="form-group">
                <label>Tanggal Pembelian</label>
                <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date') }}">
            </div>
            <div class="form-group">
                <label>Harga Satuan (Rp)</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', 0) }}" min="0" step="1000">
            </div>
        </div>
    </div>

    <!-- Deskripsi -->
    <div class="form-section">
        <div class="form-section-title">Deskripsi</div>
        <div class="form-group">
            <textarea name="description" class="form-control" placeholder="Tambahkan catatan atau deskripsi tambahan...">{{ old('description') }}</textarea>
        </div>
    </div>

    <!-- Hidden fields -->
    <input type="hidden" name="barcode" value="INV-{{ str_pad(rand(1,9999), 4, '0', STR_PAD_LEFT) }}">
    <input type="hidden" name="condition" value="Baik">
    <input type="hidden" name="status" value="Tersedia">

    <div style="display:flex;gap:12px;justify-content:flex-end;padding:8px 0 24px;">
        <a href="{{ route('admin.item.index') }}" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M5 12l5 5l10 -10"/></svg>
            Simpan Barang
        </button>
    </div>
</form>
@endsection

@push('js')
<script>
function updateCounters() {
    const total = parseInt(document.getElementById('totalQuantity').value) || 0;

    // Condition
    let condSum = 0;
    document.querySelectorAll('.condition-input').forEach(input => { condSum += parseInt(input.value) || 0; });
    const condCounter = document.getElementById('conditionCounter');
    condCounter.textContent = condSum + '/' + total;
    condCounter.className = 'breakdown-counter ' + (condSum === total ? 'valid' : 'invalid');

    // Status
    let statusSum = 0;
    document.querySelectorAll('.status-input').forEach(input => { statusSum += parseInt(input.value) || 0; });
    const statusCounter = document.getElementById('statusCounter');
    statusCounter.textContent = statusSum + '/' + total;
    statusCounter.className = 'breakdown-counter ' + (statusSum === total ? 'valid' : 'invalid');
}

function autoFillTersedia() {
    const total = parseInt(document.getElementById('totalQuantity').value) || 0;
    let otherStatus = 0;
    document.querySelectorAll('.status-input:not([name="qty_tersedia"])').forEach(input => {
        otherStatus += parseInt(input.value) || 0;
    });
    const tersedia = Math.max(0, total - otherStatus);
    document.querySelector('[name="qty_tersedia"]').value = tersedia;
    updateCounters();
}

document.getElementById('totalQuantity').addEventListener('input', updateCounters);
document.querySelectorAll('.condition-input, .status-input').forEach(input => {
    input.addEventListener('input', updateCounters);
});

updateCounters();
</script>
@endpush
