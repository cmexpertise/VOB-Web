<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PsalmsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Psalm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PsalmsController extends Controller
{
    public function index(PsalmsDataTable $DataTable){
        $header = 'Psalms';
        return $DataTable->render('admin.psalms.list',compact('header'));
    }

    public function datatable(PsalmsDataTable $DataTable){
        return $DataTable->render('admin.psalms.list');
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
        
        $image = 'psalms_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->storeAs('psalms/', $image, 'public');
        $image = 'psalms/'.$image;
        

        $psalms = new Psalm();
        $psalms->name = $request->name;
        $psalms->korean_name = $request->korean_name;
        $psalms->spanish_name = $request->spanish_name;
        $psalms->portuguese_name = $request->portuguese_name;
        $psalms->filipino_name = $request->filipino_name;
        $psalms->description = $request->description;
        $psalms->korean_description = $request->korean_description;
        $psalms->spanish_description = $request->spanish_description;
        $psalms->portuguese_description = $request->portuguese_description;
        $psalms->filipino_description = $request->filipino_description;
        $psalms->image = $image;
        $psalms->save();
        
        toastr('Your data has been saved');
        return redirect()->route('psalms');
    }

    public function delete(Request $request){
        $chapter = Psalm::select('image')
            ->where('id',decrypt($request->id))
            ->get();
        Psalm::where('id', decrypt($request->id))->delete();
        if (Storage::disk('public')->exists($chapter[0]->image)) {
            Storage::disk('public')->delete($chapter[0]->image);
        }
        toastr('Psalms deleted successfully !');
        return redirect()->route('psalms');
    }

    public function edit(Request $request){
        $psalms = Psalm::where('id',$request->id)
            ->get();
        $psalm = $psalms[0]; 
        $html = view('admin.psalms.edit',compact('psalm'));
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
            $image = 'psalms_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('psalms/', $image, 'public');
            $image = 'psalms/'.$image;

            if (Storage::disk('public')->exists($request->old_image)) {
                Storage::disk('public')->delete($request->old_image);
            }
        }

        Psalm::where('id',$request->id)
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
        return redirect()->route('psalms');
    }

}
