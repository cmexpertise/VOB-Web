@extends('layouts.user_layout')
@section('content')

<section class="plan-wrapper subscribe-plan download-app">
    <div class="container">
         <div class="title text-center">
             <h2 class="wow pulse" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0" style="visibility: visible; animation-duration: 2s; animation-name: pulse;">Download App</h2>
             <p class="mx-auto wow fadeIn" data-wow-duration="3s" data-wow-delay="0" data-wow-offset="0" style="visibility: visible; animation-duration: 3s; animation-name: fadeIn;">Download app for Android and iOS today</p>
         </div>
         <div class="plan-card">
             <div class="row align-items-center">
                 <div class="col-lg-6 mb-lg-0 mb-4">
                     <h2 class="sub-heading">Download now and start your prayer</h2>
                     <p>Download app for Android and iOS today</p>
                     <div class="app-store d-flex justify-content-center gap-2">
                         <a href="https://play.google.com/store/apps/details?id=com.obs.oribible&hl=en_IN" class="google-app">
                             <img src="{{ asset('assets/user/') }}/img/btn_playstore.svg" alt="google-app">
                         </a>
                         <a href="https://apps.apple.com/in/app/vision-of-the-bible/id1570619920" class="mobile-app">
                             <img src="{{ asset('assets/user/') }}/img/btn_appstore.svg" alt="mobile-app">
                         </a>
                     </div>
                 </div>
                 <div class="col-lg-5">
                     <div class="plan-img">
                         <img src="{{ asset('assets/user/') }}/img/download-img.png" alt="plan-img">
                     </div>
                 </div>
             </div>
         </div>
    </div>
 </section>

 @endsection