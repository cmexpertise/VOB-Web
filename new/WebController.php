<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Daily_prayer;
use App\Models\Meditation;
use App\Models\Night_time_story_video;
use App\Models\Postcard;
use App\Models\Quote;
use App\Models\Travel_samaritan;
use App\Models\User;
use App\Models\Payment;
use App\Models\Subscription;
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
        $user = Auth::user();
        $plan = (isset($user->subscription->end_date) && $user->subscription->end_date >= date('Y-m-d'))?$user->subscription->plan:"";
        $books = Book::limit(10)->get();
        $quotes = Quote::limit(10)->get();
        $broadcasts = Daily_prayer::get();
        $night_time_stories = Night_time_story_video::limit(10)->get();
        $meditations = Meditation::limit(10)->orderBy('id','DESC')->get();
        $postcards = Postcard::limit(10)->orderBy('id','DESC')->get();
        return view('welcome',compact('books','quotes','night_time_stories','broadcasts','meditations','postcards','plan'));
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
        $user = Auth::user();
        $plan = (isset($user->subscription->end_date) && $user->subscription->end_date >= date('Y-m-d'))?$user->subscription->plan:"";
        return view('users.subscription_plan',compact('plan'));
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
        
		if (!empty($request->stripeToken)) {
			$token  = $request->stripeToken; 
			$name = $request->name; 	
            $user = Auth::user();
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

                $subscriptions = Subscription::where('user_id',$user->id)
                    ->orderBy('id','DESC')
                    ->where('end_date','>=',date('Y-m-d'))
                    ->where('unsubscribe_request',0)
                    ->get();
                if(isset($subscriptions) && $subscriptions->isEmpty()){
                    
                }else{
                     return redirect()->route('subscription_plan')->with('success', 'Already Subscribed !!!');
                }


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
                   return redirect()->route('subscription_plan')->with('error', 'Email Already Exist !!!');
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
              
                //add customer to stripe
                

                //item information
               
                $success = false;
                try {

                    $customer = \Stripe\Customer::create(array(
                        'name' => 'Jenny Rosen',
                        'address' => [
                            'line1' => '510 Townsend St',
                            'postal_code' => '98140',
                            'city' => 'San Francisco',
                            'state' => 'CA',
                            'country' => 'US',
                        ],
                        'email' => $email,
                        'source'  => $token
                    ));

                    $itemName = "Stripe Donation";
                    $itemNumber = "PS123456";
                    $itemPrice = $plan * 100; //"1900";
                    $currency = "usd";
                    // $currency = strtolower($this->input->post('currency'));
                    $orderID = "SKA92712382139";

                    //charge a credit or a debit card
                    $charge = \Stripe\Charge::create(array(

                        'customer' => $customer->id,
                        'amount'   => $itemPrice,
                        'currency' => $currency,
                        'description' => $itemNumber,
                        // 'customername'=>"test",
                        'metadata' => array(
                            'item_id' => $itemNumber
                        )
                    ));
                    $success = true;
                    //retrieve charge details
                    $chargeJson = $charge->jsonSerialize();
                } catch (\Stripe\Error\Card $e) {
                    $errormessage = $e->getMessage();
                    // redirect('member/payfees');
                } catch (\Stripe\Error\RateLimit $e) {
                    $errormessage = $e->getMessage();
                    // redirect('member/payfees');
                } catch (\Stripe\Error\InvalidRequest $e) {
                    $errormessage = $e->getMessage();
                    // redirect('member/payfees');
                } catch (\Stripe\Error\Authentication $e) {
                    $errormessage = $e->getMessage();
                    // redirect('member/payfees');
                } catch (\Stripe\Error\ApiConnection $e) {
                    $errormessage = $e->getMessage();
                    // redirect('member/payfees');
                } catch (\Stripe\Error\Base $e) {
                    $errormessage = $e->getMessage();
                    // redirect('member/payfees');
                } catch (Exception $e) {
                    $errormessage = $e->getMessage();
                    // redirect('member/payfees');
                }

            if ($success) {
                //check whether the charge is successful
                if ($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1) {
                    $amount = $chargeJson['amount'];
                    $balance_transaction = $chargeJson['balance_transaction'];
                    $currency = $chargeJson['currency'];
                    $status = $chargeJson['status'];
                    
                    if ($plan > 13) {
                            $enddate = date("Y-m-d H:m:s", strtotime('+365 days'));
                    } else {
                        $enddate = date("Y-m-d H:m:s", strtotime('+30 days'));
                    }

                    $payment = new Payment();
                    $payment->user_id = $user->id;
                    $payment->transaction_id = $balance_transaction;
                    $payment->package_name = $type;
                    $payment->amount = $amount;
                    $payment->save();
                    $duration = Payment::where('user_id',$user->id)->count();

                    $subscribed = Subscription::where('user_id',$user->id)
                        ->select('id','user_id')
                        ->get();
                    if(isset($subscribed) && !$subscribed->isEmpty()){
                        $subscription = Subscription::where('id',$subscribed[0]->id)
                            ->update([
                                'plan' => $type,
                                'start_date' => now(),
                                'end_date' => $enddate,
                                'feedback' => null,
                                'receipt' => $balance_transaction,
                                'purchase_token' => null,
                                'app_id' => null,
                                'product_id' => null,
                                'duration' => $duration,
                                'status' => '1',
                                'unsubscribe_request' => 0,
                                'device_type' => 'Stripe'
                            ]);
                    }else{
                        $subscription = new Subscription();
                        $subscription->user_id = $user->id;
                        $subscription->plan = $type;
                        $subscription->start_date = now();
                        $subscription->end_date = $enddate;
                        $subscription->feedback = null;
                        $subscription->receipt = null;
                        $subscription->purchase_token = null;
                        $subscription->app_id = null;
                        $subscription->product_id = null;
                        $subscription->duration = $duration;
                        $subscription->save();
                    }
                    return redirect()->route('home')->with('success', 'Subscription Done!!!');

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
        $user = Auth::user();
        $languange = $request->lang;
        Session::put('language', $languange);
    }

    public function add_contact_us(Request $request){
        
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
