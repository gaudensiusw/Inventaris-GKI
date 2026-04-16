@extends('layouts.master', ['title' => 'Ruangan'])

@section('content')
    <x-container>
        <div class="col-12 col-lg-8">
            <x-card title="DAFTAR RUANGAN" class="card-body p-0">
                <x-table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Ruangan</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rooms as $i => $room)
                            <tr>
                                <td>{{ $i + $rooms->firstItem() }}</td>
                                <td>{{ $room->name }}</td>
                                <td>{{ Str::limit($room->description, 50) }}</td>
                                <td>
                                    <x-button-modal :id="$room->id" title="" icon="edit" style="" class="btn btn-info btn-sm" />
                                    <x-modal :id="$room->id" title="Edit Ruangan - {{ $room->name }}">
                                        <form action="{{ route('admin.room.update', $room->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <x-input name="name" type="text" title="Nama Ruangan" placeholder="Masukan Nama Ruangan" :value="$room->name" />
                                            <x-textarea name="description" title="Deskripsi Ruangan" placeholder="Masukan Deskripsi Ruangan">{{ $room->description }}</x-textarea>
                                            <x-button-save title="Simpan" icon="save" class="btn btn-primary" />
                                        </form>
                                    </x-modal>

                                    <x-button-delete :id="$room->id" :url="route('admin.room.destroy', $room->id)" title="" class="btn btn-danger btn-sm" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data ruangan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </x-table>
            </x-card>
            @if(method_exists($rooms, 'links'))
                <div class="mt-3">{{ $rooms->links() }}</div>
            @endif
        </div>
        
        <div class="col-12 col-lg-4">
            <x-card title="TAMBAH RUANGAN" class="card-body">
                <form action="{{ route('admin.room.store') }}" method="POST">
                    @csrf
                    <x-input name="name" type="text" title="Nama Ruangan" placeholder="Contoh: Ruang Audio Visual" :value="old('name')" />
                    <x-textarea name="description" title="Deskripsi Ruangan" placeholder="Penjelasan singkat ruangan...">{{ old('description') }}</x-textarea>
                    <x-button-save title="Tambahkan Ruangan" icon="plus" class="btn btn-primary w-100 mt-2" />
                </form>
            </x-card>
        </div>
    </x-container>
@endsection
