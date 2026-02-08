<!-- Standard Menu Items -->
<div id="emp-std-menu" style="display: none;">
    <a href="{{ route('emp.dashboard') }}"
        class="nav-item {{ request()->routeIs('emp.dashboard') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white mb-2">
        <i class="fa-solid fa-chart-line text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Dashboard</span>
    </a>

    <a href="{{ route('emp.tasks.index') }}"
        class="nav-item {{ request()->routeIs('emp.tasks.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white mb-2">
        <i class="fa-solid fa-list-check text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">My Tasks</span>
    </a>

    <a href="{{ route('emp.tickets.index') }}"
        class="nav-item {{ request()->routeIs('emp.tickets.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white mb-2">
        <i class="fa-solid fa-headset text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Support</span>
    </a>

    <a href="{{ route('emp.calendar.index') }}"
        class="nav-item {{ request()->routeIs('emp.calendar.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white mb-2">
        <i class="fa-solid fa-calendar-days text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Calendar</span>
    </a>

    <!-- RC Toggle Button -->
    <button onclick="switchEmpMenu('rc')"
        class="w-full nav-item flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white hover:bg-white/10 transition-colors">
        <i class="fa-solid fa-server text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">RC</span>
    </button>
</div>

<!-- RC Menu Items (Hidden Initially) -->
<div id="emp-rc-menu" style="display: none;">
    <a href="{{ route('emp.atps.index') }}"
        class="nav-item {{ request()->routeIs('emp.atps.*') ? 'active' : '' }} flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white mb-2">
        <i class="fa-solid fa-building-columns text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60 text-center">Training Providers</span>
    </a>

    <!-- Back to Main Menu Button -->
    <button onclick="switchEmpMenu('std')"
        class="w-full nav-item flex flex-col items-center justify-center gap-1 px-2 py-4 rounded-xl text-white hover:text-white hover:bg-white/10 transition-colors mt-auto">
        <i class="fa-solid fa-arrow-left text-2xl"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Back</span>
    </button>
</div>

<script>
    function switchEmpMenu(mode) {
        localStorage.setItem('emp_menu_mode', mode);
        window.location.href = "{{ route('emp.dashboard') }}";
    }

    // Immediately invoked function to prevent FOUC (Flash of Unstyled Content)
    (function() {
        const mode = localStorage.getItem('emp_menu_mode') || 'std';
        const stdMenu = document.getElementById('emp-std-menu');
        const rcMenu = document.getElementById('emp-rc-menu');

        if (mode === 'rc') {
            if(stdMenu) stdMenu.style.display = 'none';
            if(rcMenu) rcMenu.style.display = 'block';
        } else {
            if(stdMenu) stdMenu.style.display = 'block';
            if(rcMenu) rcMenu.style.display = 'none';
        }
    })();
</script>
