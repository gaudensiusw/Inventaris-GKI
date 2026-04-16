@extends('layouts.master', ['title' => 'Pengajuan Pinjaman'])

@section('content')
    <x-container>
        <div class="col-12 text-center mb-4">
            <h1 class="text-2xl font-bold">Daftar Pengajuan Pinjaman Anda</h1>
        </div>
        <div class="col-12 col-lg-12">
            <x-card title="Pengajuan Peminjaman Barang" class="card-body p-0">
                <x-table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Aset</th>
                            <th>Nomor Induk</th>
                            <th>Mulai Pinjam</th>
                            <th>Selesai Pinjam</th>
                            <th>Status Pengajuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $i => $order)
                            <tr>
                                <td>{{ $i + $orders->firstItem() }}</td>
                                <td>{{ $order->item->name ?? '-' }}</td>
                                <td>{{ $order->identity_number }}</td>
                                <td>{{ $order->start_date }}</td>
                                <td>{{ $order->end_date }}</td>
                                <td class="{{ $order->status == 'Pending' ? 'text-danger' : 'text-success' }}">
                                    {{ $order->status }}
                                </td>
                                <td>
                                    @if ($order->status == 'Pending')
                                        <x-button-delete :id="$order->id" :url="route('customer.order.destroy', $order->id)" title="" class="btn btn-danger btn-sm" />
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Saat ini tidak ada pengajuan peminjaman barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </x-table>
            </x-card>
            <div class="d-flex justify-content-end">{{ $orders->links() }}</div>
        </div>
    </x-container>
@endsection
