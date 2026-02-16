@extends('layouts.app')
@php /** @var \App\Models\User $user */ @endphp

@section('title', 'My Profile')
@section('subtitle', 'Your personal and professional information')

@section('content')
    <div class="max-w-5xl mx-auto space-y-8 animate-fade-in-up">

        <!-- Profile Header Card -->
        <div class="premium-card p-0 overflow-hidden relative">
            <div class="h-48 bg-gradient-brand p-10 flex items-start justify-between relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-black/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">
                    <h1 class="text-4xl font-display font-bold text-white mb-2">My Profile</h1>
                    <p class="text-white/70 font-medium">Employee Portal ID: <span
                            class="text-white font-bold">{{ $employee->employee_code ?? '-' }}</span></p>
                </div>

                <div class="relative z-10 flex gap-3">
                    <span
                        class="px-4 py-2 bg-white/20 backdrop-blur-md rounded-xl text-white text-xs font-bold border border-white/30">
                        <i class="fa-solid fa-shield-check mr-2"></i> Verified Account
                    </span>
                </div>
            </div>

            <div class="px-10 pb-10 relative">
                <div class="flex flex-col md:flex-row gap-8 items-end -mt-16">
                    <!-- Avatar -->
                    <div class="w-40 h-40 rounded-3xl bg-white p-2 shadow-2xl relative z-20">
                        <div
                            class="w-full h-full rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-5xl font-black text-slate-400 border border-slate-100 uppercase">
                            {{ substr($employee->first_name ?? '?', 0, 1) }}{{ substr($employee->last_name ?? '', 0, 1) }}
                        </div>
                        <div class="absolute -right-2 -bottom-2 w-8 h-8 bg-green-500 border-4 border-white rounded-full shadow-lg"
                            title="Active"></div>
                    </div>

                    <div class="pb-2 flex-1">
                        <h2 class="text-3xl font-display font-bold text-premium">{{ $employee->first_name ?? 'User' }}
                            {{ $employee->last_name ?? '' }}</h2>
                        <p class="text-brand-dark font-bold flex items-center gap-2">
                            <i class="fa-solid fa-briefcase text-xs"></i>
                            {{ $employee->designation->designation_name ?? 'IQC Sense Staff' }}
                            <span class="text-slate-300">•</span>
                            <span
                                class="text-slate-400 font-medium">{{ $employee->department->department_name ?? 'Corporate' }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Left: Basic Info -->
            <div class="md:col-span-2 space-y-8">
                <div class="premium-card p-8">
                    <div class="flex items-center justify-between mb-8 border-b border-slate-50 pb-6">
                        <h3 class="text-lg font-display font-bold text-premium">Professional Details</h3>
                        <i class="fa-solid fa-id-card-clip text-slate-100 text-3xl"></i>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Employee
                                Name</span>
                            <p class="text-slate-700 font-bold">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Official
                                Email</span>
                            <p class="text-slate-700 font-bold break-all">
                                {{ $employee->employee_email ?? $user->user_email }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Job Title /
                                Designation</span>
                            <p class="text-slate-700 font-bold">{{ $employee->designation->designation_name ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <span
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Department</span>
                            <p class="text-slate-700 font-bold">{{ $employee->department->department_name ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Join
                                Date</span>
                            <p class="text-slate-700 font-bold">{{ $employee->join_date ?? 'N/A' }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Mobile
                                Number</span>
                            <p class="text-slate-700 font-bold">{{ $employee->mobile_nmbr ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-amber-50 rounded-3xl border border-amber-100 flex items-start gap-4">
                    <div
                        class="w-10 h-10 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-600 shrink-0">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-amber-900 mb-1">Data Correction Notice</h4>
                        <p class="text-xs text-amber-800/70 leading-relaxed">The information displayed above is pulled from
                            the official HR database. If you spot any inaccuracies in your profile, please submit a
                            <strong>Support Service Request</strong> addressed to the HR Department for correction.</p>
                    </div>
                </div>
            </div>

            <!-- Right: Stats & Quick Actions -->
            <div class="space-y-8">
                <div class="premium-card p-8 bg-slate-900 text-white relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                    <h3 class="text-sm font-bold text-white/50 uppercase tracking-widest mb-6 relative z-10">Account Status
                    </h3>

                    <div class="space-y-6 relative z-10">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-white/70">Member Since</span>
                            <span
                                class="font-bold text-white">{{ \Carbon\Carbon::parse($employee->join_date)->year }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-white/70">Login Status</span>
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-green-400"></span>
                                <span class="font-bold text-white text-xs">ONLINE</span>
                            </span>
                        </div>
                        <div class="pt-6 border-t border-white/10 mt-6">
                            <p class="text-[10px] text-white/40 leading-relaxed italic italic">"Commitment to excellence and
                                teamwork represents our core values at IQC Sense."</p>
                        </div>
                    </div>
                </div>

                <div class="premium-card p-6 flex flex-col gap-3">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 px-2">Navigation</h4>
                    <a href="{{ route('emp.requests.index') }}"
                        class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl hover:bg-brand-dark hover:text-white transition-all group">
                        <span class="text-sm font-bold">Request Center</span>
                        <i class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="{{ route('emp.messages.index') }}"
                        class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl hover:bg-brand-dark hover:text-white transition-all group">
                        <span class="text-sm font-bold">My Inbox</span>
                        <i class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>
@endsection
