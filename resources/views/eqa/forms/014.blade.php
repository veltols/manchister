@extends('layouts.app')

@section('title', 'Form 014')
@section('subtitle', 'ATP Approval Site Inspection Checklist')

@section('content')
    <div class="max-w-6xl mx-auto space-y-8 animate-fade-in-up">

        <!-- Header Action Bar -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-brand flex items-center justify-center text-white shadow-lg shadow-brand/20">
                    <i class="fa-solid fa-clipboard-check text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-premium leading-tight">{{ $atp->atp_name }}</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">ATP Approval Site Inspection Checklist</p>
                </div>
            </div>
            <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'visit']) }}"
                class="group flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 text-slate-500 hover:bg-brand hover:text-white transition-all duration-300 font-bold text-xs uppercase tracking-tighter shadow-sm">
                <i class="fa-solid fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
                <span>Back to ATP</span>
            </a>
        </div>

        <form action="{{ route('eqa.forms.store', ['form_id' => '014', 'atp_id' => $atp->atp_id]) }}" method="POST" class="space-y-8">
            @csrf

            <!-- Standard Checklist Sections -->
            @php
                $checklistSections = [
                    'Premises Safety' => [
                        'icon' => 'fa-fire-extinguisher',
                        'items' => [
                            'Fire exits are clearly marked and unobstructed?',
                            'First aid kits are available and stocked?',
                            'Lighting and ventilation are adequate?'
                        ]
                    ],
                    'Learners Reception' => [
                        'icon' => 'fa-concierge-bell',
                        'items' => [
                            'Reception area is clean and welcoming?',
                            'Information brochures are available?',
                            'Staff is present to assist learners?'
                        ]
                    ],
                    'Learners Facilities' => [
                        'icon' => 'fa-restroom',
                        'items' => [
                            'Training rooms are spacious along with proper seating?',
                            'Washrooms are clean and accessible?',
                            'Water/Refreshment facility is available?'
                        ]
                    ],
                    'Equipment' => [
                        'icon' => 'fa-laptop-code',
                        'items' => [
                            'Projectors/Screens are functional?',
                            'Computers/Lab equipment is up to date?',
                            'Safety equipment for labs is in place?'
                        ]
                    ]
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($checklistSections as $sectionName => $sectionData)
                    <div class="premium-card overflow-hidden">
                        <div class="bg-slate-50 border-b border-slate-100 px-8 py-5 flex items-center justify-between">
                            <h3 class="font-bold text-slate-600 uppercase text-[10px] tracking-[0.2em] flex items-center gap-3">
                                <i class="fa-solid {{ $sectionData['icon'] }} text-brand"></i>
                                {{ $sectionName }}
                            </h3>
                            <span class="px-2 py-0.5 rounded bg-brand/10 text-brand text-[10px] font-black uppercase tracking-tighter">{{ count($sectionData['items']) }} Items</span>
                        </div>
                        <div class="p-6 space-y-6">
                            @foreach($sectionData['items'] as $idx => $item)
                                <div class="space-y-4 pb-6 border-b border-slate-50 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start gap-4">
                                        <p class="text-sm font-bold text-premium leading-snug">{{ $item }}</p>
                                        <select name="criteria_{{ Str::slug($sectionName) }}_{{ $idx }}"
                                            class="w-24 text-[10px] font-black uppercase tracking-widest bg-slate-50 border border-slate-100 rounded-lg px-2 py-1.5 text-slate-500 focus:ring-brand focus:border-brand shadow-inner cursor-pointer transition-all">
                                            <option value="">STT</option>
                                            <option value="1">YES</option>
                                            <option value="0">NO</option>
                                        </select>
                                    </div>
                                    <textarea name="feedback_{{ Str::slug($sectionName) }}_{{ $idx }}" rows="2"
                                        class="premium-input w-full text-xs bg-slate-50/30 border-dashed focus:bg-white resize-none" 
                                        placeholder="Add specific observations for this item...">{{ $formData->{Str::slug($sectionName) . "_$idx"} ?? '' }}</textarea>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Staff Met During Visit -->
            <div class="premium-card p-8">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="font-bold text-premium uppercase text-xs tracking-[0.2em] border-l-4 border-l-brand pl-4">Staff Interaction Log</h3>
                    <div class="px-4 py-1.5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-widest border border-slate-200">
                        Visit Interaction Traceability
                    </div>
                </div>

                <div id="staff-container" class="space-y-4">
                    @php
                        $staffCount = 3; // Default rows
                    @endphp
                    @for($i = 0; $i < $staffCount; $i++)
                        <div class="flex flex-col md:flex-row gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 group hover:bg-white hover:border-brand/30 hover:shadow-md transition-all">
                            <div class="flex-1 space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Staff Name</label>
                                <div class="relative">
                                    <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                    <input type="text" name="staff_name[]" placeholder="Enter full name" class="premium-input w-full pl-11 bg-white">
                                </div>
                            </div>
                            <div class="flex-1 space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Role / Position</label>
                                <div class="relative">
                                    <i class="fa-solid fa-briefcase absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                    <input type="text" name="staff_role[]" placeholder="e.g. Training Manager" class="premium-input w-full pl-11 bg-white">
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
                
                <div class="mt-6 flex justify-center">
                    <button type="button" class="group flex items-center gap-2 px-6 py-2 rounded-xl bg-white border border-slate-200 text-slate-500 hover:border-brand hover:text-brand hover:shadow-lg transition-all text-[10px] font-bold uppercase tracking-widest">
                        <i class="fa-solid fa-plus-circle transition-transform group-hover:rotate-90"></i>
                        <span>Add Another Staff Member</span>
                    </button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between bg-white/40 backdrop-blur-md p-6 rounded-2xl border border-white/20 shadow-xl">
                <div class="flex items-center gap-3 text-slate-400">
                    <i class="fa-solid fa-eye text-brand"></i>
                    <span class="text-xs font-bold uppercase tracking-widest">Site inspection data will be logged in audit trail</span>
                </div>
                <div class="flex items-center gap-4">
                    <button type="reset" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-500 font-bold text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">
                        Reset Checklist
                    </button>
                    <button type="submit" class="premium-button px-10 py-3.5 text-sm shadow-xl shadow-brand/30">
                        <i class="fa-solid fa-file-export mr-2"></i>
                        Submit Inspection Checklist
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection
