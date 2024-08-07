@extends('layouts.user_layout')
@section('content')
<!-- =========== bible-wraper ============ -->
<section class="books-wrapper bible-wraper">
    <div class="container">
        <div class="row">
            <div class="col-xxl-12 col-xl-12 col-lg-12">
                <div class="title text-center">
                    <h1 class="wow fadeIn" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Old Testament</h1>
                    <!-- <p class="mx-auto wow fadeIn" data-wow-duration="3s" data-wow-delay="0" data-wow-offset="0">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Tincidunt arcu non sodales neque sodales ut.</p> -->
               </div>
            </div>

            @foreach ($books as $key => $book)
                @if ($book->type == '2')
                    <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-4 col-sm-6 mb-4 wow fadeInDown" data-wow-duration="1s" data-wow-delay="0" data-wow-offset="0">
                        <a href="{{route('allChapters',['book' => encrypt($book->id)])}}">
                            <div class="book-card">
                                <div class="book-img">
                                    <img src="{{asset("storage/".$book->video_image)}}" class="" alt="book-img">
                                </div>
                                <h3>{{$book->name}}</h3>
                            </div>
                        </a>
                    </div>
                @endif
            @endforeach
            <div class="col-xxl-12 col-xl-12 col-lg-12 mt-4">
                <div class="title text-center">
                    <h1 class="wow fadeIn" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">New Testament</h1>
               </div>
            </div>
            @foreach ($books as $key => $book)
                @if ($book->type == '1')
                    <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-4 col-sm-6 mb-4 wow fadeInDown" data-wow-duration="1s" data-wow-delay="0" data-wow-offset="0">
                        <a href="{{route('allChapters',['book' => encrypt($book->id)])}}">
                            <div class="book-card">
                                <div class="book-img">
                                    <img src="{{asset("storage/".$book->video_image)}}" class="" alt="book-img">
                                </div>
                                <h3>{{$book->name}}</h3>
                            </div>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endsection
@push('script')
@endpush