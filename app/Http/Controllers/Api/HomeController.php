<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Book;
use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\Daily_prayer;
use App\Models\Duration;
use App\Models\Feature_movie;
use App\Models\Inspiration;
use App\Models\Meditation;
use App\Models\Night_time_story;
use App\Models\Postcard;
use App\Models\Psalm;
use App\Models\Quote;
use App\Models\Quote_category;
use App\Models\Subscription;
use App\Models\Travel_samaritan;
use App\Models\User;
use App\Models\User_postcard;
// use App\Models\Chapter;
use Illuminate\Http\Request;

class HomeController extends BaseController{

    public function index(){
        $user = Auth::user();
        $data = json_decode(file_get_contents('php://input'), true);
        $old_testament = $this->getAllBooks($data,2,$user->id);
        $daily_prayer = $this->dailyPrayer($data,$user->id);
        $travel_smaritan = $this->travelSamaritan($data,$user->id);
        $new_testament = $this->getAllBooks($data,1,$user->id);
        $quotes = $this->quotes($data,'all_quotes');
        $quote = $this->quote($data);
        $psalms = $this->psalms($data,$user->id);
        $night_time_stories = $this->nightTimeStories($data,$user->id);
        $inspiration = $this->inspiration($data,$user->id);
        $post_cards = $this->postCards($data,$user->id);
        // $last_played = $this->lastPlayed($data);
        $filter = [];
        if($data['lang'] == 'ko'){
            $filter['All'] = '모두'; 
            $filter['Tanakh'] = '타나크'; 
            $filter['Broadcast'] = '방송'; 
            $filter['Psalms'] = '시편'; 
        }elseif ($data['lang'] == 'es') {
            $filter['All'] = 'Todo'; 
            $filter['Tanakh'] = 'Tanaj'; 
            $filter['Broadcast'] = 'Transmisión'; 
            $filter['Psalms'] = 'salmos'; 
        }elseif ($data['lang'] == 'pt') {
            $filter['All'] = 'Todos'; 
            $filter['Tanakh'] = 'Tanakh'; 
            $filter['Broadcast'] = 'Transmissão'; 
            $filter['Psalms'] = 'Salmos'; 
        }elseif ($data['lang'] == 'tl') {
            $filter['All'] = 'Lahat'; 
            $filter['Tanakh'] = 'Tanakh'; 
            $filter['Broadcast'] = 'I-broadcast'; 
            $filter['Psalms'] = 'Mga Awit'; 
        }else{
            $filter['All'] = 'All'; 
            $filter['Tanakh'] = 'Tanakh'; 
            $filter['Broadcast'] = 'Broadcast'; 
            $filter['Psalms'] = 'Psalms'; 
        }
        // $audios = $this->audios($data,$user->id);
        // $holy_land_tours = $this->holyLandTours($data,$user->id);
        // $user_postcards = $this->userPostcards($data,$user->id);
        // dd($last_played);
        $tanakh = [];
        
        foreach ($old_testament['data'] as $key => $value) {
            if($key < 5){

                $value['image'] = asset("storage/tanakh/".$key.".png");
                // dd($value);
                $tanakh[] = $value;
            }else{
                break;
            }
        }
        $mergedResponse = [
            'message' => 'Data Found',
            'status' => true,
            'data' => [
                'filter' => $filter,
                'tanakh' => $tanakh,
                'broadcast' => $daily_prayer,
                'travel_samaritans' => $travel_smaritan,
                'old_testament' => $old_testament,
                'new_testament' => $new_testament,
                'all_quotes' => $quotes,
                'quotes' => $quote,
                'psalms' => $psalms,
                'night_time_stories' => $night_time_stories,
                'daily_meditations' => $inspiration,
                'post_cards' => $post_cards,
                // 'holy_land_tours' => $holy_land_tours,
                // 'audios' => $audios,
                // 'user_postcard' => $user_postcards,
                // 'last_played' => $last_played,
            ],
        ];
        return response()->json($mergedResponse);
    }

    public function last_played(){
        $data = json_decode(file_get_contents('php://input'), true);
        $last_played = $this->lastPlayed($data);
        // $mergedResponse = [
        //     'message' => 'Data Found',
        //     'status' => true,
        //     'data' => [
        //         'last_played' => $last_played,
        //     ],
        // ];
        return response()->json($last_played);
    }

    public function lastPlayed($data){
        $user = Auth::user();
        $lang = (isset($data['lang'])) ? $data['lang'] : 'en';
        $video = Duration::where('user_id',$user->id)
            ->orderBy('updated_at','DESC')
            ->limit(1)
            ->get();
        if(!$video->isEmpty()){
            $all_book_mark = $this->duration_bookmark_video($lang,$user,$video);
            $mergedResponse = [
                'message' => 'Last Played Video',
                'status' => true,
                'data' => $all_book_mark[0],
            ];
            return $mergedResponse;
        }else{
            $mergedResponse = [
                'message' => "Your Haven't Played Any Video Or Audio",
                'status' => true,
                'data' => [],
            ];
            return $mergedResponse;
        }
    }

    public function check_subscription(){
        $user = Auth::user();

        
        $subscriptions = Subscription::where('user_id',$user->id)
        ->orderBy('id','DESC')
        // ->where('end_date','>=',date('Y-m-d'))
        // ->where('unsubscribe_request',0)
        ->select('id','plan','start_date','end_date','purchase_token','app_id','product_id','receipt')
        ->limit(1)
        ->get();
        
        if(isset($subscriptions) && !$subscriptions->isEmpty()){
            if($subscriptions[0]->purchase_token != null && $subscriptions[0]->receipt == null){
                $data = app('App\Http\Controllers\Api\AuthenticationController')->set_android_iap($subscriptions[0]->app_id,$subscriptions[0]->product_id,$subscriptions[0]->purchase_token);
                if(isset($data['expiryTimeMillis'])){
                    $enddate = date('Y-m-d H:i:s', ($data['expiryTimeMillis'] / 1000));
                }else{
                    $enddate = date('Y-m-d',strtotime("-1 days"));
                }
                if(isset($data["cancelReason"]) && $data['cancelReason'] != ''){
                    Subscription::where('user_id',$user->id)
                    ->update([
                        'end_date' => $enddate,
                        'unsubscribe_request' => 1,
                    ]);
                }else{
                    Subscription::where('user_id',$user->id)
                    ->update(['end_date' => $enddate]);
                }
            }
            $subscription = Subscription::where('user_id',$user->id)
                ->orderBy('id','DESC')
                ->where('end_date','>=',date('Y-m-d'))
                ->where('unsubscribe_request',0)
                ->select('id','plan','start_date','end_date','purchase_token','app_id','product_id')
                ->limit(1)
                ->get();
                // dd($subscription);
            if(isset($subscription) && !$subscription->isEmpty()){
                $mergedResponse = [
                    'message' => 'Subscription Details !',
                    'status' => true,
                    'data' => [
                        'subscription' => $subscription[0],
                    ],
                ];
            }else{
                $mergedResponse = [
                    'message' => 'Subscription is Over !',
                    'status' => false,
                    'data' => [
                        'subscription' => [],
                    ]
                ];
            }
        }else{
            $mergedResponse = [
                'message' => 'Subscription is Over !',
                'status' => false,
                'data' => [
                    'subscription' => [],
                ]
            ];
        }
        return response()->json($mergedResponse);

    }

    public function dailyPrayer($data,$user_id){
        $lang = (isset($data['lang'])) ? $data['lang'] : 'en';
        $type = 'dailyPrayer';
        if ($lang == 'ko') {
            $name = 'korean_name';
            $title = 'korean_title';
            $video = 'korean_video';
        }elseif ($lang == 'es') {
            $name = 'spanish_name';
            $title = 'spanish_title';
            $video = 'spanish_video';
        }elseif ($lang == 'pt') {
            $name = 'portuguese_name';
            $title = 'portuguese_title';
            $video = 'portuguese_video';
        }elseif ($lang == 'tl') {
            $name = 'filipino_name';
            $title = 'filipino_title';
            $video = 'filipino_video';
        }else{
            $name = 'name';
            $title = 'title';
            $video = 'video';
        }
        $prayers = $this->getSingleVideo($user_id,$type,$name,$video,$title);
        $resposnse = [];
        $resposnse['type'] = 'Broadcasts';
        $resposnse['data'] = $prayers;
        return $resposnse;
    }

