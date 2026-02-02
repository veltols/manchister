@extends('layouts.app')

@section('title', 'ATP Details')
@section('subtitle', 'Full information about this training partner')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('emp.ext.atps.index') }}"
                class="text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span class="font-bold text-sm uppercase tracking-wider">Back to List</span>
            </a>

            <div class="flex gap-2">
                <button class="premium-button-secondary px-4 py-2 text-xs">
                    <i class="fa-solid fa-edit"></i> Edit Details
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">

                <div class="premium-card p-8">
                    <div class="flex items-start justify-between mb-8">
                        <div class="flex items-center gap-6">
                            <div
                                class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-3xl font-display font-bold shadow-lg shadow-indigo-200">
                                {{ substr($atp->atp_name, 0, 1) }}
                            </div>
                            <div>
                                <h1 class="text-3xl font-display font-bold text-premium">{{ $atp->atp_name }}</h1>
                                <div class="flex items-center gap-3 mt-2">
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500">
                                        Ref: {{ $atp->atp_ref }}
                                    </span>
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                                @if($atp->atp_status_id == 1) bg-yellow-100 text-yellow-600
                                                @elseif($atp->atp_status_id == 2) bg-blue-100 text-blue-600
                                                @elseif($atp->atp_status_id == 3) bg-emerald-100 text-emerald-600
                                                @else bg-slate-100 text-slate-500 @endif">
                                        {{ $atp->status->atp_status_name ?? 'Unknown Status' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-8 border-t border-slate-100">
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Contact
                                Person</label>
                            <p class="font-bold text-slate-700 flex items-center gap-2">
                                <i class="fa-solid fa-user text-indigo-400"></i>
                                {{ $atp->contact_name }}
                            </p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Email
                                Address</label>
                            <p class="font-bold text-slate-700 flex items-center gap-2">
                                <i class="fa-solid fa-envelope text-indigo-400"></i>
                                {{ $atp->atp_email }}
                            </p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Phone
                                Number</label>
                            <p class="font-bold text-slate-700 flex items-center gap-2">
                                <i class="fa-solid fa-phone text-indigo-400"></i>
                                {{ $atp->atp_phone }}
                            </p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Date
                                Joined</label>
                            <p class="font-bold text-slate-700 flex items-center gap-2">
                                <i class="fa-solid fa-calendar text-indigo-400"></i>
                                {{ \Carbon\Carbon::parse($atp->added_date)->format('F d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="premium-card p-6">
                    <h3 class="text-lg font-display font-bold text-premium mb-4">Location Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <span class="block text-slate-400 text-xs mb-1">Emirate</span>
                            <span class="font-bold text-slate-700">{{ $atp->emirate_id ?? 'N/A' }}</span>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <span class="block text-slate-400 text-xs mb-1">Area</span>
                            <span class="font-bold text-slate-700">{{ $atp->area_name ?? 'N/A' }}</span>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <span class="block text-slate-400 text-xs mb-1">Street</span>
                            <span class="font-bold text-slate-700">{{ $atp->street_name ?? 'N/A' }}</span>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <span class="block text-slate-400 text-xs mb-1">Building</span>
                            <span class="font-bold text-slate-700">{{ $atp->building_name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status Timeline/Logs (Placeholder) -->
                <div class="premium-card p-6">
                    <h3 class="text-lg font-display font-bold text-premium mb-4">System Logs</h3>
                    <div class="space-y-4">
                        @forelse($atp->logs->sortByDesc('log_id')->take(5) as $log)
                            <div class="flex gap-3">
                                <div class="flex flex-col items-center">
                                    <div class="w-2 h-2 rounded-full bg-slate-300"></div>
                                    <div class="w-0.5 h-full bg-slate-100 my-1"></div>
                                </div>
                                <div class="pb-4">
                                    <p class="text-xs font-bold text-slate-700">{{ $log->log_action ?? 'Action' }}</p>
                                    <p class="text-[10px] text-slate-400">
                                        {{ \Carbon\Carbon::parse($log->log_date)->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic">No logs recorded.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
