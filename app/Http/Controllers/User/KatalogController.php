<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Room;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $categoryId = $request->get('category_id');
        $roomId = $request->get('room_id');

        $query = Item::with(['category', 'room']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('kode_aset', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($roomId) {
            $query->where('room_id', $roomId);
        }

        $items = $query->orderBy('name')->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $rooms = Room::orderBy('name')->get();

        return view('user.katalog.index', compact('items', 'categories', 'rooms', 'search', 'categoryId', 'roomId'));
    }

    public function rooms(Request $request)
    {
        $search = $request->get('search');
        $query = Room::withCount('items')->orderBy('name');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $rooms = $query->get();
        return view('user.katalog.rooms', compact('rooms', 'search'));
    }

    public function roomShow($id)
    {
        $room = Room::withCount('items')->findOrFail($id);
        $items = $room->items()->with(['category'])->get();
        return view('user.katalog.room_show', compact('room', 'items'));
    }

    public function qrScanner()
    {
        $recentItems = Item::latest()->take(5)->get();
        return view('user.katalog.qr_scanner', compact('recentItems'));
    }

    public function qrSearch(Request $request)
    {
        $request->validate(['code' => 'required|string|max:50']);
        $code = $request->code;
        $item = Item::where('kode_aset', $code)
            ->orWhere('barcode', $code)
            ->orWhere('entno', $code)
            ->first();

        if ($item) {
            $item->load(['category', 'room']);
            return response()->json([
                'success' => true,
                'item' => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'kode_aset' => $item->kode_aset,
                    'quantity' => $item->quantity,
                    'status' => $item->status,
                    'condition' => $item->condition,
                    'category' => $item->category->name ?? 'Tanpa Kategori',
                    'room' => $item->room->name ?? 'Tanpa Ruangan',
                    'image_url' => $item->image_url,
                ],
                'redirect_url' => route('user.orders.create', $item->id)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Barang dengan kode tersebut tidak ditemukan.'
        ]);
    }

    public function show($id)
    {
        $item = Item::with(['category', 'room'])->findOrFail($id);
        
        // Fetch pending and approved orders for this item
        $orders = \App\Models\Order::where('id_barang', $id)
            ->whereIn('status', ['Pending', 'Disetujui'])
            ->get();
            
        // Map orders to calendar events
        $events = $orders->map(function($order) {
            $color = $order->status === 'Disetujui' ? '#10b981' : '#f97316'; // Green for Booked (Approved), Orange for Pending Request
            return [
                'title' => ($order->status === 'Disetujui' ? 'Booked' : 'Pending') . ' (' . $order->nama_peminjam . ')',
                'start' => $order->start_date->format('Y-m-d'),
                'end' => $order->end_date->addDay()->format('Y-m-d'), // add 1 day for inclusive end date display
                'color' => $color,
                'allDay' => true,
            ];
        });

        return view('user.katalog.show', compact('item', 'events'));
    }
}
