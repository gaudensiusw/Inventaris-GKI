<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('rooms', 'public');
            $validated['image'] = $path;
        }

        Room::create($validated);

        return back()->with('success', 'Lokasi penyimpanan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:rooms,name,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }
            $path = $request->file('image')->store('rooms', 'public');
            $validated['image'] = $path;
        }

        $room->update($validated);

        return back()->with('success', 'Lokasi penyimpanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        
        if ($room->items()->withTrashed()->count() > 0) {
            return back()->with('error', 'Lokasi tidak bisa dihapus karena masih berisi barang (termasuk barang di Disposal)!');
        }

        if ($room->image) {
            Storage::disk('public')->delete($room->image);
        }

        $room->delete();

        return back()->with('success', 'Lokasi penyimpanan berhasil dihapus!');
    }
}
