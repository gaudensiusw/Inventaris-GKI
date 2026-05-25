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
        $category = Category::findOrFail($id);

        if (\App\Models\Item::withTrashed()->where('category_id', $category->id)->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki barang (termasuk barang di Disposal)!');
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}
