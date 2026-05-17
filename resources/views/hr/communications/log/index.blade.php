@extends('layouts.app')

@section('title', 'Communications Log')
@section('subtitle', 'Master log of all Inbound and Outbound correspondences')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-premium">Communications Log</h2>
            <p class="text-sm text-slate-500 mt-1">Combined register of all liaison-managed correspondences</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex items-center gap-4 border-b border-slate-200 mb-6">
        <button onclick="switchTab('correspondence')" id="btn-correspondence" class="px-4 py-3 text-sm font-bold border-b-2 border-indigo-600 text-indigo-600 transition-colors">
            Correspondences Register
        </button>
        <button onclick="switchTab('syslogs')" id="btn-syslogs" class="px-4 py-3 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-colors">
            System Actions Log
        </button>
    </div>

    {{-- Correspondences Tab --}}
    <div id="tab-correspondence" class="premium-card overflow-hidden block">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-semibold text-slate-700">All Correspondences</h3>
            <div class="relative w-64">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                <input type="text" id="logSearch" placeholder="Search correspondences..." class="premium-input w-full pl-9 py-2 text-sm">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="premium-table w-full" id="logTable">
                <thead>
                    <tr>
                        <th class="text-center font-bold text-slate-400">Type</th>
                        <th class="text-left font-bold text-slate-400">Date</th>
                        <th class="text-left font-bold text-slate-400">Ref Code</th>
                        <th class="text-left font-bold text-slate-400">Entity</th>
                        <th class="text-left font-bold text-slate-400">Subject</th>
                        <th class="text-center font-bold text-slate-400">Priority</th>
                        <th class="text-center font-bold text-slate-400">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/50 transition-colors log-row">
                        <td class="text-center">
                            @if($log['type'] == 'Inbound')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 uppercase tracking-wider">
                                    <i class="fa-solid fa-arrow-right-to-bracket text-[10px]"></i> {{ $log['type'] }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 uppercase tracking-wider">
                                    <i class="fa-solid fa-paper-plane text-[10px]"></i> {{ $log['type'] }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="text-sm text-slate-500 font-mono">{{ \Carbon\Carbon::parse($log['date'])->format('M d, Y') }}</span>
                        </td>
                        <td>
                            <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded">
                                {{ $log['ref_code'] }}
                            </span>
                        </td>
                        <td>
                            <span class="font-semibold text-slate-700 text-sm">{{ $log['entity'] }}</span>
                        </td>
                        <td class="max-w-xs">
                            <p class="text-sm text-slate-600 truncate" title="{{ $log['subject'] }}">{{ $log['subject'] }}</p>
                        </td>
                        <td class="text-center">
                            @php
                                $priorityColor = ['High'=>'red','Medium'=>'amber','Low'=>'emerald'][$log['priority']] ?? 'slate';
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-{{ $priorityColor }}-50 text-{{ $priorityColor }}-700 uppercase">
                                {{ $log['priority'] }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">
                                {{ $log['status'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-20">
                            <i class="fa-solid fa-clipboard-list text-5xl text-slate-100 mb-4 block"></i>
                            <p class="text-slate-400 font-medium">No correspondences found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- System Logs Tab --}}
    <div id="tab-syslogs" class="premium-card overflow-hidden hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-semibold text-slate-700">System Actions Log</h3>
            <p class="text-xs text-slate-500 mt-1">Raw audit trail of your actions in the system.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-left font-bold text-slate-400">Date & Time</th>
                        <th class="text-left font-bold text-slate-400">Action</th>
                        <th class="text-left font-bold text-slate-400">Related Table</th>
                        <th class="text-left font-bold text-slate-400">Remark / Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($sysLogs as $sLog)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="w-48 whitespace-nowrap">
                            <span class="text-sm text-slate-500 font-mono">{{ \Carbon\Carbon::parse($sLog->log_date)->format('M d, Y H:i:s') }}</span>
                        </td>
                        <td class="w-48 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700">
                                {{ $sLog->log_action }}
                            </span>
                        </td>
                        <td class="w-48 whitespace-nowrap">
                            <span class="text-xs text-slate-400 font-mono">{{ $sLog->related_table }} (ID: {{ $sLog->related_id }})</span>
                        </td>
                        <td>
                            <p class="text-sm text-slate-600">{{ $sLog->log_remark }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-20">
                            <i class="fa-solid fa-server text-5xl text-slate-100 mb-4 block"></i>
                            <p class="text-slate-400 font-medium">No system logs recorded for your user.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById('logSearch').addEventListener('keyup', function() {
        let val = this.value.toLowerCase();
        let rows = document.querySelectorAll('.log-row');
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
        });
    });

    function switchTab(tab) {
        document.getElementById('tab-correspondence').classList.add('hidden');
        document.getElementById('tab-syslogs').classList.add('hidden');
        
        document.getElementById('btn-correspondence').className = 'px-4 py-3 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-colors';
        document.getElementById('btn-syslogs').className = 'px-4 py-3 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-colors';
        
        document.getElementById('tab-' + tab).classList.remove('hidden');
        document.getElementById('btn-' + tab).className = 'px-4 py-3 text-sm font-bold border-b-2 border-indigo-600 text-indigo-600 transition-colors';
    }
</script>

@endsection
