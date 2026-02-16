@extends('layouts.app')

@section('title', 'Form 008')
@section('subtitle', 'IQC External Quality Assurance Visit Planner')

@section('content')
    <div class="max-w-6xl mx-auto space-y-8 animate-fade-in-up">

        <!-- Header Action Bar -->
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-sm">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-brand flex items-center justify-center text-white shadow-lg shadow-brand/20">
                    <i class="fa-solid fa-calendar-check text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-premium leading-tight">{{ $atp->atp_name }}</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">EQA Visit Planner</p>
                </div>
            </div>
            <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'planner']) }}"
                class="premium-button bg-stone-500 hover:bg-stone-600 shadow-md">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Planner</span>
            </a>
        </div>

        <form action="{{ route('eqa.forms.store', ['form_id' => '008', 'atp_id' => $atp->atp_id]) }}" method="POST"
            class="space-y-8">
            @csrf

            <!-- Section 1: Institutional Context (Read Only) -->
            <div class="premium-card overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-100 px-8 py-4">
                    <h3 class="font-black text-slate-600 uppercase text-[10px] tracking-widest flex items-center gap-2">
                        <span>Institutional Context</span>
                    </h3>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Institute
                            Name</label>
                        <div
                            class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-premium font-bold text-sm shadow-inner group transition-all">
                            <i
                                class="fa-solid fa-building-columns text-brand/30 mr-2 group-hover:text-brand transition-colors"></i>
                            {{ $atp->atp_name }}
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Author of
                            SED</label>
                        <div
                            class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-premium font-bold text-sm shadow-inner group transition-all">
                            <i class="fa-solid fa-user-pen text-brand/30 mr-2 group-hover:text-brand transition-colors"></i>
                            {{ $sed_data->sed_1 ?? 'Legacy Data (N/A)' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Visit Details -->
            <div
                class="premium-card p-8 bg-white relative overflow-hidden shadow-lg shadow-slate-200/50 border-slate-100/50">
                <h3
                    class="font-black text-slate-800 mb-8 uppercase text-xs tracking-[0.2em] border-l-4 border-l-brand pl-4">
                    Visit Logistics</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Date of
                            Visit</label>
                        <div class="relative">
                            <i class="fa-solid fa-calendar-day absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                            <input type="date" name="eqa_visit_date"
                                class="premium-input w-full pl-11 focus:border-brand focus:ring-brand/5"
                                value="{{ $formData->eqa_visit_date ?? '' }}" required>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Visit
                            Type</label>
                        <div class="relative">
                            <i class="fa-solid fa-tag absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                            <select name="visit_type"
                                class="premium-input w-full pl-11 focus:border-brand focus:ring-brand/5">
                                <option value="">Please Select</option>
                                <option value="1" {{ (isset($formData->visit_type) && $formData->visit_type == '1') ? 'selected' : '' }}>Accreditation/Reaccreditation</option>
                                <option value="11" {{ (isset($formData->visit_type) && $formData->visit_type == '11') ? 'selected' : '' }}>Accreditation/Reaccreditation Approval</option>
                                <option value="111" {{ (isset($formData->visit_type) && $formData->visit_type == '111') ? 'selected' : '' }}>Qualification External Verification (QEV)</option>
                                <option value="1111" {{ (isset($formData->visit_type) && $formData->visit_type == '1111') ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Activity
                            Mode</label>
                        <div class="relative">
                            <i class="fa-solid fa-bolt absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                            <select name="activity"
                                class="premium-input w-full pl-11 focus:border-brand focus:ring-brand/5">
                                <option value="">Please Select</option>
                                <option value="1" {{ (isset($formData->activity) && $formData->activity == '1') ? 'selected' : '' }}>Site Visit</option>
                                <option value="11" {{ (isset($formData->activity) && $formData->activity == '11') ? 'selected' : '' }}>Remote</option>
                            </select>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Length
                            (Hours)</label>
                        <div class="relative">
                            <i class="fa-solid fa-clock absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                            <select name="visit_length"
                                class="premium-input w-full pl-11 focus:border-brand focus:ring-brand/5">
                                <option value="">Please Select</option>
                                @for ($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}" {{ (isset($formData->visit_length) && $formData->visit_length == $i) ? 'selected' : '' }}>{{ $i }} Hour(s)</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Visit Plan Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <div class="premium-card p-8 bg-white shadow-lg shadow-slate-200/50 border-slate-100/50">
                        <h3
                            class="font-black text-slate-800 mb-6 uppercase text-xs tracking-[0.2em] flex items-center gap-2">
                            <span>Scope & Agenda</span>
                        </h3>
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Scope
                                    of Visit</label>
                                <textarea name="visit_scope" rows="5"
                                    class="premium-input w-full resize-none leading-relaxed focus:border-brand focus:ring-brand/5"
                                    placeholder="Define the primary objectives and scope...">{{ $formData->visit_scope ?? '' }}</textarea>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Detailed
                                    Agenda</label>
                                <textarea name="visit_agenda" rows="8"
                                    class="premium-input w-full resize-none leading-relaxed focus:border-brand focus:ring-brand/5"
                                    placeholder="Step-by-step visit timeline...">{{ $formData->visit_agenda ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="premium-card p-8 bg-slate-50/50 shadow-lg shadow-slate-200/50 border-slate-100/50">
                        <h3
                            class="font-black text-slate-800 mb-6 uppercase text-xs tracking-widest flex items-center gap-2">
                            <span>Observations</span>
                        </h3>
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Additional
                                Notes</label>
                            <textarea name="visit_comment" rows="10"
                                class="premium-input w-full bg-white resize-none focus:border-brand focus:ring-brand/5"
                                placeholder="Internal comments or specific focus areas...">{{ $formData->visit_comment ?? '' }}</textarea>
                        </div>
                    </div>

                    <!-- Read Only Quick Stats -->
                    <div class="premium-card p-6 bg-brand text-white shadow-xl shadow-brand/20 border-brand">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest opacity-80">Institutional Data</h4>
                            <i class="fa-solid fa-database opacity-50"></i>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-sm">
                                <span class="opacity-70 font-medium">Qualifications</span>
                                <span class="font-bold">{{ count($qualifications) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="opacity-70 font-medium">Faculty Members</span>
                                <span class="font-bold">{{ count($faculties) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Read Only Info (Dynamic Grids) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Qualifications -->
                <div class="premium-card overflow-hidden">
                    <div class="bg-slate-50 border-b border-slate-100 px-8 py-5 flex items-center justify-between">
                        <h3 class="font-bold text-slate-600 uppercase text-[10px] tracking-widest">Qualifications Offered
                        </h3>
                        <span
                            class="px-2 py-0.5 rounded bg-brand/10 text-brand text-[10px] font-bold">{{ count($qualifications) }}</span>
                    </div>
                    <div class="p-6 max-h-[400px] overflow-y-auto custom-scrollbar">
                        @forelse($qualifications as $q)
                            <div
                                class="mb-4 last:mb-0 p-4 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-brand group-hover:text-white transition-all shadow-inner">
                                        <i class="fa-solid fa-graduation-cap"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-premium text-sm">{{ $q->qualification_name }}</div>
                                        <div class="text-[10px] text-slate-400 mt-1 uppercase tracking-wider font-bold">
                                            {{ $q->qualification_provider }} • {{ $q->qualification_type }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 text-slate-400 italic text-sm">No qualifications listed.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Faculty Members -->
                <div class="premium-card overflow-hidden">
                    <div class="bg-slate-50 border-b border-slate-100 px-8 py-5 flex items-center justify-between">
                        <h3 class="font-bold text-slate-600 uppercase text-[10px] tracking-widest">Faculty Details</h3>
                        <span
                            class="px-2 py-0.5 rounded bg-amber-100 text-amber-600 text-[10px] font-bold">{{ count($faculties) }}</span>
                    </div>
                    <div class="p-6 max-h-[400px] overflow-y-auto custom-scrollbar">
                        @forelse($faculties as $f)
                            <div
                                class="mb-4 last:mb-0 p-4 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-brand group-hover:text-white transition-all shadow-inner">
                                            <i class="fa-solid fa-user-tie"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-premium text-sm">{{ $f->faculty_name }}</div>
                                            <div class="text-[10px] text-slate-400 mt-0.5 font-bold uppercase">
                                                {{ $f->faculty_spec }}</div>
                                        </div>
                                    </div>
                                    <span
                                        class="px-3 py-1 rounded-lg bg-slate-50 border border-slate-100 text-[10px] font-black text-slate-500 uppercase tracking-tighter">
                                        {{ ['1' => 'IQA', '2' => 'IQA Lead', '3' => 'Assessor', '4' => 'Trainer', '5' => 'Teacher'][$f->faculty_type] ?? 'Staff' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 text-slate-400 italic text-sm">No faculty details found.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div
                class="flex items-center justify-between bg-white/40 backdrop-blur-md p-6 rounded-2xl border border-white/20 shadow-xl">
                <div class="flex items-center gap-3 text-slate-400">
                    <i class="fa-solid fa-circle-info text-brand"></i>
                    <span class="text-xs font-bold uppercase tracking-widest">Fields with * are mandatory</span>
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" class="premium-button px-10 py-3.5 text-sm shadow-xl shadow-brand/30">
                        <i class="fa-solid fa-save mr-2"></i>
                        Save Planner
                    </button>
                </div>
            </div>

        </form>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
@endsection