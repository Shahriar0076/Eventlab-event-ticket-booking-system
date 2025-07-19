<div class="sidebar-menu">
    <!-- Close Button -->
    <span class="sidebar-menu__close d-lg-none d-inline-flex"><i class="las la-times"></i></span>
    <!-- Sidebar Logo -->
    <div class="sidebar-logo d-lg-none d-blcok">
        <a href="{{ route('home') }}" class="sidebar-logo__link ms-auto">
            <img src="{{ siteLogo() }}" alt="Site Logo">
        </a>
    </div>
    <!-- Sidebar Menu -->
    <ul class="sidebar-menu-list">
        <li class="sidebar-menu-list__item {{ menuActive('user.home') }}">
            <a href="{{ route('user.home') }}" class="sidebar-menu-list__link">
                <span class="icon"><i class="las la-home"></i></span>
                <span class="text">@lang('Dashboard')</span>
            </a>
        </li>
        <li class="sidebar-menu-list__item has-dropdown {{ menuActive('user.event.ticket.*') }}">
            <a href="javascript:void(0)" class="sidebar-menu-list__link ">
                <span class="icon"><i class="las la-file-invoice"></i></span>
                <span class="text">@lang('Manage Tickets')</span>
            </a>
            <div class="sidebar-submenu" @php echo menuActive('user.event.ticket.*',4) @endphp>
                <ul class="sidebar-submenu-list">
                    <li class="sidebar-submenu-list__item {{ menuActive(['user.event.ticket.index', 'user.event.ticket.details']) }}">
                        <a href="{{ route('user.event.ticket.index') }}" class="sidebar-submenu-list__link">
                            <span class="text">@lang('All Tickets')</span>
                        </a>
                    </li>
                    <li class="sidebar-submenu-list__item {{ menuActive('user.event.ticket.active') }}">
                        <a href="{{ route('user.event.ticket.active') }}" class="sidebar-submenu-list__link">
                            <span class="text">@lang('Active Tickets')</span>
                        </a>
                    </li>
                    <li class="sidebar-submenu-list__item {{ menuActive('user.event.ticket.payment.pending') }}">
                        <a href="{{ route('user.event.ticket.payment.pending') }}" class="sidebar-submenu-list__link">
                            <span class="text">@lang('Payment Pending')</span>
                        </a>
                    </li>
                    <li class="sidebar-submenu-list__item {{ menuActive('user.event.ticket.refunded') }}">
                        <a href="{{ route('user.event.ticket.refunded') }}" class="sidebar-submenu-list__link">
                            <span class="text">@lang('Refund Tickets')</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="sidebar-menu-list__item has-dropdown @if (!isset($orderId)) {{ menuActive(['user.deposit.*']) }} @endif">
            <a href="javascript:void(0)" class="sidebar-menu-list__link ">
                <span class="icon"><i class="las la-file-invoice-dollar"></i></span>
                <span class="text">@lang('Deposit')</span>
            </a>
            <div class="sidebar-submenu" @if (!isset($orderId)) @php echo menuActive(['user.deposit.*'],4) @endphp @endif>
                <ul class="sidebar-submenu-list">
                    <li class="sidebar-submenu-list__item {{ menuActive('user.deposit.index') }}">
                        <a href="{{ route('user.deposit.index') }}" class="sidebar-submenu-list__link">
                            <span class="text">@lang('Deposit Now')</span>
                        </a>
                    </li>
                    <li class="sidebar-submenu-list__item {{ menuActive('user.deposit.history') }}">
                        <a href="{{ route('user.deposit.history') }}" class="sidebar-submenu-list__link">
                            <span class="text">@lang('Deposit History')</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="sidebar-menu-list__item has-dropdown {{ menuActive(['user.withdraw*']) }}">
            <a href="javascript:void(0)" class="sidebar-menu-list__link ">
                <span class="icon"><i class="menu-icon la la-bank"></i></span>
                <span class="text">@lang('Withdraw')</span>
            </a>
            <div class="sidebar-submenu" @php echo menuActive(['user.withdraw*'],4) @endphp>
                <ul class="sidebar-submenu-list">
                    <li class="sidebar-submenu-list__item {{ menuActive('user.withdraw') }}">
                        <a href="{{ route('user.withdraw') }}" class="sidebar-submenu-list__link">
                            <span class="text">@lang('Withdraw Now')</span>
                        </a>
                    </li>
                    <li class="sidebar-submenu-list__item {{ menuActive('user.withdraw.history') }}">
                        <a href="{{ route('user.withdraw.history') }}" class="sidebar-submenu-list__link">
                            <span class="text">@lang('Withdraw History')</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="sidebar-menu-list__item {{ menuActive('user.transactions') }}">
            <a href="{{ route('user.transactions') }}" class="sidebar-menu-list__link">
                <span class="icon"><i class="las la-list"></i></span>
                <span class="text">@lang('Transactions')</span>
            </a>
        </li>

        <li class="sidebar-menu-list__item has-dropdown {{ menuActive(['ticket.*']) }}">
            <a href="javascript:void(0)" class="sidebar-menu-list__link ">
                <span class="icon"><i class="las la-phone"></i></span>
                <span class="text">@lang('Support')</span>
            </a>
            <div class="sidebar-submenu" @php echo menuActive(['ticket.*'],4) @endphp>
                <ul class="sidebar-submenu-list">
                    <li class="sidebar-submenu-list__item {{ menuActive('ticket.open') }}">
                        <a href="{{ route('ticket.open') }}" class="sidebar-submenu-list__link">
                            <span class="text">@lang('Create Ticket')</span>
                        </a>
                    </li>
                    <li class="sidebar-submenu-list__item {{ menuActive('ticket.index') }}">
                        <a href="{{ route('ticket.index') }}" class="sidebar-submenu-list__link">
                            <span class="text">@lang('All Tickets')</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="sidebar-menu-list__item">
            <a href="{{ route('user.logout') }}" class="sidebar-menu-list__link">
                <span class="icon"><i class="las la-sign-out-alt"></i></span>
                <span class="text">@lang('Log Out')</span>
            </a>
        </li>
    </ul>
</div>
