<!-- Shared Sidebar Content -->
<div class="flex flex-col gap-2 shrink-0 h-full">
    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest px-4 mb-2">General</h3>

    <a href="{{ route('admin.users.index') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white hover:shadow-sm text-slate-600 hover:text-indigo-600 transition-all {{ request()->routeIs('admin.users.*') ? 'bg-white shadow-sm text-indigo-600' : '' }}">
        <i class="fa-solid fa-users w-5 text-center"></i>
        <span class="font-bold text-sm">Users</span>
    </a>

    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest px-4 mb-2 mt-4">HR & Ops Lists</h3>

    <a href="{{ route('admin.settings.leave_types') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white hover:shadow-sm text-slate-600 hover:text-indigo-600 transition-all {{ request()->routeIs('admin.settings.leave_types') ? 'bg-white shadow-sm text-indigo-600' : '' }}">
        <i class="fa-solid fa-calendar-days w-5 text-center"></i>
        <span class="font-bold text-sm">Leave Types</span>
    </a>

    <a href="{{ route('admin.settings.support_categories') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white hover:shadow-sm text-slate-600 hover:text-indigo-600 transition-all {{ request()->routeIs('admin.settings.support_categories') ? 'bg-white shadow-sm text-indigo-600' : '' }}">
        <i class="fa-solid fa-headset w-5 text-center"></i>
        <span class="font-bold text-sm">Support Routes</span>
    </a>

    <a href="{{ route('admin.settings.priorities') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white hover:shadow-sm text-slate-600 hover:text-indigo-600 transition-all {{ request()->routeIs('admin.settings.priorities') ? 'bg-white shadow-sm text-indigo-600' : '' }}">
        <i class="fa-solid fa-flag w-5 text-center"></i>
        <span class="font-bold text-sm">Priorities</span>
    </a>

    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest px-4 mb-2 mt-4">Assets Lists</h3>

    <a href="{{ route('admin.settings.asset_categories') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white hover:shadow-sm text-slate-600 hover:text-indigo-600 transition-all {{ request()->routeIs('admin.settings.asset_categories') ? 'bg-white shadow-sm text-indigo-600' : '' }}">
        <i class="fa-solid fa-layer-group w-5 text-center"></i>
        <span class="font-bold text-sm">Category</span>
    </a>

    <a href="{{ route('admin.settings.asset_statuses') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white hover:shadow-sm text-slate-600 hover:text-indigo-600 transition-all {{ request()->routeIs('admin.settings.asset_statuses') ? 'bg-white shadow-sm text-indigo-600' : '' }}">
        <i class="fa-solid fa-tags w-5 text-center"></i>
        <span class="font-bold text-sm">Status</span>
    </a>
</div>
