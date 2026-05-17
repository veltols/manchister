@extends('layouts.app')

@section('title', 'Manager: Outbound Communications')
@section('subtitle', 'Review and approve external communication requests')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="premium-card p-6 bg-white border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Pending Review</p>
                        <h3 class="text-2xl font-display font-bold text-premium">{{ $stats['pending'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-600">
                        <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="premium-card p-6 bg-white border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Approved by Me</p>
                        <h3 class="text-2xl font-display font-bold text-premium">{{ $stats['approved'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-green-600">
                        <i class="fa-solid fa-check-double text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="premium-card p-6 bg-white border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Rejected</p>
                        <h3 class="text-2xl font-display font-bold text-premium">{{ $stats['rejected'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-red-600">
                        <i class="fa-solid fa-ban text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="premium-card p-6 bg-white border-l-4 border-teal-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Queue</p>
                        <h3 class="text-2xl font-display font-bold text-premium">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600">
                        <i class="fa-solid fa-list-check text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Queue -->
        <div class="premium-card overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h2 class="text-lg font-bold text-premium flex items-center gap-2">
                    <i class="fa-solid fa-inbox text-brand"></i>
                    Review Queue
                </h2>
                <div class="flex gap-2">
                    <a href="{{ route('emp.lm.communications.index', ['status' => 'pending']) }}" 
                       class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all {{ $statusFilter == 'pending' ? 'bg-brand text-white shadow-lg' : 'bg-white text-slate-400 hover:bg-slate-100' }}">Pending</a>
                    <a href="{{ route('emp.lm.communications.index', ['status' => 'all']) }}" 
                       class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all {{ $statusFilter == 'all' ? 'bg-brand text-white shadow-lg' : 'bg-white text-slate-400 hover:bg-slate-100' }}">All History</a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left">Employee</th>
                            <th class="text-left">Ref / Subject</th>
                            <th class="text-center">Priority</th>
                            <th class="text-center">Confidentiality</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($records as $rec)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold">
                                            {{ substr($rec->employee->employee_name ?? 'E', 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-700">{{ $rec->employee->employee_name }}</span>
                                            <span class="text-[10px] text-slate-400 font-medium">{{ $rec->employee->department->dept_name ?? 'Dept' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="font-mono text-[10px] font-bold text-teal-600">{{ $rec->communication_code }}</span>
                                        <span class="text-sm text-slate-600 font-medium truncate max-w-xs" title="{{ $rec->communication_subject }}">{{ $rec->communication_subject }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase {{ $rec->priority == 'high' ? 'bg-red-50 text-red-600' : ($rec->priority == 'medium' ? 'bg-orange-50 text-orange-600' : 'bg-green-50 text-green-600') }}">
                                        {{ $rec->priority }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase bg-slate-100 text-slate-600">
                                        {{ $rec->confidentiality }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($rec->is_approved_1 == 3 || $rec->is_approved_2 == 3)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-[10px] font-bold uppercase shadow-sm"
                                            style="background: #f97316;">
                                            Required Modification
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-[10px] font-bold uppercase shadow-sm"
                                            style="background: #{{ $rec->status->status_color ?? '64748b' }};">
                                            {{ $rec->status->communication_status_name ?? 'Pending' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('emp.communications.show', $rec->communication_id) }}" class="w-full flex items-center justify-center gap-2 px-3 py-2 bg-indigo-50 text-indigo-600 rounded-lg font-bold text-xs hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                                            <span>Review Request</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-20">
                                    <div class="flex flex-col items-center opacity-20">
                                        <i class="fa-solid fa-inbox text-6xl mb-4"></i>
                                        <p class="font-bold uppercase tracking-widest text-sm">Queue Empty</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $records->links() }}
            </div>
        </div>
    </div>

@endsection