    public function getSingleVideo($user_id,$type,$name,$video,$title = null){
        if($type == 'dailyPrayer'){
            $prayers = Daily_prayer::with(['bookmarks' => function ($que) use($user_id) {
                $que->select('id','video_id','type')->where('user_id',$user_id)
                    ->where('table_name','Daily_prayer');
                },'duration' => function ($q) use($user_id){
                    $q->select('id','video_id','type','duration','total_duration')
                        ->where('user_id',$user_id)
                        ->where('table_name','Daily_prayer');
                }])
                ->select('id','name',$name,'image','video',$video,'title',$title)
                ->orderBy('id','ASC')
                ->get();
        }elseif($type == 'meditations'){
            $prayers = Meditation::with(['bookmarks' => function ($que) use($user_id) {
                $que->select('id','video_id','type')->where('user_id',$user_id)
                    ->where('table_name','Meditation');
                },'duration' => function ($q) use($user_id){
                    $q->select('id','video_id','type','duration','total_duration')
                        ->where('user_id',$user_id)
                        ->where('table_name','Meditation');
                }])
                ->select('id','name',$name,'image','audio',$video,)
                ->orderBy('id','ASC')
                ->get();
        }
        $daily_prayer = [];
        foreach ($prayers as $key => $value) {
            $prayer = [];
            $prayer['id'] = $value->id;
            $prayer['name'] = (isset($value->$name) && $value->$name != null)?$value->$name:$value->name;
            $prayer['url'] = (isset($value->$video) && $value->$video != null)?$value->$video:$value->video;
            $prayer['image'] = asset("storage/".$value->image);
            if($type == 'dailyPrayer'){
                $bookmark_audio = 0;
                $total_audio_duration = '0';
                if(isset($value->bookmarks)){
                    $bookmarkArray = $value->bookmarks->toArray();
                    $type_column = array_column($bookmarkArray, 'type');
                    $book_audio_key = array_search('1', $type_column);
                    if ($book_audio_key !== false) {
                        $bookmark_audio = 1;
                    }
                }
                if(isset($value->duration)){
                    $durationArray = $value->duration->toArray();
                    $type_column = array_column($durationArray, 'type');
                    $duration_audio_key = array_search('1', $type_column);
                    $duration_audio = '0';
                    if($duration_audio_key !== false){
                        $duration_audio = $durationArray[$duration_audio_key]['duration'];
                        $total_audio_duration = $durationArray[$duration_audio_key]['total_duration'];
                    }
                }
                $prayer['duration_audio'] = $duration_audio;
                $prayer['total_audio_duration'] = $total_audio_duration;
                $prayer['bookmark_audio'] = $bookmark_audio;
                $prayer['title'] = (isset($value->$title) && $value->$title != null)?$value->$title:$value->title;
            }elseif($type == 'meditations'){
                $bookmark_audio = 0;
                $total_audio_duration = '0';
                if(isset($value->bookmarks)){
                    $bookmarkArray = $value->bookmarks->toArray();
                    $type_column = array_column($bookmarkArray, 'type');
                    $book_audio_key = array_search('1', $type_column);
                    if ($book_audio_key !== false) {
                        $bookmark_audio = 1;
                    }
                }
                if(isset($value->duration)){
                    $durationArray = $value->duration->toArray();
                    $type_column = array_column($durationArray, 'type');
                    $duration_audio_key = array_search('1', $type_column);
                    $duration_audio = '0';
                    if($duration_audio_key !== false){
                        $duration_audio = $durationArray[$duration_audio_key]['duration'];
                        $total_audio_duration = $durationArray[$duration_audio_key]['total_duration'];
                    }
                }
                $prayer['duration_audio'] = $duration_audio;
                $prayer['total_audio_duration'] = $total_audio_duration;
                $prayer['bookmark_audio'] = $bookmark_audio;
            }
            $daily_prayer[] = $prayer;
        }
        return $daily_prayer;
    }

    public function travelSamaritan($data,$user_id){
        $limit = 'no_limit';
        $lang = (isset($data['lang'])) ? $data['lang'] : 'en';
        $offset = 0;
        if (isset($data['slider']) && $data['slider'] == 'travel_smaritan') {
            $offset = (isset($data['offset']) && $data['offset'] > 0)?$data['offset']:0;
            if ($offset > 0) {
              $offset = $limit * $offset;
            }
        }
        if ($lang == 'ko') {
            $name = 'korean_name';
            $video = 'korean_video';
            $srt = 'korean_srt';
        }elseif ($lang == 'es') {
            $name = 'spanish_name';
            $video = 'spanish_video';
            $srt = 'spanish_srt';
        }elseif ($lang == 'pt') {
            $name = 'portuguese_name';
            $video = 'portuguese_video';
            $srt = 'portuguese_srt';
        }elseif ($lang == 'tl') {
            $name = 'filipino_name';
            $video = 'filipino_video';
            $srt = 'filipino_srt';
        }else{
            $name = 'name';
            $video = 'video';
            $srt = 'srt';
        }
        $travel_samaritan = $this->getTravelSamaritan($user_id,$name,$video,$srt,$limit,$offset);
        $resposnse = [];
        $resposnse['type'] = 'Travel Samaritan';
        $resposnse['data'] = $travel_samaritan;
        return $resposnse;
    }

    public function getTravelSamaritan($user_id,$name,$video,$srt,$limit,$offset,$orderBy = 'ASC',$featured_video=null){
        $travels = Travel_samaritan::select('id','name',$name,'image','video',$video,'srt',$srt)
            ->with(['duration' => function ($que) use($user_id){
                $que->select('video_id','id','duration','table_name','total_duration','type')
                    ->where('table_name','Travel_samaritan')
                    ->where('type','0')
                    ->where('user_id',$user_id);
            },'bookmarks' => function ($que) use($user_id) {
                $que->select('id','video_id','type')->where('user_id',$user_id)
                    ->where('table_name','Travel_samaritan');
            }])
            ->orderBy('id',$orderBy)
            ->when($featured_video != null , function ($q) {
                return $q->where('featured_video',1);
            })
            ->when($limit != 'no_limit' , function ($q) use($limit,$offset){
                return $q->limit($limit)
                    ->offset($offset);
            })
            ->get();
            // dd($travels);
        $travel_samaritan = [];
        foreach ($travels as $key => $value) {
            $caption = (isset($value->$srt) && $value->$srt != null)?$value->$srt:$value->srt;
            $travel = [];

            $bookmark_video = 0;
            $duration_video = '0';
            $total_audio_duration = '0';
            $total_video_duration = '0';
            if(isset($value->bookmarks)){
                $bookmarkArray = $value->bookmarks->toArray();
                $type_column = array_column($bookmarkArray, 'type');
                $book_audio_key = array_search('0', $type_column);
                if ($book_audio_key !== false) {
                    $bookmark_video = 1;
                }
            }
            if(isset($value->duration[0])){
                $durationArray = $value->duration->toArray();
                $type_column = array_column($durationArray, 'type');
                $duration_video_key = array_search('0', $type_column);
                
                if($duration_video_key !== false){
                    $duration_video = $durationArray[$duration_video_key]['duration'];
                    $total_video_duration = $durationArray[$duration_video_key]['total_duration'];
                }    
            }
            $travel['duration_video'] = $duration_video;
            $travel['total_video_duration'] = $total_video_duration;
            $travel['bookmark_video'] = $bookmark_video;
            $travel['id'] = $value->id;
            $travel['name'] = (isset($value->$name) && $value->$name != null)?$value->$name:$value->name;
            $travel['video'] = (isset($value->$video) && $value->$video != null)?$value->$video:$value->video;
            $travel['srt'] = ($caption != null)?$caption:'';
            $travel['image'] = asset("storage/".$value->image);
            $travel_samaritan[] = $travel;
        }
        return $travel_samaritan;
    }

    public function getAllBooks($data,$type = 1,$user_id){
        
        $lang = (isset($data['lang'])) ? $data['lang'] : 'en';
        $resposnse = [];
        if($type == 1){
            $resposnse['type'] = 'New Testament';
        }elseif($type == 2){
            $resposnse['type'] = 'Old Testament';
        }
        $media_video = 'video';
        $media_audio = 'audio';
        $image = 'video_image';
        if ($lang == 'ko') {
            $name = 'korean_name';
            $description = 'korean_description';
            $video = 'korean_video';
            $audio = 'korean_audio';
            $srt = 'korean_srt';
            if($type == 1){
                $resposnse['testament'] = '신약 성서';
            }elseif($type == 2){
                $resposnse['testament'] = '구약 성서';
            }
        }elseif ($lang == 'es') {
            $name = 'spanish_name';
            $description = 'spanish_description';
            $video = 'spanish_video';
            $audio = 'spanish_audio';
            $srt = 'spanish_srt';
            if($type == 1){
                $resposnse['testament'] = 'Nuevo Testamento';
            }elseif($type == 2){
                $resposnse['testament'] = 'Viejo Testamento';
            }
        }elseif ($lang == 'pt') {
            $name = 'portuguese_name';
            $description = 'portuguese_description';
            $video = 'portuguese_video';
            $audio = 'portuguese_audio';
            $srt = 'portuguese_srt';
            if($type == 1){
                $resposnse['testament'] = 'Novo Testamento';
            }elseif($type == 2){
                $resposnse['testament'] = 'Antigo Testamento';
            }
        }elseif ($lang == 'tl') {
            $name = 'filipino_name';
            $description = 'filipino_description';
            $video = 'filipino_video';
            $audio = 'filipino_audio';
            $srt = 'filipino_srt';
            if($type == 1){
                $resposnse['testament'] = 'Bagong Tipan';
            }elseif($type == 2){
                $resposnse['testament'] = 'Lumang Tipan';
            }
        }else{
            $name = 'name';
            $description = 'description';
            $video = 'video';
            $audio = 'audio';
            $srt = 'srt';
            if($type == 1){
                $resposnse['testament'] = 'New Testament';
            }elseif($type == 2){
                $resposnse['testament'] = 'Old Testament';
            }
        }
        $allBook = $this->getBooks($user_id,$type,$name,$description,$srt,$video,$audio,$media_video,$media_audio,$image);
        $resposnse['data'] = $allBook;
        if($type == 'all_video'){
            return $allBook;
        }else{
            return $resposnse;
        }
    }

