@extends('layouts.app')

@section('title', 'Communication Hub')
@section('subtitle', 'Manage your inbound and outbound communications')

@section('content')
    <div class="space-y-8 animate-fade-in-up">

        <!-- Welcome Header -->
        <div class="premium-card p-8 bg-white border-l-4 border-brand-dark relative overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-brand-dark/5 rounded-full -mr-32 -mt-32"></div>
            <div class="relative z-10">
                <h2 class="text-3xl font-display font-bold text-premium">Communication Center</h2>
                <p class="text-slate-500 mt-2 max-w-2xl">Track incoming correspondence and manage outgoing formal communications in one central hub.</p>
            </div>
        </div>

        <!-- Communication Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- Inbound Correspondence -->
            <div class="hub-card group bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-20 h-20 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 group-hover:bg-teal-600 group-hover:text-white transition-all duration-500 shadow-sm">
                        <i class="fa-solid fa-inbox text-3xl"></i>
                    </div>
                    @if($inboundCount > 0)
                        <span class="bg-amber-500 text-white text-xs font-black px-3 py-1 rounded-full shadow-md animate-pulse">
                            {{ $inboundCount }} PENDING
                        </span>
                    @endif
                </div>
                
                <h3 class="text-2xl font-bold text-premium mb-3">Inbound Portal</h3>
                <p class="text-slate-500 mb-8 leading-relaxed">Review incoming mail, official letters, and assigned action items from the General Manager's office.</p>
                
                <div class="space-y-4">
                    @if(!Auth::user()->is_gm && !Auth::user()->is_liaison)
                        <a href="{{ route('emp.inbound.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-teal-50 group/item transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-teal-600 shadow-sm">
                                    <i class="fa-solid fa-list-check"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-700">My Action Items</span>
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Execution Stage</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($inboundActionCount > 0)
                                    <span class="bg-teal-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $inboundActionCount }}</span>
                                @endif
                                <i class="fa-solid fa-chevron-right text-slate-300 group-hover/item:text-teal-600 group-hover/item:translate-x-1 transition-all"></i>
                            </div>
                        </a>
                    @endif
                    
                    @if(Auth::user()->is_gm)
                        <a href="{{ route('emp.inbound-gm.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-amber-50 group/item transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-amber-600 shadow-sm">
                                    <i class="fa-solid fa-crown"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-700">GM Review Queue</span>
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Assignment Stage</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($empGmInboundPending > 0)
                                    <span class="bg-amber-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $empGmInboundPending }}</span>
                                @endif
                                <i class="fa-solid fa-chevron-right text-slate-300 group-hover/item:text-amber-600 group-hover/item:translate-x-1 transition-all"></i>
                            </div>
                        </a>
                    @endif

                    @if(Auth::user()->is_liaison && !Auth::user()->is_gm)
                        <a href="{{ route('emp.inbound-liaison.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-sky-50 group/item transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-sky-600 shadow-sm">
                                    <i class="fa-solid fa-envelope-circle-check"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-700">Entity Registration</span>
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Registration Stage</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-slate-300 group-hover/item:text-sky-600 group-hover/item:translate-x-1 transition-all"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Outbound Correspondence -->
            <div class="hub-card group bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-20 h-20 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 shadow-sm">
                        <i class="fa-solid fa-paper-plane text-3xl"></i>
                    </div>
                    @if($outboundCount > 0)
                        <span class="bg-indigo-500 text-white text-xs font-black px-3 py-1 rounded-full shadow-md animate-pulse">
                            {{ $outboundCount }} PENDING
                        </span>
                    @endif
                </div>

                <h3 class="text-2xl font-bold text-premium mb-3">Outbound Portal</h3>
                <p class="text-slate-500 mb-8 leading-relaxed">Create new communication requests, track approval workflows, and manage formal external correspondence.</p>

                <div class="space-y-4">
                    {{-- My Requests --}}
                    <!-- @if(!Auth::user()->is_gm)
                        <a href="{{ route('emp.communications.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-indigo-50 group/item transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-indigo-600 shadow-sm">
                                    <i class="fa-solid fa-file-pen"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-700">My Requests</span>
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">History & New</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-slate-300 group-hover/item:text-indigo-600 group-hover/item:translate-x-1 transition-all"></i>
                        </a>
                    @endif -->

                    {{-- Team Review (Line Manager) --}}
                    @php $isLM = \App\Models\Department::where('line_manager_id', Auth::user()->employee->employee_id ?? 0)->exists(); @endphp
                    @if($isLM && !Auth::user()->is_gm)
                    <a href="{{ route('emp.communications.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-indigo-50 group/item transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-indigo-600 shadow-sm">
                                    <i class="fa-solid fa-file-pen"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-700">My Requests</span>
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">History & New</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-slate-300 group-hover/item:text-indigo-600 group-hover/item:translate-x-1 transition-all"></i>
                        </a>
                        <a href="{{ route('emp.lm.communications.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-teal-50 group/item transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-teal-600 shadow-sm">
                                    <i class="fa-solid fa-user-tie"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-700">Team Review Queue</span>
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Line Manager Stage</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($lmCommPending > 0)
                                    <span class="bg-teal-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $lmCommPending }}</span>
                                @endif
                                <i class="fa-solid fa-chevron-right text-slate-300 group-hover/item:text-teal-600 group-hover/item:translate-x-1 transition-all"></i>
                            </div>
                        </a>
                    @endif

                    {{-- GM Review --}}
                    @if(Auth::user()->is_gm)
                        <a href="{{ route('admin.communications.outbound.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-amber-50 group/item transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-amber-600 shadow-sm">
                                    <i class="fa-solid fa-crown"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-700">GM Review Queue</span>
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Final Approval Stage</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($gmOutboundPending > 0)
                                    <span class="bg-amber-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $gmOutboundPending }}</span>
                                @endif
                                <i class="fa-solid fa-chevron-right text-slate-300 group-hover/item:text-amber-600 group-hover/item:translate-x-1 transition-all"></i>
                            </div>
                        </a>
                    @endif

                    {{-- Dispatch (Liaison) --}}
                    @if(Auth::user()->is_liaison && !Auth::user()->is_gm)
                        <a href="{{ route('emp.outbound-liaison.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-sky-50 group/item transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-sky-600 shadow-sm">
                                    <i class="fa-solid fa-truck-fast"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-700">Dispatch Queue</span>
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Liaison Stage</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($liaisonOutboundPending > 0)
                                    <span class="bg-sky-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $liaisonOutboundPending }}</span>
                                @endif
                                <i class="fa-solid fa-chevron-right text-slate-300 group-hover/item:text-sky-600 group-hover/item:translate-x-1 transition-all"></i>
                            </div>
                        </a>
                    @endif

                    {{-- Action Tasks --}}
                    @php $isLM = $isLM ?? \App\Models\Department::where('line_manager_id', Auth::user()->employee->employee_id ?? 0)->exists(); @endphp
                    @if(!Auth::user()->is_gm && !$isLM)
                        <a href="{{ route('emp.outbound-tasks.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-rose-50 group/item transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-rose-600 shadow-sm">
                                    <i class="fa-solid fa-tasks"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-700">Action Items</span>
                                    <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Assigned Items</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($outboundTaskCount > 0)
                                    <span class="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $outboundTaskCount }}</span>
                                @endif
                                <i class="fa-solid fa-chevron-right text-slate-300 group-hover/item:text-rose-600 group-hover/item:translate-x-1 transition-all"></i>
                            </div>
                        </a>
                    @endif
                </div>
            </div>

        </div>

    </div>

    <style>
        .hub-card {
            cursor: default;
        }
    </style>
@endsection
