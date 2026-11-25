
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

    <h1>Edit Category</h1>
    <p class="category-form-subtitle">
        Update the category information.
    </p>

    <div class="category-form-box">

        <form action="{{ route('admin.categories.update', $category) }}"
              method="POST">
            @csrf
            @method('PUT')

            <div class="category-field">
                <label for="name">Category Name</label>

                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $category->name) }}"
                       required>

                @error('name')
                    <div class="category-validation">{{ $message }}</div>
                @enderror
            </div>

            <div class="category-field">
                <label for="description">Description</label>

                <textarea id="description"
                          name="description">{{ old('description', $category->description) }}</textarea>

                @error('description')
                    <div class="category-validation">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="category-submit">
                Update Category
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
