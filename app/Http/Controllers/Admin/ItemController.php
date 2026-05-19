<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $categoryId = $request->get('category_id');
        $roomId = $request->get('room_id');
        $perPage = min((int) $request->get('per_page', 10), 100);

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

        if ($roomId) {
            $query->where('room_id', $roomId);
        }

        $items = $query->latest()->paginate($perPage)->withQueryString();
        $categories = Category::all();
        $rooms = Room::all();

        return view('admin.inventory.index', compact('items', 'categories', 'rooms', 'perPage', 'search', 'categoryId', 'roomId'));
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
            'purchase_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        try {
            // Generate Asset Code based on Category
            $categoryId = $validated['category_id'];
            
            // Find the last item in this category to get the next sequence number
            // We look for codes that start with the category ID
            $lastItem = Item::where('category_id', $categoryId)
                            ->where('kode_aset', 'REGEXP', '^' . $categoryId . '[0-9]+$')
                            ->orderByRaw('LENGTH(kode_aset) DESC, kode_aset DESC')
                            ->first();

            $nextSequence = 1;
            if ($lastItem) {
                // Extract number from the end (e.g. 4001 -> 001 -> 1)
                $lastCode = $lastItem->kode_aset;
                $sequenceStr = substr($lastCode, strlen($categoryId));
                if (is_numeric($sequenceStr)) {
                    $nextSequence = (int)$sequenceStr + 1;
                }
            }
            
            // Format: CategoryID + 4-digit sequence (e.g. 10001)
            $validated['kode_aset'] = $categoryId . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
            $validated['barcode'] = $validated['kode_aset'];
            $validated['slug'] = Str::slug($validated['name']) . '-' . time();
            
            // Default condition and status based on quantities
            $validated['condition'] = $validated['qty_baik'] > 0 ? 'Baik' : ($validated['qty_rusak_ringan'] > 0 ? 'Rusak Ringan' : 'Rusak Berat');
            $validated['status'] = $validated['qty_tersedia'] > 0 ? 'Tersedia' : ($validated['qty_dipinjam'] > 0 ? 'Dipinjam' : 'Lainnya');
            
            // Sync legacy fields
            $validated['unit'] = 'Unit';
            $validated['is_write_off'] = false;
            $validated['keterangan'] = $validated['description'];
            $validated['tgl_perolehan'] = $validated['purchase_date'];

            Item::create($validated);

            return redirect()->route('inventory.index')->with('success', 'Barang berhasil ditambahkan dengan Kode Aset: ' . $validated['kode_aset']);
        } catch (\Exception $e) {
            Log::error('Gagal simpan barang: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Gagal menambahkan barang: ' . $e->getMessage())->withInput();
        }
    }

    // Method show() dihapus — detail barang ditampilkan via modal popup di halaman index.

    public function exportCsv()
    {
        $items = Item::with(['category', 'room'])->get();
        $csvHeader = ['ID', 'Kode Aset', 'Nama', 'Kategori', 'Lokasi', 'Total', 'Tersedia'];
        
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

    public function destroy(Request $request, $id)
    {
        try {
            $item = Item::findOrFail($id);
            $item->deleted_by = auth()->id();
            $item->delete_reason = $request->input('delete_reason', 'Tanpa alasan');
            $item->save();
            $item->delete(); // This will soft delete because of the SoftDeletes trait
            
            return redirect()->route('inventory.index')->with('success', 'Barang berhasil dipindahkan ke daftar penghapusan.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus barang: ' . $e->getMessage(), ['id' => $id]);
            return back()->with('error', 'Gagal menghapus barang. Silakan coba lagi.');
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
            'purchase_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        try {
            $item = Item::findOrFail($id);
            
            // Default condition and status based on quantities
            $validated['condition'] = $validated['qty_baik'] > 0 ? 'Baik' : ($validated['qty_rusak_ringan'] > 0 ? 'Rusak Ringan' : 'Rusak Berat');
            $validated['status'] = $validated['qty_tersedia'] > 0 ? 'Tersedia' : ($validated['qty_dipinjam'] > 0 ? 'Dipinjam' : 'Lainnya');
            $validated['keterangan'] = $validated['description'];
            $validated['tgl_perolehan'] = $validated['purchase_date'];

            $item->update($validated);

            return redirect()->route('inventory.index')->with('success', 'Barang berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal update barang: ' . $e->getMessage(), [
                'id' => $id,
                'request' => $request->all()
            ]);
            return back()->with('error', 'Gagal memperbarui barang: ' . $e->getMessage())
                        ->with('is_edit', true)
                        ->with('edit_item_id', $id)
                        ->withInput();
        }
    }
}
