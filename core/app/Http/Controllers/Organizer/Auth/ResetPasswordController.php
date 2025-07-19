<?php

namespace App\Http\Controllers\Organizer\Auth;

use App\Http\Controllers\Controller;
use App\Models\OrganizerPasswordReset;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {

        $email = session('fpass_email');
        $token = session()->has('token') ? session('token') : $token;
        if (OrganizerPasswordReset::where('token', $token)->where('email', $email)->count() != 1) {
            $notify[] = ['error', 'Invalid token'];
            return to_route('organizer.password.request')->withNotify($notify);
        }
        return view('Template::organizer.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $email, 'pageTitle' => 'Reset Password']
        );
    }

    public function reset(Request $request)
    {
        $request->validate($this->rules());
        $reset = OrganizerPasswordReset::where('token', $request->token)->orderBy('created_at', 'desc')->first();
        if (!$reset) {
            $notify[] = ['error', 'Invalid verification code'];
            return to_route('organizer.login')->withNotify($notify);
        }

        $organizer = Organizer::where('email', $reset->email)->first();
        $organizer->password = Hash::make($request->password);
        $organizer->save();



        $organizerIpInfo = getIpInfo();
        $organizerBrowser = osBrowser();
        notify($organizer, 'PASS_RESET_DONE', [
            'operating_system' => @$organizerBrowser['os_platform'],
            'browser' => @$organizerBrowser['browser'],
            'ip' => @$organizerIpInfo['ip'],
            'time' => @$organizerIpInfo['time']
        ], ['email']);


        $notify[] = ['success', 'Password changed successfully'];
        return to_route('organizer.login')->withNotify($notify);
    }


    protected function rules()
    {
        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', $passwordValidation],
        ];
    }
}
