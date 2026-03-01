@extends('layouts.app')

@section('title', 'Project: ' . $project->project_name)
@section('subtitle', $project->project_ref . ' · ' . ($project->plan->plan_title ?? 'No Strategic Plan'))

@section('content')
<div x-data="{
        activeTab: 'overview',
        linkKpiOpen: false,
        addMsOpen: false, addMsKpiId: 0, addMsKpiTitle: '',
        editOpen: false,
    }"
     class="space-y-6 animate-fade-in-up">

    {{-- ── MODALS ── --}}

    {{-- Link KPI Modal --}}
    <div x-show="linkKpiOpen" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,0.5); backdrop-filter:blur(4px);">
        <div @click.outside="linkKpiOpen = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6"
             style="border:1.5px solid rgba(0,79,104,0.12);">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-premium flex items-center gap-2">
                    <i class="fa-solid fa-link text-brand-dark text-base"></i> Link a KPI
                </h3>
                <button @click="linkKpiOpen = false" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
            </div>
            <form action="{{ route('emp.ext.strategies.projects.kpis.store', $project->project_id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Select KPI from Strategic Plan</label>
                    <select name="kpi_id" required
                            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark transition-colors">
                        <option value="">— choose a KPI —</option>
                        @foreach($availableKpis as $kpi)
                            <option value="{{ $kpi->kpi_id }}">
                                [{{ $kpi->kpi_code }}] {{ $kpi->kpi_title }}
                                @if($kpi->objective) — {{ Str::limit($kpi->objective->objective_title, 40) }}@endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="linkKpiOpen = false"
                            class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-500 hover:bg-slate-100 transition-colors">Cancel</button>
                    <button type="submit" class="premium-button px-5 py-2 text-sm">
                        <i class="fa-solid fa-link text-xs"></i> Link KPI
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Milestone Modal --}}
    <div x-show="addMsOpen" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,0.5); backdrop-filter:blur(4px);">
        <div @click.outside="addMsOpen = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6"
             style="border:1.5px solid rgba(0,79,104,0.12);">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-premium flex items-center gap-2">
                    <i class="fa-solid fa-flag text-amber-500 text-base"></i> Add Milestone
                </h3>
                <button @click="addMsOpen = false" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
            </div>
            <p class="text-xs text-slate-400 mb-4 -mt-2">
                Linked KPI: <span class="font-bold text-slate-600" x-text="addMsKpiTitle"></span>
            </p>
            <form :action="`{{ url('emp/ext/strategies/projects/view/' . $project->project_id . '/milestones') }}`" method="POST">
                @csrf
                <input type="hidden" name="kpi_id" :value="addMsKpiId">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Milestone Title *</label>
                        <input type="text" name="milestone_title" required placeholder="e.g. Complete Training Report"
                               class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Description</label>
                        <textarea name="milestone_description" rows="2" placeholder="Optional notes..."
                                  class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark resize-none transition-colors"></textarea>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Weight (%)</label>
                            <input type="number" name="milestone_weight" min="0" max="100" value="0" required
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Start Date</label>
                            <input type="date" name="start_date" required
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">End Date</label>
                            <input type="date" name="end_date" required
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark transition-colors">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="addMsOpen = false"
                            class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-500 hover:bg-slate-100 transition-colors">Cancel</button>
                    <button type="submit" class="premium-button px-5 py-2 text-sm">
                        <i class="fa-solid fa-flag text-xs"></i> Add Milestone
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Project Modal --}}
    <div x-show="editOpen" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,0.5); backdrop-filter:blur(4px);">
        <div @click.outside="editOpen = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto"
             style="border:1.5px solid rgba(0,79,104,0.12);">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-premium flex items-center gap-2">
                    <i class="fa-solid fa-pen text-brand-dark text-base"></i> Edit Project
                </h3>
                <button @click="editOpen = false" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
            </div>
            <form action="{{ route('emp.ext.strategies.projects.update', $project->project_id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Project Name *</label>
                        <input type="text" name="project_name" value="{{ $project->project_name }}" required
                               class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Description *</label>
                        <textarea name="project_description" rows="3" required
                                  class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark resize-none transition-colors">{{ $project->project_description }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Start Date</label>
                            <input type="date" name="project_start_date" value="{{ $project->project_start_date }}"
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">End Date</label>
                            <input type="date" name="project_end_date" value="{{ $project->project_end_date }}"
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Duration / Period</label>
                        <input type="text" name="project_period" value="{{ $project->project_period }}" placeholder="e.g. Q1 2025"
                               class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Project Analysis</label>
                        <textarea name="project_analysis" rows="2"
                                  class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark resize-none transition-colors">{{ $project->project_analysis }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Recommendations</label>
                        <textarea name="project_recommendations" rows="2"
                                  class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark resize-none transition-colors">{{ $project->project_recommendations }}</textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="editOpen = false"
                            class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-500 hover:bg-slate-100 transition-colors">Cancel</button>
                    <button type="submit" class="premium-button px-5 py-2 text-sm">
                        <i class="fa-solid fa-save text-xs"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── HEADER ── --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-400 mb-2">
                <a href="{{ route('emp.ext.strategies.projects.index') }}"
                   class="hover:text-brand-dark transition-colors flex items-center gap-1.5 font-semibold">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Operational Projects
                </a>
                <i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
                <span class="text-slate-500">{{ $project->project_ref }}</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-display font-bold text-premium">{{ $project->project_name }}</h1>
            <div class="flex flex-wrap items-center gap-2 mt-2">
                <span class="{{ $project->project_status_id == 1 ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700' }}
                              text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full">
                    {{ $project->project_status_id == 1 ? 'Draft' : 'Published' }}
                </span>
                <span class="text-xs text-slate-400 flex items-center gap-1.5 border-l pl-3 border-slate-200">
                    <i class="fa-solid fa-building text-[10px]"></i>
                    {{ $project->department->department_name ?? '—' }}
                </span>
                <span class="text-xs text-slate-400 flex items-center gap-1.5 border-l pl-3 border-slate-200">
                    <i class="fa-solid fa-chess-knight text-[10px]" style="color:#004F68;"></i>
                    {{ $project->plan->plan_title ?? 'No Strategic Plan' }}
                </span>
            </div>
        </div>
        <div class="flex gap-2 flex-shrink-0">
            @if($project->project_status_id == 1)
                <form action="{{ route('emp.ext.strategies.projects.publish', $project->project_id) }}" method="POST" id="publishProjectForm">
                    @csrf
                    <button type="button" onclick="confirmProjectPublish()"
                        style="background:linear-gradient(135deg,#10b981,#059669); color:#fff; border-radius:12px; padding:0.6rem 1.2rem; border:none; cursor:pointer; font-weight:600; display:inline-flex; align-items:center; gap:0.5rem; box-shadow:0 4px 6px rgba(16,185,129,0.2);">
                        <i class="fa-solid fa-upload text-xs"></i> Publish Project
                    </button>
                </form>
                <button @click="editOpen = true" class="premium-button px-4 py-2 text-sm">
                    <i class="fa-solid fa-pen text-xs"></i> Edit Project
                </button>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-2xl text-emerald-700 text-sm font-semibold"
             style="background:linear-gradient(135deg,#f0fdf4,#dcfce7); border:1.5px solid rgba(16,185,129,0.2);">
            <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif

    {{-- ── TABS ── --}}
    <div class="border-b border-slate-200">
        <nav class="-mb-px flex space-x-0 overflow-x-auto" aria-label="Project Tabs">
            @php
                $tabs = [
                    'overview'  => ['Overview',         'fa-circle-info'],
                    'analysis'  => ['Analysis & Recs',  'fa-magnifying-glass-chart'],
                    'kpis'      => ['KPIs & Milestones', 'fa-chart-line'],
                ];
            @endphp
            @foreach($tabs as $tab => [$label, $icon])
                <button @click="activeTab = '{{ $tab }}'"
                    :class="activeTab === '{{ $tab }}' ? 'font-bold' : 'text-slate-500 hover:text-slate-700'"
                    :style="activeTab === '{{ $tab }}' ? 'color:#004F68; border-bottom: 2px solid #004F68;' : 'border-bottom: 2px solid transparent;'"
                    class="whitespace-nowrap py-4 px-4 text-sm transition-all flex items-center gap-1.5">
                    <i class="fa-solid {{ $icon }} text-xs"></i> {{ $label }}
                </button>
            @endforeach
        </nav>
    </div>

    {{-- ── TAB: OVERVIEW ── --}}
    <div x-show="activeTab === 'overview'" class="space-y-6">
        <div class="premium-card p-6">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                <i class="fa-solid fa-align-left"></i> Project Description
            </h3>
            <p class="text-slate-700 leading-relaxed">{{ $project->project_description ?? 'No description provided.' }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Timeline --}}
            <div class="premium-card p-6">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                         style="background:linear-gradient(145deg,#6366f1,#4f46e5); box-shadow:0 4px 12px rgba(99,102,241,0.3);">
                        <i class="fa-solid fa-calendar text-white text-xs"></i>
                    </div>
                    <h3 class="font-bold text-slate-700">Timeline</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-slate-50">
                        <span class="text-xs text-slate-400 uppercase font-bold">Start Date</span>
                        <span class="font-bold text-slate-700 text-sm">{{ $project->project_start_date }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-50">
                        <span class="text-xs text-slate-400 uppercase font-bold">End Date</span>
                        <span class="font-bold text-slate-700 text-sm">{{ $project->project_end_date }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-xs text-slate-400 uppercase font-bold">Duration</span>
                        <span class="font-bold text-sm" style="color:#004F68;">{{ $project->project_period ?? '—' }}</span>
                    </div>
                </div>
            </div>

            {{-- Alignment --}}
            <div class="premium-card p-6">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                         style="background:linear-gradient(145deg,#10b981,#059669); box-shadow:0 4px 12px rgba(16,185,129,0.3);">
                        <i class="fa-solid fa-chess-knight text-white text-xs"></i>
                    </div>
                    <h3 class="font-bold text-slate-700">Strategic Alignment</h3>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block uppercase font-bold mb-1">Linked Strategic Plan</span>
                    <p class="font-bold text-slate-700">{{ $project->plan->plan_title ?? 'N/A' }}</p>
                    @if($project->plan)
                        <span class="text-xs text-slate-400 mt-1 block">
                            {{ $project->plan->plan_from }} – {{ $project->plan->plan_to }}
                        </span>
                    @endif
                </div>
                <div class="mt-4 pt-4 border-t border-slate-50 grid grid-cols-2 gap-3">
                    <div class="text-center p-3 rounded-xl" style="background:rgba(14,165,233,0.08);">
                        <p class="text-2xl font-black" style="color:#0284c7;">{{ $project->kpis->count() }}</p>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#0ea5e9;">Linked KPIs</p>
                    </div>
                    <div class="text-center p-3 rounded-xl" style="background:rgba(245,158,11,0.08);">
                        <p class="text-2xl font-black" style="color:#d97706;">{{ $project->milestones->count() }}</p>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#f59e0b;">Milestones</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── TAB: ANALYSIS & RECS ── --}}
    <div x-show="activeTab === 'analysis'" style="display:none;" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="premium-card p-6">
            <h3 class="text-xs font-bold text-amber-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                <i class="fa-solid fa-magnifying-glass"></i> Project Analysis
            </h3>
            <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $project->project_analysis ?? 'No analysis provided.' }}</p>
        </div>
        <div class="premium-card p-6">
            <h3 class="text-xs font-bold text-blue-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                <i class="fa-solid fa-lightbulb"></i> Recommendations
            </h3>
            <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $project->project_recommendations ?? 'No recommendations provided.' }}</p>
        </div>
    </div>

    {{-- ── TAB: KPIs & MILESTONES ── --}}
    <div x-show="activeTab === 'kpis'" style="display:none;">

        <div class="flex justify-between items-center mb-5">
            <h3 class="text-base font-bold text-slate-700">Linked KPIs & Milestones</h3>
            @if($project->project_status_id == 1)
            <button @click="linkKpiOpen = true" class="premium-button px-4 py-2 text-sm">
                <i class="fa-solid fa-plus text-xs"></i> Link KPI
            </button>
            @endif
        </div>

        @forelse($project->kpis as $pk)
        @php $linkedKpi = $pk->linkedKpi; @endphp
        <div class="premium-card mb-5 overflow-hidden">
            {{-- KPI Header --}}
            <div class="flex justify-between items-start p-5 border-b border-slate-50"
                 style="background:linear-gradient(135deg,rgba(14,165,233,0.04) 0%,#fff 100%);">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-mono text-[10px] font-bold px-2 py-0.5 rounded-lg"
                              style="background:rgba(14,165,233,0.1); color:#0284c7;">
                            {{ $linkedKpi->kpi_code ?? 'KPI' }}
                        </span>
                        @if($linkedKpi?->theme)
                        <span class="text-[10px] bg-purple-50 text-purple-600 px-2 py-0.5 rounded font-bold">
                            {{ Str::limit($linkedKpi->theme->theme_title, 30) }}
                        </span>
                        @endif
                    </div>
                    <p class="font-bold text-slate-700">{{ $linkedKpi->kpi_title ?? 'Unknown KPI' }}</p>
                    @if($linkedKpi?->objective)
                    <p class="text-xs text-slate-400 mt-0.5">{{ $linkedKpi->objective->objective_title }}</p>
                    @endif
                </div>
                @if($project->project_status_id == 1)
                <button
                    @click="addMsOpen = true; addMsKpiId = {{ $linkedKpi->kpi_id ?? 0 }}; addMsKpiTitle = '{{ addslashes($linkedKpi->kpi_title ?? '') }}'"
                    class="premium-button px-3 py-1.5 text-xs flex-shrink-0 ml-4">
                    <i class="fa-solid fa-plus text-xs"></i> Milestone
                </button>
                @endif
            </div>

            {{-- Milestones for this KPI --}}
            @php $kpiMilestones = $pk->milestones; @endphp
            @if($kpiMilestones->count())
            <div class="px-5 py-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">Milestones</p>
                <div class="space-y-2">
                    @foreach($kpiMilestones as $ms)
                    <div class="flex items-center gap-3 bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                        <i class="fa-solid fa-flag text-amber-400 text-[10px] flex-shrink-0"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-700 truncate">{{ $ms->milestone_title }}</p>
                            @if($ms->milestone_description)
                                <p class="text-xs text-slate-400 mt-0.5 truncate">{{ $ms->milestone_description }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 text-xs text-slate-400 flex-shrink-0">
                            <span class="font-bold px-2 py-0.5 rounded-full"
                                  style="background:rgba(245,158,11,0.1); color:#d97706;">
                                W: {{ $ms->milestone_weight }}%
                            </span>
                            <span>{{ $ms->start_date }} → {{ $ms->end_date }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="px-5 py-4 text-center">
                <p class="text-xs text-slate-400 italic">No milestones added yet for this KPI.</p>
                @if($project->project_status_id == 1)
                <button
                    @click="addMsOpen = true; addMsKpiId = {{ $linkedKpi->kpi_id ?? 0 }}; addMsKpiTitle = '{{ addslashes($linkedKpi->kpi_title ?? '') }}'"
                    class="mt-1 text-xs text-indigo-500 hover:text-indigo-700 font-semibold">
                    + Add first milestone
                </button>
                @endif
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-slate-200">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4"
                 style="background:linear-gradient(135deg,rgba(14,165,233,0.08),rgba(14,165,233,0.04));">
                <i class="fa-solid fa-chart-line text-2xl" style="color:#0ea5e9; opacity:0.5;"></i>
            </div>
            <p class="text-slate-500 font-bold mb-1">No KPIs Linked</p>
            <p class="text-xs text-slate-400 mb-4">Link KPIs from the strategic plan to track performance.</p>
            @if($project->project_status_id == 1)
            <button @click="linkKpiOpen = true; activeTab = 'kpis'" class="premium-button px-5 py-2 text-sm">
                <i class="fa-solid fa-plus text-xs"></i> Link First KPI
            </button>
            @endif
        </div>
        @endforelse

    </div>

</div>

@push('scripts')
<script>
function confirmProjectPublish() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Publish this project?',
            text: 'Once published, the project will be locked for editing.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Publish',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#059669',
            cancelButtonColor: '#6b7280',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('publishProjectForm').submit();
            }
        });
    } else {
        if (confirm('Publish this project? This action cannot be undone.')) {
            document.getElementById('publishProjectForm').submit();
        }
    }
}
</script>
@endpush
@endsection
