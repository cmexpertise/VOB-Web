<!-- Floating Labels Form -->
<form class="update_form" name="update_form" method="POST" id="update_form" novalidate action="{{route('inspirations.update_video')}}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" class="" name="id" value="{{$inspiration->id}}">
    <input type="hidden" class="inspiration_id" name="inspiration_id" value="{{$inspiration->inspiration_id}}">
    <input type="hidden" class="inspiration_name" name="inspiration_name" value="{{$inspiration->inspiration->name}}">
    <input type="hidden" class="old_image" name="old_image" value="{{$inspiration->image}}">
    <div class="row g-3">
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control name" name="name" id="name" value="{{$inspiration->name}}" placeholder="">
                    <label for="name">English Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control korean_name" id="korean_name" name="korean_name" value="{{$inspiration->korean_name}}" placeholder="">
                    <label for="korean_name">Korean Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control spanish_name" id="spanish_name" name="spanish_name" value="{{$inspiration->spanish_name}}" placeholder="">
                    <label for="spanish_name">Spanish Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control portuguese_name" id="portuguese_name" name="portuguese_name" value="{{$inspiration->portuguese_name}}" placeholder="">
                    <label for="portuguese_name">Portuguese Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control filipino_name" id="filipino_name" name="filipino_name" value="{{$inspiration->filipino_name}}" placeholder="">
                    <label for="filipino_name">Filipino Name</label>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control video" id="video" name="video" value="{{$inspiration->video}}" placeholder="">
                    <label for="video">English Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control korean_video" id="korean_video" name="korean_video" value="{{$inspiration->korean_video}}" placeholder="">
                    <label for="korean_video">Korean Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control spanish_video" id="spanish_video" name="spanish_video" value="{{$inspiration->spanish_video}}" placeholder="">
                    <label for="spanish_video">Spanish Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control portuguese_video" id="portuguese_video" name="portuguese_video" value="{{$inspiration->portuguese_video}}" placeholder="">
                    <label for="portuguese_video">Portuguese Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control filipino_video" id="filipino_video" name="filipino_video" value="{{$inspiration->filipino_video}}" placeholder="">
                    <label for="filipino_video">Filipino Video Link</label>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control srt" name="srt" value="{{$inspiration->srt}}" placeholder="">
                    <label for="srt">English Subtitle</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control korean_srt"  name="korean_srt" value="{{$inspiration->korean_srt}}" placeholder="">
                    <label for="korean_srt">Korean Subtitle</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control spanish_srt"  name="spanish_srt" value="{{$inspiration->spanish_srt}}" placeholder="">
                    <label for="spanish_srt">Spanish Subtitle</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control portuguese_srt"  name="portuguese_srt" value="{{$inspiration->portuguese_srt}}" placeholder="">
                    <label for="portuguese_srt">Portuguese Subtitle</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control filipino_srt"  name="filipino_srt" value="{{$inspiration->filipino_srt}}" placeholder="">
                    <label for="filipino_srt">Filipino Subtitle</label>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <textarea class="form-control description" placeholder="Description" name="description" style="height: 100px;">{{$inspiration->description}}</textarea>
                <label for="description">English Description</label>
            </div>
          </div>
        <div class="col-md-6">
            <div class="form-floating">
                <textarea class="form-control korean_description" placeholder="Description"  name="korean_description" style="height: 100px;">{{$inspiration->korean_description}}</textarea>
                <label for="korean_description">Korean Description</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <textarea class="form-control spanish_description" placeholder="Description" name="spanish_description" style="height: 100px;">{{$inspiration->spanish_description}}</textarea>
                <label for="spanish_description">Spanish Description</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <textarea class="form-control portuguese_description" placeholder="Description"  name="portuguese_description" style="height: 100px;">{{$inspiration->portuguese_description}}</textarea>
                <label for="portuguese_description">Portuguese Description</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <textarea class="form-control filipino_description" placeholder="Description" name="filipino_description" style="height: 100px;">{{$inspiration->filipino_description}}</textarea>
                <label for="filipino_description">Filipino Description</label>
            </div>
        </div>
        <div class="col-md-3">
            <label for="name">Image</label>
            <input class="form-control image" onchange="preview_image();" id="upload" type="file" name="image">
        </div>
        <div class="col-md-3">
            <div class="image_preview" style="display:flex"></div>
        </div>
    </div>
    <div class="text-center mt-5">
        <button type="submit" class="btn btn-primary update save-btn">Update</button>
        <button type="button" class="btn btn-danger cancel save-btn">Cancel</button>
    </div>
</form><!-- End floating Labels Form -->
<script src="{{ asset('assets/admin/') }}/js/jquery-1.11.1.min.js" ></script>
<script src="{{ asset('assets/admin/') }}/js/jquery.validate.min.js"></script>