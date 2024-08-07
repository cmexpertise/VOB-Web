<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\Api\UserResource;
use App\Models\Device;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class AuthenticationController extends BaseController
{
    public function store(Request $request)
    {   
        if(isset($request['login_type']) && $request['login_type'] != null){
            if($request['login_type'] == 0){
                if (
                    Auth::attempt([
                        'email' => $request['email'],
                        'password' => $request['password'],
                    ])
                ) {
                    DB::table('oauth_access_tokens')->where('user_id',Auth::user()->id)->delete();
                    $user = User::find(Auth::user()->id);
                    $user->accessToken = $user->createToken('appToken');
                    if(isset($request['fcm_token'])){
                        $device = $this->add_device($user->id,$request['fcm_token']);
                    }
                    return $this->sendResponse(
                        __('messages.loggedIn'),
                        new UserResource($user)
                    );
                } else {
                    return $this->sendError(
                        'Invalid Email Or Password',
                        ['error' => __('Invalid Email Or Password')],
                        200
                    );
                }
            }else{
                $users = User::where('email',$request['email'])->get();
                if(!$users->isEmpty()){
                    $user = $users[0];

                    if($request['login_type'] == 1){
                        User::where('email',$request['email'])
                            ->update(['social_token' => $request['token']]);
                    }elseif ($request['login_type'] == 2) {
                        User::where('email',$request['email'])
                            ->update(['facebook_token' => $request['token']]);
                    }elseif ($request['login_type'] == 3) {
                        User::where('email',$request['email'])
                            ->update(['apple_token' => $request['token']]);
                    }
                    if(isset($request['fcm_token'])){
                        $device = $this->add_device($user->id,$request['fcm_token']);
                    }
                    DB::table('oauth_access_tokens')->where('user_id',$user->id)->delete();
                    $user->accessToken = $user->createToken('appToken');
                    return $this->sendResponse(
                        __('messages.loggedIn'),
                        new UserResource($user)
                    );
                }else{
                    $user = new User();
                    $user->email = $request['email'];
                    if($request['login_type'] == 1){
                        $user->social_token = $request['token'];
                    }elseif ($request['login_type'] == 2) {
                        $user->facebook_token = $request['token'];
                    }elseif ($request['login_type'] == 3) {
                        $user->apple_token = $request['token'];
                    }
                    $user->fcm_token = $request['fcm_token'];
                    $user->login_type = 1; 
                    $user->name = $request['name'];
                    $user->assignRole('User');
                    $user->role_id = 2;
                    $user->save();
                    $user_id = $user->id;
                    if(isset($request['fcm_token'])){
                        $device = $this->add_device($user_id,$request['fcm_token']);
                    }
                    $user->accessToken = $user->createToken('appToken');
                    return $this->sendResponse(
                        __('messages.loggedIn'),
                        new UserResource($user)
                    );
                }
            }
        }else{
            return $this->sendError(
                'Unauthorised.',
                ['error' => __('messages.authFailed')],
                401
            );
        }
    }
    
    public function add_device($user_id,$fcm_token){
        if($fcm_token == null){
            Device::where('user_id',$user_id)->delete();
            return true;
        }
        $devices = Device::where('user_id',$user_id)->first();
        
        if(isset($devices) && !empty($devices)){
            $update = Device::where('id',$devices->id)
                ->update(['device_id' => $fcm_token]);
        }else{
            $device = new Device();
            $device->device_id = $fcm_token;
            $device->user_id = $user_id;
            $device->save();
        }
        return true;
    }

    public function destroy(Request $request)
    {
        if (Auth::user()) {
            $user = Auth::user();
            DB::table('oauth_access_tokens')->where('user_id',$user->id)->delete();
            Device::where('user_id',$user->id)->delete();
            return $this->sendResponse(__('messages.loggedout'),200);
        } else {
            return $this->sendResponse(__('messages.authFailed'), 401);
        }
    }

    public function add_subscription(){
        $user = Auth::user();
        $data = json_decode(file_get_contents('php://input'), true);        
        $app_id = $data['app_id'];
        $product_id = $data['product_id'];
        $user_id = $user->id;
        $purchaseToken = $data['purchase_token'];

        $responce =  $this->set_android_iap($app_id, $product_id, $purchaseToken);

        if (isset($responce['autoRenewing']) && ($responce['autoRenewing'] == '0' || $responce['autoRenewing'] == "")) {

            $exp_date =  date('Y-m-d H:i:s', ($responce['expiryTimeMillis'] /  1000));
            $current_date = date('Y-m-d H:i:s');
            if (strtotime($current_date) > strtotime($exp_date)) {                
              $data = ['status' => true, 'subscription' => 'failure'];
            //   echo json_encode($data);
              return response()->json($data);
              die;
            }
        }

        $enddate = date('Y-m-d H:i:s', ($responce['expiryTimeMillis'] / 1000));
        if($data['product_id'] == 'com.obs.oribible.yearly'){
            $plan = '1 Year';
        }else{
            $plan = '1 Year';
        }
        
        $payment = new Payment();
        $payment->user_id = $user->id;
        $payment->transaction_id = $data['transaction_id'];
        $payment->package_name = $data['product_id'];
        $payment->amount = $data['amount'];
        $payment->save();
        $duration = Payment::where('user_id',$user->id)->count();

        $subscribed = Subscription::where('user_id',$user->id)
            ->select('id','user_id')
            ->get();
        if(isset($subscribed) && !$subscribed->isEmpty()){
            $subscription = Subscription::where('id',$subscribed[0]->id)
                ->update([
                    'plan' => $plan,
                    'start_date' => now(),
                    'end_date' => $enddate,
                    'feedback' => null,
                    'receipt' => null,
                    'purchase_token' => $data['purchase_token'],
                    'app_id' => $data['app_id'],
                    'product_id' => $data['product_id'],
                    'duration' => $duration,
                    'unsubscribe_request' => 0
                ]);
        }else{
            $subscription = new Subscription();
            $subscription->user_id = $user->id;
            $subscription->plan = $plan;
            $subscription->start_date = now();
            $subscription->end_date = $enddate;
            $subscription->feedback = null;
            $subscription->receipt = null;
            $subscription->purchase_token = $data['purchase_token'];
            $subscription->app_id = $data['app_id'];
            $subscription->product_id = $data['product_id'];
            $subscription->duration = $duration;
            $subscription->save();
        }
        
        return $this->sendResponse(
            __('Subscription Successfully'),
            1
        );
    }

    public function set_android_iap($appid, $productID, $purchaseToken){
        $ch = curl_init();


        $TOKEN_URL = "https://accounts.google.com/o/oauth2/token";

        $VALIDATE_URL = "https://www.googleapis.com/androidpublisher/v3/applications/" .
        $appid . "/purchases/subscriptions/" .
        $productID . "/tokens/" . $purchaseToken;


        $input_fields = 'refresh_token=' . $refreshToken .
        '&client_secret=' . $clientSecret .
        '&client_id=' . $clientId .
        '&redirect_uri=' . $redirectUri .
        '&grant_type=refresh_token';

        //Request to google oauth for authentication
        curl_setopt($ch, CURLOPT_URL, $TOKEN_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $input_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $result = json_decode($result, true);

        if (!$result || !$result["access_token"]) {
        //error  
        // return;
        }

        // echo $VALIDATE_URL."?access_token=".$result["access_token"];exit;
        // echo $VALIDATE_URL."?access_token=".$result["access_token"];die;
        //request to play store with the access token from the authentication request


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $VALIDATE_URL . "?access_token=" . $result["access_token"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result1 = curl_exec($ch);
        $result1 = json_decode($result1, true);
        if (!$result1 || (isset($result1["error"]) && $result1["error"] != null)) {
        //error
        // return;
        }
        // print_r($result1);exit;
        return $result1;
  }
    
}