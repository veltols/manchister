@extends('layouts.app')

@section('title', 'Self Studies')
@section('subtitle', 'Strategic self-assessment studies')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <div class="flex justify-end">
            <button class="premium-button px-6 py-2">
                <i class="fa-solid fa-plus"></i>
                <span>Create New Study</span>
            </button>
        </div>

        @forelse($studies as $study)
            <div
                class="premium-card p-6 hover:shadow-lg transition-shadow border-l-4 {{ $study->study_status_id == 1 ? 'border-amber-400' : 'border-indigo-500' }}">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                    <div>
                        <h2 class="text-xl font-display font-bold text-premium">{{ $study->study_title }}</h2>
                        <div class="flex items-center gap-2 mt-2">
                            <span
                                class="px-2 py-1 rounded text-[10px] uppercase font-bold tracking-wider bg-slate-100 text-slate-500">
                                Ref: {{ $study->study_ref }}
                            </span>
                            <span
                                class="px-2 py-1 rounded text-[10px] uppercase font-bold tracking-wider {{ $study->study_status_id == 1 ? 'bg-amber-50 text-amber-600' : 'bg-indigo-50 text-indigo-600' }}">
                                {{ $study->study_status_id == 1 ? 'In Progress' : 'Published' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <a href="#" class="premium-button-secondary px-4 py-2 text-xs">
                            {{ $study->study_status_id == 1 ? 'Edit Study' : 'Study Overview' }}
                        </a>
                    </div>
                </div>

                <div class="prose prose-sm text-slate-500 max-w-none bg-slate-50 p-4 rounded-xl">
                    {{ Str::limit($study->study_overview, 200) }}
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <i class="fa-solid fa-book-open text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-500">No Studies Found</h3>
                <p class="text-slate-400 text-sm mt-1">Begin a new strategic self-study.</p>
            </div>
        @endforelse

        <div class="mt-4">
            {{ $studies->links() }}
        </div>
    </div>
@endsection
