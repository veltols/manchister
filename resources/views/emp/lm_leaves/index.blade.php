@extends('layouts.app')
@section('title', 'Leave Requests — My Team')
@section('subtitle', 'Review and process leave requests from your department')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="text-2xl font-display font-bold text-premium flex items-center gap-3">
                <i class="fa-solid fa-user-tie text-teal-500"></i> Team Leave Queue
            </h2>
            <p class="text-sm text-slate-500 mt-1">Approve to forward to GM, or reject with a reason</p>
        </div>
        {{-- Toggle: Pending / All --}}
        <div class="flex items-center gap-2 bg-slate-100 rounded-xl p-1">
            <a href="{{ route('emp.lm.leaves.index', ['status' => 'pending']) }}"
               class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $statusFilter === 'pending' ? 'bg-white text-teal-700 shadow' : 'text-slate-500 hover:text-slate-700' }}">
                <i class="fa-solid fa-clock mr-1"></i> Pending
            </a>
            <a href="{{ route('emp.lm.leaves.index', ['status' => 'all']) }}"
               class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $statusFilter === 'all' ? 'bg-white text-slate-700 shadow' : 'text-slate-500 hover:text-slate-700' }}">
                <i class="fa-solid fa-list mr-1"></i> All
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="premium-card p-4 flex items-center gap-3 border-l-4 border-amber-400">
            <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-clock text-amber-600"></i>
            </div>
            <div>
                <p class="text-xl font-black text-slate-800">{{ $stats['pending'] }}</p>
                <p class="text-xs text-slate-500 font-semibold">Awaiting Review</p>
            </div>
        </div>
        <div class="premium-card p-4 flex items-center gap-3 border-l-4 border-teal-400">
            <div class="w-11 h-11 rounded-xl bg-teal-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-share text-teal-600"></i>
            </div>
            <div>
                <p class="text-xl font-black text-slate-800">{{ $stats['approved'] }}</p>
                <p class="text-xs text-slate-500 font-semibold">Forwarded to GM</p>
            </div>
        </div>
        <div class="premium-card p-4 flex items-center gap-3 border-l-4 border-rose-400">
            <div class="w-11 h-11 rounded-xl bg-rose-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-times-circle text-rose-600"></i>
            </div>
            <div>
                <p class="text-xl font-black text-slate-800">{{ $stats['rejected'] }}</p>
                <p class="text-xs text-slate-500 font-semibold">Rejected</p>
            </div>
        </div>
        <div class="premium-card p-4 flex items-center gap-3 border-l-4 border-slate-300">
            <div class="w-11 h-11 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-layer-group text-slate-500"></i>
            </div>
            <div>
                <p class="text-xl font-black text-slate-800">{{ $stats['total'] }}</p>
                <p class="text-xs text-slate-500 font-semibold">Total Requests</p>
            </div>
        </div>
    </div>

    {{-- Leave Cards --}}
    @forelse($leaves as $leave)
    @php
        $sid = (int)$leave->leave_status_id;
        $isPending = in_array($sid, [\App\Models\HrLeave::STATUS_PENDING, \App\Models\HrLeave::STATUS_PENDING_APPROVAL]);
        $statusMap = [
            \App\Models\HrLeave::STATUS_PENDING          => ['label' => 'Awaiting Your Review', 'color' => 'bg-amber-100 text-amber-800', 'border' => 'border-amber-400'],
            \App\Models\HrLeave::STATUS_PENDING_APPROVAL => ['label' => 'Awaiting Your Review', 'color' => 'bg-amber-100 text-amber-800', 'border' => 'border-amber-400'],
            \App\Models\HrLeave::STATUS_PENDING_GM        => ['label' => 'Forwarded to GM',      'color' => 'bg-teal-100 text-teal-800',  'border' => 'border-teal-400'],
            \App\Models\HrLeave::STATUS_APPROVED          => ['label' => 'Approved',              'color' => 'bg-emerald-100 text-emerald-800','border' => 'border-emerald-400'],
            \App\Models\HrLeave::STATUS_REJECTED          => ['label' => 'Rejected',              'color' => 'bg-rose-100 text-rose-800',  'border' => 'border-rose-400'],
        ];
        $sc = $statusMap[$sid] ?? ['label' => 'Unknown', 'color' => 'bg-slate-100 text-slate-600', 'border' => 'border-slate-300'];
    @endphp
    <div class="premium-card overflow-hidden border-l-4 {{ $sc['border'] }}">
        <div class="p-5">
            {{-- Employee Header --}}
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-brand flex items-center justify-center text-white font-black text-lg flex-shrink-0">
                        {{ substr($leave->employee->first_name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">{{ optional($leave->employee)->full_name ?? 'N/A' }}</h3>
                        <p class="text-xs text-slate-500">
                            {{ optional(optional($leave->employee)->designation)->designation_name ?? '' }}
                            @if(optional($leave->employee)->department)
                                &bull; {{ $leave->employee->department->department_name }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex-shrink-0 text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $sc['color'] }}">
                        {{ $sc['label'] }}
                    </span>
                    <p class="text-xs text-slate-400 mt-1">Leave #{{ $leave->leave_id }}</p>
                </div>
            </div>

            {{-- Leave Details Grid --}}
            <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="p-3 bg-slate-50 rounded-xl">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Type</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $leave->type->leave_type_name ?? 'N/A' }}</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Days</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $leave->total_days }} working day(s)</p>
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

            {{-- Employee Reason --}}
            @if($leave->leave_remarks)
            <div class="mt-3 p-3 bg-indigo-50 rounded-xl">
                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-wide mb-1">Employee Reason</p>
                <p class="text-sm text-indigo-900">{{ $leave->leave_remarks }}</p>
            </div>
            @endif

            {{-- Attachment --}}
            @if($leave->leave_attachment && $leave->leave_attachment !== 'no-img.png')
            <div class="mt-3">
                <a href="{{ asset('uploads/' . $leave->leave_attachment) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition-colors">
                    <i class="fa-solid fa-paperclip"></i> View Attachment
                </a>
            </div>
            @endif

            {{-- Submitted at --}}
            <p class="text-[10px] text-slate-400 mt-2">Submitted: {{ \Carbon\Carbon::parse($leave->submission_date)->format('d M Y') }}</p>

            {{-- Already reviewed — show LM comments --}}
            @if($leave->lm_comments && !$isPending)
            <div class="mt-3 p-3 {{ $sid === \App\Models\HrLeave::STATUS_REJECTED ? 'bg-rose-50 border-l-2 border-rose-400' : 'bg-teal-50 border-l-2 border-teal-400' }} rounded-xl">
                <p class="text-[10px] font-bold {{ $sid === \App\Models\HrLeave::STATUS_REJECTED ? 'text-rose-600' : 'text-teal-600' }} uppercase tracking-wide mb-1">Your Comments</p>
                <p class="text-sm text-slate-700">{{ $leave->lm_comments }}</p>
                @if($leave->lm_reviewed_at)
                    <p class="text-[10px] text-slate-400 mt-1">Reviewed {{ \Carbon\Carbon::parse($leave->lm_reviewed_at)->format('d M Y H:i') }}</p>
                @endif
            </div>
            @endif

            {{-- Action Forms — only for pending --}}
            @if($isPending)
            <div class="mt-4 grid grid-cols-2 gap-3">
                {{-- Approve --}}
                <form id="approveForm{{ $leave->leave_id }}"
                      action="{{ route('emp.lm.leaves.approve', $leave->leave_id) }}"
                      method="POST" class="space-y-2">
                    @csrf
                    <textarea id="approveComments{{ $leave->leave_id }}" name="lm_comments" rows="2"
                        class="premium-input w-full px-3 py-2 text-sm"
                        placeholder="Comments (optional)..."></textarea>
                    <button type="button"
                        onclick="lmDecision('approve', {{ $leave->leave_id }}, '{{ addslashes(optional($leave->employee)->full_name ?? '') }}')"
                        class="w-full py-2.5 bg-gradient-to-r from-teal-500 to-emerald-600 text-white font-bold rounded-xl shadow hover:scale-105 transition-all text-sm">
                        <i class="fa-solid fa-share mr-1"></i> Approve & Forward to GM
                    </button>
                </form>
                {{-- Reject --}}
                <form id="rejectForm{{ $leave->leave_id }}"
                      action="{{ route('emp.lm.leaves.reject', $leave->leave_id) }}"
                      method="POST" class="space-y-2">
                    @csrf
                    <textarea id="rejectComments{{ $leave->leave_id }}" name="lm_comments" rows="2" required
                        class="premium-input w-full px-3 py-2 text-sm"
                        placeholder="Rejection reason (required)..."></textarea>
                    <button type="button"
                        onclick="lmDecision('reject', {{ $leave->leave_id }}, '{{ addslashes(optional($leave->employee)->full_name ?? '') }}')"
                        class="w-full py-2.5 bg-gradient-to-r from-rose-500 to-red-600 text-white font-bold rounded-xl shadow hover:scale-105 transition-all text-sm">
                        <i class="fa-solid fa-times-circle mr-1"></i> Reject
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="premium-card p-12 text-center">
        <div class="w-16 h-16 rounded-full bg-teal-100 flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-check-circle text-teal-500 text-2xl"></i>
        </div>
        <h3 class="text-slate-700 font-bold text-lg">
            {{ $statusFilter === 'pending' ? 'No pending leave requests!' : 'No leave requests found.' }}
        </h3>
        <p class="text-slate-400 text-sm mt-1">
            {{ $statusFilter === 'pending' ? 'Your team has no leaves awaiting your review.' : 'No leave requests have been assigned to you yet.' }}
        </p>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($leaves->hasPages())
    <div class="flex justify-center">{{ $leaves->links() }}</div>
    @endif

</div>

@push('scripts')
<script>
function lmDecision(type, leaveId, empName) {
    if (type === 'approve') {
        Swal.fire({
            title: 'Forward to GM?',
            html: `You are approving the leave request for <strong>${empName}</strong> and forwarding it to the <strong>General Manager</strong> for final decision.<br><br>The employee will be notified.`,
            icon: 'question',
            iconColor: '#0d9488',
            showCancelButton: true,
            confirmButtonColor: '#0d9488',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa-solid fa-share mr-1"></i> Yes, Forward to GM',
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
            title: 'Reject Leave Request?',
            html: `You are about to <strong>reject</strong> the leave request for <strong>${empName}</strong>.<br><br>The employee will be notified with your reason.`,
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
