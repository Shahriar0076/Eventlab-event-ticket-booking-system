<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Page;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\Category;
use App\Models\Event;
use App\Models\Organizer;
use App\Models\Location;
use App\Models\Subscriber;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use PDF;


class SiteController extends Controller
{
    public function index()
    {
        $pageTitle   = 'Home';
        $sections    = Page::where('tempname', activeTemplate())->where('slug', '/')->first();
        $seoContents = $sections->seo_content;
        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::home', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function pages($slug)
    {
        $page = Page::where('tempname', activeTemplate())->where('slug', $slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page;
        $seoContents = $page->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::pages', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }


    public function contact()
    {
        $pageTitle = "Contact Us";
        $user = auth()->user();
        $sections = Page::where('tempname', activeTemplate())->where('slug', 'contact')->first();
        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::contact', compact('pageTitle', 'user', 'sections', 'seoContents', 'seoImage'));
    }


    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = Status::PRIORITY_MEDIUM;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = Status::TICKET_OPEN;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new contact message has been submitted';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug)
    {
        $policy = Frontend::where('slug', $slug)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = @$policy->data_values->title;
        $seoContents = $policy->seo_content;
        $seoImage = @$seoContents->image ? frontendImage('policy_pages', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::policy', compact('policy', 'pageTitle', 'seoContents', 'seoImage'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return back();
    }

    public function blogs()
    {
        $pageTitle = 'All Blogs';
        $blog      = Frontend::where('data_keys', 'blog.content')->first();
        $blogs     = Frontend::where('data_keys', 'blog.element')->latest()->paginate(getPaginate(12));
        $sections  = Page::where('tempname', activeTemplate())->where('slug', 'blog')->first()?->secs;
        return view('Template::blogs', compact('blog', 'blogs', 'pageTitle', 'sections'));
    }

    public function blogDetails($slug)
    {
        $blog        = Frontend::where('slug', $slug)->where('data_keys', 'blog.element')->firstOrFail();
        $latestBlogs = Frontend::where('data_keys', 'blog.element')->whereNotIn('id', [$blog->id])->orderBy('id', 'DESC')->limit(5)->get();

        $pageTitle   = @$blog->data_values->title;
        $seoContents = $blog->seo_content;
        $seoImage    = @$seoContents->image ? frontendImage('blog', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::blog_details', compact('blog', 'pageTitle', 'seoContents', 'seoImage', 'latestBlogs'));
    }


    public function cookieAccept()
    {
        Cookie::queue('gdpr_cookie', gs('site_name'), 43200);
    }

    public function cookiePolicy()
    {
        $cookieContent = Frontend::where('data_keys', 'cookie.data')->first();
        abort_if(@$cookieContent->data_values->status != Status::ENABLE, 404);
        $pageTitle = 'Cookie Policy';
        $cookie = Frontend::where('data_keys', 'cookie.data')->first();
        return view('Template::cookie', compact('pageTitle', 'cookie'));
    }

    public function addSubscriber(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:subscribers,email',
        ]);

        if ($validator->fails()) return jsonResponse($validator->errors()->all());

        $subscriber        = new Subscriber();
        $subscriber->email = $request->email;
        $subscriber->save();

        return jsonResponse('Thanks for Subscribing', true);
    }

    public function placeholderImage($size = null)
    {
        $imgWidth = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font/solaimanLipi_bold.ttf');
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function maintenance()
    {
        $pageTitle = 'Maintenance Mode';
        if (gs('maintenance_mode') == Status::DISABLE) {
            return to_route('home');
        }
        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
        return view('Template::maintenance', compact('pageTitle', 'maintenance'));
    }

    public function allEvents(Request $request)
    {
        $pageTitle  = 'All Events';
        $categories = Category::active()->orderBy('sort_order')->get();
        $events     = Event::approved()->futureEvents()->with('location', 'organizer', 'likes');

        if ($request->search) {
            $info      = $this->searchEvents($request->search, $events);
            $pageTitle = $info['pageTitle'];
            $events    = $info['events'];
        }

        if ($request->organizer) {
            $organizer = Organizer::approved()->where('slug', $request->organizer)->firstOrFail();
            $info      = $this->organizerEvents($organizer, $events);
            $pageTitle = $info['pageTitle'];
            $events    = $info['events'];
        }

        if ($request->location) {
            $location  = Location::active()->where('slug', $request->location)->firstOrFail();
            $info      = $this->locationEvents($location, $events);
            $pageTitle = $info['pageTitle'];
            $events    = $info['events'];
        }

        if ($request->category) {
            $category = $categories->where('slug', $request->category)->firstOrFail();
            $events = $events->where('category_id', $category->id);
        }

        if ($request->sort) {
            $events = $this->sortEvents($request->sort, $events);
        }

        if (!$request->category && !$request->sort) {
            $events = $events->orderBy('is_featured', 'desc');
        }

        $eventsCount = $events->count();

        $events = $events->paginate(getPaginate(18));
        return view('Template::events', compact('pageTitle', 'events', 'categories', 'eventsCount'));
    }

    protected function searchEvents($searchQuery, $events)
    {
        $pageTitle = 'Search Results';
        $events    = $events->searchable([
            'title',
            'description',
            'short_description',
            'organizer:organization_name',
            'category:name',
            'location:name'
        ]);
        return ['pageTitle' => $pageTitle, 'events' => $events];
    }

    protected function organizerEvents($organizer, $events)
    {
        $pageTitle          = ($organizer->organization_name) . ' Events';
        $events             = $events->where('organizer_id', $organizer->id);
        return ['pageTitle' => $pageTitle, 'events' => $events];
    }

    protected function locationEvents($location, $events)
    {
        $pageTitle          = ($location->name) . ' Events';
        $events             = $events->where('location_id', $location->id);
        return ['pageTitle' => $pageTitle, 'events' => $events];
    }

    protected function sortEvents($sort, $events)
    {
        if ($sort == 'categories') {
            $events = $events->orderBy('category_id', 'desc');
        }
        if ($sort == 'location') {
            $events = $events->orderBy('location_id', 'desc');
        }
        if ($sort == 'organizer') {
            $events = $events->orderBy('organizer_id', 'desc');
        }
        if ($sort == 'newestToOldest') {
            $events = $events->orderBy('created_at', 'desc');
        }
        if ($sort == 'oldestToNewest') {
            $events = $events->orderBy('created_at', 'asc');
        }
        if ($sort == 'priceLowToHigh') {
            $events = $events->orderBy('price', 'asc');
        }
        if ($sort == 'priceHighToLow') {
            $events = $events->orderBy('price', 'desc');
        }
        return $events;
    }
    public function eventDetails($slug)
    {
        $pageTitle = 'Event Details';

        if (authOrganizer()) {
            $event = Event::where('slug', $slug)->where('organizer_id', authOrganizerId())->with('speakers', 'timeSlots', 'timeSlots.slots')->first();
            if ($event) {
                if ($event->step < 5) {
                    return returnBack('Please fill up all the essential information', route: 'organizer.event.overview', routeId: $event->id);
                }
                return view('Template::event_details', compact('pageTitle', 'event'));
            }
        }

        $event    = Event::where('slug', $slug)->approved()->with('speakers', 'timeSlots', 'timeSlots.slots')->firstOrFail();

        $seoContents['keywords']           = explode(" ", __($event->title));
        $seoContents['social_title']       = __($event->title);
        $seoContents['description']        = strLimit(__($event->short_description), 150);
        $seoContents['social_description'] = __($event->short_description);
        $seoContents['image']              = getImage(getFilePath('eventCover') . '/' . @$event->cover_image, getFileSize('eventCover'));
        $seoContents['image_size']         = getFileSize('eventCover');

        return view('Template::event_details', compact('pageTitle', 'event', "seoContents"));
    }

    public function organizerDetails($slug)
    {
        $pageTitle = 'Organizer Details';
        $organizer = Organizer::active()->profileCompleted()->where('slug', $slug)->with(['events.organizer', 'events' => function ($query) {
            $query->approved()->with('location', 'likes')->orderByDesc('is_featured');
        }])->firstOrFail();
        $events = Event::where('organizer_id', $organizer->id)->whereDate("start_date", ">=", now())->approved()->take(8)->get();
        return view('Template::organizer_details', compact('pageTitle', 'organizer', 'events'));
    }

    public function allOrganizers()
    {
        $pageTitle  = 'All Organizers';
        $organizers = Organizer::active()->profileCompleted()->with('followers')->orderByDesc('is_featured')->paginate(getPaginate());
        $sections   = Page::where('tempname', activeTemplate())->where('slug', 'organizers')->first();
        return view('Template::organizers', compact('pageTitle', 'organizers', 'sections'));
    }

    public function downloadTicket($ticketId)
    {
        try {
            $ticketId = decrypt($ticketId);
        } catch (\Throwable $th) {
            abort(404);
        }

        $ticket = Order::with('event')->find($ticketId);
        $pageTitle = 'Ticket Invoice';

        if (!$ticket) return returnBack('Ticket not found', route: 'home');

        $pdf    = PDF::loadView('Template::user.event.ticket.invoice', compact('pageTitle', 'ticket'));

        return $pdf->download('invoice.pdf');
    }
}
