@extends('layouts.master', ['title' => 'Edit Barang'])

@section('content')
    <div class="page-header">
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="{{ route('admin.item.index') }}" class="btn-back">←</a>
            <div>
                <h1>Edit Barang</h1>
                <p>Ubah detail barang: {{ $item->name }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.item.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Informasi Dasar -->
        <div class="form-section">
            <div class="form-section-title">Informasi Dasar</div>

            <div class="form-group">
                <label>Nama Barang <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Nama Barang"
                    value="{{ old('name', $item->name) }}" required>
                @error('name') <p style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</p> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category_id" class="form-control">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label>Kode Barcode</label>
                    <input type="text" name="barcode" class="form-control" value="{{ old('barcode', $item->barcode) }}" required>
                    @error('barcode') <p style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Lokasi dan Jumlah -->
        <div class="form-section">
            <div class="form-section-title">Lokasi dan Jumlah</div>

            <div class="form-row">
                <div class="form-group">
                    <label>Lokasi Penyimpanan</label>
                    <select name="room_id" class="form-control">
                        <option value="">Pilih Lokasi</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id', $item->room_id) == $room->id ? 'selected' : '' }}>
                                {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('room_id') <p style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label>Total Jumlah Barang <span class="required">*</span></label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $item->quantity) }}" min="1" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Satuan</label>
                    <select name="unit" class="form-control">
                        <option value="">Pilih Satuan</option>
                        @foreach(['unit','buah','buku','set','lembar','kotak','roll','pasang'] as $u)
                        <option value="{{ $u }}" {{ old('unit', $item->unit) == $u ? 'selected' : '' }}>{{ ucfirst($u) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Kondisi & Status -->
        <div class="form-section">
            <div class="form-section-title">Kondisi & Status</div>

            <div class="form-row">
                <div class="form-group">
                    <label>Kondisi Fisik <span class="required">*</span></label>
                    <select name="condition" class="form-control" required>
                        <option value="Baik" {{ old('condition', $item->condition) == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak ringan" {{ old('condition', $item->condition) == 'Rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak berat" {{ old('condition', $item->condition) == 'Rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status <span class="required">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="Tersedia" {{ old('status', $item->status) == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Dipinjam" {{ old('status', $item->status) == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Sedang diperbaiki" {{ old('status', $item->status) == 'Sedang diperbaiki' ? 'selected' : '' }}>Sedang Diperbaiki</option>
                        <option value="Hilang" {{ old('status', $item->status) == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                        <option value="Tidak digunakan" {{ old('status', $item->status) == 'Tidak digunakan' ? 'selected' : '' }}>Tidak Digunakan</option>
                        <option value="Dalam pengadaan" {{ old('status', $item->status) == 'Dalam pengadaan' ? 'selected' : '' }}>Dalam Pengadaan</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Informasi Keuangan -->
        <div class="form-section">
            <div class="form-section-title">Informasi Keuangan</div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal Pembelian</label>
                    <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', $item->purchase_date) }}">
                </div>
                <div class="form-group">
                    <label>Harga Satuan (Rp)</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', $item->price ?? 0) }}" min="0" step="1000">
                </div>
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="form-section">
            <div class="form-section-title">Deskripsi</div>
            <div class="form-group">
                <textarea name="description" class="form-control" placeholder="Catatan tambahan...">{{ old('description', $item->description) }}</textarea>
            </div>
        </div>

        <!-- Foto -->
        <div class="form-section">
            <div class="form-section-title">Foto Barang</div>
            <div class="form-group">
                @if($item->image)
                    <div style="margin-bottom:10px;">
                        <img src="{{ asset('storage/items/' . $item->image) }}" alt="{{ $item->name }}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid var(--border-color);">
                        <p style="font-size:11px;color:var(--text-muted);margin-top:4px;">Foto saat ini</p>
                    </div>
                @endif
                <input type="file" name="image" class="form-control" accept="image/*">
                <p style="font-size:11px;color:var(--text-muted);margin-top:4px;">Kosongkan jika tidak ingin mengubah foto</p>
            </div>
        </div>

        <div style="display:flex;gap:12px;justify-content:flex-end;padding:8px 0 24px;">
            <a href="{{ route('admin.item.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                    <path d="M5 12l5 5l10 -10" />
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
@endsection
