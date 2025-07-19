<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Lib\FormProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class KycController extends Controller
{
    public function setting()
    {
        $pageTitle = 'KYC Setting';
        $act = $this->getAct();
        $form = Form::where('act', $act)->first();
        return view('admin.kyc.setting', compact('pageTitle', 'form', 'act'));
    }

    public function settingUpdate(Request $request)
    {
        $request->validate([
            'act' => 'required:in:user_kyc,organizer_kyc'
        ]);

        $formProcessor = new FormProcessor();
        $generatorValidation = $formProcessor->generatorValidation();
        $request->validate($generatorValidation['rules'], $generatorValidation['messages']);
        $exist = Form::where('act', $request->act)->first();
        $formProcessor->generate($request->act, $exist, 'act');

        $notify[] = ['success', 'KYC data updated successfully'];
        return back()->withNotify($notify);
    }

    private function getAct()
    {
        if (Route::currentRouteName() == 'admin.kyc.setting.user') {
            return 'user_kyc';
        } else {
            return 'organizer_kyc';
        }
    }
}
