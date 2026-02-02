@extends('layouts.app')

@section('title', 'Operational Projects')
@section('subtitle', 'Manage department projects and operations')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <div class="flex justify-end">
            <button class="premium-button px-6 py-2">
                <i class="fa-solid fa-plus"></i>
                <span>Create New Project</span>
            </button>
        </div>

        @forelse($projects as $project)
            <div
                class="premium-card p-6 hover:shadow-lg transition-shadow border-l-4 {{ $project->project_status_id == 1 ? 'border-amber-400' : 'border-emerald-500' }}">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-display font-bold text-premium">{{ $project->project_name }}</h2>
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            <span
                                class="px-2 py-1 rounded text-[10px] uppercase font-bold tracking-wider {{ $project->project_status_id == 1 ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' }}">
                                {{ $project->project_status_id == 1 ? 'Draft' : 'Active' }}
                            </span>
                            <span class="text-slate-400 text-xs flex items-center gap-1">
                                <i class="fa-solid fa-building"></i>
                                {{ $project->department->department_name ?? 'Department' }}
                            </span>
                            <span class="text-slate-400 text-xs flex items-center gap-1 border-l pl-2 border-slate-200">
                                <i class="fa-solid fa-chess-knight"></i>
                                {{ $project->plan->plan_title ?? 'No Linked Plan' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <a href="#" class="premium-button-secondary px-4 py-2 text-xs">
                            Manage Project
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 py-4 border-t border-slate-50 text-sm">
                    <div class="flex justify-between p-3 bg-slate-50 rounded-lg">
                        <span class="text-slate-400 text-xs uppercase font-bold">Start Date</span>
                        <span class="font-bold text-slate-700">{{ $project->project_start_date }}</span>
                    </div>
                    <div class="flex justify-between p-3 bg-slate-50 rounded-lg">
                        <span class="text-slate-400 text-xs uppercase font-bold">End Date</span>
                        <span class="font-bold text-slate-700">{{ $project->project_end_date }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <i class="fa-solid fa-briefcase text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-500">No Projects Found</h3>
                <p class="text-slate-400 text-sm mt-1">Start by creating a new operational project.</p>
            </div>
        @endforelse

        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    </div>
@endsection
