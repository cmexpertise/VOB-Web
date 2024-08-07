@extends('layouts.user_layout')
@section('content')

<!-- =============== travel-wrapper ================ -->
<section class="travel-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xxl-12 col-xl-12 col-lg-12">
                <div class="title text-center">
                    <h1 class="wow fadeIn" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Travel Samaritan</h1>
                    <p class="mx-auto wow fadeIn" data-wow-duration="3s" data-wow-delay="0" data-wow-offset="0">We offer guided tours of sacred sites and regions mentioned in the Bible. This allows you to virtually experience places like Jerusalem and Capernaum without physically traveling there.</p>
                </div>
            </div>
            @foreach ($travelsamaritans as $travel)
                <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6 col-sm-6 mb-4 wow fadeIn" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">
                    <a href="{{route('travelList',['id' => encrypt($travel->id)])}}">
                        <div class="book-card">
                            <div class="book-img">
                                <img src="{{asset("storage/".$travel->image)}}" class="" alt="book-img">
                            </div>
                            <h3>{{$travel->name}}</h3>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        {{-- <div class="text-center load-more">
            <a href="#" class="cmn-btn" type="button">Load More</a>
        </div> --}}
    </div>
</section>


@endsection
@push('script')
@endpush
