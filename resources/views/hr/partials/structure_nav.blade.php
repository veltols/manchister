@php
    $prefix = request()->is('admin*') ? 'admin' : 'hr';
@endphp
<div class="premium-card p-2 mb-8 w-fit">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route($prefix . '.departments.index') }}"
           class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ request()->routeIs($prefix . '.departments.index') ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-building mr-2"></i>Departments
        </a>
        <a href="{{ route($prefix . '.designations.index') }}"
           class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ request()->routeIs($prefix . '.designations.*') ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-briefcase mr-2"></i>Designations
        </a>
        <a href="{{ route($prefix . '.departments.chart') }}"
           class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ request()->routeIs($prefix . '.departments.chart') ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-sitemap mr-2"></i>Chart
        </a>
    </div>
</div>
