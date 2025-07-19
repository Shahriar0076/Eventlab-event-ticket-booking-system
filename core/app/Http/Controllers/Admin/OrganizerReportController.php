<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\Transaction;
use App\Models\UserLogin;
use Illuminate\Http\Request;

class OrganizerReportController extends Controller
{
    public function transaction(Request $request, $organizerId = null)
    {
        $pageTitle = 'Transaction Logs';

        $remarks = Transaction::organizerTransaction()->distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::organizerTransaction()->searchable(['trx', 'organizer:username'])->filter(['trx_type', 'remark'])->dateFilter()->orderBy('id', 'desc')->with('organizer');

        if ($organizerId) {
            $transactions = $transactions->where('organizer_id', $organizerId);
        }

        $transactions = $transactions->paginate(getPaginate());

        return view('admin.reports.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function loginHistory(Request $request)
    {
        $pageTitle = 'Organizer Login History';
        $loginLogs = UserLogin::organizerLogin()->orderBy('id', 'desc')->searchable(['organizer:username'])->dateFilter()->with('organizer')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs'));
    }

    public function loginIpHistory($ip)
    {
        $pageTitle = 'Login by - ' . $ip;
        $loginLogs = UserLogin::organizerLogin()->where('ip', $ip)->orderBy('id', 'desc')->with('organizer')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs', 'ip'));
    }

    public function notificationHistory(Request $request)
    {
        $pageTitle = 'Notification History';
        $logs = NotificationLog::organizerNotification()->orderBy('id', 'desc')->searchable(['organizer:username'])->dateFilter()->with('organizer')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs'));
    }

    public function emailDetails($id)
    {
        $pageTitle = 'Email Details';
        $email = NotificationLog::organizerNotification()->findOrFail($id);
        return view('admin.reports.email_details', compact('pageTitle', 'email'));
    }
}
