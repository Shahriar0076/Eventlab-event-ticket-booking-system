<?php

namespace App\Http\Controllers\Organizer;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Constants\Status;
use App\Lib\Intended;

class AuthorizationController extends Controller
{
    protected function checkCodeValidity($organizer, $addMin = 2)
    {
        if (!$organizer->ver_code_send_at) {
            return false;
        }
        if ($organizer->ver_code_send_at->addMinutes($addMin) < Carbon::now()) {
            return false;
        }
        return true;
    }

    public function authorizeForm()
    {
        $organizer = authOrganizer();
        if (!$organizer->status) {
            $pageTitle = 'Banned';
            $type = 'ban';
        } elseif (!$organizer->ev) {
            $type = 'email';
            $pageTitle = 'Verify Email';
            $notifyTemplate = 'EVER_CODE';
        } elseif (!$organizer->sv) {
            $type = 'sms';
            $pageTitle = 'Verify Mobile Number';
            $notifyTemplate = 'SVER_CODE';
        } elseif (!$organizer->tv) {
            $pageTitle = '2FA Verification';
            $type = '2fa';
        } else {
            return to_route('organizer.home');
        }

        if (!$this->checkCodeValidity($organizer) && ($type != '2fa') && ($type != 'ban')) {
            $organizer->ver_code = verificationCode(6);
            $organizer->ver_code_send_at = Carbon::now();
            $organizer->save();
            notify($organizer, $notifyTemplate, [
                'code' => $organizer->ver_code
            ], [$type]);
        }

        return view('Template::organizer.auth.authorization.' . $type, compact('organizer', 'pageTitle'));
    }

    public function sendVerifyCode($type)
    {
        $organizer = authOrganizer();

        if ($this->checkCodeValidity($organizer)) {
            $targetTime = $organizer->ver_code_send_at->addMinutes(2)->timestamp;
            $delay = $targetTime - time();
            throw ValidationException::withMessages(['resend' => 'Please try after ' . $delay . ' seconds']);
        }

        $organizer->ver_code = verificationCode(6);
        $organizer->ver_code_send_at = Carbon::now();
        $organizer->save();

        if ($type == 'email') {
            $type = 'email';
            $notifyTemplate = 'EVER_CODE';
        } else {
            $type = 'sms';
            $notifyTemplate = 'SVER_CODE';
        }

        notify($organizer, $notifyTemplate, [
            'code' => $organizer->ver_code
        ], [$type]);

        $notify[] = ['success', 'Verification code sent successfully'];
        return back()->withNotify($notify);
    }

    public function emailVerification(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);

        $organizer = authOrganizer();

        if ($organizer->ver_code == $request->code) {
            $organizer->ev = Status::VERIFIED;
            $organizer->ver_code = null;
            $organizer->ver_code_send_at = null;
            $organizer->save();

            $redirection = Intended::getRedirection();
            return $redirection ? $redirection : to_route('organizer.home');
        }
        throw ValidationException::withMessages(['code' => 'Verification code didn\'t match!']);
    }

    public function mobileVerification(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);


        $organizer = authOrganizer();
        if ($organizer->ver_code == $request->code) {
            $organizer->sv = Status::VERIFIED;
            $organizer->ver_code = null;
            $organizer->ver_code_send_at = null;
            $organizer->save();
            $redirection = Intended::getRedirection();
            return $redirection ? $redirection : to_route('organizer.home');
        }
        throw ValidationException::withMessages(['code' => 'Verification code didn\'t match!']);
    }

    public function g2faVerification(Request $request)
    {
        $organizer = authOrganizer();
        $request->validate([
            'code' => 'required',
        ]);
        $response = verifyG2fa($organizer, $request->code);
        if ($response) {
            $redirection = Intended::getRedirection();
            return $redirection ? $redirection : to_route('organizer.home');
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }
}
