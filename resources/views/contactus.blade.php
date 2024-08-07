@extends('layouts.user_layout')
@section('content')

<style>
    textarea {
        border: 1px solid rgba(190, 196, 202, 0.3);
        border-radius: 10px;
        height: 120px;
        background-color: transparent !important;
        padding: 20px 20px;
        width: 100%;
        color: var(--White) !important;
        font-family: var(--Inter-Regular);
        font-size: 14px;
        transition: 0.5s;
    }

    textarea:focus {
        border: 1px solid var(--primary-color) !important;
    }

    textarea:focus-visible {
        box-shadow: none !important;
        outline: none !important;
    }
</style>
<section class="login-wrapper contact-us-wrapper">
    <div class="container">
        
        <div class="col-xxl-12 col-xl-12 col-lg-12">
            <div class="login-form">
                <h2 class="text-center">Sign In To ORI Community</h2>
                <p class="text-center">Download the #1 App for Daily Prayer and Bedtime Bible Stories.</p>
                <form action="{{route('add_contact_us')}}" id="contactForm" method="post">
                    @csrf
                    <div class="email-wrp">
                        <input type="text" name="name" placeholder="Name" class="form-control" value="" autocomplete="off" id="name">
                        <label id="name" class="error" for="name"></label>
                    </div>
                    <div class="">
                        <input type="email" name="email" placeholder="Email" class="form-control" value="" autocomplete="off" id="exampleInputEmail1">
                        <label id="exampleInputEmail1-error" class="error" for="exampleInputEmail1"></label>
                    </div>
                    <div class="">
                        <input type="phone" name="phone" placeholder="Phone" class="form-control" value="" autocomplete="off" id="phone-number">
                        <label id="phonenumber" class="error" for="phone-number"></label>
                    </div>
                    <div class="">
                        <textarea name="message" id="" cols="30" rows="10" placeholder="Message"></textarea>
                    </div>
                    <div class="text-center">
                        <input type="submit" name="submit" class="cmn-btn" value="Send">

                    </div>
                    <div class="guest-link text-center">

                    </div>

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
<script type="text/javascript">
$(document).ready(function() {
    $('#contactForm').validate({
        rules: {
            'email': {
                required: true
            },
            'phone': {
                required: true
            },
            'name': {
                required: true
            },
            'message': {
                required: true
            },
            
        },
        messages: {
            'email': {
                required: "Please Enter Email"
            },
            'phone': {
                required: "Please Enter Phone Number"
            },
            'name': {
                required: "Please Enter  Name"
            },
            'message': {
                required: "Please Enter Message"
            },
           
        }
    });
});
</script>
@endpush