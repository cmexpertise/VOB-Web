<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Daily_prayersDataTable;
use App\Http\Controllers\Controller;
use App\Models\Daily_prayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DailyPrayerController extends Controller
{
    public function index(Daily_prayersDataTable $DataTable){
        $header = 'Broadcasts';
        
        return $DataTable->render('admin.daily_prayer.list',compact('header'));
    }

    public function add(Request $request){
        $request->validate([
            'name' => ['required', 'string'],
            'korean_name' => ['required', 'string',],
            'spanish_name' => ['required', 'string',],
            'portuguese_name' => ['required', 'string',],
            'filipino_name' => ['required', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp'],
            'video' => ['required', 'string'],
        ]);
        
        $image = 'prayer_image_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->storeAs('daily_prayer/', $image, 'public');
        $image = 'daily_prayer/'.$image;
        

        $daily_prayer = new Daily_prayer();
        $daily_prayer->name = $request->name;
        $daily_prayer->korean_name = $request->korean_name;
        $daily_prayer->spanish_name = $request->spanish_name;
        $daily_prayer->portuguese_name = $request->portuguese_name;
        $daily_prayer->filipino_name = $request->filipino_name;
        $daily_prayer->title = $request->title;
        $daily_prayer->korean_title = $request->korean_title;
        $daily_prayer->spanish_title = $request->spanish_title;
        $daily_prayer->portuguese_title = $request->portuguese_title;
        $daily_prayer->filipino_title = $request->filipino_title;
        $daily_prayer->video = $request->video;
        $daily_prayer->korean_video = $request->korean_video;
        $daily_prayer->spanish_video = $request->spanish_video;
        $daily_prayer->portuguese_video = $request->portuguese_video;
        $daily_prayer->filipino_video = $request->filipino_video;
        $daily_prayer->image = $image;
        $daily_prayer->save();
        
        toastr('Your data has been saved');
        return redirect()->route('daily_prayer');
    }

    public function datatable(Daily_prayersDataTable $DataTable){
        return $DataTable->render('admin.daily_prayer.list');
    }

    public function edit(Request $request){
        
        $prayers = Daily_prayer::where('id',$request->id)
            ->get();
        $prayer = $prayers[0]; 
        $html = view('admin.daily_prayer.edit',compact('prayer'));
        echo $html;
    }

    public function update(Request $request){
        $request->validate([
            'name' => ['required', 'string'],
            'korean_name' => ['required', 'string',],
            'spanish_name' => ['required', 'string',],
            'portuguese_name' => ['required', 'string',],
            'filipino_name' => ['required', 'string'],
            'video' => ['required', 'string'],
        ]);
        $image = $request->old_image;
        if ($request->file('image')) {
           
            $request->validate([
                'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp'],
            ]);

            $image = 'prayer_image_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('daily_prayer/', $image, 'public');
            $image = 'daily_prayer/'.$image;

            if (Storage::disk('public')->exists($request->old_image)) {
                Storage::disk('public')->delete($request->old_image);
            }
        }

        Daily_prayer::where('id',$request->id)
            ->update([
            'name'=> $request->name,
            'korean_name'=> $request->korean_name,
            'spanish_name'=> $request->spanish_name,
            'portuguese_name'=> $request->portuguese_name,
            'filipino_name'=> $request->filipino_name,
            'title'=> $request->title,
            'korean_title'=> $request->korean_title,
            'spanish_title'=> $request->spanish_title,
            'portuguese_title'=> $request->portuguese_title,
            'filipino_title'=> $request->filipino_title,
            'video'=> $request->video,
            'korean_video'=> $request->korean_video,
            'spanish_video'=> $request->spanish_video,
            'portuguese_video'=> $request->portuguese_video,
            'filipino_video'=> $request->filipino_video,
            'image' => $image,
            ]);
        toastr('Your data has been saved');
        return redirect()->route('daily_prayer');
    }

    public function delete(Request $request){
        $chapter = Daily_prayer::select('image')
            ->where('id',decrypt($request->id))
            ->get();
        Daily_prayer::where('id', decrypt($request->id))->delete();
        if (Storage::disk('public')->exists($chapter[0]->image)) {
            Storage::disk('public')->delete($chapter[0]->image);
        }

        toastr('Prayer deleted successfully !');
        return redirect()->route('daily_prayer');
    }
}
