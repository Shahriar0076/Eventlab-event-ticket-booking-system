<header class="header" id="header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand logo" href="{{ route('home') }}"><img src="{{ siteLogo() }}" alt="image"></a>
            <button class="navbar-toggler header-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span id="hiddenNav"><i class="las la-bars"></i></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('organizer.home') }}"
                            href="{{ route('organizer.home') }}">@lang('Dashboard') </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link {{ menuActive('organizer.event.*') }}" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false"> @lang('Events') </a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link"
                                    href="{{ route('organizer.event.overview') }}"> @lang('Add Event') </a>
                            <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link"
                                    href="{{ route('organizer.event.index') }}"> @lang('View Events') </a>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link {{ menuActive(['organizer.withdraw', 'organizer.withdraw.history']) }}"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @lang('Withdraw') </a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link"
                                    href="{{ route('organizer.withdraw') }}"> @lang('Withdraw Now') </a>
                            <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link"
                                    href="{{ route('organizer.withdraw.history') }}"> @lang('Withdraw History') </a>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('organizer.transactions') }}"
                            href="{{ route('organizer.transactions') }}">@lang('Transactions') </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link {{ menuActive(['organizer.change.password', 'organizer.profile.setting', 'organizer.twofactor', 'organizer.logout']) }}"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ authOrganizer()->fullname }} </a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link"
                                    href="{{ route('organizer.change.password') }}"> @lang('Change Password') </a>
                            <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link"
                                    href="{{ route('organizer.profile.setting') }}"> @lang('Profile Setting') </a>
                            <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link"
                                    href="{{ route('organizer.twofactor') }}"> @lang('2FA Security') </a>
                            <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link"
                                    href="{{ route('organizer.ticket.index') }}"> @lang('Support Tickets') </a>
                            <li class="dropdown-menu__list"><a class="dropdown-item dropdown-menu__link"
                                    href="{{ route('organizer.logout') }}"> @lang('Logout') </a>
                        </ul>
                    </li>

                    @if (gs('multi_language'))
                        @php
                            $language = App\Models\Language::all();
                        @endphp
                        <li class="nav-item">
                            <select class="langSel form-control form-select">
                                <option value="">@lang('Select One')</option>
                                @foreach ($language as $item)
                                    <option value="{{ $item->code }}"
                                        @if (session('lang') == $item->code) selected @endif>{{ __($item->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</header>
