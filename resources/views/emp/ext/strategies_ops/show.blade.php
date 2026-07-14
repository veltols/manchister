@extends('layouts.app')

@section('title', 'Project: ' . $project->project_name)
@section('subtitle', $project->project_ref . ' · ' . ($project->plan->plan_title ?? 'No Strategic Plan'))

@section('content')
<div x-data="{
        activeTab: 'overview',
        linkKpiOpen: false,
        linkThemeId: 0,
        linkObjId: 0,
        addMsOpen: false, addMsKpiId: 0, addMsKpiTitle: '',
        addMsWeight: 0, addMsMaxWeight: 0,
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
                <div class="space-y-4">
                    {{-- Fixed Headers --}}
                    <div class="grid grid-cols-2 gap-3 opacity-60">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Project</label>
                            <div class="bg-slate-50 px-3 py-2 rounded-lg text-xs font-semibold text-slate-600 truncate border border-slate-100">
                                {{ $project->project_name }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Plan</label>
                            <div class="bg-slate-50 px-3 py-2 rounded-lg text-xs font-semibold text-slate-600 truncate border border-slate-100">
                                {{ $project->plan?->plan_title ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    {{-- Theme Select --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">1. Strategic Theme</label>
                        <select x-model="linkThemeId" @change="linkObjId = 0"
                                class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark transition-colors">
                            <option value="0">— select theme —</option>
                            @foreach($availableKpis->pluck('theme')->unique('theme_id') as $theme)
                                @if($theme)
                                    <option value="{{ $theme->theme_id }}">{{ $theme->theme_title }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{-- Objective Select --}}
                    <div x-show="linkThemeId != 0" x-transition>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">2. Strategic Objective</label>
                        <select x-model="linkObjId"
                                class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark transition-colors">
                            <option value="0">— select objective —</option>
                            @foreach($availableKpis->pluck('objective')->unique('objective_id') as $obj)
                                @if($obj)
                                    <template x-if="linkThemeId == {{ $obj->theme_id }}">
                                        <option value="{{ $obj->objective_id }}">{{ $obj->objective_title }}</option>
                                    </template>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{-- KPI Select --}}
                    <div x-show="linkObjId != 0" x-transition>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">3. Strategic KPI</label>
                        <select name="kpi_id" required
                                class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark transition-colors">
                            <option value="">— choose a KPI —</option>
                            @foreach($availableKpis as $kpi)
                                <template x-if="linkObjId == {{ $kpi->objective_id }}">
                                    <option value="{{ $kpi->kpi_id }}">[{{ $kpi->kpi_code }}] {{ $kpi->kpi_title }}</option>
                                </template>
                            @endforeach
                        </select>
                    </div>
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
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Owner *</label>
                        <select name="employee_id" required
                                class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark transition-colors">
                            <option value="">— Select Owner —</option>
                            @foreach($managerDepartments as $dept)
                                @if($dept->lineManager)
                                    <optgroup label="{{ $dept->department_name }}">
                                        <option value="{{ $dept->lineManager->employee_id }}">{{ $dept->lineManager->employee_name }}</option>
                                    </optgroup>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 flex justify-between">
                            Weight (%) <span class="text-indigo-600 font-black" x-text="addMsWeight + '%'"></span>
                        </label>
                        <div class="flex flex-col gap-1 bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <input type="range" name="milestone_weight" min="0" :max="addMsMaxWeight" step="5"
                                   x-model="addMsWeight"
                                   class="w-full h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                            <p class="text-[10px] text-slate-400">Available weight: <span x-text="addMsMaxWeight + '%'"></span></p>
                        </div>
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
                    'overview'   => ['Project Overview', 'fa-circle-info'],
                    'plan'       => ['Plan Overview',    'fa-chess-knight'],
                    'kpis'       => ['Linked KPIs',      'fa-link'],
                    'milestones' => ['Milestones',       'fa-flag'],
                    'logs'       => ['Logs',             'fa-clock-rotate-left'],
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

        {{-- Analysis & Recommendations --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
    </div>

    {{-- ── TAB: PLAN OVERVIEW ── --}}
    <div x-show="activeTab === 'plan'" style="display:none;" class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="premium-card p-6 border-t-4 border-indigo-500">
                <h3 class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-3">Plan Vision</h3>
                <p class="text-slate-700 italic font-display">"{{ $project->plan?->plan_vision ?? 'N/A' }}"</p>
            </div>
            <div class="premium-card p-6 border-t-4 border-emerald-500">
                <h3 class="text-xs font-bold text-emerald-500 uppercase tracking-widest mb-3">Plan Mission</h3>
                <p class="text-slate-700">{{ $project->plan?->plan_mission ?? 'N/A' }}</p>
            </div>
            <div class="premium-card p-6 border-t-4 border-slate-400">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Core Values</h3>
                <p class="text-slate-600 whitespace-pre-line">{{ $project->plan?->plan_values ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="premium-card overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100" style="background:#f8fafc;">
                <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-slate-400"></i>
                    Strategic Hierarchy
                </h3>
            </div>
            <div class="p-6">
                @forelse($project->plan->themes ?? [] as $theme)
                    <div class="mb-10 last:mb-0">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold border border-indigo-100">
                                {{ $theme->theme_weight }}%
                            </span>
                            <div>
                                <h4 class="font-bold text-slate-800">{{ $theme->theme_title }}</h4>
                                <p class="text-[10px] text-slate-400 font-mono tracking-widest uppercase">{{ $theme->theme_ref }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 ml-12">
                            @foreach($theme->objectives as $obj)
                                <div class="bg-slate-50/50 rounded-xl p-4 border border-slate-100 hover:border-blue-200 transition-colors">
                                    <p class="text-xs font-bold text-slate-400 mb-1 uppercase tracking-tighter">{{ $obj->objective_ref }}</p>
                                    <p class="text-xs font-bold text-slate-700 mb-3">{{ $obj->objective_title }}</p>
                                    <div class="space-y-1.5 opacity-80">
                                        @foreach($obj->kpis as $k)
                                            <div class="text-[10px] text-slate-500 flex gap-2">
                                                <i class="fa-solid fa-chart-line text-blue-400 mt-0.5"></i>
                                                <span>{{ $k->kpi_title }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-center text-slate-400 italic py-10">No hierarchical data available for this plan.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── TAB: LINKED KPIs ── --}}
    <div x-show="activeTab === 'kpis'" style="display:none;" class="space-y-6">
        <div class="flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-700 flex items-center gap-2">
                <i class="fa-solid fa-link text-indigo-500"></i> Linked Strategic KPIs
            </h3>
            @if($project->project_status_id == 1)
                <button @click="linkKpiOpen = true" class="premium-button px-4 py-2 text-sm">
                    <i class="fa-solid fa-plus text-xs"></i> Link KPI
                </button>
            @endif
        </div>

        <div class="premium-card overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Plan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Theme</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Objective</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">KPI</th>
                        @if($project->project_status_id == 1)
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($project->kpis as $pk)
                        @php 
                            $lk = $pk->linkedKpi; 
                            $kpiMsWeight = $project->milestones->where('kpi_id', $lk?->kpi_id)->sum('milestone_weight');
                            $availableWeight = max(0, 100 - $kpiMsWeight);
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-xs font-semibold text-slate-600 truncate block max-w-[120px]">{{ $project->plan?->plan_title ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-slate-500 font-medium">{{ $lk?->theme?->theme_title ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-slate-500">{{ $lk?->objective?->objective_title ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700">{{ $lk?->kpi_title ?? '—' }}</span>
                                    <span class="text-[10px] text-indigo-400 font-mono">{{ $lk?->kpi_code ?? '—' }}</span>
                                </div>
                            </td>
                            @if($project->project_status_id == 1)
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-3">
                                        @if($availableWeight > 0)
                                        <button @click="addMsOpen = true; addMsKpiId = {{ $lk?->kpi_id ?? 0 }}; addMsKpiTitle = '{{ addslashes($lk?->kpi_title ?? '') }}'; addMsMaxWeight = {{ $availableWeight }}"
                                            class="text-amber-500 hover:text-amber-700 transition-colors" title="Add Milestone">
                                            <i class="fa-solid fa-flag text-sm"></i>
                                        </button>
                                        @else
                                        <div class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100 flex items-center gap-1" title="100% Reached">
                                            <i class="fa-solid fa-check"></i> Milestones 100% Reached
                                        </div>
                                        @endif
                                        <form action="{{ route('emp.ext.strategies.projects.kpis.destroy', $pk->linked_kpi_id ?? 0) }}" method="POST" class="inline" 
                                              onsubmit="return confirmDeletion(event, 'Remove KPI Link?', 'This will decouple the operational project from this strategic KPI.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-400 hover:text-rose-600 transition-colors">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">No KPIs linked yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── TAB: MILESTONES ── --}}
    <div x-show="activeTab === 'milestones'" style="display:none;" class="space-y-6">
        <div class="flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-700 flex items-center gap-2">
                <i class="fa-solid fa-flag text-amber-500"></i> Project Milestones
            </h3>
        </div>

        <div class="premium-card overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Linked KPI</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Milestone</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Description</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Owner</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Duration</th>
                        @if($project->project_status_id == 1)
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($project->milestones as $ms)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-xs font-semibold text-slate-600 truncate block max-w-[150px]">{{ $ms->kpi?->kpi_title ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700">{{ $ms->milestone_title }}</span>
                                    <span class="text-[10px] text-amber-500 font-bold">Weight: {{ $ms->milestone_weight }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-slate-500 truncate block max-w-[200px]">{{ $ms->milestone_description ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-400 uppercase">
                                        {{ substr($ms->owner->first_name ?? '?', 0, 1) }}
                                    </div>
                                    <span class="text-xs text-slate-600 font-medium">{{ $ms->owner->employee_name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] text-slate-400 font-medium">{{ $ms->start_date }} → {{ $ms->end_date }}</span>
                            </td>
                            @if($project->project_status_id == 1)
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('emp.ext.strategies.projects.milestones.destroy', $ms->milestone_id) }}" method="POST" class="inline"
                                          onsubmit="return confirmDeletion(event, 'Delete Milestone?', 'Are you sure you want to remove this milestone?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-rose-400 hover:text-rose-600 transition-colors">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">No milestones added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── TAB: LOGS ── --}}
    <div x-show="activeTab === 'logs'" style="display:none;" class="space-y-6">
        <div class="premium-card p-0 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Time</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Action</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">User</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Remark</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @php
                        $logs = DB::table('sys_logs')
                            ->where('related_table', 'm_operational_projects')
                            ->where('related_id', $project->project_id)
                            ->join('users', 'sys_logs.logged_by', '=', 'users.id')
                            ->select('sys_logs.*', 'users.name as user_name')
                            ->orderBy('log_id', 'desc')
                            ->get();
                    @endphp
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-[10px] text-slate-400 font-mono">{{ $log->log_date }}</td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold @if($log->log_action == 'CREATE') text-emerald-600 @elseif($log->log_action == 'UPDATE') text-blue-600 @else text-slate-600 @endif">
                                    {{ $log->log_action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-600">{{ $log->user_name }}</td>
                            <td class="px-6 py-4 text-xs text-slate-500">{{ $log->log_remark }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">No log entries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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

function confirmDeletion(event, title = 'Are you sure?', text = 'This action cannot be undone.') {
    event.preventDefault();
    const form = event.target;
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    } else {
        if (confirm(title + '\n' + text)) {
            form.submit();
        }
    }
    return false;
}
</script>
@endpush
@endsection
