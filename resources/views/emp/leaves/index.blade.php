@extends('layouts.app')

@section('title', 'My Leaves')
@section('subtitle', 'Leave requests and balance')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-slate-800">My Leaves</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $leaves->total() }} total requests</p>
        </div>
        <button onclick="openModal('requestLeaveModal')" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
            <i class="fa-solid fa-plus"></i>
            <span>Request Leave</span>
        </button>
    </div>

    <!-- Leaves Table -->
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Ref</th>
                        <th class="text-left">Type</th>
                        <th class="text-left">Inclusive Dates</th>
                        <th class="text-center">Days</th>
                        <th class="text-center">Status</th>
                        <th class="text-left">Submitted On</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                    <tr>
                        <td><span class="font-mono text-sm font-semibold text-slate-600">#{{ $leave->leave_id }}</span></td>
                        <td>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-purple-50 text-purple-700 text-sm font-medium">
                                <i class="fa-solid fa-tag text-xs"></i>
                                {{ $leave->type->leave_type_name ?? 'Unknown' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <span>{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</span>
                                <i class="fa-solid fa-arrow-right text-xs text-slate-400"></i>
                                <span>{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 text-white font-bold shadow-md">
                                {{ $leave->total_days }}
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $statusConfig = match($leave->leave_status_id) {
                                    100 => ['bg' => 'from-green-500 to-emerald-600', 'text' => 'Approved', 'icon' => 'check'],
                                    200 => ['bg' => 'from-red-500 to-rose-600', 'text' => 'Rejected', 'icon' => 'times'],
                                    default => ['bg' => 'from-yellow-500 to-amber-600', 'text' => 'Pending', 'icon' => 'clock']
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r {{ $statusConfig['bg'] }} text-white text-xs font-bold shadow-md">
                                <i class="fa-solid fa-{{ $statusConfig['icon'] }}"></i>
                                {{ $statusConfig['text'] }}
                            </span>
                        </td>
                        <td><span class="text-sm text-slate-600">{{ \Carbon\Carbon::parse($leave->submission_date)->format('M d, Y') }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                    <i class="fa-solid fa-calendar-days text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 font-medium">No leave requests found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leaves->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $leaves->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

</div>

<!-- Request Leave Modal -->
<div class="modal" id="requestLeaveModal">
    <div class="modal-backdrop" onclick="closeModal('requestLeaveModal')"></div>
    <div class="modal-content max-w-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-display font-bold text-slate-800">Request Leave</h2>
            <button onclick="closeModal('requestLeaveModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('emp.leaves.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-tag text-indigo-600 mr-2"></i>Leave Type
                    </label>
                    <select name="leave_type_id" class="premium-input w-full px-4 py-3 text-sm" required>
                        <option value="">Select Type</option>
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->leave_type_id }}">{{ $type->leave_type_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-calendar text-indigo-600 mr-2"></i>Start Date
                        </label>
                        <input type="date" name="start_date" class="premium-input w-full px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-calendar-check text-indigo-600 mr-2"></i>End Date
                        </label>
                        <input type="date" name="end_date" class="premium-input w-full px-4 py-3 text-sm" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-comment text-indigo-600 mr-2"></i>Reason/Remarks
                    </label>
                    <textarea name="leave_remarks" rows="3" class="premium-input w-full px-4 py-3 text-sm" placeholder="Explain your reason..." required></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('requestLeaveModal')" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">Submit Request</button>
            </div>
        </form>
    </div>
</div>

@endsection
