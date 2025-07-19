@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30 justify-content-center">
        <div class="col-xl-4 col-md-6 mb-30">
            <div class="card  overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Event')</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Title')
                            <span class="fw-bold">{{ __(@$event->title) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Type')
                            <span class="fw-bold">
                                @if (@$event->type == Status::EVENT_OFFLINE)
                                    @lang('Offline')
                                @else
                                    @lang('Online')
                                @endif
                            </span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Organizer')
                            <span class="fw-bold"> <a
                                    href="{{ route('admin.organizers.detail', @$event->organizer->id) }}">{{ __(@$event->organizer->organization_name) }}</a>
                            </span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Category')
                            <span class="fw-bold">{{ __(@$event->category->name) }} </span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Location')
                            <span class="fw-bold">{{ __(@$event->location->name) }} </span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Start Date')
                            <span class="fw-bold">{{ showDateTime(@$event->start_date, 'F j, Y') }} </span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('End Date')
                            <span class="fw-bold">{{ showDateTime(@$event->end_date, 'F j, Y') }} </span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Seats')
                            <span class="fw-bold">{{ @$event->seats }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Seats Booked')
                            <span class="fw-bold">{{ @$event->seats_booked }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Speakers')
                            <span class="fw-bold">
                                @if (@$event->speakers)
                                    {{ count(@$event->speakers) }}
                                @endif
                            </span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Featured')
                            <span class="fw-bold">@php echo @$event->featuredBadge @endphp</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Price')
                            <span class="fw-bold">{{ gs('cur_sym') }}{{ __(@$event->price) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            <span class="fw-bold">@php echo @$event->statusBadge() @endphp</span>
                        </li>
                    </ul>
                </div>
            </div>

            @if (@$event->timeSlots)
                <div class="card  overflow-hidden box--shadow1 mt-2">
                    <div class="card-body">
                        <h5 class="mb-20 text-muted">@lang('Time Slots')</h5>
                        <div class="accordion custom--accordion">
                            @foreach (@$event->timeSlots ?? [] as $timeSlot)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="faqHeading{{ $loop->iteration }}">
                                        <button class="accordion-button @if (!$loop->first) collapsed @endif"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#faqCollapse{{ $loop->iteration }}"
                                            @if ($loop->first) aria-expanded="true"
                                            @else
                                            aria-expanded="false" @endif
                                            aria-controls="faqCollapse{{ $loop->iteration }}">
                                            <span>{{ $timeSlot->date }}</span>
                                        </button>
                                    </h2>
                                    <div id="faqCollapse{{ $loop->iteration }}"
                                        class="accordion-collapse collapse @if ($loop->first) show @endif"
                                        aria-labelledby="faqHeading{{ $loop->iteration }}"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            @foreach (@$timeSlot->slots ?? [] as $slot)
                                                <div class="event-info-wrapper">
                                                    <div class="event-info-group">
                                                        <h6>@lang('Time'):</h6>
                                                        <p>{{ $slot->start_time }} - {{ $slot->end_time }}</p>
                                                    </div>

                                                    <div class="event-info-group">
                                                        <h6>@lang('Title'):</h6>
                                                        <p>{{ __($slot->title) }}</p>
                                                    </div>

                                                    <div class="event-info-group">
                                                        <h6>@lang('Description'):</h6>
                                                        <p>{{ __($slot->description) }}</p>
                                                    </div>
                                                </div>
                                                @if (!$loop->last)
                                                @endif()
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if (@$event->link && @$event->type == Status::EVENT_OFFLINE)
                <div class="card  overflow-hidden box--shadow1 mt-2">
                    <div class="card-body">
                        <h5 class="mb-20 text-muted">@lang('Location')</h5>
                        <div class="details__map">
                            <div class="map">
                                <iframe src="{{ @$event->link }}" width="100%" height="250" style="border:0;"
                                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <div class="col-xl-8 col-md-6 mb-30">

            <div class="card  overflow-hidden box--shadow1 mb-3">
                <div class="card-body">
                    <h5 class="card-title  border-bottom mb-3 text-muted">@lang('Info')</h5>
                    <div class="event-info-group">
                        <h6 for="">@lang('Short Description'):</h6>
                        <p class="mb-3">{{ @$event->short_description }}</p>
                    </div>
                    <div class="event-info-group">
                        <h6 for="">@lang('Description'):</h6>
                        <p>@php echo @$event->description @endphp</p>
                    </div>
                    <div class="event-info-group">
                        @if (@$event->location_address)
                            <h6 for="">@lang('Event Address'):</h6>
                            <p>{{ __(@$event->location_address) }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card  overflow-hidden box--shadow1 mb-3">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2 text-muted">@lang('Cover Photo')</h5>
                    <div class="d-flex justify-content-center">
                        <img class="rounded-3"
                            src="{{ getImage(getFilePath('eventCover') . '/' . @$event->cover_image, getFileSize('eventCover')) }}"
                            alt="event_cover">
                    </div>
                </div>
            </div>

            <div class="card  overflow-hidden box--shadow1 mb-3">
                <div class="card-body">
                    <h5 class="card-title  border-bottom pb-2 text-muted">@lang('Speakers')</h5>
                    <div class="row gy-4">
                        @forelse (@$event->speakers ?? [] as $speaker)
                            <div class="col-xl-3 col-lg-4 col-sm-6">
                                <div class="speaker-item">
                                    <img class="rounded-3"
                                        src="{{ getImage(getFilePath('eventSpeaker') . '/' . $speaker->image, getFileSize('eventSpeaker')) }}"
                                        alt="speaker_profile">
                                    <div class="speaker-item__content mt-2">
                                        <h6 class="speaker-item__name text-center">
                                            <span class="speaker__name"> {{ __($speaker->name) }} </span>
                                        </h6>
                                        <p class="speaker-item__title text-center">
                                            {{ __($speaker->designation) }} </p>
                                        <ul class="social-list social-list-two d-flex justify-content-center mt-2">
                                            @if ($speaker->social['facebook_url'])
                                                <li class="social-list__item"><a
                                                        href="{{ $speaker->social['facebook_url'] }}"
                                                        class="social-list__link flex-center" target="_blank"> <i
                                                            class="lab la-facebook-f"></i></a></li>
                                            @endif

                                            @if ($speaker->social['instagram_url'])
                                                <li class="social-list__item"><a
                                                        href="{{ $speaker->social['instagram_url'] }}"
                                                        class="social-list__link flex-center" target="_blank"> <i
                                                            class="lab la-youtube"></i></a></li>
                                            @endif

                                            @if ($speaker->social['youtube_url'])
                                                <li class="social-list__item"><a
                                                        href="{{ $speaker->social['youtube_url'] }}"
                                                        class="social-list__link flex-center" target="_blank"> <i
                                                            class="lab la-instagram"></i></a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>@lang('No Speakers added')</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="card  overflow-hidden box--shadow1 mb-3">
                <div class="card-body">
                    <h5 class="card-title  border-bottom pb-2 text-muted">@lang('Gallery')</h5>
                    <div class="row gy-4">
                        @forelse (@$event->galleryImages as $image)
                            <div class="col-xl-3 col-md-4 col-6">
                                <div class="d-flex justify-content-center">
                                    <img class="rounded-3"
                                        src="{{ getImage(getFilePath('eventGallery') . '/' . $image->image) }}"
                                        alt="event_image">
                                </div>
                            </div>
                        @empty
                            <p>@lang('No Images added')</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- APPROVE MODAL --}}
    <div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Approve Event Confirmation')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.event.change.status', @$event->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="{{ Status::EVENT_APPROVED }}">
                    <div class="modal-body">
                        <p>@lang('Are you sure to approve this event?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- REJECT MODAL --}}
    <div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Reject Event Confirmation')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.event.change.status', @$event->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="{{ Status::EVENT_REJECTED }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Reason of Rejection')</label>
                            <textarea name="verification_details" class="form-control pt-3" rows="3"
                                value="{{ old('verification_details') }}" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')

    @if ($event->status == Status::EVENT_APPROVED)
        @if ($event->is_featured)
            <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn" data-question="@lang('Are you sure to unfeature this event?')"
                data-action="{{ route('admin.event.featured', $event->id) }}">
                <i class="la la-eye-slash"></i> @lang('Unfeature')
            </button>
        @else
            <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn" data-question="@lang('Are you sure to feature this event?')"
                data-action="{{ route('admin.event.featured', $event->id) }}">
                <i class="la la-eye"></i> @lang('Make Feature')
            </button>
        @endif
    @endif

    @if ($event->status == Status::EVENT_PENDING)
        <button class="btn btn-outline--success ms-1 approveBtn" data-id="3" data-amount="48.00 USD">
            <i class="fas la-check"></i> @lang('Approve')
        </button>
        <button class="btn btn-outline--danger ms-1 rejectBtn" data-id="3"> <i class="fas fa-ban"></i>
            @lang('Reject') </button>
    @endif
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.approveBtn').on('click', function() {
                var modal = $('#approveModal');
                modal.modal('show');
            });

            $('.rejectBtn').on('click', function() {
                var modal = $('#rejectModal');
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
