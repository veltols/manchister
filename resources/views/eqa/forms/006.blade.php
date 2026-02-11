@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="bg-white border-b border-slate-100 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-20 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'planner']) }}" 
                       class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">Module 006</h1>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Internal Remote Activity Feedback Form</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto bg-slate-50/50 p-6">
        <div class="max-w-7xl mx-auto space-y-8">
            
            <!-- General Information (Read Only) -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
                <h3 class="text-lg font-black text-slate-800 uppercase tracking-widest mb-6">General Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Author of SED</label>
                        <input type="text" value="{{ $sed_data->sed_1 ?? '' }}" readonly class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-500 font-bold text-sm focus:ring-0">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Role</label>
                        <input type="text" value="{{ $sed_data->sed_2 ?? '' }}" readonly class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-500 font-bold text-sm focus:ring-0">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Date of Last College Internal Audit</label>
                        <input type="text" value="{{ $sed_data->sed_3 ?? '' }}" readonly class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-500 font-bold text-sm focus:ring-0">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Submitted Date</label>
                        <input type="text" value="{{ $sed_data->submitted_date ?? '' }}" readonly class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-500 font-bold text-sm focus:ring-0">
                    </div>
                </div>
            </div>

            <!-- Evidence Table -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-widest">Desktop Review</h3>
                    <p class="text-xs text-slate-400 mt-1">Prospective ATP Evidence against the ATPQS</p>
                </div>
                
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/30">
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 w-1/4">Standard / Requirement</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 w-1/6">ATP Status</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 w-1/6">Comment/Evidence</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 w-1/3">EQA Response</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 text-right w-20">Action</th>
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
                                <span class="inline-block px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-widest border {{ $status['class'] }}">
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
                                <select class="w-full rounded-lg border-slate-200 text-xs font-bold focus:border-brand focus:ring-brand" 
                                        id="criteria-{{ $record->record_id }}">
                                    <option value="100" {{ $record->eqa_criteria == 100 ? 'selected' : '' }}>Please Select...</option>
                                    <option value="1" {{ $record->eqa_criteria == 1 ? 'selected' : '' }}>YES</option>
                                    <option value="0" {{ $record->eqa_criteria == 0 ? 'selected' : '' }}>NO</option>
                                </select>
                                <textarea rows="3" class="w-full rounded-lg border-slate-200 text-xs focus:border-brand focus:ring-brand" 
                                          id="feedback-{{ $record->record_id }}" placeholder="Enter feedback here...">{{ $record->eqa_feedback }}</textarea>
                            </td>
                            <td class="px-6 py-5 align-top text-right">
                                <button type="button" onclick="saveRecord({{ $record->record_id }})" 
                                        class="w-8 h-8 rounded-lg bg-brand text-white flex items-center justify-center hover:bg-brand-dark transition-all shadow-md shadow-brand/20 active:scale-95">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
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
            if(data.success) {
                // Success feedback
                btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                btn.classList.add('bg-emerald-500');
                setTimeout(() => {
                    btn.classList.remove('bg-emerald-500');
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
