@extends('layouts.app')

@section('title', 'Inbound — GM Review')
@section('subtitle', 'Review and decision on inbound correspondence')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-premium">Inbound Correspondence — GM View</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $records->total() }} total records pending review</p>
        </div>
    </div>

    {{-- Status Filter Tabs --}}
    <div class="flex flex-wrap gap-2">
        <a href="{{ request()->routeIs('emp.*') ? route('emp.inbound-gm.index') : route('admin.inbound.index') }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ !request('status') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            All
        </a>
        @php
            $statuses = ['Pending Approval', 'Resubmitted', 'Under Review', 'Approved', 'Rejected', 'Modifications Required'];
        @endphp
        @foreach($statuses as $st)
        <a href="{{ request()->routeIs('emp.*') ? route('emp.inbound-gm.index', ['status'=>$st]) : route('admin.inbound.index', ['status'=>$st]) }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request('status')==$st ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            {{ $st }}
        </a>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-left font-bold text-slate-400">Ref Code</th>
                        <th class="text-left font-bold text-slate-400">Entity</th>
                        <th class="text-left font-bold text-slate-400">Subject</th>
                        <th class="text-center font-bold text-slate-400">Priority</th>
                        <th class="text-left font-bold text-slate-400">Date</th>
                        <th class="text-center font-bold text-slate-400">Actions</th>
                        <th class="text-center font-bold text-slate-400">Status</th>
                        <th class="text-center font-bold text-slate-400"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($records as $rec)
                    @php
                        $pc = ['high'=>'red','medium'=>'amber','low'=>'emerald'][$rec->priority] ?? 'slate';
                        $statusStyle = match($rec->status) {
                            'Pending Approval'       => 'bg-amber-50 text-amber-700',
                            'Resubmitted'            => 'bg-indigo-50 text-indigo-700',
                            'Under Review'           => 'bg-blue-50 text-blue-700',
                            'Approved'               => 'bg-emerald-50 text-emerald-700',
                            'Rejected'               => 'bg-red-50 text-red-700',
                            'Modifications Required' => 'bg-orange-50 text-orange-700',
                            default                  => 'bg-slate-100 text-slate-600',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td>
                            <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">{{ $rec->reference_code }}</span>
                        </td>
                        <td>
                            <span class="font-semibold text-slate-700 text-sm">{{ $rec->entity->entity_name ?? '-' }}</span>
                        </td>
                        <td class="max-w-xs">
                            <p class="text-sm text-slate-600 truncate" title="{{ $rec->subject }}">{{ $rec->subject }}</p>
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-{{ $pc }}-50 text-{{ $pc }}-700 uppercase">{{ $rec->priority }}</span>
                        </td>
                        <td>
                            <span class="text-xs text-slate-400 font-mono">{{ \Carbon\Carbon::parse($rec->date_of_receipt)->format('M d, Y') }}</span>
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-600">
                                <i class="fa-solid fa-list-check text-xs"></i> {{ $rec->actionItems->count() }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold {{ $statusStyle }}">{{ $rec->status }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ request()->routeIs('emp.*') ? route('emp.inbound-gm.show', $rec->inbound_id) : route('admin.inbound.show', $rec->inbound_id) }}"
                               class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md mx-auto">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-20">
                            <i class="fa-solid fa-envelope-open text-5xl text-slate-100 mb-4 block"></i>
                            <p class="text-slate-400 font-medium">No inbound correspondences found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($records->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">{{ $records->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
