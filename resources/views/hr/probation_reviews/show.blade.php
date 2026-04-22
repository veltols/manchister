@extends('layouts.app')
@section('title', 'Probation Review Details')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('hr.probation-reviews.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-600">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
    <div class="premium-card p-6 bg-gradient-to-r {{ $review->status_color }} text-white">
        <p class="text-white/70 text-xs uppercase tracking-widest mb-1">Status</p>
        <h2 class="text-2xl font-black">{{ $review->status_label }}</h2>
    </div>
    <div class="premium-card p-6">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Employee</h3>
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-gradient-brand flex items-center justify-center text-white font-black text-lg">{{ substr($review->employee->first_name ?? 'U', 0, 1) }}</div>
            <div>
                <h3 class="font-bold text-slate-800">{{ $review->employee->full_name ?? 'N/A' }}</h3>
                <p class="text-sm text-slate-500">{{ $review->employee->department->department_name ?? '' }}</p>
            </div>
        </div>
    </div>
    <div class="premium-card p-6 space-y-4">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">HR Review Content</h3>
        <div><p class="text-xs font-bold text-slate-400 uppercase mb-1">Objectives</p><div class="p-4 bg-slate-50 rounded-xl text-sm text-slate-700">{{ $review->objectives }}</div></div>
        <div><p class="text-xs font-bold text-slate-400 uppercase mb-1">KPIs</p><div class="p-4 bg-slate-50 rounded-xl text-sm text-slate-700">{{ $review->kpis }}</div></div>
        @if($review->hr_notes)<div><p class="text-xs font-bold text-slate-400 uppercase mb-1">HR Notes</p><div class="p-4 bg-indigo-50 rounded-xl text-sm text-indigo-900">{{ $review->hr_notes }}</div></div>@endif
    </div>
    @if($review->manager_feedback)
    <div class="premium-card p-6 space-y-3 border-l-4 border-amber-400">
        <h3 class="text-xs font-bold text-amber-600 uppercase tracking-widest">Line Manager Assessment</h3>
        <div class="flex items-center gap-3"><span class="text-xs font-bold text-slate-400 uppercase">Rating:</span><span class="px-3 py-1 bg-amber-100 text-amber-800 font-bold text-sm rounded-lg">{{ $review->manager_rating }}</span></div>
        <div class="p-4 bg-amber-50 rounded-xl text-sm text-slate-700">{{ $review->manager_feedback }}</div>
    </div>
    @endif
    @if($review->gm_comments)
    <div class="premium-card p-6 space-y-3 border-l-4 {{ $review->status === 'approved' ? 'border-emerald-400' : 'border-rose-400' }}">
        <h3 class="text-xs font-bold {{ $review->status === 'approved' ? 'text-emerald-600' : 'text-rose-600' }} uppercase tracking-widest">GM Decision — {{ $review->status_label }}</h3>
        <div class="p-4 {{ $review->status === 'approved' ? 'bg-emerald-50' : 'bg-rose-50' }} rounded-xl text-sm text-slate-700">{{ $review->gm_comments }}</div>
    </div>
    @endif
</div>
@endsection
