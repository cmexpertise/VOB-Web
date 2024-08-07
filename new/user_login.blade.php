@extends('layouts.user_layout')
@section('content')

<section class="login-wrapper">
    <div class="container">
        <div class="col-xxl-12 col-xl-12 col-lg-12">
            <div class="login-form">
                <h2 class="text-center wow pulse" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Sign In To ORI Community</h2>
                <p class="text-center wow fadeIn" data-wow-duration="3s" data-wow-delay="0" data-wow-offset="0">Download the #1 App for Daily Prayer and Bedtime Bible Stories.</p>
                <form action="{{route('user_login')}}" method="POST">
                    @csrf
                    <div class="email-wrp">
                        <input type="email" placeholder="Enter your email address" name="email" class="form-control">
                    </div>
                    <div class="password-wrp">
                        <input id="password-field" type="password" name="password" class="form-control" placeholder="Enter your password">
                        <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                    </div>
                    <div class="mt-4 forgot-link">
                        <input type="checkbox" checked="checked" name="remember" class="form-check-input"> <span class="ps-2">Remember me</span>
                        <a href="{{route('password.email')}}" class="ms-auto">Forgot password?</a>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="cmn-btn">Sign In</button>
                    </div>
                    {{-- <div class="guest-link text-center">
                        <a href="#">Continue as Guest</a>
                    </div>
                    <div class="acount-link d-flex justify-content-center align-items-center">
                        <p>Don't have an account? </p>
                        <a href="sign-up.php" class="ms-1">Sign Up</a>
                    </div> --}}
                </form>
            </div>
            <div class="login-bg">
                <img src="{{ asset('assets/user/') }}/img/login/login-img-2.png" alt="login-img">
            </div>
        </div>
    </div>
</section>

@endsection
@push('script')
@endpush