<!-- Floating Labels Form -->
<form class="update_form" name="update_form" method="POST" id="update_form" novalidate action="{{route('daily_prayer.update')}}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" class="prayer_id" name="id" value="{{$prayer->id}}">
    <input type="hidden" class="old_image" name="old_image" value="{{$prayer->image}}">
    <div class="row g-3">
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control name" name="name" id="name" value="{{$prayer->name}}" placeholder="">
                    <label for="name">English Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control korean_name" id="korean_name" name="korean_name" value="{{$prayer->korean_name}}" placeholder="">
                    <label for="korean_name">Korean Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control spanish_name" id="spanish_name" name="spanish_name" value="{{$prayer->spanish_name}}" placeholder="">
                    <label for="spanish_name">Spanish Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control portuguese_name" id="portuguese_name" name="portuguese_name" value="{{$prayer->portuguese_name}}" placeholder="">
                    <label for="portuguese_name">Portuguese Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control filipino_name" id="filipino_name" name="filipino_name" value="{{$prayer->filipino_name}}" placeholder="">
                    <label for="filipino_name">Filipino Name</label>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control title" name="title" id="title" value="{{$prayer->title}}" placeholder="">
                    <label for="title">English title</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control korean_title" id="korean_title" name="korean_title" value="{{$prayer->korean_title}}" placeholder="">
                    <label for="korean_title">Korean title</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control spanish_title" id="spanish_title" name="spanish_title" value="{{$prayer->spanish_title}}" placeholder="">
                    <label for="spanish_title">Spanish title</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control portuguese_title" id="portuguese_title" name="portuguese_title" value="{{$prayer->portuguese_title}}" placeholder="">
                    <label for="portuguese_title">Portuguese title</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control filipino_title" id="filipino_title" name="filipino_title" value="{{$prayer->filipino_title}}" placeholder="">
                    <label for="filipino_title">Filipino title</label>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control video" id="video" name="video" value="{{$prayer->video}}" placeholder="">
                    <label for="video">English Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control korean_video" id="korean_video" name="korean_video" value="{{$prayer->korean_video}}" placeholder="">
                    <label for="korean_video">Korean Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control spanish_video" id="spanish_video" name="spanish_video" value="{{$prayer->spanish_video}}" placeholder="">
                    <label for="spanish_video">Spanish Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control portuguese_video" id="portuguese_video" name="portuguese_video" value="{{$prayer->portuguese_video}}" placeholder="">
                    <label for="portuguese_video">Portuguese Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control filipino_video" id="filipino_video" name="filipino_video" value="{{$prayer->filipino_video}}" placeholder="">
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