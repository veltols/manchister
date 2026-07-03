@extends('layouts.app')

@section('title', 'ATP Details')
@section('subtitle', $atp->atp_name)

@push('styles')
    <style>
        /* ── Phase Stepper ── */
        .phase-stepper {
            display: flex;
            align-items: center;
            gap: 0;
            padding: 2.5rem 2rem;
            background: linear-gradient(135deg, #004F68 0%, #00384a 50%, #002233 100%);
            position: relative;
            z-index: 10;
        }

        .phase-stepper::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.4;
        }

        .phase-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
            min-width: 120px;
            position: relative;
            z-index: 2;
        }

        .phase-step::after {
            content: '';
            position: absolute;
            top: 22px;
            left: calc(50% + 25px);
            width: calc(100% - 50px);
            height: 3px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .phase-step:last-child::after {
            display: none;
        }

        .phase-step.done::after {
            background: linear-gradient(90deg, #10b981, rgba(16, 185, 129, 0.2));
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
        }

        .phase-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(8px);
            color: rgba(255, 255, 255, 0.4);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }

        .phase-step.done .phase-icon {
            background: #10b981;
            border-color: #10b981;
            color: white;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
            transform: translateY(-2px);
        }

        .phase-step.active .phase-icon {
            background: white;
            border-color: white;
            color: #004F68;
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.4);
            transform: scale(1.1) translateY(-4px);
        }

        .phase-step.active .phase-icon::before {
            content: '';
            position: absolute;
            inset: -6px;
            border: 2px solid white;
            border-radius: 20px;
            opacity: 0.3;
            animation: pulse-ring 2s infinite;
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.5; }
            50% { transform: scale(1.05); opacity: 0.2; }
            100% { transform: scale(0.95); opacity: 0.5; }
        }

        .phase-label {
            font-size: 11px;
            font-weight: 800;
            color: rgba(255, 255, 255, 0.4);
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            transition: all 0.3s ease;
        }

        .phase-step.done .phase-label { color: #10b981; }
        .phase-step.active .phase-label { color: white; transform: translateY(2px); }

        /* ── Content Panels ── */
        .info-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            height: 100%;
            transition: all 0.3s ease;
        }

        .timeline-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.75rem 1.25rem;
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 12px;
            color: #92400e;
        }

        .rc-comment-card {
            background: #fff1f2;
            border: 1px solid #ffe4e6;
            border-radius: 16px;
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
        }

        .rc-comment-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 4px; height: 100%;
            background: #fb7185;
        }

        /* ── Todo List ── */
        .todo-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem;
            border-radius: 18px;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            margin-bottom: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .todo-item:hover {
            background: white;
            border-color: #e2e8f0;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            transform: translateX(8px);
        }

        .todo-item.done {
            background: linear-gradient(to right, #f0fdf4, #ffffff);
            border-color: #dcfce7;
        }

        .todo-btn {
            padding: 0.5rem 1.25rem;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-fill { background: #004F68; color: white; }
        .btn-fill:hover { background: #00384a; transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0, 79, 104, 0.25); }

        .btn-edit { background: #e0f2fe; color: #0369a1; }
        .btn-edit:hover { background: #bae6fd; transform: translateY(-2px); }

        .btn-submit { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; box-shadow: 0 8px 20px rgba(217, 119, 6, 0.3); }
        .btn-submit:hover:not(.disabled) { transform: translateY(-2px); box-shadow: 0 12px 25px rgba(217, 119, 6, 0.4); }
        .btn-submit.disabled { opacity: 0.5; filter: grayscale(1); cursor: not-allowed; }

        /* ── Progress Glow ── */
        .glow-line {
            height: 4px;
            background: rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
        }

        .glow-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #34d399, #10b981);
            background-size: 200% 100%;
            animation: move-glow 3s linear infinite;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.6);
            transition: width 1.5s ease;
        }

        @keyframes move-glow {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up" x-data="{ activeTab: 'accreditation' }">
    <!-- ATP Header & Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Main Stats & Info -->
        <div class="lg:col-span-3 space-y-8">
            <div class="premium-card p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-20 h-20 rounded-3xl bg-brand/5 flex items-center justify-center text-brand border border-brand/10 shadow-inner">
                            <i class="fa-solid fa-building-columns text-4xl"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $atp->atp_ref }}</div>
                            <h2 class="text-3xl font-bold text-premium tracking-tight">{{ $atp->atp_name }}</h2>
                            <div class="flex items-center gap-4 mt-2">
                                <span class="text-sm font-medium text-slate-500 flex items-center gap-2">
                                    <i class="fa-solid fa-location-dot text-brand"></i>
                                    {{ $atp->emirate->city_name ?? 'N/A' }}
                                </span>
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                <span class="text-sm font-medium text-slate-500">
                                    Added on {{ \Carbon\Carbon::parse($atp->added_date)->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-3">
                        <span class="px-5 py-2 rounded-2xl text-xs font-bold uppercase tracking-widest
                            {{ $atp->atp_status_id == 1 ? 'bg-amber-50 text-amber-600 border border-amber-100' : 
                               ($atp->atp_status_id == 4 ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-blue-50 text-blue-600 border border-blue-100') }}">
                            {{ $atp->status->atp_status_name ?? 'Unknown' }}
                        </span>
                        <div class="text-[10px] font-bold text-slate-400 uppercase flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-brand"></i>
                            Phase Verified
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Bar -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="premium-card p-6 flex items-center gap-4 border-l-4 border-l-brand">
                    <div class="w-12 h-12 rounded-2xl bg-brand/5 flex items-center justify-center text-brand">
                        <i class="fa-solid fa-file-contract text-xl"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Apps</div>
                        <div class="text-xl font-bold text-premium">{{ count($apps) }} Active</div>
                    </div>
                </div>
                <div class="premium-card p-6 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                        <i class="fa-solid fa-user-graduate text-xl"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Learners</div>
                        <div class="text-xl font-bold text-premium">{{ $leRecords->count() }} Records</div>
                    </div>
                </div>
                <div class="premium-card p-6 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500">
                        <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Audit Log</div>
                        <div class="text-xl font-bold text-premium">{{ $logs->count() }} Actions</div>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="flex flex-wrap items-center gap-2 p-1 bg-slate-100/50 rounded-2xl w-full">
                <button @click="activeTab = 'accreditation'" 
                        :class="activeTab === 'accreditation' ? 'bg-gradient-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white'"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-route"></i>
                    Accreditation Journey
                </button>
                <button @click="activeTab = 'apps'" 
                        :class="activeTab === 'apps' ? 'bg-gradient-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white'"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-list-check"></i>
                    Applications
                </button>
                <button @click="activeTab = 'renewals'" 
                        :class="activeTab === 'renewals' ? 'bg-gradient-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white'"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-rotate"></i>
                    Renewals
                </button>
                <button @click="activeTab = 'cancellations'" 
                        :class="activeTab === 'cancellations' ? 'bg-gradient-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white'"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-ban"></i>
                    Cancellations
                </button>
                <button @click="activeTab = 'le'" 
                        :class="activeTab === 'le' ? 'bg-gradient-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white'"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap"></i>
                    Learner Enrollment
                </button>
                <button @click="activeTab = 'contacts'" 
                        :class="activeTab === 'contacts' ? 'bg-gradient-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white'"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-address-book"></i>
                    Contacts
                </button>
                <button @click="activeTab = 'logs'" 
                        :class="activeTab === 'logs' ? 'bg-gradient-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand hover:bg-white'"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-history"></i>
                    Logs
                </button>
            </div>

            <!-- Tab Content -->
            <div class="space-y-6">
                <!-- Accreditation Tab -->
                <div x-show="activeTab === 'accreditation'" class="animate-fade-in-up" x-cloak>
                    @php
                        $phaseCount = $phases->count();
                        $doneCount = $phases->filter(function ($p) use ($currentPhaseId) {
                            return $p->phase_id < $currentPhaseId;
                        })->count();
                        $progressPct = $phaseCount > 1 ? round(($doneCount / ($phaseCount - 1)) * 100) : 0;
                    @endphp

                    <div class="overflow-hidden bg-transparent">
                        {{-- Stepper Header --}}
                        <div style="border-radius:24px 24px 0 0; overflow:hidden; border:1px solid rgba(0,0,0,0.05); background: white;">
                            <div class="phase-stepper">
                                @foreach($phases as $phase)
                                    @php
                                        $state = 'pending';
                                        if ($phase->phase_id < $currentPhaseId) $state = 'done';
                                        elseif ($phase->phase_id == $currentPhaseId) $state = 'active';

                                        $phaseIcons = [
                                            1 => 'fa-file-signature',
                                            2 => 'fa-book-open-reader',
                                            3 => 'fa-building-shield',
                                            4 => 'fa-certificate',
                                        ];

                                        $icon = $phaseIcons[$phase->phase_id] ?? 'fa-circle-dot';

                                        if ($state === 'done') $icon = 'fa-check';
                                        if ($state === 'active' && $is_phase_ok == 0) $icon = 'fa-triangle-exclamation';
                                    @endphp
                                    <div class="phase-step {{ $state }}">
                                        <div class="phase-icon">
                                            <i class="fa-solid {{ $icon }}"></i>
                                        </div>
                                        <span class="phase-label">{{ $phase->phase_title }}</span>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Progress Bar --}}
                            <div class="glow-line" style="background: rgba(0,0,0,0.05);">
                                <div class="glow-fill" style="width: {{ $progressPct }}%;"></div>
                            </div>
                        </div>

                        {{-- ── Body ── --}}
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-6">

                            {{-- LEFT: Phase Info Card --}}
                            <div class="info-card shadow-sm border border-slate-100">
                                @if($currentPhase)
                                    <div class="flex items-center gap-4 mb-8">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white flex-shrink-0"
                                            style="background:linear-gradient(135deg,#004F68,#006a8a); box-shadow:0 10px 20px rgba(0,79,104,0.15);">
                                            <i class="fa-solid fa-compass text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-slate-400 uppercase tracking-[0.2em] font-black mb-1">Navigation / Focus</p>
                                            <h2 class="text-xl font-black text-slate-800 tracking-tight">{{ $currentPhase->phase_title }}</h2>
                                        </div>
                                    </div>

                                    <div class="prose prose-slate max-w-none">
                                        <p class="text-[15px] text-slate-500 leading-relaxed font-medium">
                                            {{ $currentPhase->phase_description }}
                                        </p>
                                    </div>

                                    <div class="mt-8 space-y-4">
                                        <div class="timeline-badge">
                                            <i class="fa-solid fa-calendar-check text-amber-500"></i>
                                            <span class="text-xs font-bold uppercase tracking-wider">Estimated Timeline:</span>
                                            <span class="text-sm font-semibold">{{ $currentPhase->phase_timeline }}</span>
                                        </div>

                                        @if(in_array($form_status, ['review', 'rejected']) && $rc_comment)
                                            <div class="rc-comment-card mt-6">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <i class="fa-solid fa-message-exclamation text-rose-500"></i>
                                                    <span class="text-[10px] font-black uppercase tracking-widest text-rose-600">Reviewer Feedback</span>
                                                </div>
                                                <p class="text-sm text-slate-700 leading-relaxed font-medium">{!! nl2br(e($rc_comment)) !!}</p>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center h-full text-center py-12">
                                        <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-face-smile-wink text-3xl text-slate-200"></i>
                                        </div>
                                        <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Waiting for initialization</p>
                                    </div>
                                @endif
                            </div>

                            {{-- RIGHT: Tasks Card --}}
                            <div class="info-card shadow-sm border border-slate-100">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white flex-shrink-0"
                                        style="background:linear-gradient(135deg,#7c38ed,#9333ea); box-shadow:0 10px 20px rgba(124,56,237,0.15);">
                                        <i class="fa-solid fa-tasks text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-slate-400 uppercase tracking-[0.2em] font-black mb-1">Execution / Status</p>
                                        <h2 class="text-xl font-black text-slate-800 tracking-tight">Requirement Checklist</h2>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    @if($form_status === 'rejected')
                                        <div class="text-center py-12 px-6 rounded-3xl bg-rose-50/50 border border-rose-100">
                                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                                <i class="fa-solid fa-xmark text-rose-500 text-2xl"></i>
                                            </div>
                                            <h3 class="text-rose-900 font-black text-lg mb-1 tracking-tight">Phase Rejected</h3>
                                            <p class="text-rose-600/70 text-sm font-medium">Please address the feedback in the red box on the left.</p>
                                        </div>

                                    @elseif($showTodos && $todos->count())
                                        @php $globalAllGood = true; @endphp
                                        @foreach($todos as $todo)
                                            @php
                                                $isSubmit = $todo->is_submit == 1;
                                                if (!$todo->isDone && !$isSubmit) $globalAllGood = false;
                                            @endphp
                                            <div class="todo-item {{ $todo->isDone ? 'done' : '' }}">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ $todo->isDone ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }}">
                                                        <i class="fa-solid {{ $todo->isDone ? 'fa-check' : ($isSubmit ? 'fa-hourglass' : 'fa-circle-dot') }} text-xs"></i>
                                                    </div>
                                                    <span class="text-sm font-bold text-slate-700 tracking-tight">{{ $todo->title }}</span>
                                                </div>

                                                @if(!$isSubmit)
                                                    <a href="{{ $todo->todo_link }}" class="todo-btn {{ $todo->isDone ? 'btn-edit' : 'btn-fill' }}">
                                                        <i class="fa-solid {{ $todo->isDone ? 'fa-pen-to-square' : 'fa-arrow-right' }}"></i>
                                                        <span>{{ $todo->isDone ? 'Modify' : 'Begin' }}</span>
                                                    </a>
                                                @else
                                                    <a href="{{ $todo->todo_link }}" class="todo-btn btn-submit {{ !$globalAllGood ? 'disabled' : '' }}">
                                                        <i class="fa-solid fa-paper-plane"></i>
                                                        <span>Finalize</span>
                                                    </a>
                                                @endif
                                            </div>
                                        @endforeach

                                    @elseif($currentPhaseId == 3)
                                        <div class="text-center py-12 px-6 rounded-3xl bg-indigo-50/50 border border-indigo-100">
                                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-md">
                                                <i class="fa-solid fa-building-shield text-indigo-500 text-3xl"></i>
                                            </div>
                                            @php
                                                $visitDate = \Illuminate\Support\Facades\DB::table('eqa_008')->where('atp_id', $atp->atp_id)->value('visit_date');
                                            @endphp
                                            @if($visitDate)
                                                <h3 class="text-indigo-900 font-black text-xl mb-1 tracking-tight">Visit Confirmed</h3>
                                                <div class="inline-block px-4 py-1 rounded-full bg-indigo-100 text-indigo-700 text-sm font-black mt-2">
                                                    {{ \Carbon\Carbon::parse($visitDate)->format('d M Y') }}
                                                </div>
                                                <p class="text-indigo-600/70 text-sm font-medium mt-4">Please ensure all facilities are ready for the audit.</p>
                                            @else
                                                <h3 class="text-indigo-900 font-black text-xl mb-1 tracking-tight">Visit Pending</h3>
                                                <p class="text-indigo-600/70 text-sm font-medium mt-2">The EQA department is reviewing your schedule.</p>
                                            @endif
                                        </div>

                                    @else
                                        <div class="text-center py-16">
                                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-100">
                                                <i class="fa-solid fa-shield-halved text-4xl text-slate-100"></i>
                                            </div>
                                            <h3 class="text-slate-400 font-black text-lg uppercase tracking-[0.15em]">Phase Locked</h3>
                                            <p class="text-slate-300 text-sm font-medium mt-2">Complete the current phase to unlock.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Applications Tab -->
                <div x-show="activeTab === 'apps'" class="animate-fade-in-up">
                    <div class="premium-card overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Application Name</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Type</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($apps as $app)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-8 py-6">
                                            <div class="font-bold text-premium">{{ $app['name'] }}</div>
                                            <div class="text-[10px] text-slate-400 mt-1">Added: {{ $app['start_date'] ? \Carbon\Carbon::parse($app['start_date'])->format('d M Y') : 'N/A' }}</div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500">{{ $app['type'] }}</span>
                                        </td>
                                        <td class="px-8 py-6 text-center">
                                            <span class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                                                {{ $app['form_status'] == 'approved' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                                {{ $app['form_status'] }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            @if($app['type'] == 'Program')
                                                <a href="{{ route('emp.atps.forms.registration-request', $atp->atp_id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                                    <i class="fa-solid fa-eye text-xs"></i>
                                                </a>
                                            @elseif($app['type'] == 'Compliance')
                                                <a href="{{ route('emp.atps.forms.sed', $atp->atp_id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                                    <i class="fa-solid fa-eye text-xs"></i>
                                                </a>
                                            @elseif($app['type'] == 'Initial')
                                                <a href="{{ route('emp.atps.forms.initial', $atp->atp_id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                                    <i class="fa-solid fa-eye text-xs"></i>
                                                </a>
                                            @else
                                                <button class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 text-slate-300 cursor-not-allowed text-xs" disabled>
                                                    <i class="fa-solid fa-eye-slash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-8 py-10 text-center text-slate-400 text-sm italic">No records found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Renewals Tab -->
                <div x-show="activeTab === 'renewals'" class="animate-fade-in-up">
                    <div class="premium-card overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Application</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Submitted Date</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($renewals as $renew)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-8 py-6 font-bold text-premium">Renewal Registration</td>
                                        <td class="px-8 py-6 text-sm text-slate-600">{{ $renew->submitted_date ?? '---' }}</td>
                                        <td class="px-8 py-6">
                                            <span class="px-3 py-1 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-600 uppercase">{{ $renew->form_status }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-8 py-10 text-center text-slate-400 text-sm italic">No renewal records found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Cancellations Tab -->
                <div x-show="activeTab === 'cancellations'" class="animate-fade-in-up">
                    <div class="premium-card overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Submit Date</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Date</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($cancellations as $cancel)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-8 py-6 text-sm text-slate-600">{{ $cancel->submission_date }}</td>
                                        <td class="px-8 py-6 text-sm text-slate-600">{{ $cancel->approved_date }}</td>
                                        <td class="px-8 py-6">
                                            <span class="px-3 py-1 rounded-lg text-[10px] font-bold bg-red-50 text-red-600 uppercase">{{ $cancel->cancel_status }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-8 py-10 text-center text-slate-400 text-sm italic">No cancellation requests</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- LE Tab -->
                <div x-show="activeTab === 'le'" class="animate-fade-in-up">
                    <div class="premium-card overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Qualification</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Cohort</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Learners</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Duration</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($leRecords as $le)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-8 py-6">
                                            <div class="font-bold text-premium">{{ $le->qualification_name }}</div>
                                            <div class="text-[10px] text-slate-400 mt-1">Submitted: {{ $le->submission_date }}</div>
                                        </td>
                                        <td class="px-8 py-6 text-sm text-slate-600">{{ $le->cohort }}</td>
                                        <td class="px-8 py-6">
                                            <span class="px-3 py-1 bg-slate-100 rounded-lg text-xs font-bold text-brand">{{ $le->learners_no }}</span>
                                        </td>
                                        <td class="px-8 py-6 text-xs text-slate-500 font-medium whitespace-nowrap">
                                            {{ $le->start_date }} → {{ $le->end_date }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-8 py-10 text-center text-slate-400 text-sm italic">No enrollment records found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Contacts Tab -->
                <div x-show="activeTab === 'contacts'" class="animate-fade-in-up">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($atp->contacts as $contact)
                        <div class="premium-card p-8 group">
                            <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6">Contact #{{ $loop->iteration }}</h4>
                            <div class="space-y-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-brand group-hover:text-white transition-all">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Name</div>
                                        <div class="text-sm font-bold text-premium">{{ $contact->contact_name }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                                        <i class="fa-solid fa-briefcase"></i>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Designation</div>
                                        <div class="text-sm font-bold text-premium">{{ $contact->contact_designation }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Email</div>
                                        <div class="text-sm font-bold text-premium">{{ $contact->contact_email }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full py-10 text-center text-slate-400 italic">No contacts registered.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Logs Tab -->
                <div x-show="activeTab === 'logs'" class="animate-fade-in-up">
                    <div class="premium-card overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Action</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Performed By</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-8 py-5 text-sm font-bold text-premium text-brand">{{ $log->log_action }}</td>
                                        <td class="px-8 py-5 text-xs text-slate-500">{{ \Carbon\Carbon::parse($log->log_date)->format('d M Y H:i') }}</td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-[10px] font-bold text-slate-400">
                                                    {{ substr($log->logger->first_name ?? 'S', 0, 1) }}
                                                </div>
                                                <span class="text-xs font-bold text-slate-600">{{ $log->logger->first_name ?? 'System' }} {{ $log->logger->last_name ?? '' }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-8 py-10 text-center text-slate-400 italic">No logs found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Summary -->
        <div class="space-y-8">
            <div class="premium-card p-6 bg-gradient-brand text-white shadow-xl shadow-brand/20">
                <h4 class="text-xs font-bold uppercase tracking-widest opacity-70 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved"></i>
                    Classification
                </h4>
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-inner">
                            <i class="fa-solid fa-tags text-white shadow-sm"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-medium opacity-60 uppercase tracking-wider">Category</div>
                            <div class="text-sm font-bold">{{ $atp->category->atp_category_name ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-inner">
                            <i class="fa-solid fa-shapes text-white shadow-sm"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-medium opacity-60 uppercase tracking-wider">Type</div>
                            <div class="text-sm font-bold">{{ $atp->type->atp_type_name ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="premium-card p-6">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6 px-1">Quick Actions</h4>
                <div class="space-y-3">
                    <form action="{{ route('emp.atps.send-email', $atp->atp_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full p-3.5 rounded-xl border border-slate-100 text-xs font-bold text-slate-600 hover:border-brand/30 hover:bg-slate-50 transition-all flex items-center gap-3 group">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-all">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            Send Credentials
                        </button>
                    </form>
                    
                    @if($atp->atp_status_id != 4)
                    <form action="{{ route('emp.atps.accredit', $atp->atp_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full p-3.5 rounded-xl border border-slate-100 text-xs font-bold text-slate-600 hover:border-brand/30 hover:bg-slate-50 transition-all flex items-center gap-3 group">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            Accredit Provider
                        </button>
                    </form>
                    @endif

                    <button class="w-full p-3.5 rounded-xl border border-slate-100 text-xs font-bold text-slate-600 hover:border-brand/30 hover:bg-slate-50 transition-all flex items-center gap-3 group">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-all">
                            <i class="fa-solid fa-print"></i>
                        </div>
                        Print Certificate
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
