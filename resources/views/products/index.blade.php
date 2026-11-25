
@extends('layouts.app')

@section('title', 'Shop Products | Luxury Fashion Hub')

@push('styles')
<style>
    .shop-hero {
        min-height: 350px;
        background: linear-gradient(90deg, #111, #383838);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 40px 20px;
    }

    .shop-hero small {
        color: #c9a96e;
        letter-spacing: 5px;
        margin-bottom: 20px;
    }

    .shop-hero h1 {
        font-size: clamp(35px, 6vw, 65px);
        margin-bottom: 15px;
    }

    .shop-hero p {
        color: #ddd;
        margin-bottom: 30px;
    }

    .shop-container {
        padding: 65px 7%;
    }

    .section-heading {
        text-align: center;
        margin-bottom: 35px;
    }

    .section-heading small {
        color: #a48650;
        letter-spacing: 3px;
    }

    .section-heading h2 {
        font-size: 34px;
        margin-top: 12px;
    }

    .search {
        display: flex;
        max-width: 550px;
        margin: 0 auto 45px;
    }

    .search input {
        flex: 1;
        min-width: 0;
        padding: 15px;
        border: 1px solid #ddd;
    }

    .search button {
        background: #111;
        color: white;
        border: none;
        padding: 0 25px;
        cursor: pointer;
    }

    .shop-products {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 28px;
    }

    .shop-card {
        background: white;
        padding: 12px;
        transition: .3s;
    }

    .shop-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px #00000015;
    }

    .shop-card img {
        width: 100%;
        height: 350px;
        object-fit: cover;
        display: block;
    }

    .shop-card h3 {
        font-size: 18px;
        margin: 17px 5px 8px;
    }

    .shop-price {
        margin: 0 5px 18px;
        font-weight: bold;
        color: #9b7945;
        font-size: 19px;
    }

    .details-btn,
    .add-cart {
        display: block;
        width: 100%;
        padding: 14px;
        text-align: center;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        margin-bottom: 9px;
    }

    .details-btn {
        background: white;
        color: #111;
        border: 1px solid #111;
    }

    .details-btn:hover {
        background: #eee;
    }

    .add-cart {
        background: #111;
        color: white;
        border: 1px solid #111;
    }

    .add-cart:hover {
        background: #c9a96e;
        color: #111;
    }

    .empty {
        text-align: center;
        padding: 40px;
        grid-column: 1 / -1;
    }

    .shop-card a {
        text-decoration: none;
        color: inherit;
    }

    @media(max-width: 800px) {
        .shop-products {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 550px) {
        .shop-products {
            grid-template-columns: 1fr;
        }

        .shop-card img {
            height: 400px;
        }

        .shop-container {
            padding: 40px 5%;
        }
    }
</style>
@endpush

@section('content')

<section class="shop-hero">
    <small>THE NEW MEN'S COLLECTION</small>

    <h1>DRESS LIKE YOU MEAN IT.</h1>

    <p>Discover timeless style. Wear your confidence.</p>

    <a href="#collection" class="details-btn"
       style="width:auto;background:#c9a96e;color:#111;border:0;padding:15px 30px;">
        EXPLORE COLLECTION
    </a>
</section>

<section class="shop-container" id="collection">

    <div class="section-heading">
        <small>STYLE THAT SPEAKS</small>
        <h2>SHOP OUR COLLECTION</h2>
    </div>

    <form class="search"
          action="{{ route('products.index') }}"
          method="GET">

        <input
            type="text"
            name="search"
            placeholder="Search shirts, jeans, jackets..."
            value="{{ request('search') }}"
        >

        <button type="submit">SEARCH</button>

    </form>

    <div class="shop-products">

        @forelse($products as $product)

            <div class="shop-card">

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

                <p class="shop-price">
                    ₹{{ number_format($product->price, 2) }}
                </p>

                <a
                    href="{{ route('products.show', $product->id) }}"
                    class="details-btn"
                >
                    VIEW DETAILS
                </a>

                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf

                    <input
                        type="hidden"
                        name="product_id"
                        value="{{ $product->id }}"
                    >

                    <button type="submit" class="add-cart">
                        ADD TO CART
                    </button>
                </form>

            </div>

        @empty

            <div class="empty">
                <h2>No products found!</h2>
                <p>Try searching for another product.</p>

                <br>

                <a href="{{ route('products.index') }}">
                    View all products
                </a>
            </div>

        @endforelse

    </div>

</section>

@endsection
