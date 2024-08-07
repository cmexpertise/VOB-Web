@extends('layouts.user_layout')
@section('content')

<!-- =========== about-ori ============ -->
<section class="about-ori">
    <div class="container">
        <div class="row">
            <div class="col-xxl-12 col-xl-12 col-lg-12">
                <div class="title text-center">
                    <h2 class="wow pulse" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">About ORI</h2>
                    <p class="mx-auto wow fadeIn" data-wow-duration="3s" data-wow-delay="0" data-wow-offset="0">We are a collective of filmmakers, historians, members of clergy pastors, rabbis,
                        ministers, etc.</p>
                </div>
                <div class="about-img">
                    <img src="{{ asset('assets/user/') }}/img/about/about-img.png" alt="about-img">
                </div>
                <div class="about-content text-center">
                    <h3 class="mb-4 wow fadeInDown" data-wow-duration="4s" data-wow-delay="0" data-wow-offset="0">We are a collective of filmmakers, historians, members of clergy (pastors, rabbis, ministers, etc.), different denominations, who are committed to spreading the word of the bible.</h3>
                    <p class="mb-4 wow fadeInDown" data-wow-duration="5s" data-wow-delay="0" data-wow-offset="0">In our travels and throughout our lives we discovered that so many people expressed interest in the bible, but never really had a chance to learn or understand the stories in a lasting and memorable way. People, young and old, didn’t know much about the bible, and they didn’t read it because it was challenging. And listening to the stories didn’t seem to be enough either because there was no image to connect to. We noticed they lacked the information and the tools, and everyone seemed to be hungry for the knowledge and inspiration. People shared their desire to visit places that are referenced in the bible, but perhaps a lack of money or time prevented them from having the opportunity.</p>
                    <p class="wow fadeInDown" data-wow-duration="6s" data-wow-delay="0" data-wow-offset="0">We saw the need and decided to embark on a journey of collecting images to use as powerful learning tools, but not just still images. It was important to us to film the locations and use the moving image to create a lasting impression. Re-enactments are entertaining, but filming different angles, aerial photography, and capturing hard to reach places made a huge difference in making learning exciting. The video camera could provide a perspective so unique, showing what we normally cannot see and take us to places we normally cannot physically experience.</p>
                </div>
                <div class="our-mission text-center">
                    <h3>Our Mission</h3>
                    <p class="mb-0">We are committed to helping people all over the world learn the bible in a way not done before, where they can travel to the actual historical places and see them through the eyes of the camera, going from words on a page to a real place. We want to unite people, friends and family, and, through scripture, music, image and words, create a complete experience of the bible that they can take with them everywhere they go.</p>
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

@endsection
@push('script')
@endpush