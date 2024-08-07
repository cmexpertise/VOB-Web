// $(document).on("click", ".videos", function () {

//   var videos = $("#video-wrap").attr("src");
//   var newVideo = $(this).attr("data-video");
// $("#video-wrap").attr("src", "./assets/img/videos/" + newVideo + ".mp4");

// });

// ============= magnified-img-pop-up ===========
$(document).ready(function(){
  $('.image-popup-vertical-fit').magnificPopup({
    type: 'image',  
    mainClass: 'mfp-with-zoom', 
    gallery:{
        enabled:true
      },
  
    zoom: {
      enabled: true, 
  
      duration: 300, // duration of the effect, in milliseconds
      easing: 'ease-in-out', // CSS transition easing function
  
      opener: function(openerElement) {
  
        return openerElement.is('img') ? openerElement : openerElement.find('img');
    }
  }
  
  });
  
});

// ========= header-sticky-animation =============
const header = document.querySelector(".page-header");
const toggleClass = "is-sticky";
window.addEventListener("scroll", () => {
  const currentScroll = window.pageYOffset;
  if (currentScroll > 150) {
    header.classList.add(toggleClass);
  } else {
    header.classList.remove(toggleClass);
  }
});

// header-toggle-menu
const navigation = document.getElementById("navbar");
const menu = document.getElementById("menu");
menu.addEventListener("click", () => {
  navigation.style.setProperty("--childenNumber", navigation.children.length);
  navigation.classList.toggle("active");
  menu.classList.toggle("active");
});


// scroll-link footer
function scrollDemo() {
  window.scrollTo({
    top: 0,
    behavior: "smooth"
  });
}

// show-password
$(".toggle-password").click(function() {
  $(this).toggleClass("fa-eye-slash fa-eye");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});

//   -----choose-image---
var loadFile = function(event) {
	var output = document.getElementById('output');
	output.src = URL.createObjectURL(event.target.files[0]);		
};

$(document).ready(function(){ 
	$(".choose-btn").click(function (){
	$("input[type='file']").trigger('click');  
	}); 
});