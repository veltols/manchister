@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand to-brand-dark p-0.5 shadow-lg shadow-brand/20">
                    <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center overflow-hidden">
                        @if($atp->atp_logo)
                            <img src="{{ asset('uploads/' . $atp->atp_logo) }}" class="w-10 h-10 object-contain">
                        @else
                            <i class="fa-solid fa-building text-2xl text-slate-300"></i>
                        @endif
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-premium tracking-tight">{{ $atp->atp_name }}</h1>
                    <p class="text-slate-500 font-medium">Registration Request Details</p>
                </div>
            </div>
            <a href="{{ route('emp.atps.show', $atp->atp_id) }}" 
               class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
                Back to ATP
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Registration Info -->
                <div class="premium-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>
                        <h2 class="text-xl font-bold text-premium">General Information</h2>
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Request Status</label>
                            <span class="px-4 py-1.5 rounded-lg text-xs font-bold {{ $regReq && $regReq->is_submitted ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600' }} uppercase">
                                {{ $regReq->form_status ?? 'Pending' }}
                            </span>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Submitted Date</label>
                            <p class="font-bold text-premium">{{ $regReq->submitted_date ?? 'Not Submitted' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Locations -->
                <div class="premium-card overflow-hidden">
                    <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <h2 class="text-xl font-bold text-premium">Location Details</h2>
                        </div>
                    </div>
                    <table class="w-full text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Name</th>
                                <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Classrooms</th>
                                <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Floor Map</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($locations as $loc)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-5 font-bold text-premium">{{ $loc->location_name }}</td>
                                    <td class="px-8 py-5 text-sm font-medium text-slate-600">{{ $loc->classrooms_count }}</td>
                                    <td class="px-8 py-5 text-right">
                                        @if($loc->floor_map)
                                            <a href="{{ asset('uploads/' . $loc->floor_map) }}" target="_blank" class="text-brand hover:underline font-bold text-sm">View File</a>
                                        @else
                                            <span class="text-slate-300 italic text-xs">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-8 py-10 text-center text-slate-400 italic">No locations recorded</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Qualifications -->
                <div class="premium-card overflow-hidden">
                    <div class="p-8 border-b border-slate-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <h2 class="text-xl font-bold text-premium">Qualifications Offered</h2>
                    </div>
                    <div class="divide-y divide-slate-50">
                        @forelse($qualifications as $q)
                            <div class="p-8 hover:bg-slate-50/50 transition-colors flex items-center justify-between">
                                <div>
                                    <h3 class="font-bold text-premium mb-1">{{ $q->qualification_name }}</h3>
                                    <div class="flex items-center gap-4">
                                        <span class="text-xs font-medium text-slate-500 flex items-center gap-1.5">
                                            <i class="fa-solid fa-building-columns text-[10px]"></i>
                                            {{ $q->qualification_provider }}
                                        </span>
                                        <span class="text-xs font-medium text-slate-500 flex items-center gap-1.5">
                                            <i class="fa-solid fa-tag text-[10px]"></i>
                                            {{ $q->qualification_type }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase mb-1">Max Learners</div>
                                    <span class="px-3 py-1 bg-slate-100 rounded-lg text-xs font-bold text-brand">{{ $q->learners_entry_no }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center text-slate-400 italic font-medium">No qualifications listed</div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Sidebar Info -->
            <div class="space-y-8">
                
                <!-- Learner Projections -->
                <div class="premium-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <h2 class="text-xl font-bold text-premium">Projections</h2>
                    </div>

                    <div class="space-y-6">
                        @php $projYears = [date('Y')+1, date('Y')+2, date('Y')+3]; @endphp
                        @foreach($projYears as $index => $year)
                            @php $field = 'le' . ($index + 7); @endphp
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Year {{ $year }}</p>
                                    <p class="text-sm font-bold text-premium">Expected Learners</p>
                                </div>
                                <div class="text-xl font-black text-brand">{{ $learnerEnrolled->$field ?? 0 }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Faculty Summary -->
                <div class="premium-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i class="fa-solid fa-users-gear"></i>
                        </div>
                        <h2 class="text-xl font-bold text-premium">Faculty</h2>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-slate-100 rounded-2xl">
                            <span class="text-sm font-bold text-slate-600">Trainers</span>
                            <span class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">{{ $trainers->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 border border-slate-100 rounded-2xl">
                            <span class="text-sm font-bold text-slate-600">Assessors</span>
                            <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">{{ $assessors->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 border border-slate-100 rounded-2xl">
                            <span class="text-sm font-bold text-slate-600">IQA Staff</span>
                            <span class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-xs font-bold">{{ $iqas->count() }}</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('emp.atps.forms.faculty', $atp->atp_id) }}" 
                       class="w-full mt-6 py-3 border border-slate-200 rounded-xl text-center text-xs font-bold text-slate-500 hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                        View Detailed Faculty
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
