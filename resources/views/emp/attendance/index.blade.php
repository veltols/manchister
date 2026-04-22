@extends('layouts.app')

@section('title', 'My Attendance')
@section('subtitle', 'Your daily clock-in logs')

@section('content')
    <div class="space-y-6">

        <!-- Filter Header -->
        <div class="premium-card p-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-display font-bold text-premium">Attendance History</h2>
                <p class="text-sm text-slate-500">Viewing logs for {{ \Carbon\Carbon::parse($month)->format('F Y') }}</p>
            </div>
            <form action="{{ route('emp.attendance.index') }}" method="GET" class="flex gap-2">
                <input type="month" name="month" value="{{ $month }}" class="premium-input text-sm">
                <button type="submit" class="px-6 py-2 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filter</span>
                </button>
            </form>
        </div>

        <!-- Attendance Table -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left font-bold text-slate-400">Date</th>
                            <th class="text-center font-bold text-slate-400">Check-In</th>
                            <th class="text-center font-bold text-slate-400">Check-Out</th>
                            <th class="text-center font-bold text-slate-400">Duration</th>
                            <th class="text-center font-bold text-slate-400">Status</th>
                            <th class="text-left font-bold text-slate-400">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50" id="attendance-container">
                        @forelse($attendances as $att)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td>
                                    <div class="font-bold text-slate-700">{{ $att->checkin_date->format('M d, Y') }}</div>
                                    <div class="text-[10px] uppercase font-bold text-slate-400">{{ $att->checkin_date->format('l') }}</div>
                                </td>
                                <td class="text-center">
                                    <div
                                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 text-green-700 rounded-xl border border-green-100 font-bold">
                                        <i class="fa-regular fa-clock text-xs"></i>
                                        {{ \Carbon\Carbon::parse($att->checkin_time)->format('h:i A') }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($att->checkout_time)
                                        <div
                                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-purple-50 text-purple-700 rounded-xl border border-purple-100 font-bold">
                                            <i class="fa-regular fa-clock text-xs"></i>
                                            {{ \Carbon\Carbon::parse($att->checkout_time)->format('h:i A') }}
                                        </div>
                                    @else
                                        <span class="text-slate-300">---</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="px-3 py-1 bg-slate-100 rounded-lg text-slate-600 font-bold text-xs">
                                        {{ $att->total_hours ?? '0.00' }} hrs
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusClass = match($att->attendance_status) {
                                            'present' => 'bg-green-100 text-green-700',
                                            'late' => 'bg-amber-100 text-amber-700',
                                            'absent' => 'bg-red-100 text-red-700',
                                            'on leave' => 'bg-blue-100 text-blue-700',
                                            default => 'bg-slate-100 text-slate-700'
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">
                                        {{ $att->attendance_status ?? 'present' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-sm text-slate-600">{{ $att->attendance_remarks ?? '-' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-20">
                                    <i class="fa-solid fa-calendar-xmark text-5xl text-slate-100 mb-4"></i>
                                    <p class="text-slate-400 font-medium">No attendance logs found for this month</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- AJAX Pagination -->
            <div id="attendance-pagination" class="px-6 py-4 border-t border-slate-100"></div>
        </div>

    </div>
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('emp.attendance.data', ['month' => $month]) }}",
            containerSelector: '#attendance-container',
            paginationSelector: '#attendance-pagination',
            renderCallback: function(data) {
                let html = '';
                data.forEach(att => {
                    const checkinDate = new Date(att.checkin_date);
                    const formattedDate = checkinDate.toLocaleDateString(undefined, {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });
                    const formattedDay = checkinDate.toLocaleDateString(undefined, {
                        weekday: 'long'
                    });
                    
                    // Format time h:i A helper
                    const formatT = (timeStr) => {
                        if(!timeStr) return '-';
                        const [hour, minute] = timeStr.split(':');
                        const h = parseInt(hour);
                        const ampm = h >= 12 ? 'PM' : 'AM';
                        const h12 = h % 12 || 12;
                        return `${h12}:${minute} ${ampm}`;
                    };

                    const formattedCheckin = formatT(att.checkin_time);
                    const formattedCheckout = formatT(att.checkout_time);

                    html += `
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td>
                                <div class="font-bold text-slate-700">${formattedDate}</div>
                                <div class="text-[10px] uppercase font-bold text-slate-400">${formattedDay}</div>
                            </td>
                            <td class="text-center">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 text-green-700 rounded-xl border border-green-100 font-bold">
                                    <i class="fa-regular fa-clock text-xs"></i>
                                    ${formattedCheckin}
                                </div>
                            </td>
                            <td class="text-center">
                                ${att.checkout_time ? `
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-purple-50 text-purple-700 rounded-xl border border-purple-100 font-bold">
                                        <i class="fa-regular fa-clock text-xs"></i>
                                        ${formattedCheckout}
                                    </div>
                                ` : '<span class="text-slate-300">---</span>'}
                            </td>
                            <td class="text-center">
                                <span class="px-3 py-1 bg-slate-100 rounded-lg text-slate-600 font-bold text-xs">
                                    ${att.total_hours || '0.00'} hrs
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider ${
                                    att.attendance_status === 'present' ? 'bg-green-100 text-green-700' : 
                                    (att.attendance_status === 'late' ? 'bg-amber-100 text-amber-700' : 
                                    (att.attendance_status === 'absent' ? 'bg-red-100 text-red-700' : 
                                    (att.attendance_status === 'on leave' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700')))
                                }">
                                    ${att.attendance_status || 'present'}
                                </span>
                            </td>
                            <td>
                                <span class="text-sm text-slate-600">${att.attendance_remarks || '-'}</span>
                            </td>
                        </tr>
                    `;
                });
                return html;
            }
        });
    </script>
@endsection
