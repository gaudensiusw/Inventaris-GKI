@extends('layouts.master', ['title' => 'Aset'])

@section('content')
    <x-container>
        <div class="col-12">
            @can('create-item')
                <x-button-link title="Tambah Aset" icon="plus" class="btn btn-primary mb-3" style="mr-1" :url="route('admin.item.create')" />
            @endcan
            <x-card title="DAFTAR Aset" class="card-body p-0">
                <x-table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Foto</th>
                            <th>Nama Aset</th>
                            <th>Nama Supplier</th>
                            <th>Kategori Aset</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $i => $item)
                            <tr>
                                <td>{{ $i + $items->firstItem() }}</td>
                                <td>
                                    <span class="avatar rounded avatar-md"
                                        style="background-image: url({{ $item->image }})"></span>
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->supplier->name }}</td>
                                <td>{{ $item->category->name }}</td>
                                <td>{{ $item->unit }}</td>
                                <td>
                                    @can('update-item')
                                        <x-button-link title="" icon="edit" class="btn btn-info btn-sm"
                                            :url="route('admin.item.edit', $item->id)" style="" />
                                    @endcan
                                    @can('delete-item')
                                        <x-button-delete :id="$item->id" :url="route('admin.item.destroy', $item->id)" title=""
                                            class="btn btn-danger btn-sm" />
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table>
            </x-card>
        </div>
    </x-container>
@endsection
