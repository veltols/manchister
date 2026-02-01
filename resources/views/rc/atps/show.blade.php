@extends('layouts.app')

@section('title', $atp->atp_name)
@section('subtitle', 'Training Provider Details')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('rc.atps.index') }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 text-sm font-semibold mb-2">
                <i class="fa-solid fa-arrow-left"></i> Back to List
            </a>
            <div class="flex items-center gap-3 min-w-0">
                <h1 class="text-2xl md:text-3xl font-display font-bold text-slate-800 truncate">{{ $atp->atp_name }}</h1>
                <span class="flex-shrink-0 px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-600 text-xs font-mono font-bold">
                    {{ $atp->atp_ref }}
                </span>
            </div>
        </div>
        <div class="flex gap-3">
             @php
                $statusColors = [
                    'Active' => 'bg-green-50 text-green-700 from-green-500 to-emerald-600',
                    'Inactive' => 'bg-red-50 text-red-700 from-red-500 to-rose-600',
                    'Pending' => 'bg-yellow-50 text-yellow-700 from-yellow-500 to-amber-600',
                    'New' => 'bg-blue-50 text-blue-700 from-blue-500 to-cyan-600'
                ];
                $status = $atp->status->status_name ?? 'Unknown';
                // Using gradient for the badge background if desired, or just text
                $badgeClass = $status == 'Active' ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-800';
            @endphp
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl {{ $badgeClass }} font-bold shadow-sm">
                <i class="fa-solid fa-circle text-[10px]"></i>
                {{ $status }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Basic Info Card -->
            <div class="premium-card p-8">
                <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="fa-solid fa-building text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">Institution Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div class="min-w-0">
                        <label class="block text-xs uppercase tracking-wider text-slate-400 font-bold mb-1">Institution Name</label>
                        <p class="text-slate-800 font-semibold break-words">{{ $atp->atp_name }}</p>
                    </div>
                    <div class="min-w-0">
                        <label class="block text-xs uppercase tracking-wider text-slate-400 font-bold mb-1">Category</label>
                        <p class="text-slate-800 font-medium truncate">{{ $atp->category->atp_category_name ?? $atp->category->category_name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-wider text-slate-400 font-bold mb-1">Type</label>
                        <p class="text-slate-800 font-medium">{{ $atp->type->atp_type_name ?? '-' }}</p>
                    </div>
                    <div>
                         <label class="block text-xs uppercase tracking-wider text-slate-400 font-bold mb-1">Emirate / City</label>
                        <p class="text-slate-800 font-medium">{{ $atp->city->city_name_en ?? $atp->city->city_name ?? '-' }}</p>
                    </div>
                     <div>
                         <label class="block text-xs uppercase tracking-wider text-slate-400 font-bold mb-1">Registered On</label>
                        <p class="text-slate-600">{{ \Carbon\Carbon::parse($atp->added_date)->format('M d, Y') }}</p>
                    </div>
                     <div>
                         <label class="block text-xs uppercase tracking-wider text-slate-400 font-bold mb-1">Registered By</label>
                        <p class="text-slate-600">{{ $atp->creator->first_name ?? '' }} {{ $atp->creator->last_name ?? '' }}</p>
                    </div>
                </div>
            </div>

            <!-- Contacts Card -->
            <div class="premium-card p-8">
                 <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i class="fa-solid fa-address-book text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">Contact Persons</h3>
                </div>

                 @if($atp->contacts->count() > 0)
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($atp->contacts as $contact)
                        <div class="flex items-center p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                            <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-lg mr-4">
                                {{ strtoupper(substr($contact->contact_name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-800">{{ $contact->contact_name }}</h4>
                                <p class="text-sm text-indigo-600 font-medium">{{ $contact->contact_designation }}</p>
                            </div>
                            <div class="text-right text-sm min-w-max hidden sm:block">
                                <div class="text-slate-600 mb-1"><i class="fa-solid fa-envelope text-slate-400 mr-2"></i>{{ $contact->contact_email }}</div>
                                <div class="text-slate-600"><i class="fa-solid fa-phone text-slate-400 mr-2"></i>{{ $contact->contact_phone }}</div>
                            </div>
                        </div>
                        <div class="sm:hidden p-4 pt-0 border-t border-slate-50 text-xs space-y-1">
                            <div class="text-slate-600"><i class="fa-solid fa-envelope text-slate-400 mr-2"></i>{{ $contact->contact_email }}</div>
                            <div class="text-slate-600"><i class="fa-solid fa-phone text-slate-400 mr-2"></i>{{ $contact->contact_phone }}</div>
                        </div>
                        @endforeach
                    </div>
                 @else
                    <!-- Fallback if no specific contacts records yet (legacy data) -->
                    <div class="flex items-center p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                        <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-lg mr-4">
                            {{ strtoupper(substr($atp->contact_name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-800">{{ $atp->contact_name }}</h4>
                            <p class="text-sm text-indigo-600 font-medium">Main Contact</p>
                        </div>
                        <div class="text-right text-sm">
                            <div class="text-slate-600 mb-1"><i class="fa-solid fa-envelope text-slate-400 mr-2"></i>{{ $atp->atp_email }}</div>
                             <div class="text-slate-600"><i class="fa-solid fa-phone text-slate-400 mr-2"></i>{{ $atp->atp_phone }}</div>
                        </div>
                    </div>
                 @endif
            </div>

            <!-- Faculty Card -->
            <div class="premium-card p-8">
                <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-chalkboard-user text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Faculty Members</h3>
                    </div>
                </div>

                @if($atp->faculty->count() > 0)
                    <div class="overflow-x-auto rounded-xl border border-slate-100 shadow-sm">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500">
                                <tr>
                                    <th class="px-6 py-3">Name</th>
                                    <th class="px-6 py-3">Role</th>
                                    <th class="px-6 py-3">Specialization</th>
                                    <th class="px-6 py-3 text-right">CV</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($atp->faculty as $faculty)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-800">{{ $faculty->faculty_name }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-2 py-1 rounded-md bg-white border border-slate-200 text-xs font-semibold text-slate-600 shadow-sm">
                                            {{ $faculty->type_name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ $faculty->faculty_spec }}</td>
                                    <td class="px-6 py-4 text-right">
                                        @if($faculty->faculty_cv)
                                            <a href="{{ asset('uploads/' . $faculty->faculty_cv) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center gap-1">
                                                <i class="fa-solid fa-file-pdf"></i> View
                                            </a>
                                        @else
                                            <span class="text-slate-400 italic">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                         <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-users-slash text-xl"></i>
                        </div>
                        <p class="text-slate-500 font-medium">No faculty members registered yet.</p>
                    </div>
                @endif
            </div>

            <!-- Learner Enrollment Card -->
            <div class="premium-card p-8">
                <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center">
                            <i class="fa-solid fa-graduation-cap text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Learner Enrollment Requests</h3>
                    </div>
                </div>

                @if($atp->learners->count() > 0)
                    <div class="overflow-x-auto rounded-xl border border-slate-100 shadow-sm">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500">
                                <tr>
                                    <th class="px-6 py-3">Cohort</th>
                                    <th class="px-6 py-3">Qualification</th>
                                    <th class="px-6 py-3 text-center">Learners</th>
                                    <th class="px-6 py-3">Start Date</th>
                                    <th class="px-6 py-3">End Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($atp->learners as $learner)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-800">{{ $learner->cohort }}</td>
                                    <td class="px-6 py-4 text-indigo-600 font-medium">
                                        {{ $learner->qualification->qualification_name ?? 'Unknown Qualification' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold">
                                            {{ $learner->learners_no }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($learner->start_date)->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($learner->end_date)->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                         <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-clipboard-list text-xl"></i>
                        </div>
                        <p class="text-slate-500 font-medium">No learner enrollment requests found.</p>
                    </div>
                @endif
            </div>

            <!-- Qualifications Card -->
            <div class="premium-card p-8">
                <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="fa-solid fa-certificate text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Qualification Registration</h3>
                    </div>
                </div>

                 @if($atp->qualifications->count() > 0)
                    <div class="space-y-6">
                        @foreach($atp->qualifications as $qual)
                        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                            
                            <!-- Qualification Header -->
                            <div class="p-4 bg-slate-50 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h4 class="font-bold text-slate-800">{{ $qual->qualification_name }}</h4>
                                    <div class="text-xs text-slate-500 font-mono mt-1">CODE: {{ $qual->qualification_code }}</div>
                                </div>
                                <div class="flex items-center gap-4 text-sm">
                                    <div class="text-slate-600">
                                        <span class="text-xs uppercase font-bold text-slate-400 block">Est. Learners</span>
                                        <span class="font-medium">{{ $qual->learners_estimate }}</span>
                                    </div>
                                     <div class="text-slate-600">
                                        <span class="text-xs uppercase font-bold text-slate-400 block">Type</span>
                                        <span class="font-medium">{{ $qual->qualification_type }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Qualification Details (Faculty & Evidence) -->
                            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <!-- Related Faculty -->
                                <div>
                                    <h5 class="text-xs uppercase font-bold text-slate-400 mb-3 flex items-center gap-2">
                                        <i class="fa-solid fa-chalkboard-user"></i> Assigned Faculty
                                    </h5>
                                    @if($qual->faculty->count() > 0)
                                        <ul class="space-y-2">
                                            @foreach($qual->faculty as $fac)
                                            <li class="flex items-center justify-between text-sm p-2 bg-slate-50 rounded border border-slate-100">
                                                <span class="font-medium text-slate-700">{{ $fac->faculty_name }}</span>
                                                <span class="text-xs text-slate-500 bg-white px-2 py-0.5 rounded border border-slate-200">{{ $fac->type_name }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-xs text-slate-400 italic">No faculty assigned specifically to this qualification.</p>
                                    @endif
                                </div>

                                <!-- Evidence -->
                                <div>
                                     <h5 class="text-xs uppercase font-bold text-slate-400 mb-3 flex items-center gap-2">
                                        <i class="fa-solid fa-file-contract"></i> Evidence
                                     </h5>
                                     @if($qual->evidence->count() > 0)
                                        <ul class="space-y-2">
                                            @foreach($qual->evidence as $ev)
                                            <li class="flex items-center justify-between text-sm p-2 bg-slate-50 rounded border border-slate-100">
                                                <span class="font-medium text-slate-700 truncate max-w-[150px]" title="Evidence Record">{{ $ev->record_id }}</span> <!-- Using ID/Title if available -->
                                                @if($ev->evidence)
                                                    <a href="{{ asset('uploads/' . $ev->evidence) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-xs font-bold inline-flex items-center gap-1">
                                                        <i class="fa-solid fa-download"></i> View
                                                    </a>
                                                @else
                                                    <span class="text-xs text-slate-400">No File</span>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-xs text-slate-400 italic">No specific evidence documents uploaded.</p>
                                    @endif
                                </div>

                            </div>

                        </div>
                        @endforeach
                    </div>
                 @else
                    <div class="text-center py-8 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-certificate text-xl"></i>
                        </div>
                        <p class="text-slate-500 font-medium">No qualifications registered.</p>
                    </div>
                @endif
            </div>

            <!-- SED Card -->
            <div class="premium-card p-8">
                <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                            <i class="fa-solid fa-file-invoice text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Self Evaluation Document (SED)</h3>
                    </div>
                </div>

                @if($atp->sed)
                    <!-- Key Info -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 p-4 bg-slate-50/50 rounded-xl border border-slate-100">
                        <div>
                            <label class="text-xs uppercase font-bold text-slate-400">Author</label>
                            <p class="font-semibold text-slate-700">{{ $atp->sed->sed_1 }}</p>
                        </div>
                        <div>
                            <label class="text-xs uppercase font-bold text-slate-400">Role</label>
                            <p class="font-semibold text-slate-700">{{ $atp->sed->sed_2 }}</p>
                        </div>
                        <div>
                            <label class="text-xs uppercase font-bold text-slate-400">Last Internal Audit</label>
                            <p class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($atp->sed->sed_3)->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Narrative Sections -->
                    <div class="space-y-6">
                        @foreach([
                            'Overview' => 'sed_4',
                            'Background Information' => 'sed_6',
                            'Methodology' => 'sed_8',
                            'Aims & Objectives' => 'sed_10',
                            'Curriculum Delivery' => 'sed_12',
                            'Future Plans' => 'sed_14'
                        ] as $title => $key)
                            @if($atp->sed->$key)
                            <div>
                                <h4 class="font-bold text-slate-800 mb-2 border-l-4 border-indigo-600 pl-3">{{ $title }}</h4>
                                <div class="bg-white p-4 rounded-lg border border-slate-100 text-sm text-slate-600 leading-relaxed break-words">
                                    {!! nl2br(e($atp->sed->$key)) !!}
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                 @else
                    <div class="text-center py-8 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-file-circle-xmark text-xl"></i>
                        </div>
                        <p class="text-slate-500 font-medium">No Self Evaluation Document found.</p>
                    </div>
                @endif
            </div>

             <!-- QIP Card -->
            <div class="premium-card p-8">
                <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                            <i class="fa-solid fa-list-check text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Quality Improvement Plan (QIP)</h3>
                    </div>
                </div>

                @if($atp->qip->count() > 0)
                    <div class="overflow-x-auto rounded-xl border border-slate-100 shadow-sm">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500">
                                <tr>
                                    <th class="px-6 py-3 w-1/3">Action Required</th>
                                    <th class="px-6 py-3">Priority</th>
                                    <th class="px-6 py-3">Impact</th>
                                    <th class="px-6 py-3">Ownership</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($atp->qip as $item)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-800">{{ $item->qip_action }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-2 py-1 rounded text-xs font-bold uppercase
                                            {{ $item->qip_priority == 'High' ? 'bg-red-100 text-red-700' : 
                                              ($item->qip_priority == 'Medium' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                                            {{ $item->qip_priority }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ $item->qip_impact }}</td>
                                    <td class="px-6 py-4 font-mono text-xs">{{ $item->qip_ownership }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-check-double text-xl"></i>
                        </div>
                        <p class="text-slate-500 font-medium">No areas requiring improvement identified.</p>
                    </div>
                @endif
            </div>

        </div>

        <!-- Right Column: Actions & Stats (Side Info) -->
        <div class="space-y-6">
            
            <div class="premium-card p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <button class="w-full text-left p-3 rounded-xl hover:bg-slate-50 text-slate-600 font-medium flex items-center gap-3 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <i class="fa-solid fa-pen"></i>
                        </div>
                        Edit Details
                    </button>
                    <button class="w-full text-left p-3 rounded-xl hover:bg-slate-50 text-slate-600 font-medium flex items-center gap-3 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                             <i class="fa-solid fa-key"></i>
                        </div>
                        Reset Password
                    </button>
                    <button class="w-full text-left p-3 rounded-xl hover:bg-slate-50 text-slate-600 font-medium flex items-center gap-3 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center">
                            <i class="fa-solid fa-ban"></i>
                        </div>
                        Suspend Provider
                    </button>
                </div>
            </div>

            <!-- Compliance Card (KPIs) -->
            <div class="premium-card p-8">
                <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center">
                            <i class="fa-solid fa-clipboard-check text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 border-none">Compliance</h3>
                    </div>
                </div>

                @if($standards->count() > 0)
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($standards as $std)
                        <div class="p-4 rounded-xl border transition-all hover:shadow-md {{ $std->kpi_color }}">
                            <div class="flex items-start justify-between mb-2">
                                <i class="fa-solid {{ $std->main_icon }} text-xl opacity-80"></i>
                                <span class="text-lg font-black">{{ $std->score }}%</span>
                            </div>
                            <h4 class="font-bold text-sm leading-tight">{{ $std->main_title }}</h4>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <p class="text-slate-500 text-sm">No standards defined.</p>
                    </div>
                @endif
            </div>

            <!-- Learner Statistics (History) -->
            <div class="premium-card p-6">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-2">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">History</h3>
                    </div>
                </div>
                
                @if($atp->learnerHistory)
                    @php $currentYear = date('Y'); @endphp
                    <div class="space-y-4">
                        <div class="p-3 bg-slate-50 rounded-lg">
                            <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Learners Enrolled</label>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-slate-500">{{ $currentYear }}</span>
                                <span class="font-bold text-slate-800">{{ $atp->learnerHistory->le3 }}</span>
                            </div>
                        </div>
                         <div class="p-3 bg-indigo-50/50 rounded-lg">
                            <label class="block text-[10px] uppercase font-bold text-indigo-400 mb-1">Projected ({{ $currentYear + 1 }})</label>
                            <span class="font-bold text-indigo-800">{{ $atp->learnerHistory->le7 }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-slate-400 text-center py-4">No historical data.</p>
                @endif
            </div>

            <!-- Location Details -->
            <div class="premium-card p-6">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-2">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Locations</h3>
                    </div>
                </div>

                @if($atp->locations->count() > 0)
                    <div class="space-y-3">
                        @foreach($atp->locations as $loc)
                        <div class="p-3 bg-slate-50 border border-slate-100 rounded-lg">
                            <h4 class="font-bold text-slate-800 text-sm mb-1">{{ $loc->location_name }}</h4>
                            <p class="text-[10px] text-slate-500">Classrooms: {{ $loc->classrooms_count }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 text-center py-4">No locations recorded.</p>
                @endif
            </div>

            <!-- EQA Card -->
            <div class="premium-card p-6">
                <div class="flex items-center gap-2 mb-4 border-b border-slate-100 pb-2">
                    <div class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">EQA Visit</h3>
                </div>

                @if($atp->eqa && $atp->eqa->eqa_visit_date)
                    <div class="text-center p-3 bg-slate-50 rounded-lg">
                        <span class="block text-xl font-black text-slate-800">{{ \Carbon\Carbon::parse($atp->eqa->eqa_visit_date)->format('d') }}</span>
                        <span class="block text-[10px] uppercase font-bold text-slate-500">{{ \Carbon\Carbon::parse($atp->eqa->eqa_visit_date)->format('M Y') }}</span>
                    </div>
                @else
                    <p class="text-xs text-slate-400 text-center py-4">Not Scheduled</p>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection
