<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Event;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use App\Models\Organizer;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\FileTypeValidate;

class ManageOrganizersController extends Controller
{

    public function allOrganizers()
    {
        $pageTitle = 'All Organizers';
        $organizers = $this->organizerData();
        return view('admin.organizers.list', compact('pageTitle', 'organizers'));
    }

    public function activeOrganizers()
    {
        $pageTitle = 'Active Organizers';
        $organizers = $this->organizerData('active');
        return view('admin.organizers.list', compact('pageTitle', 'organizers'));
    }

    public function bannedOrganizers()
    {
        $pageTitle = 'Banned Organizers';
        $organizers = $this->organizerData('banned');
        return view('admin.organizers.list', compact('pageTitle', 'organizers'));
    }

    public function emailUnverifiedOrganizers()
    {
        $pageTitle = 'Email Unverified Organizers';
        $organizers = $this->organizerData('emailUnverified');
        return view('admin.organizers.list', compact('pageTitle', 'organizers'));
    }

    public function kycUnverifiedOrganizers()
    {
        $pageTitle = 'KYC Unverified Organizers';
        $organizers = $this->organizerData('kycUnverified');
        return view('admin.organizers.list', compact('pageTitle', 'organizers'));
    }

    public function kycPendingOrganizers()
    {
        $pageTitle = 'KYC Unverified Organizers';
        $organizers = $this->organizerData('kycPending');
        return view('admin.organizers.list', compact('pageTitle', 'organizers'));
    }

    public function emailVerifiedOrganizers()
    {
        $pageTitle = 'Email Verified Organizers';
        $organizers = $this->organizerData('emailVerified');
        return view('admin.organizers.list', compact('pageTitle', 'organizers'));
    }

    public function mobileUnverifiedOrganizers()
    {
        $pageTitle = 'Mobile Unverified Organizers';
        $organizers = $this->organizerData('mobileUnverified');
        return view('admin.organizers.list', compact('pageTitle', 'organizers'));
    }

    public function mobileVerifiedOrganizers()
    {
        $pageTitle = 'Mobile Verified Organizers';
        $organizers = $this->organizerData('mobileVerified');
        return view('admin.organizers.list', compact('pageTitle', 'organizers'));
    }

    public function organizersWithBalance()
    {
        $pageTitle = 'Organizers with Balance';
        $organizers = $this->organizerData('withBalance');
        return view('admin.organizers.list', compact('pageTitle', 'organizers'));
    }

    protected function organizerData($scope = null)
    {
        if ($scope) {
            $organizers = Organizer::$scope();
        } else {
            $organizers = Organizer::query();
        }
        return $organizers->with('events')->searchable(['username', 'email', 'organization_name'])->orderBy('id', 'desc')->paginate(getPaginate());
    }

    public function detail($id)
    {
        $organizer = Organizer::findOrFail($id);
        $pageTitle = 'Organizer Detail - ' . $organizer->organization_name;

        $totalWithdrawals = Withdrawal::where('organizer_id', $organizer->id)->approved()->sum('amount');
        $totalTransaction = Transaction::where('organizer_id', $organizer->id)->count();
        $countries        = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $eventsCount      = Event::where('organizer_id', $id)->count();
        return view('admin.organizers.detail', compact('pageTitle', 'organizer', 'totalWithdrawals', 'totalTransaction', 'countries', 'eventsCount'));
    }

    public function kycDetails($id)
    {
        $pageTitle = 'KYC Details';
        $organizer = Organizer::findOrFail($id);
        return view('admin.organizers.kyc_detail', compact('pageTitle', 'organizer'));
    }

    public function kycApprove($id)
    {
        $organizer = Organizer::findOrFail($id);
        $organizer->kv = Status::KYC_VERIFIED;
        $organizer->save();

        notify($organizer, 'KYC_APPROVE', []);

        $notify[] = ['success', 'KYC approved successfully'];
        return to_route('admin.organizers.kyc.pending')->withNotify($notify);
    }

