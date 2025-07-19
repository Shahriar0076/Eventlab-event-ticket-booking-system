<div class="dashboard-header">
    <div class="dashboard-header__inner flex-between">
        <div class="dashboard-header__left">
            <div class="dashboard-body__bar d-lg-none d-block">
                <span class="dashboard-body__bar-icon"><i class="las la-bars"></i></span>
            </div>
            <div class="header-search-inner">
                <div class="header-search-icon d-md-none">
                    <i class="las la-search"></i>
                </div>
                <form action="{{ route('organizer.event.index') }}" autocomplete="off" class="search-box-wrapper">
                    <div class="search-box">
                        <input type="text" class="form--control" name="search" value="{{ request()->search }}" placeholder="@lang('Type your event')...">
                        <button type="submit" class="search-box__button"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <div class="dashboard-header__right flex-align">
            <div class="user-info">
                <div class="user-info__right">
                    <div class="user-info__button">
                        <div class="user-info__thumb">
                            <img src="{{ getImage(getFilePath('organizerProfile') . '/' . authOrganizer()->profile_image, getFileSize('organizerCover'), $avatar = true) }}" alt="image">
                        </div>
                        <div class="user-info__profile">
                            <p class="user-info__name"> {{ @authOrganizer()->fullname }} </p>
                        </div>
                    </div>
                </div>
                <ul class="user-info-dropdown">
                    <li class="user-info-dropdown__item">
                        <a class="user-info-dropdown__link" href="{{ route('organizer.profile.setting') }}">
                            <span class="icon"><i class="las la-user-circle"></i></span>
                            <span class="text"> @lang('My Profile') </span>
                        </a>
                    </li>
                    <li class="user-info-dropdown__item">
                        <a class="user-info-dropdown__link" href="{{ route('organizer.change.password') }}">
                            <span class="icon"><i class="las la-key"></i></span>
                            <span class="text"> @lang('Change Password') </span>
                        </a>
                    </li>
                    <li class="user-info-dropdown__item">
                        <a class="user-info-dropdown__link" href="{{ route('organizer.twofactor') }}">
                            <span class="icon"><i class="las la-lock-open"></i></span>
                            <span class="text"> @lang('2FA Security') </span>
                        </a>
                    </li>
                    <li class="user-info-dropdown__item">
                        <a class="user-info-dropdown__link" href="{{ route('organizer.logout') }}">
                            <span class="icon"><i class="las la-sign-out-alt"></i></span>
                            <span class="text"> @lang('Logout') </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
