@extends('layouts.layout')
@section('content')

<section class="section">
    <div class="text-end">
        <button type="button" id="click-btn" class="btn btn-primary">Add Broadcast</button>
    </div>
    <div class="form-slide">
        <div class="d-flex justify-content-between mt-5"><h2>Broadcast Form</h2>
            <button class="close-btn"><span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c-9.4 9.4-9.4 24.6 0 33.9l47 47-47 47c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l47-47 47 47c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-47-47 47-47c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-47 47-47-47c-9.4-9.4-24.6-9.4-33.9 0z"/></svg></span></button>
        </div>
        
        <div class="row edit_form"> 
            <!-- Floating Labels Form -->
            <form class="" name="add_form" method="POST" id="add_form" novalidate action="{{route('daily_prayer.add')}}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control name" name="name" placeholder="">
                                <label for="name">English Name</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control korean_name"  name="korean_name" placeholder="">
                                <label for="korean_name">Korean Name</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control spanish_name"  name="spanish_name" placeholder="">
                                <label for="spanish_name">Spanish Name</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control portuguese_name"  name="portuguese_name" placeholder="">
                                <label for="portuguese_name">Portuguese Name</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control filipino_name"  name="filipino_name" placeholder="">
                                <label for="filipino_name">Filipino Name</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control title" name="title" placeholder="">
                                <label for="title">English title</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control korean_title"  name="korean_title" placeholder="">
                                <label for="korean_title">Korean title</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control spanish_title"  name="spanish_title" placeholder="">
                                <label for="spanish_title">Spanish title</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control portuguese_title"  name="portuguese_title" placeholder="">
                                <label for="portuguese_title">Portuguese title</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control filipino_title"  name="filipino_title" placeholder="">
                                <label for="filipino_title">Filipino title</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control video" name="video" placeholder="">
                                <label for="video">English Video Link</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control korean_video"  name="korean_video" placeholder="">
                                <label for="korean_video">Korean Video Link</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control spanish_video"  name="spanish_video" placeholder="">
                                <label for="spanish_video">Spanish Video Link</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control portuguese_video"  name="portuguese_video" placeholder="">
                                <label for="portuguese_video">Portuguese Video Link</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control filipino_video"  name="filipino_video" placeholder="">
                                <label for="filipino_video">Filipino Video Link</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="name">Image</label>
                        <input class="form-control image" onchange="preview_image();" id="upload" type="file" name="image">
                    </div>
                    <div class="image_preview" style="display:flex"></div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary save-btn">Save</button>
                    <button type="reset" class="btn btn-secondary save-btn">Reset</button>
                    <button type="button" class="btn btn-danger cancel save-btn">Cancel</button>
                </div>
            </form><!-- End floating Labels Form -->
        </div>
    </div>
    <div class="card p-4 mt-5">.
        {{ $dataTable->table() }}
    </div>
          
</section>
<div class="modal fade" id="deleteModal" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Delete Prayer</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <i class="bi bi-x-circle"></i>
          </button>
        </div>
        <div class="modal-body">
          <p class="pt-3">Are you sure you want to delete this Prayer ?</p>
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
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
<script type="text/javascript">
$(document).ready(function() {
    
    $('#add_form').validate({
        rules: {
            'image': {
                required: true
            },
            'name': {
                required: true
            },
            'korean_name': {
                required: true
            },
            'spanish_name': {
                required: true
            },
            'portuguese_name': {
                required: true
            },
            'filipino_name': {
                required: true
            },
            'video':{
                required: true
            },
        },
        messages: {
            'image': {
                required: "Please Select Video Image"
            },
            'name': {
                required: "Please Enter English Title"
            },
            'korean_name': {
                required: "Please Enter Korean Title"
            },
            'spanish_name': {
                required: "Please Enter Spanish Title"
            },
            'portuguese_name': {
                required: "Please Enter Portuguese Title"
            },
            'filipino_name': {
                required: "Please Enter Filipino Title"
            },
            'video': {
                required: "Please Enter English Video Link"
            },
        }
    });
});


$(document).on('click','.deletePrayer',function(e){
    var id = $(this).data('id');
    $('.deleteUrl').attr('href','{{route("daily_prayer.delete", ["id" => ""]) }}'+id);
})

$(document).on('click','.edit',function(e){
    var id = $(this).data('id');
    $.ajax({
        type : 'POST',
        url  : "{{(route('daily_prayer.edit'))}}",
        data : { id : id ,_token: '{{ csrf_token() }}',},
        success: function(data){
            $('#add_form').hide();
            $('.edit_form').append(data);
            $('section').toggleClass('newClass');
            $('#update_form').validate({
                rules: {
                    'name': {
                        required: true
                    },
                    'korean_name': {
                        required: true
                    },
                    'spanish_name': {
                        required: true
                    },
                    'portuguese_name': {
                        required: true
                    },
                    'filipino_name': {
                        required: true
                    },
                    'video':{
                        required: true
                    },
                },
                messages: {
                    'name': {
                        required: "Please Enter English Title"
                    },
                    'korean_name': {
                        required: "Please Enter Korean Title"
                    },
                    'spanish_name': {
                        required: "Please Enter Spanish Title"
                    },
                    'portuguese_name': {
                        required: "Please Enter Portuguese Title"
                    },
                    'filipino_name': {
                        required: "Please Enter Filipino Title"
                    },
                    'video': {
                        required: "Please Enter English Video Link"
                    },
                }
            });
        }
    });
})

</script>
@endpush