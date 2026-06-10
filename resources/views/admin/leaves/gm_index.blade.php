@extends('layouts.app')
@section('title', 'GM — Leave Approvals')
@section('subtitle', 'Final leave decision queue')

@section('content')
@php $routePrefix = request()->routeIs('emp.*') ? 'emp.' : 'admin.'; @endphp
<div class="space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-premium flex items-center gap-3">
                <i class="fa-solid fa-crown text-amber-500"></i> GM Leave Queue
            </h2>
            <p class="text-sm text-slate-500 mt-1">Review leave requests approved by Line Managers and make final decisions</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="premium-card p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-clock text-amber-600 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800">{{ $stats['pending_gm'] }}</p>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Awaiting Decision</p>
            </div>
        </div>
        <div class="premium-card p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-check-circle text-emerald-600 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800">{{ $stats['approved'] }}</p>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">You Approved</p>
            </div>
        </div>
        <div class="premium-card p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-times-circle text-rose-600 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800">{{ $stats['rejected'] }}</p>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">You Rejected</p>
            </div>
        </div>
    </div>

    {{-- Leave Cards --}}
    @forelse($leaves as $leave)
    <div class="premium-card overflow-hidden border-l-4 border-amber-400">
        <div class="p-5">
            {{-- Employee Info --}}
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-brand flex items-center justify-center text-white font-black text-lg flex-shrink-0">
                        {{ substr(optional($leave->employee)->first_name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">{{ optional($leave->employee)->full_name ?? 'N/A' }}</h3>
                        <p class="text-xs text-slate-500">
                            {{ optional(optional($leave->employee)->designation)->designation_name ?? '' }}
                            @if(optional($leave->employee)->department)
                                &bull; {{ $leave->employee->department->department_name }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                        <i class="fa-solid fa-crown text-[10px] mr-1"></i> Pending GM
                    </span>
                    <p class="text-xs text-slate-400 mt-1">Leave #{{ $leave->leave_id }}</p>
                </div>
            </div>

            {{-- Leave Details --}}
            <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="p-3 bg-slate-50 rounded-xl">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Leave Type</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $leave->type->leave_type_name ?? 'N/A' }}</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Duration</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $leave->total_days }} day(s)</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">From</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $leave->start_date->format('d M Y') }}</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">To</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $leave->end_date->format('d M Y') }}</p>
                </div>
            </div>

            @if($leave->leave_remarks)
            <div class="mt-3 p-3 bg-indigo-50 rounded-xl">
                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-wide mb-1">Employee Reason</p>
                <p class="text-sm text-indigo-900">{{ $leave->leave_remarks }}</p>
            </div>
            @endif

            {{-- Line Manager Feedback --}}
            @if($leave->lm_comments)
            <div class="mt-3 p-3 bg-amber-50 rounded-xl border-l-2 border-amber-400">
                <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wide mb-1 flex items-center gap-1">
                    <i class="fa-solid fa-user-tie text-[10px]"></i>
                    Line Manager Comments
                    @if($leave->lineManager)
                        &mdash; {{ $leave->lineManager->first_name }} {{ $leave->lineManager->last_name }}
                    @endif
                </p>
                <p class="text-sm text-slate-700">{{ $leave->lm_comments }}</p>
                @if($leave->lm_reviewed_at)
                    <p class="text-[10px] text-slate-400 mt-1">Reviewed {{ \Carbon\Carbon::parse($leave->lm_reviewed_at)->format('d M Y H:i') }}</p>
                @endif
            </div>
            @endif

            {{-- GM Decision Forms --}}
            <div class="mt-4 grid grid-cols-2 gap-3">
                {{-- Approve --}}
                <form id="approveForm{{ $leave->leave_id }}" action="{{ route($routePrefix . 'leaves.gm-approve', $leave->leave_id) }}" method="POST" class="space-y-2">
                    @csrf
                    <textarea id="approveComments{{ $leave->leave_id }}" name="gm_comments" rows="2"
                        class="premium-input w-full px-3 py-2 text-sm"
                        placeholder="Approval comments (optional)..."></textarea>
                    <button type="button"
                        onclick="gmDecision('approve', {{ $leave->leave_id }}, '{{ addslashes(optional($leave->employee)->full_name ?? '') }}')"
                        class="w-full py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold rounded-xl shadow hover:scale-105 transition-all text-sm">
                        <i class="fa-solid fa-check-circle mr-1"></i> Approve
                    </button>
                </form>
                {{-- Reject --}}
                <form id="rejectForm{{ $leave->leave_id }}" action="{{ route($routePrefix . 'leaves.gm-reject', $leave->leave_id) }}" method="POST" class="space-y-2">
                    @csrf
                    <textarea id="rejectComments{{ $leave->leave_id }}" name="gm_comments" rows="2" required
                        class="premium-input w-full px-3 py-2 text-sm"
                        placeholder="Rejection reason (required)..."></textarea>
                    <button type="button"
                        onclick="gmDecision('reject', {{ $leave->leave_id }}, '{{ addslashes(optional($leave->employee)->full_name ?? '') }}')"
                        class="w-full py-2.5 bg-gradient-to-r from-rose-500 to-red-600 text-white font-bold rounded-xl shadow hover:scale-105 transition-all text-sm">
                        <i class="fa-solid fa-times-circle mr-1"></i> Reject
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="premium-card p-12 text-center">
        <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-check-circle text-emerald-500 text-2xl"></i>
        </div>
        <h3 class="text-slate-700 font-bold text-lg">All clear!</h3>
        <p class="text-slate-400 text-sm mt-1">No leave requests are waiting for your decision.</p>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($leaves->hasPages())
    <div class="flex justify-center">{{ $leaves->links() }}</div>
    @endif

</div>

@push('scripts')
<script>
function gmDecision(type, leaveId, empName) {
    if (type === 'approve') {
        Swal.fire({
            title: 'Approve Leave?',
            html: `You are about to <strong>approve</strong> the leave request for <strong>${empName}</strong>.<br><br>The employee and HR will be notified.`,
            icon: 'question',
            iconColor: '#10b981',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa-solid fa-check-circle mr-1"></i> Yes, Approve',
            cancelButtonText: 'Cancel',
            customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl font-bold px-5 py-2.5', cancelButton: 'rounded-xl font-bold px-5 py-2.5' }
        }).then(r => {
            if (r.isConfirmed) {
                Swal.fire({ title: 'Processing...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                document.getElementById('approveForm' + leaveId).submit();
            }
        });
    } else {
        const reason = document.getElementById('rejectComments' + leaveId).value.trim();
        if (!reason) {
            Swal.fire({
                title: 'Reason Required',
                text: 'Please provide a rejection reason before proceeding.',
                icon: 'warning',
                confirmButtonColor: '#f59e0b',
                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl font-bold px-5 py-2.5' }
            });
            document.getElementById('rejectComments' + leaveId).focus();
            return;
        }
        Swal.fire({
            title: 'Reject Leave?',
            html: `You are about to <strong>reject</strong> the leave request for <strong>${empName}</strong>.<br><br>The employee and HR will be notified with your reason.`,
            icon: 'warning',
            iconColor: '#ef4444',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa-solid fa-times-circle mr-1"></i> Yes, Reject',
            cancelButtonText: 'Cancel',
            customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl font-bold px-5 py-2.5', cancelButton: 'rounded-xl font-bold px-5 py-2.5' }
        }).then(r => {
            if (r.isConfirmed) {
                Swal.fire({ title: 'Processing...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                document.getElementById('rejectForm' + leaveId).submit();
            }
        });
    }
}
</script>
@endpush
@endsection
