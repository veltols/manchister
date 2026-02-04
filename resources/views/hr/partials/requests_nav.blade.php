<div class="premium-card p-2 mb-8 w-fit animate-fade-in max-w-full overflow-x-auto scrollbar-hide">
    <div class="flex flex-nowrap gap-2">
        <a href="{{ route('hr.requests.index') }}"
            class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ request()->routeIs('hr.requests.index') ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }} flex items-center whitespace-nowrap">
            <i class="fa-solid fa-hubspot mr-2"></i>All
        </a>
        <a href="{{ route('hr.leaves.index') }}"
            class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ request()->routeIs('hr.leaves.*') ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }} flex items-center whitespace-nowrap">
            <i class="fa-solid fa-calendar-check mr-2"></i>Leaves
        </a>
        <a href="{{ route('hr.permissions.index') }}"
            class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ request()->routeIs('hr.permissions.*') ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }} flex items-center whitespace-nowrap">
            <i class="fa-solid fa-clock mr-2"></i>Permissions
        </a>
        <a href="{{ route('hr.disciplinary.index') }}"
            class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ request()->routeIs('hr.disciplinary.*') ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }} flex items-center whitespace-nowrap">
            <i class="fa-solid fa-gavel mr-2"></i>Disciplinary
        </a>
        <a href="{{ route('hr.attendance.index') }}"
            class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ request()->routeIs('hr.attendance.*') ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }} flex items-center whitespace-nowrap">
            <i class="fa-solid fa-clipboard-user mr-2"></i>Attendance
        </a>
        <a href="{{ route('hr.exit_interviews.index') }}"
            class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ request()->routeIs('hr.exit_interviews.*') ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }} flex items-center whitespace-nowrap">
            <i class="fa-solid fa-door-open mr-2"></i>Exit Interview
        </a>
        <a href="{{ route('hr.performance.index') }}"
            class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ request()->routeIs('hr.performance.*') ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }} flex items-center whitespace-nowrap">
            <i class="fa-solid fa-star mr-2"></i>Performance
        </a>
        <a href="{{ route('hr.documents.index') }}"
            class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ request()->routeIs('hr.documents.*') ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }} flex items-center whitespace-nowrap">
            <i class="fa-solid fa-file-invoice mr-2"></i>HR Documents
        </a>
        <a href="{{ route('hr.groups.index') }}"
            class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ request()->routeIs('hr.groups.*') ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }} flex items-center whitespace-nowrap">
            <i class="fa-solid fa-users-rectangle mr-2"></i>Teams
        </a>
    </div>
</div>