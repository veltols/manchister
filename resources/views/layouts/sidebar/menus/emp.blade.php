
<a href="{{ route('emp.dashboard') }}"
    class="nav-item {{ request()->routeIs('emp.dashboard') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-chart-line text-2xl"></i>
    <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Dashboard</span>
</a>

<a href="{{ route('emp.tasks.index') }}"
    class="nav-item {{ request()->routeIs('emp.tasks.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-list-check text-2xl"></i>
    <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">My Tasks</span>
</a>


<a href="{{ route('emp.tickets.index') }}"
    class="nav-item {{ request()->routeIs('emp.tickets.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-headset text-2xl"></i>
    <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Support</span>
</a>

<a href="{{ route('emp.calendar.index') }}"
    class="nav-item {{ request()->routeIs('emp.calendar.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-calendar-days text-2xl"></i>
    <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Calendar</span>
</a>

<a href="{{ route('emp.atps.index') }}"
    class="nav-item {{ request()->routeIs('emp.atps.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-building-columns text-2xl"></i>
    <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Providers</span>
</a>



