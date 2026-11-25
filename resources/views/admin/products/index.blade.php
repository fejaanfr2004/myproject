
@extends('layouts.admin')

@section('title', 'Product Management | Luxury Fashion Hub')

@push('styles')
<style>
.product-page {
    max-width: 1250px;
    margin: 0 auto;
    color: #eee;
}
.product-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 25px;
}
.product-top h1 { margin: 0 0 8px; font-size: 28px; }
.product-top p { color: #aaa; margin: 0; }
.product-btn {
    display: inline-block;
    padding: 11px 16px;
    border: 1px solid #c9a44c;
    border-radius: 5px;
    background: #c9a44c;
    color: #111;
    text-decoration: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
}
.product-btn:hover { background: #e0bd67; }
.product-btn.secondary {
    background: transparent;
    color: #ddd;
    border-color: #555;
}
.product-btn.secondary:hover { background: #292929; }
.product-btn.edit {
    background: #292929;
    color: #e3bd65;
    border-color: #555;
}
.product-btn.delete {
    background: #8e2525;
    color: white;
    border-color: #8e2525;
}
.product-card {
    background: #171717;
    border: 1px solid #333;
    border-radius: 10px;
    padding: 22px;
    overflow-x: auto;
}
.product-message {
    padding: 13px 16px;
    margin-bottom: 18px;
    background: #183522;
    color: #9de2ae;
    border: 1px solid #285b38;
    border-radius: 6px;
}
.product-error {
    padding: 13px 16px;
    margin-bottom: 18px;
    background: #3b1c1c;
    color: #ffaaaa;
    border: 1px solid #713333;
    border-radius: 6px;
}
.product-search {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 18px;
}
.product-search input {
    min-width: 220px;
    flex: 1;
    padding: 12px;
    background: #222;
    border: 1px solid #444;
    color: white;
    border-radius: 5px;
}
.product-table {
    width: 100%;
    min-width: 760px;
    border-collapse: collapse;
}
.product-table th,
.product-table td {
    padding: 14px 10px;
    border-bottom: 1px solid #333;
    text-align: left;
}
.product-table th {
    color: #d8b65d;
    background: #202020;
    font-weight: 600;
}
.product-table td { color: #ddd; }
.product-photo {
    width: 58px;
    height: 65px;
    object-fit: cover;
    border-radius: 5px;
}
.product-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}
.product-actions form { margin: 0; }
.product-status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    background: #183522;
    color: #9de2ae;
    font-size: 12px;
}
.product-status.inactive {
    background: #333;
    color: #bbb;
}
.product-pagination { margin-top: 25px; }
.product-pagination nav { display: flex; justify-content: space-between; }
.product-pagination a,
.product-pagination span {
    color: #d8b65d;
}
.product-empty { text-align: center; padding: 30px; color: #aaa; }
@media(max-width: 600px) {
    .product-card { padding: 12px; }
    .product-top h1 { font-size: 23px; }
}
</style>
@endpush

@section('admin-content')
<div class="product-page">

    <div class="product-top">
        <div>
            <h1>Product Management</h1>
            <p>Manage your Luxury Fashion Hub products.</p>
        </div>

        <div>
            <a class="product-btn secondary" href="{{ route('admin.dashboard') }}">
                Dashboard
            </a>

            @can('create-products')
                <a class="product-btn" href="{{ route('admin.products.create') }}">
                    + Add Product
                </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="product-message">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="product-error">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="product-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="product-card">

        <form class="product-search" method="GET"
              action="{{ route('admin.products.index') }}">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search products..."
            >
            <button class="product-btn" type="submit">Search</button>
            <a class="product-btn secondary"
               href="{{ route('admin.products.index') }}">Reset</a>
        </form>

        <table class="product-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            @if($product->image)
                                <img
                                    class="product-photo"
                                    src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image) }}"
                                    alt="{{ $product->name }}"
                                >
                            @else
                                No image
                            @endif
                        </td>

                        <td>
                            <strong>{{ $product->name }}</strong>
                            <br>
                            <small>{{ $product->category?->name ?? 'Uncategorised' }}</small>
                        </td>

                        <td>₹{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->stock }}</td>

                        <td>
                            @if($product->is_active)
                                <span class="product-status">Active</span>
                            @else
                                <span class="product-status inactive">Inactive</span>
                            @endif
                        </td>

                        <td>
                            <div class="product-actions">
                                @can('edit-products')
                                    <a class="product-btn edit"
                                       href="{{ route('admin.products.edit', $product) }}">
                                        Edit
                                    </a>
                                @endcan

                                @can('delete-products')
                                    <form
                                        action="{{ route('admin.products.destroy', $product) }}"
                                        method="POST"
                                        data-confirm="Are you sure you want to delete this product?"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button class="product-btn delete" type="submit">
                                            Delete
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="product-empty">
                            No products found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="product-pagination">
            {{ $products->links() }}
        </div>

    </div>
</div>
@endsection
