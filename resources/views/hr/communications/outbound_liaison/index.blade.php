@extends('layouts.app')

@section('title', 'Liaison: Outbound Dispatch')
@section('subtitle', 'Finalize dispatch and generate reference codes (Form 2)')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <!-- Info Card -->
        <div class="premium-card p-6 bg-brand text-white flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-paper-plane text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-display font-bold">Ready for Dispatch</h3>
                    <p class="text-xs text-white/70">These requests have been approved by the GM and require a final reference code and document upload.</p>
                </div>
            </div>
        </div>

        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left">Ref Number</th>
                            <th class="text-left">Initiator</th>
                            <th class="text-left">Subject</th>
                            <th class="text-center">GM Approval</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($records as $rec)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4">
                                    @if($rec->communication_code)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-mono font-bold uppercase bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $rec->communication_code }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase bg-slate-50 text-slate-400 border border-slate-100 italic">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                              
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold">
                                            {{ substr($rec->employee->employee_name ?? 'E', 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-700">{{ $rec->employee->employee_name }}</span>
                                            <span class="text-[10px] text-slate-400 font-medium">{{ $rec->employee->department->department_name ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-sm text-slate-600 font-medium truncate max-w-xs">{{ $rec->communication_subject }}</span>
                                        <span class="text-[10px] text-slate-400 italic">Approved on {{ $rec->approved_2_date ? date('Y-m-d H:i', $rec->approved_2_date) : 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase bg-green-50 text-green-600">
                                        <i class="fa-solid fa-check-double mr-1"></i> GM Approved
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('emp.outbound-liaison.show', $rec->communication_id) }}" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-brand transition-all shadow-lg inline-block">
                                        Review & Finalize
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-20 text-slate-400">
                                    <div class="flex flex-col items-center opacity-20">
                                        <i class="fa-solid fa-inbox text-6xl mb-4"></i>
                                        <p class="font-bold uppercase tracking-widest text-sm">No requests ready for dispatch</p>
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
