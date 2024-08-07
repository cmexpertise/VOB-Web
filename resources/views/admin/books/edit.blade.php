<form class="row g-3 update_form" name="update_form" method="POST" id="update_form" action="{{route('books.update')}}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{$book->id}}">
    <input type="hidden" name="old_video_image" value="{{$book->video_image}}">
    <input type="hidden" name="old_audio_image" value="{{$book->audio_image}}">
<!-- Floating Labels Form -->
    
    
    <div class="col-xxl-2 col-xl-2 col-lg-3 col-md-3">
        <div class="form-floating">
            <select class="form-select type" name="type" aria-label="State">
                <option value="">Select Type</option>
                <option value="1" {{($book->type==1)?'selected':''}}>new testament</option>
                <option value="2" {{($book->type==2)?'selected':''}}>old testament</option>
                {{-- <option value="3" {{($book->type==3)?'selected':''}}>four gospets</option> --}}
            </select>
            <label for="type">Select Book Type</label>
      </div>
    </div>
    <div class="col-xxl-2 col-xl-2 col-lg-3 col-md-3">
        <div class="form-floating">
            <input type="text" class="form-control name" name="name" placeholder="" value="{{$book->name}}">
            <label for="name">Book English Name</label>
        </div>
    </div>
    <div class="col-xxl-2 col-xl-2 col-lg-3 col-md-3">
        <div class="form-floating">
            <input type="text" class="form-control korean_name"  name="korean_name" value="{{$book->korean_name}}" placeholder="">
            <label for="korean_name">Book Korean Name</label>
        </div>
    </div>
    <div class="col-xxl-2 col-xl-2 col-lg-3 col-md-3">
        <div class="form-floating">
            <input type="text" class="form-control spanish_name"  name="spanish_name" value="{{$book->spanish_name}}" placeholder="">
            <label for="spanish_name">Book Spanish Name</label>
        </div>
    </div>
    <div class="col-xxl-2 col-xl-2 col-lg-3 col-md-3">
        <div class="form-floating">
            <input type="text" class="form-control portuguese_name"  name="portuguese_name" value="{{$book->portuguese_name}}" placeholder="">
            <label for="portuguese_name">Book Portuguese Name</label>
        </div>
    </div>
    <div class="col-xxl-2 col-xl-2 col-lg-3 col-md-3">
        <div class="form-floating">
            <input type="text" class="form-control filipino_name"  name="filipino_name" value="{{$book->filipino_name}}" placeholder="">
            <label for="filipino_name">Book Filipino Name</label>
        </div>
    </div>
    <div class="col-12">
        <div class="form-floating">
            <textarea class="form-control description" placeholder="Description" name="description" style="height: 100px;">{{$book->description}}</textarea>
            <label for="description">English Description</label>
        </div>
      </div>
    <div class="col-md-6">
        <div class="form-floating">
            <textarea class="form-control korean_description" placeholder="Description"  name="korean_description" style="height: 100px;">{{$book->korean_description}}</textarea>
            <label for="korean_description">Korean Description</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating">
            <textarea class="form-control spanish_description" placeholder="Description" name="spanish_description" style="height: 100px;">{{$book->spanish_description}}</textarea>
            <label for="spanish_description">Spanish Description</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating">
            <textarea class="form-control portuguese_description" placeholder="Description"  name="portuguese_description" style="height: 100px;">{{$book->portuguese_description}}</textarea>
            <label for="portuguese_description">Portuguese Description</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating">
            <textarea class="form-control filipino_description" placeholder="Description" name="filipino_description" style="height: 100px;">{{$book->filipino_description}}</textarea>
            <label for="filipino_description">Filipino Description</label>
        </div>
    </div>
    <div class="col-md-3">
        <label for="video_image">Video Image</label>
        <input class="form-control upload" id="upload" type="file" name="video_image">
    </div>
    <div class="col-md-3">
        <label for="audio_image">Audio Image</label>
        <input class="form-control upload" id="upload" type="file" name="audio_image">
    </div>
    <div class="image_preview" style="display:flex,height:10px"></div>

    <div class="text-center">
        <button type="submit" id="update" class="btn btn-primary save-btn" value="Update">Update</button>
        <button type="button" class="btn btn-danger save-btn cancel">Cancel</button>
    </div>
</form>
<script src="{{ asset('assets/admin/') }}/js/jquery-1.11.1.min.js" ></script>
<script src="{{ asset('assets/admin/') }}/js/jquery.validate.min.js"></script>