    public function getBooks($user_id,$type,$name,$description,$srt,$video,$audio,$media,$media_audio,$image,$limit = null,$offset = null){
        
        $books = Book::with(['chapters' => function ($query) use($user_id,$name,$description,$srt,$video,$audio,$type,$media,$media_audio,$image,$limit,$offset){
            $query->select('id','book_id','name',$name,$image,'description',$description,$media,$video,'srt',$srt,$audio,$media_audio)
                ->with(['bookmarks' => function ($que) use($user_id) {
                    $que->select('id','video_id','type')->where('user_id',$user_id)
                        ->where('table_name','Chapter');
                },'duration' => function ($q) use($user_id){
                    $q->select('id','video_id','type','duration','total_duration')
                        ->where('user_id',$user_id)
                        ->where('table_name','Chapter');
                }]);
        }])
        ->select('id','name',$name,$image,'description',$description,'type')
        ->when($type == '1' || $type == '2' , function ($q) use($type){
            return $q->where('type',$type);
        })
        ->when($type == 'audio', function ($q) use($offset,$limit){
            return $q->offset($offset)
                ->limit($limit);
        })
        ->get();
        
        $allBook = [];
        foreach ($books as $key => $value) {
            $book = [];
            $book['id'] = $value->id;
            $book['name'] = (isset($value->$name) && $value->$name != null)?$value->$name:$value->name; 
            $book['description'] = (isset($value->$description) && $value->$description != null)?$value->$description:$value->description; 
            $book['image'] = asset("storage/".$value->$image);
            $book['type'] = $value->type;
            $chapters = [];
            if(!$value->chapters->isEmpty()){
                foreach ($value->chapters as $k => $val) {
                    $chapter = [];
                    $is_video = (isset($val->$video) && $val->$video != null)?$val->$video:$val->$media;
                    $is_audio = (isset($val->$audio) && $val->$audio != null)?$val->$audio:$val->$media_audio;
                    $caption = (isset($val->$srt) && $val->$srt != null)?$val->$srt:$val->srt;
                    if($is_video != null){
                        $bookmark_video = 0;
                        $bookmark_audio = 0;
                        $duration_video = '0';
                        $duration_audio = '0';
                        $total_video_duration = '0';
                        $total_audio_duration = '0';
                        if(isset($val->bookmarks)){
                            $bookmarkArray = $val->bookmarks->toArray();
                            $type_column = array_column($bookmarkArray, 'type');
                            
                            $book_audio_key = array_search('1', $type_column);
                            $book_video_key = array_search('0', $type_column);
                            if ($book_audio_key !== false) {
                                $bookmark_audio = 1;
                            }
                            if ($book_video_key !== false) {
                                $bookmark_video = 1;
                            }
                        }
                        if(isset($val->duration)){
                            $durationArray = $val->duration->toArray();
                            $type_column = array_column($durationArray, 'type');
                            $duration_audio_key = array_search('1', $type_column);
                            $duration_video_key = array_search('0', $type_column);
                            // dd($duration_video_key);
                            if($duration_video_key !== false ){
                                
                                $duration_video = $durationArray[$duration_video_key]['duration'];
                                $total_video_duration = $durationArray[$duration_video_key]['total_duration'];
                            }
                            if($duration_audio_key !== false){
                                $duration_audio = $durationArray[$duration_audio_key]['duration'];
                                $total_audio_duration = $durationArray[$duration_audio_key]['total_duration'];
                            }
                            
                        }
                        $chapter['id'] = $val->id;
                        $chapter['book_id'] = $val->book_id;
                        $chapter['name'] = (isset($val->$name) && $val->$name != null)?$val->$name:$val->name; 
                        $chapter['description'] = (isset($val->$description) && $val->$description != null)?$val->$description:$val->description; 
                        $chapter['image'] = asset("storage/".$val->$image);
                        $chapter['srt'] = ($caption != null)?$caption:'';
                        $chapter['video'] = $is_video;
                        $chapter['audio'] = ($is_audio != null)?$is_audio:"";
                        $chapter['bookmark_video'] = $bookmark_video;
                        $chapter['bookmark_audio'] = $bookmark_audio;
                        $chapter['duration_video'] = $duration_video;
                        $chapter['duration_audio'] = $duration_audio;
                        $chapter['total_video_duration'] = $total_video_duration;
                        $chapter['total_audio_duration'] = $total_audio_duration;
                        $chapters[] = $chapter;
                    }
                } 
            }
            $book['chapter'] = $chapters;
            $allBook[] = $book;
        }
        return $allBook;
    }

    public function is_bookmark($video_id,$bookmarks){
        $bookmarkArray = $bookmarks->toArray();
        $video_column = array_column($bookmarkArray, 'video_id');
        $key = array_search($video_id, $video_column);
        if ($key !== false) {
            $bookmark = 1;
        }else{
            $bookmark = 0;
        }
        return $bookmark;
    }
    
    public function quotes($data,$all_quotes = null){
       
        $offset = 0;
        $lang = (isset($data['lang'])) ? $data['lang'] : 'en';
        if($all_quotes != null){
            $limit = 'all';
        }else{
            $limit = 10;
            if (isset($data['slider']) && $data['slider'] == 'quotes') {
                $offset = (isset($data['offset']) && $data['offset'] > 0)?$data['offset']:0;
                if ($offset > 0) {
                  $offset = $limit * $offset;
                }
            }
        }
        if($lang == 'ko'){
            $name = 'korean_name';
        }elseif($lang == 'es'){
            $name = 'spanish_name';
        }elseif($lang == 'pt'){
            $name = 'portuguese_name';
        }elseif($lang == 'tl'){
            $name = 'filipino_name';
        }else{
            $name = 'name';
        }
        return $this->getQuotes($name,$limit,$offset);
    }

    public function quote($data,$all_quotes = null){
        $allquotes = Quote::select('id','image')
            ->orderBy('id','DESC')
            ->limit(8)
            ->get();
        $quotes = [];
        if(!$allquotes->isEmpty()){
            foreach ($allquotes as $k => $val) {
                $quote = [];
                $quote['id'] = $val->id; 
                $quote['image'] = asset("storage/".$val->image);
                $quotes[] = $quote;
            } 
        }
        return $quotes;

    }

    public function getQuotes($name,$limit,$offset){
        $quote_categories = Quote_category::with(['quotes' => function ($query){
            $query->select('id','quote_category_id','image');
        }])
        ->select('id','name',$name)
        ->orderBy('id','ASC')
        ->when($limit != 'all', function ($q) use($limit,$offset){
            return $q->limit($limit)
                ->offset($offset);
        })
        ->get();
        $allQuote = [];
        foreach ($quote_categories as $key => $value) {
            $category = [];
            $category['name'] = (isset($value->$name) && $value->$name != null)?$value->$name:$value->name;
            $quotes = [];
            if(!$value->quotes->isEmpty()){
                foreach ($value->quotes as $k => $val) {
                    $quote = [];
                    $quote['id'] = $val->id; 
                    $quote['quote_category_id'] = $val->quote_category_id; 
                    $quote['image'] = asset("storage/".$val->image);
                    $quotes[] = $quote;
                } 
            }
            $category['quotes'] = $quotes;
            $allQuote[] = $category;
        }
        return $allQuote;
    }

    public function psalms($data,$user_id){
        $lang = (isset($data['lang'])) ? $data['lang'] : 'en';
        if($lang == 'ko'){
            $name = 'korean_name';
        }elseif($lang == 'es'){
            $name = 'spanish_name';
        }elseif($lang == 'pt'){
            $name = 'portuguese_name';
        }elseif($lang == 'tl'){
            $name = 'filipino_name';
        }else{
            $name = 'name';
        }
        $psalms_type = Psalm::select('id','name',$name,'image')
            ->get();
        $psalms = [];
        foreach ($psalms_type as $key => $value) {
            if($value->name == 'HUMANITY'){
                $limit = 42;
                $skip = 0;
            }elseif($value->name == 'DELIVERANCE'){
                $skip = 42;
                $limit = 31;
            }elseif($value->name == 'SANCTUARY'){
                $skip = 73;
                $limit = 17;
            }elseif($value->name == 'REIGN OF GOD'){
                $skip = 90;
                $limit = 17;
            }elseif($value->name == 'WORD OF GOD'){
                $skip = 107;
                $limit = 45;
            }
            $psalms_chapters = Book::where('name','Psalms')
                ->select('id')
                ->get();
            $psalm = [];
            $psalm['id'] = $value->id;
            $psalm['name'] = (isset($value->$name) && $value->$name != null)?$value->$name:$value->name;
            $psalm['image'] = asset("storage/".$value->image);
            $psalm['book_id'] = $psalms_chapters[0]->id;
            // $chapters = $this->psalms_chapters($user_id,$value,$skip,$limit,$name,$video,$srt);
            $psalms[] = $psalm;
        }
        return $psalms;
    }

