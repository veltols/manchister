@extends('layouts.app')

@section('title', 'Attendance')
@section('subtitle', 'Track employee attendance')

@section('content')
    <div class="space-y-6">
        @include('hr.partials.requests_nav')

        <!-- Header with Action Button -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Attendance Tracking</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $attendances->total() }} total records</p>
            </div>
            <button onclick="openModal('addAttendanceModal')"
                class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                <i class="fa-solid fa-plus"></i>
                <span>Add Manual Entry</span>
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="premium-card p-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-user-check text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Present Today</p>
                        <p class="text-2xl font-bold text-slate-800">0</p>
                    </div>
                </div>
            </div>
            <div class="premium-card p-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-amber-600 flex items-center justify-center">
                        <i class="fa-solid fa-clock text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Late Arrivals</p>
                        <p class="text-2xl font-bold text-slate-800">0</p>
                    </div>
                </div>
            </div>
            <div class="premium-card p-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center">
                        <i class="fa-solid fa-user-xmark text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Absents</p>
                        <p class="text-2xl font-bold text-slate-800">0</p>
                    </div>
                </div>
            </div>
            <div class="premium-card p-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center">
                        <i class="fa-solid fa-calendar-minus text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">On Leave</p>
                        <p class="text-2xl font-bold text-slate-800">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left">Ref</th>
                            <th class="text-left">Employee</th>
                            <th class="text-left">Check-in Date</th>
                            <th class="text-left">Time</th>
                            <th class="text-left">Remarks</th>
                            <th class="text-left">Added On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td><span
                                        class="font-mono text-sm font-semibold text-slate-600">#{{ $attendance->attendance_id }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold shadow-md">
                                            {{ $attendance->employee ? strtoupper(substr($attendance->employee->first_name, 0, 1)) . strtoupper(substr($attendance->employee->last_name, 0, 1)) : '?' }}
                                        </div>
                                        <span class="font-semibold text-slate-800">
                                            {{ $attendance->employee ? $attendance->employee->first_name . ' ' . $attendance->employee->last_name : 'Unknown Employee' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-sm text-slate-600">
                                    {{ $attendance->checkin_date instanceof \Illuminate\Support\Carbon ? $attendance->checkin_date->format('M d, Y') : $attendance->checkin_date }}
                                </td>
                                <td>
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-sm font-medium font-mono">
                                        <i class="fa-regular fa-clock text-xs"></i>
                                        {{ $attendance->checkin_time }}
                                    </span>
                                </td>
                                <td><span class="text-sm text-slate-600">{{ $attendance->attendance_remarks }}</span></td>
                                <td><span
                                        class="text-sm text-slate-400">{{ $attendance->added_date ? $attendance->added_date->format('Y-m-d') : '-' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                            <i class="fa-solid fa-clock text-2xl text-slate-400"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">No attendance records found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($attendances->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $attendances->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>

    </div>

    <!-- Add Attendance Modal -->
    <div class="modal" id="addAttendanceModal">
        <div class="modal-backdrop" onclick="closeModal('addAttendanceModal')"></div>
        <div class="modal-content max-w-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-display font-bold text-premium">Add Attendance</h2>
                <button onclick="closeModal('addAttendanceModal')"
                    class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('hr.attendance.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Employee</label>
                        <select name="employee_id" class="premium-input w-full px-4 py-3 text-sm" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Date</label>
                            <input type="date" name="checkin_date" value="{{ date('Y-m-d') }}"
                                class="premium-input w-full px-4 py-3 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Time</label>
                            <input type="time" name="checkin_time" class="premium-input w-full px-4 py-3 text-sm" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Remarks</label>
                        <textarea name="attendance_remarks" rows="2" class="premium-input w-full px-4 py-3 text-sm"
                            placeholder="Optional remarks"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('addAttendanceModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                    <button type="submit"
                        class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">Save
                        Entry</button>
                </div>
            </form>
        </div>
    </div>

@endsection