<?php
namespace App\Traits;

use App\Constants\Status;

trait OrganizerNotify
{
    public static function notifyToOrganizer(){
        return [
            'allOrganizers'              => 'All Organizers',
            'selectedOrganizers'         => 'Selected Organizers',
            'kycUnverified'         => 'Kyc Unverified Organizers',
            'kycVerified'           => 'Kyc Verified Organizers',
            'kycPending'            => 'Kyc Pending Organizers',
            'withBalance'           => 'With Balance Organizers',
            'emptyBalanceOrganizers'     => 'Empty Balance Organizers',
            'twoFaDisableOrganizers'     => '2FA Disable Organizer',
            'twoFaEnableOrganizers'      => '2FA Enable Organizer',
            'hasDepositedOrganizers'       => 'Deposited Organizers',
            'notDepositedOrganizers'       => 'Not Deposited Organizers',
            'pendingDepositedOrganizers'   => 'Pending Deposited Organizers',
            'rejectedDepositedOrganizers'  => 'Rejected Deposited Organizers',
            'topDepositedOrganizers'     => 'Top Deposited Organizers',
            'hasWithdrawOrganizers'      => 'Withdraw Organizers',
            'pendingWithdrawOrganizers'  => 'Pending Withdraw Organizers',
            'rejectedWithdrawOrganizers' => 'Rejected Withdraw Organizers',
            'pendingTicketOrganizer'     => 'Pending Ticket Organizers',
            'answerTicketOrganizer'      => 'Answer Ticket Organizers',
            'closedTicketOrganizer'      => 'Closed Ticket Organizers',
            'notLoginOrganizers'         => 'Last Few Days Not Login Organizers',
        ];
    }

    public function scopeSelectedOrganizers($query)
    {
        return $query->whereIn('id', request()->organizer ?? []);
    }

    public function scopeAllOrganizers($query)
    {
        return $query;
    }

    public function scopeEmptyBalanceOrganizers($query)
    {
        return $query->where('balance', '<=', 0);
    }

    public function scopeTwoFaDisableOrganizers($query)
    {
        return $query->where('ts', Status::DISABLE);
    }

    public function scopeTwoFaEnableOrganizers($query)
    {
        return $query->where('ts', Status::ENABLE);
    }

    public function scopeHasDepositedOrganizers($query)
    {
        return $query->whereHas('deposits', function ($deposit) {
            $deposit->successful();
        });
    }

    public function scopeNotDepositedOrganizers($query)
    {
        return $query->whereDoesntHave('deposits', function ($q) {
            $q->successful();
        });
    }

    public function scopePendingDepositedOrganizers($query)
    {
        return $query->whereHas('deposits', function ($deposit) {
            $deposit->pending();
        });
    }

    public function scopeRejectedDepositedOrganizers($query)
    {
        return $query->whereHas('deposits', function ($deposit) {
            $deposit->rejected();
        });
    }

    public function scopeTopDepositedOrganizers($query)
    {
        return $query->whereHas('deposits', function ($deposit) {
            $deposit->successful();
        })->withSum(['deposits'=>function($q){
            $q->successful();
        }], 'amount')->orderBy('deposits_sum_amount', 'desc')->take(request()->number_of_top_deposited_organizer ?? 10);
    }

    public function scopeHasWithdrawOrganizers($query)
    {
        return $query->whereHas('withdrawals', function ($q) {
            $q->approved();
        });
    }

    public function scopePendingWithdrawOrganizers($query)
    {
        return $query->whereHas('withdrawals', function ($q) {
            $q->pending();
        });
    }

    public function scopeRejectedWithdrawOrganizers($query)
    {
        return $query->whereHas('withdrawals', function ($q) {
            $q->rejected();
        });
    }

    public function scopePendingTicketOrganizer($query)
    {
        return $query->whereHas('tickets', function ($q) {
            $q->whereIn('status', [Status::TICKET_OPEN, Status::TICKET_REPLY]);
        });
    }

    public function scopeClosedTicketOrganizer($query)
    {
        return $query->whereHas('tickets', function ($q) {
            $q->where('status', Status::TICKET_CLOSE);
        });
    }

    public function scopeAnswerTicketOrganizer($query)
    {
        return $query->whereHas('tickets', function ($q) {

            $q->where('status', Status::TICKET_ANSWER);
        });
    }

    public function scopeNotLoginOrganizers($query)
    {
        return $query->whereDoesntHave('loginLogs', function ($q) {
            $q->whereDate('created_at', '>=', now()->subDays(request()->number_of_days ?? 10));
        });
    }

    public function scopeKycVerified($query)
    {
        return $query->where('kv', Status::KYC_VERIFIED);
    }

}
