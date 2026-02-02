@extends('layouts.app')

@section('title', 'HR Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="glass-panel p-6 flex flex-col relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
        <div class="z-10">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Total Employees</p>
            <h3 class="text-3xl font-bold text-premium">{{ $employee_count ?? 0 }}</h3>
        </div>
        <div class="absolute -right-4 -bottom-4 text-slate-100 opacity-50 group-hover:scale-110 transition-transform duration-500">
            <i class="fa-solid fa-users text-8xl"></i>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center text-xs text-green-600 font-bold">
            <i class="fa-solid fa-arrow-up mr-1"></i> 12% increase
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="glass-panel p-6 flex flex-col relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
        <div class="z-10">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">On Leaves</p>
            <h3 class="text-3xl font-bold text-premium">{{ $leaves_count ?? 0 }}</h3>
        </div>
        <div class="absolute -right-4 -bottom-4 text-slate-100 opacity-50 group-hover:scale-110 transition-transform duration-500">
             <i class="fa-solid fa-plane-departure text-8xl"></i>
        </div>
         <div class="mt-4 pt-4 border-t border-slate-100 flex items-center text-xs text-slate-400">
            Active requests
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="glass-panel p-6 flex flex-col relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
        <div class="z-10">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Departments</p>
            <h3 class="text-3xl font-bold text-premium">{{ $dept_count ?? 0 }}</h3>
        </div>
        <div class="absolute -right-4 -bottom-4 text-slate-100 opacity-50 group-hover:scale-110 transition-transform duration-500">
             <i class="fa-solid fa-building text-8xl"></i>
        </div>
         <div class="mt-4 pt-4 border-t border-slate-100 flex items-center text-xs text-slate-400">
            Across 3 branches
        </div>
    </div>
    
     <!-- Stat Card 4 -->
    <div class="glass-panel p-6 flex flex-col relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300 bg-gradient-to-br from-primary to-secondary text-white border-none shadow-lg shadow-blue-900/20">
        <div class="z-10">
            <p class="text-sm font-bold text-blue-200 uppercase tracking-wider mb-1">Pending Actions</p>
            <h3 class="text-3xl font-bold text-white">5</h3>
        </div>
        <div class="absolute -right-4 -bottom-4 text-white opacity-10 group-hover:scale-110 transition-transform duration-500">
             <i class="fa-solid fa-bell text-8xl"></i>
        </div>
         <div class="mt-4 pt-4 border-t border-white/10 flex items-center text-xs text-blue-100">
            Requires attention
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Chart Section -->
    <div class="lg:col-span-2 glass-panel p-6">
        <h3 class="text-lg font-bold text-premium mb-6 flex items-center">
            <i class="fa-solid fa-chart-area mr-2 text-accent"></i> Attendance Overview
        </h3>
        <div class="h-64 bg-slate-50 rounded-xl flex items-center justify-center border border-dashed border-slate-300">
            <span class="text-slate-400 text-sm">Chart Visualization Placeholder (Integrate Chart.js)</span>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="glass-panel p-6">
         <h3 class="text-lg font-bold text-premium mb-6">Quick Actions</h3>
         <div class="space-y-3">
             <a href="{{ route('hr.employees.create') }}" class="flex items-center p-3 rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors group">
                 <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-green-500 shadow-sm mr-3 group-hover:scale-110 transition-transform">
                     <i class="fa-solid fa-user-plus"></i>
                 </div>
                 <span class="font-medium text-slate-700">Add New Employee</span>
             </a>
             <a href="{{ route('rc.atps.index') }}" class="flex items-center p-3 rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors group">
                 <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-blue-500 shadow-sm mr-3 group-hover:scale-110 transition-transform">
                     <i class="fa-solid fa-graduation-cap"></i>
                 </div>
                 <span class="font-medium text-slate-700">Manage Providers</span>
             </a>
             <a href="{{ route('hr.documents.index') }}" class="flex items-center p-3 rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors group">
                 <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-amber-500 shadow-sm mr-3 group-hover:scale-110 transition-transform">
                     <i class="fa-solid fa-file-upload"></i>
                 </div>
                 <span class="font-medium text-slate-700">Upload Policy</span>
             </a>
         </div>
    </div>
</div>
@endsection
