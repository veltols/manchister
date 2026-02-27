<!-- Standard Menu Items -->
<div id="emp-std-menu" style="display: none;">
    <a href="{{ route('emp.dashboard') }}"
        class="nav-item {{ request()->routeIs('emp.dashboard') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-chart-line text-base"></i>
        </div>
        <span class="text-base font-semibold">Dashboard</span>
    </a>

    <a href="{{ route('emp.tasks.index') }}"
        class="nav-item {{ request()->routeIs('emp.tasks.*') && !request()->routeIs('emp.tasks.pending') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-list-check text-base"></i>
        </div>
        <span class="text-base font-semibold">My Tasks</span>
    </a>

    @php
        $empPendingCount = 0;
        if (Auth::user() && Auth::user()->employee) {
            $empPendingCount = \App\Models\Task::where('pending_line_manager_id', Auth::user()->employee->employee_id)->count();
        }
    @endphp
    @if($empPendingCount > 0)
        <a href="{{ route('emp.tasks.pending') }}"
            class="nav-item {{ request()->routeIs('emp.tasks.pending') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1 relative"
            style="background: rgba(251,191,36,0.1); color: #92400e;">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 relative"
                style="background: rgba(251,191,36,0.2); color: #d97706;">
                <i class="fa-solid fa-clock-rotate-left text-base"></i>
                <span
                    class="absolute -top-1.5 -right-1.5 bg-amber-500 text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center">{{ $empPendingCount }}</span>
            </div>
            <span class="text-base font-semibold">Pending</span>
        </a>
    @endif

    <a href="{{ route('emp.tickets.index') }}"
        class="nav-item {{ request()->routeIs('emp.tickets.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-headset text-base"></i>
        </div>
        <span class="text-base font-semibold">Support</span>
    </a>

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
    @if(Auth::user()->user_type == 'eqa')
        <a href="{{ route('eqa.atps.index') }}" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-user-shield text-base"></i>
            </div>
            <span class="text-base font-semibold">EQA</span>
        </a>
    @else
        <a href="{{ route('emp.atps.index') }}"
            class="nav-item {{ request()->routeIs('emp.atps.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-building-columns text-base"></i>
            </div>
            <span class="text-base font-semibold">Training Providers</span>
        </a>
    @endif

    <!-- Back to Main Menu Button -->
    <button onclick="switchEmpMenu('std')" class="w-full nav-item flex items-center gap-3 px-3 py-3 rounded-xl mt-auto">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-arrow-left text-base"></i>
        </div>
        <span class="text-base font-semibold">Back</span>
    </button>
</div>

<!-- RC/EQA Toggle Button — always visible, pinned to bottom via sidebar -->
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
                {{ Auth::user()->user_type == 'eqa' ? 'EQA Portal' : 'RC Portal' }}
            </p>
            <p class="text-[10px] mt-0.5" style="color: rgba(255,255,255,0.6);">Switch workspace</p>
        </div>

        <!-- Arrow -->
        <i class="fa-solid fa-arrow-right text-xs flex-shrink-0" style="color: rgba(255,255,255,0.5);"></i>
    </button>
</div>

<script>
    function switchEmpMenu(mode) {
        localStorage.setItem('emp_menu_mode', mode);
        window.location.href = "{{ route('emp.dashboard') }}";
    }

    // Immediately invoked function to prevent FOUC (Flash of Unstyled Content)
    (function () {
        const mode = localStorage.getItem('emp_menu_mode') || 'std';
        const stdMenu = document.getElementById('emp-std-menu');
        const rcMenu = document.getElementById('emp-rc-menu');
        const rcToggle = document.getElementById('emp-rc-eqa-toggle');

        if (mode === 'rc') {
            if (stdMenu) stdMenu.style.display = 'none';
            if (rcMenu) rcMenu.style.display = 'block';
            if (rcToggle) rcToggle.style.display = 'none';
        } else {
            if (stdMenu) stdMenu.style.display = 'block';
            if (rcMenu) rcMenu.style.display = 'none';
            if (rcToggle) rcToggle.style.display = 'block';
        }
    })();
</script>