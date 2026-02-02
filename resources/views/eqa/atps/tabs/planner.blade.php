<div class="space-y-4">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-bold text-premium">Visit Planner</h3>
            <p class="text-xs text-slate-500">IQC External Quality Assurance Visit Planner Forms</p>
        </div>
    </div>

    <!-- IQC EQA Visit Planner (008) -->
    <div
        class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-xl hover:bg-white hover:shadow-md transition-all group">
        <div class="flex items-center gap-4">
            <div
                class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div>
                <h4 class="font-bold text-slate-700">IQC External Quality Assurance Visit Planner</h4>
                <p class="text-xs text-slate-400">Form 008</p>
            </div>
        </div>
        <a href="{{ route('eqa.forms.show', ['form_id' => '008', 'atp_id' => $atp->atp_id]) }}"
            class="px-4 py-2 text-xs font-bold text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-600 hover:text-white transition-colors">Open</a>
    </div>

    <!-- EQA Report on ATP Accreditation (003) -->
    <div
        class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-xl hover:bg-white hover:shadow-md transition-all group">
        <div class="flex items-center gap-4">
            <div
                class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-lg group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <i class="fa-solid fa-file-contract"></i>
            </div>
            <div>
                <h4 class="font-bold text-slate-700">EQA Report on ATP Accreditation</h4>
                <p class="text-xs text-slate-400">Form 003</p>
            </div>
        </div>
        <a href="{{ route('eqa.forms.show', ['form_id' => '003', 'atp_id' => $atp->atp_id]) }}"
            class="px-4 py-2 text-xs font-bold text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-600 hover:text-white transition-colors">Open</a>
    </div>

    <!-- Internal ATP Approval Site Inspection (014) -->
    <div
        class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-xl hover:bg-white hover:shadow-md transition-all group">
        <div class="flex items-center gap-4">
            <div
                class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-lg group-hover:bg-amber-600 group-hover:text-white transition-colors">
                <i class="fa-solid fa-building-circle-check"></i>
            </div>
            <div>
                <h4 class="font-bold text-slate-700">ATP Approval Site Inspection Checklist</h4>
                <p class="text-xs text-slate-400">Form 014</p>
            </div>
        </div>
        <a href="{{ route('eqa.forms.show', ['form_id' => '014', 'atp_id' => $atp->atp_id]) }}"
            class="px-4 py-2 text-xs font-bold text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-600 hover:text-white transition-colors">Open</a>
    </div>
</div>
