<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->latest()
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => $this->generateSlug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        ActivityLogger::log(
            'Category Created',
            "Created category: {$category->name}",
            $category
        );

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'description' => ['nullable', 'string'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => $this->generateSlug($validated['name'], $category->id),
            'description' => $validated['description'] ?? null,
        ]);

        ActivityLogger::log(
            'Category Updated',
            "Updated category: {$category->name}",
            $category
        );

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with(
                'error',
                'This category cannot be deleted because products are assigned to it. Reassign those products first.'
            );
        }

        ActivityLogger::log(
            'Category Deleted',
            "Deleted category: {$category->name}",
            $category
        );

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    private function generateSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
