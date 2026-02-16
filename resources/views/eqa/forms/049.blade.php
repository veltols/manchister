@extends('layouts.app')

@section('title', 'Form 049')
@section('subtitle', 'Teaching and Learning Observation Form')

@section('content')
    <div class="max-w-5xl mx-auto space-y-8 animate-fade-in-up">

        <!-- Header Action Bar -->
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-sm">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-brand flex items-center justify-center text-white shadow-lg shadow-brand/20">
                    <i class="fa-solid fa-chalkboard-user text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-premium leading-tight">{{ $atp->atp_name }}</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">Teaching Observation</p>
                </div>
            </div>
            <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'interim']) }}"
                class="premium-button bg-stone-500 hover:bg-stone-600 shadow-md">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Audit</span>
            </a>
        </div>

        <form action="{{ route('eqa.forms.store', ['form_id' => '049', 'atp_id' => $atp->atp_id]) }}" method="POST"
            class="space-y-8">
            @csrf

            <!-- Instructor Info -->
            <div class="premium-card p-8 bg-white">
                <h3
                    class="font-black text-slate-800 mb-6 uppercase text-xs tracking-[0.2em] border-l-4 border-l-brand pl-4 flex items-center gap-3">
                    <span>Instructor Information</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 flex flex-col">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Instructor
                            Name</label>
                        <div class="relative flex-1">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                            <input type="text" name="instructor_name"
                                class="premium-input w-full h-full pl-11 focus:border-brand focus:ring-brand/5"
                                value="{{ $formData->instructor_name ?? '' }}" placeholder="Name">
                        </div>
                    </div>
                    <div class="space-y-2 flex flex-col">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Course /
                            Module</label>
                        <div class="relative flex-1">
                            <i class="fa-solid fa-book absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                            <input type="text" name="course_module"
                                class="premium-input w-full h-full pl-11 focus:border-brand focus:ring-brand/5"
                                value="{{ $formData->course_module ?? '' }}" placeholder="Course Title">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pre-Observation Details -->
            <div class="premium-card p-8 bg-white">
                <h3
                    class="font-black text-slate-800 mb-6 uppercase text-xs tracking-[0.2em] border-l-4 border-l-brand pl-4 flex items-center gap-3">
                    <span>Pre-Observation Details</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-2 flex flex-col">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Lesson Plan
                            Available?</label>
                        <select name="lesson_plan_available"
                            class="premium-input w-full h-full focus:border-brand focus:ring-brand/5">
                            <option value="">Select</option>
                            <option value="Yes" {{ (isset($formData->lesson_plan_available) && $formData->lesson_plan_available == 'Yes') ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ (isset($formData->lesson_plan_available) && $formData->lesson_plan_available == 'No') ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div class="space-y-2 flex flex-col">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Materials
                            Prepared?</label>
                        <select name="materials_prepared"
                            class="premium-input w-full h-full focus:border-brand focus:ring-brand/5">
                            <option value="">Select</option>
                            <option value="Yes" {{ (isset($formData->materials_prepared) && $formData->materials_prepared == 'Yes') ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ (isset($formData->materials_prepared) && $formData->materials_prepared == 'No') ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Objectives &
                        Goals</label>
                    <textarea name="objectives" rows="3"
                        class="premium-input w-full resize-none focus:border-brand focus:ring-brand/5"
                        placeholder="Lesson objectives...">{{ $formData->objectives ?? '' }}</textarea>
                </div>
            </div>

            <!-- Observation Criteria -->
            @php
                $criteriaTree = [
                    'Planning & Organizing' => [
                        'Lesson is well planned and structured',
                        'Learning outcomes are clear',
                        'Resources are appropriate and used effectively'
                    ],
                    'Teaching & Learning' => [
                        'Instructor demonstrates good subject knowledge',
                        'Teaching methods engage learners',
                        'Pace of lesson is appropriate',
                        'Practical application is demonstrated'
                    ],
                    'Assessment & Feedback' => [
                        'Checks for understanding',
                        'Constructive feedback provided',
                        'Learners are encouraged to ask questions'
                    ]
                ];
            @endphp

            <div class="premium-card p-0 overflow-hidden">
                <div class="p-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-black text-slate-800 uppercase text-xs tracking-[0.2em]">Observation Criteria</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Rating Scale 1-5
                        </p>
                    </div>
                    <span
                        class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-400 text-[10px] font-bold uppercase tracking-widest border border-slate-200">1
                        (Poor) to 5 (Excellent)</span>
                </div>
                <div class="p-8 space-y-8">
                    @foreach($criteriaTree as $domain => $items)
                        <div class="space-y-4">
                            <h4
                                class="font-black text-slate-700 uppercase text-[10px] tracking-widest bg-brand/5 text-brand w-fit px-3 py-1.5 rounded-lg border border-brand/10">
                                {{ $domain }}</h4>
                            @foreach($items as $idx => $item)
                                @php $key = Str::slug($domain . '_' . $idx); @endphp
                                <div
                                    class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start pb-4 border-b border-slate-50 last:border-0 hover:bg-slate-50/50 p-2 rounded-xl transition-colors">
                                    <div class="md:col-span-5 pt-2">
                                        <p class="text-xs font-bold text-slate-600 leading-snug">{{ $item }}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        @php
                                            $ratingKey = 'rating_' . $key;
                                            $savedRating = $formData->$ratingKey ?? '';
                                        @endphp
                                        <select name="rating_{{ $key }}"
                                            class="w-full text-[10px] font-black uppercase tracking-widest bg-white border border-slate-200 rounded-lg px-2 py-2 text-slate-600 focus:ring-brand focus:border-brand shadow-sm focus:ring-4 focus:ring-brand/5 transition-all">
                                            <option value="">Rate</option>
                                            <option value="1" {{ $savedRating == '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ $savedRating == '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ $savedRating == '3' ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ $savedRating == '4' ? 'selected' : '' }}>4</option>
                                            <option value="5" {{ $savedRating == '5' ? 'selected' : '' }}>5</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-5">
                                        <textarea name="comment_{{ $key }}" rows="1"
                                            class="premium-input w-full text-xs resize-none bg-slate-50 focus:bg-white focus:border-brand focus:ring-brand/5"
                                            placeholder="Specific evidence...">{{ $formData->{'comment_' . $key} ?? '' }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>


            <!-- Post-Observation Reflection -->
            <div class="premium-card p-8 bg-white">
                <h3
                    class="font-black text-slate-800 mb-6 uppercase text-xs tracking-[0.2em] border-l-4 border-l-brand pl-4 flex items-center gap-3">
                    <span>Post-Observation Reflection</span>
                </h3>
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Strengths
                            Observed</label>
                        <textarea name="strengths" rows="3"
                            class="premium-input w-full resize-none focus:border-brand focus:ring-brand/5">{{ $formData->strengths ?? '' }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Areas for
                            Improvement</label>
                        <textarea name="improvements" rows="3"
                            class="premium-input w-full resize-none focus:border-brand focus:ring-brand/5">{{ $formData->improvements ?? '' }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Development
                            Suggestions</label>
                        <textarea name="suggestions" rows="3"
                            class="premium-input w-full resize-none focus:border-brand focus:ring-brand/5">{{ $formData->suggestions ?? '' }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Overall
                            Feedback</label>
                        <textarea name="feedback" rows="3"
                            class="premium-input w-full resize-none focus:border-brand focus:ring-brand/5">{{ $formData->feedback ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div
                class="flex items-center justify-between bg-white/40 backdrop-blur-md p-6 rounded-2xl border border-white/20 shadow-xl">
                <div class="flex items-center gap-3 text-slate-400">
                    <i class="fa-solid fa-chalkboard-user text-brand"></i>
                    <span class="text-xs font-bold uppercase tracking-widest">Formal Observation Record</span>
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
@endsection