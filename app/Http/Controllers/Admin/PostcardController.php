<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PostcardDataTable;
use App\DataTables\UserPostcardDataTable;
use App\Http\Controllers\Controller;
use App\Models\Postcard;
use Illuminate\Http\Request;
use Spatie\Image\Image;
use Illuminate\Support\Facades\Storage;

class PostcardController extends Controller
{
    public function index(PostcardDataTable $DataTable){
        $header = 'Postcards';
        return $DataTable->render('admin.postcards.list',compact('header'));
    }

    public function datatable(PostcardDataTable $DataTable){
        return $DataTable->render('admin.postcards.list');
    }

    public function add(Request $request){
        
        $request->validate([
            'image.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
        ]);
        foreach ($request->file('image') as $key => $image) {
            $postcard_image = 'postcard_image' . time() .$key. '.' . $image->getClientOriginalExtension();
            $image->storeAs('postcards/', $postcard_image, 'public');
            $postcard_image = 'postcards/'.$postcard_image;

            $thumbnailName = 'thumbnail_' . $image->getClientOriginalName();

            $temporaryThumbnailPath = storage_path('app/public/thumbnails/tmp_' . $thumbnailName);

            // Create the thumbnail with compression
            Image::load($image->getRealPath())
                ->width(500)
                ->optimize()
                ->save($temporaryThumbnailPath);
    
            // Store the compressed thumbnail using storeAs
            $thumbnailStoragePath = 'thumbnails/' . $thumbnailName;
            Storage::disk('public')->put($thumbnailStoragePath, file_get_contents($temporaryThumbnailPath));
    
            // Optionally, you can delete the temporary file if needed
            unlink($temporaryThumbnailPath);

            $quote = new Postcard();
            $quote->name = $request->name[$key];
            $quote->korean_name = $request->korean_name[$key];
            $quote->spanish_name = $request->spanish_name[$key];
            $quote->portuguese_name = $request->portuguese_name[$key];
            $quote->filipino_name = $request->filipino_name[$key];
            $quote->image = $postcard_image;
            $quote->thumbnail = $thumbnailStoragePath;
            $quote->save();
        }
        toastr('Your data has been saved');
        return redirect()->route('postcards');
    }

    public function delete(Request $request){
        $postcard = Postcard::select('id','image')
            ->where('id',decrypt($request->id))
            ->get();
        Postcard::where('id',decrypt($request->id))->delete();
        if (Storage::disk('public')->exists($postcard[0]->image)) {
            Storage::disk('public')->delete($postcard[0]->image);
        }
        toastr('Your data has been deleted');
        return redirect()->route('postcards');
        
    }

    public function users(Request $request,UserPostcardDataTable $DataTable){
        $header = 'Postcards';
        return $DataTable->render('admin.postcards.user_list',compact('header'));
    }

    public function userdatatable(UserPostcardDataTable $DataTable){
        return $DataTable->render('admin.postcards.user_list');
    }

    public function edit(Request $request){
        
        $chapters = Postcard::where('id',$request->id)
            ->get();
        $chapter = $chapters[0]; 
        $html = view('admin.postcards.edit',compact('chapter'));
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
        $thumbnail = $request->old_thumbnail;
        if ($request->file('image')) {
            $request->validate([
                'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
            ]);
            
            $postcard_image = 'postcard_image' . time().'.' .$request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('postcards/', $postcard_image, 'public');
            $image = 'postcards/'.$postcard_image;

            $thumbnailName = 'thumbnail_' .time().$request->file('image')->getClientOriginalName();

            $temporaryThumbnailPath = storage_path('app/public/thumbnails/tmp_' . $thumbnailName);
            // Create the thumbnail with compression
            Image::load($request->file('image')->getRealPath())
                ->width(500)
                ->optimize()
                ->save($temporaryThumbnailPath);
    
            // Store the compressed thumbnail using storeAs
            $thumbnail = 'thumbnails/' . $thumbnailName;
            Storage::disk('public')->put($thumbnail, file_get_contents($temporaryThumbnailPath));
    
            // Optionally, you can delete the temporary file if needed
            unlink($temporaryThumbnailPath);

            if(isset($request->old_image) && $request->old_image != ''){
                if (Storage::disk('public')->exists($request->old_image)) {
                    Storage::disk('public')->delete($request->old_image);
                }
                if (Storage::disk('public')->exists($request->old_thumbnail)) {
                    Storage::disk('public')->delete($request->old_thumbnail);
                }
            }
        }
        

        Postcard::where('id',$request->id)
            ->update([
            'name'=> $request->name,
            'korean_name'=> $request->korean_name,
            'spanish_name'=> $request->spanish_name,
            'portuguese_name'=> $request->portuguese_name,
            'filipino_name'=> $request->filipino_name,
            'image' => $image,
            'thumbnail' => $thumbnail,
            ]);
        toastr('Your data has been saved');
        return redirect()->route('postcards');
    }
}
