@extends('layouts.front')

@section('content')

<style>
    .login-page {
    min-height: 80vh;
}

.login-card {
    width: 100%;
    max-width: 400px;
    padding: 30px;
    border-radius: 10px;
    border: 1px solid #eee;
    background: #fff;
}

.login-card input {
    height: 45px;
    border-radius: 6px;
}

.login-card .btn {
    height: 45px;
}
</style>

<section class="login-page d-flex align-items-center justify-content-center">

    <div class="login-card">

        <h4 class="text-center mb-4">Login</h4>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- EMAIL -->
            <div class="mb-3">
                <input 
                    type="email" 
                    name="email" 
                    class="form-control" 
                    placeholder="Email Address"
                    required
                >
            </div>

            <!-- PASSWORD -->
            <div class="mb-3">
                <input 
                    type="password" 
                    name="password" 
                    class="form-control" 
                    placeholder="Password"
                    required
                >
            </div>

            <!-- REMEMBER -->
            <div class="d-flex justify-content-between mb-3">
                <label>
                    <input type="checkbox" name="remember"> Remember me
                </label>

                <a href="#">Forgot Password?</a>
            </div>

            <!-- BUTTON -->
            <button type="submit" class="btn btn-orange w-100">
                Login
            </button>

        </form>

        <!-- REGISTER LINK -->
        <p class="text-center mt-3">
            Don't have an account?
            <a href="/register">Sign Up</a>
        </p>

    </div>

</section>
    
@endsection