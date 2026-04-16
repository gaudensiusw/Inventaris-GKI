@extends('layouts.landing.master', ['title' => 'Kategori'])

@section('content')
    <div class="w-full py-6 px-4">
        <div class="container mx-auto">
            <div class="flex flex-col mb-5">
                <h1 class="text-gray-700 font-bold md:text-lg text-base">
                    Daftar Barang dengan kategori - {{ $category->name }}
                </h1>
                <p class="text-gray-400 text-xs">Kumpulan data barang dengan kategori - {{ $category->name }}</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                @foreach ($items as $item)
                    <div class="relative bg-white p-4 rounded-lg border shadow-custom">
                        @if($item->image)
                            <img src="{{ asset('storage/items/' . $item->image) }}" class="rounded-lg w-full object-cover" />
                        @endif
                        <div
                            class="font-mono absolute -top-3 -right-3 p-2 bg-sky-700 rounded-lg text-gray-50">
                            {{ $item->condition }}
                        </div>
                        <div class="flex flex-col gap-2 py-2">
                            <div class="flex justify-between">
                                <a href="{{ route('item.show', $item->slug) }}"
                                    class="text-gray-700 text-sm hover:underline">{{ $item->name }}</a>
                                <div class="text-gray-500 text-sm">{{ $item->category->name }}</div>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ Str::limit($item->description, 35) }}
                            </div>
                            <div class="text-xs text-gray-400">
                                Ruangan: {{ $item->room ? $item->room->name : 'N/A' }} | Status: {{ $item->status }}
                            </div>
                            @if ($item->status == 'Tersedia')
                                <form action="{{ route('customer.order.create') }}" method="GET">
                                    <input type="hidden" name="item" value="{{ $item->slug }}">
                                    <button
                                        class="text-gray-700 bg-gray-200 p-2 rounded-lg text-sm text-center hover:bg-gray-300 w-full"
                                        type="submit">
                                        Ajukan Pinjaman
                                    </button>
                                </form>
                            @else
                                <button
                                    class="text-gray-700 bg-gray-200 p-2 rounded-lg text-sm text-center hover:bg-gray-300 w-full cursor-not-allowed">
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
