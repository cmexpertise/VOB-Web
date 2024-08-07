@php
    $user = Auth::user();
@endphp
<header class="page-header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="{{route('home')}}">
                <img src="{{ asset('assets/user/') }}/img/header/header-logo.png" alt="logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" id="menu">
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0" id="navbar">
                    <li class="nav-item">
                        <a class="nav-link {{(Request::segment(1) == 'home')?'active':''}}" aria-current="page" href="{{route('home')}}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{(Request::segment(1) == 'book_of_the_bible')?'active':''}}" href="{{route('book_of_the_bible')}}">Books of the Bible </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{(Request::segment(1) == 'audio_books')?'active':''}}" href="{{route('audio_books')}}">Audio Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{(Request::segment(1) == 'travelSamaritan')?'active':''}}" href="{{route('travelSamaritan')}}">Travel Samaritan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{(Request::segment(1) == 'subscription_plan')?'active':''}}" href="{{route('subscription_plan')}}">Memberships</a>
                    </li>
                </ul>
            </div>
            <div class="d-flex header-right">
                <div id="language" class="select-label d-none">
                  
                </div>
                <div id="language_custom" class="select-label">
                   <div class="skiptranslate goog-te-gadget" dir="ltr" style=""><div id=":0.targetLanguage"><select class="goog-te-combo" aria-label="Language Translate Widget"><option value="">Select Language</option><option value="en">English</option><option value="tl">Filipino</option><option value="ko">Korean</option><option value="pt">Portuguese</option><option value="es">Spanish</option></select></div>Powered by <span style="white-space:nowrap"><a class="VIpgJd-ZVi9od-l4eHX-hSRGPd" href="https://translate.google.com" target="_blank"><img src="https://www.gstatic.com/images/branding/googlelogo/1x/googlelogo_color_42x16dp.png" width="37px" height="14px" style="padding-right: 3px" alt="Google Translate">Translate</a></span></div>
                </div>
                <div class="login-sign dropdown">
                    <a href="@if (isset($user)){{'#'}}@else{{route('signin')}}@endif" 
                        class="d-flex dropdown-toggle" @if (isset($user)) data-bs-toggle="dropdown" @endif  aria-expanded="false">
                        <i class="fa-regular fa-user"></i>
                        <h5>@if (isset($user))
                            {{$user->name}}
                        @else
                            Login
                        @endif</h5>
                    </a>
                    <ul class="dropdown-menu">
                        @if (isset($user))
                            {{-- <li><a class="dropdown-item" href="#"><i class="fa-regular fa-user"></i>View Profile</a></li> --}}
                            
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item d-flex align-items-center" href="{{route('logout')}}" onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                      <span>Log Out</span>
                                    </a>
                                </form>
                            </li>
                                {{-- <a class="dropdown-item" href="#"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></li> --}}
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>

