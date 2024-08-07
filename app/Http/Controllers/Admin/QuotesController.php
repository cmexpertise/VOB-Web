<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\QuotesCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Quete;
use App\Models\Quote;
use App\Models\Quote_category;
use App\Models\Quotes_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuotesController extends Controller
{
    public function index(QuotesCategoryDataTable $DataTable){
        $header = 'Quotes Category';
        return $DataTable->render('admin.quotes.list',compact('header'));
    }

    public function datatable(QuotesCategoryDataTable $DataTable){
        return $DataTable->render('admin.quotes.list');
    }

    public function add(Request $request){
        $request->validate([
            'name' => ['required', 'string'],
            'korean_name' => ['required', 'string',],
            'spanish_name' => ['required', 'string',],
            'portuguese_name' => ['required', 'string',],
            'filipino_name' => ['required', 'string'],
        ]);
        
        $quote_category = new Quote_category();
        $quote_category->name = $request->name;
        $quote_category->korean_name = $request->korean_name;
        $quote_category->spanish_name = $request->spanish_name;
        $quote_category->portuguese_name = $request->portuguese_name;
        $quote_category->filipino_name = $request->filipino_name;
        $quote_category->save();
        
        toastr('Your data has been saved');
        return redirect()->route('quotes');
    }

    public function delete(Request $request){
        
        Quote_category::where('id', decrypt($request->id))->delete();
        toastr('Quote Category deleted successfully !');
        return redirect()->route('quotes');
    }

    public function edit(Request $request){
        $categories = Quote_category::where('id',$request->id)
            ->get();
        $category = $categories[0]; 
        $html = view('admin.quotes.edit',compact('category'));
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

        Quote_category::where('id',$request->id)
            ->update([
                'name'=> $request->name,
                'korean_name'=> $request->korean_name,
                'spanish_name'=> $request->spanish_name,
                'portuguese_name'=> $request->portuguese_name,
                'filipino_name'=> $request->filipino_name,
            ]);
        toastr('Your data has been saved');
        return redirect()->route('quotes');
    }

    public function add_quote(Request $request){
        $name = decrypt($request->name);
        $header = 'Quote of '.$name;
        $quote_id = decrypt($request->id);
        return view('admin.quotes.quote_list',compact('header','quote_id','name'));
    }

    public function add_quotes(Request $request){
        $request->validate([
            'quote_id' => ['required', 'string'],
            'image.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
        ]);
        foreach ($request->file('image') as $key => $image) {
            $quote_image = 'quote_image' . time() .$key. '.' . $image->getClientOriginalExtension();
            $image->storeAs('quotes/', $quote_image, 'public');
            $quote_image = 'quotes/'.$quote_image;

            $quote = new Quote();
            $quote->quote_category_id = $request->quote_id;
            $quote->image = $quote_image;
            $quote->save();
        }
        toastr('Your data has been saved');
        return redirect()->route('quotes.add_quote',['id'=>encrypt($request->quote_id),'name' => encrypt($request->quote_name)]);
    }

    public function getquotes(Request $request){
        $limit = ($request->limit != 0) ? $request->limit : 12;
        $offset = ($request->offset != 0) ? $request->offset : 0;
        $quote_category_id = $request->quote_id;
        $result = Quote::select('quote_category_id','image','id')
            ->where('quote_category_id',$quote_category_id)
            ->limit($limit)
            ->skip($offset)
            ->orderBy('id','DESC')
            ->get();

        foreach ($result as $value) {
            $delete_url = route('quotes.quotes_delete',["id" => encrypt($value->id)]);
            echo " <div class='col-lg-2 col-md-4'>
                    <div class='magnific-img position-relative'>
                        <a href='" .asset("storage/".$value->image). "' class='quotes-box mt-5 image-popup-vertical-fit'>
                            <div class='quote-img '>
                                <img data-src=" .asset("storage/".$value->image). " alt='' class='lazyload'>
                            </div>
                        </a>
                            <div class='quotes-btn mt-4'>
                                <button class='btn btn-danger ml-3 deleteQuote' data-id = '".encrypt($value->id)."' data-bs-toggle='modal' data-bs-target='#deleteModal'><i class='ri-delete-bin-6-line'></i></button>
                            </div>
                    </div>
                </div>";
        }
    }

    public function quotes_delete(Request $request){
        $quotes = Quote::with(['quote_category' => function ($query){
            $query->select('id','name');
        }])
        ->select('id','quote_category_id','image')
        ->where('id',decrypt($request->id))
        ->get();
        Quote::where('id',decrypt($request->id))->delete();
        if (Storage::disk('public')->exists($quotes[0]->image)) {
            Storage::disk('public')->delete($quotes[0]->image);
        }
        toastr('Your data has been deleted');
        if(!$quotes->isEmpty()){
            $quote_category_id = $quotes[0]->quote_category->id;
            $quote_name = $quotes[0]->quote_category->name;
            return redirect()->route('quotes.add_quote',['id'=>encrypt($quote_category_id),'name' => encrypt($quote_name)]);
        }else{
            return redirect()->route('quotes');
        }
    }

}
