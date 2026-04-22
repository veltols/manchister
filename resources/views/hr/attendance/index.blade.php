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
            <div class="flex gap-3">
                <form action="{{ route('hr.attendance.sync_absents') }}" method="POST">
                    @csrf
                    <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-slate-800 text-white font-semibold rounded-xl shadow-lg hover:bg-slate-900 transition-all duration-200">
                        <i class="fa-solid fa-calendar-check"></i>
                        <span>Finalize Absents</span>
                    </button>
                </form>
                <button onclick="openModal('addAttendanceModal')"
                    class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Manual Entry</span>
                </button>
            </div>
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
                            <th class="text-left">Check-in</th>
                            <th class="text-left">Check-out</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-left">Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="attendance-container">
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
                                    <div class="font-bold">{{ $attendance->checkin_date instanceof \Illuminate\Support\Carbon ? $attendance->checkin_date->format('M d, Y') : $attendance->checkin_date }}</div>
                                    <div class="text-[10px] uppercase font-bold text-blue-600">{{ $attendance->checkin_time }}</div>
                                </td>
                                <td class="text-sm text-slate-600">
                                    <div class="font-bold">{{ $attendance->checkout_date ? (\Carbon\Carbon::parse($attendance->checkout_date)->format('M d, Y')) : '-' }}</div>
                                    <div class="text-[10px] uppercase font-bold text-purple-600">{{ $attendance->checkout_time ?? '-' }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex px-2 py-1 rounded-lg bg-slate-100 text-slate-700 font-bold text-xs">
                                        {{ $attendance->total_hours ?? '0.00' }} hrs
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusClass = match($attendance->attendance_status) {
                                            'present' => 'bg-green-100 text-green-700',
                                            'late' => 'bg-amber-100 text-amber-700',
                                            'absent' => 'bg-red-100 text-red-700',
                                            'on leave' => 'bg-blue-100 text-blue-700',
                                            default => 'bg-slate-100 text-slate-700'
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">
                                        {{ $attendance->attendance_status ?? 'present' }}
                                    </span>
                                </td>
                                <td><span class="text-sm text-slate-600">{{ $attendance->attendance_remarks }}</span></td>
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

            <!-- AJAX Pagination -->
            <div id="attendance-pagination"></div>
        </div>

    </div>

    @push('scripts')
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('hr.attendance.data') }}",
            containerSelector: '#attendance-container',
            paginationSelector: '#attendance-pagination',
            perPage: 20,
            renderCallback: function(entries) {
                const container = document.querySelector('#attendance-container');
                if (entries.length === 0) {
                    container.innerHTML = `
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
                    `;
                    return;
                }

                let html = '';
                entries.forEach(entry => {
                    let initials = '?';
                    let fullName = 'Unknown Employee';
                    if (entry.employee) {
                        initials = (entry.employee.first_name.charAt(0) + entry.employee.last_name.charAt(0)).toUpperCase();
                        fullName = entry.employee.first_name + ' ' + entry.employee.last_name;
                    }

                    // Format date (backend returns Y-m-d)
                    let checkinDate = entry.checkin_date;
                    try {
                        const d = new Date(entry.checkin_date);
                        checkinDate = d.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
                    } catch(e) {}

                    html += `
                        <tr>
                            <td><span class="font-mono text-sm font-semibold text-slate-600">#${entry.attendance_id}</span></td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold shadow-md">
                                        ${initials}
                                    </div>
                                    <span class="font-semibold text-slate-800">${fullName}</span>
                                </div>
                            </td>
                            <td class="text-sm text-slate-600">
                                <div class="font-bold">${checkinDate}</div>
                                <div class="text-[10px] uppercase font-bold text-blue-600">${entry.checkin_time}</div>
                            </td>
                            <td class="text-sm text-slate-600">
                                <div class="font-bold">${entry.checkout_date ? new Date(entry.checkout_date).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) : '-'}</div>
                                <div class="text-[10px] uppercase font-bold text-purple-600">${entry.checkout_time || '-'}</div>
                            </td>
                            <td class="text-center">
                                <span class="inline-flex px-2 py-1 rounded-lg bg-slate-100 text-slate-700 font-bold text-xs">
                                    ${entry.total_hours || '0.00'} hrs
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider ${
                                    entry.attendance_status === 'present' ? 'bg-green-100 text-green-700' : 
                                    (entry.attendance_status === 'late' ? 'bg-amber-100 text-amber-700' : 
                                    (entry.attendance_status === 'absent' ? 'bg-red-100 text-red-700' : 
                                    (entry.attendance_status === 'on leave' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700')))
                                }">
                                    ${entry.attendance_status || 'present'}
                                </span>
                            </td>
                            <td><span class="text-sm text-slate-600">${entry.attendance_remarks || ''}</span></td>
                        </tr>
                    `;
                });
                container.innerHTML = html;
            }
        });

        // Initial pagination setup
        @if($attendances->hasPages())
            window.ajaxPagination.renderPagination({
                current_page: {{ $attendances->currentPage() }},
                last_page: {{ $attendances->lastPage() }},
                from: {{ $attendances->firstItem() }},
                to: {{ $attendances->lastItem() }},
                total: {{ $attendances->total() }}
            });
        @endif
    </script>
    @endpush

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