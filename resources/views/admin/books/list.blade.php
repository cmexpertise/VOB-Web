@extends('layouts.layout')
@section('content')
<style>
   
</style>
<section class="section">
    <div class="text-end">
        <button type="button" id="click-btn" class="btn btn-primary">Add Book</button>
    </div>
    <div class="form-slide">
        <div class="d-flex justify-content-between mt-5">
            <h2>Book Form</h2>
            <button class="close-btn"><span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c-9.4 9.4-9.4 24.6 0 33.9l47 47-47 47c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l47-47 47 47c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-47-47 47-47c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-47 47-47-47c-9.4-9.4-24.6-9.4-33.9 0z"/></svg></span></button>
        </div>
        <div class="row edit_form"> 
            <form class="row g-3" name="add_form" method="POST" id="add_form" novalidate action="{{route('books.add')}}" enctype="multipart/form-data">
                @csrf
                <div class="col-xxl-2 col-xl-2 col-lg-3 col-md-3">
                    <div class="form-floating">
                        <select class="form-select type" name="type[]" aria-label="State">
                            <option value="">Select Type</option>
                            <option value="1">new testament</option>
                            <option value="2">old testament</option>
                            {{-- <option value="3">four gospets</option> --}}
                        </select>
                        <label for="type">Select Book Type</label>
                    </div>
                </div>
                <div class="col-xxl-2 col-xl-2 col-lg-3 col-md-3">
                    <div class="form-floating">
                        <input type="text" class="form-control name" name="name[]" placeholder="">
                        <label for="name">Book English Name</label>
                    </div>
                </div>
                <div class="col-xxl-2 col-xl-2 col-lg-3 col-md-3">
                    <div class="form-floating">
                        <input type="text" class="form-control korean_name"  name="korean_name[]" placeholder="">
                        <label for="korean_name">Book Korean Name</label>
                    </div>
                </div>
                <div class="ccol-xxl-2 col-xl-2 col-lg-3 col-md-3">
                    <div class="form-floating">
                        <input type="text" class="form-control spanish_name"  name="spanish_name[]" placeholder="">
                        <label for="spanish_name">Book Spanish Name</label>
                    </div>
                </div>
                <div class="col-xxl-2 col-xl-2 col-lg-3 col-md-3">
                    <div class="form-floating">
                        <input type="text" class="form-control portuguese_name"  name="portuguese_name[]" placeholder="">
                        <label for="portuguese_name">Book Portuguese Name</label>
                    </div>
                </div>
                <div class="col-xxl-2 col-xl-2 col-lg-3 col-md-3">
                    <div class="form-floating">
                        <input type="text" class="form-control filipino_name"  name="filipino_name[]" placeholder="">
                        <label for="filipino_name">Book Filipino Name</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <textarea class="form-control description" placeholder="Description" name="description[]" style="height: 100px;"></textarea>
                        <label for="description">English Description</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <textarea class="form-control korean_description" placeholder="Description"  name="korean_description[]" style="height: 100px;"></textarea>
                        <label for="korean_description">Korean Description</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <textarea class="form-control spanish_description" placeholder="Description" name="spanish_description[]" style="height: 100px;"></textarea>
                        <label for="spanish_description">Spanish Description</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <textarea class="form-control portuguese_description" placeholder="Description"  name="portuguese_description[]" style="height: 100px;"></textarea>
                        <label for="portuguese_description">Portuguese Description</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <textarea class="form-control filipino_description" placeholder="Description" name="filipino_description[]" style="height: 100px;"></textarea>
                        <label for="filipino_description">Filipino Description</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="video_image">Video Image</label>
                    <input class="form-control video_image upload" id="upload" type="file" name="video_image[]">
                </div>
                <div class="col-md-3">
                    <label for="audio_image">Audio Image</label>
                    <input class="form-control audio_image upload" id="upload" type="file" name="audio_image[]">    
                </div>
                <div class="image_preview" style="display:flex"></div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary save-btn">Save</button>
                    <button type="reset" class="btn btn-secondary reset-btn">Reset</button>
                </div>
            </form><!-- End floating Labels Form -->
        </div>
    </div>
    <div class="card p-4 mt-5"> 
        {{ $dataTable->table() }}
    </div>         
