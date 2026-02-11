<div class="space-y-8">
    <div class="flex flex-col gap-2">
        <h3 class="text-2xl font-black text-slate-800 uppercase tracking-widest">Interim Audit</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Interim Forms and Interview Protocols</p>
    </div>

    <!-- Interviews Section -->
    <div class="mt-8 space-y-6">
        <div class="flex items-center gap-4">
            <h4 class="text-[10px] font-black text-brand uppercase tracking-[0.3em]">Interview Protocols</h4>
            <div class="flex-1 h-px bg-slate-100"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Assessors Interview (017) -->
            <div class="premium-card p-5 bg-white border-slate-100/50 shadow-md hover:shadow-[0_15px_30px_rgba(0,0,0,0.06)] hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-brand/5 text-brand flex items-center justify-center group-hover:bg-gradient-brand group-hover:text-white transition-all duration-300 border border-brand/10">
                            <i class="fa-solid fa-user-tie text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Module 017</p>
                            <h5 class="font-bold text-slate-700 text-sm">Assessors/Trainer Interview</h5>
                        </div>
                    </div>
                    <a href="{{ route('eqa.forms.show', ['form_id' => '017', 'atp_id' => $atp->atp_id]) }}" 
                        class="px-4 py-2 rounded-lg bg-slate-50 text-[10px] font-black text-brand uppercase tracking-widest hover:bg-gradient-brand hover:text-white transition-all shadow-sm">
                        Access <i class="fa-solid fa-chevron-right ml-1 text-[8px]"></i>
                    </a>
                </div>
            </div>

            <!-- IQA Interview (018) -->
            <div class="premium-card p-5 bg-white border-slate-100/50 shadow-md hover:shadow-[0_15px_30px_rgba(0,0,0,0.06)] hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-all duration-300 border border-amber-500/10">
                            <i class="fa-solid fa-user-shield text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Module 018</p>
                            <h5 class="font-bold text-slate-700 text-sm">IQA Interview Questions</h5>
                        </div>
                    </div>
                    <a href="{{ route('eqa.forms.show', ['form_id' => '018', 'atp_id' => $atp->atp_id]) }}" 
                        class="px-4 py-2 rounded-lg bg-slate-50 text-[10px] font-black text-brand uppercase tracking-widest hover:bg-gradient-brand hover:text-white transition-all shadow-sm">
                        Access <i class="fa-solid fa-chevron-right ml-1 text-[8px]"></i>
                    </a>
                </div>
            </div>

            <!-- Learner Interview (019) -->
            <div class="premium-card p-5 bg-white border-slate-100/50 shadow-md hover:shadow-[0_15px_30px_rgba(0,0,0,0.06)] hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300 border border-emerald-500/10">
                            <i class="fa-solid fa-user-graduate text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Module 019</p>
                            <h5 class="font-bold text-slate-700 text-sm">Learner Interview Questions</h5>
                        </div>
                    </div>
                    <a href="{{ route('eqa.forms.show', ['form_id' => '019', 'atp_id' => $atp->atp_id]) }}" 
                        class="px-4 py-2 rounded-lg bg-slate-50 text-[10px] font-black text-brand uppercase tracking-widest hover:bg-gradient-brand hover:text-white transition-all shadow-sm">
                        Access <i class="fa-solid fa-chevron-right ml-1 text-[8px]"></i>
                    </a>
                </div>
            </div>

            <!-- Lead IQA Interview (020) -->
            <div class="premium-card p-5 bg-white border-slate-100/50 shadow-md hover:shadow-[0_15px_30px_rgba(0,0,0,0.06)] hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition-all duration-300 border border-rose-500/10">
                            <i class="fa-solid fa-users-gear text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Module 020</p>
                            <h5 class="font-bold text-slate-700 text-sm">Lead IQA Interview</h5>
                        </div>
                    </div>
                    <a href="{{ route('eqa.forms.show', ['form_id' => '020', 'atp_id' => $atp->atp_id]) }}" 
                        class="px-4 py-2 rounded-lg bg-slate-50 text-[10px] font-black text-brand uppercase tracking-widest hover:bg-gradient-brand hover:text-white transition-all shadow-sm">
                        Access <i class="fa-solid fa-chevron-right ml-1 text-[8px]"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
