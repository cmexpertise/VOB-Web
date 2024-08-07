<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\ContactUs;
use App\Models\Daily_prayer;
use App\Models\Meditation;
use App\Models\Night_time_story_video;
use App\Models\Postcard;
use App\Models\Quote;
use App\Models\Travel_samaritan;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;


class WebController extends Controller
{
    public function __construct()
    {
        
    }

    public function index(){
        $books = Book::limit(10)->get();
        $quotes = Quote::limit(10)->get();
        $broadcasts = Daily_prayer::get();
        $night_time_stories = Night_time_story_video::limit(10)->get();
        $meditations = Meditation::limit(10)->orderBy('id','DESC')->get();
        $postcards = Postcard::limit(10)->orderBy('id','DESC')->get();
        return view('welcome',compact('books','quotes','night_time_stories','broadcasts','meditations','postcards'));
    }

    public function book_of_the_bible(){
        $books = Book::all();
        return view('users/book_of_the_bible',compact('books'));
    }

    public function audio_books(){
        $books = Book::all();
        return view('users.audio_books',compact('books'));
    }

    public function travelSamaritan(){
        $travelsamaritans = Travel_samaritan::all();
        return view('users.travel_samaritan',compact('travelsamaritans'));
    }

    public function subscription_plan(){
        return view('users.subscription_plan');
    }

    public function signin(){
        return view('auth.user_login');
    }

    public function allChapters(Request $request){
        $book_id = decrypt($request->book);
        $chapters = Chapter::with(['book' => function($query){
            $query->select('id','name','description');
        }])
            ->select('name','id','book_id','video_image','description','video','korean_video','spanish_video','portuguese_video','filipino_video','srt')
            ->where('book_id',$book_id)->get();
        return view('users.allChapters',compact('chapters','book_id'));
    }
    
    public function allAudios(Request $request){
        $book_id = decrypt($request->book);
        $chapters = Chapter::with(['book' => function($query){
            $query->select('id','name','description');
        }])
            ->select('name','id','book_id','video_image','description','audio','korean_audio','spanish_audio','portuguese_audio','filipino_audio','srt')
            ->where('book_id',$book_id)->get();
        return view('users.allAudios',compact('chapters','book_id'));
    }

    public function travelList(Request $request){
        $travel = decrypt($request->id);
        $travels = Travel_samaritan::where('id',$travel)->first();
        return view('users.travelList',compact('travels'));
    }

    public function nighttimestoryList(Request $request){
        $night_story_id = decrypt($request->id);
        $night_story = Night_time_story_video::where('night_time_story_id',$night_story_id)->first();
        return view('users.nighttimestoryList',compact('night_story'));
    }
    
    public function broadcastList(Request $request){
        $night_story_id = decrypt($request->id);
        $night_story = Daily_prayer::where('id',$night_story_id)->first();
        return view('users.nighttimestoryList',compact('night_story'));
    }

    public function meditationList(Request $request){
        $night_story_id = decrypt($request->id);
        $night_story = Meditation::where('id',$night_story_id)->first();
        return view('users.meditationList',compact('night_story'));
    }



    public function download_app(){
        return view('users.download_app');
    }

    public function payfees(Request $request){
        $data['amount'] = $request->amount;
		$data['errormessage'] = "";
		$data['plan'] = $request->plan;
		$data['type'] = $request->type;
		$data['pkKey'] = env('STRIPE_SECRET');
		$data['affiliate_code'] = "";
		$ip = $this->get_ip();
		$countryCode = $this->ip_info($ip);

		if ($countryCode == 'IN' || $countryCode == 'ZA') {
			$data['currency'] = "INR";
		} else {
			$data['currency'] = "usd";
		}

        return view('users.product_form',compact('data',));
    }

    public function charge(Request $request)
	{
        dd($request);
		if (!empty($request->stripeToken)) {
			$token  = $request->stripeToken; 
			$name = $request->name; 	
            $user = Auth::user();
            dd($request);
            if(!empty($user)){
                $email = $user->email; 
                $card_num = $request->card_num; 
                $card_cvc = $request->cvc;
                $card_exp_month = $request->exp_month; 
                $card_exp_year = $request->exp_year; 
                $amount = $request->amount; 
                $plan = $request->amount;
                $type = $request->type;
                $device = $request->device;
            }else{
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email|unique:users',
                ]);
                if ($validator->fails()) {
                    $errors = $validator->errors();
                    $errorKeys = $errors->keys();
                    $firstErrorKey = $errorKeys[0];
                    $status = 0;
                    if ($firstErrorKey == 'email') {
                        $status = false;
                    }
                    return response()->json(
                        [
                            'status' => $status,
                            'message' => $validator->errors()->first()
                        ],
                    );
                }
                
                $user = new User();
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->login_type = 0;
                $user->assignRole('User');
                $user->role_id = 2;
                $user->save();
                $user_id = $user->id;
                $users = User::where('id',$user_id)->first();
                Auth::login($user);
            }

