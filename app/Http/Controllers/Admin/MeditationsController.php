<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\MeditationDataTable;
use App\Http\Controllers\Controller;
use App\Models\Meditation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MeditationsController extends Controller
{
    public function index(MeditationDataTable $DataTable){
        $header = 'Meditations';
        
        return $DataTable->render('admin.meditations.list',compact('header'));
    }

    public function add(Request $request){
        $request->validate([
            'name' => ['required', 'string'],
            'korean_name' => ['required', 'string',],
            'spanish_name' => ['required', 'string',],
            'portuguese_name' => ['required', 'string',],
            'filipino_name' => ['required', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp'],
            'audio' => ['required', 'string'],
        ]);
        
        $image = 'meditation_image_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->storeAs('meditations/', $image, 'public');
        $image = 'meditations/'.$image;
        

        $daily_prayer = new Meditation();
        $daily_prayer->name = $request->name;
        $daily_prayer->korean_name = $request->korean_name;
        $daily_prayer->spanish_name = $request->spanish_name;
        $daily_prayer->portuguese_name = $request->portuguese_name;
        $daily_prayer->filipino_name = $request->filipino_name;
        $daily_prayer->audio = $request->audio;
        $daily_prayer->korean_audio = $request->korean_audio;
        $daily_prayer->spanish_audio = $request->spanish_audio;
        $daily_prayer->portuguese_audio = $request->portuguese_audio;
        $daily_prayer->filipino_audio = $request->filipino_audio;
        $daily_prayer->image = $image;
        $daily_prayer->save();
        
        toastr('Your data has been saved');
        return redirect()->route('meditations');
    }

    public function datatable(MeditationDataTable $DataTable){
        return $DataTable->render('admin.meditations.list');
    }

    public function edit(Request $request){
        
        $prayers = Meditation::where('id',$request->id)
            ->get();
        $prayer = $prayers[0]; 
        $html = view('admin.meditations.edit',compact('prayer'));
        echo $html;
    }

    public function update(Request $request){
        $request->validate([
            'name' => ['required', 'string'],
            'korean_name' => ['required', 'string',],
            'spanish_name' => ['required', 'string',],
            'portuguese_name' => ['required', 'string',],
            'filipino_name' => ['required', 'string'],
            'audio' => ['required', 'string'],
        ]);
        $image = $request->old_image;
        if ($request->file('image')) {
           
            $request->validate([
                'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp'],
            ]);

            $image = 'meditations_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('meditations/', $image, 'public');
            $image = 'meditations/'.$image;

            if (Storage::disk('public')->exists($request->old_image)) {
                Storage::disk('public')->delete($request->old_image);
            }
        }

        Meditation::where('id',$request->id)
            ->update([
            'name'=> $request->name,
            'korean_name'=> $request->korean_name,
            'spanish_name'=> $request->spanish_name,
            'portuguese_name'=> $request->portuguese_name,
            'filipino_name'=> $request->filipino_name,
            'audio'=> $request->audio,
            'korean_audio'=> $request->korean_audio,
            'spanish_audio'=> $request->spanish_audio,
            'portuguese_audio'=> $request->portuguese_audio,
            'filipino_audio'=> $request->filipino_audio,
            'image' => $image,
            ]);
        toastr('Your data has been saved');
        return redirect()->route('meditations');
    }

    public function delete(Request $request){
        $chapter = Meditation::select('image')
            ->where('id',decrypt($request->id))
            ->get();
        Meditation::where('id', decrypt($request->id))->delete();
        if (Storage::disk('public')->exists($chapter[0]->image)) {
            Storage::disk('public')->delete($chapter[0]->image);
        }

        toastr('Meditation deleted successfully !');
        return redirect()->route('meditations');
    }
}
