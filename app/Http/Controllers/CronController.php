<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\Chapter;
use App\Models\Device;
use App\Models\Duration;
use App\Models\Mail_format;
use App\Models\Old_payment;
use App\Models\Old_subscription;
use App\Models\Payment;
use App\Models\Signup;
use App\Models\Smtp_setting;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class CronController extends Controller
{
    protected $serverKey;
    public function __construct()
    {
        $this->serverKey = 'AAAAcogJf3w:APA91bHT1h9tCfrLAjZi4ArYSuyPD1PaVmCCerUoFabVWE1LzpuIRx9rxqdfJSHNeeD4hsHPHybwTV-bb4J1Z7Ag8XV8ljPNzKySyoo6zk61ACswW_JmKv6BO_ebiJpR2rNq4zBXRTpZ';
    }

    public function getContinueWatching(){
       
        $devices = Device::with(['user' => function ($query){
            $query->select('id')
                ->with(['durations' => function ($quer){
                    $quer->select('*')
                        ->orderBy('updated_at','DESC');
                }]);
        }])
            
            ->select('id','device_id','user_id')
            ->get();
        $datas = [];
        foreach ($devices as $key => $device) {
            
            if(isset($device->user->durations[0]->table_name) && $device->user->durations[0]->table_name != ''){
                
                // $data = $this->duration_bookmark_video('en',$device->user,$device->user->durations);
                $data = app('App\Http\Controllers\Api\HomeController')->duration_bookmark_video('en',$device->user,$device->user->durations);
                if(isset($data[0])){
                    
                    $notification = array(
                        'title' => 'Continue Watching',
                        'body' => 'Your Video is not Completely watched.',
                        'sound' => 'default', 
                        'image' => asset("storage/".$data[0]['image']),
                    );
                    $newData = array(
                        'table_name' => $data[0]['table_name'],
                        'bookmark_id' => $data[0]['bookmark_id'],
                        'id' => $data[0]['id'],
                        'name' => $data[0]['name'],
                        'description' => $data[0]['description'],
                        'type' => $data[0]['type'],
                        'url' => $data[0]['url'],
                        'duration' => $data[0]['duration'],
                        'total_duration' => $data[0]['total_duration'],
                        'book_name' => $data[0]['book_name'],
                        'srt' => $data[0]['srt'],
                        'book_id' => $data[0]['book_id'],
                        'image' => asset("storage/".$data[0]['image']),
                    );
                    $message = array(
                        'to' => $device->device_id,
                        'notification' => $notification,
                        'data' => $newData,
                        'aps'=>array(
                            'alert'=>array(
                                'title'=>'Continue Watching',
                                'subtitle'=>'Continue Watching',
                                'body'=>'Your Video is not Completely watched.'),
                            'category'=>'myNotificationCategory',
                            'mutable-content'=>true,
                        ),
                    );
                    $response = $this->sendNotification($message,$this->serverKey);
                    array_push($datas, $response);
                }
            }
        }
        print_r($datas);
    }

    public function sendNotification($message,$serverKey){
       
        $jsonMessage = json_encode($message);
            $url = 'https://fcm.googleapis.com/fcm/send';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: key=' . $serverKey
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonMessage);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
    }

    public function duration_bookmark_video($lang,$user,$bookmark_videos){
        $all_book_mark = [];
        foreach ($bookmark_videos as $key => $bookmark) {
            $model_name = $bookmark->table_name;
            $bookmark_id = $bookmark->id;
            $fully_qualified_model_name = "App\\Models\\" . $model_name;
            if($lang == 'ko'){
                $name = 'korean_name';
                $description = 'korean_description';
                $lang_video = 'korean_video';
                $lang_audio = 'korean_audio';
                $srt = 'korean_srt';
            }elseif($lang == 'es') {
                $name = 'spanish_name';
                $description = 'spanish_description';
                $lang_video = 'spanish_video';
                $lang_audio = 'spanish_audio';
                $srt = 'spanish_srt';
            }elseif($lang == 'pt') {
                $name = 'portuguese_name';
                $description = 'portuguese_description';
                $lang_video = 'portuguese_video';
                $lang_audio = 'portuguese_audio';
                $srt = 'portuguese_srt';
            }elseif($lang == 'tl') {
                $name = 'filipino_name';
                $description = 'filipino_description';
                $lang_video = 'filipino_video';
                $lang_audio = 'filipino_audio';
                $srt = 'filipino_srt';
            }else{
                $name = 'name';
                $description = 'description';
                $lang_video = 'video';
                $lang_audio = 'audio';
                $srt = 'srt';
            }
            $videos = $fully_qualified_model_name::where('id',$bookmark->video_id)
                ->when($bookmark->type == '0', function ($q) use($model_name,$name,$description,$lang_video,$srt,$user) {
                    if($model_name == 'Chapter'){
                        return $q->select('id','name',$name,'video_image as image','description',$description,'video',$lang_video,'srt',$srt,'book_id')
                            ->with(['book' => function ($q) use($name){
                                $q->select('id','name',$name);
                            },'duration' => function ($que) use($user,$model_name){
                                $que->select('video_id','id','duration','table_name','total_duration')
                                    ->where('table_name',$model_name)
                                    ->where('type','0')
                                    ->where('user_id',$user->id);
                            }]);
                    }elseif ($model_name == 'Travel_samaritan') {
                        return $q->select('id','name',$name,'image','video',$lang_video)
                            ->with(['duration' => function ($que) use($user,$model_name){
                                $que->select('video_id','id','duration','table_name','total_duration','type')
                                    ->where('table_name',$model_name)
                                    ->where('type','0')
                                    ->where('user_id',$user->id);
                            }]);
                    }elseif ($model_name == 'Inspiration_video') {
                        return $q->select('id','name',$name,'image','video',$lang_video,'inspiration_id')
                            ->with(['duration' => function ($que) use($user,$model_name){
                                $que->select('video_id','id','duration','table_name','total_duration','type')
                                    ->where('table_name',$model_name)
                                    ->where('type','0')
                                    ->where('user_id',$user->id);
                            },'inspiration' => function ($quer) use($name,$description){
                                $quer->select('id','name',$name,'image','description',$description);
                            }]);
                    }
                })
                ->when($bookmark->type == '1', function ($q) use($model_name,$name,$description,$lang_audio,$srt,$user,$lang_video) {
                    if($model_name == 'Chapter'){
                        return $q->select('id','name',$name,'video_image as image','description',$description,'audio',$lang_audio,'srt',$srt,'book_id')
                            ->with(['book' => function ($q) use($name){
                                $q->select('id','name',$name);
                            },'duration' => function ($que) use($user,$model_name){
                                $que->select('video_id','id','duration','table_name','total_duration')
                                    ->where('table_name',$model_name)
                                    ->where('type','1')
                                    ->where('user_id',$user->id);
                            }]);
                    }elseif ($model_name == 'Daily_prayer') {
                        return $q->select('id','name',$name,'image','description',$description,'video',$lang_video)
                            ->with(['duration' => function ($que) use($user,$model_name){
                                $que->select('video_id','id','duration','table_name','total_duration','type')
                                    ->where('table_name',$model_name)
                                    ->where('type','0')
                                    ->where('user_id',$user->id);
                            }]);
                    }elseif ($model_name == 'Night_time_story_video') {
                        return $q->select('id','name',$name,'image','description',$description,'video',$lang_video,'night_time_story_id')
                            ->with(['night_time_story' => function ($q) use($name){
                                $q->select('id','name',$name);
                            },'duration' => function ($que) use($user,$model_name){
                                $que->select('video_id','id','duration','table_name','total_duration','type')
                                    ->where('table_name',$model_name)
                                    ->where('type','0')
                                    ->where('user_id',$user->id);
                            }]);
                    }
                })
                ->get();
                // dd($videos);
            if(isset($videos[0])){
                $chapter = [];
                $chapter['table_name'] = $model_name;
                $chapter['bookmark_id'] = $bookmark_id;
                foreach ($videos as $key => $video) {
                    $chapter['id'] = $video->id;
                    $chapter['name'] = ($video->$name != '')?$video->$name:$video->name;
                    $chapter['description'] = (isset($video->$description))?($video->$description != "")?$video->$description:$video->description:"";
                    $chapter['type'] = $bookmark->type;
                    
                    if($bookmark->type == '0'){
                        $chapter['url'] = ($video->$lang_video != '')?$video->$lang_video:$video->video;
                        $chapter['duration'] = (isset($video->duration) && !$video->duration->isEmpty())?$video->duration[0]->duration:'0';
                        $chapter['total_duration'] = (isset($video->duration) && !$video->duration->isEmpty())?$video->duration[0]->total_duration:'0';
                    }else{
                        $chapter['url'] = (isset($video->$lang_audio) && $video->$lang_audio != '')?$video->$lang_audio:$video->audio;
                        $chapter['duration'] = (isset($video->duration) && !$video->duration->isEmpty())?$video->duration[0]->duration:'0';
                        $chapter['total_duration'] = (isset($video->duration) && !$video->duration->isEmpty())?$video->duration[0]->total_duration:'0';
                    }
                    if($model_name == 'Chapter'){
                        $chapter['srt'] = ($video->$srt != '')?$video->$srt:$video->srt;
                        $chapter['book_name'] = ($video->book->$name != '')?$video->book->$name:$video->book->name;
                        $chapter['book_id'] = $video->book->id;
                    }elseif ($model_name == 'Daily_prayer') {
                        $chapter['url'] = (isset($video->$lang_video) && $video->$lang_video != '')?$video->$lang_video:$video->video;
                        $chapter['book_name'] = "";
                        $chapter['srt'] = "";
                        $chapter['book_id'] = "";
                    }elseif ($model_name == 'Night_time_story_video') {
                        $chapter['url'] = (isset($video->$lang_video) && $video->$lang_video != '')?$video->$lang_video:$video->video;
                        $chapter['book_name'] = $video->night_time_story->name;
                        $chapter['srt'] = "";
                        $chapter['book_id'] = $video->night_time_story->id;
                    }elseif ($model_name == 'Travel_samaritan') {
                        $chapter['url'] = (isset($video->$lang_video) && $video->$lang_video != '')?$video->$lang_video:$video->video;
                        $chapter['book_name'] = "";
                        $chapter['srt'] = "";
                        $chapter['book_id'] = "";
                    }elseif ($model_name == 'Inspiration_video') {
                        $chapter['url'] = (isset($video->$lang_video) && $video->$lang_video != '')?$video->$lang_video:$video->video;
                        $chapter['book_name'] = ($video->inspiration->$name != '')?$video->inspiration->$name:$video->inspiration->name;
                        $chapter['srt'] = "";
                        $chapter['book_id'] = $video->inspiration->id;
                    }
                    $chapter['image'] = asset("storage/".$video->image);
                }
                
                $all_book_mark[] = $chapter;
            }
        }
        
        return $all_book_mark;
       
    }

    public function change_extensions(){
        $chapters = Chapter::select('id','video_image')->get();
        foreach ($chapters as $key => $chapter) {
            $video_image = $chapter->video_image;
            $audio_image = $chapter->audio_image;
            $updated_video_image = str_replace('.png', '.jpg', $video_image);
            $updated_audio_image = str_replace('.png', '.jpg', $audio_image);
            Chapter::where('id',$chapter->id)
            ->where([
                'video_image' => $updated_video_image,
                'audio_image' => $updated_audio_image,
            ]);
        }
        dd('Extension Changed');
    }

    public function send_notification(Request $request){
        $devices = Device::select('id','device_id','user_id')
            ->get();
        $datas = [];
        $description = $request->description;
        $name = $request->name;
        foreach ($devices as $key => $device) {
            $notification = array(
                'title' => $name,
                'body' => $description,
                'sound' => 'default', 
                'image' => '',
            );
            $newData = array(
                'title' => $name,
                'body' => $description,
            );
            $message = array(
                'to' => $device->device_id,
                'notification' => $notification,
                'data' => $newData,
                'aps'=>array(
                    'alert'=>array(
                        'title'=>$name,
                        'subtitle'=>'',
                        'body'=>$description),
                    'category'=>'myNotificationCategory',
                    'mutable-content'=>true,
                ),
            );
            $response = $this->sendNotification($message,$this->serverKey);
            array_push($datas, $response);
        }
        toastr('Notification Has been sent');
        return redirect()->route('admin.notification');
    }

    public function send_mail(){
        $smtp = Smtp_setting::first();
        if(isset($smtp) && !empty($smtp)){

            config([
                'mail.mailers.smtp.host' => $smtp->host,
                'mail.mailers.smtp.port' => $smtp->port,
                'mail.mailers.smtp.username' => $smtp->user,
                'mail.mailers.smtp.password' => $smtp->password,
                'mail.mailers.smtp.encryption' => $smtp->encryption,
                'mail.from.address' => $smtp->from_mail,
                'mail.from.name' => $smtp->name,
            ]);

            $users = User::where('email','!=',null)
                ->where('role_id','!=','1')
                ->get();
            $mail_format = Mail_format::first();
            $details = [
                'subject' => (isset($mail_format->subject) && $mail_format->subject != null)?$mail_format->subject:'',
                'title' => (isset($mail_format->title) && $mail_format->title != null)?$mail_format->title:'',
                'body' => (isset($mail_format->body) && $mail_format->body != null)?$mail_format->body:''
            ];

            foreach ($users as $user) {
                SendEmailJob::dispatch($user, $details);
            }
            
            dd($users);
        }else{
            dd('add smtp configuration');
        }
    }

    public function check(){
        $users = Signup::get();
        foreach ($users as $key => $old) {
            $user = new User();
            $user->name = $old->name;
            $user->email = $old->mobile_email;
            $user->login_type = 0;
            $user->role_id = 2;
            $user->password = $old->password;
            $user->mobile = $old->mobile;
            // $user->affiliate_id = $old->affilate_id;
            $user->currency = $old->currency;
            $user->language = $old->lang;
            $user->created_at = $old->created_at;
            $user->save();
            $user_id = $user->id;
            $old_subscriptions = Old_subscription::where('signupid',$old->signup_id)->orderBy('id','DESC')->first();
            if($old_subscriptions!= null){
                if($old_subscriptions->start_date == null){
                    continue;
                }
                $subscription = new Subscription();
                $subscription->user_id = $user_id;
                $subscription->plan = $old_subscriptions->plan;
                $subscription->status = $old_subscriptions->status;
                $subscription->start_date = $old_subscriptions->start_date;
                $subscription->end_date = $old_subscriptions->end_date;
                $subscription->unsubscribe_request = $old_subscriptions->unsub_request;
                $subscription->feedback = $old_subscriptions->feedback;
                $subscription->duration = ($old_subscriptions->duration!=null)?$old_subscriptions->duration:0;
                $subscription->receipt = $old_subscriptions->receipt;
                $subscription->purchase_token = $old_subscriptions->purchaseToken;
                $subscription->app_id = $old_subscriptions->app_id;
                $subscription->product_id = $old_subscriptions->product_id;
                $subscription->created_at = $old_subscriptions->created_date;
                $subscription->save();
            }
            $old_payments = Old_payment::where('singup_id',$old->signup_id)->get();
            if(isset($old_payments) && !$old_payments->isEmpty()){
                $payment_array = [];
                foreach ($old_payments as $key => $old_payment) {
                    $payment_array[] = [
                        'user_id' => $user_id,
                        'transaction_id' => $old_payment->trans_id,
                        'package_name' => $old_payment->package_name,
                        'amount' => $old_payment->amount,
                        'created_at' => $old_payment->created_at
                    ];
                }
                Payment::insert($payment_array);
            }
        }
        dd('user updated successfully');
    }
}