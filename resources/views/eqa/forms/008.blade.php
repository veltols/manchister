@extends('layouts.app')

@section('title', 'Form 008')
@section('subtitle', 'IQC External Quality Assurance Visit Planner')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6 animate-fade-in-up">

        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-lg">For: {{ $atp->atp_name }}</h2>
            <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'planner']) }}"
                class="text-sm font-bold text-slate-500 hover:text-slate-800"><i class="fa-solid fa-arrow-left"></i> Back to
                ATP</a>
        </div>

        <form action="{{ route('eqa.forms.store', ['form_id' => '008', 'atp_id' => $atp->atp_id]) }}" method="POST">
            @csrf

            <!-- Visit Details -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6">
                <h3 class="font-bold text-premium mb-6 uppercase text-xs tracking-widest border-b pb-2">Visit Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Date of Visit</label>
                        <input type="date" name="visit_date" class="premium-input w-full"
                            value="{{ $formData->visit_date ?? '' }}" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Visit Type</label>
                        <select name="visit_type" class="premium-input w-full">
                            <option value="">Please Select</option>
                            <option value="1">Accreditation/Reaccreditation</option>
                            <option value="11">Accreditation/Reaccreditation Approval</option>
                            <option value="111">Qualification External Verification (QEV)</option>
                            <option value="1111">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Activity Mode</label>
                        <select name="activity" class="premium-input w-full">
                            <option value="">Please Select</option>
                            <option value="1">Site Visit</option>
                            <option value="11">Remote</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Visit Length (Hours)</label>
                        <select name="visit_length" class="premium-input w-full">
                            <option value="">Please Select</option>
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}">{{ $i }} Hour(s)</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <!-- Visit Plan -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6">
                <h3 class="font-bold text-premium mb-6 uppercase text-xs tracking-widest border-b pb-2">Visit Plan</h3>

                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2">Scope of Visit</label>
                        <textarea name="visit_scope" rows="4" class="premium-input w-full"
                            placeholder="Define the scope...">{{ $formData->visit_scope ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2">Agenda</label>
                        <textarea name="visit_agenda" rows="6" class="premium-input w-full"
                            placeholder="Outline the agenda...">{{ $formData->visit_agenda ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2">Additional Details / Comments</label>
                        <textarea name="visit_comment" rows="3"
                            class="premium-input w-full">{{ $formData->visit_comment ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Read Only Info: Qualifications & Faculty -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Qualifications -->
                <div class="bg-slate-50 rounded-xl border border-slate-200 p-6 opacity-75">
                    <h3 class="font-bold text-slate-600 mb-4 uppercase text-xs tracking-widest">Qualifications Offered</h3>
                    @if(isset($qualifications) && count($qualifications) > 0)
                        <ul class="space-y-3">
                            @foreach($qualifications as $q)
                                <li class="bg-white p-3 rounded-lg border border-slate-200 shadow-sm">
                                    <div class="font-bold text-sm text-slate-800">{{ $q->qualification_name }}</div>
                                    <div class="text-xs text-slate-500">{{ $q->qualification_provider }} -
                                        {{ $q->qualification_type }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-slate-400 italic">No qualifications listed.</p>
                    @endif
                </div>

                <!-- Faculty -->
                <div class="bg-slate-50 rounded-xl border border-slate-200 p-6 opacity-75">
                    <h3 class="font-bold text-slate-600 mb-4 uppercase text-xs tracking-widest">Faculty Details</h3>
                    @if(isset($faculties) && count($faculties) > 0)
                        <ul class="space-y-3">
                            @foreach($faculties as $f)
                                <li
                                    class="bg-white p-3 rounded-lg border border-slate-200 shadow-sm flex justify-between items-center">
                                    <div>
                                        <div class="font-bold text-sm text-slate-800">{{ $f->faculty_name }}</div>
                                        <div class="text-xs text-slate-500">{{ $f->faculty_spec }}</div>
                                    </div>
                                    <span class="px-2 py-1 text-xs bg-slate-100 rounded font-bold text-slate-500">
                                        {{ ['1' => 'IQA', '2' => 'IQA Lead', '3' => 'Assessor', '4' => 'Trainer'][$f->faculty_type] ?? 'Staff' }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-slate-400 italic">No faculty details found.</p>
                    @endif
                </div>
            </div>

            <div class="flex justify-end pt-6">
                <button type="submit" class="premium-button px-8 py-3 text-lg">Save Planner</button>
            </div>

        </form>
    </div>
@endsection
