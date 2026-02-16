@extends('layouts.app')

@section('title', 'Form 028')
@section('subtitle', 'EQA Live Assessment Observation Checklist')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up">

        <!-- Header Action Bar -->
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-sm">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-brand flex items-center justify-center text-white shadow-lg shadow-brand/20">
                    <i class="fa-solid fa-video text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-premium leading-tight">{{ $atp->atp_name }}</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">Live Assessment Observation
                    </p>
                </div>
            </div>
            <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'interim']) }}"
                class="premium-button bg-stone-500 hover:bg-stone-600 shadow-md">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Audit</span>
            </a>
        </div>

        <form action="{{ route('eqa.forms.store', ['form_id' => '028', 'atp_id' => $atp->atp_id]) }}" method="POST"
            class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Assessors Table -->
                <div class="premium-card p-6 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50">
                    <h3 class="font-black text-slate-800 mb-4 uppercase text-xs tracking-[0.1em] flex items-center gap-2">
                        <i class="fa-solid fa-user-tie text-brand"></i> Assessors Observed
                    </h3>
                    <div id="assessors-container" class="space-y-3">
                        @php
                            $assessors = isset($formData->assessors) ? json_decode($formData->assessors, true) : (isset($formData->assessors) ? [] : ['']);
                            if (empty($assessors))
                                $assessors = [''];
                        @endphp
                        @foreach($assessors as $assessor)
                            <div class="assesssor-row flex gap-2 group">
                                <input type="text" name="assessors[]" value="{{ $assessor }}"
                                    class="premium-input w-full focus:border-brand focus:ring-brand/5"
                                    placeholder="Assessor Name">
                                <button type="button" onclick="this.parentElement.remove()"
                                    class="text-slate-300 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100 p-2"><i
                                        class="fa-solid fa-trash-can"></i></button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addAssessor()"
                        class="mt-4 text-[10px] font-bold uppercase tracking-widest text-brand hover:underline flex items-center gap-2">
                        <i class="fa-solid fa-plus bg-brand/10 p-1 rounded-md"></i> Add Assessor
                    </button>
                </div>

                <!-- Learners Table -->
                <div class="premium-card p-6 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50">
                    <h3 class="font-black text-slate-800 mb-4 uppercase text-xs tracking-[0.1em] flex items-center gap-2">
                        <i class="fa-solid fa-graduation-cap text-brand"></i> Learners Observed
                    </h3>
                    <div id="learners-container" class="space-y-3">
                        @php
                            $learners = isset($formData->learners) ? json_decode($formData->learners, true) : [['name' => '', 'cohort' => '']];
                            if (empty($learners))
                                $learners = [['name' => '', 'cohort' => '']];
                        @endphp
                        @foreach($learners as $learner)
                            <div class="learner-row flex gap-2 group">
                                <input type="text" name="learner_names[]" value="{{ $learner['name'] ?? $learner }}"
                                    class="premium-input w-full focus:border-brand focus:ring-brand/5"
                                    placeholder="Learner ID/Name">
                                <input type="text" name="learner_cohorts[]" value="{{ $learner['cohort'] ?? '' }}"
                                    class="premium-input w-1/3 focus:border-brand focus:ring-brand/5" placeholder="Cohort">
                                <button type="button" onclick="this.parentElement.remove()"
                                    class="text-slate-300 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100 p-2"><i
                                        class="fa-solid fa-trash-can"></i></button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addLearner()"
                        class="mt-4 text-[10px] font-bold uppercase tracking-widest text-brand hover:underline flex items-center gap-2">
                        <i class="fa-solid fa-plus bg-brand/10 p-1 rounded-md"></i> Add Learner
                    </button>
                </div>
            </div>

            <!-- Observation Criteria -->
            @php
                $criteria = [
                    'Preparation' => [
                        'Environment is safe and suitable for assessment',
                        'Assessment materials and resources are available',
                        'Learner identity is verified'
                    ],
                    'Assessment Process' => [
                        'Assessment instructions are clear and understood',
                        'Assessor uses appropriate assessment methods',
                        'Fairness and validity are maintained',
                        'Sufficient evidence is gathered'
                    ],
                    'Feedback' => [
                        'Feedback is constructive and timely',
                        'Learner is given opportunity to ask questions',
                        'Action planning is agreed'
                    ]
                ];
            @endphp

            <div class="premium-card p-0 overflow-hidden bg-white shadow-lg shadow-slate-200/50 border-slate-100/50">
                <div class="p-6 bg-slate-50 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-brand text-white flex items-center justify-center">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-800 uppercase text-xs tracking-[0.2em]">Observation Criteria</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Checklist &
                            Comments</p>
                    </div>
                </div>
                <div class="p-8 space-y-8">
                    @foreach($criteria as $category => $items)
                        <div class="space-y-4">
                            <h4
                                class="font-black text-slate-700 uppercase text-[10px] tracking-widest bg-slate-100 w-fit px-3 py-1.5 rounded-lg border border-slate-200">
                                {{ $category }}</h4>
                            @foreach($items as $idx => $item)
                                @php $key = Str::slug($category . '_' . $idx); @endphp
                                <div
                                    class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start pb-4 border-b border-slate-50 last:border-0 hover:bg-slate-50/50 p-2 rounded-xl transition-colors">
                                    <div class="md:col-span-5 pt-2">
                                        <p class="text-xs font-bold text-slate-600 leading-snug">{{ $item }}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <select name="criteria_status_{{ $key }}"
                                            class="w-full text-[10px] font-black uppercase tracking-widest bg-white border border-slate-200 rounded-lg px-2 py-2 text-slate-600 focus:ring-brand focus:border-brand shadow-sm transition-all focus:ring-4 focus:ring-brand/5">
                                            <option value="">Select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                            <option value="N/A">N/A</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-5">
                                        <textarea name="criteria_comment_{{ $key }}" rows="1"
                                            class="premium-input w-full text-xs resize-none focus:border-brand focus:ring-brand/5"
                                            placeholder="Comments...">{{ $formData->{'criteria_comment_' . $key} ?? '' }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recommendations & Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Recommendations -->
                <div class="premium-card p-6 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50">
                    <h3 class="font-black text-slate-800 mb-4 uppercase text-xs tracking-[0.1em] flex items-center gap-2">
                        <i class="fa-solid fa-lightbulb text-brand"></i> Recommendations
                    </h3>
                    <div id="rec-container" class="space-y-3">
                        @php
                            $recommendations = isset($formData->recommendations) ? json_decode($formData->recommendations, true) : (isset($formData->recommendations) ? [] : ['']);
                            if (empty($recommendations))
                                $recommendations = [''];
                        @endphp
                        @foreach($recommendations as $rec)
                            <div class="rec-row flex gap-2 group">
                                <textarea name="recommendations[]" rows="2"
                                    class="premium-input w-full resize-none focus:border-brand focus:ring-brand/5"
                                    placeholder="Recommendation">{{ $rec }}</textarea>
                                <button type="button" onclick="this.parentElement.remove()"
                                    class="text-slate-300 hover:text-red-500 transition-colors self-start mt-2 opacity-0 group-hover:opacity-100 p-2"><i
                                        class="fa-solid fa-trash-can"></i></button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addRec()"
                        class="mt-4 text-[10px] font-bold uppercase tracking-widest text-brand hover:underline flex items-center gap-2">
                        <i class="fa-solid fa-plus bg-brand/10 p-1 rounded-md"></i> Add Recommendation
                    </button>
                </div>

                <!-- Actions -->
                <div class="premium-card p-6 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50">
                    <h3 class="font-black text-slate-800 mb-4 uppercase text-xs tracking-[0.1em] flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-brand"></i> Agreed Actions
                    </h3>
                    <div id="action-container" class="space-y-3">
                        @php
                            $actions = isset($formData->actions) ? json_decode($formData->actions, true) : [['action' => '', 'date' => '']];
                            if (empty($actions))
                                $actions = [['action' => '', 'date' => '']];
                        @endphp
                        @foreach($actions as $action)
                            <div
                                class="action-row flex flex-col gap-2 p-3 border border-slate-100 rounded-xl relative group bg-slate-50/30 hover:bg-slate-50 transition-colors">
                                <button type="button" onclick="this.parentElement.remove()"
                                    class="absolute top-2 right-2 text-slate-300 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100"><i
                                        class="fa-solid fa-trash-can"></i></button>
                                <textarea name="action_texts[]" rows="2"
                                    class="premium-input w-full resize-none focus:border-brand focus:ring-brand/5 bg-transparent"
                                    placeholder="Action">{{ $action['action'] ?? $action }}</textarea>
                                <input type="date" name="action_dates[]" value="{{ $action['date'] ?? '' }}"
                                    class="premium-input w-full focus:border-brand focus:ring-brand/5">
                            </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addAction()"
                        class="mt-4 text-[10px] font-bold uppercase tracking-widest text-brand hover:underline flex items-center gap-2">
                        <i class="fa-solid fa-plus bg-brand/10 p-1 rounded-md"></i> Add Action
                    </button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div
                class="flex items-center justify-between bg-white/40 backdrop-blur-md p-6 rounded-2xl border border-white/20 shadow-xl">
                <div class="flex items-center gap-3 text-slate-400">
                    <i class="fa-solid fa-video text-brand"></i>
                    <span class="text-xs font-bold uppercase tracking-widest">Live Assessment Record</span>
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" class="premium-button px-10 py-3.5 text-sm shadow-xl shadow-brand/30">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        Save Observation
                    </button>
                </div>
            </div>

        </form>
    </div>

    <script>
        function addAssessor() {
            const container = document.getElementById('assessors-container');
            const div = document.createElement('div');
            div.className = 'assesssor-row flex gap-2 animate-fade-in-up group';
            div.innerHTML = `<input type="text" name="assessors[]" class="premium-input w-full focus:border-brand focus:ring-brand/5" placeholder="Assessor Name">
                                 <button type="button" onclick="this.parentElement.remove()" class="text-slate-300 hover:text-red-500 transition-colors p-2"><i class="fa-solid fa-trash-can"></i></button>`;
            container.appendChild(div);
        }

        function addLearner() {
            const container = document.getElementById('learners-container');
            const div = document.createElement('div');
            div.className = 'learner-row flex gap-2 animate-fade-in-up group';
            div.innerHTML = `<input type="text" name="learner_names[]" class="premium-input w-full focus:border-brand focus:ring-brand/5" placeholder="Learner ID/Name">
                                 <input type="text" name="learner_cohorts[]" class="premium-input w-1/3 focus:border-brand focus:ring-brand/5" placeholder="Cohort">
                                 <button type="button" onclick="this.parentElement.remove()" class="text-slate-300 hover:text-red-500 transition-colors p-2"><i class="fa-solid fa-trash-can"></i></button>`;
            container.appendChild(div);
        }

        function addRec() {
            const container = document.getElementById('rec-container');
            const div = document.createElement('div');
            div.className = 'rec-row flex gap-2 animate-fade-in-up group';
            div.innerHTML = `<textarea name="recommendations[]" rows="2" class="premium-input w-full resize-none focus:border-brand focus:ring-brand/5" placeholder="Recommendation"></textarea>
                                 <button type="button" onclick="this.parentElement.remove()" class="text-slate-300 hover:text-red-500 transition-colors self-start mt-2 p-2"><i class="fa-solid fa-trash-can"></i></button>`;
            container.appendChild(div);
        }

        function addAction() {
            const container = document.getElementById('action-container');
            const div = document.createElement('div');
            div.className = 'action-row flex flex-col gap-2 p-3 border border-slate-100 rounded-xl relative group animate-fade-in-up bg-slate-50/30 hover:bg-slate-50 transition-colors';
            div.innerHTML = `<button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-slate-300 hover:text-red-500 transition-colors"><i class="fa-solid fa-trash-can"></i></button>
                                 <textarea name="action_texts[]" rows="2" class="premium-input w-full resize-none focus:border-brand focus:ring-brand/5 bg-transparent" placeholder="Action"></textarea>
                                 <input type="date" name="action_dates[]" class="premium-input w-full focus:border-brand focus:ring-brand/5">`;
            container.appendChild(div);
        }
    </script>
@endsection