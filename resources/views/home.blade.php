
@extends('layouts.app')

@section('title', 'Luxury Fashion Hub | Mens Fashion')

@push('styles')
<style>
    .hero {
        min-height: 430px;
        background: linear-gradient(135deg, #111, #39332a);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 40px 20px;
    }

    .hero h1 {
        font-size: 48px;
        margin-bottom: 18px;
        color: #d4b477;
    }

    .hero p {
        font-size: 17px;
        margin-bottom: 28px;
        color: #eee;
    }

    .btn {
        background: #c9a96e;
        color: #171717;
        padding: 15px 30px;
        text-decoration: none;
        display: inline-block;
        font-weight: bold;
    }

    .collection {
        padding: 65px 8%;
        text-align: center;
    }

    .collection h2 {
        font-size: 30px;
        margin-bottom: 35px;
    }

    .products {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }

    .product {
        text-align: left;
        background: white;
        padding-bottom: 20px;
    }

    .product img {
        width: 100%;
        height: 330px;
        object-fit: cover;
        background: #eee;
    }

    .product h3 {
        margin: 15px 12px 0;
        font-size: 18px;
    }

    .product p {
        margin: 8px 12px;
        color: #9b7945;
        font-weight: bold;
    }

    .product a {
        color: inherit;
        text-decoration: none;
    }

    .product .view {
        display: inline-block;
        margin: 8px 12px;
        background: #111;
        color: white;
        padding: 11px 18px;
    }

    .all-products {
        margin-top: 40px;
    }

    @media(max-width: 700px) {
        .hero h1 {
            font-size: 32px;
        }

        .products {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<section class="hero">
    <h1>Upgrade Your Style</h1>

    <p>Discover premium men's fashion at Luxury Fashion Hub.</p>

    <a href="{{ route('products.index') }}" class="btn">
        SHOP NOW
    </a>
</section>

<section class="collection" id="collection">

    <h2>Our Latest Collection</h2>

    <div class="products">

        @forelse($products as $product)

            <div class="product">

                <a href="{{ route('products.show', $product->id) }}">

                    <img
                        src="{{ $product->image
                            ? (str_starts_with($product->image, 'http')
                                ? $product->image
                                : asset('storage/' . $product->image))
                            : 'https://placehold.co/600x700?text=No+Image' }}"
                        alt="{{ $product->name }}"
                    >

                    <h3>{{ $product->name }}</h3>

                </a>

                <p>₹{{ number_format($product->price, 2) }}</p>

                <a class="view"
                   href="{{ route('products.show', $product->id) }}">
                    VIEW PRODUCT
                </a>

            </div>

        @empty

            <p>No products available yet.</p>

        @endforelse

    </div>

    <div class="all-products">
        <a href="{{ route('products.index') }}" class="btn">
            VIEW ALL PRODUCTS
        </a>
    </div>

</section>

@endsection
