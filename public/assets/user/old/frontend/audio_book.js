
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

$(document).on('click','.play_audio',function () {
    audio.pause();
    var buffInterval = null;
	$('.audio-list').removeClass('active');
	var audioFile = $(this).data('audio');
	var title = $(this).data('title');
	$('.current_audio').attr('src', audioFile);
	$('#currentChapter').val(audioFile);
	$('#currentChapterTitle').val(title);
  countDuration();
	// $(".current_audio")[0].load();
	clearInterval(buffInterval);
	$("#player-track").removeClass("active");
	$("#album-art").removeClass("active");
  $("#album-art").removeClass("buffering");
  $("#play-pause-button").find("i").attr("class", "fas fa-play");
	$(this).parents().closest('.audio-list').addClass('active');
	newPlayer();
})

function countDuration() {
	// setTimeout(function () {
		$('.audio_chapter').each(function() {
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
var i = 0;
setInterval(function(){
    if(i<=3){
      countDuration();
    i++;  
    }
    console.log(i);
}, 3000)

$(document).ready(function() {
	// document.getElementById("nowPlaying").setAttribute('autoplay', true);

	var subscription = $('#subscription').val();
	var end_date = $('#end_date').val();
	var current_date = $('#current_date').val();

	if(subscription != 'yes' || end_date <= current_date){
		$("#nowPlaying").on("timeupdate", function() {
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
		$(".audio-list.active").addClass('lastVideo');
		var currentChapter = $(".audio-list.active").data('chapter');
		$(".audio-list.active").parents().next().children('.audio-list').addClass('active');
		$(".audio-list.lastVideo").removeClass('active');
		var totalChapter = $('#totalChapter').val();

		// console.log(currentChapter);
		// console.log(totalChapter);
		if(totalChapter!=currentChapter){
			autoPlayNextVideo();
		}else{
			$('#nowPlaying').get(0).pause();
		}
	};
})

function autoPlayNextVideo() {
	var audioFile = $('.audio-list.active').find('.play_audio').data('audio');
	// console.log(audioFile);
	$('.current_audio').attr('src', audioFile);
	$(".current_audio")[0].load();
	countDuration();

}

function newPlayer() {
  var firstChapter = $('#currentChapter').val();
  var firstChapterName = $('#currentChapterTitle').val();
  var playerTrack = $("#player-track"),
    bgArtwork = $("#bg-artwork"),
    bgArtworkUrl,
    albumName = $("#album-name"),
    trackName = $("#track-name"),
    albumArt = $("#album-art"),
    sArea = $("#s-area"),
    seekBar = $("#seek-bar"),
    trackTime = $("#track-time"),
    insTime = $("#ins-time"),
    sHover = $("#s-hover"),
    playPauseButton = $("#play-pause-button"),
    i = playPauseButton.find("i"),
    tProgress = $("#current-time"),
    tTime = $("#track-length"),
    seekT,
    seekLoc,
    seekBarPos,
    cM,
    ctMinutes,
    ctSeconds,
    curMinutes,
    curSeconds,
    durMinutes,
    durSeconds,
    playProgress,
    bTime,
    nTime = 0,
    buffInterval = null,
    tFlag = false,
    albums = [ 
      firstChapterName,
    ],
    trackNames = [
    ],
    albumArtworks = ["_1", "_2", "_3", "_4", "_5","_6","_7","_8","_9","_10","_11"],
    trackUrl = [
      firstChapter,
    ],
    playPreviousTrackButton = $("#play-previous"),
    playNextTrackButton = $("#play-next"),
    currIndex = -1;

  function playPause() {
    setTimeout(function () {
      if (audio.paused) {
        playerTrack.addClass("active");
        albumArt.addClass("active");
        checkBuffering();
        i.attr("class", "fas fa-pause");
        audio.play();
        // alert()
      } else {
        audio.pause();
        playerTrack.removeClass("active");
        albumArt.removeClass("active");
        clearInterval(buffInterval);
        albumArt.removeClass("buffering");
        i.attr("class", "fas fa-play");
      }
    }, 300);
    // countDuration();
  }

  function showHover(event) {
    seekBarPos = sArea.offset();
    seekT = event.clientX - seekBarPos.left;
    seekLoc = audio.duration * (seekT / sArea.outerWidth());

    sHover.width(seekT);

    cM = seekLoc / 60;

    ctMinutes = Math.floor(cM);
    ctSeconds = Math.floor(seekLoc - ctMinutes * 60);

    if (ctMinutes < 0 || ctSeconds < 0) return;

    if (ctMinutes < 0 || ctSeconds < 0) return;

    if (ctMinutes < 10) ctMinutes = "0" + ctMinutes;
    if (ctSeconds < 10) ctSeconds = "0" + ctSeconds;

    if (isNaN(ctMinutes) || isNaN(ctSeconds)) insTime.text("--:--");
    else insTime.text(ctMinutes + ":" + ctSeconds);

    insTime.css({ left: seekT, "margin-left": "-21px" }).fadeIn(0);
  }

  function hideHover() {
    sHover.width(0);
    insTime.text("00:00").css({ left: "0px", "margin-left": "0px" }).fadeOut(0);
  }

  function playFromClickedPos() {
    audio.currentTime = seekLoc;
    seekBar.width(seekT);
    hideHover();
  }

  function updateCurrTime() {
    nTime = new Date();
    nTime = nTime.getTime();

    if (!tFlag) {
      tFlag = true;
      trackTime.addClass("active");
    }


    curMinutes = Math.floor(audio.currentTime / 60);
    curSeconds = Math.floor(audio.currentTime - curMinutes * 60);

    durMinutes = Math.floor(audio.duration / 60);
    durSeconds = Math.floor(audio.duration - durMinutes * 60);

    var subscription = $('#subscription').val();
    var end_date = $('#end_date').val();
    var current_date = $('#current_date').val();
    
    if(subscription != 'yes' || end_date <= current_date){
      // console.log(curSeconds);
      if(curSeconds >= 30){
          audio.pause();
          i.attr("class", "fa fa-play");
          seekBar.width(0);
          tProgress.text("00:00");
          albumArt.removeClass("buffering").removeClass("active");
          clearInterval(buffInterval);
          var base_url = $('#base_url').val();
          window.location.href = base_url+"subscription_plan";
      }
    }

    playProgress = (audio.currentTime / audio.duration) * 100;

    if (curMinutes < 10) curMinutes = "0" + curMinutes;
    if (curSeconds < 10) curSeconds = "0" + curSeconds;

    if (durMinutes < 10) durMinutes = "0" + durMinutes;
    if (durSeconds < 10) durSeconds = "0" + durSeconds;

    if (isNaN(curMinutes) || isNaN(curSeconds)) tProgress.text("00:00");
    else tProgress.text(curMinutes + ":" + curSeconds);

    if (isNaN(durMinutes) || isNaN(durSeconds)) tTime.text("00:00");
    else tTime.text(durMinutes + ":" + durSeconds);

    if (
      isNaN(curMinutes) ||
      isNaN(curSeconds) ||
      isNaN(durMinutes) ||
      isNaN(durSeconds)
    )
      trackTime.removeClass("active");
    else trackTime.addClass("active");

    seekBar.width(playProgress + "%");

    if (playProgress == 100) {
      i.attr("class", "fa fa-play");
      seekBar.width(0);
      tProgress.text("00:00");
      albumArt.removeClass("buffering").removeClass("active");
      clearInterval(buffInterval);
    }
  }

  function checkBuffering() {
    clearInterval(buffInterval);
    console.log(buffInterval);
    buffInterval = setInterval(function () {
      if (nTime == 0 || bTime - nTime > 1000) albumArt.addClass("buffering");
      else albumArt.removeClass("buffering");

      bTime = new Date();
      bTime = bTime.getTime();
    }, 100);
  }

  function selectTrack(flag) {
    if (flag == 0 || flag == 1) ++currIndex;
    else --currIndex;

    if (currIndex > -1 && currIndex < albumArtworks.length) {
      if (flag == 0) i.attr("class", "fa fa-play");
      else {
        albumArt.removeClass("buffering");
        i.attr("class", "fa fa-pause");
      }

      seekBar.width(0);
      trackTime.removeClass("active");
      tProgress.text("00:00");
      tTime.text("00:00");

      currAlbum = albums[currIndex];
      currTrackName = trackNames[currIndex];
      currArtwork = albumArtworks[currIndex];

      audio.src = trackUrl[currIndex];

      nTime = 0;
      bTime = new Date();
      bTime = bTime.getTime();

      if (flag != 0) {
        audio.play();
        playerTrack.addClass("active");
        albumArt.addClass("active");

        clearInterval(buffInterval);
        checkBuffering();
      }

      albumName.text(currAlbum);
      trackName.text(currTrackName);
      albumArt.find("img.active").removeClass("active");
      $("#" + currArtwork).addClass("active");

      bgArtworkUrl = $("#" + currArtwork).attr("src");

      bgArtwork.css({ "background-image": "url(" + bgArtworkUrl + ")" });
    } else {
      if (flag == 0 || flag == 1) --currIndex;
      else ++currIndex;
    }
  }

  function initPlayer() {
    audio = new Audio();

    selectTrack(0);
    audio.loop = false;
   
    playPauseButton.on("click", playPause);


    sArea.mousemove(function (event) {
      showHover(event);
    });

    sArea.mouseout(hideHover);

    sArea.on("click", playFromClickedPos);

    $(audio).on("timeupdate", updateCurrTime);

    playPreviousTrackButton.on("click", function () {
      selectTrack(-1);
    });
    playNextTrackButton.on("click", function () {
      selectTrack(1);
    });

  }

  initPlayer();
};
newPlayer();
