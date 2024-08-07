@extends('layouts.user_layout')
@section('content')

<!-- ============ audio-wrapper ============== -->
<section class="audio-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xxl-12 col-xl-12 col-lg-12">
                <div class="audio-img">
                    <img src="{{asset("storage/".$night_story->image)}}" alt="audio-img">
                </div>
                <div class="audio-conrol">
                    <audio controls autoplay class="current_audio" id="nowPlaying">
                        <source src="{{$night_story->audio}}">
                    </audio>
                </div>
                <div class="eazy-audio-wrapper">
                </div>
                <div class="title text-center">
                    <h2 class="wow pulse" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">{{$night_story->name}}</h2>
                    <p class="mx-auto wow fadeIn" data-wow-duration="3s" data-wow-delay="0" data-wow-offset="0">{{$night_story->description}}</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@push('script')
<script>
$(document).ready(function() {
	document.getElementById("nowPlaying").setAttribute('autoplay', true);
	var subscription = $('#subscription').val();
	var end_date = $('#end_date').val();
	var current_date = $('#current_date').val();

	if(subscription != 'yes' || end_date <= current_date){
		$("#nowPlaying").on("timeupdate", function() {
			// countDuration();
	    	var videoElem = this;
	    	if(parseInt(videoElem.currentTime) >= 30){
	    		$('#nowPlaying').get(0).currentTime = 0;
	    		var base_url = $('#base_url').val();
	    		window.location.href = "{{route('download_app')}}";
	    	}
	  	});
	}

	var i = 0;
	$("#nowPlaying").on("timeupdate", function() {
		if(i==0){
			i++;
			countDuration();
		}
	});	

	function str_pad_left(string, pad, length) {
 		return (new Array(length + 1).join(pad) + string).slice(-length);
	}

	let aud = document.getElementById("nowPlaying");  	
	aud.onended = function() {
		// console.log($(".audio-list.active").parents().html());
		$(".video-list.active").addClass('lastVideo');
		var currentChapter = $(".video-list.active").data('chapter');
		$(".video-list.active").parents().next().children('.video-list').addClass('active');
		$(".video-list.lastVideo").removeClass('active');
		var totalChapter = $('#totalChapter').val();
		if(totalChapter!=currentChapter){
			autoPlayNextVideo();
		}else{
			$('#nowPlaying').get(0).pause();
		}
	};
})
</script>
@endpush