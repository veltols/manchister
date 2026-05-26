@extends('layouts.app')

@section('title', 'Liaison Portal Dashboard')

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
</style>
@endpush

@section('content')

<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Liaison Portal Overview</h2>
        <p class="text-sm text-slate-500 mt-1">Key metrics and recent activities</p>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     STAT CARDS ROW
═══════════════════════════════════════════ --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    {{-- External Entities --}}
    <div class="emp-stat-card"
         style="background: linear-gradient(135deg,#0ea5e9 0%,#0284c7 50%,#0369a1 100%);
                box-shadow: 0 10px 40px rgba(14,165,233,0.35), 0 2px 8px rgba(0,0,0,0.1);">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sky-100 text-xs font-bold uppercase tracking-widest mb-2">External Entities</p>
                <div class="flex items-end gap-2">
                    <span class="text-5xl font-black text-white leading-none">{{ $metrics['external_entities'] ?? 0 }}</span>
                </div>
                <p class="text-sky-200 text-xs mt-3 font-medium">Registered organizations</p>
            </div>
            <div class="stat-icon-3d"
                 style="background: linear-gradient(145deg,rgba(255,255,255,0.28) 0%,rgba(255,255,255,0.1) 100%);
                        border: 1.5px solid rgba(255,255,255,0.3);
                        box-shadow: 0 6px 20px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.4);">
                <i class="fa-solid fa-building-user text-white"></i>
            </div>
        </div>
    </div>

    {{-- Inbound Communications --}}
    <div class="emp-stat-card"
         style="background: linear-gradient(135deg,#f59e0b 0%,#d97706 50%,#b45309 100%);
                box-shadow: 0 10px 40px rgba(245,158,11,0.35), 0 2px 8px rgba(0,0,0,0.1);">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-amber-100 text-xs font-bold uppercase tracking-widest mb-2">Inbound Comm</p>
                <div class="flex items-end gap-2">
                    <span class="text-5xl font-black text-white leading-none">{{ $metrics['inbound_communications'] ?? 0 }}</span>
                </div>
                <p class="text-amber-200 text-xs mt-3 font-medium">Total received</p>
            </div>
            <div class="stat-icon-3d"
                 style="background: linear-gradient(145deg,rgba(255,255,255,0.28) 0%,rgba(255,255,255,0.1) 100%);
                        border: 1.5px solid rgba(255,255,255,0.3);
                        box-shadow: 0 6px 20px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.4);">
                <i class="fa-solid fa-envelope-open-text text-white"></i>
            </div>
        </div>
    </div>

    {{-- Outbound Pending --}}
    <div class="emp-stat-card"
         style="background: linear-gradient(135deg,#8b5cf6 0%,#7c3aed 50%,#6d28d9 100%);
                box-shadow: 0 10px 40px rgba(139,92,246,0.35), 0 2px 8px rgba(0,0,0,0.1);">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-violet-100 text-xs font-bold uppercase tracking-widest mb-2">Outbound Pending</p>
                <div class="flex items-end gap-2">
                    <span class="text-5xl font-black text-white leading-none">{{ $metrics['outbound_pending'] ?? 0 }}</span>
                </div>
                <p class="text-violet-200 text-xs mt-3 font-medium">⚡ Pending Dispatch</p>
            </div>
            <div class="stat-icon-3d"
                 style="background: linear-gradient(145deg,rgba(255,255,255,0.28) 0%,rgba(255,255,255,0.1) 100%);
                        border: 1.5px solid rgba(255,255,255,0.3);
                        box-shadow: 0 6px 20px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.4);">
                <i class="fa-solid fa-paper-plane text-white"></i>
            </div>
        </div>
    </div>

    {{-- Comm Logs --}}
    <div class="emp-stat-card"
         style="background: linear-gradient(135deg,#10b981 0%,#059669 50%,#047857 100%);
                box-shadow: 0 10px 40px rgba(16,185,129,0.35), 0 2px 8px rgba(0,0,0,0.1);">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-emerald-100 text-xs font-bold uppercase tracking-widest mb-2">Comm Logs</p>
                <div class="flex items-end gap-2">
                    <span class="text-5xl font-black text-white leading-none">{{ $metrics['communications_log'] ?? 0 }}</span>
                </div>
                <p class="text-emerald-200 text-xs mt-3 font-medium">Logged interactions</p>
            </div>
            <div class="stat-icon-3d"
                 style="background: linear-gradient(145deg,rgba(255,255,255,0.28) 0%,rgba(255,255,255,0.1) 100%);
                        border: 1.5px solid rgba(255,255,255,0.3);
                        box-shadow: 0 6px 20px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.4);">
                <i class="fa-solid fa-clipboard-list text-white"></i>
            </div>
        </div>
    </div>
</div>

@endsection
