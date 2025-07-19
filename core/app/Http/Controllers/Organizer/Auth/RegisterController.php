<?php

namespace App\Http\Controllers\Organizer\Auth;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\Intended;
use App\Models\AdminNotification;
use App\Models\Organizer;
use App\Models\UserLogin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{

    use RegistersUsers;

    public function __construct()
    {
        parent::__construct();
    }

    public function showRegistrationForm()
    {
        $pageTitle = "Register";
        Intended::identifyRoute();
        return view('Template::organizer.auth.register', compact('pageTitle'));
    }


    protected function validator(array $data)
    {

        $passwordValidation = Password::min(6);

        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $agree = 'nullable';
        if (gs('agree')) {
            $agree = 'required';
        }

        $validate     = Validator::make($data, [
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'     => 'required|string|email|unique:organizers',
            'password'  => ['required', 'confirmed', $passwordValidation],
            'captcha'   => 'sometimes|required',
            'agree'     => $agree
        ], [
            'firstname.required' => 'The first name field is required',
            'lastname.required' => 'The last name field is required'
        ]);

        return $validate;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $request->session()->regenerateToken();

        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = ['info', 'Username can contain only small letters, numbers and underscore.'];
            $notify[] = ['error', 'No special character, space or capital letters in username.'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }



        event(new Registered($organizer = $this->create($request->all())));

        $this->guard()->login($organizer);

        return $this->registered($request, $organizer)
            ?: redirect($this->redirectPath());
    }



    protected function create(array $data)
    {

        //Organizer Create
        $organizer            = new Organizer();
        $organizer->email     = strtolower($data['email']);
        $organizer->firstname = $data['firstname'];
        $organizer->lastname  = $data['lastname'];
        $organizer->password  = Hash::make($data['password']);
        $organizer->kv = gs('kv') ? Status::NO : Status::YES;
        $organizer->ev = gs('ev') ? Status::NO : Status::YES;
        $organizer->sv = gs('sv') ? Status::NO : Status::YES;
        $organizer->ts = Status::DISABLE;
        $organizer->tv = Status::ENABLE;
        $organizer->save();

        $adminNotification                 = new AdminNotification();
        $adminNotification->organizer_id   = $organizer->id;
        $adminNotification->title          = 'New organizer registered';
        $adminNotification->click_url      = urlPath('admin.organizers.detail', $organizer->id);
        $adminNotification->save();


        //Login Log Create
        $ip        = getRealIP();
        $exist     = UserLogin::where('ip', $ip)->first();
        $organizerLogin = new UserLogin();

        if ($exist) {
            $organizerLogin->longitude    = $exist->longitude;
            $organizerLogin->latitude     = $exist->latitude;
            $organizerLogin->city         = $exist->city;
            $organizerLogin->country_code = $exist->country_code;
            $organizerLogin->country      = $exist->country;
        } else {
            $info                    = json_decode(json_encode(getIpInfo()), true);
            $organizerLogin->longitude    = @implode(',', $info['long']);
            $organizerLogin->latitude     = @implode(',', $info['lat']);
            $organizerLogin->city         = @implode(',', $info['city']);
            $organizerLogin->country_code = @implode(',', $info['code']);
            $organizerLogin->country      = @implode(',', $info['country']);
        }

        $organizerAgent               = osBrowser();
        $organizerLogin->organizer_id = $organizer->id;
        $organizerLogin->ip           = $ip;

        $organizerLogin->browser = @$organizerAgent['browser'];
        $organizerLogin->os      = @$organizerAgent['os_platform'];
        $organizerLogin->save();

        if (auth()->check()) {
            auth()->logout();
        }


        return $organizer;
    }

    protected function guard()
    {
        return auth()->guard('organizer');
    }

    public function checkUser(Request $request)
    {
        $exist['data'] = false;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = Organizer::where('email', $request->email)->exists();
            $exist['type'] = 'email';
            $exist['field'] = 'Email';
        }
        if ($request->mobile) {
            $exist['data'] = Organizer::where('mobile', $request->mobile)->where('dial_code', $request->mobile_code)->exists();
            $exist['type'] = 'mobile';
            $exist['field'] = 'Mobile';
        }
        if ($request->username) {
            $exist['data'] = Organizer::where('username', $request->username)->exists();
            $exist['type'] = 'username';
            $exist['field'] = 'Username';
        }
        return response($exist);
    }

    public function registered()
    {
        return to_route('organizer.home');
    }
}
