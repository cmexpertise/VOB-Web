@extends('layouts.layout')
@section('content')

<section class="section">
    <div class="row edit_form"> 
        <form class="" name="add_form" method="POST" id="add_form" novalidate action="{{route('cron.send_notification')}}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control name" name="name" placeholder="">
                            <label for="name">Enter Text</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <textarea class="form-control description" placeholder="Description" name="description" style="height: 100px;"></textarea>
                            <label for="description">Description</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <button type="submit" class="btn btn-primary save-btn">Send</button>
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
    $('#add_form').validate({
        rules: {
            'name': {
                required: true
            },
        },
        messages: {
            'name': {
                required: "Please Enter Notification Text"
            },
        }
    });
});

</script>
@endpush