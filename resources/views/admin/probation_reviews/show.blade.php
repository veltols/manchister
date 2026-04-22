@extends('layouts.app')
@section('title', 'GM — Review Decision')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('admin.probation-reviews.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-600"><i class="fa-solid fa-arrow-left"></i> Back</a>

    <div class="premium-card p-6 bg-gradient-to-r {{ $review->status_color }} text-white">
        <p class="text-white/70 text-xs uppercase tracking-widest mb-1">Review #{{ $review->review_id }}</p>
        <h2 class="text-2xl font-black">{{ $review->review_title }}</h2>
        <p class="text-white/70 text-sm mt-1">{{ $review->status_label }}</p>
    </div>

    <div class="premium-card p-6">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Employee</h3>
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-gradient-brand flex items-center justify-center text-white font-black text-lg">{{ substr($review->employee->first_name ?? 'U', 0, 1) }}</div>
            <div>
                <h3 class="font-bold text-slate-800">{{ $review->employee->full_name ?? 'N/A' }}</h3>
                <p class="text-sm text-slate-500">{{ $review->employee->designation->designation_name ?? '' }} • {{ $review->employee->department->department_name ?? '' }}</p>
                @if($review->probation_type)<span class="mt-1 inline-block px-2 py-0.5 rounded bg-amber-100 text-amber-700 text-[10px] font-bold uppercase">{{ str_replace('_',' ',$review->probation_type) }}</span>@endif
                @if($review->probation_end_date)<p class="text-xs text-slate-500 mt-1">Probation End: {{ \Carbon\Carbon::parse($review->probation_end_date)->format('d M Y') }}</p>@endif
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
        <h3 class="text-xs font-bold text-amber-600 uppercase tracking-widest flex items-center gap-2"><i class="fa-solid fa-user-tie"></i> Line Manager — {{ $review->lineManager->first_name ?? '' }} {{ $review->lineManager->last_name ?? '' }}</h3>
        <div class="flex items-center gap-3"><span class="px-3 py-1 bg-amber-100 text-amber-800 font-bold text-sm rounded-lg">{{ $review->manager_rating }}</span></div>
        <div class="p-4 bg-amber-50 rounded-xl text-sm text-slate-700">{{ $review->manager_feedback }}</div>
        @if($review->manager_reviewed_at)<p class="text-xs text-slate-400">Reviewed {{ \Carbon\Carbon::parse($review->manager_reviewed_at)->format('d M Y H:i') }}</p>@endif
    </div>
    @endif

    @if($review->status === \App\Models\ProbationReview::STATUS_REVIEWED)
    <div class="premium-card p-6 space-y-4">
        <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2"><i class="fa-solid fa-gavel text-indigo-500"></i> Your Decision</h3>
        <div class="grid grid-cols-2 gap-4">

            {{-- Approve Form --}}
            <form id="approveForm" action="{{ route('admin.probation-reviews.approve', $review->review_id) }}" method="POST" class="space-y-3">
                @csrf
                <textarea id="approveComments" name="gm_comments" rows="4"
                    class="premium-input w-full px-4 py-2.5 text-sm"
                    placeholder="Comments for approval (optional)..."></textarea>
                <button type="button" onclick="confirmDecision('approve')"
                    class="w-full py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all">
                    <i class="fa-solid fa-check-circle mr-2"></i> Approve
                </button>
            </form>

            {{-- Reject Form --}}
            <form id="rejectForm" action="{{ route('admin.probation-reviews.reject', $review->review_id) }}" method="POST" class="space-y-3">
                @csrf
                <textarea id="rejectComments" name="gm_comments" rows="4" required
                    class="premium-input w-full px-4 py-2.5 text-sm"
                    placeholder="Reason for rejection (required)..."></textarea>
                <button type="button" onclick="confirmDecision('reject')"
                    class="w-full py-3 bg-gradient-to-r from-rose-500 to-red-600 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all">
                    <i class="fa-solid fa-times-circle mr-2"></i> Reject
                </button>
            </form>

        </div>
    </div>
    @elseif($review->gm_comments)
    <div class="premium-card p-6 space-y-3 border-l-4 {{ $review->status === 'approved' ? 'border-emerald-400' : 'border-rose-400' }}">
        <h3 class="text-xs font-bold {{ $review->status === 'approved' ? 'text-emerald-600' : 'text-rose-600' }} uppercase tracking-widest">Your Decision — {{ $review->status_label }}</h3>
        <div class="p-4 {{ $review->status === 'approved' ? 'bg-emerald-50' : 'bg-rose-50' }} rounded-xl text-sm text-slate-700">{{ $review->gm_comments }}</div>
        @if($review->gm_reviewed_at)<p class="text-xs text-slate-400">Decided {{ \Carbon\Carbon::parse($review->gm_reviewed_at)->format('d M Y H:i') }}</p>@endif
    </div>
    @endif
</div>

@push('scripts')
<script>
function confirmDecision(type) {
    const empName = @json($review->employee->full_name ?? 'this employee');

    if (type === 'approve') {
        Swal.fire({
            title: 'Approve Probation Review?',
            html: `You are about to <strong>approve</strong> the probation performance review for <strong>${empName}</strong>.<br><br>This action will notify HR, the Line Manager, and the Employee.`,
            icon: 'question',
            iconColor: '#10b981',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa-solid fa-check-circle mr-1"></i> Yes, Approve',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl font-bold px-6 py-2.5',
                cancelButton: 'rounded-xl font-bold px-6 py-2.5',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Processing...',
                    text: 'Submitting approval decision.',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
                document.getElementById('approveForm').submit();
            }
        });

    } else {
        const rejectReason = document.getElementById('rejectComments').value.trim();

        if (!rejectReason) {
            Swal.fire({
                title: 'Reason Required',
                text: 'Please provide a reason for rejection before proceeding.',
                icon: 'warning',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'OK',
                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl font-bold px-6 py-2.5' }
            });
            document.getElementById('rejectComments').focus();
            return;
        }

        Swal.fire({
            title: 'Reject Probation Review?',
            html: `You are about to <strong>reject</strong> the probation performance review for <strong>${empName}</strong>.<br><br>HR, the Line Manager, and the Employee will be notified with your comments.`,
            icon: 'warning',
            iconColor: '#ef4444',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa-solid fa-times-circle mr-1"></i> Yes, Reject',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl font-bold px-6 py-2.5',
                cancelButton: 'rounded-xl font-bold px-6 py-2.5',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Submitting rejection decision.',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
                document.getElementById('rejectForm').submit();
            }
        });
    }
}
</script>
@endpush
@endsection
