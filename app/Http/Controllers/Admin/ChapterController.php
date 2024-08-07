<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ChaptersDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function index(ChaptersDataTable $DataTable){
        $header = 'Chapter';
        $books = Book::select('id','name')    
            ->get();
        return $DataTable->render('admin.chapters.list',compact('header','books'));
    }

    public function datatable(ChaptersDataTable $DataTable){
        return $DataTable->render('admin.chapters.list');
    }

    public function add(Request $request){
        $request->validate([
            'book_id.*' => ['required', 'string'],
            'name.*' => ['required', 'string'],
            'korean_name.*' => ['required', 'string',],
            'spanish_name.*' => ['required', 'string',],
            'portuguese_name.*' => ['required', 'string',],
            'filipino_name.*' => ['required', 'string'],
            'description.*' => ['required', 'string'],
            'korean_description.*' => ['required', 'string'],
            'spanish_description.*' => ['required', 'string'],
            'portuguese_description.*' => ['required', 'string'],
            'filipino_description.*' => ['required', 'string'],
            'video_image.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
            'audio_image.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
            'video.*' => ['required', 'string'],
        ]);

        foreach ($request->file('video_image') as $key => $video) {
            $video_image = 'chapter_video_image_' . time() . '.' . $video->getClientOriginalExtension();
            $video->storeAs('chapter/video/', $video_image, 'public');
            $video_image = 'chapter/video/'.$video_image;

            $audio_image = 'chapter_audio_image_'. time() . '.' . $request->file('audio_image')[$key]->getClientOriginalExtension();
            $request->file('audio_image')[$key]->storeAs('chapter/audio/',$audio_image,'public');
            $audio_image = 'chapter/audio/'.$audio_image;

            $chapter = new Chapter();
            $chapter->book_id = $request->book_id[$key];
            $chapter->name = $request->name[$key];
            $chapter->korean_name = $request->korean_name[$key];
            $chapter->spanish_name = $request->spanish_name[$key];
            $chapter->portuguese_name = $request->portuguese_name[$key];
            $chapter->filipino_name = $request->filipino_name[$key];
            $chapter->description = $request->description[$key];
            $chapter->korean_description = $request->korean_description[$key];
            $chapter->spanish_description = $request->spanish_description[$key];
            $chapter->portuguese_description = $request->portuguese_description[$key];
            $chapter->filipino_description = $request->filipino_description[$key];
            $chapter->video = $request->video[$key];
            $chapter->korean_video = $request->korean_video[$key];
            $chapter->spanish_video = $request->spanish_video[$key];
            $chapter->portuguese_video = $request->portuguese_video[$key];
            $chapter->filipino_video = $request->filipino_video[$key];
            $chapter->audio = $request->audio[$key];
            $chapter->korean_audio = $request->korean_audio[$key];
            $chapter->spanish_audio = $request->spanish_audio[$key];
            $chapter->portuguese_audio = $request->portuguese_audio[$key];
            $chapter->filipino_audio = $request->filipino_audio[$key];
            $chapter->srt = $request->srt[$key];
            $chapter->korean_srt = $request->korean_srt[$key];
            $chapter->spanish_srt = $request->spanish_srt[$key];
            $chapter->portuguese_srt = $request->portuguese_srt[$key];
            $chapter->filipino_srt = $request->filipino_srt[$key];
            $chapter->video_image = $video_image;
            $chapter->audio_image = $audio_image;
            $chapter->save();
        }
        toastr('Your data has been saved');
        return redirect()->route('chapters');
    }

    public function delete(Request $request){
        $chapter = Chapter::select('video_image','audio_image')
            ->where('id',decrypt($request->id))
            ->get();
        Chapter::where('id', decrypt($request->id))->delete();
        
        if (Storage::disk('public')->exists($chapter[0]->video_image)) {
            Storage::disk('public')->delete($chapter[0]->video_image);
        }
        if (Storage::disk('public')->exists($chapter[0]->audio_image)) {
            Storage::disk('public')->delete($chapter[0]->audio_image);
        }

        toastr('Chapter deleted successfully !');
        return redirect()->route('chapters');
    }

    public function edit(Request $request){
        $books = Book::select('id','name')    
            ->get();
        $chapters = Chapter::with(['book' => function ($query){
            $query->select('id','name');
        }])
            ->where('id',$request->id)
            ->get();
        $chapter = $chapters[0]; 
        $html = view('admin.chapters.edit',compact('chapter','books'));
        echo $html;
    }

    public function update(Request $request){
        $request->validate([
            'book_id' => ['required', 'string'],
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
            'video' => ['required', 'string'],
        ]);
        $video_image = $request->old_video_image;
        $audio_image = $request->old_audio_image;
        if ($request->file('video_image')) {
           
            $request->validate([
                'video_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
            ]);

            $video_image = 'chapter_video_image_' . time() . '.' . $request->file('video_image')->getClientOriginalExtension();
            $request->file('video_image')->storeAs('chapter/video/', $video_image, 'public');
            $video_image = 'chapter/video/'.$video_image;
            if(isset($request->old_video_image) && $request->old_video_image != ''){
                if (Storage::disk('public')->exists($request->old_video_image)) {
                    Storage::disk('public')->delete($request->old_video_image);
                }
            }
        }
        if ($request->file('audio_image')) {
           
            $request->validate([
                'audio_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
            ]);

            $audio_image = 'chapter_audio_image_'. time() . '.' . $request->file('audio_image')->getClientOriginalExtension();
            $request->file('audio_image')->storeAs('chapter/audio/',$audio_image,'public');
            $audio_image = 'chapter/audio/'.$audio_image;
            if(isset($request->old_audio_image) && $request->old_audio_image != ''){
                if (Storage::disk('public')->exists($request->old_audio_image)) {
                    Storage::disk('public')->delete($request->old_audio_image);
                }
            }
        }

        Chapter::where('id',$request->id)
            ->update([
            'book_id' => $request->book_id,
            'name'=> $request->name,
            'korean_name'=> $request->korean_name,
            'spanish_name'=> $request->spanish_name,
            'portuguese_name'=> $request->portuguese_name,
            'filipino_name'=> $request->filipino_name,
            'description'=> $request->description,
            'korean_description'=> $request->korean_description,
            'spanish_description'=> $request->spanish_description,
            'portuguese_description'=> $request->portuguese_description,
            'filipino_description'=> $request->filipino_description,
            'video'=> $request->video,
            'korean_video'=> $request->korean_video,
            'spanish_video'=> $request->spanish_video,
            'portuguese_video'=> $request->portuguese_video,
            'filipino_video'=> $request->filipino_video,
            'audio'=> $request->audio,
            'korean_audio'=> $request->korean_audio,
            'spanish_audio'=> $request->spanish_audio,
            'portuguese_audio'=> $request->portuguese_audio,
            'filipino_audio'=> $request->filipino_audio,
            'srt'=> $request->srt,
            'korean_srt'=> $request->korean_srt,
            'spanish_srt'=> $request->spanish_srt,
            'portuguese_srt'=> $request->portuguese_srt,
            'filipino_srt'=> $request->filipino_srt,
            'video_image' => $video_image,
            'audio_image' => $audio_image,
            ]);
        toastr('Your data has been saved');
        return redirect()->route('chapters');
    }
}
