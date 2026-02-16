@extends('layouts.app')

@section('title', 'Form 003')
@section('subtitle', 'EQA Report on ATP Accreditation')

@section('content')
    <div class="max-w-5xl mx-auto space-y-8 animate-fade-in-up">

        <!-- Header Action Bar -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-sm transition-all duration-300 hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-brand flex items-center justify-center text-white shadow-lg shadow-brand/20">
                    <i class="fa-solid fa-file-signature text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 leading-tight">{{ $atp->atp_name }}</h2>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">EQA Report on ATP Accreditation</p>
                </div>
            </div>
            <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'visit']) }}"
                class="premium-button bg-slate-800 hover:bg-slate-900 shadow-xl shadow-slate-900/10">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                <span>Back to Visit</span>
            </a>
        </div>

        @if(session('success'))
            <div class="premium-card p-4 bg-emerald-50 border-emerald-100 flex items-center gap-4 animate-bounce-in">
                <div class="w-10 h-10 rounded-lg bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <i class="fa-solid fa-check text-lg"></i>
                </div>
                <div>
                    <h4 class="text-xs font-black text-emerald-800 uppercase tracking-wider">Success</h4>
                    <p class="text-[11px] font-bold text-emerald-600">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="premium-card p-4 bg-rose-50 border-rose-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-rose-500 text-white flex items-center justify-center shadow-lg shadow-rose-500/20">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-xs font-black text-rose-800 uppercase tracking-wider">Validation Error</h4>
                    <p class="text-[11px] font-bold text-rose-600">Please check the fields and try again.</p>
                </div>
            </div>
        @endif

        <form action="{{ route('eqa.forms.store', ['form_id' => '003', 'atp_id' => $atp->atp_id]) }}" method="POST"
            class="space-y-8">
            @csrf

            <!-- Section 1: General Info -->
            <div class="premium-card p-10 bg-white shadow-xl shadow-slate-200/50 border-slate-100">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-1.5 h-6 bg-brand rounded-full"></div>
                    <h3 class="font-black text-slate-800 uppercase text-[11px] tracking-[0.2em]">General Information</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Date of Visit</label>
                        <div class="relative group">
                            <i class="fa-solid fa-calendar-day absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand transition-colors"></i>
                            <input type="date" name="eqa_visit_date"
                                class="premium-input w-full pl-11 focus:border-brand focus:ring-brand/5 border-2"
                                value="{{ $formData->eqa_visit_date ?? '' }}" required>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Author of SED</label>
                        <div class="relative group">
                            <i class="fa-solid fa-user-edit absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand transition-colors"></i>
                            <input type="text" name="sed_author"
                                class="premium-input w-full pl-11 focus:border-brand focus:ring-brand/5 border-2"
                                value="{{ $formData->sed_author ?? '' }}" placeholder="Enter author name..." required>
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
                    <div class="premium-card overflow-hidden group bg-white shadow-xl shadow-slate-200/50 border-slate-100/50 hover:border-brand/20 transition-all duration-300">
                        <div class="bg-slate-50 border-b border-slate-100 px-10 py-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-brand group-hover:scale-110 transition-transform">
                                    <i class="fa-solid {{ $section['icon'] }} text-lg"></i>
                                </div>
                                <h3 class="font-black text-slate-700 uppercase text-[10px] tracking-widest">
                                    {{ $section['title'] }}
                                </h3>
                            </div>
                            <div class="relative w-full md:w-auto">
                                <select name="criteria_{{ $index }}" required
                                    class="w-full md:w-48 text-[10px] font-black uppercase tracking-widest bg-gray-100 border-2 border-transparent hover:border-slate-200 rounded-xl px-5 py-2.5 text-slate-600 focus:ring-brand focus:border-brand focus:bg-white transition-all cursor-pointer outline-none">
                                    <option value="">Select Status</option>
                                    <option value="1" {{ ($formData->{"criteria_$index"} ?? '') == '1' ? 'selected' : '' }}>YES - Fully Met</option>
                                    <option value="0" {{ ($formData->{"criteria_$index"} ?? '') == '0' ? 'selected' : '' }}>NO - Not Met</option>
                                    <option value="2" {{ ($formData->{"criteria_$index"} ?? '') == '2' ? 'selected' : '' }}>Partially Agree</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-10">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-4">Findings & Feedback</label>
                            <textarea name="feedback_{{ $index }}" rows="4" required
                                class="premium-input w-full text-xs font-bold text-slate-600 border-2 resize-none leading-relaxed focus:border-brand focus:ring-brand/5 placeholder:text-slate-300"
                                placeholder="Enter specific findings and EQA feedback for this area...">{{ $formData->{"feedback_$index"} ?? '' }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Section 3: Recommendation -->
            <div class="premium-card p-12 bg-slate-900 relative overflow-hidden shadow-2xl shadow-slate-900/20 border-0">
                <div class="absolute top-0 right-0 w-96 h-96 bg-brand/10 rounded-full -mr-48 -mt-48 blur-[100px]"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-slate-800/50 rounded-full -ml-32 -mb-32 blur-[80px]"></div>
                
                <div class="relative z-10 flex flex-col items-center text-center space-y-10">
                    <div class="w-20 h-20 rounded-2xl bg-brand text-white flex items-center justify-center shadow-2xl shadow-brand/30 animate-pulse-slow">
                        <i class="fa-solid fa-stamp text-3xl"></i>
                    </div>

                    <div class="max-w-2xl">
                        <h3 class="text-3xl font-black text-white mb-4 tracking-tight">Final Accreditation Recommendation</h3>
                        <p class="text-sm font-bold text-slate-400 mb-10 leading-relaxed">Based on the comprehensive assessment above, please provide your final verdict on the eligibility of this provider for accreditation.</p>

                        <div class="inline-flex items-center gap-10 p-8 bg-white/5 backdrop-blur-xl rounded-3xl border border-white/10 shadow-2xl mb-12 hover:border-brand/40 transition-all group">
                            <label class="flex items-center gap-6 cursor-pointer">
                                <div class="relative flex items-center justify-center">
                                    <input type="checkbox" name="recommendation" value="1"
                                        class="w-8 h-8 rounded-xl text-brand focus:ring-offset-slate-900 focus:ring-brand border-white/20 bg-white/10 transition-all cursor-pointer backdrop-blur-md"
                                        {{ (isset($formData->recommendation) && $formData->recommendation == '1') ? 'checked' : '' }}>
                                </div>
                                <div class="text-left">
                                    <span class="block font-black text-white text-base tracking-tight">Grant Accreditation</span>
                                    <span class="block text-[10px] font-bold text-brand uppercase tracking-widest mt-0.5">Formal Approval Recommendation</span>
                                </div>
                            </label>
                        </div>

                        <div class="w-full max-w-sm mx-auto space-y-4">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest leading-loose">In case of rejection, specify follow-up review date</label>
                            <div class="relative group">
                                <i class="fa-solid fa-clock-rotate-left absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-brand transition-colors"></i>
                                <input type="date" name="accreditation_date"
                                    class="premium-input w-full pl-11 bg-white/5 border-2 border-white/10 text-white focus:border-brand focus:ring-brand/5 focus:bg-white/10 transition-all"
                                    value="{{ $formData->accreditation_date ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between bg-white/60 backdrop-blur-xl p-8 rounded-3xl border border-white/40 shadow-2xl shadow-slate-200/50">
                <div class="flex items-center gap-4 text-slate-400">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-brand">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <span class="block text-[11px] font-black text-slate-800 uppercase tracking-widest">Official Submission</span>
                        <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-[0.1em] mt-0.5">EQA Report Confirmation</span>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <button type="reset"
                        class="px-8 py-4 rounded-2xl bg-slate-100 text-slate-500 font-bold text-[10px] uppercase tracking-[0.2em] hover:bg-slate-200 transition-all active:scale-95">
                        Clear Report
                    </button>
                    <button type="submit" class="premium-button px-12 py-4 text-[11px] font-black uppercase tracking-[0.2em] shadow-2xl shadow-brand/30">
                        <i class="fa-solid fa-cloud-arrow-up mr-3 text-base"></i>
                        Confirm & Save
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection