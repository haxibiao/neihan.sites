<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
     */
    use VerifiesEmails;
    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/nova';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function show(Request $request)
    {
        $user = $request->user();
        if (ends_with($user->email, '@haxibiao.com') || ends_with($user->account, '@haxibiao.com')) {
            return $request->user()->hasVerifiedEmail()
            ? redirect($this->redirectPath())
            : view('auth.verify');
        }
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            dd("发送的邮箱获取失败");
        }

        return back()->with('resent', true);
    }
}
