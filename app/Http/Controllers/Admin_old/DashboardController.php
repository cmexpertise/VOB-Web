<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserAnalyticsDataTable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Inspiration;
use App\Models\Inspiration_video;
use App\Models\Mail_format;
use App\Models\Night_time_story_video;
use App\Models\Quote;
use App\Models\Smtp_setting;
use App\Models\Subscription;
use App\Models\Travel_samaritan;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        
    }

    public function index(){
        $users = User::select('id')
            ->where('role_id','!=',1)
            ->count();
        $book = Book::count();
        $video = Chapter::where('video','!=',null)
            ->count();
        $audio = Chapter::where('audio','!=',null)
            ->count();
        $quote = Quote::count();
        $travel_samaritan = Travel_samaritan::count();
        $inspiration = Inspiration_video::count();
        $night_story = Night_time_story_video::count();
        $monthwise_array = [];
        $monthlyData = User::select(DB::raw('MONTH(created_at) AS month'), DB::raw('COUNT(id) as total_user'))
            ->whereYear(DB::raw('created_at'), date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->where('role_id','!=',1)
            ->get();
        if($monthlyData){
            foreach($monthlyData as $temp){
                $monthwise_array[$temp->month] = $temp;
            }
        }
        $monthwise_array_sbscr = [];
        $monthlyData = Subscription::select(DB::raw('MONTH(created_at) AS month'), DB::raw('COUNT(id) as total_user'))
            ->whereYear(DB::raw('created_at'), date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
        if($monthlyData){
            foreach($monthlyData as $temp){
                $monthwise_array_sbscr[$temp->month] = $temp;
            }
        }
        $header = 'Dashboard';
        return view('dashboard',compact('users','night_story','travel_samaritan','inspiration','header','book','video','audio','quote','monthwise_array','monthwise_array_sbscr'));
    }

    public function user_analytics(UserAnalyticsDataTable $dataTable){
        $header = 'User Analytics';
        return $dataTable->render('admin.user_analytics',compact('header'));
    }

    public function user_analytics_datatable(UserAnalyticsDataTable $dataTable){
        return $dataTable->render('admin.user_analytics');
    }

    public function notification(){
        $header = 'Send Notification';
        return view('notification',compact('header'));
    }

    public function smtp_setting(){
        $header = 'SMTP Setting';
        $mail = Smtp_setting::first();
        return view('smtp_setting',compact('header','mail'));
    }
    
    public function mail_format(){
        $header = 'SMTP Setting';
        $mail = Mail_format::first();
        return view('mail_format',compact('header','mail'));
    }

    public function add_update_smtp(Request $request){
        $request->validate([
            'host' => ['required', 'string'],
            'port' => ['required', 'string'],
            'user' => ['required', 'string',],
            'password' => ['required', 'string',],
            'from_mail' => ['required', 'string',],
            'name' => ['required', 'string',],
        ]);
        if(isset($request->id) && $request->id != null){
            Smtp_setting::where('id',decrypt($request->id))
                ->update([
                    'host' => $request->host,
                    'port' => $request->port,
                    'user' => $request->user,
                    'password' => $request->password,
                    'from_mail' => $request->from_mail,
                    'encryption' => $request->encryption,
                    'name' => $request->name,
                ]);
        }else{
            $smtp = new Smtp_setting();
            $smtp->host = $request->host;
            $smtp->port = $request->port;
            $smtp->user = $request->user;
            $smtp->password = $request->password;
            $smtp->from_mail = $request->from_mail;
            $smtp->encryption = $request->encryption;
            $smtp->name = $request->name;
            $smtp->save();
        }

        toastr('Your data has been saved');
        return redirect()->route('admin.smtp_setting');
    }

    public function add_mail_format(Request $request){
        $request->validate([
            'subject' => ['required', 'string'],
            'title' => ['required', 'string'],
            'body' => ['required', 'string',],
        ]);
        if(isset($request->id) && $request->id != null){
            Mail_format::where('id',decrypt($request->id))
                ->update([
                    'subject' => $request->subject,
                    'title' => $request->title,
                    'body' => $request->body,
                ]);
        }else{
            $smtp = new Mail_format();
            $smtp->subject = $request->subject;
            $smtp->title = $request->title;
            $smtp->body = $request->body;
            $smtp->save();
        }

        toastr('Your data has been saved');
        return redirect()->route('admin.mail_format');
    }

    public function reset(){
        return view('reset');
    }


}
