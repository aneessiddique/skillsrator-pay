@if(in_array('Admin', Auth::user()->roles->pluck('title')->toArray()))
    <!-- admin menu -->
        {{-- <ul class="nav"> --}}
            <li class="nav-item">
                <a href="{{ route("admin.admhome") }}" class="nav-link">
                    <i class="nav-icon fas fa-fw fa-tachometer-alt">

                    </i>
                    {{ trans('global.dashboard') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("admin.gateways.index") }}" class="nav-link {{ request()->is('admin/gateways') || request()->is('admin/gateways/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-unlock-alt nav-icon">

                    </i>
                    {{ trans('cruds.gateway.title') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("admin.apikeys.index") }}" class="nav-link {{ request()->is('admin/apikeys') || request()->is('admin/apikeys/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-unlock-alt nav-icon">

                    </i>
                    {{ trans('cruds.apikey.title') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("account.transactions.index") }}" class="nav-link {{ request()->is('account/transactions') || request()->is('account/transactions/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-unlock-alt nav-icon">

                    </i>
                    {{ trans('cruds.transaction.title') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route("account.transaction.kuickpay_index") }}" class="nav-link {{ request()->is('account/transactions') || request()->is('account/transactions/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-unlock-alt nav-icon">

                    </i>
                    Kuickpay {{ trans('cruds.transaction.title') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("account.transactions.transaction_refund_request_index") }}" class="nav-link {{ request()->is('account/transactions') || request()->is('account/transactions/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-unlock-alt nav-icon">

                    </i>
                    Refund Requests
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("account.transactions.deposit_slip_transaction_index") }}" class="nav-link {{ request()->is('account/transactions') || request()->is('account/transactions/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-unlock-alt nav-icon">

                    </i>
                    Deposit Slips
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("account.depositSlipFields.index") }}" class="nav-link {{ request()->is('account/transactions') || request()->is('account/transactions/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-unlock-alt nav-icon">

                    </i>
                    Deposit Slip Fields
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("account.manual_invoices.index") }}" class="nav-link {{ request()->is('account/transactions') || request()->is('account/transactions/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-unlock-alt nav-icon">

                    </i>
                    Manual Invoices
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("admin.ipnlogs.index") }}" class="nav-link {{ request()->is('admin/ipnlogs') || request()->is('admin/ipnlogs/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-unlock-alt nav-icon">

                    </i>
                    {{ trans('cruds.ipnlog.title') }}
                </a>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link  nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users nav-icon">

                    </i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-unlock-alt nav-icon">

                                </i>
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-briefcase nav-icon">

                                </i>
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-user nav-icon">

                                </i>
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="nav-icon fas fa-fw fa-sign-out-alt">

                    </i>
                    {{ trans('global.logout') }}
                </a>
            </li>
        {{-- </ul> --}}

        @elseif(in_array('Accountant', Auth::user()->roles->pluck('title')->toArray()))
        <!-- account menu -->
        {{-- <ul class="nav"> --}}
            <li class="nav-item">
                <a href="{{ route("account.acchome") }}" class="nav-link">
                    <i class="nav-icon fas fa-fw fa-tachometer-alt">

                    </i>
                    {{ trans('global.dashboard') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("account.transactions.index") }}" class="nav-link {{ request()->is('account/transactions') || request()->is('account/transactions/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-unlock-alt nav-icon">

                    </i>
                    {{ trans('cruds.transaction.title') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route("account.transaction.kuickpay_index") }}" class="nav-link {{ request()->is('account/transactions') || request()->is('account/transactions/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-unlock-alt nav-icon">

                    </i>
                    Kuickpay {{ trans('cruds.transaction.title') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("account.transactions.transaction_refund_request_index") }}" class="nav-link {{ request()->is('account/transactions') || request()->is('account/transactions/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-unlock-alt nav-icon">

                    </i>
                    Refund Requests
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("account.transactions.deposit_slip_transaction_index") }}" class="nav-link {{ request()->is('account/transactions') || request()->is('account/transactions/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-unlock-alt nav-icon">

                    </i>
                    Deposit Slips
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route("account.manual_invoices.index") }}" class="nav-link {{ request()->is('account/transactions') || request()->is('account/transactions/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-unlock-alt nav-icon">

                    </i>
                    Manual Invoices
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="nav-icon fas fa-fw fa-sign-out-alt">

                    </i>
                    {{ trans('global.logout') }}
                </a>
            </li>
        {{-- </ul> --}}
        @endif
<form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>
