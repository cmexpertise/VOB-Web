@extends('layouts.layout')
@section('content')
<style>
   
</style>
<section class="section">
    <div class="card p-4 mt-5">
        {{ $dataTable->table() }}
        {{-- <table class="table datatable pt-4 pb-4" id="userpostcard-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User Name</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table> --}}
    </div>
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
          <h5 class="modal-title">Delete Postcard</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <i class="bi bi-x-circle"></i>
          </button>
        </div>
        <div class="modal-body">
          <p class="pt-3">Are you sure you want to delete this Postcard ?</p>
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
    filter();
});

function filter(){
    // $('#userpostcard-table').DataTable({
    //     processing: true,
    //     serverSide: false,
    //     ordering: false,
    //     ajax: {
    //         url: '{{route("postcards.userdatatable")}}',
    //         type: 'GET',
    //     },
    //     columns: [
    //         {data: 'sr_no',},
    //         {data: 'user_name',},
    //         {data: 'image',},
    //     ],
    //     "columnDefs": [
    //         { "className": "text-left", "targets": "_all" }
    //     ],
    // });

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
    }, 500);
}

$(document).on('click','.deletePostcard',function(e){
    var id = $(this).data('id');
    $('.deleteUrl').attr('href','{{route("postcards.delete", ["id" => ""]) }}'+id);
})  
</script>
@endpush