<?php

namespace App\Http\Controllers\Organizer;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\Event;
use App\Models\Order;
use App\Models\Withdrawal;
use App\Models\DeviceToken;
use App\Models\Form;
use App\Models\Transaction;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\FileTypeValidate;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class OrganizerController extends Controller
{
    public function home()
    {
        $pageTitle                  = 'Organizer Dashboard';
        $organizer                  = authOrganizer();
        $events                     = Event::organizerEvents();
        $widget['total_events']     = (clone $events)->count();
        $widget['followers_count']  = $organizer->followers()->count();
        $widget['upcoming_events']  = (clone $events)->futureEvents()->count();
        $widget['running_events']   = (clone $events)->running()->count();
        $widget['total_sold_count'] = Order::organizerTicketsSold()->completed()->sum('total_price');
        $widget['pending_withdraw']   = Withdrawal::where('organizer_id', $organizer->id)->pending()->count();
        $widget['pending_events']   = (clone $events)->pending()->count();

        $events = (clone $events)->approved()->orderBy('id', 'desc')->limit(5)->get();

        $organizerOrderMonth = Order::organizerTicketsSold()->where('created_at', '>=', now()->subYear())->completed()
            ->selectRaw("SUM( CASE WHEN status = " . Status::ORDER_COMPLETED . " THEN total_price  END) as orderTotal")
            ->selectRaw("DATE_FORMAT(created_at,'%M-%Y') as months")
            ->orderBy('created_at')
            ->groupBy('months')->get();

        $report['months'] = collect([]);
        $report['order_month_amount'] = collect([]);

        $organizerOrderMonth->map(function ($orderData) use ($report) {
            if (!in_array($orderData->months, $report['months']->toArray())) {
                $report['months']->push($orderData->months);
            }
            $report['order_month_amount']->push(getAmount($orderData->orderTotal));
        });

        $months = $report['months'];
        for ($i = 0; $i < $months->count(); ++$i) {
            $monthVal = Carbon::parse($months[$i]);
            if (isset($months[$i + 1])) {
                $monthValNext = Carbon::parse($months[$i + 1]);
                if ($monthValNext < $monthVal) {
                    $temp = $months[$i];
                    $months[$i] = Carbon::parse($months[$i + 1])->format('F-Y');
                    $months[$i + 1] = Carbon::parse($temp)->format('F-Y');
                } else {
                    $months[$i] = Carbon::parse($months[$i])->format('F-Y');
                }
            }
        }

        return view('Template::organizer.dashboard', compact('pageTitle', 'widget', 'events', 'months', 'organizerOrderMonth'));
    }

    public function show2faForm()
    {
        $ga = new GoogleAuthenticator();
        $organizer = authOrganizer();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($organizer->username . '@' . gs('site_name'), $secret);
        $pageTitle = '2FA Security';
        return view('Template::organizer.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $organizer = authOrganizer();
        $request->validate([
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($organizer, $request->code, $request->key);
        if ($response) {
            $organizer->tsc = $request->key;
            $organizer->ts = Status::ENABLE;
            $organizer->save();
            $notify[] = ['success', 'Two factor authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $organizer = authOrganizer();
        $response = verifyG2fa($organizer, $request->code);
        if ($response) {
            $organizer->tsc = null;
            $organizer->ts = Status::DISABLE;
            $organizer->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions()
    {
        $pageTitle = 'Transactions';
        $remarks = Transaction::distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::where('organizer_id', authOrganizer()->id)->searchable(['trx'])->filter(['trx_type', 'remark'])->orderBy('id', 'desc')->paginate(getPaginate());

        return view('Template::organizer.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function kycForm()
    {
        if (authOrganizer()->kv == Status::KYC_PENDING) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('organizer.home')->withNotify($notify);
        }
        if (authOrganizer()->kv == Status::KYC_VERIFIED) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('organizer.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        $form = Form::where('act', 'organizer_kyc')->first();
        return view('Template::organizer.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $organizer = authOrganizer();
        $pageTitle = 'KYC Data';
        return view('Template::organizer.kyc.info', compact('pageTitle', 'organizer'));
    }

    public function kycSubmit(Request $request)
    {
        $form = Form::where('act', 'organizer_kyc')->firstOrFail();
        $formData = $form->form_data;
        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $organizer = authOrganizer();
        foreach (@$organizer->kyc_data ?? [] as $kycData) {
            if ($kycData->type == 'file') {
                fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
            }
        }
        $organizerData = $formProcessor->processFormData($request, $formData);
        $organizer->kyc_data = $organizerData;
        $organizer->kyc_rejection_reason = null;
        $organizer->kv = Status::KYC_PENDING;
        $organizer->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('organizer.home')->withNotify($notify);
    }

    public function organizerData()
    {
        $organizer = authOrganizer();

        if ($organizer->profile_complete == Status::YES) {
            return to_route('organizer.home');
        }

        $pageTitle  = 'Organizer Data';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('Template::organizer.organizer_data', compact('pageTitle', 'organizer', 'countries', 'mobileCode'));
    }

    public function organizerDataSubmit(Request $request)
    {

        $organizer = authOrganizer();

        if ($organizer->profile_complete == Status::YES) {
            return to_route('organizer.home');
        }

        $countryData  = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        $request->validate([
            'organization_name' => [
                'required', 'string',
                Rule::unique('organizers')->ignore($organizer->id),
            ],
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'username'     => 'required|unique:organizers|min:6',
            'mobile'       => ['required', 'regex:/^([0-9]*)$/', Rule::unique('organizers')->where('dial_code', $request->mobile_code)],

            'title'             => 'nullable|string',
            'short_description' => 'nullable|string',
            'long_description'  => 'nullable|string',
            'profile_image'     => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'cover_image'       => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        $organizer->country_code = $request->country_code;
        $organizer->mobile       = $request->mobile;
        $organizer->username     = $request->username;


        $organizer->address = $request->address;
        $organizer->city = $request->city;
        $organizer->state = $request->state;
        $organizer->zip = $request->zip;
        $organizer->country_name = @$request->country;
        $organizer->dial_code = $request->mobile_code;

        if ($request->hasFile('profile_image')) {
            try {
                $organizer->profile_image = fileUploader($request->profile_image, getFilePath('organizerProfile'), getFileSize('organizerProfile'), $organizer->profile_image);
            } catch (\Exception $exp) {
                return returnBack('Couldn\'t upload your image');
            }
        }

        if ($request->hasFile('cover_image')) {
            try {
                $organizer->cover_image   = fileUploader($request->cover_image, getFilePath('organizerCover'), getFileSize('organizerCover'), $organizer->cover_image);
            } catch (\Exception $exp) {
                return returnBack('Couldn\'t upload your image');
            }
        }

        $organizer->organization_name = $request->organization_name;
        $organizer->slug              = slug($request->organization_name);
        $organizer->title             = $request->title;
        $organizer->short_description = $request->short_description;
        $organizer->long_description  = $request->long_description;

        $organizer->profile_complete = Status::YES;
        $organizer->save();

        return to_route('organizer.home');
    }


    public function addDeviceToken(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->errors()->all()];
        }

        $deviceToken = DeviceToken::where('token', $request->token)->first();

        if ($deviceToken) {
            return ['success' => true, 'message' => 'Already exists'];
        }

        $deviceToken               = new DeviceToken();
        $deviceToken->organizer_id = authOrganizer()->id;
        $deviceToken->token        = $request->token;
        $deviceToken->is_app       = Status::NO;
        $deviceToken->save();

        return ['success' => true, 'message' => 'Token saved successfully'];
    }

    public function downloadAttachment($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }
}
