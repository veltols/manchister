@extends('layouts.app')

@section('title', 'Form 014')
@section('subtitle', 'ATP Approval Site Inspection Checklist')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6 animate-fade-in-up">

        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-lg">For: {{ $atp->atp_name }}</h2>
            <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'planner']) }}"
                class="text-sm font-bold text-slate-500 hover:text-slate-800"><i class="fa-solid fa-arrow-left"></i> Back to
                ATP</a>
        </div>

        <form action="{{ route('eqa.forms.store', ['form_id' => '014', 'atp_id' => $atp->atp_id]) }}" method="POST">
            @csrf

            <!-- Standard Checklist Sections -->
            @php
                $checklistSections = [
                    'Premises Safety' => [
                        'Fire exits are clearly marked and unobstructed?',
                        'First aid kits are available and stocked?',
                        'Lighting and ventilation are adequate?'
                    ],
                    'Learners Reception' => [
                        'Reception area is clean and welcoming?',
                        'Information brochures are available?',
                        'Staff is present to assist learners?'
                    ],
                    'Learners Facilities' => [
                        'Training rooms are spacious along with proper seating?',
                        'Washrooms are clean and accessible?',
                        'Water/Refreshment facility is available?'
                    ],
                    'Equipment' => [
                        'Projectors/Screens are functional?',
                        'Computers/Lab equipment is up to date?',
                        'Safety equipment for labs is in place?'
                    ]
                ];
            @endphp

            <div class="space-y-6">
                @foreach($checklistSections as $sectionName => $items)
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                            <h3 class="font-bold text-premium uppercase text-xs tracking-widest">{{ $sectionName }}</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @foreach($items as $idx => $item)
                                <div class="flex items-start gap-4 pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-slate-700 mb-2">{{ $item }}</p>
                                        <textarea name="feedback_{{ Str::slug($sectionName) }}_{{ $idx }}" rows="2"
                                            class="premium-input w-full text-xs" placeholder="Observations..."></textarea>
                                    </div>
                                    <div class="shrink-0">
                                        <select name="criteria_{{ Str::slug($sectionName) }}_{{ $idx }}"
                                            class="premium-input text-xs">
                                            <option value="">Status</option>
                                            <option value="1">YES</option>
                                            <option value="0">NO</option>
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Staff Met During Visit -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mt-6">
                <h3 class="font-bold text-premium mb-6 uppercase text-xs tracking-widest">Staff Met During Visit</h3>
                <div id="staff-container" class="space-y-3">
                    <!-- Dynamic Row Template -->
                    <div class="flex gap-4 items-center">
                        <input type="text" name="staff_name[]" placeholder="Staff Name" class="premium-input flex-1">
                        <input type="text" name="staff_role[]" placeholder="Role/Position" class="premium-input flex-1">
                    </div>
                    <div class="flex gap-4 items-center">
                        <input type="text" name="staff_name[]" placeholder="Staff Name" class="premium-input flex-1">
                        <input type="text" name="staff_role[]" placeholder="Role/Position" class="premium-input flex-1">
                    </div>
                    <div class="flex gap-4 items-center">
                        <input type="text" name="staff_name[]" placeholder="Staff Name" class="premium-input flex-1">
                        <input type="text" name="staff_role[]" placeholder="Role/Position" class="premium-input flex-1">
                    </div>
                </div>
                <button type="button" class="mt-4 text-xs font-bold text-indigo-600 hover:text-indigo-800">+ Add Row (Logic
                    Pending)</button>
            </div>

            <div class="flex justify-end pt-6">
                <button type="submit" class="premium-button px-8 py-3 text-lg">Save Checklist</button>
            </div>

        </form>
    </div>
@endsection
