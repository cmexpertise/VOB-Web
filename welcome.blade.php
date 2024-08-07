@extends('layouts.user_layout')
@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/video.js/5.10.2/alt/video-js-cdn.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/5.10.2/video.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/3.0.2/videojs-contrib-hls.js"></script>

<!-- ============= banner-wrapper ======== -->
<section class="banner-wrapper">
    <img src="{{ asset('assets/user/') }}/img/home/about-prayer.png" alt="left-banner" class="left-img wow fadeInLeft" data-wow-duration="5s" data-wow-delay="0" data-wow-offset="0">
    <img src="{{ asset('assets/user/') }}/img/home/about-prayer.png" alt="right-banner" class="right-img wow fadeInRight" data-wow-duration="5s" data-wow-delay="0" data-wow-offset="0">
    <div class="container">
        <div class="banner-content">
            <h1 class="wow fadeInDown" data-wow-duration="1s" data-wow-delay="0" data-wow-offset="0"><h1 class="mb-0 wow pulse" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0" style="visibility: visible; animation-duration: 2s; animation-name: pulse;">Explore the Holy Land: <span class="wow bounceInLeft" data-wow-duration="3s" data-wow-delay="0" data-wow-offset="0" style="visibility: visible; animation-duration: 3s; animation-name: bounceInLeft;">Bible's Visual Journey</span></h1></h1>
            <p class="wow fadeInDown" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Uncover the wonders of the Holy Land through inspiring visual adventures. Join our cinematic journey through all 66 books, guided tours, and daily inspiration.
                Deepen your spiritual connection with the ORI - Vision of the Bible. Experience the Bible like never before !</p>
            <div><h4 class="text-center app-store-title">Download App Now</h4>
            <div class="app-store d-flex justify-content-center gap-2">
                <a href="https://play.google.com/store/apps/details?id=com.obs.oribible&hl=en_IN" class="google-app">
                    <img src="{{ asset('assets/user/') }}/img/btn_playstore.svg" alt="google-app">
                </a>
                <a href="https://apps.apple.com/in/app/vision-of-the-bible/id1570619920" class="mobile-app">
                    <img src="{{ asset('assets/user/') }}/img/btn_appstore.svg" alt="mobile-app">
                </a>
            </div>
            </div>
        </div>
        <div class="banner-img">
            <img src="{{ asset('assets/user/') }}/img/home/bannner-img.png" alt="birthday-card">
        </div>
    </div>
</section>

<!-- ======== about-wrapepr section ======== -->
<section class="about-wrapepr">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="title text-center">
                    <h2 class="wow fadeInDown" data-wow-duration="6s" data-wow-delay="0" data-wow-offset="0">About ORI</h2>
                    <p class="mx-auto wow fadeIn" data-wow-duration="7s" data-wow-delay="0" data-wow-offset="0">Where faith and history intertwine, unveiling the Holy Land's secrets through mesmerizing cinematic experiences.</p>
                </div>
            </div>
            <div class="col-lg-6 mb-lg-0 mb-md-4 mb-sm-4">
                <div class="about-img">
                    <img src="{{ asset('assets/user/') }}/img/about/about-img.jpeg" alt="about-img">
                </div>
            </div>
            <div class="col-lg-6 mt-4">
                <div class="about-content">
                    <h3>Our team includes<span> filmmakers, historians, and clergy </span> from different denominations, all committed to sharing the wisdom of the Bible with everyone.</h3>
                    <p class="mb-3 mt-2">Discover the stories of all 66 books of the Bible like never before with our cinematic presentations. We make learning engaging and memorable by using moving footage, aerial shots, and beautiful locations. Join our guided tour series, Travel Samaritan, to virtually experience sacred sites like Jerusalem and Capernaum. We know how important it is to understand the Bible, so we've created a complete experience with scripture, music, images, and words. Whether you're young or old, our journey will deepen your faith and knowledge.</p>
                    <p class="mb-3">Come with us on this transformative quest to explore the Holy Land and connect with its timeless wisdom. Together, let's embark on a journey of faith and discovery that you can carry with you wherever you go. Start your adventure with ORI Vision of the Bible today and experience the power of the scriptures firsthand.</p>
                    <a href="#" type="button" class="cmn-btn wow fadeInRight" data-wow-duration="9s" data-wow-delay="0" data-wow-offset="0">Explore More</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ Broadcasts-wrapper ============ -->
