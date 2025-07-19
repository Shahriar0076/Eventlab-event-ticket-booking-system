<header class="header" id="header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand logo" href="{{ route('home') }}"><img src="{{ siteLogo() }}" alt="image"></a>
            <button class="navbar-toggler header-button" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span id="hiddenNav"><i class="las la-bars"></i></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu ms-auto align-items-lg-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="icon"><i class="fa fa-user"></i></span> {{ auth()->user()->fullname }}</a>
                        <ul class="dropdown-menu">

                            <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link" href="{{ route('user.liked.events') }}"> @lang('Liked Events') </a>

                            <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link" href="{{ route('user.following.organizers') }}"> @lang('Following Organizers')</a>

                            <li class="dropdown-menu__list">
                                <a class="dropdown-item dropdown-menu__link" href="{{ route('user.profile.setting') }}">@lang('My Profile')</a>
                            </li>

                            <li class="dropdown-menu__list">
                                <a class="dropdown-item dropdown-menu__link" href="{{ route('user.change.password') }}">@lang('Change Password')</a>
                            </li>

                            <li class="dropdown-menu__list">
                                <a class="dropdown-item dropdown-menu__link" href="{{ route('user.twofactor') }}">@lang('2FA Security')</a>
                            </li>

                            <li class="dropdown-menu__list">
                                <a class="dropdown-item dropdown-menu__link" href="{{ route('user.logout') }}">@lang('Logout')</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
