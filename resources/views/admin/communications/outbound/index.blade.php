@extends('layouts.app')

@section('title', 'GM: Outbound Communications')
@section('subtitle', 'Final review and task assignment')

@section('content')
    <div class="space-y-6 animate-fade-in-up">
        <div class="premium-card overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h2 class="text-lg font-bold text-premium flex items-center gap-2">
                    <i class="fa-solid fa-crown text-brand"></i>
                    Pending GM Review
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left">Initiator</th>
                            <th class="text-left">Ref / Subject</th>
                            <th class="text-center">Priority</th>
                            <th class="text-center">LM Approval</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($records as $rec)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold">
                                            {{ substr($rec->employee->employee_name ?? 'E', 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-700">{{ $rec->employee->employee_name }}</span>
                                            <span class="text-[10px] text-slate-400 font-medium italic">{{ $rec->requested_date ? $rec->requested_date->format('Y-m-d H:i') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="font-mono text-[10px] font-bold text-teal-600">{{ $rec->communication_code }}</span>
                                        <span class="text-sm text-slate-600 font-medium truncate max-w-xs">{{ $rec->communication_subject }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase {{ $rec->priority == 'high' ? 'bg-red-50 text-red-600' : ($rec->priority == 'medium' ? 'bg-orange-50 text-orange-600' : 'bg-green-50 text-green-600') }}">
                                        {{ $rec->priority }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase bg-green-50 text-green-600">
                                        <i class="fa-solid fa-check-circle"></i> Approved
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.communications.outbound.show', $rec->communication_id) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-brand text-white shadow-lg shadow-brand/20 hover:scale-105 transition-all">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-20">
                                    <div class="flex flex-col items-center opacity-20">
                                        <i class="fa-solid fa-envelope-open-text text-6xl mb-4"></i>
                                        <p class="font-bold uppercase tracking-widest text-sm">No requests pending review</p>
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
