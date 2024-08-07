<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\NightTimeStoryDataTable;
use App\DataTables\NightTimeStoryVideoDataTable;
use App\Http\Controllers\Controller;
use App\Models\Night_time_story;
use App\Models\Night_time_story_video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NightTimeStoriesController extends Controller
{
    public function index(NightTimeStoryDataTable $DataTable){
        $header = 'Night Time Stories';
        return $DataTable->render('admin.night_time_stories.list',compact('header'));
    }

    public function datatable(NightTimeStoryDataTable $DataTable){
        return $DataTable->render('admin.night_time_stories.list');
    }

    public function add(Request $request){
        $request->validate([
            'name' => ['required', 'string'],
            'korean_name' => ['required', 'string',],
            'spanish_name' => ['required', 'string',],
            'portuguese_name' => ['required', 'string',],
            'filipino_name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'korean_description' => ['required', 'string'],
            'spanish_description' => ['required', 'string'],
            'portuguese_description' => ['required', 'string'],
            'filipino_description' => ['required', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp'],
        ]);
        
        $image = 'night_time_story_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->storeAs('night_time_story/', $image, 'public');
        $image = 'night_time_story/'.$image;
        

        $nighttimestory = new Night_time_story();
        $nighttimestory->name = $request->name;
        $nighttimestory->korean_name = $request->korean_name;
        $nighttimestory->spanish_name = $request->spanish_name;
        $nighttimestory->portuguese_name = $request->portuguese_name;
        $nighttimestory->filipino_name = $request->filipino_name;
        $nighttimestory->description = $request->description;
        $nighttimestory->korean_description = $request->korean_description;
        $nighttimestory->spanish_description = $request->spanish_description;
        $nighttimestory->portuguese_description = $request->portuguese_description;
        $nighttimestory->filipino_description = $request->filipino_description;
        $nighttimestory->image = $image;
        $nighttimestory->save();
        
        toastr('Your data has been saved');
        return redirect()->route('nighttimestories');
    }

    public function delete(Request $request){
        $chapter = Night_time_story::select('image')
            ->where('id',decrypt($request->id))
            ->get();
        Night_time_story::where('id', decrypt($request->id))->delete();
        if (Storage::disk('public')->exists($chapter[0]->image)) {
            Storage::disk('public')->delete($chapter[0]->image);
        }
        toastr('Night time story deleted successfully !');
        return redirect()->route('nighttimestories');
    }

    public function edit(Request $request){
        $stories = Night_time_story::where('id',$request->id)
            ->get();
        $story = $stories[0]; 
        $html = view('admin.night_time_stories.edit',compact('story'));
        echo $html;
    }

    public function update(Request $request){
        $request->validate([
            'name' => ['required', 'string'],
            'korean_name' => ['required', 'string',],
            'spanish_name' => ['required', 'string',],
            'portuguese_name' => ['required', 'string',],
            'filipino_name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'korean_description' => ['required', 'string'],
            'spanish_description' => ['required', 'string'],
            'portuguese_description' => ['required', 'string'],
            'filipino_description' => ['required', 'string'],
        ]);
        $image = $request->old_image;
        if ($request->file('image')) {
            $request->validate([
                'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp'],
            ]);
            $image = 'night_time_story_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('night_time_story/', $image, 'public');
            $image = 'night_time_story/'.$image;

            if (Storage::disk('public')->exists($request->old_image)) {
                Storage::disk('public')->delete($request->old_image);
            }
        }

        Night_time_story::where('id',$request->id)
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
        return redirect()->route('nighttimestories');
    }

    public function add_video(Request $request,NightTimeStoryVideoDataTable $DataTable){
        $name = decrypt($request->name);
        $header = 'Night Time Story Videos of '.$name;
        $story = decrypt($request->id);
        return $DataTable->render('admin.night_time_story_video.list',compact('header','story','name'));
    }

    public function video_datatable(NightTimeStoryVideoDataTable $DataTable){
        return $DataTable->render('admin.night_time_story_video.list');
    }

    public function insert_video(Request $request){
        $request->validate([
            'name' => ['required', 'string'],
            'korean_name' => ['required', 'string',],
            'spanish_name' => ['required', 'string',],
            'portuguese_name' => ['required', 'string',],
            'filipino_name' => ['required', 'string'],
            'video' => ['required', 'string'],
            'story_id' => ['required', 'integer'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp'],
            'description' => ['required', 'string'],
            'korean_description' => ['required', 'string'],
            'spanish_description' => ['required', 'string'],
            'portuguese_description' => ['required', 'string'],
            'filipino_description' => ['required', 'string'],
        ]);
        
        $image = 'night_time_story_video_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->storeAs('night_time_story_video/', $image, 'public');
        $image = 'night_time_story_video/'.$image;
        

        $story_video = new Night_time_story_video();
        $story_video->night_time_story_id = $request->story_id;
        $story_video->name = $request->name;
        $story_video->korean_name = $request->korean_name;
        $story_video->spanish_name = $request->spanish_name;
        $story_video->portuguese_name = $request->portuguese_name;
        $story_video->filipino_name = $request->filipino_name;
        $story_video->description = $request->description;
        $story_video->korean_description = $request->korean_description;
        $story_video->spanish_description = $request->spanish_description;
        $story_video->portuguese_description = $request->portuguese_description;
        $story_video->filipino_description = $request->filipino_description;
        $story_video->video = $request->video;
        $story_video->korean_video = $request->korean_video;
        $story_video->spanish_video = $request->spanish_video;
        $story_video->portuguese_video = $request->portuguese_video;
        $story_video->filipino_video = $request->filipino_video;
        $story_video->srt = $request->srt;
        $story_video->korean_srt = $request->korean_srt;
        $story_video->spanish_srt = $request->spanish_srt;
        $story_video->portuguese_srt = $request->portuguese_srt;
        $story_video->filipino_srt = $request->filipino_srt;
        $story_video->image = $image;
        $story_video->save();
        
        toastr('Your data has been saved');
        return redirect()->route('nighttimestories.add_video',['id'=>encrypt($request->story_id),'name' => encrypt($request->story_name)]);
    }

    public function edit_video(Request $request){
        $stories = Night_time_story_video::with(['night_time_story' => function ($query){
            $query->select('id','name');
        }])
            ->where('id',$request->id)
            ->get();
        $story = $stories[0]; 
        $html = view('admin.night_time_story_video.edit',compact('story'));
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
            'description' => ['required', 'string'],
            'korean_description' => ['required', 'string'],
            'spanish_description' => ['required', 'string'],
            'portuguese_description' => ['required', 'string'],
            'filipino_description' => ['required', 'string'],
        ]);
        $image = $request->old_image;
        if ($request->file('image')) {
            $request->validate([
                'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp'],
            ]);
            $image = 'night_time_story_video_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('night_time_story_video/', $image, 'public');
            $image = 'night_time_story_video/'.$image;

            if (Storage::disk('public')->exists($request->old_image)) {
                Storage::disk('public')->delete($request->old_image);
            }
        }

        Night_time_story_video::where('id',$request->id)
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
        return redirect()->route('nighttimestories.add_video',['id'=>encrypt($request->story_id),'name'=>encrypt($request->story_name)]);
    }

    public function delete_video(Request $request){
        
        $story = Night_time_story_video::with(['night_time_story' => function ($query){
            $query->select('id','name');
        }])
            ->select('image','night_time_story_id')
            ->where('id',decrypt($request->id))
            ->get();
        Night_time_story_video::where('id', decrypt($request->id))->delete();
        if (Storage::disk('public')->exists($story[0]->image)) {
            Storage::disk('public')->delete($story[0]->image);
        }
        toastr('Night Time Story Video deleted successfully !');
        return redirect()->route('nighttimestories.add_video',['id'=>encrypt($story[0]->night_time_story->id),'name'=>encrypt($story[0]->night_time_story->name)]);
    }
}
