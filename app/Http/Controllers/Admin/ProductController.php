<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Fitur cari produk sesuai input di desain
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Fitur filter berdasarkan kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(12);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
        ], [
            'name.unique' => 'Nama produk ini sudah ada! Silakan gunakan nama lain.',
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'price.required' => 'Harga produk wajib diisi.',
            'stock.required' => 'Stok produk wajib diisi.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $stock = (int) $request->stock;
        $isActive = $stock > 0;

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $stock,
            'image' => $imagePath,
            'is_active' => $isActive,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
        ], [
            'name.unique' => 'Nama produk ini sudah ada! Silakan gunakan nama lain.',
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'price.required' => 'Harga produk wajib diisi.',
            'stock.required' => 'Stok produk wajib diisi.',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            $oldCleanPath = $this->normalizeImagePath($product->image);
            if ($oldCleanPath && Storage::disk('public')->exists($oldCleanPath)) {
                Storage::disk('public')->delete($oldCleanPath);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $stock = (int) $request->stock;
        $isActive = $stock > 0 ? $product->is_active : false;

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $stock,
            'image' => $imagePath,
            'is_active' => $isActive,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();

        return redirect()->back()->with('success', 'Status produk berhasil diubah.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $cleanPath = $this->normalizeImagePath($product->image);
        if ($cleanPath && Storage::disk('public')->exists($cleanPath)) {
            Storage::disk('public')->delete($cleanPath);
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function normalizeImagePath(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $clean = preg_replace('#^(public/|storage/)#', '', ltrim($path, '/'));
        return $clean !== '' ? $clean : null;
    }
}