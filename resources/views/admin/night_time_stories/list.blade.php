@extends('layouts.layout')
@section('content')

<section class="section">
    <div class="text-end">
        <button type="button" id="click-btn" class="btn btn-primary">Add Night Time Story</button>
    </div>
    <div class="form-slide">
        <div class="d-flex justify-content-between mt-5"><h2>Night Time Story Form</h2>
            <button class="close-btn"><span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c-9.4 9.4-9.4 24.6 0 33.9l47 47-47 47c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l47-47 47 47c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-47-47 47-47c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-47 47-47-47c-9.4-9.4-24.6-9.4-33.9 0z"/></svg></span></button>
        </div>
        
        <div class="row edit_form"> 
            <!-- Floating Labels Form -->
            <form class="add_form" name="add_form" method="POST" id="add_form" novalidate action="{{route('nighttimestories.add')}}" enctype="multipart/form-data">
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
                    <div class="col-6">
                        <div class="form-floating">
                            <textarea class="form-control description" placeholder="Description" name="description" style="height: 100px;"></textarea>
                            <label for="description">English Description</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <textarea class="form-control korean_description" placeholder="Description"  name="korean_description" style="height: 100px;"></textarea>
                            <label for="korean_description">Korean Description</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <textarea class="form-control spanish_description" placeholder="Description" name="spanish_description" style="height: 100px;"></textarea>
                            <label for="spanish_description">Spanish Description</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <textarea class="form-control portuguese_description" placeholder="Description"  name="portuguese_description" style="height: 100px;"></textarea>
                            <label for="portuguese_description">Portuguese Description</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <textarea class="form-control filipino_description" placeholder="Description" name="filipino_description" style="height: 100px;"></textarea>
                            <label for="filipino_description">Filipino Description</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="name">Image</label>
                        <input class="form-control image" onchange="preview_image();" id="upload" type="file" name="image">
                    </div>
                    <div class="col-md-3">
                        <div class="image_preview" style="display:flex"></div>
                    </div>
                </div>
                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-primary save-btn">Save</button>
                    <button type="reset" class="btn btn-secondary save-btn">Reset</button>
                    <button type="button" class="btn btn-danger cancel save-btn">Cancel</button>
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
          <h5 class="modal-title">Delete Story</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <i class="bi bi-x-circle"></i>
          </button>
        </div>
        <div class="modal-body">
          <p class="pt-3">Are you sure you want to delete this Story ?</p>
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
            'description': {
                required: true
            },
            'korean_description': {
                required: true
            },
            'spanish_description': {
                required: true
            },
            'portuguese_description': {
                required: true
            },
            'filipino_description': {
                required: true
            },
        },
        messages: {
            'image': {
                required: "Please Select Image"
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
            'description': {
                required: "Please Enter English Description"
            },
            'korean_description': {
                required: "Please Enter Korean Description"
            },
            'spanish_description': {
                required: "Please Enter Spanish Description"
            },
            'portuguese_description': {
                required: "Please Enter Portuguese Description"
            },
            'filipino_description': {
                required: "Please Enter Filipino Description"
            },
        }
    });
});

$(document).on('click','.deleteStory',function(e){
    var id = $(this).data('id');
    $('.deleteUrl').attr('href','{{route("nighttimestories.delete", ["id" => ""]) }}'+id);
})

$(document).on('click','.edit',function(e){
    var id = $(this).data('id');
    $.ajax({
        type : 'POST',
        url  : "{{(route('nighttimestories.edit'))}}",
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
                    'description': {
                        required: true
                    },
                    'korean_description': {
                        required: true
                    },
                    'spanish_description': {
                        required: true
                    },
                    'portuguese_description': {
                        required: true
                    },
                    'filipino_description': {
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
                    'description': {
                        required: "Please Enter English Description"
                    },
                    'korean_description': {
                        required: "Please Enter Korean Description"
                    },
                    'spanish_description': {
                        required: "Please Enter Spanish Description"
                    },
                    'portuguese_description': {
                        required: "Please Enter Portuguese Description"
                    },
                    'filipino_description': {
                        required: "Please Enter Filipino Description"
                    },
                }
            });
        }
    });
})

</script>
@endpush