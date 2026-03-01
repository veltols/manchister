@extends('layouts.app')

@section('title', 'New Self Study')
@section('subtitle', 'Create a new strategic self-assessment study')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('emp.ext.strategies.self_studies.index') }}"
           class="hover:text-brand-dark transition-colors flex items-center gap-1.5 font-semibold">
            <i class="fa-solid fa-arrow-left text-xs"></i> Self Studies
        </a>
        <i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
        <span class="text-slate-500">New Study</span>
    </div>

    <div class="bg-white rounded-2xl p-8"
         style="border:1.5px solid rgba(0,79,104,0.1); box-shadow:0 4px 16px rgba(0,79,104,0.06);">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                 style="background:linear-gradient(145deg,#6366f1,#4f46e5); box-shadow:0 4px 12px rgba(99,102,241,0.3);">
                <i class="fa-solid fa-book-open text-white text-sm"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-premium">Create Self Study</h2>
                <p class="text-xs text-slate-400">Fill in the study details below</p>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-5 p-4 rounded-xl text-red-700 text-sm" style="background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.2);">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('emp.ext.strategies.self_studies.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                    Study Title <span class="text-red-400">*</span>
                </label>
                <input type="text" name="study_title" value="{{ old('study_title') }}"
                       placeholder="e.g. Digital Transformation Self-Assessment 2025"
                       required
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-dark transition-colors">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                    Study Overview <span class="text-red-400">*</span>
                </label>
                <textarea name="study_overview" rows="6" required
                          placeholder="Provide an overview of what this study covers, its purpose and scope..."
                          class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-dark resize-none transition-colors">{{ old('study_overview') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('emp.ext.strategies.self_studies.index') }}"
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-500 hover:bg-slate-100 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="premium-button px-6 py-2.5 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk text-xs"></i> Create Study
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
