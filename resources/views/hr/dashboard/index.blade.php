@extends('layouts.app')

@section('title', 'HR Dashboard')
@section('subtitle', 'Overview of your workforce')

@push('styles')
<style>
    /* ── Welcome Banner ── */
    .hr-welcome-banner {
        background: linear-gradient(135deg, #004F68 0%, #006a8a 45%, #1a8aaa 80%, #0ea5e9 100%);
        border-radius: 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 16px 48px rgba(0,79,104,0.3), 0 4px 12px rgba(0,0,0,0.1);
        min-height: 180px;
    }
    .hr-welcome-banner::before {
        content: '';
        position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }
    .hr-welcome-banner::after {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 280px; height: 280px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        pointer-events: none;
    }

    /* ── Tab icon-box cards ── */
    .hr-tab-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 115px;
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
    .hr-tab-btn:hover {
        transform: translateY(-4px) scale(1.04);
        box-shadow: 0 8px 24px rgba(0,79,104,0.15);
    }
    .hr-tab-btn .tab-icon-box {
        width: 44px; height: 44px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
        position: relative; overflow: hidden;
    }
    .hr-tab-btn .tab-icon-box::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 48%;
        background: linear-gradient(180deg, rgba(255,255,255,0.45) 0%, transparent 100%);
        border-radius: 14px 14px 0 0;
        pointer-events: none;
    }
    .hr-tab-btn:hover .tab-icon-box {
        transform: scale(1.12) rotate(-5deg);
    }
    .hr-tab-btn.active-tab {
        border-color: rgba(0,79,104,0.2);
        box-shadow: 0 8px 28px rgba(0,79,104,0.18), inset 0 1px 0 rgba(255,255,255,0.9);
        transform: translateY(-2px);
    }

    /* ── Gradient stat card ── */
    .grad-stat-card {
        border-radius: 22px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.34,1.2,0.64,1);
        text-decoration: none;
    }
    .grad-stat-card:hover {
        transform: translateY(-6px);
    }
    .grad-stat-card .stat-icon-box {
        width: 56px; height: 56px;
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        position: relative; overflow: hidden;
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
        flex-shrink: 0;
    }
    .grad-stat-card:hover .stat-icon-box {
        transform: scale(1.12) rotate(-5deg);
    }
    .grad-stat-card .stat-icon-box::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 48%;
        background: linear-gradient(180deg, rgba(255,255,255,0.35) 0%, transparent 100%);
        border-radius: 18px 18px 0 0;
    }

    /* ── Panel header ── */
    .panel-header-gradient {
        background: linear-gradient(135deg, #004F68 0%, #006a8a 60%, #1a8aaa 100%);
        padding: 1.1rem 1.5rem;
        display: flex; align-items: center; justify-content: space-between;
    }

    /* ── Quick action card ── */
    .quick-action-card {
        display: flex; align-items: center; gap: 1rem;
        padding: 1.1rem 1.25rem;
        border-radius: 20px;
        text-decoration: none;
        position: relative; overflow: hidden;
        transition: all 0.3s cubic-bezier(0.34,1.2,0.64,1);
    }
    .quick-action-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.18);
    }
    .quick-action-card .qa-icon {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        position: relative; overflow: hidden;
    }
    .quick-action-card .qa-icon::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 48%;
        background: linear-gradient(180deg, rgba(255,255,255,0.35) 0%, transparent 100%);
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    {{-- ═══════════════════════════════════
         WELCOME BANNER
    ═══════════════════════════════════ --}}
    <div class="hr-welcome-banner">
        <div class="flex flex-col justify-center h-full px-8 md:px-14 py-10 md:py-12 relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full mb-4 w-fit"
                 style="background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.3);">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                </span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-white">HR Command Centre</span>
            </div>
            <div class="flex items-center gap-4 mb-2">
                {{-- 3D Icon Box --}}
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0"
                     style="background:linear-gradient(145deg,rgba(255,255,255,0.28),rgba(255,255,255,0.1));
                            border:1.5px solid rgba(255,255,255,0.35);
                            box-shadow:0 8px 24px rgba(0,0,0,0.18), inset 0 1px 0 rgba(255,255,255,0.5);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.35);"></div>
                    <i class="fa-solid fa-users text-white text-2xl relative z-10 drop-shadow-sm"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-display font-extrabold text-white tracking-tight drop-shadow-sm">
                    Workforce Overview
                </h1>
            </div>
            <p class="text-sky-100 text-sm md:text-base font-medium max-w-md leading-relaxed">
                Monitor your team, demographics, skills and HR actions — all in one place.
            </p>
        </div>
        {{-- Big 3D HR icon cluster (right side) --}}
        <div class="absolute right-8 md:right-16 top-1/2 -translate-y-1/2 pointer-events-none hidden md:flex items-center justify-center" style="width:220px; height:180px;">

            {{-- Main large icon --}}
            <div class="absolute" style="width:90px; height:90px; top:50%; left:50%; transform:translate(-50%,-55%);">
                <div class="w-full h-full rounded-3xl flex items-center justify-center relative overflow-hidden"
                     style="background:linear-gradient(145deg, rgba(255,255,255,0.28) 0%, rgba(255,255,255,0.08) 100%);
                            border:1.5px solid rgba(255,255,255,0.4);
                            box-shadow:0 16px 48px rgba(0,0,0,0.2), inset 0 2px 0 rgba(255,255,255,0.5);
                            animation: float 4s ease-in-out infinite;">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-3xl" style="background:rgba(255,255,255,0.3);"></div>
                    <i class="fa-solid fa-users text-white relative z-10" style="font-size:2.4rem; filter:drop-shadow(0 4px 8px rgba(0,0,0,0.2));"></i>
                </div>
            </div>

            {{-- Top-right: chart icon --}}
            <div class="absolute" style="width:52px; height:52px; top:0; right:10px; animation: float 4s ease-in-out infinite; animation-delay:0.6s;">
                <div class="w-full h-full rounded-2xl flex items-center justify-center relative overflow-hidden"
                     style="background:linear-gradient(145deg,rgba(255,255,255,0.22) 0%,rgba(255,255,255,0.06) 100%);
                            border:1.5px solid rgba(255,255,255,0.3);
                            box-shadow:0 8px 24px rgba(0,0,0,0.15),inset 0 1px 0 rgba(255,255,255,0.45);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                    <i class="fa-solid fa-chart-pie text-white relative z-10" style="font-size:1.2rem; filter:drop-shadow(0 2px 4px rgba(0,0,0,0.2));"></i>
                </div>
            </div>

            {{-- Bottom-left: briefcase icon --}}
            <div class="absolute" style="width:52px; height:52px; bottom:0; left:0; animation: float 4s ease-in-out infinite; animation-delay:1.2s;">
                <div class="w-full h-full rounded-2xl flex items-center justify-center relative overflow-hidden"
                     style="background:linear-gradient(145deg,rgba(255,255,255,0.22) 0%,rgba(255,255,255,0.06) 100%);
                            border:1.5px solid rgba(255,255,255,0.3);
                            box-shadow:0 8px 24px rgba(0,0,0,0.15),inset 0 1px 0 rgba(255,255,255,0.45);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                    <i class="fa-solid fa-briefcase text-white relative z-10" style="font-size:1.2rem; filter:drop-shadow(0 2px 4px rgba(0,0,0,0.2));"></i>
                </div>
            </div>

            {{-- Bottom-right: certificate icon --}}
            <div class="absolute" style="width:46px; height:46px; bottom:4px; right:4px; animation: float 4s ease-in-out infinite; animation-delay:1.8s;">
                <div class="w-full h-full rounded-xl flex items-center justify-center relative overflow-hidden"
                     style="background:linear-gradient(145deg,rgba(255,255,255,0.18) 0%,rgba(255,255,255,0.05) 100%);
                            border:1.5px solid rgba(255,255,255,0.25);
                            box-shadow:0 6px 18px rgba(0,0,0,0.12),inset 0 1px 0 rgba(255,255,255,0.4);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.25);"></div>
                    <i class="fa-solid fa-certificate text-white relative z-10" style="font-size:1rem; filter:drop-shadow(0 2px 4px rgba(0,0,0,0.2));"></i>
                </div>
            </div>

        </div>
    </div>


    {{-- ═══════════════════════════════════
         TABS + CONTENT
    ═══════════════════════════════════ --}}
    <div x-data="{ activeTab: 'workforce' }" class="space-y-5">

        {{-- Icon-box tab buttons --}}
        <div class="flex flex-wrap items-center justify-end gap-3 w-full">

            {{-- Workforce --}}
            <button @click="activeTab = 'workforce'"
                class="hr-tab-btn"
                :class="activeTab === 'workforce' ? 'active-tab' : ''"
                :style="activeTab === 'workforce' ? 'background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#1d4ed8;' : 'color:#1d4ed8;'">
                <div class="tab-icon-box"
                     :style="activeTab === 'workforce'
                        ? 'background:linear-gradient(145deg,#3b82f6,#2563eb); box-shadow:0 6px 18px rgba(37,99,235,0.4),inset 0 1px 0 rgba(255,255,255,0.3); color:#fff;'
                        : 'background:linear-gradient(145deg,#eff6ff,#bfdbfe); color:#3b82f6; box-shadow:0 3px 8px rgba(37,99,235,0.15);'">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span>Workforce</span>
            </button>

            {{-- Demographics --}}
            <button @click="activeTab = 'demographics'"
                class="hr-tab-btn"
                :class="activeTab === 'demographics' ? 'active-tab' : ''"
                :style="activeTab === 'demographics' ? 'background:linear-gradient(135deg,#fdf4ff,#fae8ff); color:#7e22ce;' : 'color:#7e22ce;'">
                <div class="tab-icon-box"
                     :style="activeTab === 'demographics'
                        ? 'background:linear-gradient(145deg,#a855f7,#9333ea); box-shadow:0 6px 18px rgba(168,85,247,0.4),inset 0 1px 0 rgba(255,255,255,0.3); color:#fff;'
                        : 'background:linear-gradient(145deg,#fdf4ff,#e9d5ff); color:#a855f7; box-shadow:0 3px 8px rgba(168,85,247,0.15);'">
                    <i class="fa-solid fa-venus-mars"></i>
                </div>
                <span>Demographics</span>
            </button>

            {{-- Skills --}}
            <button @click="activeTab = 'skills'"
                class="hr-tab-btn"
                :class="activeTab === 'skills' ? 'active-tab' : ''"
                :style="activeTab === 'skills' ? 'background:linear-gradient(135deg,#f0fdf4,#dcfce7); color:#15803d;' : 'color:#15803d;'">
                <div class="tab-icon-box"
                     :style="activeTab === 'skills'
                        ? 'background:linear-gradient(145deg,#22c55e,#16a34a); box-shadow:0 6px 18px rgba(22,163,74,0.4),inset 0 1px 0 rgba(255,255,255,0.3); color:#fff;'
                        : 'background:linear-gradient(145deg,#f0fdf4,#bbf7d0); color:#22c55e; box-shadow:0 3px 8px rgba(22,163,74,0.15);'">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <span>Skills &amp; Certs</span>
            </button>

            {{-- Quick Actions --}}
            <button @click="activeTab = 'actions'"
                class="hr-tab-btn"
                :class="activeTab === 'actions' ? 'active-tab' : ''"
                :style="activeTab === 'actions' ? 'background:linear-gradient(135deg,#fff7ed,#fed7aa); color:#c2410c;' : 'color:#c2410c;'">
                <div class="tab-icon-box"
                     :style="activeTab === 'actions'
                        ? 'background:linear-gradient(145deg,#f97316,#ea580c); box-shadow:0 6px 18px rgba(234,88,12,0.4),inset 0 1px 0 rgba(255,255,255,0.3); color:#fff;'
                        : 'background:linear-gradient(145deg,#fff7ed,#fed7aa); color:#f97316; box-shadow:0 3px 8px rgba(234,88,12,0.15);'">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <span>Quick Actions</span>
            </button>

        </div>

        {{-- ── Tab: Workforce ── --}}
        <div x-show="activeTab === 'workforce'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="space-y-5">
                {{-- Stat cards --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                    {{-- Total Employees --}}
                    <div class="grad-stat-card hover:shadow-2xl"
                         style="background:linear-gradient(135deg,#eff6ff,#dbeafe); border:1.5px solid rgba(37,99,235,0.15); box-shadow:0 4px 16px rgba(37,99,235,0.1);">
                        <div class="absolute inset-0 opacity-0 hover:opacity-100 transition-opacity duration-500 rounded-2xl"
                             style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                        <div class="stat-icon-box"
                             style="background:linear-gradient(145deg,#3b82f6,#2563eb); box-shadow:0 8px 22px rgba(37,99,235,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-users text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none count" style="color:#2563eb;" data-target="{{ $totalEmps }}">0</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#3b82f6;">Total Employees</p>
                    </div>

                    {{-- Departments --}}
                    <div class="grad-stat-card hover:shadow-2xl"
                         style="background:linear-gradient(135deg,#fff7ed,#fed7aa); border:1.5px solid rgba(245,158,11,0.2); box-shadow:0 4px 16px rgba(245,158,11,0.1);">
                        <div class="absolute inset-0 opacity-0 hover:opacity-100 transition-opacity duration-500 rounded-2xl"
                             style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                        <div class="stat-icon-box"
                             style="background:linear-gradient(145deg,#f59e0b,#d97706); box-shadow:0 8px 22px rgba(245,158,11,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-building text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#d97706;">{{ count($deptDataLabels) }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#f59e0b;">Departments</p>
                    </div>

                </div>

                {{-- Chart --}}
                <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(37,99,235,0.08); border:1.5px solid rgba(37,99,235,0.1);">
                    <div class="panel-header-gradient" style="background:linear-gradient(135deg,#3b82f6 0%,#2563eb 60%,#1d4ed8 100%);">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-chart-pie text-white"></i>
                            <h3 class="font-bold text-white">Employees by Department</h3>
                        </div>
                        <span class="px-3 py-1 rounded-lg text-xs font-bold text-white"
                              style="background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3);">
                            {{ array_sum($deptDataCounts) }} Total
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="relative h-64">
                            <canvas id="empByDept"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Tab: Demographics ── --}}
        <div x-show="activeTab === 'demographics'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="space-y-5">
                {{-- Stat cards --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                    {{-- Average Age --}}
                    <div class="grad-stat-card hover:shadow-2xl"
                         style="background:linear-gradient(135deg,#fdf4ff,#f3e8ff); border:1.5px solid rgba(168,85,247,0.15); box-shadow:0 4px 16px rgba(168,85,247,0.1);">
                        <div class="stat-icon-box"
                             style="background:linear-gradient(145deg,#a855f7,#9333ea); box-shadow:0 8px 22px rgba(168,85,247,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-cake-candles text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#9333ea;">
                            <span class="count" data-target="{{ $averageAge }}">0</span>
                        </h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#a855f7;">Avg Age</p>
                    </div>

                    {{-- Diversity --}}
                    <div class="grad-stat-card hover:shadow-2xl"
                         style="background:linear-gradient(135deg,#fff1f2,#ffe4e6); border:1.5px solid rgba(244,63,94,0.15); box-shadow:0 4px 16px rgba(244,63,94,0.1);">
                        <div class="stat-icon-box"
                             style="background:linear-gradient(145deg,#f43f5e,#e11d48); box-shadow:0 8px 22px rgba(244,63,94,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-venus-mars text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#e11d48;">{{ $diversityStat }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#f43f5e;">Diversity</p>
                    </div>

                </div>

                {{-- Chart --}}
                <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(168,85,247,0.08); border:1.5px solid rgba(168,85,247,0.12);">
                    <div class="panel-header-gradient" style="background:linear-gradient(135deg,#a855f7 0%,#9333ea 60%,#7e22ce 100%);">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-chart-pie text-white"></i>
                            <h3 class="font-bold text-white">Gender Distribution</h3>
                        </div>
                        <span class="px-3 py-1 rounded-lg text-xs font-bold text-white"
                              style="background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3);">
                            Diversity Metrics
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="relative h-64">
                            <canvas id="empByGender"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Tab: Skills & Certs ── --}}
        <div x-show="activeTab === 'skills'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="space-y-5">
                {{-- Stat cards --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                    {{-- Certifications --}}
                    <div class="grad-stat-card hover:shadow-2xl"
                         style="background:linear-gradient(135deg,#f0fdf4,#dcfce7); border:1.5px solid rgba(22,163,74,0.15); box-shadow:0 4px 16px rgba(22,163,74,0.1);">
                        <div class="stat-icon-box"
                             style="background:linear-gradient(145deg,#22c55e,#16a34a); box-shadow:0 8px 22px rgba(22,163,74,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-certificate text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#16a34a;">{{ count($certDataLabels) }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#22c55e;">Certifications</p>
                    </div>

                </div>

                {{-- Chart --}}
                <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(22,163,74,0.08); border:1.5px solid rgba(22,163,74,0.12);">
                    <div class="panel-header-gradient" style="background:linear-gradient(135deg,#22c55e 0%,#16a34a 60%,#15803d 100%);">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-chart-bar text-white"></i>
                            <h3 class="font-bold text-white">Employees by Certification</h3>
                        </div>
                        <span class="px-3 py-1 rounded-lg text-xs font-bold text-white"
                              style="background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3);">
                            Skills Overview
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="relative h-64">
                            <canvas id="empByCert"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Tab: Quick Actions ── --}}
        <div x-show="activeTab === 'actions'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(234,88,12,0.08); border:1.5px solid rgba(234,88,12,0.12);">
                <div class="panel-header-gradient" style="background:linear-gradient(135deg,#f97316 0%,#ea580c 60%,#c2410c 100%);">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-white"></i>
                        <h3 class="font-bold text-white">Quick Actions</h3>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                        {{-- Manage Leaves --}}
                        <a href="{{ route('hr.leaves.index') }}" class="quick-action-card"
                           style="background:linear-gradient(135deg,#0ea5e9 0%,#0284c7 60%,#0369a1 100%); box-shadow:0 6px 20px rgba(14,165,233,0.3);">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                                 style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.1) 50%,transparent 70%);"></div>
                            <div class="qa-icon" style="background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3);">
                                <i class="fa-solid fa-calendar-check text-white relative z-10"></i>
                            </div>
                            <div class="flex-1 relative z-10">
                                <p class="font-bold text-white">Manage Leaves</p>
                                <p class="text-xs text-white/70">Review &amp; approve requests</p>
                            </div>
                            <i class="fa-solid fa-chevron-right text-white/60 relative z-10 transition-transform group-hover:translate-x-1"></i>
                        </a>

                        {{-- Performance --}}
                        <a href="{{ route('hr.performance.index') }}" class="quick-action-card"
                           style="background:linear-gradient(135deg,#f59e0b 0%,#d97706 60%,#b45309 100%); box-shadow:0 6px 20px rgba(245,158,11,0.3);">
                            <div class="qa-icon" style="background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3);">
                                <i class="fa-solid fa-star text-white relative z-10"></i>
                            </div>
                            <div class="flex-1 relative z-10">
                                <p class="font-bold text-white">Performance</p>
                                <p class="text-xs text-white/70">Employee reviews</p>
                            </div>
                            <i class="fa-solid fa-chevron-right text-white/60 relative z-10"></i>
                        </a>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Chart.js Library --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Counter Animation
    document.querySelectorAll('.count').forEach(el => {
        const target = parseInt(el.getAttribute('data-target'));
        if (isNaN(target)) return;
        let count = 0;
        const inc = target / 50;
        if (target > 0) {
            const timer = setInterval(() => {
                count += inc;
                if (count >= target) { el.innerText = target; clearInterval(timer); }
                else { el.innerText = Math.ceil(count); }
            }, 20);
        }
    });

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#006a8a';

    // 1. Department Chart (Doughnut)
    const ctxDept = document.getElementById('empByDept');
    new Chart(ctxDept, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($deptDataLabels) !!},
            datasets: [{
                data: {!! json_encode($deptDataCounts) !!},
                backgroundColor: [
                    'rgba(59,130,246,0.85)',
                    'rgba(139,92,246,0.85)',
                    'rgba(16,185,129,0.85)',
                    'rgba(245,158,11,0.85)',
                    'rgba(244,63,94,0.85)',
                ],
                borderWidth: 0, hoverOffset: 10
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 15, font: { size: 12, weight: '500' }, usePointStyle: true, pointStyle: 'circle' } },
                tooltip: { backgroundColor: 'rgba(0,79,104,0.9)', padding: 12, cornerRadius: 10, titleFont: { size: 14, weight: 'bold' }, bodyFont: { size: 13 } }
            }
        }
    });

    // 2. Gender Chart (Pie)
    const ctxGender = document.getElementById('empByGender');
    new Chart(ctxGender, {
        type: 'pie',
        data: {
            labels: {!! json_encode($genderDataLabels) !!},
            datasets: [{
                data: {!! json_encode($genderDataCounts) !!},
                backgroundColor: [
                    'rgba(59,130,246,0.85)',
                    'rgba(244,63,94,0.85)',
                    'rgba(168,85,247,0.85)',
                ],
                borderWidth: 0, hoverOffset: 10
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 15, font: { size: 12, weight: '500' }, usePointStyle: true, pointStyle: 'circle' } },
                tooltip: { backgroundColor: 'rgba(168,85,247,0.9)', padding: 12, cornerRadius: 10, titleFont: { size: 14, weight: 'bold' }, bodyFont: { size: 13 } }
            }
        }
    });

    // 3. Certifications Chart (Bar)
    const ctxCert = document.getElementById('empByCert');
    new Chart(ctxCert, {
        type: 'bar',
        data: {
            labels: {!! json_encode($certDataLabels) !!},
            datasets: [{
                label: 'Employees',
                data: {!! json_encode($certDataCounts) !!},
                backgroundColor: 'rgba(22,163,74,0.85)',
                borderRadius: 10, borderSkipped: false,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,79,104,0.05)', drawBorder: false }, ticks: { font: { size: 11 }, padding: 8 } },
                x: { grid: { display: false }, ticks: { font: { size: 11 }, padding: 8 } }
            },
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: 'rgba(22,163,74,0.9)', padding: 12, cornerRadius: 10, titleFont: { size: 14, weight: 'bold' }, bodyFont: { size: 13 } }
            }
        }
    });
</script>
@endsection