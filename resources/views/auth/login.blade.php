
@extends('layouts.app')

@section('title', 'Login | Luxury Fashion Hub')

@push('styles')
<style>

* {
    box-sizing: border-box;
}

.login-page {
    min-height: 75vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 50px 15px;
    background: #f8f8f6;
}

.login-container {
    width: 100%;
    max-width: 430px;
    background: #171717;
    padding: 40px;
    border: 1px solid #b9975b;
    border-radius: 10px;
    color: white;
    box-shadow: 0 10px 35px rgba(0,0,0,0.12);
}

.login-container h1 {
    text-align: center;
    color: #d4af72;
    margin-bottom: 10px;
}

.login-subtitle {
    text-align: center;
    color: #aaa;
    margin-bottom: 25px;
}

.login-container input {
    width: 100%;
    padding: 14px;
    margin: 10px 0;
    background: #292929;
    border: 1px solid #444;
    color: white;
    border-radius: 6px;
    outline: none;
}

.login-container input:focus {
    border-color: #c5a16a;
}

.password-wrapper {
    position: relative;
}

.password-wrapper input {
    padding-right: 55px;
}

.toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    color: #d4af72;
    cursor: pointer;
    padding: 8px;
    font-size: 19px;
    margin: 0;
    width: auto;
}

.login-button {
    width: 100%;
    padding: 14px;
    margin-top: 15px;
    background: #c5a16a;
    color: #111;
    border: none;
    font-weight: bold;
    cursor: pointer;
    border-radius: 6px;
    transition: 0.3s;
}

.login-button:hover {
    background: #e0c18c;
}

.login-error {
    color: #ff7777;
    font-size: 14px;
    margin-bottom: 10px;
}

.login-bottom {
    text-align: center;
    margin-top: 25px;
    color: #aaa;
    line-height: 2;
}

.login-bottom a {
    color: #d4af72;
    text-decoration: none;
}

.login-bottom a:hover {
    text-decoration: underline;
}

@media(max-width: 480px) {
    .login-container {
        padding: 28px 22px;
    }
}

</style>
@endpush

@section('content')

<div class="login-page">

    <div class="login-container">

        <h1>Welcome Back</h1>

        <p class="login-subtitle">
            Login to Luxury Fashion Hub
        </p>

        @if ($errors->any())
            <div class="login-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <input
                type="email"
                name="email"
                placeholder="Email Address"
                value="{{ old('email') }}"
                required
            >

            <div class="password-wrapper">

                <input
                    type="password"
                    name="password"
                    id="loginPassword"
                    placeholder="Password"
                    required
                >

                <button
                    type="button"
                    class="toggle-password"
                    id="togglePassword"
                    onclick="togglePasswordVisibility()"
                    aria-label="Show password"
                >
                    <span id="eyeIcon">👁</span>
                </button>

            </div>

            <button type="submit" class="login-button">
                LOGIN
            </button>

        </form>

        <div class="login-bottom">

            Don't have an account?
            <a href="{{ route('register') }}">Register</a>

            <br>

            <a href="{{ route('home') }}">Back to Home</a>

        </div>

    </div>

</div>

<script>
function togglePasswordVisibility() {

    const password = document.getElementById('loginPassword');
    const eyeIcon = document.getElementById('eyeIcon');
    const button = document.getElementById('togglePassword');

    if (password.type === 'password') {

        password.type = 'text';
        eyeIcon.textContent = '🙈';
        button.setAttribute('aria-label', 'Hide password');

    } else {

        password.type = 'password';
        eyeIcon.textContent = '👁';
        button.setAttribute('aria-label', 'Show password');

    }
}
</script>

<script>
function togglePasswordVisibility() {

    const password = document.getElementById('loginPassword');
    const eyeIcon = document.getElementById('eyeIcon');
    const button = document.getElementById('togglePassword');

    if (password.type === 'password') {

        password.type = 'text';
        eyeIcon.textContent = '🙈';
        button.setAttribute('aria-label', 'Hide password');

    } else {

        password.type = 'password';
        eyeIcon.textContent = '👁';
        button.setAttribute('aria-label', 'Show password');

    }
}
</script>

@endsection
