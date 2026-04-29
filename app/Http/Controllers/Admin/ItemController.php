<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $items = Item::with(['category', 'room'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('barcode', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);
            
        return view('admin.item.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        $rooms = Room::all();
        return view('admin.item.create', compact('categories', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'room_id' => 'required|exists:rooms,id',
            'name' => 'required|string|max:255',
            'barcode' => 'required|string|unique:items,barcode',
            'condition' => 'required|in:Baik,Rusak ringan,Rusak berat',
            'status' => 'required|in:Tersedia,Dipinjam,Sedang diperbaiki,Hilang,Tidak digunakan,Dalam pengadaan',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(5);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/items', $image->hashName());
            $data['image'] = $image->hashName();
        }

        Item::create($data);

        return redirect()->route('admin.item.index')->with('success', 'Item berhasil ditambahkan.');
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        $rooms = Room::all();
        return view('admin.item.edit', compact('item', 'categories', 'rooms'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'room_id' => 'required|exists:rooms,id',
            'name' => 'required|string|max:255',
            'barcode' => 'required|string|unique:items,barcode,' . $item->id,
            'condition' => 'required|in:Baik,Rusak ringan,Rusak berat',
            'status' => 'required|in:Tersedia,Dipinjam,Sedang diperbaiki,Hilang,Tidak digunakan,Dalam pengadaan',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(5);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/items', $image->hashName());
            $data['image'] = $image->hashName();
        }

        $item->update($data);

        return redirect()->route('admin.item.index')->with('success', 'Item berhasil diupdate.');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('admin.item.index')->with('success', 'Item berhasil dihapus.');
    }

    public function barcode(Item $item)
    {
        return view('admin.item.qrcode', compact('item'));
    }
}

