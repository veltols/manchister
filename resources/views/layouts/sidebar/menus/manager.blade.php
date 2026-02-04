@php
    $isAdmin = in_array($user->user_type, ['root', 'sys_admin']);
    $isHR = in_array($user->user_type, ['hr', 'admin_hr']);
    // Check if user is ONLY HR (not admin), to apply specific legacy layout if needed
    // But request implies "accordingly" meaning MATCHING the legacy structure for HR.
@endphp

<a href="{{ $isAdmin ? route('admin.dashboard') : route('hr.dashboard') }}"
    class="nav-item {{ (request()->routeIs('hr.dashboard') || request()->routeIs('admin.dashboard')) ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white mb-4">
    <i class="fa-solid fa-chart-line text-lg w-5"></i>
    <span class="font-medium">Dashboard</span>
</a>

@if($isHR)
    <!-- Legacy HR Structure Alignment -->

    <!-- Organization Structure -->
    <div class="px-4 pb-2 pt-4">
        <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">Organization</p>
    </div>
    <a href="{{ route('hr.departments.index') }}"
        class="nav-item {{ request()->routeIs('hr.departments.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-sitemap text-lg w-5"></i>
        <span class="font-medium">Departments</span>
    </a>
    <a href="{{ route('hr.designations.index') }}"
        class="nav-item {{ request()->routeIs('hr.designations.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-id-badge text-lg w-5"></i>
        <span class="font-medium">Designations</span>
    </a>
    <a href="{{ route('hr.employees.index') }}"
        class="nav-item {{ request()->routeIs('hr.employees.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-users text-lg w-5"></i>
        <span class="font-medium">Employees</span>
    </a>

    <!-- Requests & Operations -->
    <div class="px-4 pb-2 pt-4">
        <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">Requests & Operations</p>
    </div>
    <a href="{{ route('hr.requests.index') }}"
        class="nav-item {{ request()->routeIs('hr.requests.index') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-hubspot text-lg w-5"></i>
        <span class="font-medium">Operations Hub</span>
    </a>
    <a href="{{ route('hr.leaves.index') }}"
        class="nav-item {{ request()->routeIs('hr.leaves.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-calendar-check text-lg w-5"></i>
        <span class="font-medium">Leaves</span>
    </a>
    <a href="{{ route('hr.permissions.index') }}"
        class="nav-item {{ request()->routeIs('hr.permissions.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-person-walking-arrow-loop-left text-lg w-5"></i>
        <span class="font-medium">Permissions</span>
    </a>
    <a href="{{ route('hr.attendance.index') }}"
        class="nav-item {{ request()->routeIs('hr.attendance.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-clipboard-user text-lg w-5"></i>
        <span class="font-medium">Attendance</span>
    </a>
    <a href="{{ route('hr.disciplinary.index') }}"
        class="nav-item {{ request()->routeIs('hr.disciplinary.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-gavel text-lg w-5"></i>
        <span class="font-medium">Disciplinary</span>
    </a>
    <a href="{{ route('hr.performance.index') }}"
        class="nav-item {{ request()->routeIs('hr.performance.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-star text-lg w-5"></i>
        <span class="font-medium">Performance</span>
    </a>
    <a href="{{ route('hr.exit_interviews.index') }}"
        class="nav-item {{ request()->routeIs('hr.exit_interviews.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-door-open text-lg w-5"></i>
        <span class="font-medium">Exit Interviews</span>
    </a>

    <!-- HR Documents -->
    <a href="{{ route('hr.documents.index') }}"
        class="nav-item {{ request()->routeIs('hr.documents.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-file text-lg w-5"></i>
        <span class="font-medium">HR Documents</span>
    </a>

    <!-- Apps Section (Legacy "Apps" Menu Items) -->
    <div class="px-4 pb-2 pt-4">
        <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">Apps</p>
    </div>

    <a href="{{ route('hr.groups.index') }}"
        class="nav-item {{ request()->routeIs('hr.groups.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-users text-lg w-5"></i>
        <span class="font-medium">Groups & Committees</span>
    </a>

    <!-- Tasks, Calendar, Chats, Tickets - Assuming these exist or linking to Employee versions if HR uses same -->
    <!-- HR likely uses same tools as Employee for these self-service aspects -->
    <!-- We link to existing routes we know of -->

    <!-- Using employee routes often shared for self-organization -->
    <a href="{{ route('hr.tasks.index') }}"
        class="nav-item {{ request()->routeIs('hr.tasks.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-list-check text-lg w-5"></i>
        <span class="font-medium">Tasks</span>
    </a>

    <a href="{{ route('hr.calendar.index') }}"
        class="nav-item {{ request()->routeIs('hr.calendar.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-calendar-days text-lg w-5"></i>
        <span class="font-medium">Calendar</span>
    </a>

    <a href="{{ route('hr.messages.index') }}"
        class="nav-item {{ request()->routeIs('hr.messages.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-envelope text-lg w-5"></i>
        <span class="font-medium">Chats</span>
    </a>

    <a href="{{ route('hr.tickets.index') }}"
        class="nav-item {{ request()->routeIs('hr.tickets.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-bookmark text-lg w-5"></i>
        <span class="font-medium">Tickets</span>
    </a>


