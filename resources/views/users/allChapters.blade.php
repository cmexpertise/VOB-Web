@extends('layouts.user_layout')
@section('content')
@php
  $language = '';
  if(session('language')){
    $language = session('language');
  }
  if($language == 'ko'){
    $video = $chapters[0]->korean_video;
  }elseif ($language == 'tl') {
    $video = $chapters[0]->filipino_video;
  }elseif ($language == 'pt') {
    $video = $chapters[0]->portuguese_video;
  }elseif ($language == 'es') {
    $video = $chapters[0]->spanish_video;
  }else {
    $video = $chapters[0]->video;
  }
@endphp

<!-- ============ audio-wrapper ============== -->
<section class="video-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xxl-12 col-xl-12 col-lg-12">
                 <div class="title text-center">
                    <h2 class="wow pulse" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">{{$chapters[0]->book->name}}</h2>
                    <p class="mx-auto wow fadeIn" data-wow-duration="3s" data-wow-delay="0" data-wow-offset="0">{{ $chapters[0]->book->description}}</p>
                </div>
                <div class="video-wrap">
                    <video autoplay controls class="current_video" id="nowPlaying" controlsList="nodownload">
                        <source src="{{$video}}"/>
                    </video>
                    <h3 id="currentChapterName">{{$chapters[0]->name}}</h3>
                </div>
            </div>
        </div>
        <div class="row">
            @php
                $i = 0;
                $j = 1; 
            @endphp
            @foreach ($chapters as $chapter)
			@php
              if($language == 'ko'){
                $chapter_video = $chapter->korean_video;
              }elseif ($language == 'tl') {
                $chapter_video = $chapter->filipino_video;
              }elseif ($language == 'pt') {
                $chapter_video = $chapter->portuguese_video;
              }elseif ($language == 'es') {
                $chapter_video = $chapter->spanish_video;
              }else {
                $chapter_video = $chapter->video;
              }
            @endphp 
            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 mb-5 wow fadeIn" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">
                <div class="video-list {{($i==0)?'active':''}}" data-chapter="{{$j}}">
                    <div class="list-video play_video" data-video="{{$chapter_video}}">
                        <img src="{{asset("storage/".$chapter->video_image)}}">
                    </div>
                    <div class="audio-detail ms-3">
                        <h5 class="mb-2 chapter_name">{{$chapter->name}}</h5>
                        <video controls style="display: none;" class="video_chapter">
                            <source src="{{$chapter_video}}"/>
                        </video>
                        <h6 class="mb-lg-3 mb-sm-2 duration"></h6>
                        <p class="chapter_description">@php
                            $string = strip_tags($chapter->description);
                            if (strlen($string) > 100) {
                                $stringCut = substr($string, 0, 100);
                                $endPoint = strrpos($stringCut, ' ');
                                $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                $string .= '<a class="read_more" style="cursor: pointer;" data-id="'.$chapter->id.'">[...]</a>';
                            }
                            echo $string;
                            $i++;
                            $j++;
                            @endphp
						</p>
                        <p id="{{$chapter->id}}" style="display: none; cursor: pointer;">{{$chapter->description}}<a class="read_less">Read Less</a></p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
@push('script')
<script>
    $(document).on('click','.read_more',function () {
	var id = $(this).data('id');
	var id = '#'+id;
	$(id).show();
	$(this).hide();
	$(this).parent().hide();
})
$(document).on('click','.read_less',function () {
	$(this).parent().parent().find('.chapter_description').show();
	$(this).parent().parent().find('.read_more').show();
	$(this).parent().hide();
	
})
	
$(document).on('click','.play_video',function () {
	$('.video-list').removeClass('active');
	var videoFile = $(this).data('video');
    console.log(videoFile);
	$('.current_video').attr('src', videoFile);
	$(".current_video")[0].load();
	$(this).parents().closest('.video-list').addClass('active');
	var currentChapterName = $(this).next().find('.chapter_name').text();
	$('#currentChapterName').text(currentChapterName);
	// console.log($(this).next().find('.chapter_name').text());
})

$(document).on('click','.load_more_books',function () {
	var that = $(this);
	var order = $(this).data('limit');
	console.log(order);
	$.ajax({
        type : 'POST',
        url : 'getMoreBooks',
        data : { order : order,},
        // dataType:'json',
        success:function(response){
        	// console.log(response);
        	$('.load_more').html(response);
        	that.hide();
        }
    });
})

function countDuration() {
	// setTimeout(function () {
		$('.video_chapter').each(function() {
	   		var duration = $(this).get(0).duration;
	   		const minutes = Math.floor(duration / 60);
	   		const seconds = duration - (minutes * 60);
	   		if(seconds<10){
	   			var finalTime = minutes + ':' + '0'+parseInt(seconds);
	   		}else{
	   			var finalTime = minutes + ':' + parseInt(seconds);
	   		}

	   		if(isNaN(minutes) || isNaN(parseInt(seconds)) ){
   				finalTime= '00:00';
   			}
	   		$(this).next().text(finalTime);
		});     
	// }, 2500);
}

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

function autoPlayNextVideo() {
	var videoFile = $('.video-list.active').find('.play_video').data('video');
	// console.log($('.video-list.active').children('.audio-detail').find('.chapter_name').text());
	var currentChapterName = $('.video-list.active').children('.audio-detail').find('.chapter_name').text();
	$('#currentChapterName').text(currentChapterName);
	$('.current_video').attr('src', videoFile);
	$(".current_video")[0].load();
	countDuration();
}

</script>
@endpush