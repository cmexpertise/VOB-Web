<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vision Of The Bible</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets/admin/') }}/img/favicon.png" rel="icon">
    <link href="{{ asset('assets/admin/') }}/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/admin/') }}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/admin/') }}/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/admin/') }}/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/admin/') }}/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="{{ asset('assets/admin/') }}/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="{{ asset('assets/admin/') }}/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="{{ asset('assets/admin/') }}/vendor/simple-datatables/style.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/admin/') }}/css/style.css" rel="stylesheet">
</head>

<body>
    @include('layouts.navigation')
    @include('layouts.sidebar')
    <main id="main" class="main">

        <div class="pagetitle">
          <h1>{{(isset($header))?$header:''}}</h1>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">{{(isset($header))?$header:''}}</li>
            </ol>
          </nav>
        </div>
    @yield('content')
    </main>
      <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
        &copy; Copyright <strong><span>Vision Of The Bible</span></strong>. All Rights Reserved
        </div>
        {{-- <div class="credits">
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div> --}}
    </footer><!-- End Footer -->
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/admin/') }}/js/jquery-1.11.1.min.js" ></script>
    <script src="{{ asset('assets/admin/') }}/js/magnific-popup.min.js"></script>
    <script src="{{ asset('assets/admin/') }}/js/jquery.validate.min.js"></script>
    <script src="{{ asset('assets/admin/') }}/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/admin/') }}/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('assets/admin/') }}/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="{{ asset('assets/admin/') }}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/admin/') }}/vendor/chart.js/chart.umd.js"></script>
    <script src="{{ asset('assets/admin/') }}/vendor/echarts/echarts.min.js"></script>
    <script src="{{ asset('assets/admin/') }}/vendor/quill/quill.js"></script>
    <script src="{{ asset('assets/admin/') }}/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="{{ asset('assets/admin/') }}/vendor/tinymce/tinymce.min.js"></script>
    <script src="{{ asset('assets/admin/') }}/js/lazysizes.min.js" async></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    {{-- <script src="{{ asset('assets/admin/') }}/vendor/php-email-form/validate.js"></script> --}}
  

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/admin/') }}/js/main.js"></script>
    @stack('script')

    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
      $(document).on('click','.cancel',function(e){
        window.location.reload();
      });
      function goBack() {
        window.history.go(-1);
      }
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}")
            @endforeach
        @endif
        $(document).on('change','#upload',function(e){
          var total_file = this.files.length;
          
          for (var i = 0; i < total_file; i++) {
            
            $(".image_preview").append(
              "<img src='" + URL.createObjectURL(event.target.files[i]) + "'>"
            ).css('height', '100px').css('width', '100px');
          }
        })
        $(document).on('click','.edit',function(e){
          $('.edit').prop('disabled', true);
        });
        $(document).on('click','.close-btn',function(e){
          $('.edit').prop('disabled', false);
        });
        // function preview_image() {
        //   var total_file = document.getElementById("upload").files.length;

        //   for (var i = 0; i < total_file; i++) {
            
        //     $("#image_preview").append(
        //       "<img src='" + URL.createObjectURL(event.target.files[i]) + "'>"
        //     ).css('height', '100px').css('width', '100px');
        //   }
        // }
        $(document).ready(function() {
          $('.click-btn').click(function() {
            $(".image_preview").empty();
            $('section').toggleClass('newClass');
          });
          $('#click-btn').click(function() {
            $(".image_preview").empty();
            $(".update_form").hide();
            $('#add_form').show();
            $('section').toggleClass('newClass');
          });
          $('.close-btn').click(function() {
            $('section').removeClass('newClass');
            $(".update_form").hide();
            $('#add_form').hide();
          });
          
        });
    </script>
</body>

</html>