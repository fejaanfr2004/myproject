
@extends('layouts.app')

@section('title', 'Register | Luxury Fashion Hub')

@push('styles')
<style>
.register-page {
    min-height: 75vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 50px 15px;
    background: #f8f8f6;
}

.register-container {
    width: 100%;
    max-width: 430px;
    background: #171717;
    padding: 40px;
    border: 1px solid #b9975b;
    border-radius: 10px;
    color: white;
    box-shadow: 0 10px 35px rgba(0,0,0,0.12);
}

.register-container h1 {
    text-align: center;
    color: #d4af72;
    margin-bottom: 10px;
}

.register-subtitle {
    text-align: center;
    color: #aaa;
    margin-bottom: 25px;
}

.register-container input {
    width: 100%;
    padding: 14px;
    margin: 10px 0;
    background: #292929;
    border: 1px solid #444;
    color: white;
    border-radius: 6px;
    outline: none;
}

.register-container input:focus {
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

.register-button {
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

.register-button:hover {
    background: #e0c18c;
}

.register-error {
    color: #ff7777;
    font-size: 14px;
    margin-bottom: 10px;
}

.register-bottom {
    text-align: center;
    margin-top: 25px;
    color: #aaa;
    line-height: 2;
}

.register-bottom a {
    color: #d4af72;
    text-decoration: none;
}

.register-bottom a:hover {
    text-decoration: underline;
}

@media(max-width: 480px) {
    .register-container {
        padding: 28px 22px;
    }
}
</style>
@endpush

@section('content')

<div class="register-page">

    <div class="register-container">

        <h1>Create Account</h1>

        <p class="register-subtitle">
            Join Luxury Fashion Hub
        </p>

        @if ($errors->any())
            <div class="register-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <input
                type="text"
                name="name"
                placeholder="Full Name"
                value="{{ old('name') }}"
                required
            >

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
                    id="registerPassword"
                    placeholder="Password"
                    required
                >

                <button
                    type="button"
                    class="toggle-password"
                    onclick="toggleRegisterPassword('registerPassword', 'registerEye1', this)"
                    aria-label="Show password"
                >
                    <span id="registerEye1">👁</span>
                </button>

            </div>

            <div class="password-wrapper">

                <input
                    type="password"
                    name="password_confirmation"
                    id="confirmPassword"
                    placeholder="Confirm Password"
                    required
                >

                <button
                    type="button"
                    class="toggle-password"
                    onclick="toggleRegisterPassword('confirmPassword', 'registerEye2', this)"
                    aria-label="Show confirm password"
                >
                    <span id="registerEye2">👁</span>
                </button>

            </div>

            <button type="submit" class="register-button">
                CREATE ACCOUNT
            </button>

        </form>

        <div class="register-bottom">

            Already have an account?
            <a href="{{ route('login') }}">Login</a>

            <br>

            <a href="{{ route('home') }}">Back to Home</a>

        </div>

    </div>

</div>

<script>
function toggleRegisterPassword(inputId, iconId, button) {

    const password = document.getElementById(inputId);
    const eyeIcon = document.getElementById(iconId);

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
