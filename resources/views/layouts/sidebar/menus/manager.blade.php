@php
    $isAdmin = in_array($user->user_type, ['root', 'sys_admin']);
    $isHR = in_array($user->user_type, ['hr', 'admin_hr']);
    // Check if user is ONLY HR (not admin), to apply specific legacy layout if needed
    // But request implies "accordingly" meaning MATCHING the legacy structure for HR.
@endphp

<a href="{{ $isAdmin ? route('admin.dashboard') : route('hr.dashboard') }}"
    class="nav-item {{ (request()->routeIs('hr.dashboard') || request()->routeIs('admin.dashboard')) ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white mb-4">
    <i class="fa-solid fa-chart-line text-2xl"></i>
    <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Dashboard</span>
</a>

@if($isHR)
    <!-- Legacy HR Structure Alignment -->

    <a href="{{ route('hr.departments.index') }}"
        class="nav-item {{ request()->routeIs('hr.departments.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-sitemap text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Departments</span>
    </a>
    <a href="{{ route('hr.designations.index') }}"
        class="nav-item {{ request()->routeIs('hr.designations.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-id-badge text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Designations</span>
    </a>
    <a href="{{ route('hr.employees.index') }}"
        class="nav-item {{ request()->routeIs('hr.employees.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-users text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Employees</span>
    </a>

    <a href="{{ route('hr.requests.index') }}"
        class="nav-item {{ request()->routeIs('hr.requests.index') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-hubspot text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Operations Hub</span>
    </a>
    <a href="{{ route('hr.leaves.index') }}"
        class="nav-item {{ request()->routeIs('hr.leaves.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-calendar-check text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Leaves</span>
    </a>
    <a href="{{ route('hr.permissions.index') }}"
        class="nav-item {{ request()->routeIs('hr.permissions.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-person-walking-arrow-loop-left text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Permissions</span>
    </a>
    <a href="{{ route('hr.attendance.index') }}"
        class="nav-item {{ request()->routeIs('hr.attendance.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-clipboard-user text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Attendance</span>
    </a>
    <a href="{{ route('hr.disciplinary.index') }}"
        class="nav-item {{ request()->routeIs('hr.disciplinary.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-gavel text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Disciplinary</span>
    </a>
    <a href="{{ route('hr.performance.index') }}"
        class="nav-item {{ request()->routeIs('hr.performance.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-star text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Performance</span>
    </a>
    <a href="{{ route('hr.exit_interviews.index') }}"
        class="nav-item {{ request()->routeIs('hr.exit_interviews.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-door-open text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Exit Interviews</span>
    </a>

    <!-- HR Documents -->
    <a href="{{ route('hr.documents.index') }}"
        class="nav-item {{ request()->routeIs('hr.documents.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-file text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">HR Documents</span>
    </a>


    <a href="{{ route('hr.groups.index') }}"
        class="nav-item {{ request()->routeIs('hr.groups.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-users text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Groups</span>
    </a>

    <!-- Tasks, Calendar, Chats, Tickets - Assuming these exist or linking to Employee versions if HR uses same -->
    <!-- HR likely uses same tools as Employee for these self-service aspects -->
    <!-- We link to existing routes we know of -->

    <!-- Using employee routes often shared for self-organization -->
    <a href="{{ route('hr.tasks.index') }}"
        class="nav-item {{ request()->routeIs('hr.tasks.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-list-check text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Tasks</span>
    </a>

    <a href="{{ route('hr.calendar.index') }}"
        class="nav-item {{ request()->routeIs('hr.calendar.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-calendar-days text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Calendar</span>
    </a>

    <a href="{{ route('hr.messages.index') }}"
        class="nav-item {{ request()->routeIs('hr.messages.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-envelope text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Chats</span>
    </a>

    <a href="{{ route('hr.tickets.index') }}"
        class="nav-item {{ request()->routeIs('hr.tickets.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-bookmark text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Tickets</span>
    </a>

    <a href="{{ route('hr.notifications.index') }}"
        class="nav-item {{ request()->routeIs('hr.notifications.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-bell text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Notifications</span>
    </a>


@endif


@if($isAdmin)
    <!-- Organization Chart (Departments) -->
    <a href="{{ route('admin.departments.index') }}"
        class="nav-item {{ request()->routeIs('admin.departments.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-sitemap text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Org Chart</span>
    </a>

    <!-- Tickets -->
    <!-- Tickets -->
    <a href="{{ route('admin.tickets.index') }}"
        class="nav-item {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-ticket text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Tickets</span>
    </a>

    <!-- Assets -->
    <a href="{{ route('admin.assets.index') }}"
        class="nav-item {{ request()->routeIs('admin.assets.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-laptop text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Assets</span>
    </a>

    <!-- Users -->
    <a href="{{ route('admin.users.index') }}"
        class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-users-gear text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Users</span>
    </a>

    <!-- Settings -->
    <a href="{{ route('admin.settings.index') }}"
        class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-cog text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Settings</span>
    </a>

    <!-- Feedback -->
    <a href="{{ route('admin.feedback.index') }}"
        class="nav-item {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-comment-dots text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Feedback</span>
    </a>

    <!-- Notifications -->
    <a href="{{ route('admin.notifications.index') }}"
        class="nav-item {{ request()->routeIs('admin.notifications.index') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-bell text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Notifications</span>
    </a>

    <!-- Messages -->
    <a href="{{ route('admin.messages.index') }}"
        class="nav-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-envelope text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Messages</span>
    </a>
@endif

@if(in_array($user->user_type, ['eqa']) && !$isAdmin)
    <a href="{{ route('emp.ext.atps.index') }}"
        class="nav-item {{ request()->routeIs('emp.ext.atps.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-certificate text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Partners</span>
    </a>
    <a href="{{ route('eqa.atps.index') }}"
        class="nav-item {{ request()->routeIs('eqa.atps.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-list-check text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Audits</span>
    </a>
@endif