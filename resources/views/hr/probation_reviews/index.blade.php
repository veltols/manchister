@extends('layouts.app')

@section('title', 'Probation Reviews')
@section('subtitle', 'Create and track probation performance reviews')

@section('content')
<div class="space-y-6">
    @include('hr.partials.requests_nav')

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-premium">Probation Performance Reviews</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $reviews->total() }} total reviews</p>
        </div>
        <button onclick="openModal('createReviewModal')"
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
            <i class="fa-solid fa-plus"></i>
            <span>New Review</span>
        </button>
    </div>

    {{-- Workflow Banner --}}
    <div class="premium-card p-5 bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-100">
        <div class="flex items-center gap-6 flex-wrap">
            <div class="flex items-center gap-2 text-sm font-bold text-indigo-700">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs">HR</div>
                Create Review
            </div>
            <i class="fa-solid fa-arrow-right text-indigo-400"></i>
            <div class="flex items-center gap-2 text-sm font-bold text-amber-700">
                <div class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs"><i class="fa-solid fa-user-tie text-xs"></i></div>
                Line Manager Review
            </div>
            <i class="fa-solid fa-arrow-right text-indigo-400"></i>
            <div class="flex items-center gap-2 text-sm font-bold text-emerald-700">
                <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs">GM</div>
                Final Decision
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="premium-card p-5">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Employee</label>
                <select name="employee_id" class="premium-input px-4 py-2.5 text-sm">
                    <option value="">All Employees</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->employee_id }}" {{ request('employee_id') == $emp->employee_id ? 'selected' : '' }}>
                            {{ $emp->first_name }} {{ $emp->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status</label>
                <select name="status" class="premium-input px-4 py-2.5 text-sm">
                    <option value="">All Statuses</option>
                    <option value="pending_manager" {{ request('status') == 'pending_manager' ? 'selected' : '' }}>Pending Manager</option>
                    <option value="reviewed"        {{ request('status') == 'reviewed'        ? 'selected' : '' }}>Forwarded to GM</option>
                    <option value="approved"        {{ request('status') == 'approved'        ? 'selected' : '' }}>Approved</option>
                    <option value="rejected"        {{ request('status') == 'rejected'        ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-gradient-brand text-white font-bold rounded-xl text-sm">
                <i class="fa-solid fa-filter mr-1"></i> Filter
            </button>
            <a href="{{ route('hr.probation-reviews.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-500 font-bold rounded-xl text-sm hover:bg-slate-200 transition-colors">
                <i class="fa-solid fa-redo"></i>
            </a>
        </form>
    </div>

    {{-- Table --}}
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th>Ref</th>
                        <th>Employee</th>
                        <th>Probation Type</th>
                        <th>End Date</th>
                        <th>Line Manager</th>
                        <th class="text-center">Status</th>
                        <th>Created</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                    @php
                        $colors = [
                            'pending_manager' => 'from-amber-400 to-amber-600',
                            'reviewed'        => 'from-blue-500 to-indigo-600',
                            'approved'        => 'from-emerald-500 to-green-600',
                            'rejected'        => 'from-rose-500 to-red-600',
                        ];
                        $color = $colors[$review->status] ?? 'from-slate-400 to-slate-600';
                    @endphp
                    <tr>
                        <td><span class="font-mono text-xs font-bold text-slate-500">#{{ $review->review_id }}</span></td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-brand flex items-center justify-center text-white text-xs font-bold">
                                    {{ substr($review->employee->first_name ?? 'U', 0, 1) }}
                                </div>
                                <span class="font-semibold text-slate-700 text-sm">
                                    {{ $review->employee->first_name ?? 'N/A' }} {{ $review->employee->last_name ?? '' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            @if($review->probation_type)
                                <span class="px-2 py-0.5 rounded-lg bg-amber-50 text-amber-700 text-[10px] font-bold uppercase">
                                    {{ str_replace('_', ' ', $review->probation_type) }}
                                </span>
                            @else
                                <span class="text-slate-400 text-xs">—</span>
                            @endif
                        </td>
                        <td>
                            @if($review->probation_end_date)
                                @php $pEnd = \Carbon\Carbon::parse($review->probation_end_date); @endphp
                                <span class="text-sm {{ $pEnd->isPast() ? 'text-rose-600 font-bold' : 'text-slate-600' }}">
                                    {{ $pEnd->format('d M Y') }}
                                </span>
                            @else
                                <span class="text-slate-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="text-sm text-slate-600">
                            {{ $review->lineManager->first_name ?? 'N/A' }} {{ $review->lineManager->last_name ?? '' }}
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r {{ $color }} text-white text-[10px] font-bold shadow-sm whitespace-nowrap">
                                {{ $review->status_label }}
                            </span>
                        </td>
                        <td class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($review->created_at)->format('d M Y') }}</td>
                        <td class="text-center">
                            <a href="{{ route('hr.probation-reviews.show', $review->review_id) }}"
                               class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 flex items-center justify-center transition-colors mx-auto"
                               title="View Details">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-16">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center">
                                    <i class="fa-solid fa-clipboard-check text-2xl text-slate-300"></i>
                                </div>
                                <p class="text-slate-500 font-medium">No probation reviews found</p>
                                <button onclick="openModal('createReviewModal')"
                                    class="mt-2 px-5 py-2.5 bg-gradient-brand text-white font-bold rounded-xl text-sm">
                                    Create First Review
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reviews->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $reviews->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

{{-- Create Review Modal --}}
<div id="createReviewModal" class="modal">
    <div class="modal-backdrop" onclick="closeModal('createReviewModal')"></div>
    <div class="modal-content max-w-2xl p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">New Probation Review</h2>
                <p class="text-slate-500 text-sm mt-0.5">This will be sent to the employee's Line Manager</p>
            </div>
            <button onclick="closeModal('createReviewModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ route('hr.probation-reviews.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="premium-label">Employee <span class="text-rose-500">*</span></label>
                    <select name="employee_id" id="review_employee_id" class="premium-input w-full px-4 py-2.5 text-sm" required onchange="loadEmployeeData(this.value)">
                        <option value="">Select Employee...</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->employee_id }}"
                                data-probation-type="{{ $emp->probation_type }}"
                                data-probation-end="{{ $emp->probation_end_date }}">
                                {{ $emp->first_name }} {{ $emp->last_name }}
                                @if($emp->probation_type) — [{{ ucfirst($emp->probation_type) }}] @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="premium-label">Review Title</label>
                    <input type="text" name="review_title" class="premium-input w-full px-4 py-2.5 text-sm"
                           value="Probation Performance Review" placeholder="e.g. End of Probation Review">
                </div>

                <div>
                    <label class="premium-label">Probation Type</label>
                    <select name="probation_type" id="review_probation_type" class="premium-input w-full px-4 py-2.5 text-sm">
                        <option value="">-- Auto from employee --</option>
                        <option value="initial">Initial Probation</option>
                        <option value="extended">Extended Probation</option>
                        <option value="completed">Probation Completed</option>
                    </select>
                </div>

                <div>
                    <label class="premium-label">Probation End Date</label>
                    <input type="date" name="probation_end_date" id="review_probation_end"
                           class="premium-input w-full px-4 py-2.5 text-sm">
                </div>

                <div class="col-span-2">
                    <label class="premium-label">Objectives <span class="text-rose-500">*</span></label>
                    <textarea name="objectives" rows="3" required
                              class="premium-input w-full px-4 py-2.5 text-sm"
                              placeholder="List the key objectives for this probation period..."></textarea>
                </div>

                <div class="col-span-2">
                    <label class="premium-label">KPIs <span class="text-rose-500">*</span></label>
                    <textarea name="kpis" rows="3" required
                              class="premium-input w-full px-4 py-2.5 text-sm"
                              placeholder="Define measurable KPIs..."></textarea>
                </div>

                <div class="col-span-2">
                    <label class="premium-label">HR Notes <span class="text-slate-400 font-normal">(optional)</span></label>
                    <textarea name="hr_notes" rows="2"
                              class="premium-input w-full px-4 py-2.5 text-sm"
                              placeholder="Any additional context for the Line Manager..."></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" onclick="closeModal('createReviewModal')"
                    class="px-6 py-2.5 rounded-xl font-bold text-slate-500 hover:text-slate-700 transition-colors">Cancel</button>
                <button type="submit"
                    class="px-6 py-2.5 bg-gradient-brand text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all border border-white/10">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Send to Line Manager
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function loadEmployeeData(employeeId) {
    const select = document.getElementById('review_employee_id');
    const option = select.options[select.selectedIndex];
    const probationType = option.getAttribute('data-probation-type') || '';
    const probationEnd  = option.getAttribute('data-probation-end') || '';

    const typeSelect = document.getElementById('review_probation_type');
    const endInput   = document.getElementById('review_probation_end');

    if (probationType) typeSelect.value = probationType;
    if (probationEnd)  endInput.value   = probationEnd;
}
</script>
@endsection
