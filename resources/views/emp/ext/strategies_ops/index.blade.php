@extends('layouts.app')

@section('title', 'Operational Projects')
@section('subtitle', 'Manage department projects aligned to strategic plans')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-display font-bold text-premium">Operational Projects</h1>
            <p class="text-sm text-slate-500 mt-1">Department project portfolio linked to strategic plans</p>
        </div>
        <a href="{{ route('emp.ext.strategies.projects.create') }}"
           class="premium-button px-5 py-2.5 flex items-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>New Project</span>
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-2xl text-emerald-700 text-sm font-semibold"
             style="background:linear-gradient(135deg,#f0fdf4,#dcfce7); border:1.5px solid rgba(16,185,129,0.2);">
            <i class="fa-solid fa-circle-check text-emerald-500"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Projects Grid --}}
    @forelse($projects as $project)
    <div class="bg-white rounded-2xl overflow-hidden border transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
         style="border:1.5px solid rgba(0,79,104,0.1); box-shadow:0 4px 16px rgba(0,79,104,0.06);">

        {{-- Colored top stripe by status --}}
        <div class="h-1.5 w-full {{ $project->project_status_id == 1 ? '' : '' }}"
             style="{{ $project->project_status_id == 1
                ? 'background:linear-gradient(90deg,#f59e0b,#d97706);'
                : 'background:linear-gradient(90deg,#10b981,#059669);' }}">
        </div>

        <div class="p-6">
            <div class="flex flex-col md:flex-row justify-between items-start gap-4">

                {{-- Left: Info --}}
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="font-mono text-[10px] font-bold px-2 py-0.5 rounded-lg"
                              style="background:rgba(0,79,104,0.08); color:#004F68;">
                            {{ $project->project_ref }}
                        </span>
                        <span class="{{ $project->project_status_id == 1
                            ? 'bg-amber-50 text-amber-600'
                            : 'bg-emerald-50 text-emerald-700' }} text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full">
                            {{ $project->project_status_id == 1 ? '● Draft' : '● Active' }}
                        </span>
                    </div>
                    <h2 class="text-lg font-display font-bold text-premium mb-1">{{ $project->project_name }}</h2>
                    <p class="text-xs text-slate-400 line-clamp-2 mb-3">{{ Str::limit($project->project_description, 120) }}</p>

                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-building text-[10px]"></i>
                            {{ $project->department->department_name ?? '—' }}
                        </span>
                        <span class="flex items-center gap-1 border-l pl-3 border-slate-200">
                            <i class="fa-solid fa-chess-knight text-[10px]" style="color:#004F68;"></i>
                            {{ $project->plan->plan_title ?? 'No Plan Linked' }}
                        </span>
                        <span class="flex items-center gap-1 border-l pl-3 border-slate-200">
                            <i class="fa-solid fa-calendar text-[10px]"></i>
                            {{ $project->project_start_date }} – {{ $project->project_end_date }}
                        </span>
                    </div>
                </div>

                {{-- Right: Stats + Action --}}
                <div class="flex flex-col items-end gap-3 flex-shrink-0">
                    {{-- Mini stat pills --}}
                    <div class="flex items-center gap-2">
                        <span class="flex items-center gap-1 text-[10px] font-bold px-2.5 py-1 rounded-full"
                              style="background:rgba(14,165,233,0.1); color:#0284c7;">
                            <i class="fa-solid fa-chart-line text-[9px]"></i>
                            {{ $project->kpis_count ?? 0 }} KPIs
                        </span>
                        <span class="flex items-center gap-1 text-[10px] font-bold px-2.5 py-1 rounded-full"
                              style="background:rgba(245,158,11,0.1); color:#d97706;">
                            <i class="fa-solid fa-flag text-[9px]"></i>
                            {{ $project->milestones_count ?? 0 }} Milestones
                        </span>
                    </div>
                    <a href="{{ route('emp.ext.strategies.projects.show', $project->project_id) }}"
                       class="premium-button px-4 py-2 text-xs flex items-center gap-1.5">
                        <span>Manage Project</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>
    @empty
        <div class="text-center py-20 bg-white rounded-2xl border-2 border-dashed border-slate-200">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                 style="background:linear-gradient(135deg,rgba(0,79,104,0.08),rgba(0,106,138,0.04));">
                <i class="fa-solid fa-briefcase text-2xl" style="color:#004F68; opacity:0.5;"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-500 mb-1">No Projects Yet</h3>
            <p class="text-slate-400 text-sm mb-4">Create your first operational project linked to a published strategic plan.</p>
            <a href="{{ route('emp.ext.strategies.projects.create') }}" class="premium-button px-5 py-2 inline-flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Create First Project
            </a>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($projects->hasPages())
    <div class="mt-4">{{ $projects->links() }}</div>
    @endif

</div>
@endsection
