<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\Organizer;
use App\Models\UserLogin;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Socialite;

class SocialLogin
{
    private $guard; //user / organizer
    private $guardModel; //User / Organizer
    private $provider;
    private $fromApi;

    public function __construct($provider, $guard, $fromApi = false)
    {
        $this->guard = $guard; //user / organizer
        $this->guardModel = ucfirst($guard); //User / Organizer
        $this->provider = $provider;
        $this->fromApi = $fromApi;
        $this->configuration();
    }

    public function redirectDriver()
    {
        return Socialite::driver($this->provider)->redirect();
    }

    private function configuration()
    {
        $provider      = $this->provider;
        $configuration = gs('socialite_credentials')->$provider;
        $provider    = $this->fromApi && $provider == 'linkedin' ? 'linkedin-openid' : $provider;

        Config::set('services.' . $provider, [
            'client_id'     => $configuration->client_id,
            'client_secret' => $configuration->client_secret,
            'redirect'      => route($this->guard . '.social.login.callback', $provider),
        ]);
    }

    public function login()
    {
        $model         = $this->guardModel;
        $guard         = $this->guard;
        $provider      = $this->provider;
        $provider      = $this->fromApi && $provider == 'linkedin' ? 'linkedin-openid' : $provider;
        $driver        = Socialite::driver($provider);
        if ($this->fromApi) {
            try {
                $user = (object)$driver->userFromToken(request()->token)->user;
            } catch (\Throwable $th) {
                throw new Exception('Something went wrong');
            }
        } else {
            $user = $driver->user();
        }
        $userData = $model::where('username', $user->id)->first();

        if ($provider == 'linkedin-openid') {
            $user->id = $user->sub;
        }

        $userData = $model::where('provider_id', $user->id)->first();

        if (!$userData) {
            $registration = $this->guard == 'user' ? gs('registration') : gs('organizer_registration');
            if (!$registration) {
                throw new Exception('New account registration is currently disabled');
            }
            $emailExists = $model::where('email', @$user->email)->exists();
            if ($emailExists) {
                throw new Exception('Email already exists');
            }
            $userData = $this->createUser($user, $this->provider, $model);
        }
        if ($this->fromApi) {
            $tokenResult = $userData->createToken('auth_token')->plainTextToken;
            $this->loginLog($userData);
            return [
                'user'         => $userData,
                'access_token' => $tokenResult,
                'token_type'   => 'Bearer',
            ];
        }

        if ($guard == 'user') {
            Auth::login($userData);
        } else {
            Auth::guard('organizer')->login($userData);
        }

        $this->loginLog($userData);
        return to_route($guard . '.home');
    }

    private function createUser($user, $provider, $model)
    {
        $general  = gs();
        $password = getTrx(8);

        $firstName = null;
        $lastName = null;

        if (@$user->first_name) {
            $firstName = $user->first_name;
        }
        if (@$user->last_name) {
            $lastName = $user->last_name;
        }

        if ((!$firstName || !$lastName) && @$user->name) {
            $firstName = preg_replace('/\W\w+\s*(\W*)$/', '$1', $user->name);
            $pieces    = explode(' ', $user->name);
            $lastName  = array_pop($pieces);
        }


        $newUser = new $model();
        $newUser->provider_id = $user->id;

        $newUser->email = $user->email;

        $newUser->password = Hash::make($password);
        $newUser->firstname = $firstName;
        $newUser->lastname = $lastName;

        $newUser->status = Status::VERIFIED;
        $newUser->kv = $general->kv ? Status::NO : Status::YES;
        $newUser->ev = Status::VERIFIED;
        $newUser->sv = gs('sv') ? Status::UNVERIFIED : Status::VERIFIED;
        $newUser->ts = Status::DISABLE;
        $newUser->tv = Status::VERIFIED;
        $newUser->provider = $provider;
        $newUser->save();

        $adminNotification = new AdminNotification();
        $guard = $this->guard;
        $userCol = $guard . '_id';
        $adminNotification->$userCol = $newUser->id;
        $adminNotification->title = 'New member registered';
        $adminNotification->click_url = urlPath('admin.' . $guard . 's.detail', $newUser->id);
        $adminNotification->save();

        $user = $model::find($newUser->id);
        return $user;
    }

    private function loginLog($user)
    {
        //Login Log Create
        $ip = getRealIP();
        $exist = UserLogin::where('ip', $ip)->first();
        $userLogin = new UserLogin();

        //Check exist or not
        if ($exist) {
            $userLogin->longitude =  $exist->longitude;
            $userLogin->latitude =  $exist->latitude;
            $userLogin->city =  $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country =  $exist->country;
        } else {
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude =  @implode(',', $info['long']);
            $userLogin->latitude =  @implode(',', $info['lat']);
            $userLogin->city =  @implode(',', $info['city']);
            $userLogin->country_code = @implode(',', $info['code']);
            $userLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $guard = $this->guard;
        $userCol = $guard . '_id';
        $userLogin->$userCol = $user->id;
        $userLogin->ip =  $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();
    }
}
