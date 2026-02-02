@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Stat 1 -->
    <div class="glass-panel p-6 relative group overflow-hidden hover:-translate-y-1 transition-all duration-300">
        <h3 class="text-slate-500 font-bold text-sm uppercase tracking-wider">Leave Balance</h3>
        <div class="flex items-end gap-2 mt-2">
            <span class="text-4xl font-bold text-slate-800">14</span>
            <span class="text-sm text-slate-400 mb-1">/ 21 Days</span>
        </div>
        <div class="absolute right-4 top-4 w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
            <i class="fa-solid fa-umbrella-beach"></i>
        </div>
    </div>
    
    <!-- Stat 2 -->
    <div class="glass-panel p-6 relative group overflow-hidden hover:-translate-y-1 transition-all duration-300">
        <h3 class="text-slate-500 font-bold text-sm uppercase tracking-wider">Pending Tasks</h3>
        <div class="flex items-end gap-2 mt-2">
            <span class="text-4xl font-bold text-slate-800">{{ $pending_tasks ?? 3 }}</span>
            <span class="text-sm text-yellow-500 font-bold mb-1">Due soon</span>
        </div>
         <div class="absolute right-4 top-4 w-12 h-12 rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center text-xl">
            <i class="fa-solid fa-clock"></i>
        </div>
    </div>

    <!-- Stat 3 -->
    <div class="glass-panel p-6 relative group overflow-hidden hover:-translate-y-1 transition-all duration-300 bg-gradient-to-br from-indigo-500 to-purple-600 text-white border-none shadow-xl shadow-indigo-500/20">
        <h3 class="text-indigo-100 font-bold text-sm uppercase tracking-wider">Messages</h3>
        <div class="flex items-end gap-2 mt-2">
            <span class="text-4xl font-bold text-white">2</span>
            <span class="text-sm text-indigo-200 mb-1">Unread</span>
        </div>
        <div class="absolute right-4 top-4 w-12 h-12 rounded-xl bg-white/20 text-white flex items-center justify-center text-xl backdrop-blur">
            <i class="fa-solid fa-envelope"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Tasks List -->
    <div class="lg:col-span-2 glass-panel p-0 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-premium">Recent Tasks</h3>
            <a href="{{ route('emp.tasks.index') }}" class="text-xs font-bold text-sky-500 hover:text-sky-600 uppercase tracking-wide">View All</a>
        </div>
        <div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="pl-6">Task</th>
                        <th>Deadline</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                         <td class="pl-6 font-medium">Update Q3 Report</td>
                         <td class="text-sm">Tomorrow</td>
                         <td><span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-bold">In Progress</span></td>
                    </tr>
                     <tr>
                         <td class="pl-6 font-medium">Safety Training</td>
                         <td class="text-sm">Feb 10, 2026</td>
                         <td><span class="px-2 py-1 bg-slate-100 text-slate-500 rounded text-xs font-bold">Pending</span></td>
                    </tr>
                     <tr>
                         <td class="pl-6 font-medium">Audit Prep</td>
                         <td class="text-sm">Feb 15, 2026</td>
                         <td><span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold">Done</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="space-y-6">
        <div class="glass-panel p-6">
            <h3 class="font-bold text-premium mb-4">Quick Links</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('emp.leaves.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-slate-50 hover:bg-sky-50 hover:text-sky-600 transition-colors border border-slate-100 group">
                    <i class="fa-solid fa-calendar-plus text-2xl mb-2 text-slate-400 group-hover:text-sky-500 transition-colors"></i>
                    <span class="text-xs font-bold">Apply Leave</span>
                </a>
                 <a href="{{ route('emp.tickets.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-slate-50 hover:bg-pink-50 hover:text-pink-600 transition-colors border border-slate-100 group">
                    <i class="fa-solid fa-headset text-2xl mb-2 text-slate-400 group-hover:text-pink-500 transition-colors"></i>
                    <span class="text-xs font-bold">Support</span>
                </a>
            </div>
        </div>

        <div class="glass-panel p-6 bg-gradient-to-br from-slate-800 to-slate-900 text-white border-none">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-slate-700 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-bullhorn text-yellow-400"></i>
                </div>
                <div>
                    <h4 class="font-bold text-lg">Next Holiday</h4>
                    <p class="text-slate-400 text-sm">Eid Al-Fitr</p>
                </div>
            </div>
            <div class="bg-slate-700/50 rounded-lg p-3 text-center">
                <span class="font-mono text-xl text-yellow-400">14 Days</span> <span class="text-slate-400 text-sm">Remaining</span>
            </div>
        </div>
    </div>
</div>
@endsection
