@extends('layouts.app')

@section('title', 'Study Details')
@section('subtitle', $study->study_title ?? 'Study Overview')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                    <a href="{{ route('emp.ext.strategies.self_studies.index') }}"
                        class="hover:text-indigo-600 transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Back to Studies
                    </a>
                </div>
                <h1 class="text-3xl font-display font-bold text-premium">{{ $study->study_title }}</h1>
                <div class="flex items-center gap-2 mt-2">
                    <span
                        class="px-2 py-1 rounded text-[10px] uppercase font-bold tracking-wider {{ $study->study_status_id == 1 ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' }}">
                        {{ $study->study_status_id == 1 ? 'Draft' : 'Published' }}
                    </span>
                    <span class="text-slate-400 text-xs flex items-center gap-1 border-l pl-2 border-slate-300">
                        <i class="fa-solid fa-hashtag"></i>
                        {{ $study->study_ref }}
                    </span>
                </div>
            </div>
            <div class="flex gap-2">
                @if($study->study_status_id == 1)
                    <button class="premium-button-secondary px-4 py-2">
                        <i class="fa-solid fa-upload"></i>
                        <span>Publish Study</span>
                    </button>
                @endif
                <button class="premium-button px-4 py-2">
                    <i class="fa-solid fa-pen text-sm"></i>
                    <span>Edit Study</span>
                </button>
            </div>
        </div>

        <div class="premium-card p-8">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Study Overview</h3>
            <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed">
                {!! nl2br(e($study->study_overview)) !!}
            </div>
        </div>

    </div>
@endsection
