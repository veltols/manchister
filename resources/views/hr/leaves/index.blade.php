@extends('layouts.app')

@section('title', 'Leaves Management')
@section('subtitle', 'Review and manage leave requests')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-slate-800">Leave Requests</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $leaves->total() }} total requests</p>
        </div>
        <button onclick="openModal('addLeaveModal')" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
            <i class="fa-solid fa-plus"></i>
            <span>Add Leave</span>
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="premium-card p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-clock text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Pending</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $leaves->where('leave_status_id', 0)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="premium-card p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-check text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Approved</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $leaves->where('leave_status_id', 100)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="premium-card p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center">
                    <i class="fa-solid fa-times text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Rejected</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $leaves->where('leave_status_id', 200)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="premium-card p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-calendar text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Total</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $leaves->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaves Table -->
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">REF</th>
                        <th class="text-left">Employee</th>
                        <th class="text-left">Type</th>
                        <th class="text-left">Duration</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                    <tr>
                        <td>
                            <span class="font-mono text-sm font-semibold text-slate-600">#{{ $leave->leave_id }}</span>
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold shadow-md">
                                    {{ strtoupper(substr($leave->employee->first_name, 0, 1)) }}{{ strtoupper(substr($leave->employee->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-semibold text-slate-800">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-purple-50 text-purple-700 text-sm font-medium">
                                <i class="fa-solid fa-tag text-xs"></i>
                                {{ $leave->type->leave_type_name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <div class="text-sm">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i class="fa-solid fa-calendar-day text-xs text-slate-400"></i>
                                    <span>{{ $leave->start_date ? $leave->start_date->format('M d, Y') : '-' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-slate-600 mt-1">
                                    <i class="fa-solid fa-calendar-check text-xs text-slate-400"></i>
                                    <span>{{ $leave->end_date ? $leave->end_date->format('M d, Y') : '-' }}</span>
                                </div>
                            </div>
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
                        <td>
                            <div class="flex items-center justify-center gap-2">
                                @if($leave->leave_status_id == 0)
                                    <form action="{{ route('hr.leaves.status', $leave->leave_id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status_id" value="100">
                                        <button type="submit" class="w-9 h-9 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md" title="Approve">
                                            <i class="fa-solid fa-check text-sm"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('hr.leaves.status', $leave->leave_id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status_id" value="200">
                                        <button type="submit" class="w-9 h-9 rounded-lg bg-gradient-to-br from-red-500 to-rose-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md" title="Reject">
                                            <i class="fa-solid fa-times text-sm"></i>
                                        </button>
                                    </form>
                                @endif
                                <button class="w-9 h-9 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md" title="View Details">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </button>
                            </div>
                        </td>
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

        <!-- Pagination -->
        @if($leaves->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $leaves->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

</div>

<!-- Create Modal -->
@include('hr.leaves.create')
@endsection
