@extends('layouts.layout')
@section('content')
<style>
   
</style>
<section class="section">
    <div class="row edit_form"> 
        <!-- Floating Labels Form -->
        <form class="add_quote" name="add_quote" method="POST" id="add_quote" novalidate action="{{route('quotes.add_quotes')}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="quote_id" value="{{$quote_id}}" id="quote_id">
            <input type="hidden" name="quote_name" value="{{$name}}" >
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="name">Image</label>
                    <input id="upload" onchange="preview_image();" type="file" class="form-control image" name="image[]" multiple="">
                </div>
                <div class="image_preview" style="display:flex"></div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary save-btn">Save</button>
                <button type="reset" class="btn btn-secondary save-btn">Reset</button>
                <a href="{{route('quotes')}}" class="btn btn-dark save-btn">Back</a>
            </div>
        </form>
        <!-- End floating Labels Form -->
    </div>
    <section class="books-table mt-4 shadows">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <h3 class="top-title">quotes list</h3>
            </div>

        </div>
        <div class="row" id="quotes">
            
        </div>
        <div id="loader_message"></div>
        </div>
      </section>
</section>
<div id="myModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="img01">
    <div id="caption"></div>
  </div>
<div class="modal fade" id="deleteModal" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Delete Quote</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <i class="bi bi-x-circle"></i>
          </button>
        </div>
        <div class="modal-body">
          <p class="pt-3">Are you sure you want to delete this Quote ?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn modal-primary-btn btn-primary save-btn" data-bs-dismiss="modal">Cancel</button>
          <a href="#" class="btn modal-secondary-btn deleteUrl btn-danger save-btn">Delete</a>
        </div>
      </div>
    </div>
</div>
@endsection
@push('script')
<script type="text/javascript">
$(document).ready(function() {
    var busy = false;
    var limit = 12;
    var offset = 0;
    
    if (busy == false) {
        busy = true;
        displayRecords(limit, offset);
    }

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
            busy = true;
            offset = limit + offset;
            setTimeout(function () {
                displayRecords(limit, offset);
            }, 500);
        }
    });
    
    $('#add_quote').validate({
        rules: {
            'image[]': {
                required: true
            },
        },
        messages: {
            'image[]': {
                required: "Please Select Image"
            },
        }
    });
});

function displayRecords(lim, off) {
    var quote_id = '{{$quote_id}}';
    $.ajax({
        type: "GET",
        async: false,
        url: "{{route('quotes.getquotes')}}",
        data: "limit=" + lim + "&offset=" + off + "&quote_id=" + quote_id,
        cache: false,
        beforeSend: function () {
            $("#loader_message").html("").hide();
        },
        success: function (html) {
            $("#quotes ").append(html);
            if (html != "") {
                $("#loader_message").html('<button class="btn btn-default" type="button">Loading please wait...</button>').show();
            }
            window.busy = false;
            zoomQuotes();
        },
    });
}

function zoomQuotes(){
    setTimeout(() => {
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
        $('.image-popup-vertical-fit1').magnificPopup({
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
    }, 100);
}

$(document).on('click','.deleteQuote',function(e){
    var id = $(this).data('id');
    $('.deleteUrl').attr('href','{{route("quotes.quotes_delete", ["id" => ""]) }}'+id);
})

</script>
@endpush