    public function psalms_chapters($user_id,$value,$skip,$limit,$name,$video,$srt){
        $psalms_chapters = Book::with(['chapters' => function ($query) use($name,$video,$skip,$limit,$srt) {
            $query->select('id','book_id','name',$name,'video',$video,'video_image','srt',$srt)
                ->skip($skip)
                ->limit($limit);
        }])->where('name','Psalms')
        ->select('id')
        ->get();
        $chapters = [];
        foreach ($psalms_chapters as $k => $allChapter) {
            foreach ($allChapter->chapters as $chapter) {
                $chap = [];
                $caption = (isset($chapter->$srt) && $chapter->$srt != null)?$chapter->$srt:$chapter->srt;
                $chap['name'] = (isset($chapter->$name) && $chapter->$name != null)?$chapter->$name:$chapter->name;
                $chap['video'] = (isset($chapter->$video) && $chapter->$video != null)?$chapter->$video:$chapter->video;
                $chap['image'] = asset("storage/".$chapter->video_image);
                $chap['id'] = $chapter->id;
                $chap['book_id'] = $chapter->book_id;
                $chap['srt'] = ($caption != null)?$caption:'';
                $chapters[] = $chap;
            }
        }
        $psalm = [];
        $psalm['id'] = $value->id;
        $psalm['name'] = (isset($value->$name) && $value->$name != null)?$value->$name:$value->name;
        $psalm['image'] = asset("storage/".$value->image);
        $psalm['book_id'] = $psalms_chapters[0]->id;
        $psalm['chapter'] = $chapters;

        return $psalm;
    }

    // public function audios($data,$user_id,$all_audio = null){
       
    //     $lang = (isset($data['lang'])) ? $data['lang'] : 'en';
    //     $offset = 0;
    //     $limit = 100;
    //     if($all_audio == null){
    //         $limit = 5;
    //         if (isset($data['slider']) && $data['slider'] == 'audio') {
    //             $offset = (isset($data['offset']) && $data['offset'] > 0)?$data['offset']:0;
    //             if ($offset > 0) {
    //               $offset = $limit * $offset;
    //             }
    //         }
    //     }
    //     $media_audio = 'audio';
    //     $media_video = 'video';
    //     $image = 'audio_image';
    //     if ($lang == 'ko') {
    //         $name = 'korean_name';
    //         $description = 'korean_description';
    //         $audio = 'korean_audio';
    //         $srt = 'korean_srt';
    //     }elseif ($lang == 'es') {
    //         $name = 'spanish_name';
    //         $description = 'spanish_description';
    //         $audio = 'spanish_audio';
    //         $srt = 'spanish_srt';
    //     }elseif ($lang == 'pt') {
    //         $name = 'portuguese_name';
    //         $description = 'portuguese_description';
    //         $audio = 'portuguese_audio';
    //         $srt = 'portuguese_srt';
    //     }elseif ($lang == 'tl') {
    //         $name = 'filipino_name';
    //         $description = 'filipino_description';
    //         $audio = 'filipino_audio';
    //         $srt = 'filipino_srt';
    //     }else{
    //         $name = 'name';
    //         $description = 'description';
    //         $audio = 'audio';
    //         $srt = 'srt';
    //     }
    //     $allBook = $this->getBooks($user_id,$media,$name,$description,$srt,$audio,$media,$image,$limit,$offset);
    //     return $allBook;
    // }

    public function nightTimeStories($data,$user_id){
        $limit = 10;
        $lang = (isset($data['lang'])) ? $data['lang'] : 'en';
        $offset = 0;
        $type = 'nightTime_stories';
        if (isset($data['slider']) && $data['slider'] == 'nightTime_stories') {
            $offset = (isset($data['offset']) && $data['offset'] > 0)?$data['offset']:0;
            if ($offset > 0) {
              $offset = $limit * $offset;
            }
        }
        if ($lang == 'ko') {
            $name = 'korean_name';
            $description = 'korean_description';
            $video = 'korean_video';
            $srt = 'korean_srt';
        }elseif ($lang == 'es') {
            $name = 'spanish_name';
            $description = 'spanish_description';
            $video = 'spanish_video';
            $srt = 'spanish_srt';
        }elseif ($lang == 'pt') {
            $name = 'portuguese_name';
            $description = 'portuguese_description';
            $video = 'portuguese_video';
            $srt = 'portuguese_srt';
        }elseif ($lang == 'tl') {
            $name = 'filipino_name';
            $description = 'filipino_description';
            $video = 'filipino_video';
            $srt = 'filipino_srt';
        }else{
            $name = 'name';
            $description = 'description';
            $video = 'video';
            $srt = 'srt';
        }
        $allBook = $this->getVideos($user_id,$type,$name,$description,$video,$limit,$offset,$srt);

        $resposnse['data'] = $allBook;
        return $resposnse;
    }

    public function getVideos($user_id,$type,$name,$description,$video,$limit,$offset,$srt){
        if($type == 'nightTime_stories'){
            $books = Night_time_story::with(['night_time_story_videos' => function ($query) use($name,$video,$description,$srt,$user_id) {
                $query->select('id','night_time_story_id','name',$name,'image','description',$description,$video,'video','srt',$srt)
                ->with(['bookmarks' => function ($que) use($user_id) {
                    $que->select('id','video_id','type')->where('user_id',$user_id)
                        ->where('table_name','Night_time_story_video');
                },'duration' => function ($q) use($user_id){
                    $q->select('id','video_id','type','duration','total_duration')
                        ->where('user_id',$user_id)
                        ->where('table_name','Night_time_story_video');
                }]);
            }])->select('id','name',$name,'image','description',$description,)
                // ->offset($offset)
                // ->limit($limit)
                ->get();
        }elseif($type == 'inspiration'){
            $books = Inspiration::with(['inspiration_videos' => function ($query) use($name,$video,$description,$srt,$user_id) {
                $query->select('id','inspiration_id','name',$name,'image','description',$description,$video,'video','srt',$srt)
                ->with(['bookmarks' => function ($que) use($user_id) {
                    $que->select('id','video_id','type')->where('user_id',$user_id)
                        ->where('table_name','Inspiration_video');
                },'duration' => function ($q) use($user_id){
                    $q->select('id','video_id','type','duration','total_duration')
                        ->where('user_id',$user_id)
                        ->where('table_name','Inspiration_video');
                }]);
            }])->select('id','name',$name,'image','description',$description)
                // ->offset($offset)
                // ->limit($limit)
                ->get();
        }
        $allBook = [];
        foreach ($books as $key => $value) {
            $book = [];
            $book['id'] = $value->id;
            $book['name'] = (isset($value->$name) && $value->$name != null)?$value->$name:$value->name; 
            $book['description'] = (isset($value->$description) && $value->$description != null)?$value->$description:$value->description; 
            $book['image'] = asset("storage/".$value->image);
            $chapters = [];

            $bookmark_video = 0;
            $bookmark_audio = 0;
            $duration_video = '0';
            $duration_audio = '0';
            $total_video_duration = '0';
            $total_audio_duration = '0';

            if(isset($value->night_time_story_videos) && !$value->night_time_story_videos->isEmpty()){
                foreach ($value->night_time_story_videos as $k => $val) {
                    $chapter = [];
                    $is_description = (isset($val->description) && $val->description != null)?$val->description:'';
                    $is_media = (isset($val->$video) && $val->$video != null)?$val->$video:$val->video;
                    if($is_media != null){

                        if(isset($val->bookmarks)){
                            $bookmarkArray = $val->bookmarks->toArray();
                            $type_column = array_column($bookmarkArray, 'type');
                            
                            $book_audio_key = array_search('1', $type_column);
                            $book_video_key = array_search('0', $type_column);
                            if ($book_audio_key !== false) {
                                $bookmark_audio = 1;
                            }
                            if ($book_video_key !== false) {
                                $bookmark_video = 1;
                            }
                        }
                        if(isset($val->duration)){
                            $durationArray = $val->duration->toArray();
                            $type_column = array_column($durationArray, 'type');
                            $duration_audio_key = array_search('1', $type_column);
                            $duration_video_key = array_search('0', $type_column);
                            if($duration_video_key !== false ){
                                
                                $duration_video = $durationArray[$duration_video_key]['duration'];
                                $total_video_duration = $durationArray[$duration_video_key]['total_duration'];
                            }
                            if($duration_audio_key !== false){
                                $duration_audio = $durationArray[$duration_audio_key]['duration'];
                                $total_audio_duration = $durationArray[$duration_audio_key]['total_duration'];
                            }
                            
                        }

                        $caption = (isset($value->$srt) && $value->$srt != null)?$value->$srt:$value->srt;
                        $chapter['id'] = $val->id;
                        $chapter['book_id'] = $val->night_time_story_id;
                        $chapter['name'] = (isset($val->$name) && $val->$name != null)?$val->$name:$val->name; 
                        $chapter['description'] = (isset($val->$description) && $val->$description != null)?$val->$description:$is_description; 
                        $chapter['image'] = asset("storage/".$val->image);
                        $chapter['audio'] = $is_media;
                        $chapter['video'] = '';
                        $chapter['srt'] = ($caption != null)?$caption:'';
                        $chapter['bookmark_video'] = $bookmark_video;
                        $chapter['bookmark_audio'] = $bookmark_audio;
                        $chapter['duration_video'] = $duration_video;
                        $chapter['duration_audio'] = $duration_audio;
                        $chapter['total_video_duration'] = $total_video_duration;
                        $chapter['total_audio_duration'] = $total_audio_duration;
                        $chapters[] = $chapter;
                    }
                } 
            }
            if(isset($value->inspiration_videos) && !$value->inspiration_videos->isEmpty()){
                foreach ($value->inspiration_videos as $k => $val) {
                    if(isset($val->bookmarks)){
                        $bookmarkArray = $val->bookmarks->toArray();
                        $type_column = array_column($bookmarkArray, 'type');
                        
                        $book_audio_key = array_search('1', $type_column);
                        $book_video_key = array_search('0', $type_column);
                        if ($book_audio_key !== false) {
                            $bookmark_audio = 1;
                        }
                        if ($book_video_key !== false) {
                            $bookmark_video = 1;
                        }
                    }
                    if(isset($val->duration)){
                        $durationArray = $val->duration->toArray();
                        $type_column = array_column($durationArray, 'type');
                        $duration_audio_key = array_search('1', $type_column);
                        $duration_video_key = array_search('0', $type_column);
                        if($duration_video_key !== false ){
                            
                            $duration_video = $durationArray[$duration_video_key]['duration'];
                            $total_video_duration = $durationArray[$duration_video_key]['total_duration'];
                        }
                        if($duration_audio_key !== false){
                            $duration_audio = $durationArray[$duration_audio_key]['duration'];
                            $total_audio_duration = $durationArray[$duration_audio_key]['total_duration'];
                        }
                        
                    }
                    $chapter = [];
                    $is_description = (isset($val->description) && $val->description != null)?$val->description:'';
                    $is_media = (isset($val->$video) && $val->$video != null)?$val->$video:$val->video;
                    if($is_media != null){
                        $caption = (isset($value->$srt) && $value->$srt != null)?$value->$srt:$value->srt;
                        $chapter['id'] = $val->id;
                        $chapter['book_id'] = $val->inspiration_id;
                        $chapter['name'] = (isset($val->$name) && $val->$name != null)?$val->$name:$val->name; 
                        $chapter['description'] = (isset($val->$description) && $val->$description != null)?$val->$description:$is_description; 
                        $chapter['image'] = asset("storage/".$val->image);
                        $chapter['video'] = $is_media;
                        $chapter['audio'] = '';
                        $chapter['srt'] = ($caption != null)?$caption:'';
                        $chapter['bookmark_video'] = $bookmark_video;
                        $chapter['bookmark_audio'] = $bookmark_audio;
                        $chapter['duration_video'] = $duration_video;
                        $chapter['duration_audio'] = $duration_audio;
                        $chapter['total_video_duration'] = $total_video_duration;
                        $chapter['total_audio_duration'] = $total_audio_duration;
                        $chapters[] = $chapter;
                    }
                } 
            }
            $book['chapter'] = $chapters;
            $allBook[] = $book;
        }
        return $allBook;
    }

