@extends('layouts.app')

@section('title', 'System Settings')
@section('subtitle', 'Manage System Lists and Configurations')

@section('content')
    <div class="h-[calc(100vh-12rem)] flex gap-6 animate-fade-in-up">

        <!-- Sidebar Navigation for Settings -->
        <div class="w-64 flex flex-col gap-2 shrink-0">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest px-4 mb-2">General</h3>

            <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white hover:shadow-sm text-slate-600 hover:text-indigo-600 transition-all">
                <i class="fa-solid fa-users w-5 text-center"></i>
                <span class="font-bold text-sm">Users</span>
            </a>

            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest px-4 mb-2 mt-4">HR & Ops</h3>

            <a href="{{ route('admin.settings.leave_types') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white hover:shadow-sm text-slate-600 hover:text-indigo-600 transition-all">
                <i class="fa-solid fa-calendar-days w-5 text-center"></i>
                <span class="font-bold text-sm">Leave Types</span>
            </a>

            <a href="{{ route('admin.settings.support_categories') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white hover:shadow-sm text-slate-600 hover:text-indigo-600 transition-all">
                <i class="fa-solid fa-headset w-5 text-center"></i>
                <span class="font-bold text-sm">Support Routes</span>
            </a>

            <a href="{{ route('admin.settings.priorities') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white hover:shadow-sm text-slate-600 hover:text-indigo-600 transition-all">
                <i class="fa-solid fa-flag w-5 text-center"></i>
                <span class="font-bold text-sm">Priorities</span>
            </a>

            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest px-4 mb-2 mt-4">Assets</h3>

            <a href="{{ route('admin.settings.asset_categories') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white hover:shadow-sm text-slate-600 hover:text-indigo-600 transition-all">
                <i class="fa-solid fa-layer-group w-5 text-center"></i>
                <span class="font-bold text-sm">Asset Categories</span>
            </a>

            <a href="{{ route('admin.settings.asset_statuses') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white hover:shadow-sm text-slate-600 hover:text-indigo-600 transition-all">
                <i class="fa-solid fa-tags w-5 text-center"></i>
                <span class="font-bold text-sm">Asset Statuses</span>
            </a>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 premium-card p-10 flex items-center justify-center bg-slate-50 border-dashed">
            <div class="text-center">
                <div
                    class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-200 flex items-center justify-center mx-auto mb-4 text-3xl">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-700">Select a Category</h2>
                <p class="text-slate-500 mt-2">Choose a setting from the sidebar to manage.</p>
            </div>
        </div>

    </div>
@endsection
