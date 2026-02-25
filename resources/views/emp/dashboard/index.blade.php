@extends('layouts.app')

@section('title', 'My Dashboard')
@section('subtitle', 'Welcome back to IQC Sense Portal')

@push('styles')
<style>
    /* ── Welcome Banner ── */
    .welcome-banner {
        background: linear-gradient(135deg, #004F68 0%, #006a8a 45%, #1a8aaa 80%, #0ea5e9 100%);
        border-radius: 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 16px 48px rgba(0,79,104,0.3), 0 4px 12px rgba(0,0,0,0.1);
    }
    .welcome-banner::before {
        content: '';
        position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 280px; height: 280px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        pointer-events: none;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50%       { transform: translateY(-15px) rotate(2deg); }
    }
    .animate-float { animation: float 8s ease-in-out infinite; }

    @media (max-width: 768px) {
        .welcome-banner { border-radius: 20px; }
        .welcome-banner h1 { font-size: 2rem; }
    }

    /* ── 3D Stat Icon ── */
    .stat-icon-3d {
        width: 52px; height: 52px;
        border-radius: 15px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
        position: relative; overflow: hidden; flex-shrink: 0;
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
    }
    .stat-icon-3d::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 48%;
        background: linear-gradient(180deg, rgba(255,255,255,0.45) 0%, transparent 100%);
        border-radius: 15px 15px 0 0;
        pointer-events: none;
    }
    .stat-card:hover .stat-icon-3d {
        transform: scale(1.14) rotate(-6deg) translateY(-3px);
    }

    /* ── Upgraded stat-card ── */
    .stat-card {
        background: white;
        border-radius: 18px;
        padding: 1.25rem 1.5rem;
        border: 1.5px solid rgba(0,79,104,0.12);
        transition: all 0.28s cubic-bezier(0.34,1.2,0.64,1);
        display: block; text-decoration: none;
        box-shadow: 0 2px 8px rgba(0,79,104,0.06);
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 32px rgba(0,79,104,0.12);
    }

    /* ── Tab navigation — Icon Box Cards ── */
    .dash-tab-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 90px;
        padding: 0.85rem 0.5rem;
        border-radius: 18px;
        font-weight: 700;
        font-size: 0.65rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        transition: all 0.3s cubic-bezier(0.34, 1.3, 0.64, 1);
        cursor: pointer;
        border: 1.5px solid transparent;
        background: white;
        position: relative;
        overflow: hidden;
    }
    .dash-tab-btn:hover {
        transform: translateY(-4px) scale(1.04);
        box-shadow: 0 8px 24px rgba(0,79,104,0.15);
    }
    .dash-tab-btn .tab-icon-box {
        width: 44px; height: 44px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
        position: relative; overflow: hidden;
    }
    /* Gloss on icon box */
    .dash-tab-btn .tab-icon-box::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 48%;
        background: linear-gradient(180deg, rgba(255,255,255,0.45) 0%, transparent 100%);
        border-radius: 14px 14px 0 0;
        pointer-events: none;
    }
    .dash-tab-btn:hover .tab-icon-box {
        transform: scale(1.12) rotate(-5deg);
    }
    .dash-tab-btn.active-tab {
        border-color: rgba(0,79,104,0.2);
        box-shadow: 0 8px 28px rgba(0,79,104,0.18), inset 0 1px 0 rgba(255,255,255,0.9);
        transform: translateY(-2px);
    }
    .dash-tab-btn:not(.active-tab) {
        color: #006a8a;
        box-shadow: 0 2px 8px rgba(0,79,104,0.06);
        border-color: rgba(0,79,104,0.08);
    }

    /* ── Panel headers ── */
    .panel-header-gradient {
        background: linear-gradient(135deg, #004F68 0%, #006a8a 60%, #1a8aaa 100%);
        padding: 1.1rem 1.5rem;
        display: flex; align-items: center; justify-content: space-between;
    }

    /* ── Announcement card ── */
    .ann-card {
        background: linear-gradient(135deg, rgba(0,79,104,0.04) 0%, rgba(0,106,138,0.02) 100%);
        border-radius: 20px;
        border: 1.5px solid rgba(0,79,104,0.1);
        padding: 1.5rem;
        position: relative; overflow: hidden;
    }
    .ann-card::before {
        content: '';
        position: absolute;
        right: -20px; top: -20px;
        width: 120px; height: 120px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(0,79,104,0.06) 0%, transparent 70%);
    }

    /* ── HR stat rows ── */
    .hr-stat-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 1.25rem;
        border-radius: 16px;
        background: white;
        border: 1.5px solid rgba(0,79,104,0.1);
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .hr-stat-row:hover {
        border-color: #004F68;
        box-shadow: 0 4px 16px rgba(0,79,104,0.1);
        transform: translateX(4px);
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    {{-- ═══════════════════════════════════
         WELCOME BANNER
    ═══════════════════════════════════ --}}
    <div class="relative mb-8" style="padding-top: 2.5rem;">
        <div class="welcome-banner">
            <div class="flex flex-col justify-center h-full px-8 md:px-14 py-10 md:py-14 max-w-2xl relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full mb-4 w-fit"
                     style="background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.3);">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-white">Enterprise Portal</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-display font-extrabold text-white tracking-tight mb-3 drop-shadow-sm">
                    Hello, <span style="color:rgba(255,255,255,0.85);">{{ $employeeName }}</span> 👋
                </h1>
                <p class="text-sky-100 text-base md:text-lg font-medium max-w-md leading-relaxed">
                    Ready to start your day? Your dashboard is all set.
                </p>
            </div>
        </div>

        {{-- Floating character --}}
        <div class="absolute -right-4 md:right-8 bottom-0 w-36 md:w-60 lg:w-72 pointer-events-none drop-shadow-[0_20px_50px_rgba(0,0,0,0.18)] animate-float overflow-visible" style="top: 0; bottom: auto; display:flex; align-items:flex-end; height:100%;">
            <img src="{{ asset('images/char.png') }}" alt="Staff character" class="w-full h-auto object-contain">
        </div>
    </div>

    {{-- ═══════════════════════════════════
         DATE FILTER BAR
    ═══════════════════════════════════ --}}
    <div class="premium-card p-5 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-28 h-28 rounded-full -mr-14 -mt-14"
             style="background:radial-gradient(circle, rgba(0,79,104,0.05) 0%, transparent 70%);"></div>
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 relative z-10">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Hello, <span class="text-brand-dark">{{ $employeeName }}</span></h2>
                <p class="text-teal-700 mt-1 flex items-center gap-2 text-sm">
                    <i class="fa-regular fa-calendar-check text-brand-dark"></i>
                    {{ now()->format('l, jS F Y') }}
                </p>
            </div>
            <div class="flex items-center gap-3">

                {{-- Today --}}
                <a href="?mode=today"
                   class="relative overflow-hidden flex flex-col items-center gap-1.5 py-3 rounded-2xl transition-all duration-300 group hover:-translate-y-1 text-center"
                   style="width:6.5rem; {{ $mode == 'today'
                        ? 'background:linear-gradient(135deg,#004F68,#006a8a); box-shadow:0 6px 20px rgba(0,79,104,0.3); border:1.5px solid rgba(255,255,255,0.1);'
                        : 'background:linear-gradient(135deg,#e0f2fe,#bae6fd); border:1.5px solid rgba(0,79,104,0.12); box-shadow:0 3px 10px rgba(0,79,104,0.08);' }}">
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-400"
                         style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.2) 50%,transparent 70%);"></div>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                         style="{{ $mode == 'today'
                            ? 'background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3); box-shadow:inset 0 1px 0 rgba(255,255,255,0.3);'
                            : 'background:linear-gradient(145deg,#0ea5e9,#0284c7); box-shadow:0 4px 12px rgba(14,165,233,0.35),inset 0 1px 0 rgba(255,255,255,0.3);' }}">
                        <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);"></div>
                        <i class="fa-solid fa-sun text-white text-sm relative z-10"></i>
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-widest {{ $mode == 'today' ? 'text-white' : 'text-teal-700' }}">Today</span>
                </a>

                {{-- This Week --}}
                <a href="?mode=this_week"
                   class="relative overflow-hidden flex flex-col items-center gap-1.5 py-3 rounded-2xl transition-all duration-300 group hover:-translate-y-1 text-center"
                   style="width:6.5rem; {{ $mode == 'this_week'
                        ? 'background:linear-gradient(135deg,#004F68,#006a8a); box-shadow:0 6px 20px rgba(0,79,104,0.3); border:1.5px solid rgba(255,255,255,0.1);'
                        : 'background:linear-gradient(135deg,#f5f3ff,#ede9fe); border:1.5px solid rgba(139,92,246,0.15); box-shadow:0 3px 10px rgba(139,92,246,0.08);' }}">
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-400"
                         style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.2) 50%,transparent 70%);"></div>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                         style="{{ $mode == 'this_week'
                            ? 'background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3); box-shadow:inset 0 1px 0 rgba(255,255,255,0.3);'
                            : 'background:linear-gradient(145deg,#8b5cf6,#7c3aed); box-shadow:0 4px 12px rgba(139,92,246,0.35),inset 0 1px 0 rgba(255,255,255,0.3);' }}">
                        <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);"></div>
                        <i class="fa-solid fa-calendar-week text-white text-sm relative z-10"></i>
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-widest {{ $mode == 'this_week' ? 'text-white' : 'text-violet-700' }}">This Week</span>
                </a>

                {{-- This Month --}}
                <a href="?mode=this_month"
                   class="relative overflow-hidden flex flex-col items-center gap-1.5 py-3 rounded-2xl transition-all duration-300 group hover:-translate-y-1 text-center"
                   style="width:6.5rem; {{ $mode == 'this_month'
                        ? 'background:linear-gradient(135deg,#004F68,#006a8a); box-shadow:0 6px 20px rgba(0,79,104,0.3); border:1.5px solid rgba(255,255,255,0.1);'
                        : 'background:linear-gradient(135deg,#f0fdf4,#dcfce7); border:1.5px solid rgba(16,185,129,0.15); box-shadow:0 3px 10px rgba(16,185,129,0.08);' }}">
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-400"
                         style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.2) 50%,transparent 70%);"></div>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                         style="{{ $mode == 'this_month'
                            ? 'background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3); box-shadow:inset 0 1px 0 rgba(255,255,255,0.3);'
                            : 'background:linear-gradient(145deg,#10b981,#059669); box-shadow:0 4px 12px rgba(16,185,129,0.35),inset 0 1px 0 rgba(255,255,255,0.3);' }}">
                        <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);"></div>
                        <i class="fa-solid fa-calendar-days text-white text-sm relative z-10"></i>
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-widest {{ $mode == 'this_month' ? 'text-white' : 'text-emerald-700' }}">This Month</span>
                </a>

            </div>


        </div>
    </div>

    {{-- ═══════════════════════════════════
         MAIN LAYOUT: CONTENT + RIGHT SIDEBAR
    ═══════════════════════════════════ --}}
    <div x-data="{ activeTab: 'tickets' }" class="space-y-6">

        {{-- ═══════════════════════════════════
             ANNOUNCEMENTS CAROUSEL
        ═══════════════════════════════════ --}}
        @if($announcements->count() > 0)
        <div class="ann-card">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform duration-700">
                <i class="fa-solid fa-bullhorn text-9xl text-brand-dark"></i>
            </div>
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                     style="background:linear-gradient(135deg,#004F68,#006a8a);
                            box-shadow:0 4px 14px rgba(0,79,104,0.3),inset 0 1px 0 rgba(255,255,255,0.2);">
                    <i class="fa-solid fa-bullhorn rotate-[-15deg] text-white text-sm"></i>
                </div>
                <h3 class="text-lg font-display font-bold text-premium">Recent Announcements</h3>
            </div>

            <div class="min-h-[120px] relative">
                @foreach($announcements as $index => $ann)
                    <div class="announcement-slide transition-all duration-500 transform {{ $index === 0 ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10 absolute inset-0 pointer-events-none' }}"
                         id="ann-{{ $index }}">
                        <h4 class="text-xl font-bold text-brand-dark mb-1">{{ $ann->document_title }}</h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-dark inline-block"></span>
                            Posted on {{ \Carbon\Carbon::parse($ann->added_date)->format('M d, Y') }}
                        </p>
                        <p class="text-slate-600 leading-relaxed">{{ Str::limit($ann->document_description, 200) }}</p>
                    </div>
                @endforeach
            </div>

            @if($announcements->count() > 1)
            <div class="flex items-center gap-3 mt-6">
                <button onclick="prevAnn()"
                    class="w-9 h-9 rounded-full border border-slate-200 hover:border-brand-dark hover:text-brand-dark bg-white shadow-sm flex items-center justify-center transition-all text-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <div class="flex gap-1.5" id="ann-dots">
                    @foreach($announcements as $index => $ann)
                        <div class="h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'w-4 bg-brand-dark' : 'w-2 bg-slate-200' }}"
                             id="ann-dot-{{ $index }}"></div>
                    @endforeach
                </div>
                <button onclick="nextAnn()"
                    class="w-9 h-9 rounded-full border border-slate-200 hover:border-brand-dark hover:text-brand-dark bg-white shadow-sm flex items-center justify-center transition-all text-sm">
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
            @endif
        </div>
        @endif

        {{-- ═══════════════════════════════════
             HORIZONTAL TAB NAV (RIGHT ALIGNED)
        ═══════════════════════════════════ --}}
        <div class="flex flex-wrap items-center justify-end gap-3 w-full">
            {{-- IT Support --}}
            <button @click="activeTab = 'tickets'"
                class="dash-tab-btn"
                :class="activeTab === 'tickets' ? 'active-tab' : ''"
                :style="activeTab === 'tickets' ? 'background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#1d4ed8;' : 'color:#1d4ed8;'">
                <div class="tab-icon-box"
                     :style="activeTab === 'tickets'
                        ? 'background:linear-gradient(145deg,#3b82f6,#2563eb); box-shadow:0 6px 18px rgba(37,99,235,0.4),inset 0 1px 0 rgba(255,255,255,0.3); color:#fff;'
                        : 'background:linear-gradient(145deg,#eff6ff,#bfdbfe); color:#3b82f6; box-shadow:0 3px 8px rgba(37,99,235,0.15);'">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <span>IT Support</span>
            </button>

            {{-- My Tasks --}}
            <button @click="activeTab = 'tasks'"
                class="dash-tab-btn"
                :class="activeTab === 'tasks' ? 'active-tab' : ''"
                :style="activeTab === 'tasks' ? 'background:linear-gradient(135deg,#f5f3ff,#ede9fe); color:#7c3aed;' : 'color:#7c3aed;'">
                <div class="tab-icon-box"
                     :style="activeTab === 'tasks'
                        ? 'background:linear-gradient(145deg,#8b5cf6,#7c3aed); box-shadow:0 6px 18px rgba(124,58,237,0.4),inset 0 1px 0 rgba(255,255,255,0.3); color:#fff;'
                        : 'background:linear-gradient(145deg,#f5f3ff,#ddd6fe); color:#8b5cf6; box-shadow:0 3px 8px rgba(124,58,237,0.15);'">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <span>My Tasks</span>
            </button>

            {{-- Assets --}}
            <button @click="activeTab = 'assets'"
                class="dash-tab-btn"
                :class="activeTab === 'assets' ? 'active-tab' : ''"
                :style="activeTab === 'assets' ? 'background:linear-gradient(135deg,#fff7ed,#fed7aa); color:#c2410c;' : 'color:#c2410c;'">
                <div class="tab-icon-box"
                     :style="activeTab === 'assets'
                        ? 'background:linear-gradient(145deg,#f97316,#ea580c); box-shadow:0 6px 18px rgba(234,88,12,0.4),inset 0 1px 0 rgba(255,255,255,0.3); color:#fff;'
                        : 'background:linear-gradient(145deg,#fff7ed,#fed7aa); color:#f97316; box-shadow:0 3px 8px rgba(234,88,12,0.15);'">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
                <span>Assets</span>
            </button>

            {{-- HR & Leaves --}}
            <button @click="activeTab = 'hr'"
                class="dash-tab-btn"
                :class="activeTab === 'hr' ? 'active-tab' : ''"
                :style="activeTab === 'hr' ? 'background:linear-gradient(135deg,#f0fdf4,#dcfce7); color:#15803d;' : 'color:#15803d;'">
                <div class="tab-icon-box"
                     :style="activeTab === 'hr'
                        ? 'background:linear-gradient(145deg,#22c55e,#16a34a); box-shadow:0 6px 18px rgba(22,163,74,0.4),inset 0 1px 0 rgba(255,255,255,0.3); color:#fff;'
                        : 'background:linear-gradient(145deg,#f0fdf4,#bbf7d0); color:#22c55e; box-shadow:0 3px 8px rgba(22,163,74,0.15);'">
                    <i class="fa-solid fa-umbrella-beach"></i>
                </div>
                <span>HR &amp; Leaves</span>
            </button>
        </div>

        {{-- ═══════════════════════════════════
             TAB CONTENT SECTIONS
        ═══════════════════════════════════ --}}
        <div class="space-y-6">

        {{-- ── Tab: IT Support ── --}}
        <div x-show="activeTab === 'tickets'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                <div class="panel-header-gradient">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-chart-pie"></i> Support Matrix
                    </h3>
                    <a href="{{ route('emp.tickets.index') }}"
                       class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold transition-all hover:-translate-y-0.5 group"
                       style="background:rgba(255,255,255,0.18); color:#fff; border:1px solid rgba(255,255,255,0.3);">
                        <span>View Portal</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform text-[10px]"></i>
                    </a>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                        {{-- Total Tickets --}}
                        <a href="{{ route('emp.tickets.index') }}"
                           class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                           style="background:linear-gradient(135deg,#eff6ff,#dbeafe);
                                  border:1.5px solid rgba(37,99,235,0.15);
                                  box-shadow:0 4px 16px rgba(37,99,235,0.1);">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                 style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                            {{-- 3D Icon Box --}}
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                 style="background:linear-gradient(145deg,#3b82f6,#2563eb);
                                        box-shadow:0 8px 22px rgba(37,99,235,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                <i class="fa-solid fa-ticket text-white text-xl relative z-10"></i>
                            </div>
                            {{-- Big Number --}}
                            <h3 class="text-4xl font-black leading-none count" style="color:#2563eb;" data-target="{{ $ticketStats->total }}">0</h3>
                            {{-- Label --}}
                            <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#3b82f6;">Total Tickets</p>
                        </a>

                        {{-- Unassigned --}}
                        <a href="{{ route('emp.tickets.index', ['stt' => 4]) }}"
                           class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                           style="background:linear-gradient(135deg,#fffbeb,#fef3c7);
                                  border:1.5px solid rgba(245,158,11,0.2);
                                  box-shadow:0 4px 16px rgba(245,158,11,0.12);">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                 style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                 style="background:linear-gradient(145deg,#f59e0b,#d97706);
                                        box-shadow:0 8px 22px rgba(245,158,11,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                <i class="fa-solid fa-user-slash text-white text-xl relative z-10"></i>
                            </div>
                            <h3 class="text-4xl font-black leading-none count" style="color:#d97706;" data-target="{{ $ticketStats->unassigned }}">0</h3>
                            <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#f59e0b;">Unassigned</p>
                        </a>

                        {{-- In Progress --}}
                        <a href="{{ route('emp.tickets.index', ['stt' => 2]) }}"
                           class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                           style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe);
                                  border:1.5px solid rgba(14,165,233,0.2);
                                  box-shadow:0 4px 16px rgba(14,165,233,0.12);">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                 style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                 style="background:linear-gradient(145deg,#0ea5e9,#0284c7);
                                        box-shadow:0 8px 22px rgba(14,165,233,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                <i class="fa-solid fa-spinner fa-spin-pulse text-white text-xl relative z-10"></i>
                            </div>
                            <h3 class="text-4xl font-black leading-none count" style="color:#0284c7;" data-target="{{ $ticketStats->progress }}">0</h3>
                            <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#0ea5e9;">In Progress</p>
                        </a>

                        {{-- Resolved --}}
                        <a href="{{ route('emp.tickets.index', ['stt' => 3]) }}"
                           class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                           style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);
                                  border:1.5px solid rgba(16,185,129,0.2);
                                  box-shadow:0 4px 16px rgba(16,185,129,0.12);">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                 style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                 style="background:linear-gradient(145deg,#10b981,#059669);
                                        box-shadow:0 8px 22px rgba(16,185,129,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                <i class="fa-solid fa-check-double text-white text-xl relative z-10"></i>
                            </div>
                            <h3 class="text-4xl font-black leading-none count" style="color:#059669;" data-target="{{ $ticketStats->resolved }}">0</h3>
                            <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#10b981;">Resolved</p>
                        </a>

                    </div>
                </div>
            </div>
        </div>


        {{-- ── Tab: Tasks ── --}}
        <div x-show="activeTab === 'tasks'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="space-y-5">
                {{-- Task stat cards --}}
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                    <div class="panel-header-gradient">
                        <h3 class="font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-chart-column"></i> Tasks Matrix
                        </h3>
                        <a href="{{ route('emp.tasks.index') }}"
                           class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold transition-all group"
                           style="background:rgba(255,255,255,0.18); color:#fff; border:1px solid rgba(255,255,255,0.3);">
                            <span>Go to Tasks</span>
                            <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform text-[10px]"></i>
                        </a>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                            {{-- Total Tasks --}}
                            <a href="{{ route('emp.tasks.index') }}"
                               class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                               style="background:linear-gradient(135deg,#f5f3ff,#ede9fe);
                                      border:1.5px solid rgba(139,92,246,0.18);
                                      box-shadow:0 4px 16px rgba(139,92,246,0.12);">
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                     style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                     style="background:linear-gradient(145deg,#8b5cf6,#7c3aed);
                                            box-shadow:0 8px 22px rgba(139,92,246,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                    <i class="fa-solid fa-layer-group text-white text-xl relative z-10"></i>
                                </div>
                                <h3 class="text-4xl font-black leading-none count" style="color:#7c3aed;" data-target="{{ $taskStats['total'] }}">0</h3>
                                <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#8b5cf6;">Total Tasks</p>
                            </a>

                            {{-- To Do --}}
                            <a href="{{ route('emp.tasks.index') }}"
                               class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                               style="background:linear-gradient(135deg,#fffbeb,#fef3c7);
                                      border:1.5px solid rgba(245,158,11,0.2);
                                      box-shadow:0 4px 16px rgba(245,158,11,0.12);">
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                     style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                     style="background:linear-gradient(145deg,#f59e0b,#d97706);
                                            box-shadow:0 8px 22px rgba(245,158,11,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                    <i class="fa-solid fa-list-ul text-white text-xl relative z-10"></i>
                                </div>
                                <h3 class="text-4xl font-black leading-none count" style="color:#d97706;" data-target="{{ $taskStats['todo'] }}">0</h3>
                                <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#f59e0b;">To Do</p>
                            </a>

                            {{-- Overdue or In Progress --}}
                            @if($taskStats['overdue'] > 0)
                            <a href="{{ route('emp.tasks.index') }}"
                               class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                               style="background:linear-gradient(135deg,#fff1f2,#ffe4e6);
                                      border:1.5px solid rgba(244,63,94,0.2);
                                      box-shadow:0 4px 16px rgba(244,63,94,0.12);">
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                     style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                     style="background:linear-gradient(145deg,#f43f5e,#e11d48);
                                            box-shadow:0 8px 22px rgba(244,63,94,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                    <i class="fa-solid fa-triangle-exclamation text-white text-xl relative z-10"></i>
                                </div>
                                <h3 class="text-4xl font-black leading-none count" style="color:#e11d48;" data-target="{{ $taskStats['overdue'] }}">0</h3>
                                <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#f43f5e;">Overdue</p>
                            </a>
                            @else
                            <a href="{{ route('emp.tasks.index') }}"
                               class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                               style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe);
                                      border:1.5px solid rgba(14,165,233,0.2);
                                      box-shadow:0 4px 16px rgba(14,165,233,0.12);">
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                     style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                     style="background:linear-gradient(145deg,#0ea5e9,#0284c7);
                                            box-shadow:0 8px 22px rgba(14,165,233,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                    <i class="fa-solid fa-spinner fa-spin-pulse text-white text-xl relative z-10"></i>
                                </div>
                                <h3 class="text-4xl font-black leading-none count" style="color:#0284c7;" data-target="{{ $taskStats['progress'] }}">0</h3>
                                <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#0ea5e9;">In Progress</p>
                            </a>
                            @endif

                            {{-- Completed --}}
                            <a href="{{ route('emp.tasks.index') }}"
                               class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                               style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);
                                      border:1.5px solid rgba(16,185,129,0.2);
                                      box-shadow:0 4px 16px rgba(16,185,129,0.12);">
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                     style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                                     style="background:linear-gradient(145deg,#10b981,#059669);
                                            box-shadow:0 8px 22px rgba(16,185,129,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                    <i class="fa-solid fa-circle-check text-white text-xl relative z-10"></i>
                                </div>
                                <h3 class="text-4xl font-black leading-none count" style="color:#059669;" data-target="{{ $taskStats['done'] }}">0</h3>
                                <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#10b981;">Completed</p>
                            </a>

                        </div>
                    </div>
                </div>

                {{-- Recent Tasks Table --}}
                <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(139,92,246,0.1); border:1.5px solid rgba(139,92,246,0.12);">
                    <div class="panel-header-gradient" style="background:linear-gradient(135deg,#8b5cf6 0%,#7c3aed 60%,#6d28d9 100%);">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-list-check text-white"></i>
                            <h3 class="text-base font-bold text-white">Recent Activities</h3>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="premium-table w-full">
                            <thead>
                                <tr>
                                    <th class="text-left font-bold text-teal-700">Task</th>
                                    <th class="text-center font-bold text-teal-700">Status</th>
                                    <th class="text-left font-bold text-teal-700">Due</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-violet-50">
                                @forelse($recentTasks as $task)
                                <tr class="hover:bg-violet-50/40 transition-colors cursor-pointer"
                                    onclick="window.location='{{ route('emp.tasks.index') }}'">
                                    <td>
                                        <div class="font-bold text-teal-900 truncate max-w-[200px]">{{ $task->task_title }}</div>
                                        <div class="text-[10px] text-teal-600 uppercase">{{ $task->priority->priority_name ?? 'Normal' }} Priority</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-black text-white uppercase tracking-wider shadow-sm"
                                              style="background:#{{ $task->status->status_color ?? '999' }}">
                                            {{ $task->status->status_name ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-xs font-semibold text-teal-700">
                                            {{ $task->task_due_date ? $task->task_due_date->format('M d') : '-' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-10 text-teal-500 font-medium">No tasks found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Tab: Assets ── --}}
        <div x-show="activeTab === 'assets'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                <div class="panel-header-gradient">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-laptop-code"></i> Assigned Assets
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="premium-table w-full">
                        <thead>
                            <tr>
                                <th class="text-left font-bold text-slate-400">REF</th>
                                <th class="text-left font-bold text-slate-400">Name</th>
                                <th class="text-left font-bold text-slate-400">Assigned By</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($assets as $asset)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td>
                                    <span class="font-mono text-xs font-bold px-2 py-1 rounded-lg"
                                          style="background:linear-gradient(135deg,rgba(0,79,104,0.08),rgba(0,106,138,0.05)); color:#004F68;">
                                        {{ $asset->asset_ref }}
                                    </span>
                                </td>
                                <td>
                                    <div class="font-bold text-slate-700">{{ $asset->asset_name }}</div>
                                    <div class="text-[10px] text-slate-400 uppercase tracking-tighter">{{ $asset->asset_sku }}</div>
                                </td>
                                <td>
                                    <span class="text-sm font-semibold text-slate-600">
                                        {{ $asset->assignedBy ? $asset->assignedBy->first_name : 'System' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-12">
                                    <i class="fa-solid fa-box-open text-4xl text-slate-200 block mb-3"></i>
                                    <p class="text-slate-400 font-medium">No assets assigned yet</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── Tab: HR & Leaves ── --}}
        <div x-show="activeTab === 'hr'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                    <div class="panel-header-gradient">
                        <h3 class="font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-umbrella-beach"></i> HR Summary
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        {{-- Total Requests card --}}
                        <a href="{{ route('emp.leaves.index') }}"
                           class="relative overflow-hidden rounded-2xl p-4 flex items-center justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                           style="background:linear-gradient(135deg,#004F68 0%,#006a8a 60%,#1a8aaa 100%);
                                  box-shadow:0 6px 20px rgba(0,79,104,0.3);">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                 style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.08) 50%,transparent 70%);"></div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0"
                                     style="background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.3);
                                            box-shadow:0 4px 12px rgba(0,0,0,0.15),inset 0 1px 0 rgba(255,255,255,0.3);">
                                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.25);"></div>
                                    <i class="fa-solid fa-paper-plane text-white relative z-10 text-sm"></i>
                                </div>
                                <span class="font-bold text-white">Total Requests</span>
                            </div>
                            <span class="text-3xl font-black text-white count" data-target="{{ $hrStats['requests'] }}">0</span>
                        </a>
                        {{-- Pending Approval card --}}
                        <a href="{{ route('emp.leaves.index', ['status' => 2]) }}"
                           class="relative overflow-hidden rounded-2xl p-4 flex items-center justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                           style="background:linear-gradient(135deg,#f59e0b 0%,#d97706 60%,#b45309 100%);
                                  box-shadow:0 6px 20px rgba(245,158,11,0.3);">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                 style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.08) 50%,transparent 70%);"></div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center relative overflow-hidden flex-shrink-0"
                                     style="background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.3);
                                            box-shadow:0 4px 12px rgba(0,0,0,0.15),inset 0 1px 0 rgba(255,255,255,0.3);">
                                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.25);"></div>
                                    <i class="fa-solid fa-clock-rotate-left text-white relative z-10 text-sm"></i>
                                </div>
                                <span class="font-bold text-slate-700">Pending Approval</span>
                            </div>
                            <span class="text-2xl font-black text-amber-600 count" data-target="{{ $hrStats['pending_approval'] }}">0</span>
                        </a>
                        <div class="pt-2">
                            <a href="{{ route('emp.leaves.index') }}"
                               class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl font-bold text-white shadow-lg transition-all hover:-translate-y-1"
                               style="background:linear-gradient(135deg,#004F68,#006a8a);
                                      box-shadow:0 6px 20px rgba(0,79,104,0.3);">
                                Request a Leave
                                <i class="fa-solid fa-plus-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border-2 border-dashed border-slate-200 flex items-center justify-center text-center p-8">
                    <div>
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                             style="background:linear-gradient(135deg,rgba(0,79,104,0.08),rgba(0,106,138,0.04));">
                            <i class="fa-solid fa-chart-line text-2xl text-brand-dark opacity-50"></i>
                        </div>
                        <h4 class="font-bold text-slate-500">More Insights Coming Soon</h4>
                        <p class="text-xs text-slate-400 mt-1">Personalized performance metrics will appear here.</p>
                    </div>
                </div>
            </div>
        </div>

            </div>{{-- end space-y-6 (tab content) --}}
    </div>{{-- end x-data tabs (grid container) --}}

<script>
    // Counter Animation
    document.querySelectorAll('.count').forEach(el => {
        const target = parseInt(el.getAttribute('data-target'));
        if (isNaN(target)) return;
        let count = 0;
        const inc = Math.max(1, target / 30);
        if (target >= 0) {
            const timer = setInterval(() => {
                count += inc;
                if (count >= target) { el.innerText = target; clearInterval(timer); }
                else { el.innerText = Math.ceil(count); }
            }, 30);
        }
    });

    // Carousel Logic
    let curAnn = 0;
    const slides = document.querySelectorAll('.announcement-slide');
    const dots = document.querySelectorAll('#ann-dots > div');
    const totalSlides = slides.length;

    function showAnn(index) {
        slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.remove('opacity-0','translate-y-10','pointer-events-none','absolute','inset-0');
                slide.classList.add('opacity-100','translate-y-0');
            } else {
                slide.classList.add('opacity-0','translate-y-10','pointer-events-none','absolute','inset-0');
                slide.classList.remove('opacity-100','translate-y-0');
            }
        });
        dots.forEach((dot, i) => {
            if (i === index) { dot.classList.add('bg-brand-dark','w-4'); dot.classList.remove('bg-slate-200','w-2'); }
            else { dot.classList.remove('bg-brand-dark','w-4'); dot.classList.add('bg-slate-200','w-2'); }
        });
    }
    function nextAnn() { curAnn = (curAnn + 1) % totalSlides; showAnn(curAnn); }
    function prevAnn() { curAnn = (curAnn - 1 + totalSlides) % totalSlides; showAnn(curAnn); }
</script>

@endsection