    public function inspiration($data,$user_id){
        $limit = 10;
        $lang = (isset($data['lang'])) ? $data['lang'] : 'en';
        $offset = 0;
        $type = 'inspiration';
        if (isset($data['slider']) && $data['slider'] == 'inspiration') {
            $offset = (isset($data['offset']) && $data['offset'] > 0)?$data['offset']:0;
            if ($offset > 0) {
                $offset = $limit * $offset;
            }
        }
        
        if ($lang == 'ko') {
            $name = 'korean_name';
            $description = 'korean_description';
            $video = 'korean_audio';
            $srt = 'korean_srt';
        }elseif ($lang == 'es') {
            $name = 'spanish_name';
            $description = 'spanish_description';
            $video = 'spanish_audio';
            $srt = 'spanish_srt';
        }elseif ($lang == 'pt') {
            $name = 'portuguese_name';
            $description = 'portuguese_description';
            $video = 'portuguese_audio';
            $srt = 'portuguese_srt';
        }elseif ($lang == 'tl') {
            $name = 'filipino_name';
            $description = 'filipino_description';
            $video = 'filipino_audio';
            $srt = 'filipino_srt';
        }else{
            $name = 'name';
            $description = 'description';
            $video = 'audio';
            $srt = 'srt';
        }
        $prayers = $this->getSingleVideo($user_id,$type,$name,$video);

        $resposnse['data'] = $prayers;
        return $resposnse;
        
    }

    public function holyLandTours($data,$user_id){
        $lang = (isset($data['lang'])) ? $data['lang'] : 'en';
        $type = 'holyLandTours';
        if ($lang == 'ko') {
            $name = 'korean_name';
            $video = 'korean_video';
            
        }elseif ($lang == 'es') {
            $name = 'spanish_name';
            $video = 'spanish_video';
            
        }elseif ($lang == 'pt') {
            $name = 'portuguese_name';
            $video = 'portuguese_video';
            
        }elseif ($lang == 'tl') {
            $name = 'filipino_name';
            $video = 'filipino_video';
            
        }else{
            $name = 'name';
            $video = 'video';
        }
        $prayers = $this->getSingleVideo($user_id,$type,$name,$video);
        $resposnse = [];
        $resposnse['type'] = 'Holy Land Tours';
        $resposnse['data'] = $prayers;
        return $resposnse;
    }

    public function postCards($data){
        $postcards = Postcard::select('id','image')->get();
        $post_cards = [];
        foreach ($postcards as $key => $card) {
            $postcard = [];
            $postcard['id'] = $card->id;
            $postcard['image'] = asset("storage/".$card->image);
            $postcard['thumbnail'] = asset("storage/".$card->image);

            $post_cards[] = $postcard; 
        }
        $resposnse = [];
        $resposnse['type'] = 'Post Cards';
        $resposnse['data'] = $post_cards;
        return $resposnse;
    }

    public function userPostcards($data){
        $user = Auth::user();
        $user_postcards = User_postcard::where('user_id',$user->id)
            ->select('id','user_id','image')->get();
        $post_cards = [];
        foreach ($user_postcards as $key => $card) {
            $postcard = [];
            $postcard['id'] = $card->id;
            $postcard['image'] = asset("storage/".$card->image);
            $postcard['thumbnail'] = asset("storage/".$card->image);
            $post_cards[] = $postcard; 
        }
        $resposnse = [];
        $resposnse['type'] = 'User Post Cards';
        $resposnse['data'] = $post_cards;
        return $resposnse;
    }

    public function allAudios(){
        $user = Auth::user();
        $data = json_decode(file_get_contents('php://input'), true);
        $audios = $this->audios($data,$user->id,1);
        $mergedResponse = [
            'message' => 'Data Found',
            'status' => true,
            'data' => [
                'audios' => $audios,
            ],
        ];
        return response()->json($mergedResponse);
    }

    public function allVideos(){
        $user = Auth::user();
        $data = json_decode(file_get_contents('php://input'), true);
        $videos = $this->getAllBooks($data,'all_video',$user->id);
        $mergedResponse = [
            'message' => 'Data Found',
            'status' => true,
            'data' => [
                'videos' => $videos,
            ],
        ];
        return response()->json($mergedResponse);
    }

    public function allQuotes(){
        $data = json_decode(file_get_contents('php://input'), true);
        $quotes = $this->quotes($data,'all_quotes');
        $mergedResponse = [
            'message' => 'Data Found',
            'status' => true,
            'data' => [
                'quotes' => $quotes,
            ],
        ];
        return response()->json($mergedResponse);
    }
    
    public function updateProfile(){
        $data = json_decode(file_get_contents('php://input'), true);
        $user = Auth::user();
        $name = $data['name'];
        $mobile = $data['mobile'];
        User::where('id',$user->id)
            ->update([
                'name'=>$name,
                'mobile'=>$mobile,
            ]);
        $users = User::where('id',$user->id)
            ->select('name','email','id','mobile')
            ->get();
        $userss = [];
        $userss['name'] = (isset($users[0]->name) && $users[0]->name != '')?$users[0]->name:"";
        $userss['email'] = (isset($users[0]->email) && $users[0]->email != '')?$users[0]->email:"";
        $userss['mobile'] = (isset($users[0]->mobile) && $users[0]->mobile != '')?$users[0]->mobile:"";
        $userss['id'] = $users[0]->id;
        $mergedResponse = [
            'message' => 'Profile Updated !',
            'status' => true,
            'data' => [
                'user' => $userss,
            ],
        ];
        return response()->json($mergedResponse);
    }

    public function allTravelSamaritan(){
        $user = Auth::user();
        $data = json_decode(file_get_contents('php://input'), true);
        $lang = (isset($data['lang'])) ? $data['lang'] : 'en';
        $travelData = [];
        $new_travel = [];
        $new_travel['type'] = 'New Release';
        $new_travel['travel_smaritan'] = [];
        if ($lang == 'ko') {
            $name = 'korean_name';
            $video = 'korean_video';
            $srt = 'korean_srt';
        }elseif ($lang == 'es') {
            $name = 'spanish_name';
            $video = 'spanish_video';
            $srt = 'spanish_srt';
            
        }elseif ($lang == 'pt') {
            $name = 'portuguese_name';
            $video = 'portuguese_video';
            $srt = 'portuguese_srt';
            
        }elseif ($lang == 'tl') {
            $name = 'filipino_name';
            $video = 'filipino_video';
            $srt = 'filipino_srt';
            
        }else{
            $name = 'name';
            $video = 'video';
            $srt = 'srt';
        }
        $travel_samaritan = $this->getTravelSamaritan($user->id,$name,$video,$srt,5,0,'DESC');
        $new_travel['travel_smaritan'][] = $travel_samaritan;
        $travelData[] = $new_travel;

        $featuredTravel = [];
        $featuredTravel['type'] = 'Featured';
        $featuredTravel['featured'] = [];
        $travel_samaritan = $this->getTravelSamaritan($user->id,$name,$video,$srt,'no_limit',0,'DESC','featured_video');
        $featuredTravel['featured'][] = $travel_samaritan;
        $travelData[] = $featuredTravel;
        
        $playList = [];
        $playList['type'] = 'playlist';
        $playList['playlist'] = [];
        $travel_samaritan = $this->getTravelSamaritan($user->id,$name,$video,$srt,'no_limit',0,'DESC');
        $playList['playlist'][] = $travel_samaritan;
        $travelData[] = $playList;

        $mergedResponse = [
            'message' => 'Data Found',
            'status' => true,
            'data' => $travelData,
        ];
        return response()->json($mergedResponse);
    }

