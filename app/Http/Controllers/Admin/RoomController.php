<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
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
        return view('admin.room.index', compact('rooms', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:rooms,name',
            'description' => 'nullable|string',
        ]);

        Room::create($validated);

        return back()->with('success', 'Lokasi penyimpanan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:rooms,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $room->update($validated);

        return back()->with('success', 'Lokasi penyimpanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        
        if ($room->items()->count() > 0) {
            return back()->with('error', 'Lokasi tidak bisa dihapus karena masih berisi barang!');
        }

        $room->delete();

        return back()->with('success', 'Lokasi penyimpanan berhasil dihapus!');
    }
}
