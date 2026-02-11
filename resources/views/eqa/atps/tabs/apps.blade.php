<div class="space-y-8">
    <div class="flex flex-col gap-2">
        <h3 class="text-2xl font-black text-slate-800 uppercase tracking-widest">Applications</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Registration & Compliance Forms History</p>
    </div>

    @if(empty($apps))
        <div class="premium-card p-20 text-center">
            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 mx-auto mb-6 border-2 border-slate-100">
                <i class="fa-solid fa-folder-open text-2xl"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-400 uppercase tracking-widest">No Applications Found</h4>
            <p class="text-xs text-slate-400 mt-2">No historical registration or program requests recorded for this provider.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($apps as $app)
                <div class="premium-card p-8 group hover:-translate-y-1 transition-all duration-500">
                    <div class="flex justify-between items-start mb-6">
                        <div class="px-3 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest">
                            {{ $app['type'] }}
                        </div>
                        <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">Ref: {{ $atp->atp_ref }}</span>
                    </div>
                    
                    <h4 class="text-lg font-black text-slate-800 leading-tight mb-4 group-hover:text-indigo-600 transition-colors">{{ $app['name'] }}</h4>
                    
                    <div class="space-y-3 mb-8">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-slate-400 font-bold uppercase tracking-widest">System Status</span>
                            <span class="font-black {{ $app['status'] == 'Submitted' ? 'text-emerald-500' : 'text-amber-500' }} uppercase tracking-widest">{{ $app['status'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-slate-400 font-bold uppercase tracking-widest">Form Status</span>
                            <span class="text-slate-600 font-black uppercase tracking-widest">{{ $app['form_status'] }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                        <div class="flex flex-col gap-1">
                            <span class="text-[9px] text-slate-300 font-bold uppercase tracking-tighter">Initiated On</span>
                            <span class="text-[10px] text-slate-500 font-black tracking-widest">{{ $app['start_date'] ? date('d M, Y', strtotime($app['start_date'])) : 'N/A' }}</span>
                        </div>
                        @if($app['submit_date'])
                            <div class="flex flex-col gap-1 text-right">
                                <span class="text-[9px] text-slate-300 font-bold uppercase tracking-tighter">Submitted On</span>
                                <span class="text-[10px] text-slate-500 font-black tracking-widest">{{ date('d M, Y', strtotime($app['submit_date'])) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
