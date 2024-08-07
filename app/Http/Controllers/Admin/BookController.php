<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BooksDataTable;
use App\Exports\ExportBook;
use App\Exports\ExportBroadcast;
use App\Exports\ExportChapter;
use App\Exports\ExportMeditation;
use App\Exports\ExportMeditationVideos;
use App\Exports\ExportNightTimeStory;
use App\Exports\ExportNightTimeStoryVideos;
use App\Exports\ExportTravelSamaritan;
use App\Http\Controllers\Controller;
use App\Imports\ImportBook;
use App\Imports\ImportBroadcast;
use App\Imports\ImportChapter;
use App\Imports\ImportMeditations;
use App\Imports\ImportMeditationVideos;
use App\Imports\ImportNightTimeStory;
use App\Imports\ImportNightTimeStoryVideos;
use App\Imports\ImportTravelSamaritan;
use App\Imports\ImportUpdatedChapterAudios;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Book;
use App\Models\Inspiration;
use App\Models\Night_time_story;
use App\Models\Travel_samaritan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{
    public function index(BooksDataTable $DataTable){
        $header = 'Books';
        return $DataTable->render('admin.books.list',compact('header'));
    }

    public function datatable(BooksDataTable $DataTable){
        return $DataTable->render('admin.books.list');
    }
    
    public function add(Request $request){
        $request->validate([
            'type.*' => ['required', 'string'],
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
        ]);
        foreach ($request->file('video_image') as $key => $image) {
            $video_image = 'book_video_image' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('books/video/', $video_image, 'public');
            $video_image = 'books/video/'.$video_image;
            
            $audio_image = 'book_audio_image' . time() . '.' . $request->file('audio_image')[$key]->getClientOriginalExtension();
            $request->file('audio_image')[$key]->storeAs('books/audio/', $audio_image, 'public');
            $audio_image = 'books/audio/'.$audio_image;

            $book = new Book();
            $book->type = $request->type[$key];
            $book->name = $request->name[$key];
            $book->korean_name = $request->korean_name[$key];
            $book->spanish_name = $request->spanish_name[$key];
            $book->portuguese_name = $request->portuguese_name[$key];
            $book->filipino_name = $request->filipino_name[$key];
            $book->video_image = $video_image;
            $book->audio_image = $audio_image;
            $book->description = $request->description[$key];
            $book->korean_description = $request->korean_description[$key];
            $book->spanish_description = $request->spanish_description[$key];
            $book->portuguese_description = $request->portuguese_description[$key];
            $book->filipino_description = $request->filipino_description[$key];
            $book->save();
        }

        toastr('Your data has been saved');
        return redirect()->route('books');
    }

    public function delete(Request $request){
        $book_image = Book::select('video_image','audio_image')
            ->where('id',decrypt($request->id))
            ->get();
        Book::where('id', decrypt($request->id))->delete();
        if (Storage::disk('public')->exists($book_image[0]->video_image)) {
            Storage::disk('public')->delete($book_image[0]->video_image);
        }
        if (Storage::disk('public')->exists($book_image[0]->audio_image)) {
            Storage::disk('public')->delete($book_image[0]->audio_image);
        }

        toastr('Book deleted successfully !');
        return redirect()->route('books');
    }

    public function edit(Request $request){
        $books = Book::where('id',$request->id)
            ->get();
        $book = $books[0]; 
        $html = view('admin.books.edit',compact('book'));
        echo $html;
    }

    public function update(Request $request){
        
        $request->validate([
            'type' => ['required', 'string'],
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
        $video_image = $request->old_video_image;
        $audio_image = $request->old_audio_image;
        if ($request->file('video_image')) {
           
            $request->validate([
                'video_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
            ]);

            $video_image = 'book_video_image' . time() . '.' . $request->file('video_image')->getClientOriginalExtension();
            $request->file('video_image')->storeAs('books/video/', $video_image, 'public');
            $video_image = 'books/video/'.$video_image;

            
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

            $audio_image = 'book_audio_image' . time() . '.' . $request->file('audio_image')->getClientOriginalExtension();
            $request->file('audio_image')->storeAs('books/audio/', $audio_image, 'public');
            $audio_image = 'books/audio/'.$audio_image;

            if(isset($request->old_audio_image) && $request->old_audio_image != ''){
                if (Storage::disk('public')->exists($request->old_audio_image)) {
                    Storage::disk('public')->delete($request->old_audio_image);
                }
            }
        }
        Book::where('id',$request->id)
            ->update([
                'type' => $request->type,
                'name' => $request->name,
                'korean_name' => $request->korean_name,
                'spanish_name' => $request->spanish_name,
                'portuguese_name' => $request->portuguese_name,
                'filipino_name' => $request->filipino_name,
                'description' => $request->description,
                'korean_description' => $request->korean_description,
                'spanish_description' => $request->spanish_description,
                'portuguese_description' => $request->portuguese_description,
                'filipino_description' => $request->filipino_description,
                'video_image' => $video_image,
                'audio_image' => $audio_image,
            ]);
        toastr('Your data has been saved');
        return redirect()->route('books');
    }

    public function export(Request $request){
        $header = 'Import/Export Books & Chapters';
        return view('admin.import_export.list',compact('header'));
    }

    public function exportBooks(Request $request){
        if($request->type == 1){
            $data = ['Type','English Name','Korean Name','Spanish Name','Portuguese Name','Filipino Name',
            'English Description','Korean Description','Spanish Description','Portuguese Description','Filipino Description'];
            return Excel::download(new ExportBook($data),'export_to_add_book.xlsx');
        }elseif ($request->type == 2) {
            $allBook = Book::select('id','name')->get();
            $books = [];
            foreach ($allBook as $key => $book) {
                array_push($books,$book->name);
            }
            $data = ['Book','English Name','Korean Name','Spanish Name','Portuguese Name','Filipino Name',
            'English Video Link','Korean Video Link','Spanish Video Link','Portuguese Video Link','Filipino Video Link',
            'English Audio Link','Korean Audio Link','Spanish Audio Link','Portuguese Audio Link','Filipino Audio Link',
            'English Subtitle Link','Korean Subtitle Link','Spanish Subtitle Link','Portuguese Subtitle Link','Filipino Subtitle Link',
            'English Description','Korean Description','Spanish Description','Portuguese Description','Filipino Description','Video Image','Audio Image'];
            return Excel::download(new ExportChapter($data,$books),'export_to_add_chapter.xlsx');
        }elseif ($request->type == 3) {
            $data = ['English Name','Korean Name','Spanish Name','Portuguese Name','Filipino Name',
            'English Video Link','Korean Video Link','Spanish Video Link','Portuguese Video Link','Filipino Video Link',
            'English Subtitle Link','Korean Subtitle Link','Spanish Subtitle Link','Portuguese Subtitle Link','Filipino Subtitle Link',
            'Featured Video','Image'];
            return Excel::download(new ExportTravelSamaritan($data),'export_to_add_travel_samaritan.xlsx');
        }elseif ($request->type == 4){
            $data = ['English Name','Korean Name','Spanish Name','Portuguese Name','Filipino Name',
            'English Title','Korean Title','Spanish Title','Portuguese Title','Filipino Title',
            'English Audio Link','Korean Audio Link','Spanish Audio Link','Portuguese Audio Link','Filipino Audio Link','Image'];
            return Excel::download(new ExportBroadcast($data),'export_to_add_broadcasts.xlsx');
        }elseif ($request->type == 5) {
            $data = ['English Name','Korean Name','Spanish Name','Portuguese Name','Filipino Name',
            'English Description','Korean Description','Spanish Description','Portuguese Description','Filipino Description','Image'];
            return Excel::download(new ExportMeditation($data),'export_to_add_meditations.xlsx');
        }elseif ($request->type == 6) {
            $allBook = Inspiration::select('id','name')->get();
            $books = [];
            foreach ($allBook as $key => $book) {
                array_push($books,$book->name);
            }
            $data = ['Inspiration','English Name','Korean Name','Spanish Name','Portuguese Name','Filipino Name',
            'English Video Link','Korean Video Link','Spanish Video Link','Portuguese Video Link','Filipino Video Link',
            'English Subtitle Link','Korean Subtitle Link','Spanish Subtitle Link','Portuguese Subtitle Link','Filipino Subtitle Link',
            'English Description','Korean Description','Spanish Description','Portuguese Description','Filipino Description','Image'];
            return Excel::download(new ExportMeditationVideos($data,$books),'export_to_add_meditation_videos.xlsx');
        }elseif ($request->type == 7) {
            $data = ['English Name','Korean Name','Spanish Name','Portuguese Name','Filipino Name',
            'English Description','Korean Description','Spanish Description','Portuguese Description','Filipino Description','Image'];
            return Excel::download(new ExportNightTimeStory($data),'export_to_add_nighttimestory.xlsx');
        }elseif ($request->type == 8) {
            $allBook = Night_time_story::select('id','name')->get();
            $books = [];
            foreach ($allBook as $key => $book) {
                array_push($books,$book->name);
            }
            $data = ['Night Time Story','English Name','Korean Name','Spanish Name','Portuguese Name','Filipino Name',
            'English Audio Link','Korean Audio Link','Spanish Audio Link','Portuguese Audio Link','Filipino Audio Link',
            'English Description','Korean Description','Spanish Description','Portuguese Description','Filipino Description','Image'];
            return Excel::download(new ExportNightTimeStoryVideos($data,$books),'export_to_add_nighttimestory_videos.xlsx');
        }
    }

    public function importBooks(Request $request){
        $request->validate([
            'excel' => 'required|mimes:xlsx,xls',
        ]);
        $file = $request->file('excel');
        if($request->type == '1'){
            Excel::import(new ImportBook, $file);
            return redirect()->back()->with('success', 'Data imported successfully!');
        }else if($request->type == '2'){
            $check = Excel::import(new ImportChapter,$file);
            if($check){
                return redirect()->back()->with('success', 'Chapter imported successfully!');
            }
        }else if($request->type == '3'){
            $check = Excel::import(new ImportTravelSamaritan,$file);
            if($check){
                return redirect()->back()->with('success', 'Travel Samaritan imported successfully!');
            }
        }elseif ($request->type == '4') {
            $check = Excel::import(new ImportBroadcast,$file);
            if($check){
                return redirect()->back()->with('success', 'Broadcasts imported successfully!');
            }
        }elseif ($request->type == '5') {
            $check = Excel::import(new ImportMeditations,$file);
            if($check){
                return redirect()->back()->with('success', 'Meditations imported successfully!');
            }
        }elseif ($request->type == '6') {
            $check = Excel::import(new ImportMeditationVideos,$file);
            if($check){
                return redirect()->back()->with('success', 'Meditation Videos imported successfully!');
            }
        }elseif ($request->type == '7') {
            $check = Excel::import(new ImportNightTimeStory,$file);
            if($check){
                return redirect()->back()->with('success', 'Night Time Stories imported successfully!');
            }
        }elseif ($request->type == '8') {
            $check = Excel::import(new ImportNightTimeStoryVideos,$file);
            if($check){
                return redirect()->back()->with('success', 'Night Time Story Videos imported successfully!');
            }
        }elseif ($request->type == '9') {
            $check = Excel::import(new ImportUpdatedChapterAudios,$file);
            if($check){
                return redirect()->back()->with('success', 'Audios Updated successfully!');
            }
        }
    }
}