            $errormessage = "";

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $success = false;
            
            try {
                // Add customer to Stripe
                $customer = \Stripe\Customer::create([
                    'name' => 'Jenny Rosen',
                    'address' => [
                        'line1' => '510 Townsend St',
                        'postal_code' => '98140',
                        'city' => 'San Francisco',
                        'state' => 'CA',
                        'country' => 'US',
                    ],
                    'email' => $email,
                ]);
                // Create a Setup Intent to save the payment method for future use
                $setupIntent = \Stripe\SetupIntent::create([
                    'customer' => $customer->id,
                    'payment_method_data' => [
                        'type' => 'card',
                        'card' => ['token' => $token],
                    ],// Use the token provided by the client
                    'confirm' => true,
                    'return_url' => 'https://visionofthebible.com',
                    'automatic_payment_methods' => [
                        'enabled' => true,
                        'allow_redirects' => 'never',
                    ],
                ]);
                dd($setupIntent);
                // Optionally, you can confirm the payment later with a Payment Intent
                $itemName = "Stripe Donation";
                $itemNumber = "PS123456";
                $itemPrice = $plan * 100; // Ensure this is above the minimum amount
                $currency = "usd";
                $orderID = "SKA92712382139";

                // Create a Payment Intent using the saved payment method
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'customer' => $customer->id,
                    'amount' => $itemPrice,
                    'currency' => $currency,
                    'payment_method' => $setupIntent->payment_method,
                    'off_session' => true,
                    'confirm' => true,
                    'automatic_payment_methods' => [
                        'enabled' => true,
                        'allow_redirects' => 'never',
                    ],
                    'metadata' => ['item_id' => $itemNumber],
                ]);

                $success = true;
                $chargeJson = $paymentIntent->jsonSerialize();
            } catch (\Stripe\Exception\CardException $e) {
                // Handle card errors
                $errormessage = $e->getMessage();
            } catch (\Stripe\Exception\RateLimitException $e) {
                // Handle rate limit errors
                $errormessage = $e->getMessage();
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                // Handle invalid request errors
                $errormessage = $e->getMessage();
            } catch (\Stripe\Exception\AuthenticationException $e) {
                // Handle authentication errors
                $errormessage = $e->getMessage();
            } catch (\Stripe\Exception\ApiConnectionException $e) {
                // Handle network communication errors
                $errormessage = $e->getMessage();
            } catch (\Stripe\Exception\ApiErrorException $e) {
                // Handle generic API errors
                $errormessage = $e->getMessage();
            } catch (Exception $e) {
                // Handle any other exceptions
                $errormessage = $e->getMessage();
            }
            if ($success) {
                //check whether the charge is successful
                if ($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1) {
                    dd($chargeJson);
                } else {
                    echo "Invalid Token";
                    $statusMsg = "";
                }
            } else {
                $data['amount'] = $amount;
                $data['errormessage'] = $errormessage;
                $data['plan'] = $plan;
                $data['type'] = $type;
                $data['pkKey'] = env('STRIPE_SECRET');
                $ip = $this->get_ip();
                $countryCode = $this->ip_info($ip);

                if ($countryCode == 'IN' || $countryCode == 'ZA') {
                    $data['currency'] = "INR";
                } else {
                    $data['currency'] = "usd";
                }
                return view('users.product_form',compact('data'));
            }
		}
	}

    public function change_language(Request $request){
        $languange = $request->lang;
        Session::put('language', $languange);
        return true;
    }

    public function add_contact_us(Request $request){
        
        $request->validate([
            'email' => ['required', 'string'],
            'name' => ['required', 'string'],
            'phone' => ['required', 'string',],
            'message' => ['required', 'string',],
        ]);

        $contact = new ContactUs();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->message = $request->message;
        $contact->save();
        // dd($contact->id);
        toastr('Your data has been saved.');
        return redirect()->route('contact-us');
    }

    public function get_ip() {
   
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city" => @$ipdat->geoplugin_city,
                            "state" => @$ipdat->geoplugin_regionName,
                            "country" => @$ipdat->geoplugin_countryName,
                            "country_code" => @$ipdat->geoplugin_countryCode,
                            "continent" => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }
}
