<!-- Standard Menu Items -->
<div id="emp-std-menu" style="display: none;">
    <a href="{{ route('emp.dashboard') }}"
        class="nav-item {{ request()->routeIs('emp.dashboard') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-chart-line text-base"></i>
        </div>
        <span class="text-sm font-semibold">Dashboard</span>
    </a>

    <a href="{{ route('emp.tasks.index') }}"
        class="nav-item {{ request()->routeIs('emp.tasks.*') && !request()->routeIs('emp.tasks.pending') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-list-check text-base"></i>
        </div>
        <span class="text-sm font-semibold">My Tasks</span>
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
            <span class="text-sm font-semibold">Pending</span>
        </a>
    @endif

    <a href="{{ route('emp.tickets.index') }}"
        class="nav-item {{ request()->routeIs('emp.tickets.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-headset text-base"></i>
        </div>
        <span class="text-sm font-semibold">Support</span>
    </a>

    <a href="{{ route('emp.calendar.index') }}"
        class="nav-item {{ request()->routeIs('emp.calendar.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-calendar-days text-base"></i>
        </div>
        <span class="text-sm font-semibold">Calendar</span>
    </a>
</div>

<!-- RC Menu Items (Hidden Initially) -->
<div id="emp-rc-menu" style="display: none;">
    @if(Auth::user()->user_type == 'eqa')
        <a href="{{ route('eqa.atps.index') }}" class="nav-item flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-user-shield text-base"></i>
            </div>
            <span class="text-sm font-semibold">EQA</span>
        </a>
    @else
        <a href="{{ route('emp.atps.index') }}"
            class="nav-item {{ request()->routeIs('emp.atps.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
            <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-building-columns text-base"></i>
            </div>
            <span class="text-sm font-semibold">Training Providers</span>
        </a>
    @endif

    <!-- Back to Main Menu Button -->
    <button onclick="switchEmpMenu('std')" class="w-full nav-item flex items-center gap-3 px-3 py-3 rounded-xl mt-auto">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-arrow-left text-base"></i>
        </div>
        <span class="text-sm font-semibold">Back</span>
    </button>
</div>

<!-- RC/EQA Toggle Button — always visible, pinned to bottom via sidebar -->
<div id="emp-rc-eqa-toggle" class="mt-auto pt-2 border-t border-slate-100">
    <button onclick="switchEmpMenu('rc')" class="w-full nav-item flex items-center gap-3 px-3 py-3 rounded-xl">
        <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-cubes text-base"></i>
        </div>
        <span class="text-sm font-semibold">{{ Auth::user()->user_type == 'eqa' ? 'EQA' : 'RC' }}</span>
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