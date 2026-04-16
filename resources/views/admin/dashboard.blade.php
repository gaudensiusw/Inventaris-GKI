@extends('layouts.master', ['title' => 'Dashboard'])

@section('content')
    <x-container>
        <div class="col-sm-6 col-xl-3">
            <x-widget title="Kategori" :subTitle="$categoriesCount" class="bg-azure">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-copy" width="24" height="24"
                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <rect x="8" y="8" width="12" height="12" rx="2"></rect>
                    <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"></path>
                </svg>
            </x-widget>
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-widget title="Ruangan" :subTitle="$roomsCount" class="bg-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-door" width="24"
                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 12v.01" />
                    <path d="M3 21h18" />
                    <path d="M6 21v-16a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v16" />
                </svg>
            </x-widget>
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-widget title="Total Aset (Items)" :subTitle="$itemsCount" class="bg-indigo">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-box" width="24"
                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" />
                    <line x1="12" y1="12" x2="20" y2="7.5" />
                    <line x1="12" y1="12" x2="12" y2="21" />
                    <line x1="12" y1="12" x2="4" y2="7.5" />
                </svg>
            </x-widget>
        </div>
        <div class="col-sm-6 col-xl-3">
            <x-widget title="Peminjam" :subTitle="$customersCount" class="bg-lime">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24"
                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                </svg>
            </x-widget>
        </div>
        <div class="col-sm-6 col-xl-4">
            <x-widget title="Barang Sedang Dipinjam" :subTitle="$loanedItemsCount" class="bg-cyan">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-cart-x"
                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <circle cx="6" cy="19" r="2"></circle>
                    <circle cx="17" cy="19" r="2"></circle>
                    <path d="M17 17h-11v-14h-2"></path>
                    <path d="M6 5l7.999 .571m5.43 4.43l-.429 2.999h-13"></path>
                    <path d="M17 3l4 4"></path>
                    <path d="M21 3l-4 4"></path>
                </svg>
            </x-widget>
        </div>
        <div class="col-sm-6 col-xl-4">
            <x-widget title="Barang Rusak" :subTitle="$damagedItemsCount" class="bg-red">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-tool"
                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5"></path>
                </svg>
            </x-widget>
        </div>
        <div class="col-sm-6 col-xl-4">
            <x-widget title="Antrian Pengajuan" :subTitle="$pendingOrders->count()" class="bg-yellow">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-cart-plus"
                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <circle cx="6" cy="19" r="2"></circle>
                    <circle cx="17" cy="19" r="2"></circle>
                    <path d="M17 17h-11v-14h-2"></path>
                    <path d="M6 5l6.005 .429m7.138 6.573l-.143 .998h-13"></path>
                    <path d="M15 6h6m-3 -3v6"></path>
                </svg>
            </x-widget>
        </div>
        <div class="col-12">
            @if ($pendingOrders->count() == 0)
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="fas fa-info-circle mr-2 fa-lg"></i>
                    Saat ini belum ada pengajuan peminjaman barang terbaru
                </div>
            @else
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="fas fa-info-circle mr-2 fa-lg"></i>
                    Saat ini terdapat {{ $pendingOrders->count() }} permintaan peminjaman barang menunggu konfirmasi.
                    <a href="{{ route('admin.order.index') }}" class="ml-1 font-weight-bold">Lihat Detail Pengajuan</a>
                </div>
            @endif
        </div>
        <div class="col-lg-12">
            <x-card title="Distribusi Kategori Barang">
                <div id="chart-total-sales" class="my-3"></div>
            </x-card>
        </div>
    </x-container>
@endsection

@push('js')
    <script>
        // @formatter:off
        document.addEventListener("DOMContentLoaded", function() {
            window.ApexCharts && (new ApexCharts(document.getElementById('chart-total-sales'), {
                chart: {
                    type: "donut",
                    fontFamily: 'inherit',
                    height: 400,
                    sparkline: {
                        enabled: true
                    },
                    animations: {
                        enabled: true
                    },
                },
                fill: {
                    opacity: 1,
                },
                series: @json($total),
                labels: @json($label),
                grid: {
                    strokeDashArray: 4,
                },
                colors: ["#206bc4", "#79a6dc", "#bfe399", "#7891b3", "#2596be"],
                legend: {
                    show: true,
                    position: 'top'
                },
                tooltip: {
                    fillSeriesColor: true
                },
                dataLabels: {
                    enabled: true,
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            })).render();
        });
        // @formatter:on
    </script>
@endpush
