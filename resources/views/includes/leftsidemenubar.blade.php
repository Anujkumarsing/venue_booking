<!-- ========== Left Sidebar Start ========== -->

<!--Left Sidebar/Menu Start-->
<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <div id="sidebar-menu">
            <ul>
                    <li>
                    <p class="mtbbn">Bookings</p>
                </li>
                <li>
                    <a href="{{ route('user.save.booking') }}" class="waves-effect {{ request()->is('save.booking') ? 'active' : '' }}">
                        <i class="fa fa-plus" aria-hidden="true"></i><span> Add Booking</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.dashboard') }}" class="waves-effect {{ request()->is('manage.booking') ? 'active' : '' }}">
                        <i class="fa fa-check" aria-hidden="true"></i><span> All Bookings </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.calendar.view') }}" class="waves-effect {{ request()->routeIs('') ? 'active' : 'user.calendar.view' }}">
                        <i class="fa fa-check" aria-hidden="true"></i><span> Calendar View </span>
                    </a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

