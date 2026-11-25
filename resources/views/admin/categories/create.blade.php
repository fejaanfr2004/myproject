
@extends('layouts.admin')

@section('admin-content')

<style>
.category-form-page {
    max-width: 750px;
    padding: 10px;
}
.category-form-page h1 {
    color: #d4af37;
    font-size: 28px;
    margin-bottom: 8px;
}
.category-form-subtitle {
    color: #999;
    margin-bottom: 25px;
}
.category-form-box {
    background: #171717;
    border: 1px solid #333;
    border-radius: 10px;
    padding: 25px;
}
.category-field {
    margin-bottom: 20px;
}
.category-field label {
    display: block;
    color: #eee;
    margin-bottom: 9px;
    font-weight: bold;
}
.category-field input,
.category-field textarea {
    width: 100%;
    padding: 12px;
    background: #242424;
    border: 1px solid #444;
    border-radius: 6px;
    color: white;
    font-size: 15px;
}
.category-field textarea {
    min-height: 120px;
    resize: vertical;
}
.category-submit {
    background: #d4af37;
    color: #111;
    border: none;
    padding: 12px 20px;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
}
.category-back {
    color: #d4af37;
    text-decoration: none;
    margin-left: 15px;
}
.category-validation {
    color: #ff9999;
    margin-top: 6px;
}
</style>

<div class="category-form-page">

    <h1>Add New Category</h1>
    <p class="category-form-subtitle">
        Create a category for your fashion products.
    </p>

    <div class="category-form-box">

        <form action="{{ route('admin.categories.store') }}"
              method="POST">
            @csrf

            <div class="category-field">
                <label for="name">Category Name</label>

                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Example: Men's Clothing"
                       required>

                @error('name')
                    <div class="category-validation">{{ $message }}</div>
                @enderror
            </div>

            <div class="category-field">
                <label for="description">Description</label>

                <textarea id="description"
                          name="description"
                          placeholder="Enter category description...">{{ old('description') }}</textarea>

                @error('description')
                    <div class="category-validation">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="category-submit">
                Create Category
            </button>

            <a href="{{ route('admin.categories.index') }}"
               class="category-back"
               data-admin-link>
                Cancel
            </a>

        </form>

    </div>
</div>

@endsection
