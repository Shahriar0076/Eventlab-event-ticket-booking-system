<?php

namespace App\Http\Controllers\Organizer\Auth;

use App\Http\Controllers\Controller;
use App\Models\OrganizerPasswordReset;
use App\Models\Organizer;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        $pageTitle = "Account Recovery";
        return view('Template::organizer.auth.passwords.email', compact('pageTitle'));
    }

    public function sendResetCodeEmail(Request $request)
    {
        $request->validate([
            'value' => 'required'
        ]);

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $fieldType = $this->findFieldType();
        $organizer = Organizer::where($fieldType, $request->value)->first();

        if (!$organizer) {
            $notify[] = ['error', 'The account could not be found'];
            return back()->withNotify($notify);
        }

        OrganizerPasswordReset::where('email', $organizer->email)->delete();
        $code = verificationCode(6);
        $password = new OrganizerPasswordReset();
        $password->email = $organizer->email;
        $password->token = $code;
        $password->created_at = \Carbon\Carbon::now();
        $password->save();

        $organizerIpInfo = getIpInfo();
        $organizerBrowserInfo = osBrowser();
        notify($organizer, 'PASS_RESET_CODE', [
            'code' => $code,
            'operating_system' => @$organizerBrowserInfo['os_platform'],
            'browser' => @$organizerBrowserInfo['browser'],
            'ip' => @$organizerIpInfo['ip'],
            'time' => @$organizerIpInfo['time']
        ], ['email']);

        $email = $organizer->email;
        session()->put('pass_res_mail', $email);
        $notify[] = ['success', 'Password reset email sent successfully'];
        return to_route('organizer.password.code.verify')->withNotify($notify);
    }

    public function findFieldType()
    {
        $input = request()->input('value');

        $fieldType = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $input]);
        return $fieldType;
    }

    public function codeVerify(Request $request)
    {
        $pageTitle = 'Verify Email';
        $email = $request->session()->get('pass_res_mail');
        if (!$email) {
            $notify[] = ['error', 'Oops! session expired'];
            return to_route('organizer.password.request')->withNotify($notify);
        }
        return view('Template::organizer.auth.passwords.code_verify', compact('pageTitle', 'email'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'email' => 'required'
        ]);
        $code =  str_replace(' ', '', $request->code);

        if (OrganizerPasswordReset::where('token', $code)->where('email', $request->email)->count() != 1) {
            $notify[] = ['error', 'Verification code doesn\'t match'];
            return to_route('organizer.password.request')->withNotify($notify);
        }
        $notify[] = ['success', 'You can change your password'];
        session()->flash('fpass_email', $request->email);
        return to_route('organizer.password.reset', $code)->withNotify($notify);
    }
}
