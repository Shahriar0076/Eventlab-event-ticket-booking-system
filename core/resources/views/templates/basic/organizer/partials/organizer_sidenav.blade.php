<div class="sidebar-menu flex-between">
    <div class="sidebar-menu__inner">
        <span class="sidebar-menu__close d-lg-none d-block"><i class="fas fa-times"></i></span>
        <div class="sidebar-logo">
            <a href="{{ route('organizer.home') }}" class="sidebar-logo__link"><img src="{{ siteLogo('dark') }}" alt="image"></a>
        </div>
        <ul class="sidebar-menu-list">
            <li class="sidebar-menu-list__item {{ menuActive('organizer.home') }}">
                <a href="{{ route('organizer.home') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="fas fa-qrcode"></i></span>
                    <span class="text">@lang('Dashboard')</span>
                </a>
            </li>

            <li class="sidebar-menu-list__item has-dropdown {{ menuActive('organizer.event.*') }}">
                <a href="javascript:void(0)" class="sidebar-menu-list__link">
                    <span class="icon"><i class="fas fa-calendar-alt"></i></span>
                    <span class="text"> @lang('Events')</span>
                </a>
                <div class="sidebar-submenu" @php echo menuActive('organizer.event.*',4) @endphp>
                    <ul class="sidebar-submenu-list">
                        <li class="sidebar-submenu-list__item {{ menuActive('organizer.event.overview') }}">
                            <a href="{{ route('organizer.event.overview') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('Add Event') </span>
                            </a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ menuActive('organizer.event.index') }}">
                            <a href="{{ route('organizer.event.index') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('All Events')</span>
                            </a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ menuActive('organizer.event.draft') }}">
                            <a href="{{ route('organizer.event.draft') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('Draft Events')</span>
                            </a>
                        </li>
                        @if (gs('event_verification'))
                            <li class="sidebar-submenu-list__item {{ menuActive('organizer.event.approved') }}">
                                <a href="{{ route('organizer.event.approved') }}" class="sidebar-submenu-list__link">
                                    <span class="text">@lang('Approved Events')</span>
                                </a>
                            </li>
                            <li class="sidebar-submenu-list__item {{ menuActive('organizer.event.pending') }}">
                                <a href="{{ route('organizer.event.pending') }}" class="sidebar-submenu-list__link">
                                    <span class="text">@lang('Pending Events')</span>
                                </a>
                            </li>
                            <li class="sidebar-submenu-list__item {{ menuActive('organizer.event.rejected') }}">
                                <a href="{{ route('organizer.event.rejected') }}" class="sidebar-submenu-list__link">
                                    <span class="text">@lang('Rejected Events')</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>

            <li class="sidebar-menu-list__item has-dropdown {{ menuActive(['organizer.withdraw', 'organizer.withdraw.history']) }}">
                <a href="javascript:void(0)" class="sidebar-menu-list__link">
                    <span class="icon"><i class="fas fa-calendar-alt"></i></span>
                    <span class="text"> @lang('Withdraw')</span>
                </a>
                <div class="sidebar-submenu" @php echo menuActive(['organizer.withdraw','organizer.withdraw.history'],4) @endphp>
                    <ul class="sidebar-submenu-list">
                        <li class="sidebar-submenu-list__item {{ menuActive('organizer.withdraw') }}">
                            <a href="{{ route('organizer.withdraw') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('Withdraw Now') </span>
                            </a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ menuActive('organizer.withdraw.history') }}">
                            <a href="{{ route('organizer.withdraw.history') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('Withdraw History')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidebar-menu-list__item">
                <a href="{{ route('organizer.transactions') }}" class="sidebar-menu-list__link {{ menuActive('organizer.transactions') }}">
                    <span class="icon"><i class="fas fa-exchange-alt"></i></span>
                    <span class="text"> @lang('Transaction') </span>
                </a>
            </li>

            <li class="sidebar-menu-list__item has-dropdown {{ menuActive('organizer.ticket.*') }}">
                <a href="javascript:void(0)" class="sidebar-menu-list__link">
                    <span class="icon"><i class="fas fa-ticket-alt"></i></span>
                    <span class="text"> @lang('Support Tickets')</span>
                </a>
                <div class="sidebar-submenu" @php echo menuActive('organizer.ticket.*',4) @endphp>
                    <ul class="sidebar-submenu-list">
                        <li class="sidebar-submenu-list__item {{ menuActive('organizer.ticket.open') }}">
                            <a href="{{ route('organizer.ticket.open') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('Create Ticket') </span>
                            </a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ menuActive('organizer.ticket.index') }}">
                            <a href="{{ route('organizer.ticket.index') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('All Tickets')</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidebar-menu-list__item">
                <a href="{{ route('organizer.logout') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span class="text">@lang('Logout')</span>
                </a>
            </li>
        </ul>
    </div>
</div>
