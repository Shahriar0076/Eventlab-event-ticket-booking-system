<div class="add-event">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <ul class="page-list">
                <li class="nav-item {{ menuActive('organizer.event.overview', 5) }} active">
                    <a class="nav-link" href="{{ route('organizer.event.overview', @$event->id) }}">
                        @lang('Overview')
                        <span class="nav-item__number">{{ Status::OVERVIEW }}</span>
                    </a>
                </li>
                <li class="nav-item {{ menuActive('organizer.event.info', 5) }} @if (@$event->step >= Status::OVERVIEW) active @endif">
                    <a class="nav-link" href="@if ($event && @$event->step >= 1) {{ route('organizer.event.info', $event->id) }}@else # @endif">
                        @lang('Info')
                        <span class="nav-item__number">{{ Status::INFO }}</span>
                    </a>
                </li>

                <li class="nav-item {{ menuActive('organizer.event.time.slots', 5) }} @if (@$event->step >= Status::INFO) active @endif">
                    <a class="nav-link" href="@if ($event && @$event->step > 1) {{ route('organizer.event.time.slots', $event->id) }}@else # @endif">
                        @lang('Time Slots')
                        <span class="nav-item__number">{{ Status::TIME_SLOTS }}</span>
                    </a>
                </li>
                <li class="nav-item {{ menuActive('organizer.event.gallery', 5) }} @if (@$event->step >= Status::TIME_SLOTS) active @endif">
                    <a class="nav-link" href="@if ($event && @$event->step > 2) {{ route('organizer.event.gallery', $event->id) }}@else # @endif">
                        @lang('Gallery')
                        <span class="nav-item__number">{{ Status::GALLERY }}</span>
                    </a>
                </li>
                <li class="nav-item {{ menuActive('organizer.event.speakers', 5) }} @if (@$event->step >= Status::GALLERY) active @endif">
                    <a class="nav-link" href="@if ($event && @$event->step > 3) {{ route('organizer.event.speakers', $event->id) }}@else # @endif">
                        @lang('Speakers')
                        <span class="nav-item__number">{{ Status::SPEAKERS }}</span>
                    </a>
                </li>
                <li class="nav-item {{ menuActive('organizer.event.publish', 5) }} @if (@$event->step >= Status::SPEAKERS) active @endif">
                    <a class="nav-link" href="@if ($event && @$event->step > 4) {{ route('organizer.event.publish', $event->id) }}@else # @endif">
                        @lang('Publish')
                        <span class="nav-item__number">{{ Status::PUBLISH }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

@push('style')
    <style>
        .spinner-border {
            width: 1rem;
            height: 1rem;
        }
    </style>
@endpush
