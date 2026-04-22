@extends('layouts.app')
@section('title', 'Probation Review — Assessment')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('emp.probation-reviews.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-600"><i class="fa-solid fa-arrow-left"></i> Back</a>

    <div class="premium-card p-6 bg-gradient-to-r {{ $review->status_color }} text-white">
        <p class="text-white/70 text-xs uppercase tracking-widest mb-1">Review #{{ $review->review_id }}</p>
        <h2 class="text-2xl font-black">{{ $review->review_title }}</h2>
        <p class="text-white/70 text-sm mt-1">{{ $review->status_label }}</p>
    </div>

    <div class="premium-card p-6">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Employee Being Reviewed</h3>
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-gradient-brand flex items-center justify-center text-white font-black text-lg">{{ substr($review->employee->first_name ?? 'U', 0, 1) }}</div>
            <div>
                <h3 class="font-bold text-slate-800">{{ $review->employee->full_name ?? 'N/A' }}</h3>
                <p class="text-sm text-slate-500">{{ $review->employee->designation->designation_name ?? '' }} • {{ $review->employee->department->department_name ?? '' }}</p>
                @if($review->probation_type)<span class="mt-1 inline-block px-2 py-0.5 rounded bg-amber-100 text-amber-700 text-[10px] font-bold uppercase">{{ str_replace('_',' ',$review->probation_type) }}</span>@endif
            </div>
        </div>
    </div>

    <div class="premium-card p-6 space-y-4">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">HR Objectives & KPIs</h3>
        <div><p class="text-xs font-bold text-slate-400 uppercase mb-1">Objectives</p><div class="p-4 bg-slate-50 rounded-xl text-sm text-slate-700">{{ $review->objectives }}</div></div>
        <div><p class="text-xs font-bold text-slate-400 uppercase mb-1">KPIs</p><div class="p-4 bg-slate-50 rounded-xl text-sm text-slate-700">{{ $review->kpis }}</div></div>
        @if($review->hr_notes)<div><p class="text-xs font-bold text-slate-400 uppercase mb-1">HR Notes</p><div class="p-4 bg-indigo-50 rounded-xl text-sm text-indigo-900">{{ $review->hr_notes }}</div></div>@endif
    </div>

    @if($review->status === \App\Models\ProbationReview::STATUS_PENDING_MANAGER)
    <div class="premium-card p-6">
        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2"><i class="fa-solid fa-star text-amber-500"></i> Your Assessment</h3>
        <form action="{{ route('emp.probation-reviews.submit', $review->review_id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="premium-label">Rating <span class="text-rose-500">*</span></label>
                <select name="manager_rating" class="premium-input w-full px-4 py-2.5 text-sm" required>
                    <option value="">Select Rating...</option>
                    <option value="Excellent">⭐⭐⭐⭐⭐ Excellent</option>
                    <option value="Very Good">⭐⭐⭐⭐ Very Good</option>
                    <option value="Good">⭐⭐⭐ Good</option>
                    <option value="Satisfactory">⭐⭐ Satisfactory</option>
                    <option value="Needs Improvement">⭐ Needs Improvement</option>
                </select>
            </div>
            <div>
                <label class="premium-label">Detailed Feedback <span class="text-rose-500">*</span></label>
                <textarea name="manager_feedback" rows="5" required class="premium-input w-full px-4 py-2.5 text-sm"
                          placeholder="Provide detailed assessment of performance against objectives and KPIs..."></textarea>
            </div>
            <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl text-sm text-blue-800 flex gap-3">
                <i class="fa-solid fa-circle-info mt-0.5"></i>
                <p>Your assessment will be forwarded to the <strong>General Manager</strong> for final approval. This action cannot be undone.</p>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('emp.probation-reviews.index') }}" class="px-6 py-2.5 rounded-xl font-bold text-slate-500 hover:text-slate-700">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-brand text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all border border-white/10">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Submit & Forward to GM
                </button>
            </div>
        </form>
    </div>
    @elseif($review->manager_feedback)
    <div class="premium-card p-6 space-y-3 border-l-4 border-amber-400">
        <h3 class="text-xs font-bold text-amber-600 uppercase tracking-widest">Your Submitted Assessment</h3>
        <div class="flex items-center gap-3"><span class="px-3 py-1 bg-amber-100 text-amber-800 font-bold text-sm rounded-lg">{{ $review->manager_rating }}</span></div>
        <div class="p-4 bg-amber-50 rounded-xl text-sm text-slate-700">{{ $review->manager_feedback }}</div>
    </div>
    @endif

    @if($review->gm_comments)
    <div class="premium-card p-6 space-y-3 border-l-4 {{ $review->status === 'approved' ? 'border-emerald-400' : 'border-rose-400' }}">
        <h3 class="text-xs font-bold {{ $review->status === 'approved' ? 'text-emerald-600' : 'text-rose-600' }} uppercase tracking-widest">GM Final Decision</h3>
        <div class="p-4 {{ $review->status === 'approved' ? 'bg-emerald-50' : 'bg-rose-50' }} rounded-xl text-sm text-slate-700">{{ $review->gm_comments }}</div>
    </div>
    @endif
</div>
@endsection
