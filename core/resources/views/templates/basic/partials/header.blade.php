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
                    <li class="nav-item @auth('organizer') {{ menuActive('organizer.index') }} @endauth">
                        @auth
                            <a class="nav-link" href="{{ route('user.liked.events') }}"><span class="icon"><i
                                        class="far fa-heart"></i></span> @lang('Favorite') </a>
                        @else
                            <a class="nav-link" href="{{ route('organizer.index') }}"><span class="icon">
                                    <i class="las la-user-friends"></i></span>
                                @lang('Organizers')
                            </a>
                        @endauth
                    </li>
                    <li class="nav-item">
                        @if (!(auth()->id() || authOrganizerId()))
                            <a class="nav-link{{ menuActive('user.login') }} " href="{{ route('user.login') }}">
                                <span class="icon"><i class="las la-sign-out-alt"></i></span> @lang('Login')
                            </a>
                        @endif
                        @auth
                            <a class="nav-link{{ menuActive('user.home') }} " href="{{ route('user.home') }}"><span
                                    class="icon"><i class="fa fa-user"></i></span> @lang('Dashboard')</a>
                        @endauth

                        @auth('organizer')
                            <a class="nav-link{{ menuActive('organizer.home') }} "
                                href="{{ route('organizer.home') }}"><span class="icon"><i
                                        class="fa fa-user"></i></span>@lang('Dashboard') </a>
                        @endauth
                    </li>

                    <li>
                        <div class="header-right">
                            @if (gs('multi_language'))
                                @php
                                    $language = App\Models\Language::all();
                                @endphp
                                <div class="custom--dropdown">
                                    @if (session('lang'))
                                        <div class="custom--dropdown__selected dropdown-list__item">
                                            <div class="thumb">
                                                <img class="flag" alt="image"
                                                    src="{{ getImage(getFilePath('language') . '/' . @$language->where('code', session('lang'))->first()->image, getFileSize('language')) }}">
                                            </div>
                                            <span class="text">{{ strtoupper(session('lang')) }}</span>
                                        </div>
                                    @else
                                        @php $default = $language->where('is_default',Status::YES)->first() @endphp
                                        <div class="custom--dropdown__selected dropdown-list__item">
                                            <div class="thumb">
                                                <img class="flag" alt="image"
                                                    src="{{ getImage(getFilePath('language') . '/' . @$default->image, getFileSize('language')) }}">
                                            </div>
                                            <span class="text">{{ strtoupper(@$default->code) }}</span>
                                        </div>
                                    @endif
                                    <ul class="dropdown-list">
                                        @foreach ($language as $item)
                                            <li class="dropdown-list__item langSel"
                                                data-url="{{ route('home') }}/change/{{ $item->code }}">
                                                <div class="thumb">
                                                    <img class="flag" alt="image"
                                                        src="{{ getImage(getFilePath('language') . '/' . $item->image, getFileSize('language')) }}"
                                                        loading="lazy">
                                                </div>
                                                <span class="text">{{ strtoupper($item->code) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <a href="{{ route('event.index') }}" class="btn btn--base">
                                @lang('Events')
                                <span class="btn--icon">
                                    <img src="{{ asset($activeTemplateTrue . 'images/icons/ticket-icon.svg') }}"
                                        alt="ticket-icon">
                                </span>
                            </a>
                        </div>
                    </li>

                </ul>
            </div>
        </nav>
    </div>
</header>
