<?php

namespace App\Http\Controllers\Auth;

// use PiwikTracker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/settings';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        // //记录到matomo
        // if(isset(config('matomo.site')[env('APP_DOMAIN')])){
        //     $siteId = config('matomo.site')[env('APP_DOMAIN')];
        //     $matomo = config('matomo.matomo');
            
        //     $piwik = new PiwikTracker($siteId,$matomo);
        //     $piwik->setUserId($this->guard()->user()->id);
        //     $piwik->doTrackEvent('visit','login','userLogin');  
        // }

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }
    
}
