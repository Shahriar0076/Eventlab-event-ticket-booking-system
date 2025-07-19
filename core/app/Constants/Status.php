<?php

namespace App\Constants;

class Status
{

    const ENABLE  = 1;
    const DISABLE = 0;

    const YES = 1;
    const NO  = 0;

    const VERIFIED   = 1;
    const UNVERIFIED = 0;

    const PAYMENT_INITIATE = 0;
    const PAYMENT_SUCCESS  = 1;
    const PAYMENT_PENDING  = 2;
    const PAYMENT_REJECT   = 3;

    const TICKET_OPEN   = 0;
    const TICKET_ANSWER = 1;
    const TICKET_REPLY  = 2;
    const TICKET_CLOSE  = 3;

    const PRIORITY_LOW    = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH   = 3;

    const USER_ACTIVE = 1;
    const USER_BAN    = 0;

    const ORGANIZER_ACTIVE = 1;
    const ORGANIZER_BAN    = 0;

    const KYC_UNVERIFIED = 0;
    const KYC_PENDING    = 2;
    const KYC_VERIFIED   = 1;

    const GOOGLE_PAY = 5001;

    const CUR_BOTH = 1;
    const CUR_TEXT = 2;
    const CUR_SYM  = 3;

    const ORDER_COMPLETED       = 1;
    const ORDER_PAYMENT_PENDING = 2;
    const ORDER_CANCELLED       = 3;

    const EVENT_APPROVED = 1;
    const EVENT_PENDING  = 2;
    const EVENT_REJECTED = 3;
    const EVENT_DRAFT    = 4;

    const EVENT_OFFLINE = 1;
    const EVENT_ONLINE  = 2;

    const PAID   = 1;
    const UNPAID = 0;

    const OVERVIEW   = 1;
    const INFO       = 2;
    const TIME_SLOTS = 3;
    const GALLERY    = 4;
    const SPEAKERS   = 5;
    const PUBLISH    = 6;
}
