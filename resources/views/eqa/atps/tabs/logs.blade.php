<div class="space-y-8">
    <div class="flex flex-col gap-2">
        <h3 class="text-2xl font-black text-slate-800 uppercase tracking-widest">Audit Logs</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Transaction Traceability & Security History</p>
    </div>

    <div class="relative">
        @if($logs->isEmpty())
            <div class="premium-card p-20 text-center">
                <i class="fa-solid fa-clock-rotate-left text-4xl text-slate-100 mb-6"></i>
                <h4 class="text-lg font-bold text-slate-400 uppercase tracking-widest">No Audit History</h4>
            </div>
        @else
            <div class="space-y-4">
                @foreach($logs as $log)
                    <div class="premium-card p-0 overflow-hidden group">
                        <div class="flex items-stretch min-h-[80px]">
                            <!-- Left Accent Color based on action -->
                            <div class="w-1.5 {{ str_contains(strtolower($log->action), 'update') ? 'bg-amber-400' : (str_contains(strtolower($log->action), 'create') ? 'bg-emerald-400' : 'bg-indigo-400') }}"></div>
                            
                            <div class="flex-1 p-6 flex items-center justify-between gap-8">
                                <div class="flex items-center gap-6">
                                    <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100 group-hover:bg-brand/10 group-hover:text-brand transition-all">
                                        <i class="fa-solid fa-fingerprint text-sm"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <p class="text-sm font-black text-slate-800 tracking-tight leading-tight">{{ $log->action }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Performed by:</span>
                                            <span class="text-[10px] font-black text-brand uppercase tracking-widest">{{ $log->logger_name }} {{ $log->logger_last_name ?? '' }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <div class="text-[11px] font-black text-slate-700 tracking-tighter">{{ date('H:i:s', strtotime($log->log_date)) }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ date('d M, Y', strtotime($log->log_date)) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
