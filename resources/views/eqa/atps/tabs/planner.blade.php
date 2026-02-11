<div class="space-y-12">
    
    <!-- Section 1: Forms -->
    <div class="space-y-6">
        <div class="flex flex-col gap-1">
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-widest">Forms</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Internal Feedback & Planning modules</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Module 008: Visit Planner -->
            <div class="premium-card p-6 bg-white hover:bg-slate-50/10 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-500 group border-slate-100/50 shadow-md">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-brand/5 text-brand flex items-center justify-center text-2xl group-hover:bg-gradient-brand group-hover:text-white transition-all duration-500 shadow-sm">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Module 008</span>
                </div>
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-tight leading-tight mb-4 min-h-[40px]">IQC External Quality Assurance Visit Planner</h4>
                <a href="{{ route('eqa.forms.show', ['form_id' => '008', 'atp_id' => $atp->atp_id]) }}" 
                    class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-widest hover:bg-gradient-brand hover:text-white hover:border-transparent transition-all active:scale-95 shadow-sm">
                    <span>Access Module</span>
                    <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>

            <!-- Module 006: Feedback Form -->
            <div class="premium-card p-6 bg-white hover:bg-slate-50/10 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-500 group border-slate-100/50 shadow-md">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl group-hover:bg-indigo-500 group-hover:text-white transition-all duration-500 shadow-sm">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Module 006</span>
                </div>
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-tight leading-tight mb-4 min-h-[40px]">Internal Remote Activity Feedback Form</h4>
                <a href="{{ route('eqa.forms.show', ['form_id' => '006', 'atp_id' => $atp->atp_id]) }}" 
                    class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-widest hover:bg-gradient-brand hover:text-white hover:border-transparent transition-all active:scale-95 shadow-sm">
                    <span>Access Module</span>
                    <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>

            <!-- Module 007: Evidence Log -->
            <div class="premium-card p-6 bg-white hover:bg-slate-50/10 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-500 group border-slate-100/50 shadow-md">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-2xl group-hover:bg-cyan-500 group-hover:text-white transition-all duration-500 shadow-sm">
                        <i class="fa-solid fa-folder-open"></i>
                    </div>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Module 007</span>
                </div>
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-tight leading-tight mb-4 min-h-[40px]">Evidence Collection Log</h4>
                <a href="{{ route('eqa.forms.show', ['form_id' => '007', 'atp_id' => $atp->atp_id]) }}" 
                    class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-widest hover:bg-gradient-brand hover:text-white hover:border-transparent transition-all active:scale-95 shadow-sm">
                    <span>Access Module</span>
                    <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Section 2: Information Requests -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex flex-col gap-1">
                <h3 class="text-xl font-black text-slate-800 uppercase tracking-widest">Information Request</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Evidence & Documentation Requirements</p>
            </div>
            
            <!-- New Request Button (Legacy: atps/new_info_request/?atp_id=...) -->
            <a href="{{ route('eqa.atps.new_info_request', ['atp_id' => $atp->atp_id]) }}" 
               class="px-5 py-2.5 rounded-xl bg-brand text-white font-black text-[10px] uppercase tracking-widest shadow-lg shadow-brand/20 hover:bg-brand-dark hover:shadow-xl transition-all active:scale-95 flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>New Request</span>
            </a>
        </div>

        @if($infoRequests->isEmpty())
            <div class="premium-card p-12 text-center border-dashed border-2 border-slate-100 bg-slate-50/30">
                <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 mx-auto mb-4">
                    <i class="fa-solid fa-inbox text-xl"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest">No Information Requests</h4>
                <p class="text-xs text-slate-400 mt-1">No pending or historical requests found.</p>
            </div>
        @else
            <div class="overflow-hidden bg-white rounded-2xl border border-slate-100 shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80">
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Request Date</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Request By</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Response Date</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Status</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 text-right">Options</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($infoRequests as $req)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-5 text-xs font-bold text-slate-600">{{ date('d M, Y', strtotime($req->request_date)) }}</td>
                                <td class="px-6 py-5 text-xs font-bold text-slate-600">
                                    {{ $req->requester_first_name }} {{ $req->requester_last_name ?? '' }}
                                </td>
                                <td class="px-6 py-5 text-xs font-bold text-slate-500">
                                    {{ $req->response_date ? date('d M, Y', strtotime($req->response_date)) : '-' }}
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest border
                                        {{ $req->request_status == 'submitted' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                                        {{ $req->request_status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <a href="{{ route('eqa.atps.view_info_request', ['request_id' => $req->request_id, 'atp_id' => $atp->atp_id]) }}" 
                                       class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-slate-50 border border-slate-200 text-slate-500 font-bold text-[10px] uppercase tracking-widest hover:bg-brand hover:text-white hover:border-brand transition-all active:scale-95">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
