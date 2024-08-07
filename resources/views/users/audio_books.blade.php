@extends('layouts.user_layout')
@section('content')
<!-- audio-books-wrapper -->
<section class="audio-book">
    <div class="container">
        <div class="row">
            <div class="col-xxl-12 col-xl-12 col-lg-12">
                <div class="title text-center">
                    <h1 class="wow fadeIn" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Audio Books</h1>
                    <p class="mx-auto wow fadeIn" data-wow-duration="3s" data-wow-delay="0" data-wow-offset="0">Listen to the Bible's wisdom with our captivating audiobooks. Indulge in the beauty of storytelling through our captivating audiobooks. Experience the scriptures come alive, enriching your spiritual journey.</p>
                </div>
            </div>
            @foreach ($books as $book)
                <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-4 col-sm-6 mb-4 wow fadeInDown" data-wow-duration="1s" data-wow-delay="0" data-wow-offset="0">
                    <a href="{{route('allAudios',['book' => encrypt($book->id)])}}">
                        <div class="book-card">
                            <div class="book-img">
                                <img src="{{asset("storage/".$book->video_image)}}" class="" alt="book-img">
                            </div>
                            <h3>{{$book->name}}</h3>
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
