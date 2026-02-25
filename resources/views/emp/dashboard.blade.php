@extends('layouts.app')

@section('title', 'My Dashboard')

@push('styles')
<style>
    /* ── Stat Cards ── */
    .emp-stat-card {
        border-radius: 22px;
        padding: 1.6rem 1.5rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.32s cubic-bezier(0.34,1.2,0.64,1), box-shadow 0.32s ease;
        cursor: default;
    }
    .emp-stat-card:hover {
        transform: translateY(-6px) scale(1.02);
    }
    /* Animated shine sweep */
    .emp-stat-card::after {
        content: '';
        position: absolute;
        top: -50%; left: -75%;
        width: 50%; height: 200%;
        background: rgba(255,255,255,0.13);
        transform: skewX(-20deg);
        transition: left 0.55s ease;
    }
    .emp-stat-card:hover::after { left: 130%; }

    /* 3D Icon Box */
    .stat-icon-3d {
        width: 56px; height: 56px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        position: relative;
        flex-shrink: 0;
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
    }
    .emp-stat-card:hover .stat-icon-3d {
        transform: scale(1.14) rotate(-6deg) translateY(-3px);
    }
    /* Gloss highlight on icon */
    .stat-icon-3d::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 48%;
        border-radius: 16px 16px 0 0;
        background: linear-gradient(180deg, rgba(255,255,255,0.45) 0%, transparent 100%);
        pointer-events: none;
    }

    /* ── Quick Link Cards ── */
    .quick-link-card {
        border-radius: 18px;
        padding: 1.4rem 1rem;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.34,1.2,0.64,1);
        position: relative; overflow: hidden;
        border: 1.5px solid rgba(255,255,255,0.18);
    }
    .quick-link-card:hover {
        transform: translateY(-5px) scale(1.04);
    }
    .quick-link-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 0.7rem;
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
        position: relative; overflow: hidden;
    }
    .quick-link-icon::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 50%;
        background: linear-gradient(180deg, rgba(255,255,255,0.5) 0%, transparent 100%);
        border-radius: 14px 14px 0 0;
        pointer-events: none;
    }
    .quick-link-card:hover .quick-link-icon {
        transform: scale(1.15) rotate(-5deg);
    }

    /* ── Tasks Table ── */
    .emp-tasks-panel {
        background: white;
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0,79,104,0.08), 0 1px 4px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,79,104,0.06);
    }
    .emp-tasks-header {
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #004F68 0%, #006a8a 60%, #1a8aaa 100%);
        display: flex; align-items: center; justify-content: space-between;
    }
    .emp-task-row {
        display: grid;
        grid-template-columns: 1fr auto auto;
        gap: 1rem;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s ease;
    }
    .emp-task-row:last-child { border-bottom: none; }
    .emp-task-row:hover { background: #f8fafc; }

    /* Badge pills */
    .badge-inprogress { background: linear-gradient(135deg,#fbbf24,#f59e0b); color:#fff; }
    .badge-pending    { background: linear-gradient(135deg,#94a3b8,#64748b); color:#fff; }
    .badge-done       { background: linear-gradient(135deg,#34d399,#10b981); color:#fff; }
    .badge-overdue    { background: linear-gradient(135deg,#f87171,#ef4444); color:#fff; }
    .task-badge {
        font-size: 10px; font-weight: 700;
        padding: 3px 10px; border-radius: 50px;
        letter-spacing: 0.04em; white-space: nowrap;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }

    /* Holiday countdown */
    .holiday-card {
        border-radius: 22px;
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        padding: 1.5rem;
        position: relative; overflow: hidden;
        box-shadow: 0 8px 32px rgba(0,0,0,0.25);
    }
    .holiday-card::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(251,191,36,0.15) 0%, transparent 70%);
    }
    .countdown-box {
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 14px;
        padding: 0.75rem 1rem;
        text-align: center;
        backdrop-filter: blur(6px);
    }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════
     STAT CARDS ROW
═══════════════════════════════════════════ --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    {{-- Leave Balance --}}
    <div class="emp-stat-card"
         style="background: linear-gradient(135deg,#0ea5e9 0%,#0284c7 50%,#0369a1 100%);
                box-shadow: 0 10px 40px rgba(14,165,233,0.35), 0 2px 8px rgba(0,0,0,0.1);">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sky-100 text-xs font-bold uppercase tracking-widest mb-2">Leave Balance</p>
                <div class="flex items-end gap-2">
                    <span class="text-5xl font-black text-white leading-none">14</span>
                    <span class="text-sm text-sky-200 font-semibold mb-1">/ 21 Days</span>
                </div>
                <div class="mt-3 h-1.5 rounded-full bg-white/20 overflow-hidden" style="width:120px;">
                    <div class="h-full rounded-full bg-white/70" style="width:66%;"></div>
                </div>
            </div>
            <div class="stat-icon-3d"
                 style="background: linear-gradient(145deg,rgba(255,255,255,0.28) 0%,rgba(255,255,255,0.1) 100%);
                        border: 1.5px solid rgba(255,255,255,0.3);
                        box-shadow: 0 6px 20px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.4);">
                <i class="fa-solid fa-umbrella-beach text-white"></i>
            </div>
        </div>
    </div>

    {{-- Pending Tasks --}}
    <div class="emp-stat-card"
         style="background: linear-gradient(135deg,#f59e0b 0%,#d97706 50%,#b45309 100%);
                box-shadow: 0 10px 40px rgba(245,158,11,0.35), 0 2px 8px rgba(0,0,0,0.1);">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-amber-100 text-xs font-bold uppercase tracking-widest mb-2">Pending Tasks</p>
                <div class="flex items-end gap-2">
                    <span class="text-5xl font-black text-white leading-none">{{ $pending_tasks ?? 3 }}</span>
                    <span class="text-sm text-amber-200 font-semibold mb-1">Due soon</span>
                </div>
                <p class="text-amber-200 text-xs mt-3 font-medium">⚡ Action needed</p>
            </div>
            <div class="stat-icon-3d"
                 style="background: linear-gradient(145deg,rgba(255,255,255,0.28) 0%,rgba(255,255,255,0.1) 100%);
                        border: 1.5px solid rgba(255,255,255,0.3);
                        box-shadow: 0 6px 20px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.4);">
                <i class="fa-solid fa-clock-rotate-left text-white"></i>
            </div>
        </div>
    </div>

    {{-- Messages --}}
    <div class="emp-stat-card"
         style="background: linear-gradient(135deg,#8b5cf6 0%,#7c3aed 50%,#6d28d9 100%);
                box-shadow: 0 10px 40px rgba(139,92,246,0.35), 0 2px 8px rgba(0,0,0,0.1);">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-violet-100 text-xs font-bold uppercase tracking-widest mb-2">Unread Messages</p>
                <div class="flex items-end gap-2">
                    <span class="text-5xl font-black text-white leading-none">2</span>
                    <span class="text-sm text-violet-200 font-semibold mb-1">New</span>
                </div>
                <p class="text-violet-200 text-xs mt-3 font-medium">💬 Tap to reply</p>
            </div>
            <div class="stat-icon-3d"
                 style="background: linear-gradient(145deg,rgba(255,255,255,0.28) 0%,rgba(255,255,255,0.1) 100%);
                        border: 1.5px solid rgba(255,255,255,0.3);
                        box-shadow: 0 6px 20px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.4);">
                <i class="fa-solid fa-envelope-open-text text-white"></i>
            </div>
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════════
     MAIN ROW: Tasks + Side Panel
═══════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Recent Tasks --}}
    <div class="lg:col-span-2 emp-tasks-panel">
        {{-- Header --}}
        <div class="emp-tasks-header">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                     style="background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.3);">
                    <i class="fa-solid fa-list-check text-white text-sm"></i>
                </div>
                <h3 class="font-bold text-white text-base tracking-wide">Recent Tasks</h3>
            </div>
            <a href="{{ route('emp.tasks.index') }}"
               class="flex items-center gap-2 px-4 py-2 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all duration-300 hover:-translate-y-0.5 group"
               style="background:rgba(255,255,255,0.18); color:#fff; border:1px solid rgba(255,255,255,0.3);
                      box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <span>View All</span>
                <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform text-[10px]"></i>
            </a>
        </div>

        {{-- Column headers --}}
        <div style="display:grid; grid-template-columns:1fr auto auto; gap:1rem; padding:0.65rem 1.5rem; background:#f8fafc; border-bottom:1px solid #e2e8f0;">
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Task</span>
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Deadline</span>
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Status</span>
        </div>

        {{-- Rows --}}
        <div class="emp-task-row">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                     style="background:linear-gradient(135deg,#fbbf24,#f59e0b);
                            box-shadow:0 3px 10px rgba(245,158,11,0.3),inset 0 1px 0 rgba(255,255,255,0.3);">
                    <i class="fa-solid fa-file-pen text-white text-xs"></i>
                </div>
                <span class="font-bold text-slate-700 text-sm">Update Q3 Report</span>
            </div>
            <span class="text-xs text-slate-500 font-medium">Tomorrow</span>
            <span class="task-badge badge-inprogress">In Progress</span>
        </div>

        <div class="emp-task-row">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                     style="background:linear-gradient(135deg,#94a3b8,#64748b);
                            box-shadow:0 3px 10px rgba(100,116,139,0.3),inset 0 1px 0 rgba(255,255,255,0.3);">
                    <i class="fa-solid fa-shield-halved text-white text-xs"></i>
                </div>
                <span class="font-bold text-slate-700 text-sm">Safety Training</span>
            </div>
            <span class="text-xs text-slate-500 font-medium">Feb 10, 2026</span>
            <span class="task-badge badge-pending">Pending</span>
        </div>

        <div class="emp-task-row">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                     style="background:linear-gradient(135deg,#34d399,#10b981);
                            box-shadow:0 3px 10px rgba(16,185,129,0.3),inset 0 1px 0 rgba(255,255,255,0.3);">
                    <i class="fa-solid fa-circle-check text-white text-xs"></i>
                </div>
                <span class="font-bold text-slate-700 text-sm">Audit Prep</span>
            </div>
            <span class="text-xs text-slate-500 font-medium">Feb 15, 2026</span>
            <span class="task-badge badge-done">Done</span>
        </div>
    </div>


    {{-- Side Panel --}}
    <div class="space-y-6">

        {{-- Quick Links --}}
        <div style="background:white; border-radius:22px; padding:1.4rem;
                    box-shadow:0 4px 24px rgba(0,79,104,0.08); border:1px solid rgba(0,79,104,0.05);">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                     style="background:linear-gradient(135deg,#004F68,#006a8a);
                            box-shadow:0 3px 8px rgba(0,79,104,0.3);">
                    <i class="fa-solid fa-bolt text-white text-xs"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm tracking-wide">Quick Links</h3>
            </div>

            <div class="grid grid-cols-2 gap-3">
                {{-- Apply Leave --}}
                <a href="{{ route('emp.leaves.index') }}" class="quick-link-card"
                   style="background:linear-gradient(135deg,#eff6ff 0%,#dbeafe 100%);
                          box-shadow:0 4px 16px rgba(59,130,246,0.12);">
                    <div class="quick-link-icon"
                         style="background:linear-gradient(145deg,#3b82f6,#2563eb);
                                box-shadow:0 6px 18px rgba(37,99,235,0.35),inset 0 1px 0 rgba(255,255,255,0.3);">
                        <i class="fa-solid fa-calendar-plus text-white"></i>
                    </div>
                    <span class="text-xs font-bold text-blue-700">Apply Leave</span>
                </a>

                {{-- Support --}}
                <a href="{{ route('emp.tickets.index') }}" class="quick-link-card"
                   style="background:linear-gradient(135deg,#fdf4ff 0%,#fae8ff 100%);
                          box-shadow:0 4px 16px rgba(168,85,247,0.12);">
                    <div class="quick-link-icon"
                         style="background:linear-gradient(145deg,#a855f7,#9333ea);
                                box-shadow:0 6px 18px rgba(147,51,234,0.35),inset 0 1px 0 rgba(255,255,255,0.3);">
                        <i class="fa-solid fa-headset text-white"></i>
                    </div>
                    <span class="text-xs font-bold text-purple-700">Support</span>
                </a>

                {{-- My Tasks --}}
                <a href="{{ route('emp.tasks.index') }}" class="quick-link-card"
                   style="background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 100%);
                          box-shadow:0 4px 16px rgba(16,185,129,0.12);">
                    <div class="quick-link-icon"
                         style="background:linear-gradient(145deg,#10b981,#059669);
                                box-shadow:0 6px 18px rgba(5,150,105,0.35),inset 0 1px 0 rgba(255,255,255,0.3);">
                        <i class="fa-solid fa-list-check text-white"></i>
                    </div>
                    <span class="text-xs font-bold text-emerald-700">My Tasks</span>
                </a>

                {{-- Calendar --}}
                <a href="{{ route('emp.calendar.index') }}" class="quick-link-card"
                   style="background:linear-gradient(135deg,#fff7ed 0%,#fed7aa 100%);
                          box-shadow:0 4px 16px rgba(249,115,22,0.12);">
                    <div class="quick-link-icon"
                         style="background:linear-gradient(145deg,#f97316,#ea580c);
                                box-shadow:0 6px 18px rgba(234,88,12,0.35),inset 0 1px 0 rgba(255,255,255,0.3);">
                        <i class="fa-solid fa-calendar-days text-white"></i>
                    </div>
                    <span class="text-xs font-bold text-orange-700">Calendar</span>
                </a>
            </div>
        </div>

        {{-- Holiday Countdown --}}
        <div class="holiday-card">
            <div class="flex items-center gap-4 mb-4 relative z-10">
                <div class="w-13 h-13 rounded-2xl flex items-center justify-center flex-shrink-0"
                     style="width:52px; height:52px;
                            background:linear-gradient(145deg,rgba(251,191,36,0.25),rgba(251,191,36,0.08));
                            border:1.5px solid rgba(251,191,36,0.35);
                            box-shadow:0 4px 16px rgba(0,0,0,0.2),inset 0 1px 0 rgba(255,255,255,0.12);">
                    <i class="fa-solid fa-star-and-crescent text-amber-400 text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-white text-base leading-none">Next Holiday</h4>
                    <p class="text-slate-400 text-xs mt-1">Eid Al-Fitr</p>
                </div>
            </div>
            <div class="countdown-box relative z-10">
                <span class="font-black text-2xl text-amber-400">14</span>
                <span class="text-sm text-slate-400 font-medium ml-1">Days Remaining</span>
                <div class="mt-2 h-1 rounded-full bg-white/10 overflow-hidden">
                    <div class="h-full rounded-full bg-amber-400/60" style="width:40%;"></div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