    public function kycReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required'
        ]);
        $organizer = Organizer::findOrFail($id);
        $organizer->kv = Status::KYC_UNVERIFIED;
        $organizer->kyc_rejection_reason = $request->reason;
        $organizer->save();

        notify($organizer, 'KYC_REJECT', []);

        $notify[] = ['success', 'KYC rejected successfully'];
        return to_route('admin.organizers.kyc.pending')->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $organizer = Organizer::findOrFail($id);
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray = (array) $countryData;
        $countries = implode(',', array_keys($countryArray));

        $countryCode = $request->country;
        $country = $countryData->$countryCode->country;
        $dialCode = $countryData->$countryCode->dial_code;

        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'email' => 'required|email|string|max:40|unique:organizers,email,' . $organizer->id,
            'mobile' => 'required|string|max:40',
            'country' => 'required|in:' . $countries,
        ]);

        $exists = Organizer::where('mobile', $request->mobile)->where('dial_code', $dialCode)->where('id', '!=', $organizer->id)->exists();
        if ($exists) {
            $notify[] = ['error', 'The mobile number already exists.'];
            return back()->withNotify($notify);
        }

        $organizer->organization_name = $request->organization_name;
        $organizer->firstname = $request->firstname;
        $organizer->lastname = $request->lastname;
        $organizer->email = $request->email;

        $organizer->address = $request->address;
        $organizer->city = $request->city;
        $organizer->state = $request->state;
        $organizer->zip = $request->zip;
        $organizer->country_name = @$country;
        $organizer->dial_code = $dialCode;
        $organizer->country_code = $countryCode;

        $organizer->ev = $request->ev ? Status::VERIFIED : Status::UNVERIFIED;
        $organizer->sv = $request->sv ? Status::VERIFIED : Status::UNVERIFIED;
        $organizer->ts = $request->ts ? Status::ENABLE : Status::DISABLE;
        if (!$request->kv) {
            $organizer->kv = Status::KYC_UNVERIFIED;
            if ($organizer->kyc_data) {
                foreach ($organizer->kyc_data as $kycData) {
                    if ($kycData->type == 'file') {
                        fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
                    }
                }
            }
            $organizer->kyc_data = null;
        } else {
            $organizer->kv = Status::KYC_VERIFIED;
        }
        $organizer->save();

        $notify[] = ['success', 'Organizer details updated successfully'];
        return back()->withNotify($notify);
    }

    public function addSubBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'act' => 'required|in:add,sub',
            'remark' => 'required|string|max:255',
        ]);

        $organizer = Organizer::findOrFail($id);
        $amount = $request->amount;
        $trx = getTrx();

        $transaction = new Transaction();

        if ($request->act == 'add') {
            $organizer->balance += $amount;

            $transaction->trx_type = '+';
            $transaction->remark = 'balance_add';

            $notifyTemplate = 'BAL_ADD';

            $notify[] = ['success', 'Balance added successfully'];
        } else {
            if ($amount > $organizer->balance) {
                $notify[] = ['error', $organizer->organization_name . ' doesn\'t have sufficient balance.'];
                return back()->withNotify($notify);
            }

            $organizer->balance -= $amount;

            $transaction->trx_type = '-';
            $transaction->remark = 'balance_subtract';

            $notifyTemplate = 'BAL_SUB';
            $notify[] = ['success', 'Balance subtracted successfully'];
        }

        $organizer->save();

        $transaction->organizer_id = $organizer->id;
        $transaction->amount = $amount;
        $transaction->post_balance = $organizer->balance;
        $transaction->charge = 0;
        $transaction->trx = $trx;
        $transaction->details = $request->remark;
        $transaction->save();

        notify($organizer, $notifyTemplate, [
            'trx' => $trx,
            'amount' => showAmount($amount, currencyFormat: false),
            'remark' => $request->remark,
            'post_balance' => showAmount($organizer->balance, currencyFormat: false)
        ]);

        return back()->withNotify($notify);
    }

    public function login($id)
    {
        if (Auth::check()) {
            Auth::logout();
        }
        Auth::guard('organizer')->loginUsingId($id);
        return to_route('organizer.home');
    }

    public function status(Request $request, $id)
    {
        $organizer = Organizer::findOrFail($id);
        if ($organizer->status == Status::ORGANIZER_ACTIVE) {
            $request->validate([
                'reason' => 'required|string|max:255',
            ]);
            $organizer->status = Status::ORGANIZER_BAN;
            $organizer->ban_reason = $request->reason;
            $notify[] = ['success', 'Organizer banned successfully'];
        } else {
            $organizer->status = Status::ORGANIZER_ACTIVE;
            $organizer->ban_reason = null;
            $notify[] = ['success', 'Organizer unbanned successfully'];
        }
        $organizer->save();
        return back()->withNotify($notify);
    }

    public function showNotificationSingleForm($id)
    {
        $organizer = Organizer::findOrFail($id);
        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.organizers.detail', $organizer->id)->withNotify($notify);
        }
        $pageTitle = 'Send Notification to ' . $organizer->organization_name;
        return view('admin.organizers.notification_single', compact('pageTitle', 'organizer'));
    }

    public function sendNotificationSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required',
            'via'     => 'required|in:email,sms,push',
            'subject' => 'required_if:via,email,push',
            'image'   => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        $imageUrl = null;
        if ($request->via == 'push' && $request->hasFile('image')) {
            $imageUrl = fileUploader($request->image, getFilePath('push'));
        }

        $template = NotificationTemplate::where('act', 'DEFAULT')->where($request->via . '_status', Status::ENABLE)->exists();
        if (!$template) {
            $notify[] = ['warning', 'Default notification template is not enabled'];
            return back()->withNotify($notify);
        }

        $organizer = Organizer::findOrFail($id);
        notify($organizer, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ], [$request->via], pushImage: $imageUrl);

        $notify[] = ['success', 'Notification sent successfully'];
        return back()->withNotify($notify);
    }

    public function showNotificationAllForm()
    {
        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }
        $notifyToOrganizer = Organizer::notifyToOrganizer();
        $organizers = Organizer::active()->count();
        $pageTitle = 'Notification to Verified Organizers';

        if (session()->has('SEND_NOTIFICATION') && !request()->email_sent) {
            session()->forget('SEND_NOTIFICATION');
        }

        return view('admin.organizers.notification_all', compact('pageTitle', 'organizers', 'notifyToOrganizer'));
    }

    public function sendNotificationAll(Request $request)
    {
        $request->validate([
            'via'                          => 'required|in:email,sms,push',
            'message'                      => 'required',
            'subject'                      => 'required_if:via,email,push',
            'start'                        => 'required|integer|gte:1',
            'batch'                        => 'required|integer|gte:1',
            'being_sent_to'                => 'required',
            'cooling_time'                 => 'required|integer|gte:1',
            'number_of_top_deposited_organizer' => 'required_if:being_sent_to,topDepositedOrganizers|integer|gte:0',
            'number_of_days'               => 'required_if:being_sent_to,notLoginOrganizers|integer|gte:0',
            'image'                        => ["nullable", 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ], [
            'number_of_days.required_if'               => "Number of days field is required",
            'number_of_top_deposited_organizer.required_if' => "Number of top deposited organizer field is required",
        ]);

        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }


        $template = NotificationTemplate::where('act', 'DEFAULT')->where($request->via . '_status', Status::ENABLE)->exists();
        if (!$template) {
            $notify[] = ['warning', 'Default notification template is not enabled'];
            return back()->withNotify($notify);
        }

        if ($request->being_sent_to == 'selectedOrganizers') {
            if (session()->has("SEND_NOTIFICATION")) {
                $request->merge(['organizer' => session()->get('SEND_NOTIFICATION')['organizer']]);
            } else {
                if (!$request->organizer || !is_array($request->organizer) || empty($request->organizer)) {
                    $notify[] = ['error', "Ensure that the organizer field is populated when sending an email to the designated organizer group"];
                    return back()->withNotify($notify);
                }
            }
        }

        $scope          = $request->being_sent_to;
        $organizerQuery      = Organizer::oldest()->active()->$scope();

        if (session()->has("SEND_NOTIFICATION")) {
            $totalOrganizerCount = session('SEND_NOTIFICATION')['total_organizer'];
        } else {
            $totalOrganizerCount = (clone $organizerQuery)->count() - ($request->start - 1);
        }


        if ($totalOrganizerCount <= 0) {
            $notify[] = ['error', "Notification recipients were not found among the selected organizer base."];
            return back()->withNotify($notify);
        }


        $imageUrl = null;

        if ($request->via == 'push' && $request->hasFile('image')) {
            if (session()->has("SEND_NOTIFICATION")) {
                $request->merge(['image' => session()->get('SEND_NOTIFICATION')['image']]);
            }
            if ($request->hasFile("image")) {
                $imageUrl = fileUploader($request->image, getFilePath('push'));
            }
        }

        $organizers = (clone $organizerQuery)->skip($request->start - 1)->limit($request->batch)->get();

        foreach ($organizers as $organizer) {
            notify($organizer, 'DEFAULT', [
                'subject' => $request->subject,
                'message' => $request->message,
            ], [$request->via], pushImage: $imageUrl);
        }

        return $this->sessionForNotification($totalOrganizerCount, $request);
    }


    private function sessionForNotification($totalOrganizerCount, $request)
    {
        if (session()->has('SEND_NOTIFICATION')) {
            $sessionData                = session("SEND_NOTIFICATION");
            $sessionData['total_sent'] += $sessionData['batch'];
        } else {
            $sessionData               = $request->except('_token');
            $sessionData['total_sent'] = $request->batch;
            $sessionData['total_organizer'] = $totalOrganizerCount;
        }

        $sessionData['start'] = $sessionData['total_sent'] + 1;

        if ($sessionData['total_sent'] >= $totalOrganizerCount) {
            session()->forget("SEND_NOTIFICATION");
            $message = ucfirst($request->via) . " notifications were sent successfully";
            $url     = route("admin.organizers.notification.all");
        } else {
            session()->put('SEND_NOTIFICATION', $sessionData);
            $message = $sessionData['total_sent'] . " " . $sessionData['via'] . "  notifications were sent successfully";
            $url     = route("admin.organizers.notification.all") . "?email_sent=yes";
        }
        $notify[] = ['success', $message];
        return redirect($url)->withNotify($notify);
    }

    public function countBySegment($methodName)
    {
        return Organizer::active()->$methodName()->count();
    }
    public function list()
    {
        $query = Organizer::active();

        if (request()->search) {
            $query->where(function ($q) {
                $q->where('email', 'like', '%' . request()->search . '%')->orWhere('username', 'like', '%' . request()->search . '%');
            });
        }
        $organizers = $query->orderBy('id', 'desc')->paginate(getPaginate());
        return response()->json([
            'success' => true,
            'organizers' => $organizers,
            'more' => $organizers->hasMorePages(),
        ]);
    }

    public function notificationLog($id)
    {
        $organizer = Organizer::findOrFail($id);
        $pageTitle = 'Notifications Sent to ' . $organizer->organization_name;
        $logs = NotificationLog::where('organizer_id', $id)->with('organizer')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs', 'organizer'));
    }

    public function featured($id)
    {
        $organizer = Organizer::findOrFail($id);
        $organizer->is_featured = !$organizer->is_featured;
        $organizer->save();

        $notify[] = ['success', 'Featured changed successfully'];
        return back()->withNotify($notify);
    }
}
