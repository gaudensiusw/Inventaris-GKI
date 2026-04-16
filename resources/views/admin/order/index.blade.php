@extends('layouts.master', ['title' => 'Manajemen Pengajuan Pinjaman'])

@section('content')
    <x-container>
        <div class="col-12">
            <x-card title="DAFTAR PENGAJUAN PINJAMAN ASET" class="card-body p-0">
                <x-table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Peminjam</th>
                            <th>Aset</th>
                            <th>No Induk</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $i => $order)
                            <tr>
                                <td>{{ $i + $orders->firstItem() }}</td>
                                <td>{{ $order->user->name ?? '-' }}</td>
                                <td>{{ $order->item->name ?? '-' }}</td>
                                <td>{{ $order->identity_number }}</td>
                                <td>{{ $order->start_date }}</td>
                                <td>{{ $order->end_date }}</td>
                                <td>
                                    <span class="badge {{ $order->status == 'Pending' ? 'bg-yellow' : ($order->status == 'Approved' ? 'bg-green' : ($order->status == 'Returned' ? 'bg-blue' : 'bg-red')) }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td>
                                    @if ($order->status == 'Pending')
                                        <form action="{{ route('admin.order.update', $order->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Approved">
                                            <x-button-save title="Setujui" icon="check" class="btn btn-success btn-sm mb-1" />
                                        </form>
                                        <form action="{{ route('admin.order.update', $order->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Rejected">
                                            <x-button-save title="Tolak" icon="x" class="btn btn-danger btn-sm mb-1" />
                                        </form>
                                    @elseif($order->status == 'Approved')
                                        <form action="{{ route('admin.order.update', $order->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Returned">
                                            <x-button-save title="Selesaikan (Barang Kembali)" icon="arrow-back-up" class="btn btn-primary btn-sm mb-1" />
                                        </form>
                                    @endif
                                    @if ($order->status == 'Rejected' || $order->status == 'Returned')
                                        <x-button-delete :id="$order->id" :url="route('admin.order.destroy', $order->id)" title="Hapus Log" class="btn btn-danger btn-sm mb-1" />
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Saat ini tidak ada pengajuan peminjaman barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </x-table>
            </x-card>
            <div class="d-flex justify-content-end">{{ $orders->links() }}</div>
        </div>
    </x-container>
@endsection
