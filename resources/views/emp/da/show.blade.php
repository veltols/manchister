@extends('layouts.app')

@section('title', 'Disciplinary Action Details')
@section('subtitle', 'Reference ID: ' . $action->da_id)

@section('content')
    <div class="max-w-4xl mx-auto animate-fade-in-up">

        <div class="premium-card overflow-hidden">
            <!-- Banner -->
            <div class="h-32 bg-gradient-to-r from-red-600 to-red-800 p-8 flex items-end justify-between">
                <h2 class="text-white text-3xl font-display font-bold">Formal Action Record</h2>
                <div
                    class="px-4 py-2 bg-white/20 backdrop-blur-md rounded-xl text-white font-bold text-sm border border-white/30">
                    {{ $action->status->status_name ?? 'Active' }}
                </div>
            </div>

            <div class="p-8 space-y-10">
                <!-- Grid Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Action Date</span>
                        <p class="text-lg font-bold text-slate-800">{{ $action->added_date }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Warning Level</span>
                        <p class="text-lg font-bold text-red-600">{{ $action->warning->da_warning_name ?? 'N/A' }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Violation Type</span>
                        <p class="text-lg font-bold text-slate-800">{{ $action->type->da_type_code ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Full Description -->
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Official Remarks & Case
                        Details</h3>
                    <div
                        class="p-8 bg-slate-50 rounded-3xl border border-slate-100 text-slate-700 leading-relaxed text-lg italic shadow-inner">
                        "{!! nl2br(e($action->da_remark)) !!}"
                    </div>
                </div>

                <!-- Type Description -->
                @if($action->type && $action->type->da_type_text)
                    <div class="p-6 rounded-2xl bg-amber-50 border border-amber-100 flex gap-4">
                        <i class="fa-solid fa-circle-info text-amber-500 mt-1"></i>
                        <div class="space-y-1">
                            <h4 class="text-sm font-bold text-amber-800">Violation Definition</h4>
                            <p class="text-sm text-amber-700/80">{{ $action->type->da_type_text }}</p>
                        </div>
                    </div>
                @endif

                <!-- Footer Note -->
                <div class="pt-10 border-t border-slate-100 flex flex-col items-center text-center">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <p class="text-xs text-slate-400 max-w-lg">This is an official record of IQC Sense. Disciplinary
                        actions are kept on file and may influence future appraisals or contract renewals. If you wish to
                        appeal this action, please contact HR within 48 hours.</p>
                    <div class="mt-6 flex gap-4">
                        <a href="{{ route('emp.da.index') }}"
                            class="px-6 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs hover:bg-slate-200 transition-all">Back
                            to List</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
