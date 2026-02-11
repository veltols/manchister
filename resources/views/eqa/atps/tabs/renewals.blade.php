<div class="space-y-8">
    <div class="flex flex-col gap-2">
        <h3 class="text-2xl font-black text-slate-800 uppercase tracking-widest">Renewals</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Accreditation Cycle Management</p>
    </div>

    @if($renewals->isEmpty())
        <div class="premium-card p-20 text-center">
            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 mx-auto mb-6 border-2 border-slate-100">
                <i class="fa-solid fa-rotate text-2xl"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-400 uppercase tracking-widest">No Renewals Found</h4>
            <p class="text-xs text-slate-400 mt-2">This provider is currently in its initial accreditation cycle.</p>
        </div>
    @else
        <div class="overflow-hidden bg-white rounded-3xl border border-slate-100 shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Cycle Date</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($renewals as $renewal)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-sm shadow-sm">
                                        <i class="fa-solid fa-calendar-check"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-700 uppercase tracking-tight">{{ date('d M, Y', strtotime($renewal->added_date)) }}</span>
                                        <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">Renewal Request</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                    {{ $renewal->is_submitted ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }}">
                                    {{ $renewal->is_submitted ? 'Completed' : 'Processing' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <button class="p-2 rounded-lg bg-slate-50 text-slate-400 hover:bg-brand hover:text-white transition-all">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
