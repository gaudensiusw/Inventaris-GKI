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

    public function show($id)
    {
        $room = Room::withCount('items')->findOrFail($id);
        $items = $room->items()->with(['category'])->get();
        $categories = \App\Models\Category::orderBy('name')->get();
        $rooms = Room::orderBy('name')->get();
        // Load all items including soft-deleted ones for the bulk move list
        $allItems = \App\Models\Item::withTrashed()->where('room_id', $id)->with(['category'])->get();
        
        return view('admin.room.show', compact('room', 'items', 'categories', 'rooms', 'allItems'));
    }

    public function bulkMove(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*' => 'required|exists:rooms,id',
        ]);

        try {
            \DB::transaction(function() use ($validated, $id) {
                foreach ($validated['items'] as $itemId => $targetRoomId) {
                    // Skip if the target room is the same as the current room
                    if ($targetRoomId == $id) {
                        continue;
                    }
                    
                    $item = \App\Models\Item::withTrashed()->findOrFail($itemId);
                    $item->room_id = $targetRoomId;
                    $item->save();
                }
            });

            return redirect()->route('room.show', $id)->with('success', 'Semua barang terpilih berhasil dipindahkan ke lokasi baru!');
        } catch (\Exception $e) {
            \Log::error('Gagal melakukan pemindahan masal barang: ' . $e->getMessage());
            return back()->with('error', 'Gagal memindahkan barang: ' . $e->getMessage());
        }
    }
}
