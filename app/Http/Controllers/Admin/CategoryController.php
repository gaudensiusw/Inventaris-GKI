<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('items')->orderBy('name')->get();
        return view('admin.category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $category = Category::findOrFail($id);

        if ($category->name === 'Belum Dikategorikan') {
            return back()->with('error', 'Kategori default tidak bisa dihapus!');
        }

        // Find or create default category "Belum Dikategorikan"
        $defaultCategory = Category::firstOrCreate(
            ['name' => 'Belum Dikategorikan'],
            ['description' => 'Kategori default untuk barang yang kategorinya telah dihapus.']
        );

        // Move all items (including soft-deleted ones) belonging to the deleted category to the default category
        \App\Models\Item::withTrashed()
            ->where('category_id', $category->id)
            ->update(['category_id' => $defaultCategory->id]);

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}
