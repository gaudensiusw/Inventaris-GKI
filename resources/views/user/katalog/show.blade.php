@extends('layouts.user')

@section('content')
<div class="space-y-6">
    <!-- Back Link -->
    <a href="{{ route('user.katalog.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition-colors font-semibold">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali ke Katalog
    </a>

    <!-- Header Section -->
    <div>
        <h1 class="text-2xl font-black text-slate-800">Detail & Jadwal Barang</h1>
        <p class="text-slate-500 text-sm mt-1">Lihat detail spesifikasi dan jadwal ketersediaan barang inventaris gereja secara transparan.</p>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Item Specifications (5 cols) -->
        <div class="lg:col-span-5 flex flex-col gap-6">
            <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden flex flex-col">
                <!-- Image Wrapper -->
                <div class="relative h-64 bg-slate-100 flex items-center justify-center border-b border-slate-100">
                    @if($item->getItemImage())
                        <img src="{{ $item->getItemImage() }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-slate-50 to-slate-100 flex flex-col items-center justify-center gap-3 text-slate-400">
                            <i data-lucide="{{ $item->category->icon ?? 'package' }}" class="w-12 h-12 text-slate-350"></i>
                            <span class="text-xs font-black uppercase tracking-widest text-slate-400">No Image</span>
                        </div>
                    @endif

                    <div class="absolute top-4 left-4 right-4 flex justify-between pointer-events-none">
                        <span class="px-3 py-1 bg-white/95 backdrop-blur-sm text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm">
                            {{ $item->category->name ?? '-' }}
                        </span>
                        @if($item->qty_tersedia > 0)
                            <span class="px-3 py-1 bg-emerald-500 text-white rounded-full text-[10px] font-bold shadow-md">
                                Tersedia
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-500 text-white rounded-full text-[10px] font-bold shadow-md">
                                Habis
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Info Body -->
                <div class="p-8 space-y-6">
                    <div>
                        <h2 class="text-xl font-black text-slate-800 leading-tight mb-1">{{ $item->name }}</h2>
                        <p class="text-xs text-slate-450 font-mono tracking-wider font-bold">KODE ASET: {{ $item->kode_aset }}</p>
                    </div>

                    <!-- Specification Grid -->
                    <div class="grid grid-cols-2 gap-6 border-t border-slate-50 pt-6">
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lokasi Ruangan</span>
                            <div class="flex items-center gap-2 text-slate-700 font-bold text-xs">
                                <i data-lucide="map-pin" class="w-4 h-4 text-blue-500 shrink-0"></i>
                                <span>{{ $item->room->name ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Stok Tersedia</span>
                            <div class="flex items-center gap-2 text-slate-700 font-bold text-xs">
                                <i data-lucide="package" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                                <span class="{{ $item->qty_tersedia > 0 ? 'text-emerald-600' : 'text-red-500' }}">{{ $item->qty_tersedia }} / {{ $item->quantity }} {{ $item->unit ?? 'Unit' }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Merk / Model</span>
                            <div class="flex items-center gap-2 text-slate-700 font-bold text-xs">
                                <i data-lucide="tag" class="w-4 h-4 text-purple-500 shrink-0"></i>
                                <span>{{ $item->merk_model ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kondisi Fisik</span>
                            <div class="flex items-center gap-2 text-slate-700 font-bold text-xs">
                                <i data-lucide="shield-check" class="w-4 h-4 text-amber-500 shrink-0"></i>
                                <span>{{ $item->condition ?? 'Baik' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description / Spec Details -->
                    @if($item->description || $item->spesifikasi || $item->keterangan)
                    <div class="border-t border-slate-50 pt-6 space-y-4">
                        @if($item->spesifikasi)
                        <div class="flex flex-col gap-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Spesifikasi</span>
                            <p class="text-xs text-slate-600 leading-relaxed font-medium bg-slate-50 p-3.5 rounded-2xl whitespace-pre-line">{{ $item->spesifikasi }}</p>
                        </div>
                        @endif
                        
                        @if($item->description || $item->keterangan)
                        <div class="flex flex-col gap-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Deskripsi / Keterangan</span>
                            <p class="text-xs text-slate-500 italic leading-relaxed">{{ $item->description ?: $item->keterangan }}</p>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Action Button -->
                    <div class="pt-2">
                        @if($item->qty_tersedia > 0)
                            <a href="{{ route('user.orders.create', $item->id) }}" 
                               class="block w-full py-4 bg-blue-600 text-white rounded-2xl text-sm font-bold hover:bg-blue-700 shadow-xl shadow-blue-200 transition-all text-center flex items-center justify-center gap-2">
                                <i data-lucide="hand" class="w-4 h-4"></i>
                                <span>Ajukan Peminjaman Sekarang</span>
                            </a>
                        @else
                            <button disabled class="w-full py-4 bg-slate-100 text-slate-400 rounded-2xl text-sm font-bold cursor-not-allowed text-center">
                                Stok Habis / Sedang Dipinjam
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Booking Calendar (7 cols) -->
        <div class="lg:col-span-7 flex flex-col gap-6">
            <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-8 flex flex-col gap-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-slate-100 pb-4 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="calendar" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">Kalender Ketersediaan</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Jadwal Booking Barang</p>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-wider">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full shrink-0" style="background-color: #f97316;"></span>
                            <span class="text-slate-500">Pending</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full shrink-0" style="background-color: #ef4444;"></span>
                            <span class="text-slate-500">Disetujui</span>
                        </div>
                    </div>
                </div>

                <!-- Calendar View Wrapper -->
                <div class="calendar-wrapper">
                    <div id="calendar" class="p-2 rounded-2xl"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- FullCalendar Integration -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            themeSystem: 'standard',
            events: @json($events),
            locale: 'id', // Indonesian localization
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false
            },
            height: 'auto',
            firstDay: 1, // Start week on Monday
            dayMaxEvents: true, // limit the number of events in a day
            
            // Premium custom styles styling via JS
            eventDidMount: function(info) {
                // Add tooltip or custom classes if needed
                info.el.style.borderRadius = '8px';
                info.el.style.border = 'none';
                info.el.style.padding = '3px 8px';
                info.el.style.fontSize = '9px';
                info.el.style.fontWeight = 'bold';
                info.el.style.color = '#ffffff';
                info.el.title = info.event.title;
            }
        });
        
        calendar.render();
    });
</script>

<style>
    /* Styling FullCalendar to match our premium aesthetic */
    .fc {
        --fc-border-color: #f1f5f9;
        --fc-button-bg-color: #3b82f6;
        --fc-button-border-color: #3b82f6;
        --fc-button-hover-bg-color: #2563eb;
        --fc-button-hover-border-color: #2563eb;
        --fc-button-active-bg-color: #1d4ed8;
        --fc-button-active-border-color: #1d4ed8;
        --fc-today-bg-color: #eff6ff;
        font-family: inherit;
    }
    
    .fc .fc-toolbar-title {
        font-size: 14px !important;
        font-weight: 900 !important;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #1e293b;
    }

    .fc .fc-button {
        padding: 8px 16px !important;
        font-size: 11px !important;
        font-weight: bold !important;
        text-transform: uppercase !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 6px -1px rgb(59 130 246 / 0.1) !important;
        transition: all 0.2s;
    }
    
    .fc .fc-button-group > .fc-button {
        border-radius: 12px !important;
        margin: 0 2px !important;
    }

    .fc-theme-standard td, .fc-theme-standard th {
        border: 1px solid #f8fafc !important;
    }

    .fc .fc-col-header-cell-cushion {
        font-size: 9px !important;
        font-weight: bold !important;
        text-transform: uppercase !important;
        color: #94a3b8 !important;
        letter-spacing: 0.1em;
        padding: 10px 0 !important;
    }

    .fc .fc-daygrid-day-number {
        font-size: 11px !important;
        font-weight: bold !important;
        color: #64748b;
        padding: 8px !important;
    }

    .fc .fc-day-today .fc-daygrid-day-number {
        color: #2563eb !important;
        font-weight: 900 !important;
    }
</style>
@endsection
