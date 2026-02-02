<!-- Employee Self Service -->
<div class="px-4 pb-2 pt-4">
    <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">Self Service</p>
</div>
<a href="{{ route('emp.dashboard') }}"
    class="nav-item {{ request()->routeIs('emp.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-chart-line text-lg w-5"></i>
    <span class="font-medium">Dashboard</span>
</a>
<a href="{{ route('emp.tickets.index') }}"
    class="nav-item {{ request()->routeIs('emp.tickets.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-ticket text-lg w-5"></i>
    <span class="font-medium">IT Tickets</span>
</a>
<a href="{{ route('emp.requests.index') }}"
    class="nav-item {{ request()->routeIs('emp.requests.*') || request()->routeIs('emp.leaves.*') || request()->routeIs('emp.permissions.*') || request()->routeIs('emp.attendance.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-hand-sparkles text-lg w-5"></i>
    <span class="font-medium">Request Center</span>
</a>
<a href="{{ route('emp.documents.index') }}"
    class="nav-item {{ request()->routeIs('emp.documents.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-file-lines text-lg w-5"></i>
    <span class="font-medium">Company Docs</span>
</a>
<a href="{{ route('emp.performance.index') }}"
    class="nav-item {{ request()->routeIs('emp.performance.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-star text-lg w-5"></i>
    <span class="font-medium">My Performance</span>
</a>
<a href="{{ route('emp.exit_interview.index') }}"
    class="nav-item {{ request()->routeIs('emp.exit_interview.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-door-open text-lg w-5"></i>
    <span class="font-medium">Exit Interview</span>
</a>
<a href="{{ route('emp.tasks.index') }}"
    class="nav-item {{ request()->routeIs('emp.tasks.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-list-check text-lg w-5"></i>
    <span class="font-medium">My Tasks</span>
</a>
<a href="{{ route('emp.groups.index') }}"
    class="nav-item {{ request()->routeIs('emp.groups.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-people-group text-lg w-5"></i>
    <span class="font-medium">Committees</span>
</a>
<a href="{{ route('emp.ext.atps.index') }}"
    class="nav-item {{ request()->routeIs('emp.ext.atps.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-certificate text-lg w-5"></i>
    <span class="font-medium">Training Partners</span>
</a>

<!-- Strategy -->
<div class="px-4 pb-2 pt-4">
    <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">Strategy</p>
</div>
<a href="{{ route('emp.ext.strategies.index') }}"
    class="nav-item {{ request()->routeIs('emp.ext.strategies.index') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-chess-knight text-lg w-5"></i>
    <span class="font-medium">Strategic Plans</span>
</a>
<a href="{{ route('emp.ext.strategies.projects.index') }}"
    class="nav-item {{ request()->routeIs('emp.ext.strategies.projects.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-briefcase text-lg w-5"></i>
    <span class="font-medium">Projects</span>
</a>
<a href="{{ route('emp.ext.strategies.self_studies.index') }}"
    class="nav-item {{ request()->routeIs('emp.ext.strategies.self_studies.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-book-open text-lg w-5"></i>
    <span class="font-medium">Self Studies</span>
</a>
<a href="{{ route('emp.calendar.index') }}"
    class="nav-item {{ request()->routeIs('emp.calendar.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-calendar-days text-lg w-5"></i>
    <span class="font-medium">Calendar</span>
</a>
<a href="{{ route('emp.messages.index') }}"
    class="nav-item {{ request()->routeIs('emp.messages.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-envelope text-lg w-5"></i>
    <span class="font-medium">Messages</span>
</a>
<a href="{{ route('emp.feedback.index') }}"
    class="nav-item {{ request()->routeIs('emp.feedback.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
    <i class="fa-solid fa-comment-dots text-lg w-5"></i>
    <span class="font-medium">Portal Feedback</span>
</a>
