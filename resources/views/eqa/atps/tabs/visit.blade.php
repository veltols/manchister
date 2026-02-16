<div class="space-y-8">
    <div class="flex flex-col gap-2">
        <h3 class="text-2xl font-black text-slate-800 uppercase tracking-widest">EQA Visit Suite</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Comprehensive Quality Assurance Modules
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
        <!-- Module 008: Visit Planner -->


        <!-- Module 004: Internal Report -->
        <div
            class="premium-card p-8 bg-white hover:bg-slate-50/10 hover:shadow-[0_20px_40px_rgba(0,0,0,0.1)] transition-all duration-500 group border-slate-100/50 relative overflow-hidden shadow-lg hover:-translate-y-1">
            <div
                class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl group-hover:bg-amber-500/10 transition-all">
            </div>

            <div class="relative">
                <div class="flex items-start justify-between mb-8">
                    <div
                        class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-3xl group-hover:bg-amber-500 group-hover:text-white transition-all duration-500 shadow-sm border border-amber-500/10">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <span
                        class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em] bg-slate-50 px-3 py-1 rounded-lg">Module
                        004</span>
                </div>
                <h4 class="text-xl font-bold text-slate-800 uppercase tracking-tight leading-tight mb-3">Internal Report
                </h4>
                <p class="text-xs font-medium text-slate-400 leading-relaxed mb-10 max-w-[280px]">Detailed internal
                    performance evaluation and accreditation reporting.</p>
                <a href="{{ route('eqa.forms.show', ['form_id' => '004', 'atp_id' => $atp->atp_id]) }}"
                    class="flex items-center justify-center gap-2 w-full py-4 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-widest hover:bg-gradient-brand hover:text-white hover:border-transparent transition-all active:scale-95 shadow-md">
                    <span>Launch Module</span>
                    <i class="fa-solid fa-arrow-right-long text-[10px] ml-1"></i>
                </a>
            </div>
        </div>


        <!-- Module 014: Site Inspection -->
        <div
            class="premium-card p-8 bg-white hover:bg-slate-50/10 hover:shadow-[0_20px_40px_rgba(0,0,0,0.1)] transition-all duration-500 group border-slate-100/50 relative overflow-hidden shadow-lg hover:-translate-y-1">
            <div
                class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:bg-emerald-500/10 transition-all">
            </div>

            <div class="relative">
                <div class="flex items-start justify-between mb-8">
                    <div
                        class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500 shadow-sm border border-emerald-500/10">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                    <span
                        class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em] bg-slate-50 px-3 py-1 rounded-lg">Module
                        014</span>
                </div>
                <h4 class="text-xl font-bold text-slate-800 uppercase tracking-tight leading-tight mb-3">Site Inspection
                </h4>
                <p class="text-xs font-medium text-slate-400 leading-relaxed mb-10 max-w-[280px]">Comprehensive physical
                    inspection and facility compliance verification.</p>
                <a href="{{ route('eqa.forms.show', ['form_id' => '014', 'atp_id' => $atp->atp_id]) }}"
                    class="flex items-center justify-center gap-2 w-full py-4 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-widest hover:bg-gradient-brand hover:text-white hover:border-transparent transition-all active:scale-95 shadow-md">
                    <span>Launch Module</span>
                    <i class="fa-solid fa-arrow-right-long text-[10px] ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Module 003: Accreditation Report -->
        <div
            class="premium-card p-8 bg-white hover:bg-slate-50/10 hover:shadow-[0_20px_40px_rgba(0,0,0,0.1)] transition-all duration-500 group border-slate-100/50 relative overflow-hidden shadow-lg hover:-translate-y-1">
            <div
                class="absolute -right-4 -top-4 w-24 h-24 bg-rose-500/5 rounded-full blur-2xl group-hover:bg-rose-500/10 transition-all">
            </div>

            <div class="relative">
                <div class="flex items-start justify-between mb-8">
                    <div
                        class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-3xl group-hover:bg-rose-500 group-hover:text-white transition-all duration-500 shadow-sm border border-rose-500/10">
                        <i class="fa-solid fa-stamp"></i>
                    </div>
                    <span
                        class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em] bg-slate-50 px-3 py-1 rounded-lg">Module
                        003</span>
                </div>
                <h4 class="text-xl font-bold text-slate-800 uppercase tracking-tight leading-tight mb-3">Accreditation
                    Report</h4>
                <p class="text-xs font-medium text-slate-400 leading-relaxed mb-10 max-w-[280px]">Final accreditation
                    assessment and official authorization report.</p>
                <a href="{{ route('eqa.forms.show', ['form_id' => '003', 'atp_id' => $atp->atp_id]) }}"
                    class="flex items-center justify-center gap-2 w-full py-4 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-widest hover:bg-gradient-brand hover:text-white hover:border-transparent transition-all active:scale-95 shadow-md">
                    <span>Launch Module</span>
                    <i class="fa-solid fa-arrow-right-long text-[10px] ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>