<div class="px-4 pb-2 pt-4">
    <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">Navigation</p>
</div>

<a href="{{ route('emp.dashboard') }}"
    class="nav-item {{ request()->routeIs('emp.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-chart-line text-lg w-5"></i>
    <span class="font-medium">Dashboard</span>
</a>

<a href="{{ route('emp.tasks.index') }}"
    class="nav-item {{ request()->routeIs('emp.tasks.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-list-check text-lg w-5"></i>
    <span class="font-medium">My Task</span>
</a>

<a href="{{ route('emp.tickets.index') }}"
    class="nav-item {{ request()->routeIs('emp.tickets.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-ticket text-lg w-5"></i>
    <span class="font-medium">My Tickets</span>
</a>

<a href="{{ route('emp.ss.index') }}"
    class="nav-item {{ request()->routeIs('emp.ss.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-headset text-lg w-5"></i>
    <span class="font-medium">Support Services</span>
</a>

<a href="{{ route('emp.calendar.index') }}"
    class="nav-item {{ request()->routeIs('emp.calendar.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-calendar-days text-lg w-5"></i>
    <span class="font-medium">Calendar</span>
</a>

<a href="{{ route('emp.atps.index') }}"
    class="nav-item {{ request()->routeIs('emp.atps.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-building-columns text-lg w-5"></i>
    <span class="font-medium">Training Providers</span>
</a>


<a href="{{ route('emp.notifications.index') }}"
    class="nav-item {{ request()->routeIs('emp.notifications.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-bell text-lg w-5"></i>
    <span class="font-medium">Notifications</span>
</a>

<a href="{{ route('emp.messages.index') }}"
    class="nav-item {{ request()->routeIs('emp.messages.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-message text-lg w-5"></i>
    <span class="font-medium">Messages</span>
</a>
