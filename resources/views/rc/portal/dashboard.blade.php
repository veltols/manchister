@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Welcome to your ATP Portal')

@push('styles')
    <style>
        /* ── Same stat card design as emp dashboard ── */
        .emp-stat-card {
            border-radius: 22px;
            padding: 1.6rem 1.5rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.32s cubic-bezier(0.34, 1.2, 0.64, 1), box-shadow 0.32s ease;
            cursor: default;
        }

        .emp-stat-card:hover {
            transform: translateY(-6px) scale(1.02);
        }

        .emp-stat-card::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -75%;
            width: 50%;
            height: 200%;
            background: rgba(255, 255, 255, 0.13);
            transform: skewX(-20deg);
            transition: left 0.55s ease;
        }

        .emp-stat-card:hover::after {
            left: 130%;
        }

        .stat-icon-3d {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            position: relative;
            flex-shrink: 0;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .emp-stat-card:hover .stat-icon-3d {
            transform: scale(1.14) rotate(-6deg) translateY(-3px);
        }

        .stat-icon-3d::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 48%;
            border-radius: 16px 16px 0 0;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.45) 0%, transparent 100%);
            pointer-events: none;
        }

        /* ── Quick Links ── */
        .quick-link-card {
            border-radius: 18px;
            padding: 1.4rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.34, 1.2, 0.64, 1);
            position: relative;
            overflow: hidden;
            border: 1.5px solid rgba(255, 255, 255, 0.18);
        }

        .quick-link-card:hover {
            transform: translateY(-5px) scale(1.04);
        }

        .quick-link-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 0.7rem;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
        }

        .quick-link-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.5) 0%, transparent 100%);
            border-radius: 14px 14px 0 0;
            pointer-events: none;
        }

        .quick-link-card:hover .quick-link-icon {
            transform: scale(1.15) rotate(-5deg);
        }

        /* ── Main panel ── */
        .emp-tasks-panel {
            background: white;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 79, 104, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0, 79, 104, 0.06);
        }

        .emp-tasks-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #004F68 0%, #006a8a 60%, #1a8aaa 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
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

        .emp-task-row:last-child {
            border-bottom: none;
        }

        .emp-task-row:hover {
            background: #f8fafc;
        }

        .task-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 50px;
            letter-spacing: 0.04em;
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        }

        .badge-open {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: #fff;
        }

        .badge-pending {
            background: linear-gradient(135deg, #94a3b8, #64748b);
            color: #fff;
        }

        .badge-done {
            background: linear-gradient(135deg, #34d399, #10b981);
            color: #fff;
        }

        /* ── ATP info card on right ── */
        .atp-info-card {
            border-radius: 22px;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
        }

        .atp-info-card::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 136, 179, 0.20) 0%, transparent 70%);
        }

        .atp-info-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .atp-info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
    </style>
@endpush

