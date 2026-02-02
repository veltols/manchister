@extends('layouts.app')

@section('title', 'Form 003')
@section('subtitle', 'EQA Report on ATP Accreditation')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">

        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-lg">For: {{ $atp->atp_name }}</h2>
            <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'planner']) }}"
                class="text-sm font-bold text-slate-500 hover:text-slate-800"><i class="fa-solid fa-arrow-left"></i> Back to
                ATP</a>
        </div>

        <form action="{{ route('eqa.forms.store', ['form_id' => '003', 'atp_id' => $atp->atp_id]) }}" method="POST">
            @csrf

            <!-- Section 1: General Info -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6">
                <h3 class="font-bold text-premium mb-4 uppercase text-xs tracking-widest border-b pb-2">General
                    Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Date of Visit</label>
                        <input type="date" name="visit_date" class="premium-input w-full"
                            value="{{ $formData->visit_date ?? '' }}">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Author of SED</label>
                        <input type="text" name="sed_author" class="premium-input w-full"
                            value="{{ $formData->sed_author ?? '' }}">
                    </div>
                </div>
            </div>

            <!-- Section 2: Criteria Assessment -->
            @php
                $sections = [
                    'Management & Administrative Systems',
                    'Physical & Staff Resources',
                    'Assessment Strategy',
                    'Quality Assurance'
                ];
            @endphp

            @foreach($sections as $index => $section)
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="font-bold text-premium uppercase text-xs tracking-widest">{{ $section }}</h3>
                        <select name="criteria_{{ $index }}"
                            class="text-xs border-none bg-slate-100 rounded-lg px-2 py-1 font-bold text-slate-600 focus:ring-0">
                            <option value="">Select Status</option>
                            <option value="1">YES</option>
                            <option value="0">NO</option>
                            <option value="2">Partially Agree</option>
                        </select>
                    </div>

                    <textarea name="feedback_{{ $index }}" rows="4" class="premium-input w-full text-sm"
                        placeholder="Enter findings and feedback here..."></textarea>
                </div>
            @endforeach

            <!-- Section 3: Recommendation -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6 border-l-4 border-l-indigo-500">
                <h3 class="font-bold text-premium mb-4 uppercase text-xs tracking-widest">Final Recommendation</h3>

                <div class="flex items-center gap-4 mb-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_recommended" value="1"
                            class="rounded text-indigo-600 focus:ring-indigo-500 w-5 h-5">
                        <span class="font-bold text-slate-700">Accreditation Recommended to be Granted?</span>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">If NOT, indicate review date:</label>
                    <input type="date" name="review_date" class="premium-input w-full max-w-xs">
                </div>
            </div>

            <div class="flex justify-end pt-6">
                <button type="submit" class="premium-button px-8 py-3 text-lg">Save Report</button>
            </div>

        </form>
    </div>
@endsection
