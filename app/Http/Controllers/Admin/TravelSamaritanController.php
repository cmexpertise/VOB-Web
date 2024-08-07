<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Travel_samaritansDataTable;
use App\Http\Controllers\Controller;
use App\Models\Travel_samaritan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TravelSamaritanController extends Controller
{
    public function index(Travel_samaritansDataTable $DataTable){
        $header = 'Travel Samaritan';
        return $DataTable->render('admin.travel_samaritan.list',compact('header'));
    }

    public function datatable(Travel_samaritansDataTable $DataTable){
        return $DataTable->render('admin.travel_samaritan.list');
    }

    public function add(Request $request){
        $request->validate([
            'name.*' => ['required', 'string'],
            'korean_name.*' => ['required', 'string',],
            'spanish_name.*' => ['required', 'string',],
            'portuguese_name.*' => ['required', 'string',],
            'filipino_name.*' => ['required', 'string'],
            'image.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
            'video.*' => ['required', 'string'],
        ]);
        if ($request->file('web_image')) {
            $request->validate([
                'web_image.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
            ]);
        }
        
        foreach ($request->file('image') as $key => $image) {
            $image_image = 'travel_samaritan_image_' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('travel_samaritan/', $image_image, 'public');
            $image_image = 'travel_samaritan/'.$image_image;
            $web_image = '';
            if ($request->file('web_image')) {
                $web_image = 'travel_samaritan_web_image_'. time() . '.' . $request->file('web_image')[$key]->getClientOriginalExtension();
                $request->file('web_image')[$key]->storeAs('travel_samaritan/',$web_image,'public');
                $web_image = 'travel_samaritan/'.$web_image;
            }

            $travel_samaritan = new Travel_samaritan();
            $travel_samaritan->name = $request->name[$key];
            $travel_samaritan->korean_name = $request->korean_name[$key];
            $travel_samaritan->spanish_name = $request->spanish_name[$key];
            $travel_samaritan->portuguese_name = $request->portuguese_name[$key];
            $travel_samaritan->filipino_name = $request->filipino_name[$key];
            $travel_samaritan->video = $request->video[$key];
            $travel_samaritan->korean_video = $request->korean_video[$key];
            $travel_samaritan->spanish_video = $request->spanish_video[$key];
            $travel_samaritan->portuguese_video = $request->portuguese_video[$key];
            $travel_samaritan->filipino_video = $request->filipino_video[$key];
            $travel_samaritan->srt = $request->srt[$key];
            $travel_samaritan->korean_srt = $request->korean_srt[$key];
            $travel_samaritan->spanish_srt = $request->spanish_srt[$key];
            $travel_samaritan->portuguese_srt = $request->portuguese_srt[$key];
            $travel_samaritan->filipino_srt = $request->filipino_srt[$key];
            $travel_samaritan->featured_video = $request->featured_video[$key];
            $travel_samaritan->image = $image_image;
            $travel_samaritan->web_image = $web_image;
            $travel_samaritan->save();
        }
        toastr('Your data has been saved');
        return redirect()->route('travel_samaritans');
    }

    public function delete(Request $request){
        $chapter = Travel_samaritan::select('image','web_image')
            ->where('id',decrypt($request->id))
            ->get();
        Travel_samaritan::where('id', decrypt($request->id))->delete();
        if (Storage::disk('public')->exists($chapter[0]->image)) {
            Storage::disk('public')->delete($chapter[0]->image);
        }
        if($chapter[0]->web_image != null){
            if (Storage::disk('public')->exists($chapter[0]->web_image)) {
                Storage::disk('public')->delete($chapter[0]->web_image);
            }
        }

        toastr('Travel Samaritan deleted successfully !');
        return redirect()->route('travel_samaritans');
    }

    public function edit(Request $request){
        
        $travel_samaritans = Travel_samaritan::where('id',$request->id)
            ->get();
        $travel = $travel_samaritans[0]; 
        $html = view('admin.travel_samaritan.edit',compact('travel'));
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
        $image_image = $request->old_image;
        $web_image = $request->old_web_image;
        if ($request->file('image')) {
           
            $request->validate([
                'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
            ]);

            $image_image = 'travel_samaritan_image_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('travel_samaritan/', $image_image, 'public');
            $image_image = 'travel_samaritan/'.$image_image;

            if (Storage::disk('public')->exists($request->old_image)) {
                Storage::disk('public')->delete($request->old_image);
            }
        }
        if ($request->file('web_image')) {
           
            $request->validate([
                'web_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
            ]);

            $web_image = 'travel_samaritan_web_image_'. time() . '.' . $request->file('web_image')->getClientOriginalExtension();
            $request->file('web_image')->storeAs('travel_samaritan/',$web_image,'public');
            $web_image = 'travel_samaritan/'.$web_image;

            if (Storage::disk('public')->exists($request->old_web_image)) {
                Storage::disk('public')->delete($request->old_web_image);
            }
        }

        Travel_samaritan::where('id',$request->id)
            ->update([
            'name'=> $request->name,
            'korean_name'=> $request->korean_name,
            'spanish_name'=> $request->spanish_name,
            'portuguese_name'=> $request->portuguese_name,
            'filipino_name'=> $request->filipino_name,
            'video'=> $request->video,
            'korean_video'=> $request->korean_video,
            'spanish_video'=> $request->spanish_video,
            'portuguese_video'=> $request->portuguese_video,
            'filipino_video'=> $request->filipino_video,
            'srt'=> $request->srt,
            'korean_srt'=> $request->korean_srt,
            'spanish_srt'=> $request->spanish_srt,
            'portuguese_srt'=> $request->portuguese_srt,
            'filipino_srt'=> $request->filipino_srt,
            'featured_video'=> $request->featured_video,
            'image' => $image_image,
            'web_image' => $web_image,
            ]);
        toastr('Your data has been saved');
        return redirect()->route('travel_samaritans');
    }
}
