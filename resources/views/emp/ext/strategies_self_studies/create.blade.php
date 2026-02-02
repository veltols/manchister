@extends('layouts.app')

@section('title', 'Create Strategic Study')
@section('subtitle', 'Initiate a new self-assessment study')

@section('content')
    <div class="max-w-3xl mx-auto space-y-8 animate-fade-in-up">

        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('emp.ext.strategies.self_studies.index') }}"
                class="text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span class="font-bold text-sm uppercase tracking-wider">Back to List</span>
            </a>
        </div>

        <div class="premium-card p-10">
            <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-50">
                <div class="w-14 h-14 rounded-2xl bg-brand-light/20 flex items-center justify-center text-brand-dark">
                    <i class="fa-solid fa-book-open text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">New Self Study</h2>
                    <p class="text-sm text-slate-500">Document strategic assessments</p>
                </div>
            </div>

            <form action="{{ route('emp.ext.strategies.self_studies.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Study Title</label>
                    <input type="text" name="study_title" class="premium-input w-full"
                        placeholder="e.g. Q1 2026 Departmental Assessment" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Study
                        Overview</label>
                    <textarea name="study_overview" rows="6" class="premium-input w-full"
                        placeholder="Provide an overview of the study scope and objectives..." required></textarea>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="submit" class="premium-button px-10 py-3">
                        <span>Create Study</span>
                        <i class="fa-solid fa-check"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
