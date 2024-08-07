@extends('layouts.layout')
@section('content')

<section class="section">
    <div class="text-end">
        <button type="button" id="click-btn" class="btn btn-primary">Add Affiliate</button>
    </div>
    <div class="form-slide">
        <div class="d-flex justify-content-between mt-5"><h2>Affiliate Form</h2>
            <button class="close-btn"><span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c-9.4 9.4-9.4 24.6 0 33.9l47 47-47 47c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l47-47 47 47c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-47-47 47-47c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-47 47-47-47c-9.4-9.4-24.6-9.4-33.9 0z"/></svg></span></button>
        </div>
        
        <div class="row edit_form"> 
            <form class="row g-3" name="add_form" method="POST" id="add_form" novalidate action="{{route('affiliates.add')}}" enctype="multipart/form-data">
                @csrf
                
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" class="form-control name" name="name" placeholder="">
                        <label for="name">Affilate Name</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="email" class="form-control email"  name="email" placeholder="">
                        <label for="email">Email</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="password" class="form-control password"  name="password" placeholder="">
                        <label for="password">Password</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <select class="form-select country_code" name="country_code" aria-label="State">
                            <option value="">Select Country Code</option>
                            @foreach ($country_code as $key => $code)
                                <option value="{{$key}}">{{$code}}</option>
                            @endforeach
                        </select>
                        <label for="country_code">Country Code</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="number" class="form-control mobile"  name="mobile" placeholder="">
                        <label for="mobile">Mobile Number</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-floating">
                        <input type="number" class="form-control percentage"  name="percentage" placeholder="">
                        <label for="percentage">Affilate Percentage</label>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary save-btn">Save</button>
                    <button type="reset" class="btn btn-secondary save-btn">Reset</button>
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
          <h5 class="modal-title">Delete Affiliate</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <i class="bi bi-x-circle"></i>
          </button>
        </div>
        <div class="modal-body">
          <p class="pt-3">Are you sure you want to delete this Affiliate ?</p>
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
            'name': {
                required: true
            },
            'email': {
                required: true
            },
            'password': {
                required: true,
                minlength: 6
            },
            'country_code': {
                required: true
            },
            'mobile': {
                required: true
            },
            'percentage': {
                required: true
            },
        },
        messages: {
            'password': {
                required: "Please Enter Password",
            },
            'email': {
                required: "Please Select Email"
            },
            'name': {
                required: "Please Enter Affiliate Name"
            },
            'country_code': {
                required: "Please Select Country Code"
            },
            'mobile': {
                required: "Please Enter Mobile"
            },
            'percentage': {
                required: "Please Enter Percentage"
            },
        }
    });
});

$(document).on('click','.deleteAffilate',function(e){
    var id = $(this).data('id');
    $('.deleteUrl').attr('href','{{route("affiliates.delete", ["id" => ""]) }}'+id);
});
$(document).on('click','.edit',function(e){
    var id = $(this).data('id');
    $.ajax({
        type : 'POST',
        url  : "{{(route('affiliates.edit'))}}",
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
                    'email': {
                        required: true
                    },
                    'country_code': {
                        required: true
                    },
                    'mobile': {
                        required: true
                    },
                    'percentage': {
                        required: true
                    },
                },
                messages: {
                    'email': {
                        required: "Please Select Email"
                    },
                    'name': {
                        required: "Please Enter Affiliate Name"
                    },
                    'country_code': {
                        required: "Please Select Country Code"
                    },
                    'mobile': {
                        required: "Please Enter Mobile"
                    },
                    'percentage': {
                        required: "Please Enter Percentage"
                    },
                }
            });
        }
    });
})

</script>
@endpush