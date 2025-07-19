@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 ">
        @forelse ($organizers as $organizer)
            <div class="col-xl-4 col-sm-6 col-xsm-6">
                <div class="organizer-item ">
                    <a href="{{ route('organizer.details', $organizer->slug) }}" class="organizer-item__thumb">
                        <img src="{{ getImage(getFilePath('organizerProfile') . '/' . @$organizer->profile_image, getFileSize('organizerProfile'), $avatar = true) }}"
                            alt="image" class="fit-image">
                    </a>
                    <div class="organizer-item__content">
                        <h6 class="organizer-item__title"> <a href="{{ route('organizer.details', $organizer->slug) }}">
                                {{ @$organizer->organization_name }} </a> </h6>
                                <p class="organizer-follower"> {{ $organizer->followers->count() }} @lang('followers') </p>
                                @if (auth()->user()->isFollowing($organizer))
                                    <a href="{{ route('user.unfollow.organizer', $organizer->id) }}" class="organizer-button">
                                        @lang('Unfollow')
                                    </a>
                                @else
                                    <a href="{{ route('user.follow.organizer', $organizer->id) }}" class="organizer-button">
                                        @lang('Follow')
                                    </a>
                                @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col">
                <h4 class="text-center py-5">@lang('You didn\'t follow anyone yet')</h4>
            </div>
        @endforelse
        <div class="col-12">
            {{ paginateLinks($organizers) }}
        </div>
    </div>
@endsection
