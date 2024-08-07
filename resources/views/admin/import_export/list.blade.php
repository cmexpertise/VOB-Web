@extends('layouts.layout')
@section('content')

<section class="section">
      
        <div class="row edit_form"> 
            <!-- Floating Labels Form -->
            <form class="" name="add_form" method="POST" id="add_form" novalidate action="{{route('books.exportBook')}}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select type" name="type" aria-label="State">
                                    <option value="">Select Type</option>
                                    <option value="1">Export Excel For Book</option>
                                    <option value="2">Export Excel For Chapter</option>
                                    <option value="3">Export Excel For Travel Samaritan</option>
                                    <option value="4">Export Excel For Broadcasts</option>
                                    <option value="5">Export Excel For Daily Meditations</option>
                                    <option value="6">Export Excel For Daily Meditations Videos</option>
                                    <option value="7">Export Excel For Night Time Stories</option>
                                    <option value="8">Export Excel For Night Time Story Videos</option>
                                </select>
                                <label for="type">Select Type</label>
                            </div>
                        </div>
                        <div class="text-center col-md-6">
                            <div class="form-floating">
                                <button type="submit" class="btn btn-primary save-btn">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <form class="" name="import" method="POST" id="import" novalidate action="{{route('books.importBook')}}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select type" name="type" aria-label="State">
                                    <option value="">Select Type</option>
                                    <option value="1">Import Excel For Book</option>
                                    <option value="2">Import Excel For Chapter</option>
                                    <option value="3">Import Excel For Travel Samaritan</option>
                                    <option value="4">Import Excel For Broadcasts</option>
                                    <option value="5">Import Excel For Meditations</option>
                                    <option value="6">Import Excel For Meditation Videos</option>
                                    <option value="7">Import Excel For Night Time Stories</option>
                                    <option value="8">Import Excel For Night Time Story Videos</option>
                                    <option value="9">Import Excel For Update Chapter Audios</option>
                                </select>
                                <label for="type">Select Type</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="name">Import Excel</label>
                            <input class="form-control" type="file" name="excel" id="excel">
                        </div>
                        <div class="text-center col-md-4">
                            <div class="form-floating">
                                <button type="submit" class="btn btn-primary save-btn">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- End floating Labels Form -->
        </div>

          
</section>
@endsection
@push('script')
<script type="text/javascript">
$(document).ready(function() {
    $('#add_form').validate({
        rules: {
            'type': {
                required: true
            },
        },
        messages: {
            'type': {
                required: "Please Select Type"
            },
        }
    });
    $('#import').validate({
        rules: {
            'type': {
                required: true
            },
            'excel': {
                required:true
            }
        },
        messages: {
            'type': {
                required: "Please Select Type"
            },
            'excel': {
                required: "Please Select Excel "
            }
        }
    });
});

</script>
@endpush