@extends('layouts.app')

@section('title', 'Provider Details')
@section('subtitle', $atp->atp_ref)

@section('content')
    <div class="flex flex-col gap-6 animate-fade-in-up">

        <!-- Header Info Card -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-start">
            <div>
                <span class="text-xs font-black text-slate-300 uppercase tracking-widest">{{ $atp->atp_emirate }}</span>
                <h1 class="text-3xl font-black font-display text-premium mt-1">{{ $atp->atp_name }}</h1>
                <div class="flex gap-6 mt-4 text-sm text-slate-500">
                    <span class="flex items-center gap-2"><i class="fa-regular fa-user"></i> {{ $atp->contact_name }}</span>
                    <span class="flex items-center gap-2"><i class="fa-regular fa-envelope"></i>
                        {{ $atp->atp_email }}</span>
                    <span class="flex items-center gap-2"><i class="fa-solid fa-phone"></i> {{ $atp->atp_phone }}</span>
                </div>
            </div>
            <div class="flex flex-col items-end gap-3">
                <span class="px-4 py-1.5 rounded-full font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                    {{ $atp->status->status_name ?? 'Active' }}
                </span>
                <div class="text-xs text-slate-400">
                    Added by {{ $atp->adder->first_name ?? 'System' }} on {{ $atp->added_date }}
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="border-b border-slate-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'planner']) }}"
                    class="{{ $tab == 'planner' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Visit Planner
                </a>
                <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'visit']) }}"
                    class="{{ $tab == 'visit' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    EQA Visit
                </a>
                <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'interim']) }}"
                    class="{{ $tab == 'interim' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Interim
                </a>
                <a href="{{ route('eqa.atps.show', ['id' => $atp->atp_id, 'tab' => 'logs']) }}"
                    class="{{ $tab == 'logs' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Logs
                </a>
            </nav>
        </div>

        <!-- Dynamic Content -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 min-h-[300px]">
            @if($tab == 'planner')
                @include('eqa.atps.tabs.planner')
            @elseif($tab == 'visit')
                @include('eqa.atps.tabs.visit')
            @elseif($tab == 'interim')
                @include('eqa.atps.tabs.interim')
            @elseif($tab == 'logs')
                <div class="text-center text-slate-400">
                    <i class="fa-solid fa-history text-4xl mb-4"></i>
                    <p>Audit Logs Feature Coming Soon</p>
                </div>
            @endif
        </div>
    </div>
@endsection
