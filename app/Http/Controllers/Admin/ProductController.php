<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Show all products
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    // Show add product form
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    // Save new product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_url' => 'nullable|url|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = $validated['image_url'] ?? null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug($validated['name']),
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'stock' => $validated['stock'],
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active'),
        ]);

        ActivityLogger::log(
            'Product Created',
            "Created product: {$product->name}",
            $product
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product added successfully!');
    }

    // Show edit form
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Update product
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_url' => 'nullable|url|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            if ($imagePath && !Str::startsWith($imagePath, ['http://', 'https://'])) {
                Storage::disk('public')->delete($imagePath);
            }

            $imagePath = $request->file('image')->store('products', 'public');
        } elseif ($request->filled('image_url')) {
            if ($imagePath && !Str::startsWith($imagePath, ['http://', 'https://'])) {
                Storage::disk('public')->delete($imagePath);
            }

            $imagePath = $validated['image_url'];
        }

        $product->update([
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug($validated['name'], $product->id),
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'stock' => $validated['stock'],
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active'),
        ]);

        ActivityLogger::log(
            'Product Updated',
            "Updated product: {$product->name}",
            $product
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    // Delete product
    public function destroy(Product $product)
    {
        // Record the activity before deleting the product
        ActivityLogger::log(
            'Product Deleted',
            "Deleted product: {$product->name}",
            $product
        );

        if ($product->image && !Str::startsWith($product->image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    // Generate a unique URL slug
    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
