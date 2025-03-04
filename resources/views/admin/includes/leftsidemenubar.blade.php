<!-- ========== Left Sidebar Start ========== -->

<!--Left Sidebar/Menu Start-->
<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <div id="sidebar-menu">
            <ul>
                <li>
                    <p>Settings</p>
                </li>
                <li>
                    <a href="{{ route('manage.users') }}" class="waves-effect {{ request()->routeIs('manage.users', 'save.users') ? 'active' : '' }}">
                        <i class="fa fa-users ri" aria-hidden="true"></i><span> Manage Users </span>
                    </a>
                </li>
              @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->user_type_id == 1)
                    <li>
                     <a href="{{ route('manage.sub.admin') }}"
                       class="waves-effect {{ request()->routeIs('manage.sub.admin', 'save.sub.admin') ? 'active' : '' }}">
                        <i class="fa fa-cog" aria-hidden="true"></i><span> Manage Sub Admin </span>
                         </a>
                           </li>
                            @endif
                <li>
                    <a href="{{ route('manage.calendar') }}" class="waves-effect {{ request()->routeIs('manage.calendar', 'save.calendar') ? 'active' : '' }}">
                        <i class="fa fa-calendar ri" aria-hidden="true"></i><span> Manage Calendar</span>
                    </a>
                </li>
                <li>
                    <p class="mtbbn">Bookings</p>
                </li>
                <li>
                    <a href="{{ route('save.booking') }}" class="waves-effect {{ request()->routeIs('save.booking') ? 'active' : '' }}">
                        <i class="fa fa-plus" aria-hidden="true"></i><span> Add Booking</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('awaiting.approval') }}" class="waves-effect {{ request()->routeIs('awaiting.approval', 'awaiting.view.booking') ? 'active' : '' }}">
                        <i class="fa fa-spinner" aria-hidden="true"></i><span> Awaiting Approval</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('approved.booking') }}" class="waves-effect {{ request()->routeIs('approved.booking', 'approved.view.booking') ? 'active' : '' }}">
                        <i class="fa fa-clock-o" aria-hidden="true"></i><span> Approved Bookings </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('cancelled.booking') }}" class="waves-effect {{ request()->routeIs('cancelled.booking', 'cancelled.view.booking') ? 'active' : '' }}">
                        <i class="fa fa-ban" aria-hidden="true"></i><span> Cancelled Bookings</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('manage.booking') }}" class="waves-effect {{ request()->routeIs('manage.booking', 'view.booking') ? 'active' : '' }}">
                        <i class="fa fa-check" aria-hidden="true"></i><span> All Bookings </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('booking.report') }}" class="waves-effect {{ request()->routeIs('booking.report') ? 'active' : '' }}">
                        <i class="fa fa-file-text ri" aria-hidden="true"></i><span> Booking Report </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('calendar_view') }}" class="waves-effect {{ request()->routeIs('calendar_view', 'calendar.view.booking') ? 'active' : '' }}">
                        <i class="fa fa-calendar ri" aria-hidden="true"></i><span> Calendar View </span>
                    </a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!-- Left Sidebar/Menu End -->
