<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $categoryId = $request->get('category_id');
        $perPage = $request->get('per_page', 10);

        $query = Item::with(['category', 'room']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('kode_aset', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $items = $query->latest()->paginate($perPage)->withQueryString();
        $categories = Category::all();
        $rooms = Room::all();

        return view('admin.inventory.index', compact('items', 'categories', 'rooms', 'perPage', 'search', 'categoryId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'room_id' => 'required|exists:rooms,id',
            'quantity' => 'required|integer|min:1',
            'qty_baik' => 'required|integer|min:0',
            'qty_rusak_ringan' => 'required|integer|min:0',
            'qty_rusak_berat' => 'required|integer|min:0',
            'qty_tersedia' => 'required|integer|min:0',
            'qty_dipinjam' => 'required|integer|min:0',
            'qty_diperbaiki' => 'required|integer|min:0',
            'qty_hilang' => 'required|integer|min:0',
            'qty_tidak_digunakan' => 'required|integer|min:0',
            'qty_pengadaan' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        try {
            // Generate metadata
            $count = Item::count() + 1;
            $validated['kode_aset'] = 'INV-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            $validated['slug'] = Str::slug($validated['name']) . '-' . time();
            
            // Default condition and status based on quantities
            $validated['condition'] = $validated['qty_baik'] > 0 ? 'Baik' : ($validated['qty_rusak_ringan'] > 0 ? 'Rusak Ringan' : 'Rusak Berat');
            $validated['status'] = $validated['qty_tersedia'] > 0 ? 'Tersedia' : ($validated['qty_dipinjam'] > 0 ? 'Dipinjam' : 'Lainnya');
            
            // Set other defaults for legacy database
            $validated['unit'] = 'Unit';
            $validated['is_write_off'] = false;
            $validated['keterangan'] = $validated['description'];

            // Explicitly set null for other fields to avoid potential issues
            $validated['merk_model'] = null;
            $validated['spesifikasi'] = null;

            Item::create($validated);

            return redirect()->route('inventory.index')->with('success', 'Barang berhasil ditambahkan!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal simpan barang: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan barang: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $item = Item::with(['category', 'room'])->findOrFail($id);
        return view('admin.inventory.show', compact('item'));
    }

    public function exportCsv()
    {
        $items = Item::with(['category', 'room'])->get();
        $csvHeader = ['ID', 'Kode Aset', 'Nama', 'Kategori', 'Lokasi', 'Total', 'Tersedia', 'Harga'];
        
        $callback = function() use ($items, $csvHeader) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $csvHeader);
            foreach ($items as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->kode_aset,
                    $item->name,
                    $item->category->name ?? '-',
                    $item->room->name ?? '-',
                    $item->quantity,
                    $item->qty_tersedia,
                    $item->price
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=inventaris-gki.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ]);
    }

    public function destroy($id)
    {
        try {
            $item = Item::findOrFail($id);
            $item->delete(); // This will soft delete because of the SoftDeletes trait
            
            return redirect()->route('inventory.index')->with('success', 'Barang berhasil dipindahkan ke daftar penghapusan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'room_id' => 'required|exists:rooms,id',
            'quantity' => 'required|integer|min:1',
            'qty_baik' => 'required|integer|min:0',
            'qty_rusak_ringan' => 'required|integer|min:0',
            'qty_rusak_berat' => 'required|integer|min:0',
            'qty_tersedia' => 'required|integer|min:0',
            'qty_dipinjam' => 'required|integer|min:0',
            'qty_diperbaiki' => 'required|integer|min:0',
            'qty_hilang' => 'required|integer|min:0',
            'qty_tidak_digunakan' => 'required|integer|min:0',
            'qty_pengadaan' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        try {
            $item = Item::findOrFail($id);
            
            // Default condition and status based on quantities
            $validated['condition'] = $validated['qty_baik'] > 0 ? 'Baik' : ($validated['qty_rusak_ringan'] > 0 ? 'Rusak Ringan' : 'Rusak Berat');
            $validated['status'] = $validated['qty_tersedia'] > 0 ? 'Tersedia' : ($validated['qty_dipinjam'] > 0 ? 'Dipinjam' : 'Lainnya');
            $validated['keterangan'] = $validated['description'];

            $item->update($validated);

            return redirect()->route('inventory.index')->with('success', 'Barang berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui barang: ' . $e->getMessage())
                        ->with('is_edit', true)
                        ->with('edit_item_id', $id)
                        ->withInput();
        }
    }
}
