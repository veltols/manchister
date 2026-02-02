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

    <!-- Organization Chart (Departments) -->
    <a href="{{ route('hr.departments.index') }}"
        class="nav-item {{ request()->routeIs('hr.departments.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-cubes text-lg w-5"></i>
        <span class="font-medium">Organization Chart</span>
    </a>

    <!-- Employees -->
    <a href="{{ route('hr.employees.index') }}"
        class="nav-item {{ request()->routeIs('hr.employees.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-users text-lg w-5"></i>
        <span class="font-medium">Employees</span>
    </a>

    <!-- Requests (Consolidated) -->
    <!-- In Legacy, Requests likely covered Leaves/Permissions. We'll group them visually or keep as list but labeled clearly -->
    <div class="px-4 pb-2 pt-4">
        <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">Requests & Operations</p>
    </div>
    <a href="{{ route('hr.leaves.index') }}"
        class="nav-item {{ request()->routeIs('hr.leaves.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-folder-open text-lg w-5"></i>
        <span class="font-medium">Requests (Leaves)</span>
    </a>
    <a href="{{ route('hr.permissions.index') }}"
        class="nav-item {{ request()->routeIs('hr.permissions.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-clock text-lg w-5"></i>
        <span class="font-medium">Permissions</span>
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
    <a href="{{ route('emp.tasks.index') }}"
        class="nav-item {{ request()->routeIs('emp.tasks.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-list-check text-lg w-5"></i>
        <span class="font-medium">Tasks</span>
    </a>

    <a href="{{ route('emp.calendar.index') }}"
        class="nav-item {{ request()->routeIs('emp.calendar.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-calendar-days text-lg w-5"></i>
        <span class="font-medium">Calendar</span>
    </a>

    <a href="{{ route('emp.messages.index') }}"
        class="nav-item {{ request()->routeIs('emp.messages.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-envelope text-lg w-5"></i>
        <span class="font-medium">Chats</span>
    </a>

    <a href="{{ route('emp.tickets.index') }}"
        class="nav-item {{ request()->routeIs('emp.tickets.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-bookmark text-lg w-5"></i>
        <span class="font-medium">Tickets</span>
    </a>

    <!-- Additional HR Specifics not in top level legacy but important -->
    <div class="px-4 pb-2 pt-4">
        <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">Management</p>
    </div>
    <a href="{{ route('hr.attendance.index') }}"
        class="nav-item {{ request()->routeIs('hr.attendance.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:text-white">
        <i class="fa-solid fa-clipboard-check text-lg w-5"></i>
        <span class="font-medium">Attendance</span>
    </a>
    <a href="{{ route('hr.disciplinary.index') }}"
        class="nav-item {{ request()->routeIs('hr.disciplinary.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:text-white">
        <i class="fa-solid fa-gavel text-lg w-5"></i>
        <span class="font-medium">Disciplinary</span>
    </a>
    <a href="{{ route('hr.performance.index') }}"
        class="nav-item {{ request()->routeIs('hr.performance.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:text-white">
        <i class="fa-solid fa-star text-lg w-5"></i>
        <span class="font-medium">Performance</span>
    </a>
    <a href="{{ route('hr.exit_interviews.index') }}"
        class="nav-item {{ request()->routeIs('hr.exit_interviews.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:text-white">
        <i class="fa-solid fa-door-open text-lg w-5"></i>
        <span class="font-medium">Exit Interviews</span>
    </a>

@endif


<!-- Admin / Root Specifics keep as is -->
@if($isAdmin || in_array($user->user_type, ['eqa']))
    @if(in_array($user->user_type, ['hr', 'admin_hr', 'root', 'sys_admin', 'eqa']))
        <!-- Training & Quality -->
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
@endif

@if(!$isHR && $isAdmin)
    <!-- If Admin but not HR (root/sys_admin usually see everything, but lets keep the blocks) -->
    <!-- Re-including organization/resources for Admins if they are NOT HR (HR sees above) -->

    <!-- Organization -->
    <div class="px-4 pb-2 pt-4">
        <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">Organization</p>
    </div>
    <a href="{{ route('hr.departments.index') }}"
        class="nav-item {{ request()->routeIs('hr.departments.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-building text-lg w-5"></i>
        <span class="font-medium">Departments</span>
    </a>
    <a href="{{ route('hr.groups.index') }}"
        class="nav-item {{ request()->routeIs('hr.groups.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:text-white">
        <i class="fa-solid fa-user-group text-lg w-5"></i>
        <span class="font-medium">Groups</span>
    </a>
    <!-- Resources -->
    <div class="px-4 pb-2 pt-4">
        <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">Resources</p>
    </div>
    <a href="{{ route('hr.assets.index') }}"
        class="nav-item {{ request()->routeIs('hr.assets.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-laptop text-lg w-5"></i>
        <span class="font-medium">Assets</span>
    </a>
@endif

@if($isAdmin)
    <!-- System -->
    <div class="px-4 pb-2 pt-4">
        <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">System</p>
    </div>
    <a href="{{ route('admin.users.index') }}"
        class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:text-white">
        <i class="fa-solid fa-users-gear text-lg w-5"></i>
        <span class="font-medium">Users</span>
    </a>
    <a href="{{ route('admin.settings.index') }}"
        class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:text-white">
        <i class="fa-solid fa-cog text-lg w-5"></i>
        <span class="font-medium">Settings</span>
    </a>
@endif
