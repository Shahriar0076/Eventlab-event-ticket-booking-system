<?php

namespace App\Http\Controllers\Organizer\Auth;

use App\Http\Controllers\Controller;
use App\Lib\Intended;
use App\Models\UserLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Status;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    use AuthenticatesUsers;


    protected $organizername;


    public function __construct()
    {
        parent::__construct();
        $this->username = $this->findUsername();
    }

    public function showLoginForm()
    {
        $pageTitle = "Login";
        Intended::identifyRoute();
        return view('Template::organizer.auth.login', compact('pageTitle'));
    }

    protected function guard()
    {
        return auth()->guard('organizer');
    }

    public function login(Request $request)
    {

        $this->validateLogin($request);

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the organizer back to the login form. Of course, when this
        // organizer surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        Intended::reAssignSession();

        return $this->sendFailedLoginResponse($request);
    }

    public function findUsername()
    {
        $login = request()->input('username');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }

    protected function validateLogin($request)
    {

        $validator = Validator::make($request->all(), [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            Intended::reAssignSession();
            $validator->validate();
        }
    }

    public function logout()
    {
        $this->guard()->logout();
        request()->session()->invalidate();

        $notify[] = ['success', 'You have been logged out.'];
        return to_route('organizer.login')->withNotify($notify);
    }


    public function authenticated(Request $request, $organizer)
    {
        $organizer->tv = $organizer->ts == Status::VERIFIED ? Status::UNVERIFIED : Status::VERIFIED;
        $organizer->save();
        $ip = getRealIP();
        $exist = UserLogin::where('ip', $ip)->first();
        $organizerLogin = new UserLogin();
        if ($exist) {
            $organizerLogin->longitude =  $exist->longitude;
            $organizerLogin->latitude =  $exist->latitude;
            $organizerLogin->city =  $exist->city;
            $organizerLogin->country_code = $exist->country_code;
            $organizerLogin->country =  $exist->country;
        } else {
            $info = json_decode(json_encode(getIpInfo()), true);
            $organizerLogin->longitude =  @implode(',', $info['long']);
            $organizerLogin->latitude =  @implode(',', $info['lat']);
            $organizerLogin->city =  @implode(',', $info['city']);
            $organizerLogin->country_code = @implode(',', $info['code']);
            $organizerLogin->country =  @implode(',', $info['country']);
        }

        $organizerAgent = osBrowser();
        $organizerLogin->organizer_id = $organizer->id;
        $organizerLogin->ip =  $ip;

        $organizerLogin->browser = @$organizerAgent['browser'];
        $organizerLogin->os = @$organizerAgent['os_platform'];
        $organizerLogin->save();

        if (auth()->check()) {
            auth()->logout();
        }

        $redirection = Intended::getRedirection();
        return $redirection ? $redirection : to_route('organizer.home');
    }
}
