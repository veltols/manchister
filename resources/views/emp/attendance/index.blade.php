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
                            <th class="text-left font-bold text-slate-400">Day</th>
                            <th class="text-center font-bold text-slate-400">Check-In</th>
                            <th class="text-left font-bold text-slate-400">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($attendances as $att)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td>
                                    <span class="font-bold text-slate-700">{{ $att->checkin_date->format('M d, Y') }}</span>
                                </td>
                                <td>
                                    <span
                                        class="text-sm font-medium text-slate-500">{{ $att->checkin_date->format('l') }}</span>
                                </td>
                                <td class="text-center">
                                    <div
                                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 text-green-700 rounded-xl border border-green-100 font-bold">
                                        <i class="fa-regular fa-clock text-xs"></i>
                                        {{ \Carbon\Carbon::parse($att->checkin_time)->format('h:i A') }}
                                    </div>
                                </td>
                                <td>
                                    @if($att->attendance_remarks == 'AUTO_CHECK_IN')
                                        <span
                                            class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-100 px-2 py-0.5 rounded">System
                                            Auto</span>
                                    @else
                                        <span class="text-sm text-slate-600">{{ $att->attendance_remarks ?? '-' }}</span>
                                    @endif
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
        </div>

    </div>
@endsection