    public function add_postcard(Request $request){
        if ($request->hasFile('image')) {
            $user = Auth::user();
            $request->validate([
                'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
            ]);
            $image = 'post_card_image' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('user_postcard/', $image, 'public');
            $image = 'user_postcard/'.$image;
            $postcard = new User_postcard();
            $postcard->user_id = $user->id;
            $postcard->image = $image;
            $postcard->save();
            $mergedResponse = [
                'message' => 'Post Card Saved Successfully !',
                'status' => true,
                'data' => $postcard,
            ];
            return response()->json($mergedResponse);
        }else{
            return response()->json(['success' => false,'message' => 'Image is Required !']);
        }
    }

    public function add_bookmark(){
        $data = json_decode(file_get_contents('php://input'), true);

        $user = Auth::user();
        if($data['isBookmark'] == 1){
            $bookmark = new Bookmark();
            $bookmark->video_id = $data['video_id'];
            $bookmark->table_name = $data['table_name'];
            $bookmark->type = $data['type'];
            $bookmark->user_id = $user->id;
            $bookmark->save();
            $message = "Video Add In Bookmark Successfully !";
        }
        
        if($data['isBookmark'] == 0){
            $book = Bookmark::where('video_id',$data['video_id'])
                ->where('table_name',$data['table_name'])
                ->where('user_id',$user->id)
                ->where('type',$data['type'])
                ->get();
                $bookmark = [];
            if(isset($book) && !$book->isEmpty()){
                $bookmark = $book[0];
                Bookmark::where('id',$book[0]->id)->delete();
            }
            $message = 'Video Remove From Bookmark Successfully !';
        }

        $mergedResponse = [
            'message' => $message,
            'status' => true,
            'isBookmark' => $data['isBookmark'],
            'data' => $bookmark,
        ];
        return response()->json($mergedResponse);

    }
    
    public function remove_bookmark(){
        // $data = json_decode(file_get_contents('php://input'), true); 
        // $bookmark = Bookmark::where('id',$data['id'])->delete(); 
        $mergedResponse = [
            'message' => 'Video Remove From Bookmark Successfully !',
            'status' => true,
            'data' => "",
        ];
        return response()->json($mergedResponse);

    }

