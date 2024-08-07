@php
    use Illuminate\Support\Facades\Session;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ORI</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- font-link -->
    <link rel="stylesheet" href="{{ asset('assets/user/') }}/css/fonts.css">

    <!-- stle-css -->
    <link rel="stylesheet" href="{{ asset('assets/user/') }}/css/style.css">

    <!-- bootstrap-link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- font-awesome-cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

    <!-- owl-carousel -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <!-- animation -->
    <link rel="stylesheet" href="{{ asset('assets/user/') }}/css/animate.css">

</head>
<body>
    @include('layouts.bodyheader')
    @yield('content')
    @include('layouts.bodyfooter')

</body>

<!-- Latest compiled JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- jquery-cdn -->
<script src="{{ asset('assets/user/') }}/js/jquery.min.js"></script>


<!-- owl-carousel-js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

<!-- custom-js -->
<script src="{{ asset('assets/user/') }}/js/slider.js"></script>
<script src="{{ asset('assets/user/') }}/js/script.js"></script>

<!-- =========== animation-js ============== -->
<script src="{{ asset('assets/user/') }}/js/animation.js"></script>
<script src='https://unpkg.co/gsap@3/dist/gsap.min.js'></script>
<script src="{{ asset('assets/user/') }}/js/wow.min.js"></script>
<script>
    new WOW().init()
</script>
</body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // $("#:1.container").attr('visibility','visible').remove();
        $("iframe").css('display', 'none');
    });

    $(document).on('change', '.goog-te-combo', function() {
        var lang = $(this).val();
        $.ajax({
            type: "POST",
            url: "{{route('change_language')}}",
            data: {
                lang: lang,
                _token: '{{ csrf_token() }}',
            },
            success: function(output) {
                setTimeout(function() {
                    location.reload();
                }, 100);
            }
        });
    });

    $(document).ready(function() {
        setTimeout(function () {
            $('.alert').hide();
        }, 3000);
    });

    $(document).on('click', '#subsrcibenow', function() {
        window.location.href = "#";
    });

    $('#monthlySubscription').click(function(){
        
       window.location.href = "#"
    });
    
     $('#yearlySubscription').click(function(){
       window.location.href = "#"
    });

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}")
        @endforeach
    @endif
    
    
</script> 

<script type="text/javascript">
    $(document).ready(function() {
  $(".live_video").on("click", function() {
    $(this).hide();
    $('.img-bg').hide();

    $("video").html("<source src='"+ $("#url").val() +"' type='application/x-mpegURL'>");
    var ply = videojs("video");
    ply.play();
  });
});
$(document).on('click','.fa-eye-slash',function(e){
    $('#exampleInputPassword2').attr('type', 'text');
    $(this).removeClass('fa-eye-slash');
    $(this).addClass('fa-eye');
})
$(document).on('click','.fa-eye',function(e){

    $('#exampleInputPassword2').attr('type', 'password');
    $(this).removeClass('fa-eye');
    $(this).addClass('fa-eye-slash');
})
</script>
@stack('script')

<script type="text/javascript">
    // $('.video-wrap').bind('contextmenu',function() { return false; });
    // $("body").on("contextmenu", function(e) {
    //     return false;
    // });
    // $('.audio-conrol').bind('contextmenu',function() { return false; });

</script>
<script>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}")
        @endforeach
    @endif
</script>
</html>
