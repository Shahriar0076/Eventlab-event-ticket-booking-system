<?php

namespace App\Http\Controllers\Organizer;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\Event;
use App\Models\GalleryImage;
use App\Models\Location;
use App\Models\Order;
use App\Models\Slot;
use App\Models\Speaker;
use App\Models\TimeSlot;
use App\Models\Transaction;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Events';
        $events    = $this->filterEvents();
        return view('Template::organizer.event.index', compact('pageTitle', 'events'));
    }

    public function draft()
    {
        $pageTitle = 'Draft Events';
        $events    = $this->filterEvents('draft');
        return view('Template::organizer.event.index', compact('pageTitle', 'events'));
    }

    public function approved()
    {
        $pageTitle = 'Approved Events';
        $events    = $this->filterEvents('approved');
        return view('Template::organizer.event.index', compact('pageTitle', 'events'));
    }

    public function pending()
    {
        $pageTitle = 'Pending Events';
        $events    = $this->filterEvents('pending');
        return view('Template::organizer.event.index', compact('pageTitle', 'events'));
    }

    public function rejected()
    {
        $pageTitle = 'Rejected Events';
        $events    = $this->filterEvents('rejected');
        return view('Template::organizer.event.index', compact('pageTitle', 'events'));
    }

    public function running()
    {
        $pageTitle = 'Running Events';
        $events    = $this->filterEvents('running');
        return view('Template::organizer.event.index', compact('pageTitle', 'events'));
    }

    public function upcoming()
    {
        $pageTitle = 'Upcoming Events';
        $events    = $this->filterEvents('futureEvents');
        return view('Template::organizer.event.index', compact('pageTitle', 'events'));
    }

    protected function filterEvents($scope = null)
    {
        $events     = Event::where('organizer_id', authOrganizerId());
        if ($scope) {
            $events = $events->$scope();
        }
        return $events->searchable(['title'])->orderBy('id', 'desc')->paginate(getPaginate());
    }

    protected function setStatus($isUpdate, $event, $step = 0, $publishPage = false)
    {
        if ($isUpdate) {
            $status = Status::EVENT_APPROVED;
            if (!$publishPage) $step = false;
        } else {
            $status = Status::EVENT_DRAFT;
        }

        if ($status == Status::EVENT_APPROVED) {
            if (gs('event_verification')) {
                $event->status = Status::EVENT_PENDING;
            } else {
                $event->status = Status::EVENT_APPROVED;
            }
        } else {
            $event->status     = $status;
        }

        if ($step) $event->step = $step;

        return $event;
    }

    public function overview($id = 0)
    {
        $pageTitle  = 'Event Overview';
        $categories = Category::active()->orderBy('sort_order')->get();
        $locations  = Location::active()->orderBy('sort_order')->get();
        $event      = Event::where('id', $id)->where('organizer_id', authOrganizerId())->first();

        if (!$event && $id) return returnBack('Event not found', route: 'organizer.event.index');

        if ($event) {
            if ($event->step == Status::OVERVIEW && $event->status == Status::EVENT_DRAFT) {
                return to_route('organizer.event.info', $event->id);
            }
            if ($event->step == Status::INFO && $event->status == Status::EVENT_DRAFT) {
                return to_route('organizer.event.time.slots', $event->id);
            }
            if ($event->step == Status::TIME_SLOTS && $event->status == Status::EVENT_DRAFT) {
                return to_route('organizer.event.gallery', $event->id);
            }
            if ($event->step == Status::GALLERY && $event->status == Status::EVENT_DRAFT) {
                return to_route('organizer.event.speakers', $event->id);
            }
            if ($event->step == Status::SPEAKERS && $event->status == Status::EVENT_DRAFT) {
                return to_route('organizer.event.publish', $event->id);
            }
        }

        return view('Template::organizer.event.overview', compact('pageTitle', 'categories', 'locations', 'event'));
    }

    public function storeOverview(Request $request, $id = 0)
    {
        $validation  = Validator::make($request->all(), [
            'category'         => 'required',
            'location'         => 'required',
            'type'             => 'required|in:' . Status::EVENT_OFFLINE . ',' . Status::EVENT_ONLINE,
            'link'             => 'nullable|url',
            'location_address' => 'required|string',
        ]);

        if ($validation->fails()) return jsonResponse($validation->errors()->all());

        if (!$request->link && $request->type == Status::EVENT_ONLINE) return jsonResponse('Please insert the meeting link');

        $categories = Category::active()->where('id', $request->category)->first();

        if (!$categories) {
            return jsonResponse('Category not found');
        }

        $location   = Location::active()->where('id', $request->location)->first();

        if (!$location) {
            return jsonResponse('Location not found');
        }

        $organizer = authOrganizer();

        if ($id) {
            $event = Event::where('organizer_id', $organizer->id)->where('id', $id)->first();
            if (!$event) return jsonResponse('Event not found');
        } else {
            $event = new Event();
        }

        $isUpdate = $event->step >= Status::OVERVIEW;
        $event    = $this->setStatus($isUpdate, $event, Status::OVERVIEW);

        $event->organizer_id     = authOrganizerId();
        $event->category_id      = $request->category;
        $event->location_id      = $request->location;
        $event->type             = $request->type;
        $event->link             = $request->link;
        $event->location_address = $request->location_address;
        $event->save();

        return $this->returnResponse($isUpdate, $event, 'organizer.event.info');
    }

    public function info($id)
    {
        $pageTitle = 'Event Information';
        $event     = Event::where('id', $id)->where('organizer_id', authOrganizerId())->firstOrFail();
        abort_if($event->step < Status::OVERVIEW, 404);
        return view('Template::organizer.event.info', compact('pageTitle', 'event'));
    }

    public function storeInfo(Request $request, $id)
    {
        $validation             = Validator::make($request->all(), [
            'title'             => 'required|string',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'seats'             => 'required|numeric|gt:0',
            'price'             => 'required|numeric|gte:0 ',
        ]);

        if ($validation->fails()) return jsonResponse($validation->errors()->all());

        $event = Event::where('id', $id)->where('organizer_id', authOrganizerId())->first();
        if (!$event) return jsonResponse('Event not found');

        $isUpdate = $event->step >= Status::INFO;
        $event    = $this->setStatus($isUpdate, $event, Status::INFO);

        if ($isUpdate && $request->seats < $event->seats_booked) {
            return jsonResponse($event->seats_booked . ' seats are booked. Amount of seats must be greater than seats booked!');
        }

        if ($event->start_date != $request->start_date || $event->end_date != $request->end_date) {
            $isUpdate = false;
        }

        if ($request->title != $event->title) {
            $event->slug = slug($this->make(slug($request->title), $id));
        }

        $purifier                 = new \HTMLPurifier();
        $event->title             = $request->title;

        $event->short_description = $request->short_description;
        $event->description       = $purifier->purify($request->description);
        $event->start_date        = $request->start_date;
        $event->end_date          = $request->end_date;
        $event->price             = $request->price;
        $event->seats             = $request->seats;
        $event->save();

        return $this->returnResponse($isUpdate, $event, 'organizer.event.time.slots');
    }

    public function make($slug, $id)
    {

        $duplicateSlug = Event::where('id', '!=', $id)->where('slug', 'LIKE', $slug . '%')->orderBy('slug', 'desc')->first();

        if ($duplicateSlug) {
            $slugArray = explode('-', $duplicateSlug->slug);

            $slug = $slug . '-' . (int) end($slugArray) + 1;
        }
        return $slug;
    }

    public function timeSlots($id)
    {
        $pageTitle = 'Time Slots';
        $event     = Event::where('id', $id)->where('organizer_id', authOrganizerId())->with('timeSlots', 'timeSlots.slots')->firstOrFail();
        abort_if($event->step < Status::INFO, 404);
        return view('Template::organizer.event.time_slots', compact('pageTitle', 'event'));
    }

    public function storeTimeSlots(Request $request, $id)
    {
        $event = Event::where('id', $id)->where('organizer_id', authOrganizerId())->first();

        if (!$event) {
            return jsonResponse('Event not found');
        }

        $startDate = Carbon::parse($event->start_date);
        $endDate = Carbon::parse($event->end_date);
        $numberOfDays = $startDate->diffInDays($endDate) + 1;

        $validation  = Validator::make($request->all(), [
            'time_slots' => 'required|array|min:' . $numberOfDays,
            'time_slots.*.date' => 'required|date_format:Y-m-d',
            'time_slots.*.slots' => 'required|array|min:1',
            'time_slots.*.slots.*.start_time' => 'required|date_format:H:i',
            'time_slots.*.slots.*.end_time' => 'required|after_or_equal:time_slots.*.slots.*.start_time',
            'time_slots.*.slots.*.title' => 'required|string:255',
            'time_slots.*.slots.*.description' => 'required|string:600',

        ], [
            'time_slots' => 'You must insert at least one time slot for each date',
            'time_slots.*.date' => 'Invalid Request',
            'time_slots.*.slots' => 'Invalid Request',
            'time_slots.*.slots.*.start_time' => 'Start time is required',
            'time_slots.*.slots.*.end_time.required' => 'End time is required',
            'time_slots.*.slots.*.end_time.after_or_equal' => 'End time must be greater than start time',
            'time_slots.*.slots.*.title' => 'Title is required',
            'time_slots.*.slots.*.description' => 'Description is required',
        ]);

        if ($validation->fails()) {
            return jsonResponse($validation->errors()->all());
        }

        // Fix the indexing
        $timeSlots = $request->time_slots;

        foreach ($timeSlots as &$entry) {
            $entry['slots'] = array_values($entry['slots']);
        }

        //delete old timeSlots
        if ($event->timeSlots) {
            foreach ($event->timeSlots as $timeSlot) {
                if ($timeSlot->slots) {
                    foreach ($timeSlot->slots as $slot) {
                        $slot->delete();
                    }
                }
                $timeSlot->delete();
            }
        }

        foreach ($timeSlots as $timeSlot) {
            $date  = $timeSlot['date'];
            $slots = $timeSlot['slots'];

            $timeSlotData = new TimeSlot();
            $timeSlotData->event_id = $event->id;
            $timeSlotData->date = $date;
            $timeSlotData->save();

            foreach ($slots as $slot) {
                $data                = new Slot();
                $data->time_slot_id  = $timeSlotData->id;
                $data->start_time    = $slot['start_time'];
                $data->end_time      = $slot['end_time'];
                $data->title         = $slot['title'];
                $data->description   = $slot['description'];
                $data->save();
            }
        }

        $isUpdate = $event->step >= Status::TIME_SLOTS;
        $event             = $this->setStatus($isUpdate, $event, Status::TIME_SLOTS);
        $event->save();

        return $this->returnResponse($isUpdate, $event, 'organizer.event.gallery');
    }

    public function gallery($id)
    {
        $pageTitle = 'Event Gallery';
        $event     = Event::where('id', $id)->where('organizer_id', authOrganizerId())->firstOrFail();
        abort_if($event->step < Status::TIME_SLOTS, 404);
        return view('Template::organizer.event.gallery', compact('pageTitle', 'event'));
    }

    public function storeGallery(Request $request, $id)
    {
        $event = Event::where('id', $id)->where('organizer_id', authOrganizerId())->first();

        if (!$event) {
            return jsonResponse('Event not found');
        }

        $isCoverRequired = $event->cover_image ? 'nullable' : 'required';
        $validation = Validator::make($request->all(), [
            'cover_image'      => [$isCoverRequired, 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'gallery_images.*' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if ($validation->fails()) {
            return jsonResponse($validation->errors()->all());
        }

        $isUpdate = $event->step >= Status::GALLERY;
        if ($request->hasFile('cover_image')) {
            try {
                $old   = ($isUpdate ? $event->cover_image : null);
                $thumb = getFileThumbSize('eventCover');

                $event->cover_image = fileUploader(
                    $request->cover_image,
                    getFilePath('eventCover'),
                    getFileSize('eventCover'),
                    $old,
                    $thumb
                );
            } catch (\Exception $exp) {
                return returnBack('Couldn\'t upload your image');
            }
        }

        $this->removeOldGalleryImages($event, $request->oldGalleryImages);

        if ($request->gallery_images) {

            foreach ($request->gallery_images as $image) {

                $galleryImage           = new GalleryImage();
                $galleryImage->event_id = $event->id;

                try {
                    $galleryImage->image = fileUploader($image, getFilePath('eventGallery'), getFileSize('eventGallery'));
                } catch (\Exception $exp) {
                    return returnBack('Couldn\'t upload your image');
                }

                $galleryImage->save();
            }
        }

        $event = $this->setStatus($isUpdate, $event, Status::GALLERY);
        $event->save();

        return $this->returnResponse($isUpdate, $event, 'organizer.event.speakers');
    }

    protected function removeOldGalleryImages($event, $oldImages)
    {
        $previousImages  = $event->galleryImages->pluck('id')->toArray();
        $imageToRemove = array_values(array_diff($previousImages, $oldImages ?? []));

        foreach ($imageToRemove as $item) {
            $galleryImage   = GalleryImage::find($item);
            removeFile(getFilePath('eventGallery'), $galleryImage->image);
            $galleryImage->delete();
        }
        return true;
    }

    public function speakers($id)
    {
        $pageTitle = 'Event Speakers';
        $event     = Event::where('id', $id)->where('organizer_id', authOrganizerId())->with('speakers')->firstOrFail();
        abort_if($event->step < Status::GALLERY, 404);
        return view('Template::organizer.event.speakers', compact('pageTitle', 'event'));
    }

    public function storeSpeakers(Request $request, $id)
    {

        $validation = Validator::make($request->all(), [
            'speakers'                 => 'nullable|array|min:1',
            'speakers.*.name'          => 'required|string',
            'speakers.*.designation'   => 'required|string',
            'speakers.*.facebook_url'  => 'nullable|url',
            'speakers.*.youtube_url'   => 'nullable|url',
            'speakers.*.instagram_url' => 'nullable|url',
            'speakers.*.image_old'     => 'nullable|string',
            'speakers.*.image'         => ['required_without:speakers.*.image_old', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'deleted_speakers'         => 'nullable|array|min:1',
        ], [
            'speakers.*.name.required'          => 'The name of speaker is required.',
            'speakers.*.name.string'            => 'The name of speaker must be a string.',
            'speakers.*.designation.required'   => 'The designation of speaker is required.',
            'speakers.*.designation.string'     => 'The designation of speaker must be a string.',
            'speakers.*.facebook_url.url'       => 'The Facebook URL must be a valid URL.',
            'speakers.*.youtube_url.url'        => 'The YouTube URL must be a valid URL.',
            'speakers.*.instagram_url.url'      => 'The Instagram URL must be a valid URL.',
            'speakers.*.image.required_without' => 'Please upload speaker image',
            'speakers.*.image.image'            => 'The image must be an image file.',
            'speakers.*.image'                  => 'The image must be a JPG, JPEG, or PNG file.',
        ]);

        if ($validation->fails()) {
            return jsonResponse($validation->errors()->all());
        }

        $event = Event::where('id', $id)->where('organizer_id', authOrganizerId())->first();

        if (!$event) {
            return jsonResponse('Event not found');
        }

        foreach (@$request->deleted_speakers ?? [] as $deletedSpeakers) {
            $deleteItem = Speaker::where('event_id', $event->id)->where('id', $deletedSpeakers)->first();
            if ($deleteItem) {
                removeFile(getFilePath('eventSpeaker'), @$deleteItem->image ?? null);
                $deleteItem->delete();
            }
        }

        foreach ($request->speakers ?? [] as $id => $speaker) {
            $image = null;
            $social = [];
            $social['facebook_url'] = @$speaker['facebook_url'];
            $social['instagram_url'] = @$speaker['instagram_url'];
            $social['youtube_url'] = @$speaker['youtube_url'];

            if (@$speaker['image']) {
                try {
                    $image = fileUploader(@$speaker['image'], getFilePath('eventSpeaker'), getFileSize('eventSpeaker'), @$speaker['image_old']);
                } catch (\Exception $exp) {
                    return returnBack('Couldn\'t upload your image');
                }
            } else {
                $image = @$speaker['image_old'];
            }

            $speakerExists = Speaker::where('event_id', $event->id)->where('id', $id)->first();

            if ($speakerExists) {
                $data = $speakerExists;
            } else {
                $data = new Speaker();
            }

            $data->event_id = $event->id;
            $data->name = $speaker['name'];
            $data->designation = $speaker['designation'];
            $data->social = $social;
            $data->image = $image;
            $data->save();
        }

        $isUpdate        = $event->step >= Status::SPEAKERS;
        $event           = $this->setStatus($isUpdate, $event, Status::SPEAKERS);
        $event->save();

        return $this->returnResponse($isUpdate, $event, 'organizer.event.publish');
    }

    public function publish($id)
    {
        $pageTitle    = 'Publish Event';
        $event        = Event::where('id', $id)->where('organizer_id', authOrganizerId())->firstOrFail();
        abort_if($event->step < Status::SPEAKERS, 404);
        return view('Template::organizer.event.publish', compact('pageTitle', 'event'));
    }

    public function storePublish($id)
    {
        $event = Event::where('id', $id)->where('organizer_id', authOrganizerId())->first();

        if (!$event) return jsonResponse('Event not found');

        $event = $this->setStatus(true, $event, Status::PUBLISH, publishPage: true);

        $event->save();

        if ($event->is_published == Status::YES && ($event->status == Status::EVENT_PENDING || $event->status == Status::EVENT_REJECTED)) {

            notify($event->organizer, 'EVENT_PENDING', [
                'event_id'   => $event->id,
                'event_name' => $event->title,
                'event_url'  => route('organizer.event.overview', $event->id),
            ]);

            $adminNotification               = new AdminNotification();
            $adminNotification->organizer_id = $event->organizer->id;
            $adminNotification->title        = 'Event Verification Pending';
            $adminNotification->click_url    = route('admin.event.details', $event->id);
            $adminNotification->save();

            $event->status = Status::EVENT_PENDING;
            $event->save();
        }

        return response()->json([
            'success'      => true,
            'status'       => $event->is_published,
            'redirect_url' => route('organizer.event.index')
        ]);
    }

    public function tickets($eventId)
    {
        $event     = Event::organizerEvents()->findOrFail($eventId);
        $pageTitle = $event->name . ' Tickets';

        $tickets = Order::where('event_id', $event->id)->with('user')->latest();
        if (!$tickets->count()) return returnBack('No tickets found');

        $tickets = $tickets->paginate(getPaginate());

        return view('Template::organizer.event.ticket.index', compact('pageTitle', 'tickets'));
    }

    public function completedOrders()
    {
        $pageTitle = 'Tickets Sold';
        $tickets   = Order::organizerTicketsSold()->completed()->with('event')->latest()->paginate(getPaginate());
        return view('Template::organizer.event.ticket.index', compact('pageTitle', 'tickets'));
    }

    public function ticketDetails($ticketId)
    {
        $pageTitle = 'Ticket Details';
        $ticket    = Order::organizerTicketsSold()->with('event')->findOrFail($ticketId);
        return view('Template::organizer.event.ticket.details', compact('pageTitle', 'ticket'));
    }

    public function cancelTicket($ticketId)
    {
        $order = Order::organizerTicketsSold()->where('status', '!=', Status::ORDER_CANCELLED)->with('event')->findOrFail($ticketId);
        $user  = $order->user;
        $event = $order->event;

        //organizer canel ticket anytime
        $trx = 'N/A';
        //if payment done refund
        if ($order->payment_status == Status::PAID && $order->price > 0) {
            $trx            = getTrx();
            $user->balance += $order->total_price;
            $user->save();

            $eventName = $event->title;

            $transaction               = new Transaction();
            $transaction->user_id      = $order->user_id;
            $transaction->amount       = $order->total_price;
            $transaction->post_balance = $user->balance;
            $transaction->charge       = 0;
            $transaction->trx_type     = '+';
            $transaction->details      = "Refund Completed for $eventName";
            $transaction->trx          = $trx;
            $transaction->remark       = 'order_refund';
            $transaction->save();

            $organizer           = $order->event->organizer;
            $organizer->balance -= $order->total_price;
            $organizer->save();
            $userUsername = $order->user->username;

            $transaction               = new Transaction();
            $transaction->organizer_id = $organizer->id;
            $transaction->amount       = $order->total_price;
            $transaction->post_balance = $organizer->balance;
            $transaction->charge       = 0;
            $transaction->trx_type     = '-';
            $transaction->details      = "Refund Completed for $eventName, ticket cancelled by user $userUsername";
            $transaction->trx          = $trx;
            $transaction->remark       = 'order_payment';
            $transaction->save();
        }

        //add event seats
        $event->seats_booked -= $order->quantity;
        $event->save();

        $order->status = Status::ORDER_CANCELLED;
        $order->save();

        //send confirmation email to user
        notify($order->user, 'ORDER_CANCELLED', [
            'trx'          => $trx,
            'order_number' => $order->id,
            'event_name'   => $order->event->title,
            'start_date'   => $order->event->start_date,
            'end_date'     => $order->event->end_date,
            'price'        => showAmount($order->price),
            'quantity'     => $order->quantity,
            'total_price'  => showAmount($order->total_price),
            'ticket_url'   => ticketDownloadUrl($order->id),
        ]);

        //send CANCELLED email to organizer
        notify($order->event->organizer, 'ORDER_CANCELLED', [
            'trx'          => $trx,
            'order_number' => $order->id,
            'event_name'   => $order->event->title . " cancelled by user " . $order->user->username,
            'start_date'   => $order->event->start_date,
            'end_date'     => $order->event->end_date,
            'price'        => showAmount($order->price),
            'quantity'     => $order->quantity,
            'total_price'  => showAmount($order->total_price),
            'ticket_url'   => ticketDownloadUrl($order->id),
        ]);

        $notify[] = ['success', 'Ticket cancelled Successfully'];
        return back()->withNotify($notify);
    }

    private function returnResponse($isUpdate, $event, $route)
    {
        return response()->json([
            'success'      => true,
            'is_update'    => $isUpdate,
            'redirect_url' => route($route, $event->id)
        ]);
    }
}
