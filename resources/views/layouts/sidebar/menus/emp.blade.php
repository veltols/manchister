<!-- Standard Menu Items -->
<div id="emp-std-menu" style="display: none;">
    <a href="{{ route('emp.dashboard') }}"
        class="nav-item {{ request()->routeIs('emp.dashboard') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white mb-2">
        <i class="fa-solid fa-chart-line text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-60">Dashboard</span>
    </a>

    <a href="{{ route('emp.tasks.index') }}"
        class="nav-item {{ request()->routeIs('emp.tasks.*') && !request()->routeIs('emp.tasks.pending') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white mb-2">
        <i class="fa-solid fa-list-check text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-60">My Tasks</span>
    </a>

    @php
        $empPendingCount = 0;
        if(Auth::user() && Auth::user()->employee) {
            $empPendingCount = \App\Models\Task::where('pending_line_manager_id', Auth::user()->employee->employee_id)->count();
        }
    @endphp
    @if($empPendingCount > 0)
    <a href="{{ route('emp.tasks.pending') }}"
        class="nav-item {{ request()->routeIs('emp.tasks.pending') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl hover:text-white mb-2 relative" style="background: rgba(251,191,36,0.15); color: #fbbf24;">
        <i class="fa-solid fa-clock-rotate-left text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-80">Pending</span>
        <span class="absolute top-2 right-2 bg-amber-500 text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center">{{ $empPendingCount }}</span>
    </a>
    @endif

    <a href="{{ route('emp.tickets.index') }}"
        class="nav-item {{ request()->routeIs('emp.tickets.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white mb-2">
        <i class="fa-solid fa-headset text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-60">Support</span>
    </a>

    <a href="{{ route('emp.calendar.index') }}"
        class="nav-item {{ request()->routeIs('emp.calendar.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white mb-2">
        <i class="fa-solid fa-calendar-days text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-60">Calendar</span>
    </a>

</div>

<!-- RC Menu Items (Hidden Initially) -->
<div id="emp-rc-menu" style="display: none;">
    @if(Auth::user()->user_type == 'eqa')
        <a href="{{ route('eqa.atps.index') }}"
            class="nav-item flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white mb-2">
            <i class="fa-solid fa-user-shield text-2xl"></i>
            <span class="text-[12px] font-bold uppercase tracking-wider opacity-60 text-center">EQA</span>
        </a>
    @else
        <a href="{{ route('emp.atps.index') }}"
            class="nav-item {{ request()->routeIs('emp.atps.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white mb-2">
            <i class="fa-solid fa-building-columns text-2xl"></i>
            <span class="text-[12px] font-bold uppercase tracking-wider opacity-60 text-center">Training Providers</span>
        </a>
    @endif

    <!-- Back to Main Menu Button -->
    <button onclick="switchEmpMenu('std')"
        class="w-full nav-item flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white hover:bg-white/10 transition-colors mt-auto">
        <i class="fa-solid fa-arrow-left text-2xl"></i>
        <span class="text-[12px] font-bold uppercase tracking-wider opacity-60">Back</span>
    </button>
</div>

<!-- RC/EQA Toggle Button — always visible, pinned to bottom via sidebar -->
<div id="emp-rc-eqa-toggle" class="mt-auto pt-2 border-t border-white/10">
    <button onclick="switchEmpMenu('rc')"
        class="w-full nav-item flex flex-col items-center justify-center gap-1 px-2 py-3 rounded-xl text-white hover:text-white hover:bg-white/10 transition-colors">
        <i class="fa-solid fa-cubes text-2xl"></i>
        <span
            class="text-[12px] font-bold uppercase tracking-wider opacity-60">{{ Auth::user()->user_type == 'eqa' ? 'EQA' : 'RC' }}</span>
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