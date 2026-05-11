@php
    $authUser = Auth::user();

    // ALL access is purely service-permission based — no user_type bypass
    $canEqa = $authUser && $authUser->hasService(10005);  // EQA portal
    $canTraining = $authUser && $authUser->hasService(10001);  // Training Providers
    $canStrategy = $authUser && $authUser->hasService(10002);  // Strategic Plans
    $canOpsPlanning = $authUser && $authUser->hasService(10003);  // Operational Planning
    $canSelfStudy = $authUser && $authUser->hasService(10004);  // Self Studies

    // RC toggle only shown if at least one RC service is granted
    $hasAnyRcService = $canEqa || $canTraining || $canStrategy || $canOpsPlanning || $canSelfStudy;

    // Check if employee has submitted feedback
    $hasSubmittedFeedback = false;
    if ($authUser && $authUser->employee) {
        $hasSubmittedFeedback = \App\Models\FeedbackForm::where('employee_id', $authUser->employee->employee_id)->exists();
    }
@endphp

<!-- Standard Menu Items -->
<div id="emp-std-menu" style="display: none;">
    <a href="{{ route('emp.dashboard') }}"
        class="nav-item {{ request()->routeIs('emp.dashboard') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-chart-line text-base"></i>
        </div>
        <span class="text-base font-semibold">Dashboard</span>
    </a>

    <!-- <a href="{{ route('emp.profile.index') }}"
        class="nav-item {{ request()->routeIs('emp.profile.index') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-user-circle text-base"></i>
        </div>
        <span class="text-base font-semibold">My Profile</span>
    </a> -->

    <a href="{{ route('emp.tasks.index') }}"
        class="nav-item {{ request()->routeIs('emp.tasks.*') && !request()->routeIs('emp.tasks.pending') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-list-check text-base"></i>
        </div>
        <span class="text-base font-semibold">My Tasks</span>
    </a>

    <a href="{{ route('emp.groups.index') }}"
        class="nav-item {{ request()->routeIs('emp.groups.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-users text-base"></i>
        </div>
        <span class="text-base font-semibold">Groups / Committees</span>
    </a>

    @php
        $taskPendingCount    = 0;
        $permPendingCount    = 0;
        $lmProbationPending  = 0;
        $isLineManager       = false;
        if ($authUser && $authUser->employee) {
            $employeeId = $authUser->employee->employee_id;
            $taskPendingCount   = \App\Models\Task::where('pending_line_manager_id', $employeeId)->count();
            $permPendingCount   = \App\Models\Permission::where('line_manager_id', $employeeId)
                ->whereIn('permission_status_id', [1, 2])->count();
            // Check if this employee is a line manager for any department
            $isLineManager = \App\Models\Department::where('line_manager_id', $employeeId)->exists();
            if ($isLineManager) {
                $lmProbationPending = \App\Models\ProbationReview::where('line_manager_id', $employeeId)
                    ->where('status', 'pending_manager')->count();
            }
        }
    @endphp

    @if($taskPendingCount + $permPendingCount > 0)
        <a href="{{ route('emp.tasks.pending') }}"
            class="nav-item {{ request()->routeIs('emp.tasks.pending') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1 relative"
            style="background: rgba(251,191,36,0.1); color: #92400e;">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                style="background: rgba(251,191,36,0.2); color: #d97706;">
                <i class="fa-solid fa-clock-rotate-left text-base"></i>
            </div>
            <span class="text-base font-semibold">Pending</span>
            <span class="absolute top-1 right-2 bg-amber-500 text-white text-[9px] font-black rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-sm">{{ $taskPendingCount + $permPendingCount }}</span>
        </a>
    @endif

    {{-- Line Manager: Probation Reviews + Team Leaves --}}
    @if($isLineManager)
        @php
            $lmLeavesPending = \App\Models\HrLeave::where('line_manager_id', $employeeId)
                ->where('leave_status_id', \App\Models\HrLeave::STATUS_PENDING_APPROVAL)->count();
        @endphp
        <a href="{{ route('emp.probation-reviews.index') }}"
            class="nav-item {{ request()->routeIs('emp.probation-reviews.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1 relative"
            style="{{ $lmProbationPending > 0 ? 'background: rgba(20,184,166,0.12); color: #134e4a;' : '' }}">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                style="{{ $lmProbationPending > 0 ? 'background: rgba(20,184,166,0.25); color: #0f766e;' : '' }}">
                <i class="fa-solid fa-clipboard-user text-base"></i>
            </div>
            <span class="text-base font-semibold">Probation Reviews</span>
            @if($lmProbationPending > 0)
                <span class="absolute top-1 right-2 bg-teal-500 text-white text-[9px] font-black rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-sm">{{ $lmProbationPending }}</span>
            @endif
        </a>
        <a href="{{ route('emp.lm.leaves.index') }}"
            class="nav-item {{ request()->routeIs('emp.lm.leaves.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1 relative"
            style="{{ $lmLeavesPending > 0 ? 'background: rgba(20,184,166,0.12); color: #134e4a;' : '' }}">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                style="{{ $lmLeavesPending > 0 ? 'background: rgba(20,184,166,0.25); color: #0f766e;' : '' }}">
                <i class="fa-solid fa-calendar-days text-base"></i>
            </div>
            <span class="text-base font-semibold">Team Leaves</span>
            @if($lmLeavesPending > 0)
                <span class="absolute top-1 right-2 bg-teal-500 text-white text-[9px] font-black rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-sm">{{ $lmLeavesPending }}</span>
            @endif
        </a>

    @endif

    {{-- ── Inbound Correspondence Portal ─────── --}}
    @php
        $inboundLiaisonCount = 0; // If you want to add a count for liaison later
        $empGmInboundPending = 0;
        if (Auth::user()->is_gm) {
            $empGmInboundPending = \App\Models\InboundCorrespondence::where('gm_user_id', Auth::user()->user_id)
                ->whereIn('status', ['Pending Approval', 'Under Review', 'Resubmitted'])->count();
        }
        $inboundPendingCount = \App\Models\InboundActionItem::where('assigned_to', Auth::user()->user_id)
            ->whereIn('status', ['Pending', 'In Progress'])
            ->count();
            
        $totalInboundPending = $empGmInboundPending + $inboundPendingCount;
    @endphp

    <div x-data="{ open: false }" @click.away="open = false" class="relative">
        <button @click="open = !open"
            class="nav-item {{ request()->routeIs('emp.inbound-liaison.*', 'emp.inbound-gm.*', 'emp.inbound.*') ? 'active' : '' }} w-full flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-inbox text-base"></i>
            </div>
            <span class="text-base font-semibold">Inbound Portal</span>
            @if($totalInboundPending > 0)
                <span class="bg-amber-500 text-white text-[9px] font-black rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-sm ml-2">{{ $totalInboundPending }}</span>
            @endif
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
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center relative overflow-hidden" style="background:linear-gradient(145deg,#0d9488,#14b8a6);
                                    box-shadow:0 6px 16px rgba(13,148,136,0.3),inset 0 1px 0 rgba(255,255,255,0.3);">
                        <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl"
                            style="background:rgba(255,255,255,0.3);"></div>
                        <i class="fa-solid fa-inbox text-white text-sm relative z-10"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-premium">Inbound Portal</h3>
                        <p class="text-[10px] text-slate-400">Incoming Correspondence</p>
                    </div>
                </div>
            </div>

            {{-- Inbound (Liaison) --}}
            @if(Auth::user()->is_liaison)
            <a href="{{ route('emp.inbound-liaison.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('emp.inbound-liaison.*') ? 'bg-teal-50' : 'hover:bg-slate-50' }}"
                style="{{ request()->routeIs('emp.inbound-liaison.*') ? 'box-shadow:0 4px 12px rgba(13,148,136,0.12);' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                    style="background:linear-gradient(145deg,#0d9488,#14b8a6);
                                box-shadow:0 4px 12px rgba(13,148,136,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);">
                    </div>
                    <i class="fa-solid fa-envelope-circle-check text-white text-xs relative z-10"></i>
                </div>
                <div class="flex flex-col flex-1">
                    <span class="font-semibold text-sm {{ request()->routeIs('emp.inbound-liaison.*') ? 'text-teal-800' : 'text-slate-700' }}">Register / Track</span>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">Liaison Stage</span>
                </div>
            </a>
            @endif

            {{-- Inbound (GM) --}}
            @if(Auth::user()->is_gm)
            <a href="{{ route('emp.inbound-gm.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('emp.inbound-gm.*') ? 'bg-amber-50' : 'hover:bg-slate-50' }}"
                style="{{ request()->routeIs('emp.inbound-gm.*') ? 'box-shadow:0 4px 12px rgba(245,158,11,0.12);' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                    style="background:linear-gradient(145deg,#f59e0b,#d97706);
                                box-shadow:0 4px 12px rgba(245,158,11,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);">
                    </div>
                    <i class="fa-solid fa-envelope-open-text text-white text-xs relative z-10"></i>
                </div>
                <div class="flex flex-col flex-1">
                    <span class="font-semibold text-sm {{ request()->routeIs('emp.inbound-gm.*') ? 'text-amber-800' : 'text-slate-700' }}">GM Review</span>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">Assignment Stage</span>
                </div>
                @if($empGmInboundPending > 0)
                    <span class="bg-amber-500 text-white text-[9px] font-black rounded-full min-w-[16px] h-[16px] flex items-center justify-center px-1">{{ $empGmInboundPending }}</span>
                @endif
            </a>
            @endif

            {{-- Inbound Actions (Assigned Items) --}}
            <a href="{{ route('emp.inbound.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('emp.inbound.*') ? 'bg-indigo-50' : 'hover:bg-slate-50' }}"
                style="{{ request()->routeIs('emp.inbound.*') ? 'box-shadow:0 4px 12px rgba(79,70,229,0.12);' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                    style="background:linear-gradient(145deg,#4f46e5,#6366f1);
                                box-shadow:0 4px 12px rgba(79,70,229,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);">
                    </div>
                    <i class="fa-solid fa-list-check text-white text-xs relative z-10"></i>
                </div>
                <div class="flex flex-col flex-1">
                    <span class="font-semibold text-sm {{ request()->routeIs('emp.inbound.*') ? 'text-indigo-800' : 'text-slate-700' }}">Action Items</span>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">Execution Stage</span>
                </div>
                @if($inboundPendingCount > 0)
                    <span class="bg-indigo-500 text-white text-[9px] font-black rounded-full min-w-[16px] h-[16px] flex items-center justify-center px-1">{{ $inboundPendingCount }}</span>
                @endif
            </a>
        </div>
    </div>


    <!-- Support Menu Drawer -->
    <div x-data="{ open: false }" @click.away="open = false" class="relative">
        <button @click="open = !open"
            class="nav-item {{ request()->routeIs('emp.ss.*', 'emp.requests.*', 'emp.tickets.*') ? 'active' : '' }} w-full flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-headset text-base"></i>
            </div>
            <span class="text-base font-semibold">Support Services</span>
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
                        <i class="fa-solid fa-headset text-white text-sm relative z-10"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-premium">Support Services</h3>
                        <p class="text-[10px] text-slate-400">Services &amp; Requests</p>
                    </div>
                </div>
            </div>

            {{-- HR Requests --}}
            <a href="{{ route('emp.requests.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('emp.requests.*') ? 'bg-amber-50' : 'hover:bg-slate-50' }}"
                style="{{ request()->routeIs('emp.requests.*') ? 'box-shadow:0 4px 12px rgba(245,158,11,0.12);' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                    style="background:linear-gradient(145deg,#f59e0b,#d97706);
                                box-shadow:0 4px 12px rgba(245,158,11,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);">
                    </div>
                    <i class="fa-solid fa-file-signature text-white text-sm relative z-10"></i>
                </div>
                <span
                    class="font-semibold text-base {{ request()->routeIs('emp.requests.*') ? 'text-amber-800' : 'text-slate-700' }}">HR Requests</span>
            </a>

            {{-- Admin Services --}}
            <a href="{{ route('emp.ss.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('emp.ss.*') ? 'bg-indigo-50' : 'hover:bg-slate-50' }}"
                style="{{ request()->routeIs('emp.ss.*') ? 'box-shadow:0 4px 12px rgba(99,102,241,0.12);' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                    style="background:linear-gradient(145deg,#6366f1,#4f46e5);
                                box-shadow:0 4px 12px rgba(99,102,241,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);">
                    </div>
                    <i class="fa-solid fa-screwdriver-wrench text-white text-sm relative z-10"></i>
                </div>
                <span
                    class="font-semibold text-base {{ request()->routeIs('emp.ss.*') ? 'text-indigo-800' : 'text-slate-700' }}">Admin Services</span>
            </a>

            {{-- IT Support / Tickets --}}
            <a href="{{ route('emp.tickets.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('emp.tickets.*') ? 'bg-teal-50' : 'hover:bg-slate-50' }}"
                style="{{ request()->routeIs('emp.tickets.*') ? 'box-shadow:0 4px 12px rgba(20,184,166,0.12);' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                    style="background:linear-gradient(145deg,#14b8a6,#0f766e);
                                box-shadow:0 4px 12px rgba(20,184,166,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);">
                    </div>
                    <i class="fa-solid fa-desktop text-white text-sm relative z-10"></i>
                </div>
                <span
                    class="font-semibold text-base {{ request()->routeIs('emp.tickets.*') ? 'text-teal-800' : 'text-slate-700' }}">IT Tickets</span>
            </a>

        </div>
    </div>

    {{-- NEW Outbound Correspondence Drawer --}}
    @php
        $employeeId = $authUser->employee->employee_id ?? 0;
        $lmCommPending = \App\Models\CommunicationRequest::where('approval_id_1', $employeeId)
            ->where('is_approved_1', 0)->count();
        $gmOutboundPending = \App\Models\CommunicationRequest::where('is_approved_1', 1)
            ->where('is_approved_2', 0)->count();
        $liaisonOutboundPending = \App\Models\CommunicationRequest::where('is_approved_2', 1)
            ->where('communication_status_id', 3)->count();
        $outboundTaskCount = \App\Models\OutboundActionItem::where('assigned_to_id', $employeeId)
            ->whereIn('status', ['Pending', 'In Progress'])
            ->count();
        
        $totalOutboundPending = $lmCommPending + $gmOutboundPending + $liaisonOutboundPending + $outboundTaskCount;
    @endphp
 
    <div x-data="{ open: false }" @click.away="open = false" class="relative">
        <button @click="open = !open"
            class="nav-item {{ request()->routeIs('emp.communications.*', 'emp.lm.communications.*', 'admin.communications.outbound.*', 'hr.communications.outbound_liaison.*', 'emp.outbound-tasks.*') ? 'active' : '' }} w-full flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-paper-plane text-base"></i>
            </div>
            <span class="text-base font-semibold">Outbound Portal</span>
            @if($totalOutboundPending > 0)
                <span class="bg-indigo-500 text-white text-[9px] font-black rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-sm ml-2">{{ $totalOutboundPending }}</span>
            @endif
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
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center relative overflow-hidden" style="background:linear-gradient(145deg,#4f46e5,#6366f1);
                                    box-shadow:0 6px 16px rgba(79,70,229,0.3),inset 0 1px 0 rgba(255,255,255,0.3);">
                        <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl"
                            style="background:rgba(255,255,255,0.3);"></div>
                        <i class="fa-solid fa-paper-plane text-white text-sm relative z-10"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-premium">Outbound Portal</h3>
                        <p class="text-[10px] text-slate-400">Manage Communications</p>
                    </div>
                </div>
            </div>
 
            {{-- My Requests --}}
            <a href="{{ route('emp.communications.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('emp.communications.*') ? 'bg-indigo-50' : 'hover:bg-slate-50' }}"
                style="{{ request()->routeIs('emp.communications.*') ? 'box-shadow:0 4px 12px rgba(79,70,229,0.12);' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                    style="background:linear-gradient(145deg,#4f46e5,#6366f1);
                                box-shadow:0 4px 12px rgba(79,70,229,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);">
                    </div>
                    <i class="fa-solid fa-paper-plane text-white text-xs relative z-10"></i>
                </div>
                <div class="flex flex-col">
                    <span class="font-semibold text-sm {{ request()->routeIs('emp.communications.*') ? 'text-indigo-800' : 'text-slate-700' }}">My Requests</span>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">History & New</span>
                </div>
            </a>
 
            {{-- Team Review (Line Manager) --}}
            @if($isLineManager)
            <a href="{{ route('emp.lm.communications.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('emp.lm.communications.*') ? 'bg-teal-50' : 'hover:bg-slate-50' }}"
                style="{{ request()->routeIs('emp.lm.communications.*') ? 'box-shadow:0 4px 12px rgba(20,184,166,0.12);' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                    style="background:linear-gradient(145deg,#14b8a6,#0f766e);
                                box-shadow:0 4px 12px rgba(20,184,166,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);">
                    </div>
                    <i class="fa-solid fa-user-tie text-white text-xs relative z-10"></i>
                </div>
                <div class="flex flex-col flex-1">
                    <span class="font-semibold text-sm {{ request()->routeIs('emp.lm.communications.*') ? 'text-teal-800' : 'text-slate-700' }}">Team Review</span>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">Line Manager Stage</span>
                </div>
                @if($lmCommPending > 0)
                    <span class="bg-teal-500 text-white text-[9px] font-black rounded-full min-w-[16px] h-[16px] flex items-center justify-center px-1">{{ $lmCommPending }}</span>
                @endif
            </a>
            @endif
 
            {{-- GM Review --}}
            @if($authUser && $authUser->is_gm)
            <a href="{{ route('admin.communications.outbound.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('admin.communications.outbound.*') ? 'bg-amber-50' : 'hover:bg-slate-50' }}"
                style="{{ request()->routeIs('admin.communications.outbound.*') ? 'box-shadow:0 4px 12px rgba(245,158,11,0.12);' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                    style="background:linear-gradient(145deg,#f59e0b,#d97706);
                                box-shadow:0 4px 12px rgba(245,158,11,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);">
                    </div>
                    <i class="fa-solid fa-crown text-white text-xs relative z-10"></i>
                </div>
                <div class="flex flex-col flex-1">
                    <span class="font-semibold text-sm {{ request()->routeIs('admin.communications.outbound.*') ? 'text-amber-800' : 'text-slate-700' }}">GM Review</span>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">Final Approval</span>
                </div>
                @if($gmOutboundPending > 0)
                    <span class="bg-amber-500 text-white text-[9px] font-black rounded-full min-w-[16px] h-[16px] flex items-center justify-center px-1">{{ $gmOutboundPending }}</span>
                @endif
            </a>
            @endif
 
            {{-- Dispatch (Liaison) --}}
            @if($authUser && $authUser->is_liaison)
            <a href="{{ route('hr.communications.outbound_liaison.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('hr.communications.outbound_liaison.*') ? 'bg-sky-50' : 'hover:bg-slate-50' }}"
                style="{{ request()->routeIs('hr.communications.outbound_liaison.*') ? 'box-shadow:0 4px 12px rgba(14,165,233,0.12);' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                    style="background:linear-gradient(145deg,#0ea5e9,#0284c7);
                                box-shadow:0 4px 12px rgba(14,165,233,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);">
                    </div>
                    <i class="fa-solid fa-truck-fast text-white text-xs relative z-10"></i>
                </div>
                <div class="flex flex-col flex-1">
                    <span class="font-semibold text-sm {{ request()->routeIs('hr.communications.outbound_liaison.*') ? 'text-sky-800' : 'text-slate-700' }}">Dispatch</span>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">Liaison Stage</span>
                </div>
                @if($liaisonOutboundPending > 0)
                    <span class="bg-sky-500 text-white text-[9px] font-black rounded-full min-w-[16px] h-[16px] flex items-center justify-center px-1">{{ $liaisonOutboundPending }}</span>
                @endif
            </a>
            @endif
 
            {{-- Action Tasks --}}
            <a href="{{ route('emp.outbound-tasks.index') }}"
                class="group flex items-center gap-3 p-3 rounded-xl transition-all hover:-translate-y-0.5 {{ request()->routeIs('emp.outbound-tasks.*') ? 'bg-rose-50' : 'hover:bg-slate-50' }}"
                style="{{ request()->routeIs('emp.outbound-tasks.*') ? 'box-shadow:0 4px 12px rgba(244,63,94,0.12);' : '' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                    style="background:linear-gradient(145deg,#f43f5e,#e11d48);
                                box-shadow:0 4px 12px rgba(244,63,94,0.35),inset 0 1px 0 rgba(255,255,255,0.35);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);">
                    </div>
                    <i class="fa-solid fa-tasks text-white text-xs relative z-10"></i>
                </div>
                <div class="flex flex-col flex-1">
                    <span class="font-semibold text-sm {{ request()->routeIs('emp.outbound-tasks.*') ? 'text-rose-800' : 'text-slate-700' }}">Action Tasks</span>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">Assigned Items</span>
                </div>
                @if($outboundTaskCount > 0)
                    <span class="bg-rose-500 text-white text-[9px] font-black rounded-full min-w-[16px] h-[16px] flex items-center justify-center px-1">{{ $outboundTaskCount }}</span>
                @endif
            </a>
        </div>
    </div>

    {{-- GM: Probation Reviews + Leave Queue — only shown when user is designated as GM --}}
    @if($authUser && $authUser->is_gm)
        @php
            $gmProbationPending = \App\Models\ProbationReview::where('gm_id', $authUser->user_id)
                ->where('status', 'reviewed')->count();
            $gmLeavesPending = \App\Models\HrLeave::where('leave_status_id', \App\Models\HrLeave::STATUS_PENDING_GM)->count();
        @endphp
        <a href="{{ route('admin.probation-reviews.index') }}"
            class="nav-item {{ request()->routeIs('admin.probation-reviews.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1 relative"
            style="background: rgba(245,158,11,0.12); color: #92400e;">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                style="background: rgba(245,158,11,0.25); color: #d97706;">
                <i class="fa-solid fa-crown text-base"></i>
            </div>
            <span class="text-base font-semibold">GM Reviews</span>
            @if($gmProbationPending > 0)
                <span class="absolute top-1 right-2 bg-amber-500 text-white text-[9px] font-black rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-sm">{{ $gmProbationPending }}</span>
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
            @if($gmLeavesPending > 0)
                <span class="absolute top-1 right-2 bg-amber-500 text-white text-[9px] font-black rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1 shadow-sm">{{ $gmLeavesPending }}</span>
            @endif
        </a>

    @endif

    @if($authUser->feedback_enabled)
        <a href="{{ route('emp.feedback.index') }}"
            class="nav-item {{ request()->routeIs('emp.feedback.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-comment-dots text-base"></i>
            </div>
            <span class="text-base font-semibold">Feedback</span>
        </a>
    @endif

    <a href="{{ route('emp.calendar.index') }}"
        class="nav-item {{ request()->routeIs('emp.calendar.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-calendar-days text-base"></i>
        </div>
        <span class="text-base font-semibold">Calendar</span>
    </a>
</div>

<!-- RC Menu Items (Hidden Initially) -->
<div id="emp-rc-menu" style="display: none;">

    @if($canEqa)
        {{-- EQA portal — requires service 10005 --}}
        <a href="{{ route('eqa.atps.index') }}" class="nav-item {{ request()->routeIs('eqa.atps.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-user-shield text-base"></i>
            </div>
            <span class="text-base font-semibold">EQA</span>
        </a>
    @endif

    @if($canTraining)
        {{-- Training Providers — requires service 10001 --}}
        <a href="{{ route('emp.atps.index') }}"
            class="nav-item {{ request()->routeIs('emp.atps.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-building-columns text-base"></i>
            </div>
            <span class="text-base font-semibold">Training Providers</span>
        </a>
    @endif

    @if($canStrategy)
        {{-- Strategic Plans — requires service 10002 --}}
        <a href="{{ route('emp.ext.strategies.index') }}"
            class="nav-item {{ request()->routeIs('emp.ext.strategies.*') && !request()->routeIs('emp.ext.strategies.projects.*') && !request()->routeIs('emp.ext.strategies.self_studies.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-chess-knight text-base"></i>
            </div>
            <span class="text-base font-semibold">Strategic Plans</span>
        </a>
    @endif

    @if($canOpsPlanning)
        {{-- Operational Planning — requires service 10003 --}}
        <a href="{{ route('emp.ext.strategies.projects.index') }}"
            class="nav-item {{ request()->routeIs('emp.ext.strategies.projects.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-briefcase text-base"></i>
            </div>
            <span class="text-base font-semibold">Operational Planning</span>
        </a>
    @endif

    @if($canSelfStudy)
        {{-- Self Studies — requires service 10004 --}}
        <a href="{{ route('emp.ext.strategies.self_studies.index') }}"
            class="nav-item {{ request()->routeIs('emp.ext.strategies.self_studies.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-book-open text-base"></i>
            </div>
            <span class="text-base font-semibold">Self Studies</span>
        </a>
    @endif

    @if(!$canEqa && !$canTraining && !$canStrategy && !$canOpsPlanning && !$canSelfStudy)
        {{-- No RC services assigned --}}
        <div class="px-3 py-6 text-center">
            <div class="w-12 h-12 rounded-full mx-auto flex items-center justify-center mb-3"
                style="background: rgba(255,255,255,0.12);">
                <i class="fa-solid fa-lock text-white/50 text-lg"></i>
            </div>
            <p class="text-white/50 text-xs font-semibold leading-snug">No RC services<br>assigned to your account.</p>
        </div>
    @endif

    <!-- Back to Main Menu Button -->
    <button onclick="switchEmpMenu('std')" class="w-full nav-item flex items-center gap-3 px-3 py-3 rounded-xl mt-auto">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-arrow-left text-base"></i>
        </div>
        <span class="text-base font-semibold">Back</span>
    </button>
</div>

<!-- RC/EQA Toggle Button — only shown if user has at least one RC service -->
@if($hasAnyRcService)
    <div id="emp-rc-eqa-toggle" class="mt-auto pt-3 px-1">
        <!-- Divider with label -->
        <div class="flex items-center gap-2 mb-3 px-2">
            <div class="flex-1 h-px"
                style="background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);"></div>
            <span
                style="font-size:9px; font-weight:700; letter-spacing:0.12em; color:rgba(255,255,255,0.45); text-transform:uppercase;">Switch
                Mode</span>
            <div class="flex-1 h-px"
                style="background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);"></div>
        </div>

        <button onclick="switchEmpMenu('rc')"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl relative overflow-hidden group" style="
                        background: linear-gradient(135deg, rgba(255,255,255,0.18) 0%, rgba(255,255,255,0.06) 100%);
                        border: 1.5px solid rgba(255,255,255,0.3);
                        box-shadow: 0 4px 20px rgba(0,0,0,0.2), 0 1px 4px rgba(0,0,0,0.1), inset 0 1px 0 rgba(255,255,255,0.25);
                        backdrop-filter: blur(12px);
                        transition: all 0.3s cubic-bezier(0.34,1.2,0.64,1);
                        color: #fff;
                    "
            onmouseenter="this.style.transform='translateY(-2px) scale(1.02)'; this.style.boxShadow='0 8px 28px rgba(0,0,0,0.28), 0 2px 8px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.35)'; this.style.borderColor='rgba(255,255,255,0.5)';"
            onmouseleave="this.style.transform=''; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.2), 0 1px 4px rgba(0,0,0,0.1), inset 0 1px 0 rgba(255,255,255,0.25)'; this.style.borderColor='rgba(255,255,255,0.3)';">

            <!-- Shimmer sweep -->
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                style="background: linear-gradient(105deg, transparent 30%, rgba(255,255,255,0.15) 50%, transparent 70%); pointer-events:none;">
            </div>

            <!-- Icon with glow ring -->
            <div class="relative flex-shrink-0">
                <!-- Pulse ring -->
                <div class="absolute inset-0 rounded-xl animate-ping"
                    style="background: rgba(255,255,255,0.15); animation-duration: 2.5s;"></div>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center relative z-10" style="
                                 background: linear-gradient(145deg, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0.1) 100%);
                                 border: 1px solid rgba(255,255,255,0.4);
                                 box-shadow: 0 4px 12px rgba(0,0,0,0.2), inset 0 1px 0 rgba(255,255,255,0.5);
                             ">
                    <i class="fa-solid fa-cubes text-sm text-white"></i>
                </div>
            </div>

            <!-- Label -->
            <div class="flex-1 text-left">
                <p class="text-base font-bold text-white leading-none">
                    {{ $canEqa ? 'EQA Portal' : 'RC Portal' }}
                </p>
                <p class="text-[10px] mt-0.5" style="color: rgba(255,255,255,0.6);">Switch workspace</p>
            </div>

            <!-- Arrow -->
            <i class="fa-solid fa-arrow-right text-xs flex-shrink-0" style="color: rgba(255,255,255,0.5);"></i>
        </button>
    </div>
@endif

<script>
    function switchEmpMenu(mode) {
        localStorage.setItem('emp_menu_mode', mode);
        
        if (mode === 'rc') {
            @if($canEqa)
                window.location.href = "{{ route('eqa.atps.index') }}";
            @elseif($canTraining)
                window.location.href = "{{ route('emp.atps.index') }}";
            @elseif($canStrategy)
                window.location.href = "{{ route('emp.ext.strategies.index') }}";
            @elseif($canOpsPlanning)
                window.location.href = "{{ route('emp.ext.strategies.projects.index') }}";
            @elseif($canSelfStudy)
                window.location.href = "{{ route('emp.ext.strategies.self_studies.index') }}";
            @else
                window.location.href = "{{ route('emp.dashboard') }}";
            @endif
        } else {
            window.location.href = "{{ route('emp.dashboard') }}";
        }
    }

    // Immediately invoked function to prevent FOUC (Flash of Unstyled Content)
    (function () {
        const hasRcServices = {{ $hasAnyRcService ? 'true' : 'false' }};
        const mode = hasRcServices ? (localStorage.getItem('emp_menu_mode') || 'std') : 'std';
        const stdMenu = document.getElementById('emp-std-menu');
        const rcMenu = document.getElementById('emp-rc-menu');
        const rcToggle = document.getElementById('emp-rc-eqa-toggle');

        if (mode === 'rc' && hasRcServices) {
            if (stdMenu) stdMenu.style.display = 'none';
            if (rcMenu) rcMenu.style.display = 'block';
            if (rcToggle) rcToggle.style.display = 'none';
        } else {
            if (stdMenu) stdMenu.style.display = 'block';
            if (rcMenu) rcMenu.style.display = 'none';
            if (rcToggle) rcToggle.style.display = hasRcServices ? 'block' : 'none';
        }
    })();
</script>