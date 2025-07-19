<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Traits\SupportTicketManager;

class TicketController extends Controller
{
    use SupportTicketManager;

    public function __construct()
    {
        parent::__construct();
        $this->layout = 'frontend';
        $this->redirectLink = 'organizer.ticket.view';
        $this->userType     = 'organizer';
        $this->column       = 'organizer_id';
        $this->user         = authOrganizer();
        if ($this->user) {
            $this->layout = 'master';
        }
    }
}
