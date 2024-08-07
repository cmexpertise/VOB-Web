<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Inspiration_videoDataTable;
use App\DataTables\InspirationDataTable;
use App\Http\Controllers\Controller;
use App\Models\Inspiration;
use App\Models\Inspiration_video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InspirationsController extends Controller
{
    public function index(InspirationDataTable $DataTable){
        $header = 'Meditation Videos';
        return $DataTable->render('admin.inspirations.list',compact('header'));
    }

    public function datatable(InspirationDataTable $DataTable){
        return $DataTable->render('admin.inspirations.list');
    }

    public function add(Request $request){
        $request->validate([
            'name' => ['required', 'string'],
            'korean_name' => ['required', 'string',],
            'spanish_name' => ['required', 'string',],
            'portuguese_name' => ['required', 'string',],
            'filipino_name' => ['required', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp'],
        ]);
        
        $image = 'inspiration_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->storeAs('inspiration/', $image, 'public');
        $image = 'inspiration/'.$image;
        

        $inspiration = new Inspiration();
        $inspiration->name = $request->name;
        $inspiration->korean_name = $request->korean_name;
        $inspiration->spanish_name = $request->spanish_name;
        $inspiration->portuguese_name = $request->portuguese_name;
        $inspiration->filipino_name = $request->filipino_name;
        $inspiration->description = $request->description;
        $inspiration->korean_description = $request->korean_description;
        $inspiration->spanish_description = $request->spanish_description;
        $inspiration->portuguese_description = $request->portuguese_description;
        $inspiration->filipino_description = $request->filipino_description;
        $inspiration->image = $image;
        $inspiration->save();
        
        toastr('Your data has been saved');
        return redirect()->route('inspirations');
    }

    public function edit(Request $request){
        $inspirations = Inspiration::where('id',$request->id)
            ->get();
        $inspiration = $inspirations[0]; 
        $html = view('admin.inspirations.edit',compact('inspiration'));
        echo $html;
    }

    public function update(Request $request){
        $request->validate([
            'name' => ['required', 'string'],
            'korean_name' => ['required', 'string',],
            'spanish_name' => ['required', 'string',],
            'portuguese_name' => ['required', 'string',],
            'filipino_name' => ['required', 'string'],
        ]);
        $image = $request->old_image;
        if ($request->file('image')) {
            $request->validate([
                'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp'],
            ]);
            $image = 'inspiration_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('inspiration/', $image, 'public');
            $image = 'inspiration/'.$image;

            if (Storage::disk('public')->exists($request->old_image)) {
                Storage::disk('public')->delete($request->old_image);
            }
        }

        Inspiration::where('id',$request->id)
            ->update([
            'name'=> $request->name,
            'korean_name'=> $request->korean_name,
            'spanish_name'=> $request->spanish_name,
            'portuguese_name'=> $request->portuguese_name,
            'filipino_name'=> $request->filipino_name,
            'description' => $request->description,
            'korean_description' => $request->korean_description,
            'spanish_description' => $request->spanish_description,
            'portuguese_description' => $request->portuguese_description,
            'filipino_description' => $request->filipino_description,
            'image' => $image,
            ]);
        toastr('Your data has been saved');
        return redirect()->route('inspirations');
    }

    public function delete(Request $request){
        $chapter = Inspiration::select('image')
            ->where('id',decrypt($request->id))
            ->get();
        Inspiration::where('id', decrypt($request->id))->delete();
        if (Storage::disk('public')->exists($chapter[0]->image)) {
            Storage::disk('public')->delete($chapter[0]->image);
        }
        toastr('Meditation deleted successfully !');
        return redirect()->route('inspirations');
    }

    public function add_video(Request $request,Inspiration_videoDataTable $DataTable){
        $name = decrypt($request->name);
        $header = 'Meditation Videos of '.$name;
        $inspiration = decrypt($request->id);
        return $DataTable->render('admin.inspiration_videos.list',compact('header','inspiration','name'));
    }

    public function insert_video(Request $request){
        $request->validate([
            'name' => ['required', 'string'],
            'korean_name' => ['required', 'string',],
            'spanish_name' => ['required', 'string',],
            'portuguese_name' => ['required', 'string',],
            'filipino_name' => ['required', 'string'],
            'video' => ['required', 'string'],
            'inspiration_id' => ['required', 'integer'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp'],
        ]);
        
        $image = 'inspiration_video_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->storeAs('inspiration_video/', $image, 'public');
        $image = 'inspiration_video/'.$image;
        

        $inspiration = new Inspiration_video();
        $inspiration->inspiration_id = $request->inspiration_id;
        $inspiration->name = $request->name;
        $inspiration->korean_name = $request->korean_name;
        $inspiration->spanish_name = $request->spanish_name;
        $inspiration->portuguese_name = $request->portuguese_name;
        $inspiration->filipino_name = $request->filipino_name;
        $inspiration->description = $request->description;
        $inspiration->korean_description = $request->korean_description;
        $inspiration->spanish_description = $request->spanish_description;
        $inspiration->portuguese_description = $request->portuguese_description;
        $inspiration->filipino_description = $request->filipino_description;
        $inspiration->video = $request->video;
        $inspiration->korean_video = $request->korean_video;
        $inspiration->spanish_video = $request->spanish_video;
        $inspiration->portuguese_video = $request->portuguese_video;
        $inspiration->filipino_video = $request->filipino_video;
        $inspiration->srt = $request->srt;
        $inspiration->korean_srt = $request->korean_srt;
        $inspiration->spanish_srt = $request->spanish_srt;
        $inspiration->portuguese_srt = $request->portuguese_srt;
        $inspiration->filipino_srt = $request->filipino_srt;
        $inspiration->image = $image;
        $inspiration->save();
        
        toastr('Your data has been saved');
        return redirect()->route('inspirations.add_video',['id'=>encrypt($request->inspiration_id),'name' => encrypt($request->inspiration_name)]);
    }

    public function video_datatable(Request $request,Inspiration_videoDataTable $DataTable){
        return $DataTable->render('admin.inspiration_videos.list');
    }

    public function edit_video(Request $request){
        $inspirations = Inspiration_video::with(['inspiration' => function ($query){
            $query->select('id','name');
        }])
            ->where('id',$request->id)
            ->get();
        $inspiration = $inspirations[0]; 
        $html = view('admin.inspiration_videos.edit',compact('inspiration'));
        echo $html;
    }

    public function update_video(Request $request){
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
            $image = 'inspiration_video_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('inspiration_video/', $image, 'public');
            $image = 'inspiration_video/'.$image;

            if (Storage::disk('public')->exists($request->old_image)) {
                Storage::disk('public')->delete($request->old_image);
            }
        }

        Inspiration_video::where('id',$request->id)
            ->update([
            'name'=> $request->name,
            'korean_name'=> $request->korean_name,
            'spanish_name'=> $request->spanish_name,
            'portuguese_name'=> $request->portuguese_name,
            'filipino_name'=> $request->filipino_name,
            'description' => $request->description,
            'korean_description' => $request->korean_description,
            'spanish_description' => $request->spanish_description,
            'portuguese_description' => $request->portuguese_description,
            'filipino_description' => $request->filipino_description,
            'video' => $request->video,
            'korean_video' => $request->korean_video,
            'spanish_video' => $request->spanish_video,
            'portuguese_video' => $request->portuguese_video,
            'filipino_video' => $request->filipino_video,
            'srt' => $request->srt,
            'korean_srt' => $request->korean_srt,
            'spanish_srt' => $request->spanish_srt,
            'portuguese_srt' => $request->portuguese_srt,
            'filipino_srt' => $request->filipino_srt,
            'image' => $image,
            ]);
        toastr('Your data has been saved');
        return redirect()->route('inspirations.add_video',['id'=>encrypt($request->inspiration_id),'name'=>encrypt($request->inspiration_name)]);
    }

    public function delete_video(Request $request){
        $inspiration = Inspiration_video::with(['inspiration' => function ($query){
            $query->select('id','name');
        }])
            ->select('image','inspiration_id')
            ->where('id',decrypt($request->id))
            ->get();
        Inspiration_video::where('id', decrypt($request->id))->delete();
        if (Storage::disk('public')->exists($inspiration[0]->image)) {
            Storage::disk('public')->delete($inspiration[0]->image);
        }
        toastr('Mediation Video deleted successfully !');
        return redirect()->route('inspirations.add_video',['id'=>encrypt($inspiration[0]->inspiration->id),'name'=>encrypt($inspiration[0]->inspiration->name)]);
    }
}
