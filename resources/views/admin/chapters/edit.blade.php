<!-- Floating Labels Form -->
<form class="update_form" name="update_form" method="POST" id="update_form" novalidate action="{{route('chapters.update')}}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" class="chapter_id" name="id" value="{{$chapter->id}}">
    <input type="hidden" class="old_video_image" name="old_video_image" value="{{$chapter->video_image}}">
    <input type="hidden" class="old_audio_image" name="old_audio_image" value="{{$chapter->audio_image}}">
    <div class="row g-3">
        <div class="col-md-2">
            <div class="form-floating">
                <select class="form-select book_id" name="book_id" id="book_id" aria-label="State">
                    <option value="">Select Book</option>
                    @foreach ($books as $book)
                        <option value="{{$book->id}}" {{($chapter->book_id==$book->id)?'selected':''}}>{{$book->name}}</option>
                    @endforeach
                </select>
                <label for="book_id">Select Book</label>
            </div>
        </div>
        <div class="col-md-2">
            <label for="name">Video Image</label>
            <input class="form-control video_image" onchange="preview_image();" id="upload" type="file" name="video_image">
        </div>
        <div class="col-md-2">
            <label for="name">Audio Image</label>
            <input class="form-control audio_image" onchange="preview_image();" id="upload" type="file" name="audio_image">
        </div>
        <div class="col-md-2">
            <div class="image_preview" style="display:flex;height:0px"></div>
        </div>
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control name" name="name" id="name" value="{{$chapter->name}}" placeholder="">
                    <label for="name">English Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control korean_name" id="korean_name" name="korean_name" value="{{$chapter->korean_name}}" placeholder="">
                    <label for="korean_name">Korean Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control spanish_name" id="spanish_name" name="spanish_name" value="{{$chapter->spanish_name}}" placeholder="">
                    <label for="spanish_name">Spanish Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control portuguese_name" id="portuguese_name" name="portuguese_name" value="{{$chapter->portuguese_name}}" placeholder="">
                    <label for="portuguese_name">Portuguese Name</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control filipino_name" id="filipino_name" name="filipino_name" value="{{$chapter->filipino_name}}" placeholder="">
                    <label for="filipino_name">Filipino Name</label>
                </div>
            </div>
            
        </div>
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control video" id="video" name="video" title="{{$chapter->video}}" value="{{$chapter->video}}" placeholder="">
                    <label for="video">English Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control korean_video" id="korean_video" name="korean_video" title="{{$chapter->korean_video}}" value="{{$chapter->korean_video}}" placeholder="">
                    <label for="korean_video">Korean Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control spanish_video" id="spanish_video" name="spanish_video" title="{{$chapter->spanish_video}}" value="{{$chapter->spanish_video}}" placeholder="">
                    <label for="spanish_video">Spanish Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control portuguese_video" id="portuguese_video" name="portuguese_video" title="{{$chapter->portuguese_video}}" value="{{$chapter->portuguese_video}}" placeholder="">
                    <label for="portuguese_video">Portuguese Video Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control filipino_video" id="filipino_video" name="filipino_video" title="{{$chapter->filipino_video}}" value="{{$chapter->filipino_video}}" placeholder="">
                    <label for="filipino_video">Filipino Video Link</label>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control audio" name="audio" value="{{$chapter->audio}}" placeholder="">
                    <label for="audio">English Audio Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control korean_audio"  name="korean_audio" value="{{$chapter->korean_audio}}" placeholder="">
                    <label for="korean_audio">Korean Audio Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control spanish_audio"  name="spanish_audio" value="{{$chapter->spanish_audio}}" placeholder="">
                    <label for="spanish_audio">Spanish Audio Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control portuguese_audio"  name="portuguese_audio" value="{{$chapter->portuguese_audio}}" placeholder="">
                    <label for="portuguese_audio">Portuguese Audio Link</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control filipino_audio"  name="filipino_audio" value="{{$chapter->filipino_audio}}" placeholder="">
                    <label for="filipino_audio">Filipino Audio Link</label>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control srt" name="srt" value="{{$chapter->srt}}" placeholder="">
                    <label for="srt">English Subtitle</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control korean_srt"  name="korean_srt" value="{{$chapter->korean_srt}}" placeholder="">
                    <label for="korean_srt">Korean Subtitle</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control spanish_srt"  name="spanish_srt" value="{{$chapter->spanish_srt}}" placeholder="">
                    <label for="spanish_srt">Spanish Subtitle</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control portuguese_srt"  name="portuguese_srt" value="{{$chapter->portuguese_srt}}" placeholder="">
                    <label for="portuguese_srt">Portuguese Subtitle</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="form-control filipino_srt"  name="filipino_srt" value="{{$chapter->filipino_srt}}" placeholder="">
                    <label for="filipino_srt">Filipino Subtitle</label>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-floating">
                <textarea class="form-control description" placeholder="Description" name="description" style="height: 100px;">{{$chapter->description}}</textarea>
                <label for="description">English Description</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <textarea class="form-control korean_description" placeholder="Description"  name="korean_description" style="height: 100px;">{{$chapter->korean_description}}</textarea>
                <label for="korean_description">Korean Description</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <textarea class="form-control spanish_description" placeholder="Description" name="spanish_description" style="height: 100px;">{{$chapter->spanish_description}}</textarea>
                <label for="spanish_description">Spanish Description</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <textarea class="form-control portuguese_description" placeholder="Description"  name="portuguese_description" style="height: 100px;">{{$chapter->portuguese_description}}</textarea>
                <label for="portuguese_description">Portuguese Description</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <textarea class="form-control filipino_description" placeholder="Description" name="filipino_description" style="height: 100px;">{{$chapter->filipino_description}}</textarea>
                <label for="filipino_description">Filipino Description</label>
            </div>
        </div>
    </div>
    <div class="text-center mt-5">
        <button type="submit" class="btn btn-primary update save-btn">Update</button>
        <button type="button" class="btn btn-danger cancel save-btn">Cancel</button>
    </div>
</form><!-- End floating Labels Form -->
<script src="{{ asset('assets/admin/') }}/js/jquery-1.11.1.min.js" ></script>
<script src="{{ asset('assets/admin/') }}/js/jquery.validate.min.js"></script>

