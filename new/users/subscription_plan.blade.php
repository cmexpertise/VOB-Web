@extends('layouts.user_layout')
@section('content')

<!-- ============ subscribe-plan =============== -->
<section class="plan-wrapper subscribe-plan">
    <div class="container">
        <div class="plan-data">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title text-center">
                        <h2 class="wow pulse" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Subscription Plans</h2>
                        <p class="mx-auto wow fadeIn" data-wow-duration="3s" data-wow-delay="0" data-wow-offset="0">Choose the perfect Subscription Plan for your spiritual exploration. Unlock a world of wisdom and inspiration.</p>
                    </div>
                </div>
                <div class="col-lg-6 mb-lg-0 mb-md-4 mb-sm-4 mb-4">
                    <form action="{{route('payfees')}}" method="post">
                        @csrf
                        <div class="plan-card {{($plan != null && ($plan =='1 Month'))?'active':''}}">
                            <h5 class="sub-heading">Monthly Plan</h5>
                            <div class="plan-offer">
                                <h4>
                                    
                                    <span class="text-transform">$6.99</span>
                                    <sub class="sign">$</sub><span class="rupes">5.99</span>
                                    <sub>per month</sub>
                                </h4>
                            </div>
                            <ul>
                                <li>HD quality available</li>
                                <li>Watch on your PC, Laptop, Phone and Tablet</li>
                                <li>Audio and video (Four Gospels)</li>
                                <li>Cancel anytime</li>
                            </ul>
                            <input type="hidden" id="monthly" name="plan" value="6.99">
                            <input type="hidden" name="type" value="1 Month" class="sr-only" />
                            @if($plan == null)
                                <button class="cmn-btn" type="submit" id="monthlySubscription" name="monthly">Buy Now</button>
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
                                <h4>
                                    <span class="text-transform">$65.99</span>
                                    <sub class="sign">$</sub><span class="rupes">59.99</span>
                                    <sub>per year</sub>
                                </h4>
                            </div>
                            <ul>
                                <li>HD quality available</li>
                                <li>Watch on your PC, Laptop, Phone and Tablet</li>
                                <li>Audio and video (Four Gospels)</li>
                                <li>Cancel anytime</li>
                            </ul>
                            <input type="hidden" id="monthly" name="plan" value="59.99">
                            <input type="hidden" name="type" value="1 Year" class="sr-only" />
                            @if($plan == null)
                            <button type="submit" id="yearlySubscription" name="yearly" class="cmn-btn">Buy Now</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>

</script>

@endsection
@push('script')
@endpush