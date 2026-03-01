@extends('layouts.app')

@section('title', 'Self Studies')
@section('subtitle', 'Strategic self-assessment studies')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-display font-bold text-premium">Self Studies</h1>
            <p class="text-sm text-slate-500 mt-1">Strategic self-assessment and research studies</p>
        </div>
        <a href="{{ route('emp.ext.strategies.self_studies.create') }}"
           class="premium-button px-5 py-2.5 flex items-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>New Study</span>
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-2xl text-emerald-700 text-sm font-semibold"
             style="background:linear-gradient(135deg,#f0fdf4,#dcfce7); border:1.5px solid rgba(16,185,129,0.2);">
            <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Studies Grid --}}
    @forelse($studies as $study)
    <div class="bg-white rounded-2xl overflow-hidden border transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
         style="border:1.5px solid rgba(0,79,104,0.1); box-shadow:0 4px 16px rgba(0,79,104,0.06);">

        <div class="p-6">
            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="font-mono text-[10px] font-bold px-2 py-0.5 rounded-lg"
                              style="background:rgba(0,79,104,0.08); color:#004F68;">
                            {{ $study->study_ref }}
                        </span>
                        <span class="text-[10px] text-slate-400">
                            {{ \Carbon\Carbon::parse($study->added_date)->format('d M Y') }}
                        </span>
                    </div>
                    <h2 class="text-lg font-display font-bold text-premium mb-2">{{ $study->study_title }}</h2>
                    <p class="text-sm text-slate-500 leading-relaxed line-clamp-2">
                        {{ Str::limit($study->study_overview, 150) }}
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('emp.ext.strategies.self_studies.show', $study->study_id) }}"
                       class="premium-button px-4 py-2 text-xs flex items-center gap-1.5">
                        <span>Manage Study</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
        <div class="text-center py-20 bg-white rounded-2xl border-2 border-dashed border-slate-200">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                 style="background:linear-gradient(135deg,rgba(99,102,241,0.08),rgba(99,102,241,0.04));">
                <i class="fa-solid fa-book-open text-2xl" style="color:#6366f1; opacity:0.5;"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-500 mb-1">No Self Studies Yet</h3>
            <p class="text-slate-400 text-sm mb-4">Begin a strategic self-assessment study.</p>
            <a href="{{ route('emp.ext.strategies.self_studies.create') }}" class="premium-button px-5 py-2 inline-flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Create First Study
            </a>
        </div>
    @endforelse

    @if($studies->hasPages())
    <div class="mt-4">{{ $studies->links() }}</div>
    @endif
</div>
@endsection
