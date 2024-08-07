<!-- Floating Labels Form -->
<form class="update_form" name="update_form" method="POST" id="update_form" novalidate action="{{route('travel_samaritans.update')}}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" class="travel_id" name="id" value="{{$travel->id}}">
    <input type="hidden" class="old_image" name="old_image" value="{{$travel->image}}">
    <input type="hidden" class="old_web_image" name="old_web_image" value="{{$travel->web_image}}">
    <div class="row g-3">
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control name" name="name" id="name" value="{{$travel->name}}" placeholder="">
                    <label for="name">English Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control korean_name" id="korean_name" name="korean_name" value="{{$travel->korean_name}}" placeholder="">
                    <label for="korean_name">Korean Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control spanish_name" id="spanish_name" name="spanish_name" value="{{$travel->spanish_name}}" placeholder="">
                    <label for="spanish_name">Spanish Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control portuguese_name" id="portuguese_name" name="portuguese_name" value="{{$travel->portuguese_name}}" placeholder="">
                    <label for="portuguese_name">Portuguese Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control filipino_name" id="filipino_name" name="filipino_name" value="{{$travel->filipino_name}}" placeholder="">
                    <label for="filipino_name">Filipino Name</label>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control video" id="video" name="video" value="{{$travel->video}}" placeholder="">
                    <label for="video">English Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control korean_video" id="korean_video" name="korean_video" value="{{$travel->korean_video}}" placeholder="">
                    <label for="korean_video">Korean Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control spanish_video" id="spanish_video" name="spanish_video" value="{{$travel->spanish_video}}" placeholder="">
                    <label for="spanish_video">Spanish Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control portuguese_video" id="portuguese_video" name="portuguese_video" value="{{$travel->portuguese_video}}" placeholder="">
                    <label for="portuguese_video">Portuguese Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control filipino_video" id="filipino_video" name="filipino_video" value="{{$travel->filipino_video}}" placeholder="">
                    <label for="filipino_video">Filipino Video Link</label>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control srt" name="srt" value="{{$travel->srt}}" placeholder="">
                    <label for="srt">English Subtitle</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control korean_srt"  name="korean_srt" value="{{$travel->korean_srt}}" placeholder="">
                    <label for="korean_srt">Korean Subtitle</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control spanish_srt"  name="spanish_srt" value="{{$travel->spanish_srt}}" placeholder="">
                    <label for="spanish_srt">Spanish Subtitle</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control portuguese_srt"  name="portuguese_srt" value="{{$travel->portuguese_srt}}" placeholder="">
                    <label for="portuguese_srt">Portuguese Subtitle</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control filipino_srt"  name="filipino_srt" value="{{$travel->filipino_srt}}" placeholder="">
                    <label for="filipino_srt">Filipino Subtitle</label>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <select class="form-select featured_video" name="featured_video" aria-label="State">
                    <option value="0" {{($travel->featured_video=='0')?'selected':''}}>No</option>
                    <option value="1" {{($travel->featured_video=='1')?'selected':''}}>Yes</option>
                </select>
                <label for="featured_video">Is Features Video ?</label>
            </div>
        </div>
        <div class="col-md-3">
            <label for="name">Image</label>
            <input class="form-control image" onchange="preview_image();" id="upload" type="file" name="image">
        </div>
        <div class="image_preview" style="display:flex"></div>
        {{-- <div class="col-md-3">
            <label for="name">Web Image</label>
            <input class="form-control web_image" onchange="readURL(this);" type="file" name="web_image">
        </div> --}}
    </div>
    <div class="text-center mt-5">
        <button type="submit" class="btn btn-primary update save-btn">Update</button>
        <button type="button" class="btn btn-danger cancel save-btn">Cancel</button>
    </div>
</form><!-- End floating Labels Form -->
<script src="{{ asset('assets/admin/') }}/js/jquery-1.11.1.min.js" ></script>
<script src="{{ asset('assets/admin/') }}/js/jquery.validate.min.js"></script>