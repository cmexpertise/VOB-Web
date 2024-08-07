@extends('layouts.layout')
@section('content')
<section class="section">
    <div class="row edit_form"> 
        <form class="" name="add_form" method="POST" id="add_form" novalidate action="{{route('admin.add_mail_format')}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{(isset($mail['id']) && $mail['id'] != null)?encrypt($mail['id']):null}}">
            <div class="row g-3">
                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control subject" name="subject" placeholder="" value="{{(isset($mail['subject']) && $mail['subject'] != null)?$mail['subject']:null}}">
                            <label for="subject">Enter Subject</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control body" name="body" placeholder="" value="{{(isset($mail['body']) && $mail['body'] != null)?$mail['body']:null}}">
                            <label for="body">Enter Body</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control title" name="title" placeholder="" value="{{(isset($mail['title']) && $mail['title'] != null)?$mail['title']:null}}">
                            <label for="title">Enter Title</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <button type="submit" class="btn btn-primary save-btn">Save</button>
                <button type="reset" class="btn btn-secondary save-btn">Reset</button>
                <button type="button" class="btn btn-danger cancel save-btn">Cancel</button>
            </div>
        </form>
    </div>
</section>
<div class="modal fade" id="deleteModal" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Delete Gemara</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <i class="bi bi-x-circle"></i>
          </button>
        </div>
        <div class="modal-body">
          <p class="pt-3">Are you sure you want to delete this Gemara ?</p>
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
    $('#add_forms').validate({
        rules: {
            'host': {
                required: true
            },
            'port': {
                required: true
            },
            'user': {
                required: true
            },
            'password': {
                required: true
            },
        },
        messages: {
            'host': {
                required: "Please Enter Host"
            },
            'port': {
                required: "Please Enter Port"
            },
            'user': {
                required: "Please Enter User"
            },
            'password': {
                required: "Please Enter Password"
            },
        }
    });
});

</script>
@endpush