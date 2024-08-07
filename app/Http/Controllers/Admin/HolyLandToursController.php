<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\HolyLandPrayersDataTable;
use App\Http\Controllers\Controller;
use App\Models\Feature_movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HolyLandToursController extends Controller
{
    public function index(HolyLandPrayersDataTable $DataTable){
        $header = 'Holy Land Tours';
        $feature_movie = Feature_movie::orderBy('id','DESC')
            ->get();
        return $DataTable->render('admin.holy_land_tours.list',compact('header','feature_movie'));
    }

    public function datatable(HolyLandPrayersDataTable $DataTable){
        return $DataTable->render('admin.holy_land_tours.list');
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
        
        $image = 'holylandtours_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->storeAs('holylandtours/', $image, 'public');
        $image = 'holylandtours/'.$image;
        

        $feature_movie = new Feature_movie();
        $feature_movie->name = $request->name;
        $feature_movie->korean_name = $request->korean_name;
        $feature_movie->spanish_name = $request->spanish_name;
        $feature_movie->portuguese_name = $request->portuguese_name;
        $feature_movie->filipino_name = $request->filipino_name;
        $feature_movie->video = $request->video;
        $feature_movie->korean_video = $request->korean_video;
        $feature_movie->spanish_video = $request->spanish_video;
        $feature_movie->portuguese_video = $request->portuguese_video;
        $feature_movie->filipino_video = $request->filipino_video;
        $feature_movie->image = $image;
        $feature_movie->save();
        
        toastr('Your data has been saved');
        return redirect()->route('holylandtours');
    }

    public function edit(Request $request){
        
        $feature_movies = Feature_movie::where('id',$request->id)
            ->get();
        $feature_movie = $feature_movies[0]; 
        $html = view('admin.holy_land_tours.edit',compact('feature_movie'));
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

            $image = 'holylandtours_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('holylandtours/', $image, 'public');
            $image = 'holylandtours/'.$image;

            if (Storage::disk('public')->exists($request->old_image)) {
                Storage::disk('public')->delete($request->old_image);
            }
        }

        Feature_movie::where('id',$request->id)
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
            'image' => $image,
            ]);
        toastr('Your data has been saved');
        return redirect()->route('holylandtours');
    }

    public function delete(Request $request){
        $chapter = Feature_movie::select('image')
            ->where('id',decrypt($request->id))
            ->get();
            Feature_movie::where('id', decrypt($request->id))->delete();
        if (Storage::disk('public')->exists($chapter[0]->image)) {
            Storage::disk('public')->delete($chapter[0]->image);
        }

        toastr('Feature movie deleted successfully !');
        return redirect()->route('holylandtours');
    }
}
