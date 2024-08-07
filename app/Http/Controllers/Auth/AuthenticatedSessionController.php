<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }
    
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        $user = Auth::user();
        if($user->role_id != 1){
            toastr('Invalid Login Credentials');
            $this->destroy($request);
        }
        return redirect()->intended(RouteServiceProvider::HOME);
    }
    
    public function user_store(Request $request)
    {
        $users = User::where('email',$request['email'])
                ->where('password',md5($request['password']))->get();
        if (!$users->isEmpty()) {
            $user = $users[0];
            Auth::login($user);
            DB::table('oauth_access_tokens')->where('user_id',$user->id)->delete();
            $user->accessToken = $user->createToken('appToken');
            if($user->role_id == 1){
                toastr('Invalid Login Credentials');
                $this->destroy($request);
            }
            return redirect('home');
        }else{
            toastr('Invalid Login Credentials');
            return redirect('signin');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
      
        $request->session()->invalidate();
        
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
