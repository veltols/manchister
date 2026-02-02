@extends('layouts.app')

@section('title', 'Communication Record')
@section('subtitle', $request->communication_code)

@section('content')
    <div class="max-w-5xl mx-auto space-y-8 animate-fade-in-up">

        <!-- Top Card -->
        <div class="premium-card p-10 bg-white border-l-8 border-teal-500 shadow-xl">
            <div class="flex flex-col md:flex-row justify-between items-start gap-8">
                <div class="space-y-4 flex-1">
                    <div class="flex items-center gap-3">
                        <span
                            class="px-3 py-1 bg-teal-50 text-teal-700 rounded-lg font-mono text-sm font-bold">{{ $request->communication_code }}</span>
                        <span class="text-slate-300">•</span>
                        <span
                            class="text-sm font-bold text-slate-400 uppercase tracking-widest">{{ $request->type->communication_type_name ?? '-' }}</span>
                    </div>
                    <h1 class="text-4xl font-display font-bold text-premium leading-tight">
                        {{ $request->communication_subject }}
                    </h1>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-building text-slate-400"></i>
                            <span class="text-slate-600 font-bold">{{ $request->external_party_name }}</span>
                        </div>
                        <div class="flex items-center gap-2 border-l border-slate-100 pl-6">
                            <i class="fa-solid fa-calendar text-slate-400"></i>
                            <span class="text-slate-600 font-medium">{{ $request->requested_date }}</span>
                        </div>
                    </div>
                </div>

                <div
                    class="flex flex-col items-center gap-4 bg-slate-50 p-6 rounded-3xl border border-slate-100 min-w-[200px]">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Current Approval
                        Status</span>
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl shadow-lg mb-2"
                        style="background: #{{ $request->status->status_color ?? '64748b' }};">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                    <span
                        class="font-bold text-slate-800 text-lg">{{ $request->status->communication_status_name ?? 'Pending' }}</span>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Description -->
            <div class="premium-card p-8">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-align-left text-teal-500"></i>
                    Description
                </h3>
                <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed font-medium">
                    {!! nl2br(e($request->communication_description)) !!}
                </div>
            </div>

            <!-- Information Shared -->
            <div class="premium-card p-8 bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-share-nodes text-teal-500"></i>
                    Information Shared
                </h3>
                <div class="p-6 bg-white rounded-2xl border border-slate-100 text-slate-600 italic">
                    {!! nl2br(e($request->information_shared)) !!}
                </div>
            </div>
        </div>

        <!-- Approval Progress (Legacy placeholder) -->
        <div class="premium-card p-8">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-8 text-center">Multi-Level Approval
                Flow</h3>
            <div class="flex flex-wrap items-center justify-center gap-8 md:gap-16">
                <!-- Stage 1 -->
                <div class="flex flex-col items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-full {{ $request->is_approved_1 == 1 ? 'bg-green-500 text-white' : 'bg-slate-100 text-slate-300' }} flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <span
                        class="text-xs font-bold {{ $request->is_approved_1 == 1 ? 'text-green-600' : 'text-slate-400' }}">Dept
                        Manager</span>
                </div>

                <div class="h-0.5 w-12 bg-slate-100 hidden md:block"></div>

                <!-- Stage 2 -->
                <div class="flex flex-col items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-full {{ $request->is_approved_2 == 1 ? 'bg-green-500 text-white' : 'bg-slate-100 text-slate-300' }} flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <span
                        class="text-xs font-bold {{ $request->is_approved_2 == 1 ? 'text-green-600' : 'text-slate-400' }}">Admin
                        Office</span>
                </div>

                <div class="h-0.5 w-12 bg-slate-100 hidden md:block"></div>

                <!-- Stage 3 (Final) -->
                <div class="flex flex-col items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-full {{ $request->communication_status_id == 4 ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-300' }} flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-flag-checkered"></i>
                    </div>
                    <span
                        class="text-xs font-bold {{ $request->communication_status_id == 4 ? 'text-blue-600' : 'text-slate-400' }}">Completed</span>
                </div>
            </div>
        </div>

    </div>
@endsection
