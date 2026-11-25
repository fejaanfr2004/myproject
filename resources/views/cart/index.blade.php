
@extends('layouts.app')

@section('title', 'Shopping Bag | Luxury Fashion Hub')

@push('styles')
<style>
.cart-container {
    max-width: 1000px;
    margin: 50px auto;
    padding: 20px;
}

.cart-heading {
    text-align: center;
    margin-bottom: 35px;
    font-size: 30px;
    letter-spacing: 2px;
}

.cart-item {
    display: flex;
    align-items: center;
    gap: 22px;
    background: white;
    padding: 18px;
    margin-bottom: 15px;
    border: 1px solid #eee;
}

.cart-item img {
    width: 110px;
    height: 120px;
    object-fit: cover;
    background: #eee;
}

.cart-details {
    flex: 1;
}

.cart-details h3 {
    margin-top: 0;
}

.cart-price {
    color: #9b7945;
    font-weight: bold;
}

.quantity-form {
    display: flex;
    gap: 8px;
    margin-top: 12px;
    flex-wrap: wrap;
}

.quantity-form input {
    width: 75px;
    padding: 9px;
    border: 1px solid #ccc;
}

.cart-button {
    padding: 10px 15px;
    border: none;
    background: #111;
    color: white;
    cursor: pointer;
}

.cart-button:hover {
    background: #c9a96e;
    color: #111;
}

.remove-button {
    background: #a83232;
    color: white;
}

.remove-button:hover {
    background: #7d2020;
    color: white;
}

.item-total {
    text-align: right;
    min-width: 120px;
}

.cart-total {
    background: white;
    padding: 25px;
    text-align: right;
    margin-top: 25px;
    border: 1px solid #eee;
}

.cart-total h2 {
    color: #9b7945;
}

.continue-shopping {
    display: inline-block;
    background: #c9a96e;
    color: #111;
    padding: 14px 25px;
    text-decoration: none;
    font-weight: bold;
    margin-top: 15px;
}

.continue-shopping:hover {
    background: #111;
    color: white;
}

.empty-cart {
    text-align: center;
    padding: 60px 15px;
    background: white;
}

.empty-cart a {
    color: #9b7945;
    font-weight: bold;
}

@media(max-width: 600px) {
    .cart-item {
        flex-wrap: wrap;
    }

    .cart-item img {
        width: 90px;
        height: 100px;
    }

    .item-total {
        width: 100%;
        text-align: left;
    }

    .cart-heading {
        font-size: 23px;
    }
}
</style>
@endpush

@section('content')

<div class="cart-container">

    <h1 class="cart-heading">
        YOUR SHOPPING BAG ({{ $cartCount }})
    </h1>

    @if(count($cart) > 0)

        @foreach($cart as $item)

            <div class="cart-item">

                <img
                    src="{{ !empty($item['image'])
                        ? (str_starts_with($item['image'], 'http')
                            ? $item['image']
                            : asset('storage/' . $item['image']))
                        : 'https://placehold.co/300x300?text=No+Image' }}"
                    alt="{{ $item['name'] }}"
                >

                <div class="cart-details">

                    <h3>{{ $item['name'] }}</h3>

                    <p class="cart-price">
                        ₹{{ number_format($item['price']) }}
                    </p>

                    <form
                        action="{{ route('cart.update') }}"
                        method="POST"
                        class="quantity-form"
                    >
                        @csrf

                        <input
                            type="hidden"
                            name="product_id"
                            value="{{ $item['id'] }}"
                        >

                        <input
                            type="number"
                            name="quantity"
                            value="{{ $item['quantity'] }}"
                            min="1"
                            max="99"
                            required
                        >

                        <button class="cart-button" type="submit">
                            Update
                        </button>
                    </form>

                </div>

                <div class="item-total">

                    <strong>
                        ₹{{ number_format($item['price'] * $item['quantity']) }}
                    </strong>

                    <form
                        action="{{ route('cart.remove') }}"
                        method="POST"
                        style="margin-top:12px"
                    >
                        @csrf

                        <input
                            type="hidden"
                            name="product_id"
                            value="{{ $item['id'] }}"
                        >

                        <button
                            class="cart-button remove-button"
                            type="submit"
                        >
                            Remove
                        </button>
                    </form>

                </div>

            </div>

        @endforeach

        <div class="cart-total">

            <h2>Total: ₹{{ number_format($total) }}</h2>

            <p>Shipping and taxes calculated at checkout.</p>

<a href="{{ route('checkout.index') }}"
   class="continue-shopping">
    PROCEED TO CHECKOUT →
</a>

<br>

<a href="{{ route('home') }}"
   style="display:inline-block;margin-top:15px;color:#9b7945;">
    CONTINUE SHOPPING
</a>

        </div>

    @else

        <div class="empty-cart">

            <h2>Your bag is empty</h2>

            <p>Looks like you haven't added anything yet.</p>

            <a href="{{ route('home') }}">
                Start Shopping
            </a>

        </div>

    @endif

</div>

@endsection
