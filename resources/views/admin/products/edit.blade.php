
@extends('layouts.admin')

@section('title', 'Edit Product | Luxury Fashion Hub')

@push('styles')
<style>
.product-form-page {
    max-width: 800px;
    margin: 0 auto;
    color: #eee;
}
.product-form-card {
    background: #171717;
    border: 1px solid #333;
    border-radius: 10px;
    padding: 30px;
}
.product-form-page h1 {
    margin: 0 0 10px;
    font-size: 27px;
}
.product-form-page .subtitle {
    color: #aaa;
    margin-bottom: 22px;
}
.product-form-page label {
    display: block;
    margin: 18px 0 8px;
    color: #d8b65d;
    font-weight: 600;
}
.product-form-page input:not([type="checkbox"]):not([type="file"]),
.product-form-page textarea,
.product-form-page select {
    width: 100%;
    padding: 12px;
    background: #222;
    border: 1px solid #444;
    border-radius: 5px;
    color: white;
    font: inherit;
}
.product-form-page input[type="file"] {
    display: block;
    width: 100%;
    color: #ccc;
    padding: 10px 0;
}
.product-form-page textarea {
    min-height: 120px;
    resize: vertical;
}
.product-form-page select option { background: #222; }
.product-form-page .checkbox-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 20px;
    color: #ddd;
}
.product-form-page .checkbox-row input {
    width: 17px;
    height: 17px;
    accent-color: #c9a44c;
}
.product-form-page .checkbox-row label {
    margin: 0;
    color: #ddd;
}
.product-form-page .form-error {
    padding: 13px;
    margin-bottom: 18px;
    background: #3b1c1c;
    color: #ffaaaa;
    border: 1px solid #713333;
    border-radius: 6px;
}
.product-form-page .form-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 25px;
}
.product-form-page .form-btn {
    display: inline-block;
    padding: 12px 20px;
    background: #c9a44c;
    color: #111;
    border: 1px solid #c9a44c;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
    font-weight: 600;
}
.product-form-page .form-btn:hover { background: #e0bd67; }
.product-form-page .form-btn.cancel {
    background: transparent;
    color: #ddd;
    border-color: #555;
}
.product-form-page .preview {
    display: block;
    width: 140px;
    height: 150px;
    object-fit: cover;
    margin-top: 10px;
    border: 1px solid #444;
    border-radius: 6px;
}
@media(max-width: 600px) {
    .product-form-card { padding: 18px; }
}
</style>
@endpush

@section('admin-content')
<div class="product-form-page">
    <div class="product-form-card">

        <h1>Edit Product</h1>
        <p class="subtitle">Update the details of this product.</p>

        <a class="form-btn cancel" href="{{ route('admin.products.index') }}">
            ← Back to Products
        </a>

        @if($errors->any())
            <div class="form-error" style="margin-top:20px;">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.products.update', $product) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <label>Product Name</label>
            <input type="text" name="name"
                   value="{{ old('name', $product->name) }}" required>

            <label>Price (₹)</label>
            <input type="number" name="price" min="0" step="0.01"
                   value="{{ old('price', $product->price) }}" required>

            <label>Description</label>
            <textarea name="description">{{ old('description', $product->description) }}</textarea>

            <label>Category</label>
            <select name="category_id">
                <option value="">Select category (optional)</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        @selected(old('category_id', $product->category_id) == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <label>Stock Quantity</label>
            <input type="number" name="stock" min="0"
                   value="{{ old('stock', $product->stock) }}" required>

            @if($product->image)
                <label>Current Image</label>
                <img class="preview"
                     src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image) }}"
                     alt="{{ $product->name }}">
            @endif

            <label>Upload a New Image (optional)</label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">

            <label>Or Replace with an Image URL</label>
            <input type="url" name="image_url"
                   placeholder="Leave blank to keep current image"
                   value="{{ old('image_url') }}">

            <div class="checkbox-row">
                <input type="checkbox" id="is_active" name="is_active"
                       value="1" @checked(old('is_active', $product->is_active))>
                <label for="is_active">Make product visible in store</label>
            </div>

            <div class="form-buttons">
                <button class="form-btn" type="submit">Update Product</button>

                <a class="form-btn cancel"
                   href="{{ route('admin.products.index') }}">Cancel</a>
            </div>

        </form>
    </div>
</div>
@endsection