    public function view_bookmark(){
        $user = Auth::user();
        $data = json_decode(file_get_contents('php://input'), true); 
        $lang = (isset($data['lang'])) ? $data['lang'] : 'en';
        $bookmark_videos = Bookmark::where('user_id',$user->id)
            ->get();
        if(!$bookmark_videos->isEmpty()){
            $all_book_mark = $this->duration_bookmark_video($lang,$user,$bookmark_videos);

            $mergedResponse = [
                'message' => 'Bookmark Videos',
                'status' => true,
                'data' => $all_book_mark,
            ];
            return response()->json($mergedResponse);
        }else{
            return $this->sendError(
                'No Data Found',
                ['error' => 'Yout Bookmark List is Empty'],
                200
            );
        }
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
                                    ->where('type','1')
                                    ->where('user_id',$user->id);
                            }]);
                    }elseif ($model_name == 'Night_time_story_video') {
                        return $q->select('id','name',$name,'image','description',$description,'video',$lang_video,'night_time_story_id')
                            ->with(['night_time_story' => function ($q) use($name){
                                $q->select('id','name',$name);
                            },'duration' => function ($que) use($user,$model_name){
                                $que->select('video_id','id','duration','table_name','total_duration','type')
                                    ->where('table_name',$model_name)
                                    ->where('type','1')
                                    ->where('user_id',$user->id);
                            }]);
                    }elseif ($model_name == 'Meditation') {
                        return $q->select('id','name',$name,'image','description',$description,'audio',$lang_audio)
                            ->with(['duration' => function ($que) use($user,$model_name){
                                $que->select('video_id','id','duration','table_name','total_duration','type')
                                    ->where('table_name',$model_name)
                                    ->where('type','1')
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
                    }elseif ($model_name == 'Meditation') {
                        $chapter['url'] = (isset($video->$lang_audio) && $video->$lang_audio != '')?$video->$lang_audio:$video->audio;
                        $chapter['book_name'] = "";
                        $chapter['srt'] = "";
                        $chapter['book_id'] = "";
                    }
                    $chapter['image'] = asset("storage/".$video->image);
                }
                
                $all_book_mark[] = $chapter;
            }
        }
        
        return $all_book_mark;
       
    }

    public function duration(){
        $user = Auth::user();
        $data = json_decode(file_get_contents('php://input'), true);
        $durations = Duration::where('user_id',$user->id)
            ->where('video_id',$data['video_id'])
            ->where('table_name',$data['table_name'])
            ->where('type',$data['type'])
            ->get();
        $is_completed = false;
        if(isset($durations) && !$durations->isEmpty()){
            if($data['duration'] == $data['total_duration']){
                $duration = Duration::where('id',$durations[0]->id)->delete();
                $is_completed = true;
                $message = 'Duration Completed Successfully !!';
            }else{

                $duration = Duration::where('id',$durations[0]->id)
                    ->update([
                        'duration' => $data['duration'],
                        'total_duration' => $data['total_duration'],
                    ]);
                $durations = Duration::where('id',$durations[0]->id)->get();
                $duration = $durations[0]; 
                $message = 'Duration Updated Successfully !!';
            }
        }else{
            $duration = new Duration();
            $duration->user_id = $user->id;
            $duration->video_id = $data['video_id'];
            $duration->table_name = $data['table_name'];
            $duration->type = $data['type'];
            $duration->duration = $data['duration'];
            $duration->total_duration = $data['total_duration'];
            $duration->save();
            $message = 'Duration Added Successfully !!';
        }
        $mergedResponse = [
            'message' => $message,
            'is_completed' => $is_completed,
            'status' => true,
            'data' => $duration,
        ];
        return response()->json($mergedResponse);
    }

    public function new_bookmark(){
        $user = Auth::user();
        // $models = ['Chapter'];
        $models = [
            [
                'name' => 'Chapter',
                'type' => 0,
            ],
            [
                'name' => 'Chapter',
                'type' => 1,
            ],
            [
                'name' => 'Daily_prayer',
                'type' => 1,
            ],
            [
                'name' => 'Night_time_story_video',
                'type' => 1,
            ],
            [
                'name' => 'Meditation',
                'type' => 1,
            ],
            [
                'name' => 'Travel_samaritan',
                'type' => 0,
            ]
        ];
        $data = json_decode(file_get_contents('php://input'), true); 
        $lang = (isset($data['lang'])) ? $data['lang'] : 'en';
        
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
        $bookmark_videos = [];
        foreach ($models as $key => $model) {
            $bookmark_video = Bookmark::where('user_id',$user->id)
            ->when($model['name'] == 'Chapter', function ($query) use($user,$model,$name,$description,$lang_audio,$lang_video,$srt) {
                return $query->with(['chapter' => function($q) use($user,$model,$name,$description,$lang_audio,$lang_video,$srt){
                    $q->select('id','name','video_image as image','description','video','audio','srt','book_id',$name,$description,$lang_audio,$lang_video,$srt)
                        ->with(['book' => function ($q) {
                            $q->select('id','name');
                        },'duration' => function ($que) use($user,$model){
                            $que->select('video_id','id','duration','table_name','total_duration','type')
                                ->where('table_name','Chapter')
                                ->where('type',$model['type'])
                                ->where('user_id',$user->id);
                        }]);
                }]);
            })
            ->when($model['name'] == 'Daily_prayer', function ($query) use($user,$model,$name,$description,$lang_audio,$lang_video,$srt) {
                return $query->with(['daily_prayer' => function($q) use($user,$model,$name,$description,$lang_audio,$lang_video,$srt){
                    $q->select('id','name','image','description','video',$name,$description,$lang_video)
                        ->with(['duration' => function ($que) use($user,$model){
                            $que->select('video_id','id','duration','table_name','total_duration','type')
                                ->where('table_name','Daily_prayer')
                                ->where('type',$model['type'])
                                ->where('user_id',$user->id);
                        }]);
                }]);
            })
            ->when($model['name'] == 'Night_time_story_video', function ($query) use($user,$model,$name,$description,$lang_video) {
                return $query->with(['night_time_story_video' => function($q) use($user,$model,$name,$description,$lang_video){
                    $q->select('id','name','image','description','video',$name,$description,$lang_video)
                        ->with(['duration' => function ($que) use($user,$model){
                            $que->select('video_id','id','duration','table_name','total_duration','type')
                                ->where('table_name','Night_time_story_video')
                                ->where('type',$model['type'])
                                ->where('user_id',$user->id);
                        }]);
                }]);
            })
            ->when($model['name'] == 'Inspiration_video', function ($query) use($user,$model,$name,$description,$lang_video) {
                return $query->with(['inspiration_video' => function($q) use($user,$model,$name,$description,$lang_video){
                    $q->select('id','name','image','description','video',$name,$description,$lang_video,'inspiration_id')
                        ->with(['duration' => function ($que) use($user,$model){
                            $que->select('video_id','id','duration','table_name','total_duration','type')
                                ->where('table_name','Inspiration_video')
                                ->where('type',$model['type'])
                                ->where('user_id',$user->id);
                        },'inspiration' => function ($quer) use($name,$description){
                            $quer->select('id','name',$name,'image','description',$description);
                        }]);
                }]);
            })
            ->when($model['name'] == 'Travel_samaritan', function ($query) use($user,$model,$name,$description,$lang_video) {
                return $query->with(['travel_samaritan' => function($q) use($user,$model,$name,$description,$lang_video){
                    $q->select('id','name','image','video',$name,$lang_video,)
                        ->with(['duration' => function ($que) use($user,$model){
                            $que->select('video_id','id','duration','table_name','total_duration','type')
                                ->where('table_name','Inspiration_video')
                                ->where('type',$model['type'])
                                ->where('user_id',$user->id);
                        }]);
                }]);
            })
            ->when($model['name'] == 'Meditation', function ($query) use($user,$model,$name,$description,$lang_audio) {
                return $query->with(['meditaion' => function($q) use($user,$model,$name,$description,$lang_audio){
                    $q->select('id','name','image','description','audio',$name,$description,$lang_audio)
                        ->with(['duration' => function ($que) use($user,$model){
                            $que->select('video_id','id','duration','table_name','total_duration','type')
                                ->where('table_name','Meditation')
                                ->where('type',$model['type'])
                                ->where('user_id',$user->id);
                        }]);
                }]);
            })
            ->where('type',$model['type'])
            ->where('table_name',$model['name'])
            ->get();
            $bookmark_videos[] = $bookmark_video; 
        }
        if(isset($bookmark_videos) && !empty($bookmark_videos)){
            $all_book_mark = [];
            foreach ($bookmark_videos as $key => $bookmarks) {
                foreach ($bookmarks as $k => $bookmark) {
                    $chapter = [];
                    $chapter['table_name'] = $bookmark['table_name'];
                    $chapter['bookmark_id'] = $bookmark['id'];
                    
                    if($bookmark['table_name'] == 'Chapter'){
                        $chapter['id'] = $bookmark->chapter->id;
                        $chapter['name'] = ($bookmark->chapter->$name != '')?$bookmark->chapter->$name:$bookmark->chapter->name;
                        $chapter['description'] = (isset($bookmark->chapter->description) || $bookmark->chapter->$description != "")?($bookmark->chapter->$description != "")?$bookmark->chapter->$description:$bookmark->chapter->description:"";
                        $chapter['srt'] = ($bookmark->chapter->$srt != '')?$bookmark->chapter->$srt:$bookmark->chapter->srt;
                        $chapter['book_name'] = ($bookmark->chapter->book->$name != '')?$bookmark->chapter->book->$name:$bookmark->chapter->book->name;
                        $chapter['book_id'] = $bookmark->chapter->book->id;
                        if($bookmark['type'] == '0'){
                            $chapter['url'] = ($bookmark->chapter->$lang_video != '')?$bookmark->chapter->$lang_video:$bookmark->chapter->video;
                            $chapter['duration'] = (isset($bookmark->chapter->duration) && !$bookmark->chapter->duration->isEmpty())?$bookmark->chapter->duration[0]->duration:'0';
                            $chapter['total_duration'] = (isset($bookmark->chapter->duration) && !$bookmark->chapter->duration->isEmpty())?$bookmark->chapter->duration[0]->total_duration:'0';
                        }else{
                            $chapter['url'] = ($bookmark->chapter->$lang_audio != '')?$bookmark->chapter->$lang_audio:$bookmark->chapter->audio;
                            $chapter['duration'] = (isset($bookmark->chapter->duration) && !$bookmark->chapter->duration->isEmpty())?$bookmark->chapter->duration[0]->duration:'0';
                            $chapter['total_duration'] = (isset($bookmark->chapter->duration) && !$bookmark->chapter->duration->isEmpty())?$bookmark->chapter->duration[0]->total_duration:'0';
                        }
                        $chapter['srt'] = (isset($bookmark->chapter->srt) || $bookmark->chapter->$srt != '')?($bookmark->chapter->$srt != '')?$bookmark->chapter->$srt:$bookmark->chapter->srt:"";
                        $chapter['image'] = asset("storage/".$bookmark->chapter->image);
                    }elseif($bookmark['table_name'] == 'Daily_prayer'){
                        $chapter['id'] = $bookmark->daily_prayer->id;
                        $chapter['name'] = ($bookmark->daily_prayer->$name != '')?$bookmark->daily_prayer->$name:$bookmark->daily_prayer->name;
                        $chapter['description'] = (isset($bookmark->daily_prayer->description) || $bookmark->daily_prayer->$description != "")?($bookmark->daily_prayer->$description != "")?$bookmark->daily_prayer->$description:$bookmark->daily_prayer->description:"";
                        $chapter['srt'] = "";
                        $chapter['book_name'] = "";
                        $chapter['book_id'] = "";
                        $chapter['url'] = ($bookmark->daily_prayer->$lang_video != '')?$bookmark->daily_prayer->$lang_video:$bookmark->daily_prayer->video;
                        $chapter['duration'] = (isset($bookmark->daily_prayer->duration) && !$bookmark->daily_prayer->duration->isEmpty())?$bookmark->daily_prayer->duration[0]->duration:'0';
                        $chapter['total_duration'] = (isset($bookmark->daily_prayer->duration) && !$bookmark->daily_prayer->duration->isEmpty())?$bookmark->daily_prayer->duration[0]->total_duration:'0';
                        $chapter['image'] = asset("storage/".$bookmark->daily_prayer->image);
                    }elseif($bookmark['table_name'] == 'Night_time_story_video'){
                        $chapter['id'] = $bookmark->night_time_story_video->id;
                        $chapter['name'] = ($bookmark->night_time_story_video->$name != '')?$bookmark->night_time_story_video->$name:$bookmark->night_time_story_video->name;
                        $chapter['description'] = (isset($bookmark->night_time_story_video->description) || $bookmark->night_time_story_video->$description != "")?($bookmark->night_time_story_video->$description != "")?$bookmark->night_time_story_video->$description:$bookmark->night_time_story_video->description:"";
                        $chapter['srt'] = "";
                        $chapter['book_name'] = "";
                        $chapter['book_id'] = "";
                        $chapter['url'] = ($bookmark->night_time_story_video->$lang_video != '')?$bookmark->night_time_story_video->$lang_video:$bookmark->night_time_story_video->video;
                        $chapter['duration'] = (isset($bookmark->night_time_story_video->duration) && !$bookmark->night_time_story_video->duration->isEmpty())?$bookmark->night_time_story_video->duration[0]->duration:'0';
                        $chapter['total_duration'] = (isset($bookmark->night_time_story_video->duration) && !$bookmark->night_time_story_video->duration->isEmpty())?$bookmark->night_time_story_video->duration[0]->total_duration:'0';
                        $chapter['image'] = asset("storage/".$bookmark->night_time_story_video->image);
                    }elseif($bookmark['table_name'] == 'Inspiration_video'){
                        // dd($bookmark);
                        $chapter['id'] = $bookmark->inspiration_video->id;
                        $chapter['name'] = ($bookmark->inspiration_video->$name != '')?$bookmark->inspiration_video->$name:$bookmark->inspiration_video->name;
                        $chapter['description'] = (isset($bookmark->inspiration_video->description) || $bookmark->inspiration_video->$description != "")?($bookmark->inspiration_video->$description != "")?$bookmark->inspiration_video->$description:$bookmark->inspiration_video->description:"";
                        $chapter['srt'] = "";
                        $chapter['book_name'] = ($bookmark->inspiration_video->inspiration->$name != '')?$bookmark->inspiration_video->inspiration->$name:$bookmark->inspiration_video->inspiration->name;
                        $chapter['book_id'] = $bookmark->inspiration_video->inspiration->id;
                        $chapter['url'] = ($bookmark->inspiration_video->$lang_video != '')?$bookmark->inspiration_video->$lang_video:$bookmark->inspiration_video->video;
                        $chapter['duration'] = (isset($bookmark->inspiration_video->duration) && !$bookmark->inspiration_video->duration->isEmpty())?$bookmark->inspiration_video->duration[0]->duration:'0';
                        $chapter['total_duration'] = (isset($bookmark->inspiration_video->duration) && !$bookmark->inspiration_video->duration->isEmpty())?$bookmark->inspiration_video->duration[0]->total_duration:'0';
                        $chapter['image'] = asset("storage/".$bookmark->inspiration_video->image);
                    }elseif($bookmark['table_name'] == 'Travel_samaritan'){
                        $chapter['id'] = $bookmark->travel_samaritan->id;
                        $chapter['name'] = ($bookmark->travel_samaritan->$name != '')?$bookmark->travel_samaritan->$name:$bookmark->travel_samaritan->name;
                        $chapter['description'] = (isset($bookmark->travel_samaritan->description) || $bookmark->travel_samaritan->$description != "")?($bookmark->travel_samaritan->$description != "")?$bookmark->travel_samaritan->$description:$bookmark->travel_samaritan->description:"";
                        $chapter['srt'] = "";
                        $chapter['book_name'] = "";
                        $chapter['book_id'] = "";
                        $chapter['url'] = ($bookmark->travel_samaritan->$lang_video != '')?$bookmark->travel_samaritan->$lang_video:$bookmark->travel_samaritan->video;
                        $chapter['duration'] = (isset($bookmark->travel_samaritan->duration) && !$bookmark->travel_samaritan->duration->isEmpty())?$bookmark->travel_samaritan->duration[0]->duration:'0';
                        $chapter['total_duration'] = (isset($bookmark->travel_samaritan->duration) && !$bookmark->travel_samaritan->duration->isEmpty())?$bookmark->travel_samaritan->duration[0]->total_duration:'0';
                        $chapter['image'] = asset("storage/".$bookmark->travel_samaritan->image);
                    }elseif($bookmark['table_name'] == 'Meditation'){
                        $chapter['id'] = $bookmark->meditation->id;
                        $chapter['name'] = ($bookmark->meditation->$name != '')?$bookmark->meditation->$name:$bookmark->meditation->name;
                        $chapter['description'] = (isset($bookmark->meditation->description) || $bookmark->meditation->$description != "")?($bookmark->meditation->$description != "")?$bookmark->meditation->$description:$bookmark->meditation->description:"";
                        $chapter['srt'] = "";
                        $chapter['book_name'] = "";
                        $chapter['book_id'] = "";
                        $chapter['url'] = ($bookmark->meditation->$lang_audio != '')?$bookmark->meditation->$lang_audio:$bookmark->meditation->audio;
                        $chapter['duration'] = (isset($bookmark->meditation->duration) && !$bookmark->meditation->duration->isEmpty())?$bookmark->meditation->duration[0]->duration:'0';
                        $chapter['total_duration'] = (isset($bookmark->meditation->duration) && !$bookmark->meditation->duration->isEmpty())?$bookmark->meditation->duration[0]->total_duration:'0';
                        $chapter['image'] = asset("storage/".$bookmark->meditation->image);
                    }
                    $chapter['type'] = $bookmark['type'];
                    $chapter['updated_at'] = $bookmark['updated_at'];
                    $all_book_mark[] = $chapter;
                }
            }

            usort($all_book_mark, function($a, $b) {
                return strtotime($b['updated_at']) - strtotime($a['updated_at']);
            });
            $all_book_mark = array_values($all_book_mark);
        // return $all_book_mark;
            $mergedResponse = [
                'message' => 'Bookmark Videos',
                'status' => true,
                'data' => $all_book_mark,
            ];
            return response()->json($mergedResponse);
        }else{
            return $this->sendError(
                'No Data Found',
                ['error' => 'Yout Bookmark List is Empty'],
                200
            );
        }
    }
    
    public function new_duration(){
        $user = Auth::user();
        // $models = ['Chapter'];
        $models = [
            [
                'name' => 'Chapter',
                'type' => 0,
            ],
            [
                'name' => 'Chapter',
                'type' => 1,
            ],
        ];
        $data = json_decode(file_get_contents('php://input'), true); 
        $lang = (isset($data['lang'])) ? $data['lang'] : 'en';
        
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
        $bookmark_videos = [];
        foreach ($models as $key => $model) {
            $bookmark_video = Duration::where('user_id',$user->id)
            ->when($model['name'] == 'Chapter', function ($query) use($user,$model,$name,$description,$lang_audio,$lang_video,$srt) {
                return $query->with(['chapter' => function($q) use($user,$model,$name,$description,$lang_audio,$lang_video,$srt){
                    $q->select('id','name','video_image as image','description','video','audio','srt','book_id',$name,$description,$lang_audio,$lang_video,$srt)
                        ->with(['book' => function ($q) {
                            $q->select('id','name');
                        },'duration' => function ($que) use($user,$model){
                            $que->select('video_id','id','duration','table_name','total_duration','type')
                                ->where('table_name','Chapter')
                                ->where('type',$model['type'])
                                ->where('user_id',$user->id);
                        }]);
                }]);
            })
            ->where('type',$model['type'])
            ->get();
            $bookmark_videos[] = $bookmark_video; 
        }
        if(isset($bookmark_videos) && !empty($bookmark_videos)){
            $all_book_mark = [];
            foreach ($bookmark_videos as $key => $bookmarks) {
                foreach ($bookmarks as $k => $bookmark) {
                    $chapter = [];
                    $chapter['table_name'] = $bookmark['table_name'];
                    $chapter['duration_id'] = $bookmark['id'];
                    if($bookmark['table_name'] == 'Chapter'){
                        $chapter['id'] = $bookmark->chapter->id;
                        $chapter['name'] = ($bookmark->chapter->$name != '')?$bookmark->chapter->$name:$bookmark->chapter->name;
                        $chapter['description'] = (isset($bookmark->chapter->description) || $bookmark->chapter->$description != "")?($bookmark->chapter->$description != "")?$bookmark->chapter->$description:$bookmark->chapter->description:"";
                        $chapter['srt'] = ($bookmark->chapter->$srt != '')?$bookmark->chapter->$srt:$bookmark->chapter->srt;
                        $chapter['book_name'] = ($bookmark->chapter->book->$name != '')?$bookmark->chapter->book->$name:$bookmark->chapter->book->name;
                        $chapter['book_id'] = $bookmark->chapter->book->id;
                        if($bookmark['type'] == '0'){
                            $chapter['url'] = ($bookmark->chapter->$lang_video != '')?$bookmark->chapter->$lang_video:$bookmark->chapter->video;
                            $chapter['duration'] = (isset($bookmark->chapter->duration) && !$bookmark->chapter->duration->isEmpty())?$bookmark->chapter->duration[0]->duration:'0';
                            $chapter['total_duration'] = (isset($bookmark->chapter->duration) && !$bookmark->chapter->duration->isEmpty())?$bookmark->chapter->duration[0]->total_duration:'0';
                        }else{
                            $chapter['url'] = ($bookmark->chapter->$lang_audio != '')?$bookmark->chapter->$lang_audio:$bookmark->chapter->audio;
                            $chapter['duration'] = (isset($bookmark->chapter->duration) && !$bookmark->chapter->duration->isEmpty())?$bookmark->chapter->duration[0]->duration:'0';
                            $chapter['total_duration'] = (isset($bookmark->chapter->duration) && !$bookmark->chapter->duration->isEmpty())?$bookmark->chapter->duration[0]->total_duration:'0';
                        }
                        $chapter['srt'] = (isset($bookmark->chapter->srt) || $bookmark->chapter->$srt != '')?($bookmark->chapter->$srt != '')?$bookmark->chapter->$srt:$bookmark->chapter->srt:"";
                        $chapter['image'] = asset("storage/".$bookmark->chapter->image);
                    }
                    $chapter['type'] = $bookmark['type'];
                    $chapter['updated_at'] = $bookmark['updated_at'];
                    $all_book_mark[] = $chapter;
                }
            }

            usort($all_book_mark, function($a, $b) {
                return strtotime($b['updated_at']) - strtotime($a['updated_at']);
            });
            $all_book_mark = array_values($all_book_mark);
        // return $all_book_mark;
            $mergedResponse = [
                'message' => 'Duration Videos',
                'status' => true,
                'data' => $all_book_mark,
            ];
            return response()->json($mergedResponse);
        }else{
            return $this->sendError(
                'No Data Found',
                ['error' => 'Yout Duration List is Empty'],
                200
            );
        }
    }

    public function view_postcard(){
        $user = Auth::user();
        $user_postcards = User_postcard::where('user_id',$user->id)
            ->select('id','user_id','image')->get();
        if(!$user_postcards->isEmpty()){
            $post_cards = [];
            foreach ($user_postcards as $key => $card) {
                $postcard = [];
                $postcard['id'] = $card->id;
                $postcard['image'] = asset("storage/".$card->image);
                $postcard['url'] = $card->image;
                $post_cards[] = $postcard; 
            }
            $mergedResponse = [
                'message' => 'Post Cards',
                'status' => true,
                'data' => $post_cards,
            ];
            return response()->json($mergedResponse);
        }else{
            return $this->sendError(
                'No Data Found',
                ['error' => 'Yout Postcard List is Empty'],
                200
            );
        }
    }

    public function delete_postcard(){
        $data = json_decode(file_get_contents('php://input'), true); 
        $user = Auth::user();
        $user_postcards = User_postcard::where('user_id',$user->id)
            ->where('id',$data['id'])
            ->delete();
        if(isset($data['url']) && $data['url'] != ''){
            if (Storage::disk('public')->exists($data['url'])) {
                Storage::disk('public')->delete($data['url']);
            }
        }
        $mergedResponse = [
            'message' => 'Post Card Deleted Successfully !',
            'status' => true,
            'data' => $user_postcards,
        ];
        return response()->json($mergedResponse);
       
    }

}
