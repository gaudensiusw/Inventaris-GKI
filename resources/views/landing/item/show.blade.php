@extends('layouts.landing.master', ['title' => 'Detail Aset'])

@section('content')
    <div class="w-full py-6 px-4 pb-20 md:pb-0">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 md:mx-8 gap-4 items-start mb-10">
                <div class="row-span-2">
                    @if($item->image)
                        <img src="{{ asset('storage/items/' . $item->image) }}" class="object-cover w-full object-center rounded-lg shadow-custom">
                    @else
                        <div class="w-full h-64 bg-gray-200 rounded-lg shadow-custom flex items-center justify-center text-gray-500">No Image</div>
                    @endif
                </div>
                <div class="bg-white border shadow-custom rounded-lg p-4">
                    <p class="text-base text-gray-500">{{ $item->category->name }}</p>
                    <h1 class="font-bold text-2xl md:text-4xl text-gray-700">{{ $item->name }}</h1>
                    <div class="flex flex-col py-2">
                        <p class="text-base md:text-lg text-gray-600 py-2 text-justify">{{ $item->description }}</p>
                    </div>
                    <div class="py-2">
                        <h1 class="text-gray-600 text-sm">Informasi Aset</h1>
                        <div class="flex flex-row justify-between text-sm text-gray-500">
                            <p>Ruangan</p>
                            <p class="text-right">{{ $item->room ? $item->room->name : 'N/A' }}</p>
                        </div>
                        <div class="flex flex-row justify-between text-sm text-gray-500">
                            <p>Kondisi</p>
                            <p>{{ $item->condition }}</p>
                        </div>
                        <div class="flex flex-row justify-between text-sm text-gray-500">
                            <p>Status Saat Ini</p>
                            <p class="font-bold {{ $item->status == 'Tersedia' ? 'text-green-600' : 'text-red-500' }}">{{ $item->status }}</p>
                        </div>
                    </div>
                    <div class="py-2 mt-4">
                        @if ($item->status == 'Tersedia')
                            <form action="{{ route('customer.order.create') }}" method="GET">
                                <input type="hidden" name="item" value="{{ $item->slug }}">
                                <button class="flex flex-row items-center justify-center w-full bg-sky-700 p-3 rounded-lg text-sm text-white hover:bg-sky-800 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clipboard-check mr-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"></path>
                                        <rect x="9" y="3" width="6" height="4" rx="2"></rect>
                                        <path d="M9 14l2 2l4 -4"></path>
                                    </svg>
                                    Ajukan Pinjaman
                                </button>
                            </form>
                        @else
                            <div class="bg-gray-300 text-center text-gray-600 rounded-lg p-3 font-semibold">
                                Aset Sedang {{ $item->status }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex flex-col mb-5 mt-10">
                <h1 class="text-gray-700 font-bold text-lg">Daftar Aset Lainnya</h1>
                <p class="text-gray-400 text-xs">Kumpulan data aset yang berada di sistem</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                @foreach ($items->take(3) as $related)
                    <div class="relative bg-white p-4 rounded-lg border shadow-custom">
                        @if($related->image)
                            <img src="{{ asset('storage/items/' . $related->image) }}" class="rounded-lg w-full object-cover h-48" />
                        @endif
                        <div class="font-mono absolute -top-3 -right-3 p-2 bg-sky-700 rounded-lg text-gray-50">
                            {{ $related->condition }}
                        </div>
                        <div class="flex flex-col gap-2 py-2">
                            <div class="flex justify-between">
                                <a href="{{ route('item.show', $related->slug) }}"
                                    class="text-gray-700 text-sm hover:underline font-bold">{{ $related->name }}</a>
                                <div class="text-gray-500 text-sm">{{ $related->category->name }}</div>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ Str::limit($related->description, 35) }}
                            </div>
                            @if ($related->status == 'Tersedia')
                                <form action="{{ route('customer.order.create') }}" method="GET">
                                    <input type="hidden" name="item" value="{{ $related->slug }}">
                                    <button class="text-gray-700 bg-gray-200 p-2 rounded-lg text-sm text-center hover:bg-gray-300 w-full mt-2" type="submit">
                                        Ajukan Pinjaman
                                    </button>
                                </form>
                            @else
                                <button class="text-gray-700 bg-gray-200 p-2 rounded-lg text-sm text-center cursor-not-allowed w-full mt-2">
                                    Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
