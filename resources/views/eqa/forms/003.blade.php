@extends('layouts.app')

@section('title', 'Form 003')
@section('subtitle', 'EQA Report on ATP Accreditation')

@section('content')
    <div class="max-w-5xl mx-auto space-y-8 animate-fade-in-up">

        <!-- Header Action Bar -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-brand flex items-center justify-center text-white shadow-lg shadow-brand/20">
                    <i class="fa-solid fa-file-signature text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-premium leading-tight">{{ $atp->atp_name }}</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">EQA Report on ATP Accreditation</p>
                </div>
            </div>
            <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'visit']) }}"
                class="group flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 text-slate-500 hover:bg-brand hover:text-white transition-all duration-300 font-bold text-xs uppercase tracking-tighter shadow-sm">
                <i class="fa-solid fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
                <span>Back to ATP</span>
            </a>
        </div>

        <form action="{{ route('eqa.forms.store', ['form_id' => '003', 'atp_id' => $atp->atp_id]) }}" method="POST" class="space-y-8">
            @csrf

            <!-- Section 1: General Info -->
            <div class="premium-card p-8">
                <h3 class="font-bold text-premium mb-8 uppercase text-xs tracking-[0.2em] border-l-4 border-l-brand pl-4">General Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Date of Visit</label>
                        <div class="relative">
                            <i class="fa-solid fa-calendar-day absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                            <input type="date" name="visit_date" class="premium-input w-full pl-11"
                                value="{{ $formData->visit_date ?? '' }}">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Author of SED</label>
                        <div class="relative">
                            <i class="fa-solid fa-user-edit absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                            <input type="text" name="sed_author" class="premium-input w-full pl-11"
                                value="{{ $formData->sed_author ?? '' }}" placeholder="Enter author name...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Criteria Assessment -->
            @php
                $sections = [
                    ['title' => 'Management & Administrative Systems', 'icon' => 'fa-users-gear'],
                    ['title' => 'Physical & Staff Resources', 'icon' => 'fa-tools'],
                    ['title' => 'Assessment Strategy', 'icon' => 'fa-chess'],
                    ['title' => 'Quality Assurance', 'icon' => 'fa-shield-halved']
                ];
            @endphp

            <div class="space-y-6">
                @foreach($sections as $index => $section)
                    <div class="premium-card overflow-hidden group">
                        <div class="bg-slate-50 border-b border-slate-100 px-8 py-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <h3 class="font-bold text-slate-600 uppercase text-[10px] tracking-widest flex items-center gap-3">
                                <i class="fa-solid {{ $section['icon'] }} text-brand text-lg"></i>
                                {{ $section['title'] }}
                            </h3>
                            <div class="relative w-full md:w-auto">
                                <select name="criteria_{{ $index }}"
                                    class="w-full md:w-48 text-[10px] font-black uppercase tracking-widest bg-white border border-slate-200 rounded-xl px-4 py-2 text-slate-600 focus:ring-2 focus:ring-brand focus:border-brand shadow-sm cursor-pointer transition-all">
                                    <option value="">Select Status</option>
                                    <option value="1">YES</option>
                                    <option value="0">NO</option>
                                    <option value="2">Partially Agree</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-8">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-3">Findings & Feedback</label>
                            <textarea name="feedback_{{ $index }}" rows="4" class="premium-input w-full text-sm resize-none leading-relaxed"
                                placeholder="Enter specific findings and EQA feedback for this area...">{{ $formData->{"feedback_$index"} ?? '' }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Section 3: Recommendation -->
            <div class="premium-card p-10 bg-slate-50 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
                <div class="relative z-10 flex flex-col items-center text-center space-y-8">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-500 text-white flex items-center justify-center shadow-lg shadow-indigo-200 animate-pulse">
                        <i class="fa-solid fa-stamp text-2xl"></i>
                    </div>
                    
                    <div class="max-w-2xl">
                        <h3 class="text-2xl font-bold text-premium mb-4">Final Accreditation Recommendation</h3>
                        <p class="text-sm text-slate-500 mb-8">Based on the comprehensive assessment above, please provide your final verdict on the eligibility of this provider for accreditation.</p>
                        
                        <div class="inline-flex items-center gap-6 p-6 bg-white rounded-2xl border border-slate-200 shadow-sm mb-8 group hover:border-indigo-300 transition-all">
                            <label class="flex items-center gap-4 cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" name="is_recommended" value="1"
                                        class="w-6 h-6 rounded-lg text-indigo-600 focus:ring-indigo-500 border-slate-300 transition-all cursor-pointer"
                                        {{ (isset($formData->is_recommended) && $formData->is_recommended == '1') ? 'checked' : '' }}>
                                </div>
                                <span class="font-bold text-slate-700 select-none">Grant Accreditation to this Training Provider?</span>
                            </label>
                        </div>

                        <div class="w-full max-w-md mx-auto space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">In case of rejection, specify follow-up review date</label>
                            <div class="relative">
                                <i class="fa-solid fa-clock-rotate-left absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                <input type="date" name="review_date" class="premium-input w-full pl-11 bg-white"
                                    value="{{ $formData->review_date ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between bg-white/40 backdrop-blur-md p-6 rounded-2xl border border-white/20 shadow-xl">
                <div class="flex items-center gap-3 text-slate-400">
                    <i class="fa-solid fa-shield-halved text-brand"></i>
                    <span class="text-xs font-bold uppercase tracking-widest">Formal Accreditation Report Submission</span>
                </div>
                <div class="flex items-center gap-4">
                    <button type="reset" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-500 font-bold text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">
                        Clear Report
                    </button>
                    <button type="submit" class="premium-button px-10 py-3.5 text-sm shadow-xl shadow-indigo-300 bg-indigo-600 hover:bg-indigo-700 border-indigo-500">
                        <i class="fa-solid fa-check-double mr-2"></i>
                        Confirm & Save Report
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection
