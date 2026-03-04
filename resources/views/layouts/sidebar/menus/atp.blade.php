{{-- ── Dashboard ─────────────────────────────────────────────────────────── --}}
<a href="{{ route('rc.portal.dashboard') }}"
    class="nav-item {{ request()->routeIs('rc.portal.dashboard') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
    <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
        <i class="fa-solid fa-chart-line text-base"></i>
    </div>
    <span class="text-base font-semibold">Dashboard</span>
</a>

{{-- ── Accreditation ─────────────────────────────────────────────────────── --}}
<a href="{{ route('rc.portal.wizard.step1') }}"
    class="nav-item {{ request()->routeIs('rc.portal.wizard.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
    <div class="nav-icon-wrap w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
        <i class="fa-solid fa-briefcase text-base"></i>
    </div>
    <span class="text-base font-semibold">Accreditation</span>
</a>