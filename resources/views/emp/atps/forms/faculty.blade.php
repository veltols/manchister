@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 p-0.5 shadow-lg shadow-emerald-100">
                    <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center overflow-hidden text-emerald-600">
                        <i class="fa-solid fa-users-gear text-2xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-premium tracking-tight">{{ $atp->atp_name }}</h1>
                    <p class="text-slate-500 font-medium">Faculty & Technical Staff Details</p>
                </div>
            </div>
            <a href="{{ url()->previous() }}" 
               class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
                Go Back
            </a>
        </div>

        <!-- Faculty Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($faculty as $member)
                @php
                    $roleMap = [
                        1 => ['label' => 'IQA Staff', 'icon' => 'fa-certificate', 'color' => 'purple'],
                        3 => ['label' => 'Assessor', 'icon' => 'fa-spell-check', 'color' => 'blue'],
                        4 => ['label' => 'Trainer', 'icon' => 'fa-chalkboard-user', 'color' => 'emerald'],
                        5 => ['label' => 'Trainer (External)', 'icon' => 'fa-chalkboard-user', 'color' => 'teal'],
                    ];
                    $role = $roleMap[$member->faculty_type] ?? ['label' => 'Staff Member', 'icon' => 'fa-user-tie', 'color' => 'slate'];
                @endphp
                <div class="premium-card p-6 flex items-start gap-4 hover:shadow-xl hover:shadow-{{ $role['color'] }}-100/20 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-{{ $role['color'] }}-50 flex items-center justify-center text-{{ $role['color'] }}-600 shrink-0">
                        <i class="fa-solid {{ $role['icon'] }} text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-bold text-premium leading-tight">{{ $member->faculty_name }}</h3>
                            <span class="px-2 py-0.5 bg-{{ $role['color'] }}-100 text-{{ $role['color'] }}-700 rounded text-[9px] font-black uppercase tracking-widest">
                                {{ $role['label'] }}
                            </span>
                        </div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">{{ $member->faculty_spec ?? 'General Specification' }}</p>
                        
                        <div class="grid grid-cols-2 gap-2 mt-4">
                            <div class="p-2 bg-slate-50 rounded-lg border border-slate-100/50">
                                <p class="text-[8px] font-bold text-slate-400 uppercase">CV Status</p>
                                <p class="text-[10px] font-bold text-slate-600">Verified</p>
                            </div>
                            <div class="p-2 bg-slate-50 rounded-lg border border-slate-100/50">
                                <p class="text-[8px] font-bold text-slate-400 uppercase">Certificates</p>
                                <p class="text-[10px] font-bold text-slate-600">Approved</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full premium-card p-20 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                        <i class="fa-solid fa-users-slash text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-premium mb-2">No Faculty Members</h3>
                    <p class="text-slate-400 max-w-md mx-auto">The faculty list for this Training Provider is currently empty or has not been populated yet.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