@endif


@if($isAdmin)
    <!-- Organization Chart (Departments) -->
    <a href="{{ route('admin.departments.index') }}"
        class="nav-item {{ request()->routeIs('admin.departments.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-sitemap text-lg w-5"></i>
        <span class="font-medium">Organization Chart</span>
    </a>

    <!-- Tickets -->
    <!-- Tickets -->
    <a href="{{ route('admin.tickets.index') }}"
        class="nav-item {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-ticket text-lg w-5"></i>
        <span class="font-medium">Tickets</span>
    </a>

    <!-- Assets -->
    <a href="{{ route('admin.assets.index') }}"
        class="nav-item {{ request()->routeIs('admin.assets.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-laptop text-lg w-5"></i>
        <span class="font-medium">Assets</span>
    </a>

    <!-- Users -->
    <a href="{{ route('admin.users.index') }}"
        class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-users-gear text-lg w-5"></i>
        <span class="font-medium">Users</span>
    </a>

    <!-- Settings -->
    <a href="{{ route('admin.settings.index') }}"
        class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-cog text-lg w-5"></i>
        <span class="font-medium">Settings</span>
    </a>

    <!-- Feedback -->
    <a href="{{ route('admin.feedback.index') }}"
        class="nav-item {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-comment-dots text-lg w-5"></i>
        <span class="font-medium">Feedback</span>
    </a>

    <!-- Notifications -->
    <a href="{{ route('admin.notifications') }}"
        class="nav-item {{ request()->routeIs('admin.notifications') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-bell text-lg w-5"></i>
        <span class="font-medium">Notifications</span>
    </a>

    <!-- Messages -->
    <a href="{{ route('admin.messages.index') }}"
        class="nav-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-envelope text-lg w-5"></i>
        <span class="font-medium">Messages</span>
    </a>
@endif

<!-- EQA Specifics (kept separate if EQA exists and is NOT admin/root, or if we want to preserve it for EQA role specifically) -->
@if(in_array($user->user_type, ['eqa']) && !$isAdmin)
    <div class="px-4 pb-2 pt-4">
        <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">Training & Quality</p>
    </div>
    <a href="{{ route('emp.ext.atps.index') }}"
        class="nav-item {{ request()->routeIs('emp.ext.atps.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-certificate text-lg w-5"></i>
        <span class="font-medium">Training Partners</span>
    </a>
    <a href="{{ route('eqa.atps.index') }}"
        class="nav-item {{ request()->routeIs('eqa.atps.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-list-check text-lg w-5"></i>
        <span class="font-medium">EQA & Audits</span>
    </a>
@endif