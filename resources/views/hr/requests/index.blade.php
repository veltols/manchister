@extends('layouts.app')

@section('title', 'HR Requests Hub')
@section('subtitle', 'Select a module to manage requests and operations')

@section('content')
    <div class="space-y-8 animate-fade-in-up">

        <!-- Sub Navigation -->
        @include('hr.partials.requests_nav')

        <!-- Hero Section / Header Summary -->
        <div
            class="premium-card p-10 bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 border-none shadow-2xl relative overflow-hidden group">
            <!-- Abstract background elements -->
            <div
                class="absolute -top-20 -right-20 w-80 h-80 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-colors duration-700">
            </div>
            <div
                class="absolute -bottom-20 -left-20 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-colors duration-700">
            </div>

            <div class="relative z-10">
                <h2 class="text-4xl font-display font-bold text-white mb-4">Operations Hub</h2>
                <p class="text-indigo-100/80 max-w-2xl text-lg font-medium leading-relaxed">
                    Welcome to the unified HR management center. From here, you can monitor and process all employee
                    lifecycle events, including attendance, leaves, performance reviews, and disciplinary proceedings.
                </p>
                <div class="flex gap-4 mt-8">
                    <div class="px-4 py-2 bg-white/10 backdrop-blur-md rounded-xl border border-white/10">
                        <span class="text-white/60 text-xs font-bold uppercase tracking-wider block">Total Operations</span>
                        <span class="text-white text-2xl font-bold">{{ array_sum($stats) }}</span>
                    </div>
                    <div class="px-4 py-2 bg-emerald-500/20 backdrop-blur-md rounded-xl border border-emerald-500/20">
                        <span class="text-emerald-300 text-xs font-bold uppercase tracking-wider block">System Health</span>
                        <span class="text-white text-2xl font-bold">Optimal</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modules Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @php
                $modules = [
                    [
                        'title' => 'Leaves Management',
                        'desc' => 'Process annual, sick, and specialized leave requests.',
                        'icon' => 'fa-calendar-check',
                        'route' => 'hr.leaves.index',
                        'count' => $stats['leaves'],
                        'color' => 'emerald',
                        'gradient' => 'from-emerald-500 to-teal-600'
                    ],
                    [
                        'title' => 'Permissions',
                        'desc' => 'Manage short leave permissions and hourly absences.',
                        'icon' => 'fa-clock',
                        'route' => 'hr.permissions.index',
                        'count' => $stats['permissions'],
                        'color' => 'blue',
                        'gradient' => 'from-blue-500 to-indigo-600'
                    ],
                    [
                        'title' => 'Disciplinary Actions',
                        'desc' => 'Record and monitor employee warnings and proceedings.',
                        'icon' => 'fa-gavel',
                        'route' => 'hr.disciplinary.index',
                        'count' => $stats['disciplinary'],
                        'color' => 'rose',
                        'gradient' => 'from-rose-500 to-orange-600'
                    ],
                    [
                        'title' => 'Attendance Logs',
                        'desc' => 'Daily check-in/out records and automated tracking.',
                        'icon' => 'fa-clipboard-user',
                        'route' => 'hr.attendance.index',
                        'count' => $stats['attendance'],
                        'color' => 'amber',
                        'gradient' => 'from-amber-400 to-orange-500'
                    ],
                    [
                        'title' => 'Exit Interviews',
                        'desc' => 'Manage the offboarding process and feedback surveys.',
                        'icon' => 'fa-door-open',
                        'route' => 'hr.exit_interviews.index',
                        'count' => $stats['exit_interviews'],
                        'color' => 'purple',
                        'gradient' => 'from-purple-500 to-indigo-700'
                    ],
                    [
                        'title' => 'Performance reviews',
                        'desc' => 'Track employee growth, scores, and manager feedback.',
                        'icon' => 'fa-star',
                        'route' => 'hr.performance.index',
                        'count' => $stats['performance'],
                        'color' => 'indigo',
                        'gradient' => 'from-indigo-500 to-blue-700'
                    ],
                    [
                        'title' => 'HR Documents',
                        'desc' => 'Internal policies, procedures, and official templates.',
                        'icon' => 'fa-file-invoice',
                        'route' => 'hr.documents.index',
                        'count' => $stats['documents'],
                        'color' => 'cyan',
                        'gradient' => 'from-cyan-500 to-blue-600'
                    ],
                    [
                        'title' => 'Groups & Committees',
                        'desc' => 'Internal team collaboration and committee units.',
                        'icon' => 'fa-users-rectangle',
                        'route' => 'hr.groups.index',
                        'count' => $stats['groups'],
                        'color' => 'blue',
                        'gradient' => 'from-blue-600 to-indigo-700'
                    ],
                    [
                        'title' => 'Task Management',
                        'desc' => 'Track assignments, deadlines, and project progress.',
                        'icon' => 'fa-list-check',
                        'route' => 'hr.tasks.index',
                        'count' => $stats['tasks'],
                        'color' => 'indigo',
                        'gradient' => 'from-indigo-600 to-violet-600'
                    ]
                ];
            @endphp

            @foreach($modules as $module)
                <a href="{{ route($module['route']) }}"
                    class="premium-card group hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div
                                class="w-14 h-14 rounded-2xl bg-{{ $module['color'] }}-50 text-{{ $module['color'] }}-600 flex items-center justify-center text-2xl shadow-sm group-hover:bg-{{ $module['color'] }}-600 group-hover:text-white transition-all duration-300">
                                <i class="fa-solid {{ $module['icon'] }}"></i>
                            </div>
                            <div
                                class="px-3 py-1 bg-{{ $module['color'] }}-100/50 text-{{ $module['color'] }}-700 rounded-full text-xs font-bold uppercase tracking-tighter">
                                {{ $module['count'] }} Records
                            </div>
                        </div>

                        <h3
                            class="text-xl font-display font-bold text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">
                            {{ $module['title'] }}
                        </h3>
                        <p class="text-slate-500 font-medium text-sm leading-relaxed mb-6">{{ $module['desc'] }}</p>

                        <div
                            class="flex items-center text-xs font-bold text-indigo-500 group-hover:translate-x-1 transition-transform">
                            MANAGE MODULE <i class="fa-solid fa-arrow-right ml-2"></i>
                        </div>
                    </div>

                    <!-- Progress Line at the bottom -->
                    <div class="h-1.5 w-full bg-slate-100 overflow-hidden mt-auto">
                        <div
                            class="h-full bg-gradient-to-r {{ $module['gradient'] }} w-full opacity-10 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                    </div>
                </a>
            @endforeach

        </div>

        <!-- Quick Links Bar (Secondary Tools) -->
        <div class="flex flex-wrap gap-4 items-center p-6 bg-slate-100/50 rounded-2xl border border-slate-200/50">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mr-2">System Config:</span>
            <a href="#"
                class="px-4 py-2 bg-white rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:shadow-md transition-all">
                <i class="fa-solid fa-gear mr-2"></i> Leave Types
            </a>
            <a href="#"
                class="px-4 py-2 bg-white rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:shadow-md transition-all">
                <i class="fa-solid fa-sliders mr-2"></i> DA Templates
            </a>
            <a href="#"
                class="px-4 py-2 bg-white rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:shadow-md transition-all">
                <i class="fa-solid fa-download mr-2"></i> Export Hub
            </a>
        </div>

    </div>

    <style>
        .premium-card {
            @apply bg-white border border-slate-200/60 rounded-[32px] shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col;
        }
    </style>
@endsection