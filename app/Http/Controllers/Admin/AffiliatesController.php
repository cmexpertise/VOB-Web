<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AffiliatesDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Helper\MyHelper;
use App\Models\Affiliate;

class AffiliatesController extends Controller
{
    public function index(AffiliatesDataTable $DataTable){
        $header = 'Affiliates';
        $country_code = MyHelper::GetDialcodelist();
        return $DataTable->render('admin.affiliates.list',compact('header','country_code'));
    }

    public function datatable(AffiliatesDataTable $DataTable){
        return $DataTable->render('admin.affiliates.list');
    }

    public function add(Request $request){
        $request->validate([
            'email' => ['required', 'string'],
            'name' => ['required', 'string'],
            'mobile' => ['required', 'string',],
            'password' => ['required', 'string',],
            'country_code' => ['required', 'string',],
            'percentage' => ['required', 'string',],
        ]);
        $coupons = $this->generateRandomString(10); 
        
		$short_link = $this->shorten_URL('https://cmexpertiseinfotech.com/dynamic/refer?code=ref-'.$coupons);
        $affiliate = new Affiliate();
        $affiliate->name = $request->name;
        $affiliate->email = $request->email;
        $affiliate->mobile = $request->mobile;
        $affiliate->percentage = $request->percentage;
        $affiliate->country_code = $request->country_code;
        $affiliate->password = Hash::make($request->password);
        $affiliate->affilate_url = $short_link;
        $affiliate->coupon_code = $coupons;
        $affiliate->save();
        
        toastr('Your data has been saved');
        return redirect()->route('affiliates');
    }

    public function edit(Request $request){
        $affiliates = Affiliate::where('id',$request->id)
            ->get();
        $affiliate = $affiliates[0]; 
        $country_code = MyHelper::GetDialcodelist();
        $html = view('admin.affiliates.edit',compact('affiliate','country_code'));
        echo $html;
    }

    public function update(Request $request){
        
        $request->validate([
            'email' => ['required', 'string'],
            'name' => ['required', 'string'],
            'mobile' => ['required', 'string',],
            'country_code' => ['required', 'string',],
            'percentage' => ['required', 'string',],
        ]);
        if($request->password!=''){
            $password = Hash::make($request->password);
        }else{
            $password = $request->old_password;
        }

        Affiliate::where('id',$request->id)
            ->update([
                'email' => $request->email,
                'name' => $request->name,
                'mobile' => $request->mobile,
                'country_code' => $request->country_code,
                'percentage' => $request->percentage,
                'password' => $password,
            ]);
        toastr('Your data has been saved');
        return redirect()->route('affiliates');
    }

    public function delete(Request $request){
        
        Affiliate::where('id', decrypt($request->id))->delete();
        toastr('Affiliate deleted successfully !');
        return redirect()->route('affiliates');
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function shorten_URL($longUrl) {
        $key = 'AIzaSyDvJiJu_KrbGjCSZD-OYMIxlRKUnfMLOL4';
        $url = 'https://firebasedynamiclinks.googleapis.com/v1/shortLinks?key=' . $key;
        $data = array(
           "dynamicLinkInfo" => array(
              "domainUriPrefix" => "https://oribible.page.link",
              "link" => $longUrl,
              "androidInfo"=>array("androidPackageName"=>"com.obs.oribible"),
              "iosInfo"=> array("iosBundleId"=>"com.obs.oribible","iosAppStoreId"=>"1570619920"), 
           )
        );
        $headers = array('Content-Type: application/json');
  
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode($data) );
  
        $data = curl_exec ( $ch );
        curl_close ( $ch );
  
        $short_url = json_decode($data);
        if(isset($short_url->error)){
            return $short_url->error->message;
        } else {
            return $short_url->shortLink;
        }
  
    }
}
