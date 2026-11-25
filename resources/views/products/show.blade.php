
@extends('layouts.app')

@section('title', $product->name . ' | Luxury Fashion Hub')

@push('styles')
<style>
.product-container {
    max-width: 1100px;
    margin: 55px auto;
    padding: 20px;
}

.back-link {
    display: inline-block;
    margin-bottom: 25px;
    color: #777;
    text-decoration: none;
}

.back-link:hover {
    color: #b89558;
}

.product-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 55px;
    align-items: center;
}

.product-image {
    width: 100%;
    height: 550px;
    object-fit: cover;
    background: #eee;
}

.details small {
    color: #a48650;
    letter-spacing: 3px;
}

.details h1 {
    font-size: 38px;
    margin: 15px 0;
}

.product-price {
    color: #9b7945;
    font-size: 26px;
    font-weight: bold;
}

.product-description {
    color: #666;
    line-height: 1.8;
    margin: 25px 0;
}

.add-cart {
    width: 100%;
    padding: 17px;
    border: none;
    background: #111;
    color: white;
    font-weight: bold;
    cursor: pointer;
    margin-top: 25px;
    transition: 0.3s;
}

.add-cart:hover {
    background: #c9a96e;
    color: #111;
}

.add-cart:disabled {
    background: #999;
    cursor: not-allowed;
}

.stock-message {
    margin-top: 15px;
    color: #777;
}

@media(max-width: 700px) {
    .product-details-grid {
        grid-template-columns: 1fr;
        gap: 25px;
    }

    .product-image {
        height: 400px;
    }

    .details h1 {
        font-size: 29px;
    }
}
</style>
@endpush

@section('content')

<div class="product-container">

    <a class="back-link" href="{{ route('products.index') }}">
        ← Back to collection
    </a>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="product-details-grid">

        <div>
            <img
                class="product-image"
                src="{{ $product->image
                    ? (str_starts_with($product->image, 'http')
                        ? $product->image
                        : asset('storage/' . $product->image))
                    : 'https://placehold.co/600x700?text=No+Image' }}"
                alt="{{ $product->name }}"
            >
        </div>

        <div class="details">

            <small>LUXURY FASHION COLLECTION</small>

            <h1>{{ $product->name }}</h1>

            <p class="product-price">
                ₹{{ number_format((float) $product->price, 2) }}
            </p>

            <p class="product-description">
                {{ $product->description ?: 'Premium quality. Modern style. Everyday comfort.' }}
            </p>

            <p>Premium quality. Modern style. Everyday comfort.</p>

            <p class="stock-message">
                @if($product->stock > 0)
                    In Stock: {{ $product->stock }} available
                @else
                    Currently out of stock
                @endif
            </p>

            <form action="{{ route('cart.add') }}" method="POST">
                @csrf

                <input
                    type="hidden"
                    name="product_id"
                    value="{{ $product->id }}"
                >

                <input
                    type="hidden"
                    name="quantity"
                    value="1"
                >

                <button
                    class="add-cart"
                    type="submit"
                    @if($product->stock < 1) disabled @endif
                >
                    {{ $product->stock > 0 ? 'ADD TO CART' : 'OUT OF STOCK' }}
                </button>
            </form>

        </div>

    </div>

</div>

@endsection
