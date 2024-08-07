<!-- Floating Labels Form -->
<form class="update_form" name="update_form" method="POST" id="update_form" novalidate action="{{route('holylandtours.update')}}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" class="feature_movie_id" name="id" value="{{$feature_movie->id}}">
    <input type="hidden" class="old_image" name="old_image" value="{{$feature_movie->image}}">
    <div class="row g-3">
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control name" name="name" id="name" value="{{$feature_movie->name}}" placeholder="">
                    <label for="name">English Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control korean_name" id="korean_name" name="korean_name" value="{{$feature_movie->korean_name}}" placeholder="">
                    <label for="korean_name">Korean Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control spanish_name" id="spanish_name" name="spanish_name" value="{{$feature_movie->spanish_name}}" placeholder="">
                    <label for="spanish_name">Spanish Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control portuguese_name" id="portuguese_name" name="portuguese_name" value="{{$feature_movie->portuguese_name}}" placeholder="">
                    <label for="portuguese_name">Portuguese Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control filipino_name" id="filipino_name" name="filipino_name" value="{{$feature_movie->filipino_name}}" placeholder="">
                    <label for="filipino_name">Filipino Name</label>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control video" id="video" name="video" value="{{$feature_movie->video}}" placeholder="">
                    <label for="video">English Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control korean_video" id="korean_video" name="korean_video" value="{{$feature_movie->korean_video}}" placeholder="">
                    <label for="korean_video">Korean Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control spanish_video" id="spanish_video" name="spanish_video" value="{{$feature_movie->spanish_video}}" placeholder="">
                    <label for="spanish_video">Spanish Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control portuguese_video" id="portuguese_video" name="portuguese_video" value="{{$feature_movie->portuguese_video}}" placeholder="">
                    <label for="portuguese_video">Portuguese Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control filipino_video" id="filipino_video" name="filipino_video" value="{{$feature_movie->filipino_video}}" placeholder="">
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
        <button type="submit" class="btn btn-primary update save-btn">Update</button>
        <button type="button" class="btn btn-danger cancel save-btn">Cancel</button>
    </div>
</form><!-- End floating Labels Form -->
<script src="{{ asset('assets/admin/') }}/js/jquery-1.11.1.min.js" ></script>
<script src="{{ asset('assets/admin/') }}/js/jquery.validate.min.js"></script>