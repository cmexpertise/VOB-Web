@php
 
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ORI | Payment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />

    <link rel="stylesheet" href="{{ asset('assets/user/') }}/css/style.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" />

    <!-- Stripe JavaScript library -->
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').hide();
                $.session.remove('errormessage');
            }, 3000);
            if (window.matchMedia("(max-width: 767px)").matches) {
                $('#device').val('mobile');
            } else {
                $('#device').val('desktop');
            }
        });
        Stripe.setPublishableKey('{{env("STRIPE_KEY")}}');
        //callback to handle the response from stripe
        function stripeResponseHandler(status, response) {

            if (response.error) {
                //enable the submit button
                $('#payBtn').removeAttr("disabled");
                //display the errors on the form
                // $('#payment-errors').attr('hidden', 'false');
                $('#payment-errors').addClass('alert alert-danger');
                $("#payment-errors").html(response.error.message);
            } else {
                var form$ = $("#paymentFrm");
                //get token id
                var token = response['id'];
                //insert the token into the form
                form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                //submit form to the server
                form$.get(0).submit();
            }
        }
        $(document).ready(function() {
            //on form submit
            $("#paymentFrm").submit(function(event) {
                //disable the submit button to prevent repeated clicks
                $('#payBtn').attr("disabled", "disabled");

                //create single-use token to charge the user
                Stripe.createToken({
                    number: $('#card_num').val(),
                    cvc: $('#card-cvc').val(),
                    exp_month: $('#card-expiry-month').val(),
                    exp_year: $('#card-expiry-year').val()
                }, stripeResponseHandler);

                //submit from callback
                return false;
            });
        });
    </script>
    <!-- <script src="https://js.stripe.com/v3/"></script> -->

    <!-- <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').hide();
            }, 3000);
            if (window.matchMedia("(max-width: 767px)").matches) {
                $('#device').val('mobile');
            } else {
                $('#device').val('desktop');
            }
        });
        var stripe = Stripe("{{env('STRIPE_KEY')}}"); 
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            
            stripe.createPaymentMethod({
            type: 'card',
            card: {
                number: $('#card_num').val(),
                exp_month: $('#card-expiry-month').val(),
                exp_year: $('#card-expiry-year').val(),
                cvc: $('#card-cvc').val()
            }
            }).then(function(result) {
            if (result.error) {
                // Handle error here
                console.error(result.error.message);
            } else {
                fetch("{{route('charge')}}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ payment_method_id: result.paymentMethod.id })
                }).then(function(response) {
                return response.json();
                }).then(function(data) {
                console.log(data);
                });
            }
            });
        });        
    </script> -->
</head>
<body>
    <!-- Bootstrap 4 Navbar  -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a href="#" class="navbar-brand">ORI Subscription Payment </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>
    <!-- End Bootstrap 4 Navbar -->
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-4 mx-auto">
                <div class="card">
                    <div class="card-header bg-danger text-white">Plan Information</div>
                    <div class="card-body bg-light">
                        <div id="payment-errors"></div>
                        <div class="form-group">
                            Payment Amount : $ {{$data['plan']}}
                        </div>
                        @if ($data['errormessage']) <div class="alert alert-danger"> {{$data['errormessage']}}</div>@endif
                        <form method="post" id="paymentFrm" enctype="multipart/form-data" action="{{route('charge')}}">
                            @csrf
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="Name" value="" required>
                            </div>
                            <input type="hidden" name="amount" value="{{$data['plan']}}">
                            <!-- <input type="hidden" class="sr-only" name="mobile" value=""/> -->
                            <input type="hidden" class="sr-only" name="type" value="{{$data['type']}}" />
                            <input type='hidden' name='device' id='device'>
                            <input type='hidden' name='currency' id='currency' value="{{$data['currency']}}">
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="Email" value="" required />
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control" placeholder="Password" value="" required />
                            </div>

                            <div class="form-group">
                                <input type="text" name="card_num" id="card_num" class="form-control" placeholder="Card Number" required maxlength="16">
                            </div>
                            <div class="row">

                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text" name="exp_month" maxlength="2"  class="form-control" id="card-expiry-month" placeholder="MM" value="" required>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text" name="exp_year" class="form-control"  maxlength="4" id="card-expiry-year" value="" placeholder="YYYY" required="">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input type="text" name="cvc" id="card-cvc" maxlength="3" class="form-control" autocomplete="off" placeholder="CVC" value="" required>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="customCheck" name="customCheck" checked value="1">
                                <label class="custom-control-label" for="customCheck">Save Card Details</label>
                            </div> --}}
                            <div class="form-group text-right">
                                <button class="btn btn-secondary" type="reset">Reset</button>
                                <button type="submit" id="payBtn" class="btn btn-danger">Submit Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>