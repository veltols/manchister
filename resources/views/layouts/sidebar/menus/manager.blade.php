@php
    $isAdmin = in_array($user->user_type, ['root', 'sys_admin']);
    $isHR = in_array($user->user_type, ['hr', 'admin_hr']);
    // Check if user is ONLY HR (not admin), to apply specific legacy layout if needed
    // But request implies "accordingly" meaning MATCHING the legacy structure for HR.
@endphp

<a href="{{ $isAdmin ? route('admin.dashboard') : route('hr.dashboard') }}"
    class="nav-item {{ (request()->routeIs('hr.dashboard') || request()->routeIs('admin.dashboard')) ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white mb-4">
    <i class="fa-solid fa-chart-line text-2xl"></i>
    <span class="text-[12px] font-bold uppercase tracking-wider opacity-60">Dashboard</span>
</a>

@if($isHR)
    <!-- Legacy HR Structure Alignment -->

    <a href="{{ route('hr.departments.index') }}"
        class="nav-item {{ request()->routeIs('hr.departments.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-sitemap text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-60 text-center">Organization Chart</span>
    </a>
    <a href="{{ route('hr.employees.index') }}"
        class="nav-item {{ request()->routeIs('hr.employees.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-users text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-60">Employees</span>
    </a>

    <a href="{{ route('hr.requests.index') }}"
        class="nav-item {{ request()->routeIs('hr.requests.index') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-folder-open text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-60">Requests</span>
    </a>

    <a href="{{ route('hr.documents.index') }}"
        class="nav-item {{ request()->routeIs('hr.documents.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-file text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-60">HR Documents</span>
    </a>

    <!-- Apps Drawer Trigger -->
    <div x-data="{ open: false }" @click.away="open = false" class="relative">
        <button @click="open = !open" 
            class="nav-item {{ request()->routeIs('hr.groups.*', 'hr.tasks.*', 'hr.calendar.*', 'hr.tickets.*', 'hr.messages.*') ? 'active' : '' }} w-full flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white transition-all">
            <i class="fa-solid fa-layer-group text-2xl"></i>
            <span class="text-[12px] font-bold uppercase tracking-wider opacity-60">Apps</span>
            <i class="fa-solid fa-chevron-right text-[12px] absolute right-2 opacity-0 group-hover:opacity-50 transition-all" :class="open ? 'rotate-90' : ''"></i>
        </button>

        <!-- Slide-Right Drawer -->
        <div x-show="open" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 -translate-x-4"
            class="fixed left-40 top-0 h-full w-64 bg-white/95 backdrop-blur-xl border-r border-slate-200 shadow-2xl z-50 p-6 flex flex-col gap-2 overflow-y-auto"
            style="display: none;">
            
            <div class="mb-6 pb-4 border-b border-slate-100">
                <h3 class="text-lg font-bold text-premium flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-indigo-500"></i>
                    Apps
                </h3>
                <p class="text-xs text-slate-400 mt-1">Productivity & Collaboration</p>
            </div>

            <a href="{{ route('hr.groups.index') }}" 
               class="group flex items-center gap-3 p-3 rounded-xl hover:bg-indigo-50 transition-all {{ request()->routeIs('hr.groups.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600' }}">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span class="font-semibold text-sm">Groups</span>
            </a>

            <a href="{{ route('hr.tasks.index') }}" 
               class="group flex items-center gap-3 p-3 rounded-xl hover:bg-purple-50 transition-all {{ request()->routeIs('hr.tasks.*') ? 'bg-purple-50 text-purple-700' : 'text-slate-600' }}">
                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <span class="font-semibold text-sm">Tasks</span>
            </a>

            <a href="{{ route('hr.calendar.index') }}" 
               class="group flex items-center gap-3 p-3 rounded-xl hover:bg-blue-50 transition-all {{ request()->routeIs('hr.calendar.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600' }}">
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <span class="font-semibold text-sm">Calendar</span>
            </a>

            <a href="{{ route('hr.tickets.index') }}" 
               class="group flex items-center gap-3 p-3 rounded-xl hover:bg-rose-50 transition-all {{ request()->routeIs('hr.tickets.*') ? 'bg-rose-50 text-rose-700' : 'text-slate-600' }}">
                <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <span class="font-semibold text-sm">Tickets</span>
            </a>

            <a href="{{ route('hr.messages.index') }}" 
               class="group flex items-center gap-3 p-3 rounded-xl hover:bg-emerald-50 transition-all {{ request()->routeIs('hr.messages.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600' }}">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <span class="font-semibold text-sm">Chats</span>
            </a>
            
        </div>
    </div>

@endif


@if($isAdmin)
    <!-- Organization Chart (Departments) -->
    <a href="{{ route('admin.departments.index') }}"
        class="nav-item {{ request()->routeIs('admin.departments.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-sitemap text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-60 text-center">Organization Chart</span>
    </a>

    <!-- Tickets -->
    <!-- Tickets -->
    <a href="{{ route('admin.tickets.index') }}"
        class="nav-item {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-ticket text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-60">Tickets</span>
    </a>

    <!-- Assets -->
    <a href="{{ route('admin.assets.index') }}"
        class="nav-item {{ request()->routeIs('admin.assets.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-laptop text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-60">Assets</span>
    </a>

    <!-- Users -->
    <a href="{{ route('admin.users.index') }}"
        class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-users-gear text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-60">Users</span>
    </a>

    <!-- Settings -->
    <a href="{{ route('admin.settings.index') }}"
        class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-cog text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-60">Settings</span>
    </a>

    <!-- Feedback -->
    <a href="{{ route('admin.feedback.index') }}"
        class="nav-item {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-comment-dots text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-60">Feedback</span>
    </a>

@endif

@if(in_array($user->user_type, ['eqa']) && !$isAdmin)
    <a href="{{ route('emp.ext.atps.index') }}"
        class="nav-item {{ request()->routeIs('emp.ext.atps.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-certificate text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-60">Partners</span>
    </a>
    <a href="{{ route('eqa.atps.index') }}"
        class="nav-item {{ request()->routeIs('eqa.atps.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white">
        <i class="fa-solid fa-list-check text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-60">Audits</span>
    </a>
@endif
