@extends('layouts.layout')
@section('content')
<section class="section">
    <div class="row edit_form"> 
        <form class="" name="add_form" method="POST" id="add_form" novalidate action="{{route('admin.add_update_smtp')}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{(isset($mail['id']) && $mail['id'] != null)?encrypt($mail['id']):null}}">
            <div class="row g-3">
                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control host" name="host" placeholder="" value="{{(isset($mail['host']) && $mail['host'] != null)?$mail['host']:null}}">
                            <label for="host">Enter Host</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="number" class="form-control port" name="port" placeholder="" value="{{(isset($mail['port']) && $mail['port'] != null)?$mail['port']:null}}">
                            <label for="port">Enter Port</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control user" name="user" placeholder="" value="{{(isset($mail['user']) && $mail['user'] != null)?$mail['user']:null}}">
                            <label for="user">Enter User</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control password" name="password" placeholder="" value="{{(isset($mail['password']) && $mail['password'] != null)?$mail['password']:null}}">
                            <label for="password">Enter Password</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control from_mail" name="from_mail" placeholder="" value="{{(isset($mail['from_mail']) && $mail['from_mail'] != null)?$mail['from_mail']:null}}">
                            <label for="from_mail">Enter From mail</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control encryption" name="encryption" placeholder="" value="{{(isset($mail['encryption']) && $mail['encryption'] != null)?$mail['encryption']:null}}">
                            <label for="encryption">Enter Encryption</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control name" name="name" placeholder="" value="{{(isset($mail['name']) && $mail['name'] != null)?$mail['name']:null}}">
                            <label for="name">Enter From Name</label>
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