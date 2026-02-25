@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('subtitle', 'System Overview & Statistics')

@push('styles')
<style>
    /* ── Welcome Banner ── */
    .admin-banner {
        background: linear-gradient(135deg, #004F68 0%, #006a8a 45%, #1a8aaa 80%, #0ea5e9 100%);
        border-radius: 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 16px 48px rgba(0,79,104,0.3);
        min-height: 190px;
    }
    .admin-banner::before {
        content: '';
        position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }

    /* ── Tab icon-box cards ── */
    .admin-tab-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 120px;
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
    .admin-tab-btn:hover { transform: translateY(-4px) scale(1.04); box-shadow: 0 8px 24px rgba(0,79,104,0.15); }
    .admin-tab-btn .tab-icon-box {
        width: 44px; height: 44px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
        position: relative; overflow: hidden;
    }
    .admin-tab-btn .tab-icon-box::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 48%;
        background: linear-gradient(180deg, rgba(255,255,255,0.45) 0%, transparent 100%);
        border-radius: 14px 14px 0 0; pointer-events: none;
    }
    .admin-tab-btn:hover .tab-icon-box { transform: scale(1.12) rotate(-5deg); }
    .admin-tab-btn.active-tab {
        border-color: rgba(0,79,104,0.2);
        box-shadow: 0 8px 28px rgba(0,79,104,0.18), inset 0 1px 0 rgba(255,255,255,0.9);
        transform: translateY(-2px);
    }

    /* ── Gradient stat card (centered) ── */
    .grad-stat {
        border-radius: 22px;
        padding: 1.5rem;
        display: flex; flex-direction: column; align-items: center;
        gap: 0.75rem; text-align: center;
        position: relative; overflow: hidden;
        transition: all 0.3s cubic-bezier(0.34,1.2,0.64,1);
    }
    .grad-stat:hover { transform: translateY(-6px); }
    .grad-stat .s-icon {
        width: 56px; height: 56px; border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; position: relative; overflow: hidden;
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1); flex-shrink: 0;
    }
    .grad-stat:hover .s-icon { transform: scale(1.12) rotate(-5deg); }
    .grad-stat .s-icon::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 48%;
        background: linear-gradient(180deg, rgba(255,255,255,0.35) 0%, transparent 100%);
        border-radius: 18px 18px 0 0;
    }

    /* ── Chart panel header ── */
    .chart-header {
        padding: 1.1rem 1.5rem;
        display: flex; align-items: center; justify-content: space-between;
    }

    /* ── Hero Total card ── */
    .hero-total-card {
        border-radius: 24px;
        padding: 2rem 2.5rem;
        position: relative; overflow: hidden;
        background: linear-gradient(135deg, #004F68 0%, #006a8a 50%, #1a8aaa 100%);
        box-shadow: 0 16px 48px rgba(0,79,104,0.35);
        transition: transform 0.3s ease;
    }
    .hero-total-card:hover { transform: translateY(-4px); }
</style>
@endpush

@section('actions')
    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center">
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none" style="color:#004F68;">
                <i class="fa-solid fa-calendar-alt"></i>
            </div>
            <select name="mode" onchange="this.form.submit()"
                class="appearance-none pl-10 pr-10 py-2.5 text-sm font-semibold rounded-xl bg-white border text-teal-800 shadow-sm focus:ring-2 transition-all cursor-pointer"
                style="border-color:rgba(0,79,104,0.2); focus:ring-color:#004F68;">
                <option value="all"        {{ $mode == 'all'        ? 'selected' : '' }}>All Time</option>
                <option value="today"      {{ $mode == 'today'      ? 'selected' : '' }}>Today</option>
                <option value="this_week"  {{ $mode == 'this_week'  ? 'selected' : '' }}>This Week</option>
                <option value="this_month" {{ $mode == 'this_month' ? 'selected' : '' }}>This Month</option>
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-teal-400">
                <i class="fa-solid fa-chevron-down text-xs"></i>
            </div>
        </div>
    </form>
@endsection

@section('content')
<div class="space-y-6">

    {{-- ═══════════════════════════════════
         WELCOME BANNER
    ═══════════════════════════════════ --}}
    <div class="admin-banner">
        <div class="flex flex-col justify-center h-full px-8 md:px-14 py-10 md:py-12 relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full mb-4 w-fit"
                 style="background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.3);">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                </span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-white">Admin Control Centre</span>
            </div>
            <div class="flex items-center gap-4 mb-2">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0"
                     style="background:linear-gradient(145deg,rgba(255,255,255,0.28),rgba(255,255,255,0.1));
                            border:1.5px solid rgba(255,255,255,0.35);
                            box-shadow:0 8px 24px rgba(0,0,0,0.18), inset 0 1px 0 rgba(255,255,255,0.5);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.35);"></div>
                    <i class="fa-solid fa-shield-halved text-white text-2xl relative z-10 drop-shadow-sm"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-display font-extrabold text-white tracking-tight drop-shadow-sm">
                    System Overview
                </h1>
            </div>
            <p class="text-sky-100 text-sm md:text-base font-medium max-w-md leading-relaxed">
                Monitor tickets, assets, and system health — all in one command centre.
            </p>
        </div>

        {{-- Right-side 3D icon cluster --}}
        <div class="absolute right-8 md:right-16 top-1/2 -translate-y-1/2 pointer-events-none hidden md:flex items-center justify-center" style="width:220px; height:180px;">
            <div class="absolute" style="width:90px; height:90px; top:50%; left:50%; transform:translate(-50%,-55%);">
                <div class="w-full h-full rounded-3xl flex items-center justify-center relative overflow-hidden"
                     style="background:linear-gradient(145deg,rgba(255,255,255,0.28),rgba(255,255,255,0.08));
                            border:1.5px solid rgba(255,255,255,0.4);
                            box-shadow:0 16px 48px rgba(0,0,0,0.2),inset 0 2px 0 rgba(255,255,255,0.5);
                            animation:float 4s ease-in-out infinite;">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-3xl" style="background:rgba(255,255,255,0.3);"></div>
                    <i class="fa-solid fa-headset text-white relative z-10" style="font-size:2.4rem; filter:drop-shadow(0 4px 8px rgba(0,0,0,0.2));"></i>
                </div>
            </div>
            <div class="absolute" style="width:52px; height:52px; top:0; right:10px; animation:float 4s ease-in-out infinite; animation-delay:0.6s;">
                <div class="w-full h-full rounded-2xl flex items-center justify-center relative overflow-hidden"
                     style="background:linear-gradient(145deg,rgba(255,255,255,0.22),rgba(255,255,255,0.06));
                            border:1.5px solid rgba(255,255,255,0.3);
                            box-shadow:0 8px 24px rgba(0,0,0,0.15),inset 0 1px 0 rgba(255,255,255,0.45);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                    <i class="fa-solid fa-server text-white relative z-10" style="font-size:1.2rem;"></i>
                </div>
            </div>
            <div class="absolute" style="width:52px; height:52px; bottom:0; left:0; animation:float 4s ease-in-out infinite; animation-delay:1.2s;">
                <div class="w-full h-full rounded-2xl flex items-center justify-center relative overflow-hidden"
                     style="background:linear-gradient(145deg,rgba(255,255,255,0.22),rgba(255,255,255,0.06));
                            border:1.5px solid rgba(255,255,255,0.3);
                            box-shadow:0 8px 24px rgba(0,0,0,0.15),inset 0 1px 0 rgba(255,255,255,0.45);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                    <i class="fa-solid fa-ticket text-white relative z-10" style="font-size:1.2rem;"></i>
                </div>
            </div>
            <div class="absolute" style="width:46px; height:46px; bottom:4px; right:4px; animation:float 4s ease-in-out infinite; animation-delay:1.8s;">
                <div class="w-full h-full rounded-xl flex items-center justify-center relative overflow-hidden"
                     style="background:linear-gradient(145deg,rgba(255,255,255,0.18),rgba(255,255,255,0.05));
                            border:1.5px solid rgba(255,255,255,0.25);
                            box-shadow:0 6px 18px rgba(0,0,0,0.12),inset 0 1px 0 rgba(255,255,255,0.4);">
                    <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.25);"></div>
                    <i class="fa-solid fa-chart-line text-white relative z-10" style="font-size:1rem;"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════
         TABS + CONTENT
    ═══════════════════════════════════ --}}
    <div x-data="{ activeTab: 'tickets' }" class="space-y-5">

        {{-- Icon-box tab buttons --}}
        <div class="flex flex-wrap items-center justify-end gap-3 w-full">

            {{-- Support Desk --}}
            <button @click="activeTab = 'tickets'"
                class="admin-tab-btn"
                :class="activeTab === 'tickets' ? 'active-tab' : ''"
                :style="activeTab === 'tickets' ? 'background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#1d4ed8;' : 'color:#1d4ed8;'">
                <div class="tab-icon-box"
                     :style="activeTab === 'tickets'
                        ? 'background:linear-gradient(145deg,#3b82f6,#2563eb); box-shadow:0 6px 18px rgba(37,99,235,0.4),inset 0 1px 0 rgba(255,255,255,0.3); color:#fff;'
                        : 'background:linear-gradient(145deg,#eff6ff,#bfdbfe); color:#3b82f6; box-shadow:0 3px 8px rgba(37,99,235,0.15);'">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <span>Support Desk</span>
            </button>

            {{-- Asset Management --}}
            <button @click="activeTab = 'assets'"
                class="admin-tab-btn"
                :class="activeTab === 'assets' ? 'active-tab' : ''"
                :style="activeTab === 'assets' ? 'background:linear-gradient(135deg,#f0fdf4,#dcfce7); color:#15803d;' : 'color:#15803d;'">
                <div class="tab-icon-box"
                     :style="activeTab === 'assets'
                        ? 'background:linear-gradient(145deg,#22c55e,#16a34a); box-shadow:0 6px 18px rgba(22,163,74,0.4),inset 0 1px 0 rgba(255,255,255,0.3); color:#fff;'
                        : 'background:linear-gradient(145deg,#f0fdf4,#bbf7d0); color:#22c55e; box-shadow:0 3px 8px rgba(22,163,74,0.15);'">
                    <i class="fa-solid fa-server"></i>
                </div>
                <span>Assets</span>
            </button>

        </div>

        {{-- ── Tab: Support Tickets ── --}}
        <div x-show="activeTab === 'tickets'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="space-y-6">

                {{-- Hero Total card --}}
                <div class="hero-total-card">
                    <div class="absolute right-0 top-0 h-full w-1/2 pointer-events-none"
                         style="background:linear-gradient(to left, rgba(255,255,255,0.07), transparent);"></div>
                    <div class="absolute right-10 top-1/2 -translate-y-1/2 opacity-10" style="transform:translateY(-50%) rotate(12deg) scale(1.5);">
                        <i class="fa-solid fa-ticket" style="font-size:7rem;"></i>
                    </div>
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center relative overflow-hidden"
                                 style="background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3); box-shadow:inset 0 1px 0 rgba(255,255,255,0.4);">
                                <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                                <i class="fa-solid fa-layer-group text-white text-2xl relative z-10"></i>
                            </div>
                            <div>
                                <p class="text-sky-100 font-medium text-base mb-1">Total System Tickets</p>
                                <h3 class="text-5xl font-black tracking-tight text-white">{{ $totalTickets }}</h3>
                            </div>
                        </div>
                        <div class="hidden md:flex items-center gap-0">
                            <div class="text-center px-8">
                                <span class="block text-3xl font-black text-white">{{ $totalOpen }}</span>
                                <span class="text-[10px] text-sky-200 uppercase tracking-wider font-bold">Open</span>
                            </div>
                            <div class="w-px h-10" style="background:rgba(255,255,255,0.2);"></div>
                            <div class="text-center px-8">
                                <span class="block text-3xl font-black text-white">{{ $totalProgress }}</span>
                                <span class="text-[10px] text-sky-200 uppercase tracking-wider font-bold">Processing</span>
                            </div>
                            <div class="w-px h-10" style="background:rgba(255,255,255,0.2);"></div>
                            <div class="text-center px-8">
                                <span class="block text-3xl font-black text-white">{{ $totalResolved }}</span>
                                <span class="text-[10px] text-sky-200 uppercase tracking-wider font-bold">Resolved</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KPI stat cards grid --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                    {{-- Open --}}
                    <div class="grad-stat" style="background:linear-gradient(135deg,#fff1f2,#ffe4e6); border:1.5px solid rgba(244,63,94,0.18); box-shadow:0 4px 16px rgba(244,63,94,0.1);">
                        <div class="absolute inset-0 rounded-[22px] opacity-0 hover:opacity-100 transition-opacity duration-500"
                             style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                        <div class="s-icon" style="background:linear-gradient(145deg,#f43f5e,#e11d48); box-shadow:0 8px 22px rgba(244,63,94,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-envelope-open text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#e11d48;">{{ $totalOpen }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#f43f5e;">Open</p>
                        <div class="w-full rounded-full overflow-hidden" style="height:4px; background:rgba(244,63,94,0.15);">
                            <div class="h-full rounded-full" style="width:{{ $totalTickets > 0 ? ($totalOpen/$totalTickets)*100 : 0 }}%; background:linear-gradient(90deg,#f43f5e,#e11d48);"></div>
                        </div>
                    </div>

                    {{-- In Progress --}}
                    <div class="grad-stat" style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe); border:1.5px solid rgba(14,165,233,0.18); box-shadow:0 4px 16px rgba(14,165,233,0.1);">
                        <div class="s-icon" style="background:linear-gradient(145deg,#0ea5e9,#0284c7); box-shadow:0 8px 22px rgba(14,165,233,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-spinner fa-spin-pulse text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#0284c7;">{{ $totalProgress }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#0ea5e9;">In Progress</p>
                        <div class="w-full rounded-full overflow-hidden" style="height:4px; background:rgba(14,165,233,0.15);">
                            <div class="h-full rounded-full" style="width:{{ $totalTickets > 0 ? ($totalProgress/$totalTickets)*100 : 0 }}%; background:linear-gradient(90deg,#0ea5e9,#0284c7);"></div>
                        </div>
                    </div>

                    {{-- Resolved --}}
                    <div class="grad-stat" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7); border:1.5px solid rgba(16,185,129,0.18); box-shadow:0 4px 16px rgba(16,185,129,0.1);">
                        <div class="s-icon" style="background:linear-gradient(145deg,#10b981,#059669); box-shadow:0 8px 22px rgba(16,185,129,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-check-circle text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#059669;">{{ $totalResolved }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#10b981;">Resolved</p>
                        <div class="w-full rounded-full overflow-hidden" style="height:4px; background:rgba(16,185,129,0.15);">
                            <div class="h-full rounded-full" style="width:{{ $totalTickets > 0 ? ($totalResolved/$totalTickets)*100 : 0 }}%; background:linear-gradient(90deg,#10b981,#059669);"></div>
                        </div>
                    </div>

                    {{-- Unassigned --}}
                    <div class="grad-stat" style="background:linear-gradient(135deg,#fff7ed,#fed7aa); border:1.5px solid rgba(245,158,11,0.18); box-shadow:0 4px 16px rgba(245,158,11,0.1);">
                        <div class="s-icon" style="background:linear-gradient(145deg,#f59e0b,#d97706); box-shadow:0 8px 22px rgba(245,158,11,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-user-slash text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#d97706;">{{ $totalUnassigned }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#f59e0b;">Unassigned</p>
                        <div class="w-full rounded-full overflow-hidden" style="height:4px; background:rgba(245,158,11,0.15);">
                            <div class="h-full rounded-full" style="width:{{ $totalTickets > 0 ? ($totalUnassigned/$totalTickets)*100 : 0 }}%; background:linear-gradient(90deg,#f59e0b,#d97706);"></div>
                        </div>
                    </div>

                </div>

                {{-- Ticket Charts --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(37,99,235,0.08); border:1.5px solid rgba(37,99,235,0.1);">
                        <div class="chart-header" style="background:linear-gradient(135deg,#3b82f6,#2563eb,#1d4ed8);">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-chart-pie text-white"></i>
                                <h3 class="font-bold text-white">Tickets by Department</h3>
                            </div>
                        </div>
                        <div class="p-6 relative min-h-[300px] flex items-center justify-center">
                            @if(array_sum($ticketsByDeptCounts) > 0)
                                <canvas id="ticketsDeptChart"></canvas>
                            @else
                                <div class="text-center" style="color:#94a3b8;">
                                    <i class="fa-solid fa-chart-pie text-4xl mb-3 opacity-30"></i>
                                    <p class="text-sm font-medium">No ticket data available</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(244,63,94,0.08); border:1.5px solid rgba(244,63,94,0.1);">
                        <div class="chart-header" style="background:linear-gradient(135deg,#f43f5e,#e11d48,#be123c);">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-chart-pie text-white"></i>
                                <h3 class="font-bold text-white">Tickets by Priority</h3>
                            </div>
                        </div>
                        <div class="p-6 relative min-h-[300px] flex items-center justify-center">
                            @if(array_sum($ticketsByPriorityCounts) > 0)
                                <canvas id="ticketsPrioChart"></canvas>
                            @else
                                <div class="text-center" style="color:#94a3b8;">
                                    <i class="fa-solid fa-chart-pie text-4xl mb-3 opacity-30"></i>
                                    <p class="text-sm font-medium">No priority data available</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Recent Tickets Table --}}
                <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(0,79,104,0.1); border:1.5px solid rgba(0,79,104,0.12);">
                    <div class="chart-header" style="background:linear-gradient(135deg,#004F68,#006a8a,#1a8aaa);">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center relative overflow-hidden"
                                 style="background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.3);">
                                <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-xl" style="background:rgba(255,255,255,0.3);"></div>
                                <i class="fa-solid fa-clock-rotate-left text-white text-sm relative z-10"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-white">Recent Activity</h3>
                                <p class="text-[10px] text-sky-200 font-medium">Latest support tickets raised</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.tickets.index') }}"
                           class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all hover:-translate-y-0.5 group"
                           style="background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.3); color:white;">
                            <span>View All</span>
                            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr style="background:rgba(0,79,104,0.04); border-bottom:1.5px solid rgba(0,79,104,0.08);">
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:#006a8a;">Ref ID</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:#006a8a;">Subject</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:#006a8a;">Requested By</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:#006a8a;">Date</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center" style="color:#006a8a;">Status</th>
                                </tr>
                            </thead>
                            <tbody style="divide-color:rgba(0,79,104,0.06);">
                                @forelse($recentTickets as $ticket)
                                    <tr class="transition-colors hover:bg-teal-50/40" style="border-bottom:1px solid rgba(0,79,104,0.06);">
                                        <td class="px-6 py-4">
                                            <span class="font-mono font-semibold px-2 py-1 rounded text-xs select-all"
                                                  style="background:rgba(0,79,104,0.08); color:#004F68;">#{{ $ticket->ticket_ref ?? $ticket->ticket_id }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-semibold" style="color:#004F68;">{{ $ticket->ticket_subject }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white ring-2 ring-white"
                                                     style="background:linear-gradient(135deg,#004F68,#1a8aaa); box-shadow:0 2px 8px rgba(0,79,104,0.3);">
                                                    {{ substr($ticket->added_employee, 0, 1) }}
                                                </div>
                                                <span class="text-sm font-medium" style="color:#006a8a;">{{ $ticket->added_employee }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium" style="color:#1a8aaa;">
                                            {{ \Carbon\Carbon::parse($ticket->ticket_added_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($ticket->status_id == 1)
                                                <span class="px-3 py-1.5 text-xs font-bold text-red-700 bg-red-50 border border-red-100 rounded-full">Open</span>
                                            @elseif($ticket->status_id == 2)
                                                <span class="px-3 py-1.5 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-100 rounded-full">In Progress</span>
                                            @elseif($ticket->status_id == 3)
                                                <span class="px-3 py-1.5 text-xs font-bold text-green-700 bg-green-50 border border-green-100 rounded-full">Resolved</span>
                                            @else
                                                <span class="px-3 py-1.5 text-xs font-bold text-slate-600 bg-slate-100 border border-slate-200 rounded-full">Unknown</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4"
                                                     style="background:rgba(0,79,104,0.06);">
                                                    <i class="fa-solid fa-inbox text-2xl" style="color:rgba(0,79,104,0.3);"></i>
                                                </div>
                                                <p class="font-medium" style="color:#006a8a;">No recent tickets found.</p>
                                                <p class="text-xs mt-1" style="color:#1a8aaa;">New support requests will appear here.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Tab: Asset Management ── --}}
        <div x-show="activeTab === 'assets'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">
            <div class="space-y-6">

                {{-- Asset KPI cards --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                    {{-- Total Assets --}}
                    <div class="grad-stat" style="background:linear-gradient(135deg,#eff6ff,#dbeafe); border:1.5px solid rgba(37,99,235,0.15); box-shadow:0 4px 16px rgba(37,99,235,0.1);">
                        <div class="s-icon" style="background:linear-gradient(145deg,#3b82f6,#2563eb); box-shadow:0 8px 22px rgba(37,99,235,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-cubes text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#2563eb;">{{ $totalAssets }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#3b82f6;">Total Assets</p>
                    </div>

                    {{-- In Stock --}}
                    <div class="grad-stat" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7); border:1.5px solid rgba(16,185,129,0.15); box-shadow:0 4px 16px rgba(16,185,129,0.1);">
                        <div class="s-icon" style="background:linear-gradient(145deg,#10b981,#059669); box-shadow:0 8px 22px rgba(16,185,129,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-box text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#059669;">{{ $totalAssetsInStock }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#10b981;">In Stock</p>
                    </div>

                    {{-- In Use --}}
                    <div class="grad-stat" style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe); border:1.5px solid rgba(14,165,233,0.15); box-shadow:0 4px 16px rgba(14,165,233,0.1);">
                        <div class="s-icon" style="background:linear-gradient(145deg,#0ea5e9,#0284c7); box-shadow:0 8px 22px rgba(14,165,233,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-user-tag text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#0284c7;">{{ $totalAssetsInUse }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#0ea5e9;">In Use</p>
                    </div>

                    {{-- Retired --}}
                    <div class="grad-stat" style="background:linear-gradient(135deg,#fdf4ff,#f3e8ff); border:1.5px solid rgba(168,85,247,0.15); box-shadow:0 4px 16px rgba(168,85,247,0.1);">
                        <div class="s-icon" style="background:linear-gradient(145deg,#a855f7,#9333ea); box-shadow:0 8px 22px rgba(168,85,247,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-ban text-white relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#9333ea;">{{ $totalAssetsRetired }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#a855f7;">Retired</p>
                    </div>

                </div>

                {{-- Asset Charts --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(37,99,235,0.08); border:1.5px solid rgba(37,99,235,0.1);">
                        <div class="chart-header" style="background:linear-gradient(135deg,#3b82f6,#2563eb);">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-chart-pie text-white"></i>
                                <h3 class="font-bold text-white">By Department</h3>
                            </div>
                        </div>
                        <div class="p-5 relative min-h-[250px] flex items-center justify-center">
                            @if(array_sum($assetsByDeptCounts) > 0)
                                <canvas id="assetsDeptChart"></canvas>
                            @else
                                <div class="text-center text-slate-400">
                                    <i class="fa-solid fa-chart-pie text-3xl mb-2 opacity-30"></i>
                                    <p class="text-xs font-medium">No asset data</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(16,185,129,0.08); border:1.5px solid rgba(16,185,129,0.1);">
                        <div class="chart-header" style="background:linear-gradient(135deg,#10b981,#059669);">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-chart-pie text-white"></i>
                                <h3 class="font-bold text-white">By Category</h3>
                            </div>
                        </div>
                        <div class="p-5 relative min-h-[250px] flex items-center justify-center">
                            @if(array_sum($assetsByCatCounts) > 0)
                                <canvas id="assetsCatChart"></canvas>
                            @else
                                <div class="text-center text-slate-400">
                                    <i class="fa-solid fa-chart-pie text-3xl mb-2 opacity-30"></i>
                                    <p class="text-xs font-medium">No asset data</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(168,85,247,0.08); border:1.5px solid rgba(168,85,247,0.1);">
                        <div class="chart-header" style="background:linear-gradient(135deg,#a855f7,#9333ea);">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-chart-pie text-white"></i>
                                <h3 class="font-bold text-white">By Status</h3>
                            </div>
                        </div>
                        <div class="p-5 relative min-h-[250px] flex items-center justify-center">
                            @if(array_sum($assetsByStatusCounts) > 0)
                                <canvas id="assetsStatusChart"></canvas>
                            @else
                                <div class="text-center text-slate-400">
                                    <i class="fa-solid fa-chart-pie text-3xl mb-2 opacity-30"></i>
                                    <p class="text-xs font-medium">No status data</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const colors = {
        primary: '#004F68', primaryLight: '#1a8aaa',
        accent: '#0ea5e9', success: '#10b981',
        warning: '#f59e0b', danger: '#f43f5e',
        purple: '#a855f7', slate: '#64748b'
    };

    const chartOptions = {
        responsive: true, maintainAspectRatio: false,
        layout: { padding: 10 },
        plugins: {
            legend: { position: 'bottom', align: 'center', labels: { usePointStyle: true, pointStyle: 'circle', padding: 16, font: { size: 11, weight: 600 }, color: '#006a8a' } },
            tooltip: { backgroundColor: 'rgba(0,79,104,0.9)', padding: 12, cornerRadius: 10, titleFont: { size: 13 }, bodyFont: { size: 13 }, displayColors: false }
        },
        cutout: '72%',
        elements: { arc: { borderWidth: 0, hoverOffset: 10 } }
    };

    // 1. Tickets by Dept
    const ticketsDeptEl = document.getElementById('ticketsDeptChart');
    if (ticketsDeptEl) new Chart(ticketsDeptEl, { type: 'doughnut', data: {
        labels: {!! json_encode($ticketsByDeptLabels) !!},
        datasets: [{ data: {!! json_encode($ticketsByDeptCounts) !!}, backgroundColor: [colors.primary, colors.accent, colors.success, colors.warning, colors.danger], borderWidth: 0 }]
    }, options: chartOptions });

    // 2. Tickets by Priority
    const ticketsPrioEl = document.getElementById('ticketsPrioChart');
    if (ticketsPrioEl) new Chart(ticketsPrioEl, { type: 'doughnut', data: {
        labels: {!! json_encode($ticketsByPriorityLabels) !!},
        datasets: [{ data: {!! json_encode($ticketsByPriorityCounts) !!}, backgroundColor: [colors.danger, colors.warning, colors.success, colors.slate], borderWidth: 0 }]
    }, options: chartOptions });

    // 3. Assets by Dept
    const assetsDeptEl = document.getElementById('assetsDeptChart');
    if (assetsDeptEl) new Chart(assetsDeptEl, { type: 'doughnut', data: {
        labels: {!! json_encode($assetsByDeptLabels) !!},
        datasets: [{ data: {!! json_encode($assetsByDeptCounts) !!}, backgroundColor: [colors.primary, colors.accent, colors.success, colors.warning, colors.danger], borderWidth: 0 }]
    }, options: chartOptions });

    // 4. Assets by Category
    const assetsCatEl = document.getElementById('assetsCatChart');
    if (assetsCatEl) new Chart(assetsCatEl, { type: 'doughnut', data: {
        labels: {!! json_encode($assetsByCatLabels) !!},
        datasets: [{ data: {!! json_encode($assetsByCatCounts) !!}, backgroundColor: [colors.primaryLight, colors.accent, colors.slate, colors.warning], borderWidth: 0 }]
    }, options: chartOptions });

    // 5. Assets by Status
    const assetsStatusEl = document.getElementById('assetsStatusChart');
    if (assetsStatusEl) new Chart(assetsStatusEl, { type: 'doughnut', data: {
        labels: {!! json_encode($assetsByStatusLabels) !!},
        datasets: [{ data: {!! json_encode($assetsByStatusCounts) !!}, backgroundColor: [colors.success, colors.primary, colors.danger, colors.warning, colors.slate], borderWidth: 0 }]
    }, options: chartOptions });
</script>
@endsection