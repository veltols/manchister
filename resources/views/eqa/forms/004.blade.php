@extends('layouts.app')

@section('title', 'Form 004')
@section('subtitle', 'EQA-ATP Internal Report for Accreditation')

@section('content')
    <div class="max-w-6xl mx-auto space-y-8 animate-fade-in-up">

        <!-- Header Action Bar -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-brand flex items-center justify-center text-white shadow-lg shadow-brand/20">
                    <i class="fa-solid fa-file-contract text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-premium leading-tight">{{ $atp->atp_name }}</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">Internal Report for Accreditation</p>
                </div>
            </div>
            <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'visit']) }}"
                class="premium-button bg-stone-500 hover:bg-stone-600 shadow-md">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Visit</span>
            </a>
        </div>

        <form action="{{ route('eqa.forms.store', ['form_id' => '004', 'atp_id' => $atp->atp_id]) }}" method="POST" class="space-y-8">
            @csrf

            <!-- Section 1: Institutional Context (Read Only) -->
            <div class="premium-card overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-100 px-8 py-4">
                    <h3 class="font-black text-slate-600 uppercase text-[10px] tracking-widest flex items-center gap-2">
                        <i class="fa-solid fa-hotel text-brand"></i>
                        Institutional Overview
                    </h3>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2 flex flex-col">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Institute Name</label>
                        <div class="flex-1 p-3.5 rounded-xl bg-slate-50 border-2 border-slate-100 text-premium font-bold text-sm shadow-inner group transition-all flex items-center">
                            {{ $atp->atp_name }}
                        </div>
                    </div>
                    <div class="space-y-2 flex flex-col">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Contact Name</label>
                        <div class="flex-1 p-3.5 rounded-xl bg-slate-50 border-2 border-slate-100 text-premium font-bold text-sm shadow-inner group transition-all flex items-center">
                            {{ $atp->contact_name }}
                        </div>
                    </div>
                     <div class="space-y-2 flex flex-col">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Date of Visit</label>
                        <div class="relative flex-1">
                             <i class="fa-solid fa-calendar-day absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                             <input type="date" name="visit_date" class="premium-input w-full h-full pl-11 focus:border-brand focus:ring-brand/5 !bg-slate-50 border-2 border-slate-100 shadow-inner"
                                value="{{ $formData->visit_date ?? '' }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Assessment Criteria -->
            <div class="premium-card p-8 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50">
                <h3 class="font-black text-slate-800 mb-8 uppercase text-xs tracking-[0.2em] border-l-4 border-l-brand pl-4 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-brand"></i>
                    Assessment Criteria
                </h3>

                <div class="space-y-8 divide-y divide-slate-100">
                    <!-- Area 1 -->
                    @php 
                        $areas = [
                            'Management & Administrative Systems',
                            'Physical & Staff Resources',
                            'Assessment Strategy',
                            'Quality Assurance'
                        ];
                    @endphp

                    @foreach($areas as $index => $area)
                    <div class="pt-8 first:pt-0 grid grid-cols-1 lg:grid-cols-12 gap-6">
                        <div class="lg:col-span-4">
                            <h4 class="font-bold text-slate-700 text-sm mb-2">{{ $area }}</h4>
                            <p class="text-xs text-slate-400 leading-relaxed">Evaluate the institute's compliance and effectiveness in this area.</p>
                            
                            <div class="mt-4">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Compliance Status</label>
                                <select name="eqa_criterias[{{ $index }}]" class="premium-input w-full focus:border-brand focus:ring-brand/5">
                                    <option value="100">Please Select</option>
                                    <option value="1" {{ (isset($formData->eqa_criterias[$index]) && $formData->eqa_criterias[$index] == '1') ? 'selected' : '' }}>YES</option>
                                    <option value="0" {{ (isset($formData->eqa_criterias[$index]) && $formData->eqa_criterias[$index] == '0') ? 'selected' : '' }}>NO</option>
                                    <option value="2" {{ (isset($formData->eqa_criterias[$index]) && $formData->eqa_criterias[$index] == '2') ? 'selected' : '' }}>Partially agree</option>
                                </select>
                            </div>
                        </div>
                        <div class="lg:col-span-8">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Feedback & Observations</label>
                            <textarea name="eqa_feedbacks[{{ $index }}]" rows="5" class="premium-input w-full resize-none leading-relaxed focus:border-brand focus:ring-brand/5"
                                placeholder="Enter detailed feedback here...">{{ $formData->eqa_feedbacks[$index] ?? '' }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 3: Final Recommendation -->
            <div class="premium-card p-8 bg-slate-50/50 shadow-lg shadow-slate-200/50 border-slate-100/50">
                <h3 class="font-black text-slate-800 mb-8 uppercase text-xs tracking-[0.2em] border-l-4 border-l-brand pl-4 flex items-center gap-2">
                    <i class="fa-solid fa-gavel text-brand"></i>
                    Final Recommendation
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <label class="block text-sm font-bold text-slate-700">Recommendation for accreditation to be granted?</label>
                        <select name="accreditation_recommendation" class="premium-input w-full focus:border-brand focus:ring-brand/5">
                            <option value="100">Please Select</option>
                            <option value="1" {{ (isset($formData->accreditation_recommendation) && $formData->accreditation_recommendation == '1') ? 'selected' : '' }}>YES</option>
                            <option value="0" {{ (isset($formData->accreditation_recommendation) && $formData->accreditation_recommendation == '0') ? 'selected' : '' }}>NO</option>
                            <option value="2" {{ (isset($formData->accreditation_recommendation) && $formData->accreditation_recommendation == '2') ? 'selected' : '' }}>Partially agree</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-200 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">If NOT recommended, indicate report sections</label>
                        <input type="text" name="not_recommended_sections" class="premium-input w-full focus:border-brand focus:ring-brand/5" 
                            placeholder="e.g. Sections 2.1, 4.0" value="{{ $formData->not_recommended_sections ?? '' }}">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Proposed Review Date</label>
                        <input type="date" name="review_date" class="premium-input w-full focus:border-brand focus:ring-brand/5" 
                            value="{{ $formData->review_date ?? '' }}">
                    </div>
                </div>
            </div>

            <!-- Section 4: Action Plan -->
            <div class="premium-card p-8 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50">
                <h3 class="font-black text-slate-800 mb-8 uppercase text-xs tracking-[0.2em] border-l-4 border-l-brand pl-4 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-brand"></i>
                    Action Plan
                </h3>
                <div class="space-y-6">
                    @foreach($areas as $index => $area)
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 pb-6 border-b border-slate-50 last:border-0 last:pb-0">
                        <div class="md:col-span-4">
                            <h4 class="font-bold text-slate-700 text-sm mb-1">{{ $area }}</h4>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">Target Date</label>
                            <input type="date" name="action_date_{{ $index }}" class="premium-input w-full focus:border-brand focus:ring-brand/5" 
                                value="{{ $formData->{'action_date_'.$index} ?? '' }}">
                        </div>
                        <div class="md:col-span-8">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Required Actions</label>
                            <textarea name="action_plan_{{ $index }}" rows="3" class="premium-input w-full resize-none leading-relaxed focus:border-brand focus:ring-brand/5"
                                placeholder="Specify actions required...">{{ $formData->{'action_plan_'.$index} ?? '' }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 5: Staff Interview Log -->
            <div class="premium-card p-8 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50">
                <h3 class="font-black text-slate-800 mb-6 uppercase text-xs tracking-[0.2em] border-l-4 border-l-brand pl-4 flex items-center gap-2">
                    <i class="fa-solid fa-users text-brand"></i>
                    Staff Interviews
                </h3>
                
                <div id="staff-container" class="space-y-4">
                    @php
                        // Try to load existing staff data if available, otherwise default to 1 empty row
                        $staffNames = isset($formData->staff_names) ? json_decode($formData->staff_names, true) : [''];
                        $staffRoles = isset($formData->staff_roles) ? json_decode($formData->staff_roles, true) : [''];
                    @endphp

                    @foreach($staffNames as $i => $name)
                    <div class="flex flex-col md:flex-row gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100 group hover:border-brand/30 transition-all">
                        <div class="flex-1 space-y-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Staff Name</label>
                            <input type="text" name="staff_names[]" value="{{ $name }}" class="premium-input w-full bg-white focus:border-brand focus:ring-brand/5" placeholder="Name">
                        </div>
                        <div class="flex-1 space-y-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Role / Position</label>
                            <input type="text" name="staff_roles[]" value="{{ $staffRoles[$i] ?? '' }}" class="premium-input w-full bg-white focus:border-brand focus:ring-brand/5" placeholder="Role">
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    <button type="button" onclick="addStaffRow()" class="text-[10px] font-bold uppercase tracking-widest text-brand hover:underline flex items-center gap-2">
                        <i class="fa-solid fa-plus bg-brand/10 p-1 rounded-md"></i> Add Another Staff Member
                    </button>
                </div>
            </div>

            <script>
                function addStaffRow() {
                    const container = document.getElementById('staff-container');
                    const row = document.createElement('div');
                    row.className = 'flex flex-col md:flex-row gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100 group hover:border-brand/30 transition-all animate-fade-in-up';
                    row.innerHTML = `
                        <div class="flex-1 space-y-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Staff Name</label>
                            <input type="text" name="staff_names[]" class="premium-input w-full bg-white focus:border-brand focus:ring-brand/5" placeholder="Name">
                        </div>
                        <div class="flex-1 space-y-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Role / Position</label>
                            <input type="text" name="staff_roles[]" class="premium-input w-full bg-white focus:border-brand focus:ring-brand/5" placeholder="Role">
                        </div>
                    `;
                    container.appendChild(row);
                }
            </script>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between bg-white/40 backdrop-blur-md p-6 rounded-2xl border border-white/20 shadow-xl">
                <div class="flex items-center gap-3 text-slate-400">
                    <i class="fa-solid fa-circle-info text-brand"></i>
                    <span class="text-xs font-bold uppercase tracking-widest">All feedback fields are required</span>
                </div>
                <div class="flex items-center gap-4">
                    <button type="reset" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-500 font-bold text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">
                        Reset
                    </button>
                    <button type="submit" class="premium-button px-10 py-3.5 text-sm shadow-xl shadow-brand/30">
                        <i class="fa-solid fa-save mr-2"></i>
                        Save Report
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection
