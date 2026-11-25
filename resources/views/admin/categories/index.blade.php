
@extends('layouts.admin')

@section('admin-content')

<style>
.category-page {
    padding: 10px;
}
.category-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 25px;
}
.category-header h1 {
    font-size: 28px;
    color: #d4af37;
    margin: 0 0 6px;
}
.category-subtitle {
    color: #999;
}
.category-btn {
    background: #d4af37;
    color: #111;
    padding: 11px 18px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    border: none;
    cursor: pointer;
}
.category-table-wrap {
    overflow-x: auto;
    background: #171717;
    border: 1px solid #333;
    border-radius: 10px;
}
.category-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 650px;
}
.category-table th,
.category-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #333;
}
.category-table th {
    color: #d4af37;
    background: #202020;
}
.category-table td {
    color: #eee;
}
.category-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.category-edit,
.category-delete {
    padding: 7px 12px;
    border-radius: 5px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-weight: bold;
}
.category-edit {
    background: #d4af37;
    color: #111;
}
.category-delete {
    background: #a83232;
    color: white;
}
.category-alert {
    padding: 12px 15px;
    margin-bottom: 18px;
    border-radius: 6px;
}
.category-success {
    background: #173d27;
    color: #9be0ae;
}
.category-error {
    background: #4a2020;
    color: #ffb0b0;
}
.category-pagination {
    margin-top: 20px;
}
.category-pagination nav {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.category-pagination a,
.category-pagination span {
    color: #d4af37;
    padding: 5px;
}
</style>

<div class="category-page">

    <div class="category-header">
        <div>
            <h1>Category Management</h1>
            <div class="category-subtitle">
                Create, edit and manage your product categories.
            </div>
        </div>

        <a href="{{ route('admin.categories.create') }}"
           class="category-btn"
           data-admin-link>
            + Add Category
        </a>
    </div>

    @if(session('success'))
        <div class="category-alert category-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="category-alert category-error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="category-alert category-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="category-table-wrap">
        <table class="category-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category Name</th>
                    <th>Slug</th>
                    <th>Products</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $categories->firstItem() + $loop->index }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>{{ $category->products_count }}</td>

                        <td>
                            <div class="category-actions">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="category-edit"
                                   data-admin-link>
                                    Edit
                                </a>

                                <form action="{{ route('admin.categories.destroy', $category) }}"
                                      method="POST"
                                      data-confirm="Are you sure you want to delete this category?">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="category-delete">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:30px;">
                            No categories found. Click Add Category to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="category-pagination">
        {{ $categories->links() }}
    </div>

</div>

@endsection
