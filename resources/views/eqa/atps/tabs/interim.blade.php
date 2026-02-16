<div class="space-y-12">
    <!-- Header -->
    <div class="flex flex-col gap-2">
        <h3 class="text-2xl font-black text-slate-800 uppercase tracking-widest">Interim Audit</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Interim Forms and Interview Protocols</p>
    </div>

    <!-- Section 1: Core Audit Forms -->
    <div class="space-y-6">
        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 pb-2">Core
            Audit Forms</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Module 007: Evidence Log -->
            <div
                class="premium-card p-6 bg-white hover:bg-slate-50/10 hover:-translate-y-1 transition-all duration-500 group border-slate-100/50 shadow-md">
                <div class="flex items-start justify-between mb-6">
                    <div
                        class="w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-2xl group-hover:bg-cyan-500 group-hover:text-white transition-all duration-500 shadow-sm">
                        <i class="fa-solid fa-folder-open"></i>
                    </div>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Module 007</span>
                </div>
                <h4
                    class="text-sm font-bold text-slate-800 uppercase tracking-tight leading-tight mb-4 min-h-[40px] flex items-center">
                    Evidence Collection Log</h4>
                <a href="{{ route('eqa.forms.show', ['form_id' => '007', 'atp_id' => $atp->atp_id]) }}"
                    class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-widest hover:bg-gradient-brand hover:text-white hover:border-transparent transition-all active:scale-95 shadow-sm">
                    <span>Access Module</span>
                    <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>

            <!-- Module 008: Visit Planner -->
            <div
                class="premium-card p-6 bg-white hover:bg-slate-50/10 hover:-translate-y-1 transition-all duration-500 group border-slate-100/50 shadow-md">
                <div class="flex items-start justify-between mb-6">
                    <div
                        class="w-14 h-14 rounded-2xl bg-brand/5 text-brand flex items-center justify-center text-2xl group-hover:bg-gradient-brand group-hover:text-white transition-all duration-500 shadow-sm">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Module 008</span>
                </div>
                <h4
                    class="text-sm font-bold text-slate-800 uppercase tracking-tight leading-tight mb-4 min-h-[40px] flex items-center">
                    Visit Planner</h4>
                <a href="{{ route('eqa.forms.show', ['form_id' => '008', 'atp_id' => $atp->atp_id]) }}"
                    class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-widest hover:bg-gradient-brand hover:text-white hover:border-transparent transition-all active:scale-95 shadow-sm">
                    <span>Access Module</span>
                    <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>

            <!-- Module 006: Feedback Form -->
            <div
                class="premium-card p-6 bg-white hover:bg-slate-50/10 hover:-translate-y-1 transition-all duration-500 group border-slate-100/50 shadow-md">
                <div class="flex items-start justify-between mb-6">
                    <div
                        class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl group-hover:bg-indigo-500 group-hover:text-white transition-all duration-500 shadow-sm">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Module 006</span>
                </div>
                <h4
                    class="text-sm font-bold text-slate-800 uppercase tracking-tight leading-tight mb-4 min-h-[40px] flex items-center">
                    Remote Activity Feedback</h4>
                <a href="{{ route('eqa.forms.show', ['form_id' => '006', 'atp_id' => $atp->atp_id]) }}"
                    class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-widest hover:bg-gradient-brand hover:text-white hover:border-transparent transition-all active:scale-95 shadow-sm">
                    <span>Access Module</span>
                    <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>

        </div>
    </div>

    <!-- Section 2: Interview Protocols -->
    <div class="space-y-6">
        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 pb-2">
            Interview Protocols</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Module 017: Assessors Interview -->
            <div
                class="premium-card p-5 bg-white border-slate-100/50 shadow-md hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex flex-col gap-4">
                    <div class="flex justify-between items-start">
                        <div
                            class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300 shadow-sm">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Mod 017</span>
                    </div>
                    <h5 class="font-bold text-slate-700 text-xs uppercase tracking-wider min-h-[32px]">Assessors /
                        Trainer Interview</h5>
                    <a href="{{ route('eqa.forms.show', ['form_id' => '017', 'atp_id' => $atp->atp_id]) }}"
                        class="text-[10px] font-black text-brand uppercase tracking-widest hover:underline flex items-center gap-1">
                        Start Interview <i class="fa-solid fa-arrow-right text-[8px]"></i>
                    </a>
                </div>
            </div>

            <!-- Module 018: IQA Interview -->
            <div
                class="premium-card p-5 bg-white border-slate-100/50 shadow-md hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex flex-col gap-4">
                    <div class="flex justify-between items-start">
                        <div
                            class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl group-hover:bg-amber-500 group-hover:text-white transition-all duration-300 shadow-sm">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Mod 018</span>
                    </div>
                    <h5 class="font-bold text-slate-700 text-xs uppercase tracking-wider min-h-[32px]">IQA Interview
                        Protocol</h5>
                    <a href="{{ route('eqa.forms.show', ['form_id' => '018', 'atp_id' => $atp->atp_id]) }}"
                        class="text-[10px] font-black text-brand uppercase tracking-widest hover:underline flex items-center gap-1">
                        Start Interview <i class="fa-solid fa-arrow-right text-[8px]"></i>
                    </a>
                </div>
            </div>

            <!-- Module 019: Learner Interview -->
            <div
                class="premium-card p-5 bg-white border-slate-100/50 shadow-md hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex flex-col gap-4">
                    <div class="flex justify-between items-start">
                        <div
                            class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl group-hover:bg-purple-500 group-hover:text-white transition-all duration-300 shadow-sm">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Mod 019</span>
                    </div>
                    <h5 class="font-bold text-slate-700 text-xs uppercase tracking-wider min-h-[32px]">Learner Interview
                        Protocol</h5>
                    <a href="{{ route('eqa.forms.show', ['form_id' => '019', 'atp_id' => $atp->atp_id]) }}"
                        class="text-[10px] font-black text-brand uppercase tracking-widest hover:underline flex items-center gap-1">
                        Start Interview <i class="fa-solid fa-arrow-right text-[8px]"></i>
                    </a>
                </div>
            </div>

            <!-- Module 020: Lead IQA Interview -->
            <div
                class="premium-card p-5 bg-white border-slate-100/50 shadow-md hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex flex-col gap-4">
                    <div class="flex justify-between items-start">
                        <div
                            class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl group-hover:bg-rose-500 group-hover:text-white transition-all duration-300 shadow-sm">
                            <i class="fa-solid fa-users-gear"></i>
                        </div>
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Mod 020</span>
                    </div>
                    <h5 class="font-bold text-slate-700 text-xs uppercase tracking-wider min-h-[32px]">Lead IQA
                        Interview</h5>
                    <a href="{{ route('eqa.forms.show', ['form_id' => '020', 'atp_id' => $atp->atp_id]) }}"
                        class="text-[10px] font-black text-brand uppercase tracking-widest hover:underline flex items-center gap-1">
                        Start Interview <i class="fa-solid fa-arrow-right text-[8px]"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Section 3: Observations & Checks -->
    <div class="space-y-6">
        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 pb-2">
            Observation & Checklists</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Module 014: Site Inspection -->
            <div
                class="premium-card p-6 bg-white hover:bg-slate-50/10 hover:-translate-y-1 transition-all duration-500 group border-slate-100/50 shadow-md">
                <div class="flex items-start justify-between mb-6">
                    <div
                        class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-2xl group-hover:bg-teal-500 group-hover:text-white transition-all duration-500 shadow-sm">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Module 014</span>
                </div>
                <h4
                    class="text-sm font-bold text-slate-800 uppercase tracking-tight leading-tight mb-4 min-h-[40px] flex items-center">
                    ATP Approval Site Checklist</h4>
                <a href="{{ route('eqa.forms.show', ['form_id' => '014', 'atp_id' => $atp->atp_id]) }}"
                    class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-widest hover:bg-gradient-brand hover:text-white hover:border-transparent transition-all active:scale-95 shadow-sm">
                    <span>Access Module</span>
                    <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>

            <!-- Module 049: Teaching Observation -->
            <div
                class="premium-card p-6 bg-white hover:bg-slate-50/10 hover:-translate-y-1 transition-all duration-500 group border-slate-100/50 shadow-md">
                <div class="flex items-start justify-between mb-6">
                    <div
                        class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl group-hover:bg-blue-500 group-hover:text-white transition-all duration-500 shadow-sm">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Module 049</span>
                </div>
                <h4
                    class="text-sm font-bold text-slate-800 uppercase tracking-tight leading-tight mb-4 min-h-[40px] flex items-center">
                    Teaching & Learning Observation</h4>
                <a href="{{ route('eqa.forms.show', ['form_id' => '049', 'atp_id' => $atp->atp_id]) }}"
                    class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-widest hover:bg-gradient-brand hover:text-white hover:border-transparent transition-all active:scale-95 shadow-sm">
                    <span>Access Module</span>
                    <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>

            <!-- Module 028: Live Assessment -->
            <div
                class="premium-card p-6 bg-white hover:bg-slate-50/10 hover:-translate-y-1 transition-all duration-500 group border-slate-100/50 shadow-md">
                <div class="flex items-start justify-between mb-6">
                    <div
                        class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center text-2xl group-hover:bg-orange-500 group-hover:text-white transition-all duration-500 shadow-sm">
                        <i class="fa-solid fa-video"></i>
                    </div>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Module 028</span>
                </div>
                <h4
                    class="text-sm font-bold text-slate-800 uppercase tracking-tight leading-tight mb-4 min-h-[40px] flex items-center">
                    Live Assessment Observation</h4>
                <a href="{{ route('eqa.forms.show', ['form_id' => '028', 'atp_id' => $atp->atp_id]) }}"
                    class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-widest hover:bg-gradient-brand hover:text-white hover:border-transparent transition-all active:scale-95 shadow-sm">
                    <span>Access Module</span>
                    <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>

        </div>
    </div>
</div>