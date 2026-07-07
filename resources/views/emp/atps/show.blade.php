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
<div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up" x-data="{ selectedForm: 'todo_0' }">
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

            <!-- Form Navigation Tabs -->
            <div class="flex flex-wrap items-center gap-4 mb-6 w-full border-b border-slate-100 pb-4">
                @if($showTodos && $todos->count())
                    @foreach($todos as $index => $todo)
                        <button @click="selectedForm = 'todo_{{ $index }}'; setTimeout(() => document.getElementById('iframe-container').scrollIntoView({behavior: 'smooth', block: 'start'}), 100);" 
                                :class="selectedForm === 'todo_{{ $index }}' ? 'bg-[#005c75] text-white shadow-md' : 'text-slate-500 hover:text-[#005c75] hover:bg-slate-50 bg-transparent'"
                                class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                            <i class="fa-solid {{ $todo->isDone ? 'fa-check-circle text-emerald-400' : 'fa-circle-dot' }}" :class="selectedForm === 'todo_{{ $index }}' ? 'text-white/80' : 'text-slate-300'"></i>
                            {{ $todo->title }}
                        </button>
                    @endforeach
                @endif
            </div>

            <!-- Content Area -->
            <div class="space-y-6 relative">
                <div class="animate-fade-in-up">
                    @if($showTodos && $todos->count())
                        <div class="bg-white rounded-2xl shadow-[0_2px_15px_rgba(0,0,0,0.02)] border border-slate-100 overflow-hidden mb-8">
                            <table class="w-full text-left">
                                <thead class="bg-white border-b border-slate-50">
                                    <tr>
                                        <th class="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-1/2">Application Name</th>
                                        <th class="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                                        <th class="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50/60 bg-white">
                                    @foreach($todos as $index => $todo)
                                    <tr class="hover:bg-slate-50/30 transition-colors group">
                                        <td class="px-8 py-6">
                                            <span class="text-sm font-medium text-[#005c75]">{{ $todo->title }}</span>
                                        </td>
                                        <td class="px-8 py-6 text-center">
                                            @if($todo->isDone)
                                                <span class="px-3 py-1 rounded border border-emerald-100 text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600">
                                                    COMPLETED
                                                </span>
                                            @else
                                                <span class="px-3 py-1 rounded border border-orange-100 text-[10px] font-bold uppercase tracking-wider bg-[#fff7ed] text-[#fb923c]">
                                                    PENDING_SUBMISSION
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <button @click="selectedForm = 'todo_{{ $index }}'; setTimeout(() => document.getElementById('iframe-container').scrollIntoView({behavior: 'smooth', block: 'start'}), 100);" 
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#ecfdf5] text-[#10b981] hover:bg-[#10b981] hover:text-white transition-all shadow-sm">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div id="iframe-container" class="space-y-6">
                            @foreach($todos as $index => $todo)
                                <div x-show="selectedForm === 'todo_{{ $index }}'" class="animate-fade-in-up w-full h-[800px] mb-8" x-cloak>
                                    <div class="premium-card h-full w-full overflow-hidden border border-slate-100 shadow-sm rounded-2xl bg-white">
                                        <iframe src="{{ $todo->todo_link }}" class="w-full h-full border-0"></iframe>
                                    </div>
                                </div>
                            @endforeach
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
