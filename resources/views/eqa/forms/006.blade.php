@extends('layouts.app')

@section('title', 'Form 006')
@section('subtitle', 'Internal Remote Activity Feedback Form')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up">

        <!-- Header Action Bar -->
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-sm">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-brand flex items-center justify-center text-white shadow-lg shadow-brand/20">
                    <i class="fa-solid fa-comments text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-premium leading-tight">{{ $atp->atp_name }}</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">Remote Activity Feedback
                    </p>
                </div>
            </div>
            <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'planner']) }}"
                class="premium-button bg-stone-500 hover:bg-stone-600 shadow-md">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Planner</span>
            </a>
        </div>

        <!-- General Information (Read Only) -->
        <div class="premium-card p-8 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50">
            <h3
                class="font-black text-slate-800 mb-6 uppercase text-xs tracking-[0.2em] border-l-4 border-l-brand pl-4 flex items-center gap-3">
                <span>General Information</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Author of
                        SED</label>
                    <div
                        class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 font-bold text-sm shadow-inner">
                        {{ $sed_data->sed_1 ?? 'N/A' }}
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Role</label>
                    <div
                        class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 font-bold text-sm shadow-inner">
                        {{ $sed_data->sed_2 ?? 'N/A' }}
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Last Audit
                        Date</label>
                    <div
                        class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 font-bold text-sm shadow-inner">
                        {{ $sed_data->sed_3 ?? 'N/A' }}
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Submitted
                        Date</label>
                    <div
                        class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 font-bold text-sm shadow-inner">
                        {{ $sed_data->submitted_date ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Evidence Table -->
        <div class="premium-card p-0 overflow-hidden bg-white shadow-lg shadow-slate-200/50 border-slate-100/50">
            <div class="p-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-black text-slate-800 uppercase text-xs tracking-[0.2em]">Desktop Review</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Evidence against ATPQS
                    </p>
                </div>
            </div>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/30">
                        <th
                            class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 w-1/4">
                            Standard / Requirement</th>
                        <th
                            class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 w-1/6">
                            ATP Status</th>
                        <th
                            class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 w-1/6">
                            Comment/Evidence</th>
                        <th
                            class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 w-1/3">
                            EQA Response</th>
                        <th
                            class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 text-right w-20">
                            Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($complianceData as $record)
                        <tr class="group hover:bg-slate-50/50 transition-colors" id="row-{{ $record->record_id }}">
                            <td class="px-6 py-5 align-top">
                                <div class="text-xs font-bold text-slate-700">
                                    <span class="text-brand mr-1">{{ $record->cat_ref }}</span>
                                    {{ $record->cat_description }}
                                </div>
                            </td>
                            <td class="px-6 py-5 align-top">
                                @php
                                    $statusLabels = [
                                        1 => ['text' => 'Applicable', 'class' => 'bg-emerald-50 text-emerald-600 border-emerald-100'],
                                        2 => ['text' => 'Not Applicable', 'class' => 'bg-slate-100 text-slate-500 border-slate-200'],
                                        3 => ['text' => 'Included in QIP', 'class' => 'bg-amber-50 text-amber-600 border-amber-100']
                                    ];
                                    $status = $statusLabels[$record->answer] ?? $statusLabels[2];
                                @endphp
                                <span
                                    class="inline-block px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-widest border {{ $status['class'] }}">
                                    {{ $status['text'] }}
                                </span>
                            </td>
                            <td class="px-6 py-5 align-top text-xs text-slate-500">
                                <p class="mb-2">{{ $record->cat_comment ?? 'No comments provided.' }}</p>
                                @if($record->evidence)
                                    <a href="{{ asset('uploads/' . $record->evidence) }}" target="_blank"
                                        class="inline-flex items-center gap-1.5 text-[9px] font-bold uppercase tracking-widest text-brand hover:text-brand-dark hover:underline">
                                        <i class="fa-solid fa-paperclip"></i> View Evidence
                                    </a>
                                @else
                                    <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">No Evidence</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 align-top space-y-3">
                                <select
                                    class="w-full text-xs font-bold uppercase tracking-widest bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-600 focus:ring-brand focus:border-brand shadow-sm focus:ring-4 focus:ring-brand/5 transition-all"
                                    id="criteria-{{ $record->record_id }}">
                                    <option value="100" {{ $record->eqa_criteria == 100 ? 'selected' : '' }}>Please Select...
                                    </option>
                                    <option value="1" {{ $record->eqa_criteria == 1 ? 'selected' : '' }}>YES</option>
                                    <option value="0" {{ $record->eqa_criteria == 0 ? 'selected' : '' }}>NO</option>
                                </select>
                                <textarea rows="3"
                                    class="premium-input w-full resize-none text-xs focus:border-brand focus:ring-brand/5"
                                    id="feedback-{{ $record->record_id }}"
                                    placeholder="Enter feedback here...">{{ $record->eqa_feedback }}</textarea>
                            </td>
                            <td class="px-6 py-5 align-top text-right">
                                <button type="button" onclick="saveRecord({{ $record->record_id }})"
                                    class="w-8 h-8 rounded-lg bg-brand text-white flex items-center justify-center hover:bg-brand-dark transition-all shadow-md shadow-brand/20 active:scale-95 group/btn">
                                    <i class="fa-solid fa-check group-hover/btn:scale-110 transition-transform"></i>
                                </button>
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