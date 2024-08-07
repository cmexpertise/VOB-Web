<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserValidate;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    function signup(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorKeys = $errors->keys();
            $firstErrorKey = $errorKeys[0];
            $status = 0;
            if ($firstErrorKey == 'email') {
                $status = false;
            }
            return response()->json(
                [
                    'status' => $status,
                    'message' => $validator->errors()->first()
                ],
            );
        }
        $users = User::where('email',$request->email)
            ->get();
        if(isset($users) && !$users->isEmpty()){
            $user = User::where('email',$request->email)
                ->update(['password'=> Hash::make($request->password)]);
        }else{
            $user = new User();
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->login_type = $request->login_type; 
            $user->name = $request->name;
            $user->fcm_token = $request->fcm_token;
            $user->assignRole('User');
            $user->role_id = 2;
            $user->save();
        }

        $user->accessToken = $user->createToken('appToken');
        return $this->sendResponse(__('messages.registered'),new UserResource($user));
    }

    function check_email(Request $request){
        $email = $request->email;
        $exists = User::where('email', $email)->exists();
        return response()->json(!$exists);
    }

}
