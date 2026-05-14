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
                    <a href="{{ route('emp.inbound.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-teal-50 group/item transition-colors">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-list-check text-teal-600"></i>
                            <span class="font-bold text-slate-700">My Action Items</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-slate-300 group-hover/item:text-teal-600 group-hover/item:translate-x-1 transition-all"></i>
                    </a>
                    
                    @if(Auth::user()->is_gm)
                        <a href="{{ route('emp.inbound-gm.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-amber-50 group/item transition-colors">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-crown text-amber-600"></i>
                                <span class="font-bold text-slate-700">GM Review Queue</span>
                            </div>
                            <i class="fa-solid fa-chevron-right text-slate-300 group-hover/item:text-amber-600 group-hover/item:translate-x-1 transition-all"></i>
                        </a>
                    @endif

                    @if(Auth::user()->is_liaison)
                        <a href="{{ route('emp.inbound-liaison.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-sky-50 group/item transition-colors">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-envelope-circle-check text-sky-600"></i>
                                <span class="font-bold text-slate-700">Liaison Registration</span>
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
                    <a href="{{ route('emp.communications.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-indigo-50 group/item transition-colors">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-file-pen text-indigo-600"></i>
                            <span class="font-bold text-slate-700">New Request / History</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-slate-300 group-hover/item:text-indigo-600 group-hover/item:translate-x-1 transition-all"></i>
                    </a>

                    <a href="{{ route('emp.outbound-tasks.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-rose-50 group/item transition-colors">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-tasks text-rose-600"></i>
                            <span class="font-bold text-slate-700">Action Tasks</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-slate-300 group-hover/item:text-rose-600 group-hover/item:translate-x-1 transition-all"></i>
                    </a>

                    @php $isLM = \App\Models\Department::where('line_manager_id', Auth::user()->employee->employee_id ?? 0)->exists(); @endphp
                    @if($isLM)
                        <a href="{{ route('emp.lm.communications.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 hover:bg-teal-50 group/item transition-colors">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-user-tie text-teal-600"></i>
                                <span class="font-bold text-slate-700">Team Review Queue</span>
                            </div>
                            <i class="fa-solid fa-chevron-right text-slate-300 group-hover/item:text-teal-600 group-hover/item:translate-x-1 transition-all"></i>
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
