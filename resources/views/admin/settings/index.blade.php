@extends('layouts.app')

@section('title', 'Settings')
@section('subtitle', 'System configuration')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="premium-card p-8">
        <h1 class="text-3xl font-display font-bold text-slate-800 mb-2">
            <i class="fa-solid fa-cogs text-indigo-600 mr-3"></i>System Settings
        </h1>
        <p class="text-slate-500">Manage system-wide configurations and dropdown lists</p>
    </div>

    <!-- Settings Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Leave Types -->
        <a href="{{ route('admin.settings.leave_types') }}" class="block group">
            <div class="premium-card p-8 text-center hover:shadow-xl transition-all duration-200">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fa-solid fa-calendar-alt text-white text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">Leave Types</h3>
                <p class="text-sm text-slate-500">Manage annual, sick, and unpaid leave definitions</p>
            </div>
        </a>

        <!-- Asset Categories -->
        <a href="{{ route('admin.settings.asset_categories') }}" class="block group">
            <div class="premium-card p-8 text-center hover:shadow-xl transition-all duration-200">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fa-solid fa-laptop text-white text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">Asset Categories</h3>
                <p class="text-sm text-slate-500">Define Electronics, Furniture, Vehicles etc.</p>
            </div>
        </a>

        <!-- Departments -->
        <a href="{{ route('hr.departments.index') }}" class="block group">
            <div class="premium-card p-8 text-center hover:shadow-xl transition-all duration-200">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fa-solid fa-building text-white text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">Departments</h3>
                <p class="text-sm text-slate-500">Manage organization structure</p>
            </div>
        </a>

        <!-- User Roles (Placeholder) -->
        <div class="premium-card p-8 text-center opacity-60">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-200 flex items-center justify-center shadow-lg">
                <i class="fa-solid fa-user-shield text-slate-400 text-2xl"></i>
            </div>
            <h3 class="font-bold text-lg text-slate-600 mb-2">Roles & Permissions</h3>
            <p class="text-sm text-slate-400">Coming Soon</p>
        </div>

    </div>

</div>
@endsection
