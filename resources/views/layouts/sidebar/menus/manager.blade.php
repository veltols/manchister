@php
    $isAdmin = in_array($user->user_type, ['root', 'sys_admin']);
    $isHR = in_array($user->user_type, ['hr', 'admin_hr']);
@endphp

<a href="{{ $isAdmin ? route('admin.dashboard') : route('hr.dashboard') }}"
    class="nav-item {{ (request()->routeIs('hr.dashboard') || request()->routeIs('admin.dashboard')) ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
    <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
        <i class="fa-solid fa-chart-line text-base"></i>
    </div>
    <span class="text-base font-semibold">Dashboard</span>
</a>

@if($isHR)

    <a href="{{ route('hr.departments.index') }}"
        class="nav-item {{ request()->routeIs('hr.departments.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-sitemap text-base"></i>
        </div>
        <span class="text-base font-semibold">Org Chart</span>
    </a>

    <a href="{{ route('hr.employees.index') }}"
        class="nav-item {{ request()->routeIs('hr.employees.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-users text-base"></i>
        </div>
        <span class="text-base font-semibold">Employees</span>
    </a>

    <a href="{{ route('hr.requests.index') }}"
        class="nav-item {{ request()->routeIs('hr.requests.index') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-folder-open text-base"></i>
        </div>
        <span class="text-base font-semibold">Requests</span>
    </a>

    {{-- Inbound Correspondence (Liaison Officer only) --}}
    @if(Auth::user()->is_liaison)
    <a href="{{ route('hr.inbound.index') }}"
        class="nav-item {{ request()->routeIs('hr.inbound.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-envelope-open-text text-base"></i>
        </div>
        <span class="text-base font-semibold">Inbound Corr.</span>
    </a>

    @php
        $liaisonOutboundPending = \App\Models\CommunicationRequest::where('is_approved_2', 1)
            ->where('communication_status_id', 3)->count();
    @endphp
    <a href="{{ route('emp.outbound-liaison.index') }}"
        class="nav-item {{ request()->routeIs('emp.outbound-liaison.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1 relative">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-paper-plane text-base"></i>
        </div>
        <span class="text-base font-semibold">Outbound Dispatch</span>
        @if($liaisonOutboundPending > 0)
            <span class="absolute top-1 right-2 bg-indigo-500 text-white text-[9px] font-black rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-sm">{{ $liaisonOutboundPending }}</span>
        @endif
    </a>
    @endif

    <a href="{{ route('hr.documents.index') }}"
        class="nav-item {{ request()->routeIs('hr.documents.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-file text-base"></i>
        </div>
        <span class="text-base font-semibold">HR Documents</span>
    </a>

    {{-- Probation Performance Reviews --}}
    @php
        $hrProbationPending = \App\Models\ProbationReview::where('status', 'pending_manager')->count();
    @endphp
    <a href="{{ route('hr.probation-reviews.index') }}"
        class="nav-item {{ request()->routeIs('hr.probation-reviews.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1 relative">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-clipboard-check text-base"></i>
        </div>
        <span class="text-base font-semibold">Probation Reviews</span>
        @if($hrProbationPending > 0)
            <span class="absolute top-1 right-2 bg-indigo-500 text-white text-[9px] font-black rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-sm">{{ $hrProbationPending }}</span>
        @endif
    </a>

    {{-- Apps Drawer Trigger --}}
    <div x-data="{ open: false }" @click.away="open = false" class="relative">
        <button @click="open = !open"
            class="nav-item {{ request()->routeIs('hr.groups.*', 'hr.tasks.*', 'hr.calendar.*', 'hr.tickets.*', 'hr.messages.*', 'hr.ss.*') ? 'active' : '' }} w-full flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-layer-group text-base"></i>
            </div>
            <span class="text-base font-semibold">Apps</span>
            <i class="fa-solid fa-chevron-right text-[11px] ml-auto transition-transform duration-200"
                :class="open ? 'rotate-90' : ''" style="color:rgba(255,255,255,0.5);"></i>
        </button>

        {{-- Slide-Right Drawer --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 -translate-x-4"
            class="fixed left-64 top-0 h-full w-64 bg-white/95 backdrop-blur-xl border-r border-slate-200 shadow-2xl z-[999] p-6 flex flex-col gap-2 overflow-y-auto"
            style="display: none;">

            {{-- Drawer header --}}
            <div class="mb-5 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center relative overflow-hidden" style="background:linear-gradient(145deg,#004F68,#1a8aaa);
                                    box-shadow:0 6px 16px rgba(0,79,104,0.3),inset 0 1px 0 rgba(255,255,255,0.3);">
                        <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl"
                            style="background:rgba(255,255,255,0.3);"></div>
                        <i class="fa-solid fa-layer-group text-white text-sm relative z-10"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-premium">Apps</h3>
                        <p class="text-[10px] text-slate-400">Productivity &amp; Collaboration</p>
                    </div>
                </div>
            </div>

            {{-- Groups --}}
            <a href="{{ route('hr.groups.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('hr.groups.*') ? 'bg-teal-50' : 'hover:bg-slate-50' }}"
                style="{{ request()->routeIs('hr.groups.*') ? 'box-shadow:0 4px 12px rgba(0,79,104,0.12);' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                    style="background:linear-gradient(145deg,#3b82f6,#2563eb);
                                box-shadow:0 4px 12px rgba(37,99,235,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);">
                    </div>
                    <i class="fa-solid fa-users text-white text-sm relative z-10"></i>
                </div>
                <span
                    class="font-semibold text-base {{ request()->routeIs('hr.groups.*') ? 'text-teal-800' : 'text-slate-700' }}">Groups</span>
            </a>

            {{-- Tasks --}}
            <a href="{{ route('hr.tasks.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('hr.tasks.*') && !request()->routeIs('hr.tasks.pending') ? 'bg-purple-50' : 'hover:bg-slate-50' }}"
                style="{{ request()->routeIs('hr.tasks.*') && !request()->routeIs('hr.tasks.pending') ? 'box-shadow:0 4px 12px rgba(139,92,246,0.12);' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                    style="background:linear-gradient(145deg,#8b5cf6,#7c3aed);
                                box-shadow:0 4px 12px rgba(139,92,246,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);">
                    </div>
                    <i class="fa-solid fa-list-check text-white text-sm relative z-10"></i>
                </div>
                <span
                    class="font-semibold text-base {{ request()->routeIs('hr.tasks.*') && !request()->routeIs('hr.tasks.pending') ? 'text-purple-800' : 'text-slate-700' }}">Tasks</span>
            </a>

            @php
                $hrPendingCount = 0;
                $permPendingCount = 0;
                if (Auth::user() && Auth::user()->employee) {
                    $empId = Auth::user()->employee->employee_id;
                    $hrPendingCount = \App\Models\Task::where('pending_line_manager_id', $empId)->count();
                    $permPendingCount = \App\Models\Permission::where('line_manager_id', $empId)
                        ->whereIn('permission_status_id', [1, 2])->count();
                }
                $totalPending = $hrPendingCount + $permPendingCount;
            @endphp
            @if($totalPending > 0)
                {{-- Pending Approvals --}}
                <a href="{{ route('hr.tasks.pending') }}"
                    class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('hr.tasks.pending') ? 'bg-amber-50' : 'hover:bg-slate-50' }}"
                    style="{{ request()->routeIs('hr.tasks.pending') ? 'box-shadow:0 4px 12px rgba(245,158,11,0.12);' : '' }}">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                        style="background:linear-gradient(145deg,#f59e0b,#d97706);
                                    box-shadow:0 4px 12px rgba(245,158,11,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                        <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);">
                        </div>
                        <i class="fa-solid fa-clock-rotate-left text-white text-sm relative z-10"></i>
                        <span
                            class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center z-20">{{ $totalPending }}</span>
                    </div>
                    <span
                        class="font-semibold text-base {{ request()->routeIs('hr.tasks.pending') ? 'text-amber-800' : 'text-slate-700' }}">Pending
                        Approvals</span>
                </a>
            @endif

            {{-- Calendar --}}
            <a href="{{ route('hr.calendar.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('hr.calendar.*') ? 'bg-sky-50' : 'hover:bg-slate-50' }}"
                style="{{ request()->routeIs('hr.calendar.*') ? 'box-shadow:0 4px 12px rgba(14,165,233,0.12);' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                    style="background:linear-gradient(145deg,#0ea5e9,#0284c7);
                                box-shadow:0 4px 12px rgba(14,165,233,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);">
                    </div>
                    <i class="fa-solid fa-calendar-days text-white text-sm relative z-10"></i>
                </div>
                <span
                    class="font-semibold text-base {{ request()->routeIs('hr.calendar.*') ? 'text-sky-800' : 'text-slate-700' }}">Calendar</span>
            </a>

            {{-- Tickets --}}
            <a href="{{ route('hr.tickets.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('hr.tickets.*') ? 'bg-rose-50' : 'hover:bg-slate-50' }}"
                style="{{ request()->routeIs('hr.tickets.*') ? 'box-shadow:0 4px 12px rgba(244,63,94,0.12);' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                    style="background:linear-gradient(145deg,#f43f5e,#e11d48);
                                box-shadow:0 4px 12px rgba(244,63,94,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);">
                    </div>
                    <i class="fa-solid fa-ticket text-white text-sm relative z-10"></i>
                </div>
                <span
                    class="font-semibold text-base {{ request()->routeIs('hr.tickets.*') ? 'text-rose-800' : 'text-slate-700' }}">Tickets</span>
            </a>

            {{-- Chats --}}
            <a href="{{ route('hr.messages.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('hr.messages.*') ? 'bg-emerald-50' : 'hover:bg-slate-50' }}"
                style="{{ request()->routeIs('hr.messages.*') ? 'box-shadow:0 4px 12px rgba(16,185,129,0.12);' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                    style="background:linear-gradient(145deg,#10b981,#059669);
                                box-shadow:0 4px 12px rgba(16,185,129,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);">
                    </div>
                    <i class="fa-solid fa-comments text-white text-sm relative z-10"></i>
                </div>
                <span
                    class="font-semibold text-base {{ request()->routeIs('hr.messages.*') ? 'text-emerald-800' : 'text-slate-700' }}">Chats</span>
            </a>
            {{-- Service Requests --}}
            @php
                $hrSsPendingCount = \App\Models\SupportService::where('status_id', 1)
                    ->when(!in_array(auth()->user()->user_type, ['root', 'sys_admin', 'admin_hr']), function($q) {
                        $empId = auth()->user()->employee?->employee_id ?? 0;
                        $q->where('sent_to_id', $empId);
                    })->count();
            @endphp
            <a href="{{ route('hr.ss.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('hr.ss.*') ? 'bg-indigo-50' : 'hover:bg-slate-50' }}"
                style="{{ request()->routeIs('hr.ss.*') ? 'box-shadow:0 4px 12px rgba(99,102,241,0.12);' : '' }}">
                <div class="relative flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center overflow-hidden"
                        style="background:linear-gradient(145deg,#6366f1,#4f46e5);
                                    box-shadow:0 4px 12px rgba(99,102,241,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                        <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);"></div>
                        <i class="fa-solid fa-screwdriver-wrench text-white text-sm relative z-10"></i>
                    </div>
                    @if($hrSsPendingCount > 0)
                        <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center z-20 shadow-sm">{{ $hrSsPendingCount }}</span>
                    @endif
                </div>
                <span class="font-semibold text-base {{ request()->routeIs('hr.ss.*') ? 'text-indigo-800' : 'text-slate-700' }}">Service Requests</span>
                @if($hrSsPendingCount > 0 && !request()->routeIs('hr.ss.*'))
                    <span class="ml-auto bg-indigo-100 text-indigo-700 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $hrSsPendingCount }}</span>
                @endif
            </a>

        </div>
    </div>

    {{-- GM Reviews + GM Leave Queue — shown if this HR user is also designated as GM --}}
    @if(Auth::user() && Auth::user()->is_gm)
        @php
            $hrGmProbationPending = \App\Models\ProbationReview::where('gm_id', Auth::user()->user_id)
                ->where('status', 'reviewed')->count();
            $hrGmLeavesPending = \App\Models\HrLeave::where('leave_status_id', \App\Models\HrLeave::STATUS_PENDING_GM)->count();
        @endphp
        <a href="{{ route('admin.probation-reviews.index') }}"
            class="nav-item {{ request()->routeIs('admin.probation-reviews.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1 relative"
            style="background: rgba(245,158,11,0.12); color: #92400e;">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                style="background: rgba(245,158,11,0.25); color: #d97706;">
                <i class="fa-solid fa-crown text-base"></i>
            </div>
            <span class="text-base font-semibold">GM Reviews</span>
            @if($hrGmProbationPending > 0)
                <span class="absolute top-1 right-2 bg-amber-500 text-white text-[9px] font-black rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-sm">{{ $hrGmProbationPending }}</span>
            @endif
        </a>
        <a href="{{ route('admin.leaves.gm') }}"
            class="nav-item {{ request()->routeIs('admin.leaves.gm') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1 relative"
            style="background: rgba(245,158,11,0.08); color: #92400e;">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                style="background: rgba(245,158,11,0.2); color: #d97706;">
                <i class="fa-solid fa-calendar-check text-base"></i>
            </div>
            <span class="text-base font-semibold">GM Leaves</span>
            @if($hrGmLeavesPending > 0)
                <span class="absolute top-1 right-2 bg-amber-500 text-white text-[9px] font-black rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-sm">{{ $hrGmLeavesPending }}</span>
            @endif
        </a>

        @php
            $gmOutboundPending = \App\Models\CommunicationRequest::where('is_approved_1', 1)
                ->where('is_approved_2', 0)->count();
        @endphp
        <a href="{{ route('admin.communications.outbound.index') }}"
            class="nav-item {{ request()->routeIs('admin.communications.outbound.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1 relative"
            style="background: rgba(245,158,11,0.12); color: #92400e;">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                style="background: rgba(245,158,11,0.25); color: #d97706;">
                <i class="fa-solid fa-paper-plane text-base"></i>
            </div>
            <span class="text-base font-semibold">Outbound Review</span>
            @if($gmOutboundPending > 0)
                <span class="absolute top-1 right-2 bg-amber-500 text-white text-[9px] font-black rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-sm">{{ $gmOutboundPending }}</span>
            @endif
        </a>
    @endif
@endif




@if($isAdmin)
    {{-- Organization Chart --}}
    <a href="{{ route('admin.departments.index') }}"
        class="nav-item {{ request()->routeIs('admin.departments.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-sitemap text-base"></i>
        </div>
        <span class="text-base font-semibold">Org Chart</span>
    </a>

    {{-- Tickets --}}
    <a href="{{ route('admin.tickets.index') }}"
        class="nav-item {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-ticket text-base"></i>
        </div>
        <span class="text-base font-semibold">Tickets</span>
    </a>

    {{-- Assets --}}
    <a href="{{ route('admin.assets.index') }}"
        class="nav-item {{ request()->routeIs('admin.assets.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-laptop text-base"></i>
        </div>
        <span class="text-base font-semibold">Assets</span>
    </a>

    {{-- Users --}}
    <a href="{{ route('admin.users.index') }}"
        class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-users-gear text-base"></i>
        </div>
        <span class="text-base font-semibold">Users</span>
    </a>

    {{-- Activity Logs --}}
    <a href="{{ route('admin.system_logs.index') }}"
        class="nav-item {{ request()->routeIs('admin.system_logs.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-clipboard-list text-base"></i>
        </div>
        <span class="text-base font-semibold">Activity Logs</span>
    </a>

    {{-- Settings --}}
    <a href="{{ route('admin.settings.index') }}"
        class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-cog text-base"></i>
        </div>
        <span class="text-base font-semibold">Settings</span>
    </a>

    {{-- Incidents --}}
    <a href="{{ route('admin.incidents.index') }}"
        class="nav-item {{ request()->routeIs('admin.incidents.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-triangle-exclamation text-base"></i>
        </div>
        <span class="text-base font-semibold">Incidents</span>
    </a>

    {{-- Inbound Correspondence (GM Review — only for GM) --}}
    @if(Auth::user()->is_gm)
    @php
        $adminInboundPending = \App\Models\InboundCorrespondence::where('gm_user_id', Auth::user()->user_id)
            ->whereIn('status', ['Pending Approval', 'Under Review', 'Resubmitted'])->count();
    @endphp
    <a href="{{ route('admin.inbound.index') }}"
        class="nav-item {{ request()->routeIs('admin.inbound.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1 relative">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-envelope-open-text text-base"></i>
        </div>
        <span class="text-base font-semibold">Inbound Corr.</span>
        @if($adminInboundPending > 0)
            <span class="absolute top-1 right-2 bg-indigo-500 text-white text-[9px] font-black rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-sm">{{ $adminInboundPending }}</span>
        @endif
    </a>
    @endif

    {{-- Feedback --}}
    <a href="{{ route('admin.feedback.index') }}"
        class="nav-item {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-comment-dots text-base"></i>
        </div>
        <span class="text-base font-semibold">Feedback</span>
    </a>

    {{-- GM: Probation Reviews (only shown if user is designated as GM) --}}
    @if(Auth::user() && Auth::user()->is_gm)
        @php
            $gmPendingCount = \App\Models\ProbationReview::where('gm_id', Auth::user()->user_id)
                ->where('status', 'reviewed')->count();
        @endphp
        <a href="{{ route('admin.probation-reviews.index') }}"
            class="nav-item {{ request()->routeIs('admin.probation-reviews.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1 relative"
            style="background: rgba(245,158,11,0.12); color: #92400e;">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                style="background: rgba(245,158,11,0.25); color: #d97706;">
                <i class="fa-solid fa-crown text-base"></i>
            </div>
            <span class="text-base font-semibold">Probation Reviews</span>
            @if($gmPendingCount > 0)
                <span class="absolute top-1 right-2 bg-amber-500 text-white text-[9px] font-black rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-sm">{{ $gmPendingCount }}</span>
            @endif
        </a>

    @endif
@endif

@if(in_array($user->user_type, ['eqa']) && !$isAdmin)
    <a href="{{ route('emp.ext.atps.index') }}"
        class="nav-item {{ request()->routeIs('emp.ext.atps.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-certificate text-base"></i>
        </div>
        <span class="text-base font-semibold">Partners</span>
    </a>
    <a href="{{ route('eqa.atps.index') }}"
        class="nav-item {{ request()->routeIs('eqa.atps.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-list-check text-base"></i>
        </div>
        <span class="text-base font-semibold">Audits</span>
    </a>
@endif