<!-- Floating Labels Form -->
<form class="update_form" name="update_form" method="POST" id="update_form" novalidate action="{{route('nighttimestories.update')}}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" class="story_id" name="id" value="{{$story->id}}">
    <input type="hidden" class="old_image" name="old_image" value="{{$story->image}}">
    <div class="row g-3">
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control name" name="name" id="name" value="{{$story->name}}" placeholder="">
                    <label for="name">English Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control korean_name" id="korean_name" name="korean_name" value="{{$story->korean_name}}" placeholder="">
                    <label for="korean_name">Korean Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control spanish_name" id="spanish_name" name="spanish_name" value="{{$story->spanish_name}}" placeholder="">
                    <label for="spanish_name">Spanish Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control portuguese_name" id="portuguese_name" name="portuguese_name" value="{{$story->portuguese_name}}" placeholder="">
                    <label for="portuguese_name">Portuguese Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control filipino_name" id="filipino_name" name="filipino_name" value="{{$story->filipino_name}}" placeholder="">
                    <label for="filipino_name">Filipino Name</label>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <textarea class="form-control description" placeholder="Description" name="description" style="height: 100px;">{{$story->description}}</textarea>
                <label for="description">English Description</label>
            </div>
          </div>
        <div class="col-md-6">
            <div class="form-floating">
                <textarea class="form-control korean_description" placeholder="Description"  name="korean_description" style="height: 100px;">{{$story->korean_description}}</textarea>
                <label for="korean_description">Korean Description</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <textarea class="form-control spanish_description" placeholder="Description" name="spanish_description" style="height: 100px;">{{$story->spanish_description}}</textarea>
                <label for="spanish_description">Spanish Description</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <textarea class="form-control portuguese_description" placeholder="Description"  name="portuguese_description" style="height: 100px;">{{$story->portuguese_description}}</textarea>
                <label for="portuguese_description">Portuguese Description</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <textarea class="form-control filipino_description" placeholder="Description" name="filipino_description" style="height: 100px;">{{$story->filipino_description}}</textarea>
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