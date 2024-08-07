<form class="row g-3 update_form" name="update_form" method="POST" id="update_form" novalidate action="{{route('affiliates.update')}}" enctype="multipart/form-data">
    @csrf
<!-- Floating Labels Form -->
    <input type="hidden" name="id" value="{{$affiliate->id}}">
    <input type="hidden" name="old_password" value="{{$affiliate->password}}">
    <div class="col-md-4">
        <div class="form-floating">
            <input type="text" class="form-control name" name="name" value="{{$affiliate->name}}" placeholder="">
            <label for="name">Affilate Name</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-floating">
            <input type="email" class="form-control email"  name="email" value="{{$affiliate->email}}" placeholder="">
            <label for="email">Email</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-floating">
            <input type="password" class="form-control password" name="password" placeholder="">
            <label for="password">Password</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-floating">
            <select class="form-select country_code" name="country_code" aria-label="State">
                <option value="">Select Country Code</option>
                @foreach ($country_code as $key => $code)
                    <option value="{{$key}}" {{($affiliate->country_code==$key)?'selected':''}} >{{$code}}</option>
                @endforeach
            </select>
            <label for="country_code">Country Code</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-floating">
            <input type="number" class="form-control mobile" value="{{$affiliate->mobile}}" name="mobile"  placeholder="">
            <label for="mobile">Mobile Number</label>
        </div>
    </div>
    <div class="col-4">
        <div class="form-floating">
            <input type="number" class="form-control percentage" value="{{$affiliate->percentage}}" name="percentage" placeholder="">
            <label for="percentage">Affilate Percentage</label>
        </div>
      </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary save-btn">Update</button>
        <button type="button" class="btn btn-danger cancel save-btn">Cancel</button>
    </div>
</form><!-- End floating Labels Form -->