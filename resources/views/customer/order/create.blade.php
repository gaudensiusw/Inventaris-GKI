@extends('layouts.master', ['title' => 'Form Pengajuan Peminjaman'])

@section('content')
    <x-container>
        <div class="col-12 col-lg-8 mx-auto">
            <x-card title="Pengajuan Peminjaman: {{ $item->name }}" class="card-body">
                <div class="mb-4 text-center">
                    @if($item->image)
                        <img src="{{ asset('storage/items/' . $item->image) }}" class="rounded shadow-sm w-32 h-32 mx-auto object-cover mb-2" />
                    @endif
                    <p class="text-sm text-gray-500">Kondisi: {{ $item->condition }} | Ruangan: {{ $item->room ? $item->room->name : '-' }}</p>
                </div>
                
                <form action="{{ route('customer.order.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    
                    <x-input name="identity_number" type="text" title="Nomor Induk / KTP" placeholder="Masukkan Nomor Identitas Anda" :value="old('identity_number')" />
                    
                    <div class="row">
                        <div class="col-md-6">
                            <x-input name="start_date" type="date" title="Tanggal Mulai Pinjam" placeholder="" :value="old('start_date')" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="end_date" type="date" title="Tanggal Selesai Pinjam" placeholder="" :value="old('end_date')" />
                        </div>
                    </div>
                    
                    <div class="mt-4 flex justify-content-end">
                        <a href="{{ route('item.index') }}" class="btn btn-secondary mr-2">Batal</a>
                        <x-button-save title="Ajukan Pinjaman" icon="send" class="btn btn-primary" />
                    </div>
                </form>
            </x-card>
        </div>
    </x-container>
@endsection
