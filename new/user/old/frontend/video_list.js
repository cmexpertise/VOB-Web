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
	    		window.location.href = base_url+"subscription_plan";
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