@section('content')

    {{-- ═══════════════════════════════════════════
    STAT CARDS ROW (3 cards like EMP)
    ═══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        {{-- 1. ATP Reference --}}
        <div class="emp-stat-card" style="background: linear-gradient(135deg,#004F68 0%,#006a8a 50%,#0088b3 100%);
                        box-shadow: 0 10px 40px rgba(0,79,104,0.35), 0 2px 8px rgba(0,0,0,0.1);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sky-100 text-xs font-bold uppercase tracking-widest mb-2">ATP Reference</p>
                    <div class="flex items-end gap-2">
                        <span class="text-3xl font-black text-white leading-none font-mono">{{ $atp->atp_ref }}</span>
                    </div>
                    <p class="text-sky-200 text-xs mt-3 font-medium">📋 Your unique identifier</p>
                </div>
                <div class="stat-icon-3d" style="background: linear-gradient(145deg,rgba(255,255,255,0.28) 0%,rgba(255,255,255,0.1) 100%);
                                border: 1.5px solid rgba(255,255,255,0.3);
                                box-shadow: 0 6px 20px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.4);">
                    <i class="fa-solid fa-hashtag text-white"></i>
                </div>
            </div>
        </div>

        {{-- 2. Status --}}
        <div class="emp-stat-card" style="background: linear-gradient(135deg,#059669 0%,#047857 50%,#065f46 100%);
                        box-shadow: 0 10px 40px rgba(5,150,105,0.35), 0 2px 8px rgba(0,0,0,0.1);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-emerald-100 text-xs font-bold uppercase tracking-widest mb-2">Current Status</p>
                    <div class="flex items-end gap-2">
                        <span
                            class="text-3xl font-black text-white leading-none">{{ $atp->status->atp_status_name ?? 'Pending' }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 mt-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse inline-block"></span>
                        <p class="text-emerald-200 text-xs font-medium">Account active</p>
                    </div>
                </div>
                <div class="stat-icon-3d" style="background: linear-gradient(145deg,rgba(255,255,255,0.28) 0%,rgba(255,255,255,0.1) 100%);
                                border: 1.5px solid rgba(255,255,255,0.3);
                                box-shadow: 0 6px 20px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.4);">
                    <i class="fa-solid fa-certificate text-white"></i>
                </div>
            </div>
        </div>

        {{-- 3. Active Requests --}}
        <div class="emp-stat-card" style="background: linear-gradient(135deg,#8b5cf6 0%,#7c3aed 50%,#6d28d9 100%);
                        box-shadow: 0 10px 40px rgba(139,92,246,0.35), 0 2px 8px rgba(0,0,0,0.1);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-violet-100 text-xs font-bold uppercase tracking-widest mb-2">Active Requests</p>
                    <div class="flex items-end gap-2">
                        <span class="text-5xl font-black text-white leading-none">{{ $activeRequests->count() }}</span>
                        <span class="text-sm text-violet-200 font-semibold mb-1">Open</span>
                    </div>
                    <p class="text-violet-200 text-xs mt-3 font-medium">
                        {{ $activeRequests->count() > 0 ? '⚡ Action needed' : '✅ All clear' }}
                    </p>
                </div>
                <div class="stat-icon-3d" style="background: linear-gradient(145deg,rgba(255,255,255,0.28) 0%,rgba(255,255,255,0.1) 100%);
                                border: 1.5px solid rgba(255,255,255,0.3);
                                box-shadow: 0 6px 20px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.4);">
                    <i class="fa-solid fa-inbox text-white"></i>
                </div>
            </div>
        </div>

    </div>


    {{-- ═══════════════════════════════════════════
    MAIN ROW: Requests Table + Side Panel
    ═══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ── My Requests Panel (col-span-2) ── --}}
        <div class="lg:col-span-2 emp-tasks-panel">

            {{-- Header (same teal gradient as emp) --}}
            <div class="emp-tasks-header">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                        style="background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.3);">
                        <i class="fa-solid fa-inbox text-white text-sm"></i>
                    </div>
                    <h3 class="font-bold text-white text-base tracking-wide">My Active Requests</h3>
                </div>
                <a href="{{ route('rc.portal.wizard.step1') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all duration-300 hover:-translate-y-0.5 group"
                    style="background:rgba(255,255,255,0.18); color:#fff; border:1px solid rgba(255,255,255,0.3);
                              box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <span>Accreditation</span>
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform text-[10px]"></i>
                </a>
            </div>

            {{-- Column headers --}}
            <div style="display:grid; grid-template-columns:1fr auto auto; gap:1rem;
                            padding:0.65rem 1.5rem; background:#f8fafc; border-bottom:1px solid #e2e8f0;">
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Request</span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Date</span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Action</span>
            </div>

            {{-- Rows --}}
            @forelse($activeRequests as $req)
                <div class="emp-task-row">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:linear-gradient(135deg,#fbbf24,#f59e0b);
                                            box-shadow:0 3px 10px rgba(245,158,11,0.3),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-file-lines text-white text-xs"></i>
                        </div>
                        <div>
                            <span class="font-bold text-slate-700 text-sm">ARC-{{ $req->request_id }}{{ $atp->atp_id }}</span>
                            @if($req->request_name ?? false)
                                <p class="text-[11px] text-slate-400">{{ $req->request_name }}</p>
                            @endif
                        </div>
                    </div>
                    <span class="text-xs text-slate-500 font-medium">
                        {{ \Carbon\Carbon::parse($req->added_date)->format('d M Y') }}
                    </span>
                    <a href="{{ route('rc.portal.wizard.step1') }}" class="task-badge badge-open"
                        style="text-decoration:none; cursor:pointer;">
                        Continue →
                    </a>
                </div>
            @empty
                <div style="padding:3.5rem 1.5rem; text-align:center;">
                    <div class="w-14 h-14 rounded-2xl mx-auto mb-3 flex items-center justify-center"
                        style="background:linear-gradient(135deg,rgba(0,79,104,0.06),rgba(0,106,138,0.08));">
                        <i class="fa-solid fa-inbox text-2xl text-slate-300"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-400">No active requests</p>
                    <p class="text-xs text-slate-300 mt-1">You have no pending tasks at the moment.</p>
                </div>
            @endforelse
        </div>


        {{-- ── Side Panel ── --}}
        <div class="space-y-6">

            {{-- ATP Info Card (dark, like emp's holiday card) --}}
            <div class="atp-info-card">
                <div class="flex items-center gap-3 mb-4 relative z-10">
                    <div style="width:48px; height:48px;
                                    background:linear-gradient(145deg,rgba(0,136,179,0.3),rgba(0,79,104,0.15));
                                    border:1.5px solid rgba(0,136,179,0.4);
                                    box-shadow:0 4px 16px rgba(0,0,0,0.2),inset 0 1px 0 rgba(255,255,255,0.1);
                                    border-radius:14px;
                                    display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fa-solid fa-building-columns text-sky-400 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-white text-sm leading-none">Institution Details</h4>
                        <p class="text-slate-400 text-xs mt-1">{{ $atp->atp_name }}</p>
                    </div>
                </div>

                <div style="position:relative; z-index:10;">
                    <div class="atp-info-row">
                        <span class="text-xs text-slate-400 font-medium">Emirate</span>
                        <span class="text-xs font-bold text-white">{{ $atp->emirate->city_name ?? '—' }}</span>
                    </div>
                    <div class="atp-info-row">
                        <span class="text-xs text-slate-400 font-medium">Category</span>
                        <span class="text-xs font-bold text-white">{{ $atp->category->atp_category_name ?? '—' }}</span>
                    </div>
                    <div class="atp-info-row">
                        <span class="text-xs text-slate-400 font-medium">Type</span>
                        <span class="text-xs font-bold text-white">{{ $atp->type->atp_type_name ?? '—' }}</span>
                    </div>
                    <div class="atp-info-row">
                        <span class="text-xs text-slate-400 font-medium">Email</span>
                        <span class="text-xs font-bold text-sky-400 truncate max-w-[130px]">{{ $atp->atp_email }}</span>
                    </div>
                    <div class="atp-info-row">
                        <span class="text-xs text-slate-400 font-medium">Phone</span>
                        <span class="text-xs font-bold text-white">{{ $atp->atp_phone ?? '—' }}</span>
                    </div>
                    <div class="atp-info-row">
                        <span class="text-xs text-slate-400 font-medium">Registered</span>
                        <span class="text-xs font-bold text-white">
                            {{ $atp->added_date ? \Carbon\Carbon::parse($atp->added_date)->format('d M Y') : '—' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection