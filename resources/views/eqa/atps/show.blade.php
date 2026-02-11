@extends('layouts.app')

@section('title', 'Provider Details')
@section('subtitle', $atp->atp_ref)

@section('content')
    <div class="flex flex-col gap-8 animate-fade-in-up">
        <!-- Breadcrumbs & Quick Actions -->
        <div class="flex justify-between items-center px-1">
            <div class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                <a href="{{ route('eqa.atps.index') }}" class="hover:text-indigo-600 transition-all">Training Providers</a>
                <i class="fa-solid fa-chevron-right text-[8px] opacity-30"></i>
                <span class="text-slate-600">{{ $atp->atp_ref }}</span>
            </div>
            
            <div class="flex gap-2">
                @if($atp->atp_status_id != 4)
                <form action="{{ route('eqa.atps.accredit', $atp->atp_id) }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Are you sure you want to accredit this Training Provider?')" class="flex items-center gap-2 px-5 py-2.5 bg-emerald-500 border border-emerald-400 rounded-xl text-white font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 hover:shadow-lg transition-all active:scale-95">
                        <i class="fa-solid fa-certificate"></i>
                        <span>Accredit ATP</span>
                    </button>
                </form>
                @endif
                <form action="{{ route('eqa.atps.send_email', $atp->atp_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-slate-600 font-black text-[10px] uppercase tracking-widest hover:bg-slate-50 hover:border-slate-300 hover:shadow-sm transition-all active:scale-95">
                        <i class="fa-solid fa-paper-plane text-indigo-500"></i>
                        <span>Send Registration Email</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Header Info Card -->
        <div class="premium-card relative overflow-hidden bg-gradient-brand border-none p-0 group shadow-2xl shadow-brand/20">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-80 h-80 bg-white/10 rounded-full -mr-40 -mt-40 blur-[80px] group-hover:bg-white/20 transition-all duration-700"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-white/5 rounded-full -ml-20 -mb-20 blur-[60px] group-hover:bg-white/10 transition-all duration-700"></div>

            <div class="relative p-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-8">
                <div class="flex items-center gap-8">
                    <div class="w-24 h-24 rounded-[2rem] bg-white/10 backdrop-blur-xl flex items-center justify-center text-white border border-white/20 shadow-2xl group-hover:scale-105 transition-transform duration-500">
                        <i class="fa-solid fa-building-columns text-4xl text-white"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-4">
                            <span class="text-[10px] font-black text-white uppercase tracking-[0.2em] bg-white/10 px-3 py-1 rounded-lg border border-white/20">
                                {{ $atp->emirate->city_name ?? '-' }} Emirates
                            </span>
                            <span class="w-1.5 h-1.5 rounded-full bg-white/20"></span>
                            <span class="text-[10px] font-black text-white/60 uppercase tracking-[0.2em]">Ref: {{ $atp->atp_ref }}</span>
                        </div>
                        <h1 class="text-5xl font-black font-display text-white mt-3 tracking-tight">{{ $atp->atp_name }}</h1>
                        <div class="flex flex-wrap gap-8 mt-5 text-[11px] font-black text-white/70 uppercase tracking-widest">
                            <span class="flex items-center gap-3"><i class="fa-regular fa-user text-white/50 text-sm"></i> {{ $atp->contact_name }}</span>
                            <span class="flex items-center gap-3"><i class="fa-regular fa-envelope text-white/50 text-sm"></i> {{ $atp->atp_email }}</span>
                            <span class="flex items-center gap-3"><i class="fa-solid fa-phone text-white/50 text-sm"></i> {{ $atp->atp_phone }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col items-end gap-4 self-stretch lg:self-auto min-w-[200px]">
                    <div class="px-6 py-2.5 rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] border transition-all duration-500
                        {{ $atp->atp_status_id == 1 
                            ? 'bg-amber-400 text-amber-950 border-amber-300 shadow-[0_0_30px_rgba(251,191,36,0.3)]' 
                            : 'bg-emerald-400 text-emerald-950 border-emerald-300 shadow-[0_0_30px_rgba(52,211,153,0.3)]' }}">
                        {{ $atp->status->atp_status_name ?? 'New Provider' }}
                    </div>
                    <div class="text-[10px] font-black text-white/40 uppercase tracking-[0.15em] text-right leading-relaxed">
                        Added by <span class="text-white/80">{{ $atp->adder->first_name ?? 'System Admin' }}</span><br>
                        On {{ date('d M, Y', strtotime($atp->added_date)) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex flex-col gap-6">
            <div class="premium-card p-2 w-fit">
                <nav class="flex gap-2" aria-label="Tabs">
                    <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'planner']) }}"
                        class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all whitespace-nowrap flex items-center gap-2
                        {{ $tab == 'planner' ? 'premium-button bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark hover:bg-slate-50' }}">
                        <i class="fa-solid fa-calendar-check text-sm"></i>
                        <span>Visit Planner</span>
                    </a>
                    <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'visit']) }}"
                        class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all whitespace-nowrap flex items-center gap-2
                        {{ $tab == 'visit' ? 'premium-button bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark hover:bg-slate-50' }}">
                        <i class="fa-solid fa-clipboard-list text-sm"></i>
                        <span>EQA Visit</span>
                    </a>
                    <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'interim']) }}"
                        class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all whitespace-nowrap flex items-center gap-2
                        {{ $tab == 'interim' ? 'premium-button bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark hover:bg-slate-50' }}">
                        <i class="fa-solid fa-hourglass-half text-sm"></i>
                        <span>Interim</span>
                    </a>
                    <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'logs']) }}"
                        class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all whitespace-nowrap flex items-center gap-2
                        {{ $tab == 'logs' ? 'premium-button bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark hover:bg-slate-50' }}">
                        <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                        <span>Audit Logs</span>
                    </a>
                </nav>
            </div>

            <!-- Dynamic Content Area -->
            <div class="premium-card p-0 border-none shadow-[0_20px_50px_rgba(0,79,104,0.1)] min-h-[500px] overflow-visible mt-2">
                @if($tab == 'planner')
                    <div class="p-10 animate-fade-in">@include('eqa.atps.tabs.planner')</div>
                @elseif($tab == 'visit')
                    <div class="p-10 animate-fade-in">@include('eqa.atps.tabs.visit')</div>
                @elseif($tab == 'interim')
                    <div class="p-10 animate-fade-in">@include('eqa.atps.tabs.interim')</div>
                @elseif($tab == 'logs')
                    <div class="p-10 animate-fade-in">@include('eqa.atps.tabs.logs')</div>
                @endif
            </div>
        </div>
    </div>
@endsection
