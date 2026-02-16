@extends('layouts.app')

@section('title', 'Form 006')
@section('subtitle', 'Internal Remote Activity Feedback Form')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8 animate-fade-in">

        <!-- Header Action Bar -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white/80 backdrop-blur-md p-6 rounded-3xl border border-white shadow-xl shadow-slate-200/50">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white shadow-lg shadow-brand/20">
                    <i class="fa-solid fa-comments text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-800 leading-tight">{{ $atp->atp_name }}</h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="px-2 py-0.5 rounded-md bg-brand/10 text-brand text-[10px] font-black uppercase tracking-wider">Form 006</span>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Internal Remote Activity Feedback</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'planner']) }}"
                    class="premium-button bg-slate-800 hover:bg-slate-900 shadow-md transform hover:-translate-x-1 transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Back to Planner</span>
                </a>
            </div>
        </div>

        <!-- General Information -->
        <div class="premium-card p-8 bg-white shadow-xl shadow-slate-200/40 border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
                <i class="fa-solid fa-circle-info text-8xl rotate-12"></i>
            </div>

            <h3 class="font-black text-slate-800 mb-8 uppercase text-[11px] tracking-[0.2em] flex items-center gap-3">
                <span class="w-8 h-1 bg-brand rounded-full"></span>
                <span>General Information</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Author of SED</label>
                    <div class="premium-input bg-slate-50/50 border-slate-200/60 text-slate-600 font-bold text-sm min-h-[45px] flex items-center px-4 rounded-xl">
                        {{ $sed_data->sed_1 ?? 'N/A' }}
                    </div>
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Role</label>
                    <div class="premium-input bg-slate-50/50 border-slate-200/60 text-slate-600 font-bold text-sm min-h-[45px] flex items-center px-4 rounded-xl">
                        {{ $sed_data->sed_2 ?? 'N/A' }}
                    </div>
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-brand">Last Audit Date</label>
                    <div class="premium-input bg-brand/5 border-brand/10 text-brand font-bold text-sm min-h-[45px] flex items-center px-4 rounded-xl">
                        <i class="fa-solid fa-calendar-day mr-2 opacity-50"></i>
                        {{ $sed_data->sed_3 ?? 'N/A' }}
                    </div>
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Submitted Date</label>
                    <div class="premium-input bg-slate-50/50 border-slate-200/60 text-slate-600 font-bold text-sm min-h-[45px] flex items-center px-4 rounded-xl">
                        <i class="fa-solid fa-clock-rotate-left mr-2 opacity-50 text-slate-400"></i>
                        {{ $sed_data->submitted_date ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Summary Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Overall Score -->
            <div class="premium-card p-6 bg-slate-900 text-white shadow-xl shadow-slate-900/10 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-10 transform scale-150 group-hover:rotate-12 transition-transform duration-500">
                    <i class="fa-solid fa-chart-line text-8xl"></i>
                </div>
                <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Overall Compliance</p>
                <div class="flex items-baseline gap-2">
                    <h4 class="text-4xl font-black">{{ $kpis['avg']['score'] }}%</h4>
                    <span class="text-[10px] font-bold text-slate-400">Score</span>
                </div>
                <div class="mt-4 h-1.5 w-full bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-brand transition-all duration-1000" style="width: {{ $kpis['avg']['score'] }}%"></div>
                </div>
            </div>

            @foreach($qsMains as $main)
                @php
                    $kpi = $kpis['mains'][$main->main_id] ?? ['score' => 0, 'status' => 'danger'];
                    $statusColors = [
                        'success' => 'bg-emerald-50 text-emerald-600 shadow-emerald-100 border-emerald-100',
                        'warning' => 'bg-amber-50 text-amber-600 shadow-amber-100 border-amber-100',
                        'danger' => 'bg-rose-50 text-rose-600 shadow-rose-100 border-rose-100'
                    ];
                    $barColors = [
                        'success' => 'bg-emerald-500',
                        'warning' => 'bg-amber-500',
                        'danger' => 'bg-rose-500'
                    ];
                    $colorClass = $statusColors[$kpi['status']] ?? $statusColors['danger'];
                    $barColor = $barColors[$kpi['status']] ?? $barColors['danger'];
                @endphp
                <div class="premium-card p-5 {{ $colorClass }} border shadow-lg relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
                    <div class="flex justify-between items-start mb-3">
                        <div class="w-8 h-8 rounded-lg bg-white/60 flex items-center justify-center">
                            <i class="fa-solid {{ $main->main_icon ?? 'fa-circle-check' }} text-sm"></i>
                        </div>
                        <span class="text-xl font-black">{{ $kpi['score'] }}%</span>
                    </div>
                    <h5 class="text-[10px] font-black uppercase tracking-wider leading-tight pr-4">
                        {{ $main->main_title }}
                    </h5>
                    <div class="mt-4 h-1 w-full bg-white/40 rounded-full overflow-hidden">
                        <div class="h-full {{ $barColor }} rounded-full" style="width: {{ $kpi['score'] }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Evidence Table -->
        <div class="premium-card p-0 overflow-hidden bg-white shadow-xl shadow-slate-200/50 border-slate-100">
            <div class="p-8 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-black text-slate-800 uppercase text-[11px] tracking-[0.2em] flex items-center gap-3">
                        <span class="w-8 h-1 bg-slate-300 rounded-full"></span>
                        <span>Desktop Review</span>
                    </h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2 ml-11">Evidence against ATPQS Standards</p>
                </div>
                <div class="hidden md:flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Applicable</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-slate-400"></span>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">N/A</span>
                    </div>
                </div>
            </div>

            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 w-1/4">Standard / Requirement</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 w-44">ATP Status</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Comment / Evidence</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 w-1/3 text-brand">EQA Response & Feedback</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 text-center w-24">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($complianceData as $record)
                        <tr class="group hover:bg-slate-50/50 transition-all duration-300" id="row-{{ $record->record_id }}">
                            <td class="px-8 py-6 align-top">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] font-black text-brand tracking-widest">{{ $record->cat_ref }}</span>
                                    <span class="text-xs font-bold text-slate-700 leading-relaxed">{{ $record->cat_description }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 align-top">
                                @php
                                    $statusLabels = [
                                        1 => ['text' => 'Applicable', 'class' => 'bg-emerald-50 text-emerald-600 border-emerald-100/50', 'icon' => 'fa-check-circle'],
                                        2 => ['text' => 'Not Applicable', 'class' => 'bg-slate-100 text-slate-500 border-slate-200/50', 'icon' => 'fa-minus-circle'],
                                        3 => ['text' => 'Included in QIP', 'class' => 'bg-amber-50 text-amber-600 border-amber-100/50', 'icon' => 'fa-arrow-circle-right']
                                    ];
                                    $status = $statusLabels[$record->answer] ?? $statusLabels[2];
                                @endphp
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $status['class'] }}">
                                    <i class="fa-solid {{ $status['icon'] }}"></i>
                                    {{ $status['text'] }}
                                </div>
                            </td>
                            <td class="px-8 py-6 align-top space-y-3">
                                <div class="p-4 rounded-xl bg-slate-50/50 border border-slate-100/50">
                                    <p class="text-[11px] font-bold text-slate-600 leading-relaxed italic">
                                        "{{ $record->cat_comment ?? 'No comments provided by provider.' }}"
                                    </p>
                                </div>
                                @if(isset($record->evidence) && $record->evidence)
                                    <a href="{{ asset('uploads/' . $record->evidence) }}" target="_blank"
                                        class="inline-flex items-center gap-2 text-[9px] font-black uppercase tracking-[0.15em] text-brand hover:text-brand-dark transition-colors bg-brand/5 px-3 py-1.5 rounded-lg border border-brand/10">
                                        <i class="fa-solid fa-paperclip text-xs"></i> View Document
                                    </a>
                                @endif
                            </td>
                            <td class="px-8 py-6 align-top flex flex-col gap-4">
                                <select
                                    class="w-full text-[10px] font-black uppercase tracking-widest bg-white border-2 border-slate-100 rounded-xl px-4 py-3 text-slate-700 focus:ring-4 focus:ring-brand/5 focus:border-brand/30 transition-all outline-none"
                                    id="criteria-{{ $record->record_id }}">
                                    <option value="100" {{ ($record->eqa_criteria ?? 100) == 100 ? 'selected' : '' }}>Please Select Recommendation...</option>
                                    <option value="1" {{ ($record->eqa_criteria ?? 100) == 1 ? 'selected' : '' }}>YES - Evidence Sufficient</option>
                                    <option value="0" {{ ($record->eqa_criteria ?? 100) == 0 ? 'selected' : '' }}>NO - Evidence Missing/Weak</option>
                                </select>
                                <textarea rows="3"
                                    class="w-full resize-none text-xs font-bold text-slate-600 bg-white border-2 border-slate-100 rounded-xl px-4 py-3 focus:ring-4 focus:ring-brand/5 focus:border-brand/30 transition-all outline-none placeholder:text-slate-300"
                                    id="feedback-{{ $record->record_id }}"
                                    placeholder="Enter your professional EQA feedback here...">{{ $record->eqa_feedback ?? '' }}</textarea>
                            </td>
                            <td class="px-8 py-6 align-top text-center">
                                <button type="button" onclick="saveRecord({{ $record->record_id }})"
                                    class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center hover:bg-brand transition-all shadow-xl shadow-slate-900/10 active:scale-90 group/btn mx-auto">
                                    <i class="fa-solid fa-check text-lg group-hover/btn:scale-125 transition-transform duration-300"></i>
                                </button>
                                <p class="text-[8px] font-black text-slate-300 uppercase mt-2 tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">Save Progress</p>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <script>
        function saveRecord(recordId) {
            const criteria = document.getElementById('criteria-' + recordId).value;
            const feedback = document.getElementById('feedback-' + recordId).value;

            // Visual feedback
            const btn = document.querySelector(`#row-${recordId} button`);
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';

            // AJAX Request
            fetch('{{ route("eqa.forms.save_006") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    record_id: recordId,
                    atp_id: {{ $atp->atp_id }},
                    eqa_criteria: criteria,
                    eqa_feedback: feedback
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Success feedback
                        btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                        btn.classList.add('bg-emerald-500', 'border-emerald-500');
                        btn.classList.remove('bg-brand');
                        setTimeout(() => {
                            btn.classList.remove('bg-emerald-500', 'border-emerald-500');
                            btn.classList.add('bg-brand');
                        }, 2000);
                    } else {
                        alert('Error saving data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    btn.innerHTML = originalIcon;
                    alert('An error occurred.');
                });
        }
    </script>
@endsection