<section class="stories-wrapper p-100 pb-0">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title text-center">
                    <h2 class="wow fadeInDown" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Broadcasts</h2>
                    {{-- <p class="mx-auto wow fadeIn" data-wow-duration="3s" data-wow-delay="0" data-wow-offset="0">Discover captivating stories that reflect the human experience. Explore life's wonders and timeless wisdom.</p> --}}
                </div>
            </div>
        </div>
        <div class="stories-slider owl-carousel owl-theme">
            @foreach ($broadcasts as $story)
                <div class="item">
                        
                    <a href="{{route('broadcastList',['id' => encrypt($story->id)])}}">
                        <div class="book-card">
                            <div class="book-img">
                                <img src="{{asset("storage/".$story->image)}}" class="" alt="story-img">
                            </div>
                            <h3>{{$story->name}}</h3>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ========= books-wrapper section ========== -->
<section class="books-wrapper p-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="sub-title d-flex justify-content-between align-items-center">
                    <div class="title mb-0 mb-sm-3 mb-3">
                        <h2 class="wow fadeIn" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Explore Books: Tales Of The Bible</h2>
                        <p class="wow fadeInLeft" data-wow-duration="4s" data-wow-delay="0" data-wow-offset="0">Unravel the Bible's tales, chapter by chapter, with our cinematic presentations of all 66 books. Journey now!</p>
                    </div>
                    <div class="view-link">
                        <a href="{{route('book_of_the_bible')}}" class="wow fadeInRight" data-wow-duration="5s" data-wow-delay="0" data-wow-offset="0">View All</a>
                    </div>
                </div>
            </div>
            <div class="book-slider owl-carousel owl-theme">
                @foreach ($books as $book)
                <div class="item">
                    <a href="{{route('allChapters',['book' => encrypt($book->id)])}}">
                        <div class="book-card">
                            <div class="book-img">
                                <img src="{{asset("storage/".$book->video_image)}}" class="" alt="book-img">
                            </div>
                            <h3>{{$book->name}}</h3>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ======== about-prayers section ======== -->
<section class="about-prayers">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mt-4 mb-md-4 mb-sm-4 mb-4">
                <div class="about-content">
                    <h5 class="sub-heading">Prayer As a Daily Habit</h5>
                    <h2>Cultivate The Powerful Practice Of Morning Prayers.</h2>
                    <p class="mb-3 mt-2">Embracing gratitude and connecting with the divine in the early hours can set a positive tone for the entire day. Here, you will find a collection of heartfelt morning prayers carefully curated to inspire your spiritual journey. Each prayer is crafted to nurture a grateful heart, foster inner peace, and invite guidance for the day ahead. Take a moment each morning to pause, reflect, and offer your thoughts to the universe, a higher power, or the source of your faith. These moments of stillness and devotion can empower you, infusing your actions and decisions with purpose and clarity. </p>
                    <p class="mb-3">Start your day on a meaningful note by engaging in these morning prayers that echo the wisdom of ancient traditions and embrace the universal yearning for spiritual growth. May these prayers be a source of strength and solace as you walk through life with grace and gratitude. Embrace the beauty of each morning, and let your heart be filled with hope and positivity.</p>
                    <a href="{{route('book_of_the_bible')}}" type="button" class="cmn-btn wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Read Books</a>
                </div>
            </div>
            <div class="col-lg-6 mb-lg-0">
                <div class="about-img wow fadeInRight" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">
                    <img src="{{ asset('assets/user/') }}/img/home/about-prayer.png" alt="prayer-img">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========= life-changing section ========== -->
<section class="books-wrapper p-100 life-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="sub-title d-flex justify-content-between align-items-center">
                    <div class="title mb-0 mb-sm-3 mb-3">
                        <h2 class="wow fadeIn" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Life-Changing Quotes</h2>
                        <p class="mx-auto wow fadeInLeft" data-wow-duration="4s" data-wow-delay="0" data-wow-offset="0">Empower your soul with motivational and inspiring Quotes. Inspire, uplift, and empower your journey with timeless truths.</p>
                    </div>
                    <div class="view-link">
                        <a href="{{route('download_app')}}" class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">View All</a>
                    </div>
                </div>
            </div>
            <div class="book-slider owl-carousel owl-theme">
                @foreach ($quotes as $quote )
                    <div class="item ">
                        <a href="{{route('download_app')}}" >
                            <div class="magnific-img">
                                <img src="{{asset("storage/".$quote->image)}}" class="" alt="life-img">
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ============== motivation-wrapper ============ -->
<section class="motivation-wrapper">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-md-4 mb-sm-4 mb-4">
                <div class="motication-img-wrap">
                    <div class="motication-img wow fadeInLeft" data-wow-duration="6s" data-wow-delay="0" data-wow-offset="0">
                        <img src="{{ asset('assets/user/') }}/img/home/motivation-mobile-img.png" alt="motivation-img">
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="motivation-content">
                    <h5 class="sub-heading">Inspirational Prayer Quotes</h5>
                    <h2>Positive And Motivational Sayings To Inspire Your Spiritual Journey</h2>
                    <p class="mb-3 mt-2">Welcome to ORI's collection of Inspirational Prayer Quotes, meant to uplift your spirit and guide your spiritual journey. These positive sayings capture the essence of prayer and offer wisdom for your life. Each quote reminds us of the power of prayer to find comfort, seek guidance, and feel grateful. These words will inspire you during tough times and encourage you to stay connected to your faith.</p>
                    <p class="mb-3">Whether you need support, seek answers, or simply want to feel uplifted, these prayer quotes will provide motivation. Keep them close, reflect on them, and allow them to enrich your connection with the divine.</p>
                    <a href="#" type="button" class="cmn-btn wow fadeInRight" data-wow-duration="6s" data-wow-delay="0" data-wow-offset="0">Explore Quotes</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ stories-wrapper ============ -->
<section class="stories-wrapper p-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title text-center">
                    <h2 class="wow fadeInDown" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Explore Stories</h2>
                    <p class="mx-auto wow fadeIn" data-wow-duration="3s" data-wow-delay="0" data-wow-offset="0">Discover captivating stories that reflect the human experience. Explore life's wonders and timeless wisdom.</p>
                </div>
            </div>
        </div>
        <div class="stories-slider owl-carousel owl-theme">
            @foreach ($night_time_stories as $story)
                <div class="item">
                        
                    <a href="{{route('nighttimestoryList',['id' => encrypt($story->id)])}}">
                        <div class="book-card">
                            <div class="book-img">
                                <img src="{{asset("storage/".$story->image)}}" class="" alt="story-img">
                            </div>
                            <h3>{{$story->name}}</h3>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ======== about-prayers section ======== -->
<section class="about-prayers audio-prayers">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mt-4 mb-md-4 mb-sm-4 mb-4">
                <div class="about-content">
                    <h5 class="sub-heading">ORI's Daily Morning Prayers Audio</h5>
                    <h2>Designed To Bring Spirituality To Your Mornings Effortlessly.</h2>
                    <p class="mb-3 mt-2">Welcome to ORI's Daily Morning Prayers Audio, where you'll find soul-nourishing meditations. With our wide range of audio prayers, you can easily access soul-nourishing meditations, chants, and reflections. Start your day with peace and purpose as you listen to heartfelt prayers that uplift your soul. Whether you prefer moments of silence or guided prayers, our collection suits your spiritual preferences.</p>
                    <p class="mb-3">We believe in the power of auditory inspiration, and our prayers are carefully chosen to provide a meaningful experience. Listen while sipping your morning coffee, on your commute, or during moments of reflection - let our Daily Morning Prayers Audio be your companion in seeking spiritual connection. Begin each day with tranquility and devotion, and let our audio prayers guide you to inner peace and spiritual growth. </p>
                    <p class="mb-3">Enjoy this effortless way to embrace spiritual moments in your daily routine. Let our guided prayers be your daily inspiration.</p>
                    <a href="{{route('audio_books')}}" type="button" class="cmn-btn fadeInLeft" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Listen Audio</a>
                </div>
            </div>
            <div class="col-lg-6 mb-lg-0">
                <div class="about-img" >
                    <img src="{{ asset('assets/user/') }}/img/home/about-prayer-2.png" alt="about-prayer-img" class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ Broadcasts-wrapper ============ -->
<section class="stories-wrapper p-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title text-center">
                    <h2 class="wow fadeInDown" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Meditations</h2>
                    {{-- <p class="mx-auto wow fadeIn" data-wow-duration="3s" data-wow-delay="0" data-wow-offset="0">Discover captivating stories that reflect the human experience. Explore life's wonders and timeless wisdom.</p> --}}
                </div>
            </div>
        </div>
        <div class="stories-slider owl-carousel owl-theme">
            @foreach ($meditations as $story)
                <div class="item">
                        
                    <a href="{{route('meditationList',['id' => encrypt($story->id)])}}">
                        <div class="book-card">
                            <div class="book-img">
                                <img src="{{asset("storage/".$story->image)}}" class="" alt="story-img">
                            </div>
                            <h3>{{$story->name}}</h3>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ========= life-changing section ========== -->
<section class="books-wrapper life-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="sub-title d-flex justify-content-between align-items-center">
                    <div class="title mb-0 mb-sm-3 mb-3">
                        <h2 class="wow fadeIn" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Postcards</h2>
                        {{-- <p class="mx-auto wow fadeInLeft" data-wow-duration="4s" data-wow-delay="0" data-wow-offset="0">Empower your soul with motivational and inspiring Quotes. Inspire, uplift, and empower your journey with timeless truths.</p> --}}
                    </div>
                    <div class="view-link">
                        <a href="{{route('download_app')}}" class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">View All</a>
                    </div>
                </div>
            </div>
            <div class="book-slider owl-carousel owl-theme">
                @foreach ($postcards as $quote )
                    <div class="item ">
                        <a href="{{route('download_app')}}">
                            <div class="magnific-img">
                                <img src="{{asset("storage/".$quote->image)}}" class="" alt="life-img">
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ======== plan-wrapper section ======== -->
<section class="plan-wrapper p-100">
    <div class="container">
        <div class="plan-data">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title text-center">
                        <h2 class="wow fadeInDown" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Pricing Plans</h2>
                        <p class="mx-auto wow fadeIn" data-wow-duration="3s" data-wow-delay="0" data-wow-offset="0">Explore our flexible and affordable pricing plans. Choose the perfect option for your spiritual journey.</p>
                    </div>
                </div>
                <div class="col-lg-6 mb-lg-0 mb-md-4 mb-sm-4 mb-4">
                    <form action="{{route('payfees')}}" method="post">
                        @csrf
                        <div class="plan-card {{($plan != null && ($plan =='1 Month'))?'active':''}}">
                            <h5 class="sub-heading">Monthly Plan</h5>
                            <div class="plan-offer">
                                <div class="d-flex align-items-center">
                                    <span class="text-transform">$7.99</span>
                                    <h3><span class="sign">$</span>6.99</h3>
                                    <sub>per month</sub>
                                </div>
                            </div>
                            <input type="hidden" id="monthly" name="plan" value="6.99">
                            <input type="hidden" name="type" value="1 Month" class="sr-only" />
                            @if($plan == null)
                            <button type="submit" class="cmn-btn wow fadeInLeft" data-wow-duration="4s" data-wow-delay="0" data-wow-offset="0" id="monthlySubscription" name="monthly">Buy Now</button>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="col-lg-6">
                    <form action="{{route('payfees')}}" method="post">
                        @csrf
                        <div class="plan-card {{($plan != null && ($plan =='1 Year'))?'active':''}}">
                            <h5 class="sub-heading">Yearly Plan</h5>
                            <div class="plan-offer">
                                <div class="d-flex align-items-center">
                                    <span class="text-transform">$69.99</span>
                                    <h3><span class="sign">$</span>59.99</h3>
                                    <sub>per year</sub>
                                </div>
                            </div>
                            <input type="hidden" id="monthly" name="plan" value="59.99">
                            <input type="hidden" name="type" value="1 Year" class="sr-only" />
                            @if($plan == null)
                            <button type="submit" class="cmn-btn wow fadeInLeft" data-wow-duration="4s" data-wow-delay="0" data-wow-offset="0" id="monthlySubscription" name="monthly">Buy Now</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- ======== FAQ section ======== -->
<section class="faq">
    <div class="container">
        <div class="row">
            <div class="col-xxl-12 col-xl-12 col-lg-12">
                <div class="title text-center">
                    <h2 class="wow fadeInDown" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Frequently Asked Questions</h2>
                    <p class="mx-auto wow fadeIn" data-wow-duration="3s" data-wow-delay="0" data-wow-offset="0">We offer plans to match your goals. Add features as you grow, or get all the tools you need at once.</p>
                </div>
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item wow fadeIn" data-wow-duration="4s" data-wow-delay="0" data-wow-offset="0">
                        <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <h4>What is ORI?</h4>
                        </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <P>ORI Vision of the Bible puts the Holy Land at your fingertips. Experience the places where Biblical history transpired, as they exist today. Experience the richness of the land, the people,and the many cultures that are expressed in and around the region. This website features:Cinematic presentations of each of the sixty-six books of the Bible, filmed over the course of seven years and artfully narrated; Audio-only versions of all sixty-six books; Travel Samaritan,a guided tour series of sacred sites and regions like Jerusalem and Capernaum; DailyInspiration.</P>
                        </div>
                        </div>
                    </div>
                    <div class="accordion-item wow fadeIn" data-wow-duration="5s" data-wow-delay="0" data-wow-offset="0">
                        <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h4>How much does ORI cost?</h4>
                        </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <P>ORI Vision of the Bible puts the Holy Land at your fingertips. Experience the places where Biblical history transpired, as they exist today. Experience the richness of the land, the people,and the many cultures that are expressed in and around the region. This website features:Cinematic presentations of each of the sixty-six books of the Bible, filmed over the course of seven years and artfully narrated; Audio-only versions of all sixty-six books; Travel Samaritan,a guided tour series of sacred sites and regions like Jerusalem and Capernaum; DailyInspiration.</P>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item wow fadeIn" data-wow-duration="6s" data-wow-delay="0" data-wow-offset="0">
                        <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <h4>Where can I watch?</h4>
                        </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <P>ORI Vision of the Bible puts the Holy Land at your fingertips. Experience the places where Biblical history transpired, as they exist today. Experience the richness of the land, the people,and the many cultures that are expressed in and around the region. This website features:Cinematic presentations of each of the sixty-six books of the Bible, filmed over the course of seven years and artfully narrated; Audio-only versions of all sixty-six books; Travel Samaritan,a guided tour series of sacred sites and regions like Jerusalem and Capernaum; DailyInspiration.</P>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item wow fadeIn" data-wow-duration="7s" data-wow-delay="0" data-wow-offset="0">
                        <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            <h4>How do I cancel?</h4>
                        </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <P>ORI Vision of the Bible puts the Holy Land at your fingertips. Experience the places where Biblical history transpired, as they exist today. Experience the richness of the land, the people,and the many cultures that are expressed in and around the region. This website features:Cinematic presentations of each of the sixty-six books of the Bible, filmed over the course of seven years and artfully narrated; Audio-only versions of all sixty-six books; Travel Samaritan,a guided tour series of sacred sites and regions like Jerusalem and Capernaum; DailyInspiration.</P>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item wow fadeIn" data-wow-duration="8s" data-wow-delay="0" data-wow-offset="0">
                        <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            <h4>What can I watch on ORI?</h4>
                        </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <P>ORI Vision of the Bible puts the Holy Land at your fingertips. Experience the places where Biblical history transpired, as they exist today. Experience the richness of the land, the people,and the many cultures that are expressed in and around the region. This website features:Cinematic presentations of each of the sixty-six books of the Bible, filmed over the course of seven years and artfully narrated; Audio-only versions of all sixty-six books; Travel Samaritan,a guided tour series of sacred sites and regions like Jerusalem and Capernaum; DailyInspiration.</P>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item wow fadeIn" data-wow-duration="9s" data-wow-delay="0" data-wow-offset="0">
                        <h2 class="accordion-header" id="headingSix">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            <h4>What do I do if I need further assistance?</h4>
                        </button>
                        </h2>
                        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <P>ORI Vision of the Bible puts the Holy Land at your fingertips. Experience the places where Biblical history transpired, as they exist today. Experience the richness of the land, the people,and the many cultures that are expressed in and around the region. This website features:Cinematic presentations of each of the sixty-six books of the Bible, filmed over the course of seven years and artfully narrated; Audio-only versions of all sixty-six books; Travel Samaritan,a guided tour series of sacred sites and regions like Jerusalem and Capernaum; DailyInspiration.</P>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========= coming-soon ====== -->

@endsection
@push('script')
@endpush