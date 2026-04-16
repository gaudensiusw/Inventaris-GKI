@extends('layouts.master', ['title' => 'Produk'])

@section('content')
    <x-container>
        <div class="row">
            <div class="col-12">
                <x-card title="UBAH PRODUK" class="card-body">
                    <form action="{{ route('admin.item.update', $item->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <x-input name="name" type="text" title="Nama Produk" placeholder="Nama Produk" :value="$item->name" />
                        <div class="row">
                            <div class="col-6">
                                <x-select title="Kategori Produk" name="category_id">
                                    <option value="">Silahkan Pilih</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected($item->category_id == $category->id)>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="col-6">
                                <x-select title="Supplier Produk" name="supplier_id">
                                    <option value="">Silahkan Pilih</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @selected($item->supplier_id == $supplier->id)>
                                            {{ $supplier->name }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <x-input name="image" type="file" title="Foto Produk" placeholder="" :value="$item->image" />
                            </div>
                            <div class="col-6">
                                <x-input name="unit" type="text" title="Satuan Produk" placeholder="Satuan Produk"
                                    :value="$item->unit" />
                            </div>
                        </div>
                        <x-textarea name="description" title="Deskripsi Produk" placeholder="Deskripsi Produk">
                            {{ $item->description }}</x-textarea>
                        <x-button-save title="Simpan" icon="save" class="btn btn-primary" />
                        <x-button-link title="Kembali" icon="arrow-left" :url="route('admin.item.index')" class="btn btn-dark"
                            style="mr-1" />
                    </form>
                </x-card>
            </div>
        </div>
    </x-container>
@endsection
