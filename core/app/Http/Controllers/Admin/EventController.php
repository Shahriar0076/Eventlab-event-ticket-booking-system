<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Organizer;
use Illuminate\Http\Request;

class EventController extends Controller {
    public function index() {
        $pageTitle = 'All Events';
        $events    = $this->eventData();
        return view('admin.event.index', compact('pageTitle', 'events'));
    }

    public function approved() {
        $pageTitle = 'Approved Events';
        $events    = $this->eventData('approved');
        return view('admin.event.index', compact('pageTitle', 'events'));
    }

    public function pending() {
        $pageTitle = 'Pending Events';
        $events    = $this->eventData('pending');
        return view('admin.event.index', compact('pageTitle', 'events'));
    }

    public function rejected() {
        $pageTitle = 'Rejected Events';
        $events    = $this->eventData('rejected');
        return view('admin.event.index', compact('pageTitle', 'events'));
    }

    public function futureEvents() {
        $pageTitle = 'Future Events Events';
        $events    = $this->eventData('futureEvents');
        return view('admin.event.index', compact('pageTitle', 'events'));
    }

    public function running() {
        $pageTitle = 'Running Events';
        $events    = $this->eventData('running');
        return view('admin.event.index', compact('pageTitle', 'events'));
    }

    public function expired() {
        $pageTitle = 'Expired Events';
        $events    = $this->eventData('expired');
        return view('admin.event.index', compact('pageTitle', 'events'));
    }

    protected function eventData($scope = null, $organizerId = 0) {
        if ($scope) {
            $events = Event::$scope()->with(['organizer', 'category', 'location']);
        } else {
            $events = Event::with(['organizer', 'category', 'location']);
        }

        if ($organizerId) {
            $events = $events->where('organizer_id', $organizerId);
        }

        $events = $events->searchable(['title', 'organizer:username', 'organizer:organization_name', 'category:name', 'location:name']);

        return $events->latest()->paginate(getPaginate());
    }

    public function featured($id) {
        $event              = Event::findOrFail($id);
        $event->is_featured = !$event->is_featured;
        $event->save();

        $notify[]           = ['success', 'Event changed successfully'];
        return back()->withNotify($notify);
    }

    public function organizerEvents($id) {
        $organizer = Organizer::findOrFail($id);
        $pageTitle = $organizer->organization_name . ' Events';
        $events    = $this->eventData(null, $id);
        return view('admin.event.index', compact('pageTitle', 'events'));
    }

    public function details($id) {
        $pageTitle = 'Events Details';
        $event     = Event::with('speakers','timeSlots','timeSlots.slots')->findOrFail($id);
        return view('admin.event.details', compact('pageTitle', 'event'));
    }

    public function changeStatus(Request $request, $eventId) {
        $request->validate([
            'status'                 => 'required|integer|in:' . Status::EVENT_APPROVED . ',' . Status::EVENT_REJECTED,
            'verification_details'   => 'nullable|string'
        ]);

        if ($request->status == Status::EVENT_APPROVED) {
            $notificationName = 'EVENT_APPROVED';
        } elseif ($request->status == Status::EVENT_REJECTED) {
            $notificationName = 'EVENT_REJECTED';
            if (!$request->verification_details) {
                $notify[] = ['error', 'Please insert reason for rejection'];
                return back()->withNotify($notify);
            }
        }

        $event                       = Event::findOrFail($eventId);
        $event->status               = $request->status;
        $event->verification_details = $request->verification_details;
        $event->save();

        notify($event->organizer, $notificationName, [
            'event_id'               => $event->id,
            'event_name'             => $event->title,
            'event_url'              => route('organizer.event.overview', $event->id),
        ]);

        $notify[]                    = ['success', 'Event status changed successfully'];
        return back()->withNotify($notify);
    }
}
