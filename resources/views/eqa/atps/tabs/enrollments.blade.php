<div class="space-y-8">
    <div class="flex flex-col gap-2">
        <h3 class="text-2xl font-black text-slate-800 uppercase tracking-widest">Learner Enrollments</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Live Training & Certification Metrics</p>
    </div>

    @if($leRecords->isEmpty())
        <div class="premium-card p-20 text-center">
            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 mx-auto mb-6 border-2 border-slate-100">
                <i class="fa-solid fa-user-graduate text-2xl"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-400 uppercase tracking-widest">No Enrollments Recorded</h4>
            <p class="text-xs text-slate-400 mt-2">No learners have been enrolled in any qualifications for this provider yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($leRecords as $record)
                <div class="premium-card p-6 border-l-4 border-l-brand hover:shadow-xl transition-all duration-500">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex flex-col">
                            <h4 class="text-sm font-black text-slate-800 uppercase tracking-tight">{{ $record->qualification_name }}</h4>
                            <span class="text-[10px] text-slate-400 font-bold tracking-widest mt-1">Ref ID: #{{ $record->record_id }}</span>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-black text-brand leading-none">{{ $record->total_learners }}</div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Enrolled</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-slate-50 flex items-center justify-between">
                        <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-calendar-day text-slate-300"></i>
                            {{ date('d M, Y', strtotime($record->added_date)) }}
                        </div>
                        <span class="text-[9px] font-bold text-brand uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-award"></i>
                            Active Cycle
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
