<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        // Simpan kategori baru
        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        return response()->json($category);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category->update([
            'name' => $request->name
        ]);

        return response()->json(['success' => 'Kategori berhasil diperbarui']);
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Hapus kategori
        $category->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Kategori berhasil dihapus!');
    }
}
