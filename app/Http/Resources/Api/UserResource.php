<?php

namespace App\Http\Resources\Api;

use App\Models\Subscription;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $plans = Subscription::where('user_id',$this->id)
        ->orderBy('id','DESC')
        ->where('end_date','>=',date('Y-m-d'))
        ->where('unsubscribe_request',0)
        ->select('id','plan','start_date','end_date')
        ->limit(1)
        ->get();

        return [
            'user_id' => $this->id,
            'name' => (isset($this->name) && $this->name != null)?$this->name:'',
            'mobile' => (isset($this->mobile) && $this->mobile != null)?$this->mobile:'',
            'country_code' => (isset($this->country_code) && $this->country_code != null)?$this->country_code:'',
            'role' => $this->role_id,
            'email' => $this->email,
            'token' => $this->accessToken->accessToken,
            'plan' => $plans,
        ];
    }
}
