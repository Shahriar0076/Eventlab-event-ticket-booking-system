<div class="col-xl-3 col-lg-4 col-sm-6 col-xsm-6">
    <div class="organizer-item">
        <a href="{{ route('organizer.details', $organizer->slug) }}" class="organizer-item__thumb">
            <img src="{{ getImage(getFilePath('organizerProfile') . '/' . @$organizer->profile_image, getFileSize('organizerProfile'), $avatar = true) }}"
                alt="image" class="fit-image">
        </a>

        <div class="organizer-ite__content">
            <h6 class="organizer-item__title"> <a href="{{ route('organizer.details', $organizer->slug) }}">
                    {{ __($organizer->organization_name) }} </a> </h6>
            <p class="organizer-follower"> {{ $organizer->followers_count }} @lang('followers') </p>
            @auth
                @if (auth()->user()->isFollowing($organizer))
                    <a href="{{ route('user.unfollow.organizer', $organizer->id) }}" class="organizer-button">
                        @lang('Unfollow')
                    </a>
                @else
                    <a href="{{ route('user.follow.organizer', $organizer->id) }}" class="organizer-button">
                        @lang('Follow')
                    </a>
                @endif
            @endauth
        </div>
    </div>
</div>
