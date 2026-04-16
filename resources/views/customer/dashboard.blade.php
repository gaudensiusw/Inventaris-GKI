@extends('layouts.master', ['title' => 'Dashboard Customer'])

@section('content')
    <x-container>
        <div class="row mb-4">
            <div class="col-12 col-md-6 col-lg-6">
                <x-widget title="Pinjaman Aktif (Disetujui)" :subTitle="$activeLoans->where('status', 'Approved')->count()" class="bg-primary text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M5 12l5 5l10 -10"></path>
                    </svg>
                </x-widget>
            </div>
            <div class="col-12 col-md-6 col-lg-6">
                <x-widget title="Menunggu Persetujuan" :subTitle="$activeLoans->where('status', 'Pending')->count()" class="bg-azure text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <circle cx="12" cy="12" r="9"></circle>
                        <polyline points="12 7 12 12 15 15"></polyline>
                    </svg>
                </x-widget>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="page-header d-print-none">
                <h2 class="page-title">
                    Katalog Item Tersedia
                </h2>
                <div class="text-muted mt-1">Daftar aset gereja yang dapat Anda pinjam</div>
            </div>
        </div>

        <div class="col-12">
            <div class="row row-cards">
                @forelse ($items as $item)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    @if($item->image)
                                        <span class="avatar me-3 rounded" style="background-image: url({{ asset('storage/items/' . $item->image) }}); width: 64px; height: 64px; background-position: center; background-size: cover;"></span>
                                    @else
                                        <span class="avatar me-3 rounded bg-blue-lt" style="width: 64px; height: 64px;">{{ substr($item->name, 0, 2) }}</span>
                                    @endif
                                    <div>
                                        <div class="font-weight-medium text-lg">{{ $item->name }}</div>
                                        <div class="text-muted text-sm">{{ $item->category->name }}</div>
                                    </div>
                                </div>
                                <div class="row text-sm text-muted mb-3">
                                    <div class="col-6">
                                        <p class="mb-1"><strong>Ruangan:</strong><br>{{ $item->room ? $item->room->name : '-' }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1"><strong>Kondisi:</strong><br>{{ $item->condition }}</p>
                                    </div>
                                    <div class="col-12 mt-1 limit-text text-truncate">
                                        {{ Str::limit($item->description, 50) }}
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <form action="{{ route('customer.order.create') }}" method="GET" class="w-100">
                                        <input type="hidden" name="item" value="{{ $item->slug }}">
                                        <button class="btn btn-outline-primary w-100" type="submit">
                                            Ajukan Peminjaman
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center w-100">
                            Saat ini tidak ada Aset yang berstatus "Tersedia".
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </x-container>
@endsection