</section>
<div class="modal fade" id="deleteModal" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Delete Book</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <i class="bi bi-x-circle"></i>
          </button>
        </div>
        <div class="modal-body">
          <p class="pt-3">Are you sure you want to delete this Book ?</p>
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
            'type[]': {
                required: true
            },
            'video_image[]': {
                required: true
            },
            'name[]': {
                required: true
            },
            'korean_name[]': {
                required: true
            },
            'spanish_name[]': {
                required: true
            },
            'portuguese_name[]': {
                required: true
            },
            'filipino_name[]': {
                required: true
            },
            'description[]': {
                required: true
            },
            'korean_description[]': {
                required: true
            },
            'spanish_description[]': {
                required: true
            },
            'portuguese_description[]': {
                required: true
            },
            'filipino_description[]': {
                required: true
            },
        },
        messages: {
            'type[]': {
                required: "Please Select Book Type"
            },
            'video_image[]': {
                required: "Please Select Book Image"
            },
            'name[]': {
                required: "Please Enter English Book Name"
            },
            'korean_name[]': {
                required: "Please Enter Korean Book Name"
            },
            'spanish_name[]': {
                required: "Please Enter Spanish Book Name"
            },
            'portuguese_name[]': {
                required: "Please Enter Portuguese Book Name"
            },
            'filipino_name[]': {
                required: "Please Enter Filipino Book Name"
            },
            'description[]': {
                required: "Please Enter English Book Description"
            },
            'korean_description[]': {
                required: "Please Enter Korean Book Description"
            },
            'spanish_description[]': {
                required: "Please Enter Spanish Book Description"
            },
            'portuguese_description[]': {
                required: "Please Enter Portuguese Book Description"
            },
            'filipino_description[]': {
                required: "Please Enter Filipino Book Description"
            },
        }
    });
});
$(document).on('click','.deleteBook',function(e){
    var id = $(this).data('id');
    $('.deleteUrl').attr('href','{{route("books.delete", ["id" => ""]) }}'+id);
});
$(document).on('click','.edit',function(e){
    var id = $(this).data('id');
    $.ajax({
        type : 'POST',
        url  : "{{(route('books.edit'))}}",
        data : { id : id ,_token: '{{ csrf_token() }}',},
        success: function(data){
            $('#add_form').hide();
            $('.edit_form').append(data);
            $('section').toggleClass('newClass');
            $('#update_form').validate({
                rules: {
                    type: {
                        required: true
                    },
                    name: {
                        required: true
                    },
                    korean_name: {
                        required: true
                    },
                    spanish_name: {
                        required: true
                    },
                    portuguese_name: {
                        required: true
                    },
                    filipino_name: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    korean_description: {
                        required: true
                    },
                    spanish_description: {
                        required: true
                    },
                    portuguese_description: {
                        required: true
                    },
                    filipino_description: {
                        required: true
                    },
                },
                messages: {
                    'type': {
                        required: "Please Select Book Type"
                    },
                    'name': {
                        required: "Please Enter English Book Name"
                    },
                    'korean_name': {
                        required: "Please Enter Korean Book Name"
                    },
                    'spanish_name': {
                        required: "Please Enter Spanish Book Name"
                    },
                    'portuguese_name': {
                        required: "Please Enter Portuguese Book Name"
                    },
                    'filipino_name': {
                        required: "Please Enter Filipino Book Name"
                    },
                    'description': {
                        required: "Please Enter English Book Description"
                    },
                    'korean_description': {
                        required: "Please Enter Korean Book Description"
                    },
                    'spanish_description': {
                        required: "Please Enter Spanish Book Description"
                    },
                    'portuguese_description': {
                        required: "Please Enter Portuguese Book Description"
                    },
                    'filipino_description': {
                        required: "Please Enter Filipino Book Description"
                    },
                }
            });
        }
    });
})

</script>
@endpush