<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    function username()
    {
        return 'username';
    }

    protected function credentials(Request $request)
    {
        return [
            'username'     => $request->username,
            'password'  => $request->password,
            'is_aktif' => '1'
        ];
    }
    //   protected function sendLoginResponse(Request $request)
    //  {
    // $user = User::where('username', $request->username)->firstOrFail();
    // $token = $user->createToken('auth_token')->plainTextToken;

    // $user = User::where('username', $request->username)->firstOrFail();
    // $user->createToken('auth_token')->accessToken;



    //  return response()->json(compact('token'));


    //  return $this->authenticated();
    //  }


    public function authenticated()
    {
        return redirect('/meeting/hr');
    }
}
