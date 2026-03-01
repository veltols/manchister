@extends('layouts.app')

@section('title', 'Strategic Plan')
@section('subtitle', $plan->plan_title ?? 'Plan Details')

@section('content')
<div x-data="{
    activeTab: 'overview',
    editPlanOpen: false,
    addThemeOpen: false,
    editThemeOpen: false,
    editThemeId: null,
    editThemeTitle: '',
    editThemeDesc: '',
    editThemeWeight: 0,
    addObjOpen: false,
    addObjThemeId: '',
    addKpiOpen: false,
    addKpiThemeId: '',
    addKpiObjId: '',
    addMsOpen: false,
    addMsThemeId: '',
    addMsObjId: '',
    addMsKpiId: '',
    addExtMapOpen: false,
    addExtThemeId: '',
    addExtObjId: ''
}" class="space-y-6 animate-fade-in-up">

    {{-- ─── HEADER ─── --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <a href="{{ route('emp.ext.strategies.index') }}"
               class="text-slate-400 hover:text-indigo-600 flex items-center gap-1.5 text-sm mb-2 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i> Back to Plans
            </a>
            <h1 class="text-2xl font-display font-bold text-premium">{{ $plan->plan_title }}</h1>
            <div class="flex items-center flex-wrap gap-2 mt-2">
                <span class="text-[10px] font-bold uppercase tracking-wider font-mono bg-slate-50 text-slate-500 px-2 py-0.5 rounded">
                    {{ $plan->plan_ref }}
                </span>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                    {{ $plan->is_published ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                    {{ $plan->is_published ? 'Published' : 'Draft' }}
                </span>
                @if($plan->department)
                    <span class="text-xs text-slate-400 flex items-center gap-1 border-l pl-2 border-slate-200">
                        <i class="fa-solid fa-building"></i> {{ $plan->department->department_name }}
                    </span>
                @endif
                <span class="text-xs text-slate-400 flex items-center gap-1 border-l pl-2 border-slate-200">
                    <i class="fa-solid fa-calendar"></i> {{ $plan->plan_from }} – {{ $plan->plan_to }}
                </span>
            </div>
        </div>
        <div class="flex gap-2 flex-shrink-0">
            @if(!$plan->is_published)
                <form action="{{ route('emp.ext.strategies.publish', $plan->plan_id) }}" method="POST" class="inline" id="publishPlanForm">
                    @csrf
                    <button type="button" onclick="confirmPublish()"
                        style="background: linear-gradient(135deg,#10b981,#059669); color:#fff; border-radius:12px; padding:0.6rem 1.2rem; border:none; cursor:pointer; font-weight:600; display:inline-flex; align-items:center; gap:0.5rem; box-shadow:0 4px 6px rgba(16,185,129,0.2); transition:all 0.3s;">
                        <i class="fa-solid fa-upload text-xs"></i> Publish Plan
                    </button>
                </form>
            @endif
            @if(!$plan->is_published)
            <button @click="editPlanOpen = true"
                class="premium-button px-4 py-2 text-sm">
                <i class="fa-solid fa-pen text-xs"></i> Edit Plan
            </button>
            @endif
        </div>
    </div>

    {{-- ─── TABS ─── --}}
    <div class="border-b border-slate-200">
        <nav class="-mb-px flex space-x-0 overflow-x-auto" aria-label="Tabs">
            @php
                $tabs = [
                    'overview' => ['Overview',          'fa-circle-info',  true],
                    'themes'   => ['Themes & Objectives','fa-layer-group',  true],
                    'mapping'  => ['External Mapping',  'fa-share-nodes',  true],
                ];
                if($plan->is_published) {
                    $tabs['insights']    = ['Insights',    'fa-chart-pie',    true];
                    $tabs['performance'] = ['Performance', 'fa-chart-line',   true];
                    $tabs['matrix']      = ['Matrix',      'fa-table-cells',  true];
                }
            @endphp
            @foreach($tabs as $tab => [$label, $icon, $visible])
                <button @click="activeTab = '{{ $tab }}'"
                    :class="activeTab === '{{ $tab }}' ? 'font-bold' : 'text-slate-500 hover:text-slate-700'"
                    :style="activeTab === '{{ $tab }}' ? 'color:#004F68; border-bottom: 2px solid #004F68;' : 'border-bottom: 2px solid transparent;'"
                    class="whitespace-nowrap py-4 px-4 text-sm transition-all flex items-center gap-1.5">
                    <i class="fa-solid {{ $icon }} text-xs"></i> {{ $label }}
                    @if(in_array($tab, ['insights','performance','matrix']))
                        <span class="text-[9px] bg-emerald-100 text-emerald-600 px-1.5 py-0.5 rounded-full font-bold ml-0.5">LIVE</span>
                    @endif
                </button>
            @endforeach
        </nav>
    </div>

    {{-- ─── TAB: OVERVIEW ─── --}}
    <div x-show="activeTab === 'overview'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-5">
            {{-- Vision --}}
            <div class="premium-card p-6 border-l-4 border-indigo-400" style="background: linear-gradient(135deg, rgba(99,102,241,0.04) 0%, #fff 100%);">
                <h3 class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-eye"></i> Vision
                </h3>
                <p class="text-slate-700 text-base italic font-display leading-relaxed">
                    "{{ $plan->plan_vision ?? 'No vision statement defined.' }}"
                </p>
            </div>
            {{-- Mission --}}
            <div class="premium-card p-6 border-l-4 border-emerald-400" style="background: linear-gradient(135deg, rgba(16,185,129,0.04) 0%, #fff 100%);">
                <h3 class="text-xs font-bold text-emerald-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-crosshairs"></i> Mission
                </h3>
                <p class="text-slate-700 leading-relaxed">{{ $plan->plan_mission ?? 'No mission statement defined.' }}</p>
            </div>
            {{-- Values --}}
            <div class="premium-card p-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-gem"></i> Core Values
                </h3>
                <p class="text-slate-600 whitespace-pre-line">{{ $plan->plan_values ?? 'No values defined.' }}</p>
            </div>
        </div>

        <div class="space-y-5">
            <div class="premium-card p-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-5">Plan Statistics</h3>
                <div class="space-y-4">
                    @foreach([
                        ['From Year',       $plan->plan_from,                                      'fa-calendar-day',   'text-indigo-500'],
                        ['To Year',         $plan->plan_to,                                        'fa-calendar-check', 'text-emerald-500'],
                        ['Period',          $plan->plan_period ?: '—',                             'fa-hourglass-half', 'text-amber-500'],
                        ['Themes',          $plan->themes->count(),                                'fa-layer-group',    'text-purple-500'],
                        ['Objectives',      $plan->objectives->count(),                            'fa-bullseye',       'text-rose-500'],
                        ['KPIs',            $plan->kpis->count(),                                  'fa-chart-line',     'text-blue-500'],
                    ] as [$label, $value, $icon, $color])
                    <div class="flex justify-between items-center pb-3.5 border-b border-slate-50 last:border-0 last:pb-0">
                        <span class="text-slate-500 text-sm flex items-center gap-2">
                            <i class="fa-solid {{ $icon }} {{ $color }} text-xs w-4 text-center"></i>
                            {{ $label }}
                        </span>
                        <span class="font-bold text-slate-700 text-sm">{{ $value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ─── TAB: THEMES & OBJECTIVES ─── --}}
    <div x-show="activeTab === 'themes'" style="display:none;">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-base font-bold text-slate-700">Strategic Themes & Objectives</h3>
            @if(!$plan->is_published)
            <button @click="addThemeOpen = true" class="premium-button px-4 py-2 text-sm">
                <i class="fa-solid fa-plus text-xs"></i> Add Theme
            </button>
            @endif
        </div>

        @forelse($plan->themes as $theme)
            <div class="premium-card mb-5 overflow-hidden">
                {{-- Theme Header --}}
                <div class="flex justify-between items-start p-5 border-b border-slate-50"
                     style="background: linear-gradient(135deg, rgba(0,79,104,0.04) 0%, #fff 100%);">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono bg-slate-100 px-2 py-0.5 rounded">
                                {{ $theme->theme_ref ?? '#' . $theme->order_no }}
                            </span>
                            <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                Weight: {{ $theme->theme_weight }}%
                            </span>
                        </div>
                        <h4 class="text-lg font-bold text-premium">{{ $theme->theme_title }}</h4>
                        @if($theme->theme_description)
                            <p class="text-sm text-slate-500 mt-1">{{ $theme->theme_description }}</p>
                        @endif
                    </div>
                    @if(!$plan->is_published)
                    <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                        <button
                            @click="editThemeOpen = true; editThemeId = {{ $theme->theme_id }}; editThemeTitle = '{{ addslashes($theme->theme_title) }}'; editThemeDesc = '{{ addslashes($theme->theme_description) }}'; editThemeWeight = {{ $theme->theme_weight }}"
                            class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 hover:text-indigo-600 transition-colors flex items-center justify-center">
                            <i class="fa-solid fa-pencil text-xs"></i>
                        </button>
                        <button
                            @click="addObjOpen = true; addObjThemeId = {{ $theme->theme_id }}"
                            class="premium-button px-3 py-1.5 text-xs">
                            <i class="fa-solid fa-plus text-xs"></i> Objective
                        </button>
                    </div>
                    @endif
                </div>

                {{-- Objectives --}}
                @forelse($theme->objectives as $obj)
                    <div class="px-5 py-4 border-b border-slate-50 last:border-0" x-data="{ open: false }">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-mono bg-blue-50 text-blue-600 px-2 py-0.5 rounded font-bold">
                                        {{ $obj->objective_ref }}
                                    </span>
                                    @if($obj->type)
                                        <span class="text-[10px] bg-purple-50 text-purple-600 px-2 py-0.5 rounded font-bold">
                                            {{ $obj->type->objective_type_name }}
                                        </span>
                                    @endif
                                    <span class="text-[10px] text-slate-400 font-bold">W: {{ $obj->objective_weight }}%</span>
                                </div>
                                <p class="font-semibold text-slate-700">{{ $obj->objective_title }}</p>
                                @if($obj->objective_description)
                                    <p class="text-xs text-slate-400 mt-1">{{ $obj->objective_description }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                                <button @click="open = !open"
                                    class="text-xs text-slate-500 hover:text-indigo-600 flex items-center gap-1 px-2 py-1 rounded hover:bg-slate-50 transition-colors">
                                    <i class="fa-solid fa-chart-line text-xs"></i>
                                    <span x-text="open ? 'Hide KPIs' : 'Show KPIs ({{ $obj->kpis->count() }})'"></span>
                                </button>
                                @if(!$plan->is_published)
                                <button
                                    @click="addKpiOpen = true; addKpiThemeId = {{ $theme->theme_id }}; addKpiObjId = {{ $obj->objective_id }}"
                                    class="premium-button px-2.5 py-1.5 text-xs">
                                    <i class="fa-solid fa-plus text-xs"></i> KPI
                                </button>
                                @endif
                            </div>
                        </div>

                        {{-- KPIs --}}
                        <div x-show="open" x-transition class="mt-4 ml-4 space-y-3">
                            @forelse($obj->kpis as $kpi)
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                                    <div class="flex justify-between items-start gap-3">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                                <span class="text-[10px] font-mono bg-white text-slate-600 border border-slate-200 px-2 py-0.5 rounded font-bold">{{ $kpi->kpi_code }}</span>
                                                @if($kpi->kpi_frequncy_id)
                                                    <span class="text-[10px] bg-sky-50 text-sky-600 px-2 py-0.5 rounded font-bold">{{ $frequencies->where('kpi_frequncy_id', $kpi->kpi_frequncy_id)->first()?->kpi_frequncy_name ?? '' }}</span>
                                                @endif
                                                <span class="text-[10px] text-slate-400 font-bold">Progress: {{ $kpi->kpi_progress }}%</span>
                                            </div>
                                            <p class="font-semibold text-slate-700 text-sm">{{ $kpi->kpi_title }}</p>
                                            @if($kpi->kpi_description)
                                                <p class="text-xs text-slate-400 mt-0.5">{{ $kpi->kpi_description }}</p>
                                            @endif
                                        </div>
                                        @if(!$plan->is_published)
                                        <button
                                            @click="addMsOpen = true; addMsThemeId = {{ $theme->theme_id }}; addMsObjId = {{ $obj->objective_id }}; addMsKpiId = {{ $kpi->kpi_id }}"
                                            class="premium-button px-2.5 py-1.5 text-xs flex-shrink-0">
                                            <i class="fa-solid fa-plus text-xs"></i> Milestone
                                        </button>
                                        @endif
                                    </div>
                                    {{-- Milestones --}}
                                    @if($kpi->milestones->count())
                                        <div class="mt-3 space-y-1.5">
                                            @foreach($kpi->milestones as $ms)
                                                <div class="flex items-center gap-2 text-xs text-slate-600 bg-white rounded-lg px-3 py-2 border border-slate-100">
                                                    <i class="fa-solid fa-flag text-amber-400 text-[10px]"></i>
                                                    <span class="font-medium">{{ $ms->milestone_title }}</span>
                                                    <span class="ml-auto text-slate-400">W: {{ $ms->milestone_weight }}%</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic">No KPIs defined yet.</p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center">
                        <p class="text-sm text-slate-400 italic">No objectives defined for this theme yet.</p>
                        @if(!$plan->is_published)
                        <button @click="addObjOpen = true; addObjThemeId = {{ $theme->theme_id }}"
                            class="mt-2 text-xs text-indigo-500 hover:text-indigo-700 font-semibold">
                            + Add first objective
                        </button>
                        @endif
                    </div>
                @endforelse
            </div>
        @empty
            <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-slate-200">
                <i class="fa-solid fa-layer-group text-4xl text-slate-300 mb-4"></i>
                <p class="text-slate-500 font-bold">No Themes Defined</p>
                <p class="text-xs text-slate-400 mt-1">Start by adding a strategic theme.</p>
            </div>
        @endforelse
    </div>

    {{-- ─── TAB: EXTERNAL MAPPING ─── --}}
    <div x-show="activeTab === 'mapping'" style="display:none;">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-base font-bold text-slate-700">External Mapping</h3>
            @if(!$plan->is_published)
            <button @click="addExtMapOpen = true" class="premium-button px-4 py-2 text-sm">
                <i class="fa-solid fa-plus text-xs"></i> Add External Map
            </button>
            @endif
        </div>

        @forelse($plan->externalMaps as $map)
            <div class="premium-card p-5 mb-4">
                <div class="flex justify-between items-start gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-rose-50 text-rose-600 text-[10px] font-bold px-2 py-0.5 rounded">External Entity</span>
                        </div>
                        <h4 class="font-bold text-slate-700">{{ $map->external_entity_name }}</h4>
                        @if($map->map_description)
                            <p class="text-sm text-slate-500 mt-1">{{ $map->map_description }}</p>
                        @endif
                        @if($map->start_date || $map->end_date)
                            <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                                <i class="fa-solid fa-calendar-range"></i>
                                {{ $map->start_date ? \Carbon\Carbon::parse($map->start_date)->format('M Y') : '—' }}
                                → {{ $map->end_date ? \Carbon\Carbon::parse($map->end_date)->format('M Y') : '—' }}
                            </p>
                        @endif
                    </div>
                    @if($map->objective)
                        <div class="text-right flex-shrink-0">
                            <span class="text-[10px] text-slate-400 block">Linked Objective</span>
                            <span class="text-xs font-bold text-slate-600">{{ $map->objective->objective_title }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-slate-200">
                <i class="fa-solid fa-share-nodes text-4xl text-slate-300 mb-4"></i>
                <p class="text-slate-500 font-bold">No External Mappings Yet</p>
                <p class="text-xs text-slate-400 mt-1">Link this plan to external entities or frameworks.</p>
            </div>
        @endforelse
    </div>

    {{-- ─── TAB: INSIGHTS (published only) ─── --}}
    @if($plan->is_published)
    <div x-show="activeTab === 'insights'" style="display:none;">

        @php
            $totalThemes     = $plan->themes->count();
            $totalObjectives = $plan->objectives->count();
            $totalKpis       = $plan->kpis->count();
            $totalMilestones = $plan->milestones->count();
            $totalExtMaps    = $plan->externalMaps->count();
            $totalIntMaps    = $plan->internalMaps->count();

            $avgKpiProgress  = $totalKpis > 0
                ? round($plan->kpis->avg('kpi_progress'), 1)
                : 0;
            $totalWeight     = $plan->themes->sum('theme_weight');
        @endphp

        {{-- Summary KPI Cards — Dashboard Style --}}
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 mb-8">
            {{-- Panel Header --}}
            <div class="flex items-center justify-between px-6 py-4"
                 style="background: linear-gradient(135deg, #004F68 0%, #006a8a 60%, #1a8aaa 100%);">
                <h3 class="font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie"></i> Plan Insights
                </h3>
                <span class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full"
                      style="background:rgba(255,255,255,0.18); color:#fff; border:1px solid rgba(255,255,255,0.3);">
                    {{ $plan->plan_from }} – {{ $plan->plan_to }}
                </span>
            </div>

            <div class="p-5">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">

                    {{-- Themes --}}
                    <div class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                         style="background:linear-gradient(135deg,#f5f3ff,#ede9fe); border:1.5px solid rgba(99,102,241,0.18); box-shadow:0 4px 16px rgba(99,102,241,0.1);">
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                             style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                             style="background:linear-gradient(145deg,#8b5cf6,#7c3aed); box-shadow:0 8px 22px rgba(139,92,246,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                            <i class="fa-solid fa-layer-group text-white text-xl relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#7c3aed;">{{ $totalThemes }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#8b5cf6;">Themes</p>
                    </div>

                    {{-- Objectives --}}
                    <div class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                         style="background:linear-gradient(135deg,#fff1f2,#ffe4e6); border:1.5px solid rgba(244,63,94,0.18); box-shadow:0 4px 16px rgba(244,63,94,0.1);">
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                             style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                             style="background:linear-gradient(145deg,#f43f5e,#e11d48); box-shadow:0 8px 22px rgba(244,63,94,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                            <i class="fa-solid fa-bullseye text-white text-xl relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#e11d48;">{{ $totalObjectives }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#f43f5e;">Objectives</p>
                    </div>

                    {{-- KPIs --}}
                    <div class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                         style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe); border:1.5px solid rgba(14,165,233,0.18); box-shadow:0 4px 16px rgba(14,165,233,0.1);">
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                             style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                             style="background:linear-gradient(145deg,#0ea5e9,#0284c7); box-shadow:0 8px 22px rgba(14,165,233,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                            <i class="fa-solid fa-chart-line text-white text-xl relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#0284c7;">{{ $totalKpis }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#0ea5e9;">KPIs</p>
                    </div>

                    {{-- Milestones --}}
                    <div class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                         style="background:linear-gradient(135deg,#fffbeb,#fef3c7); border:1.5px solid rgba(245,158,11,0.18); box-shadow:0 4px 16px rgba(245,158,11,0.1);">
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                             style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                             style="background:linear-gradient(145deg,#f59e0b,#d97706); box-shadow:0 8px 22px rgba(245,158,11,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                            <i class="fa-solid fa-flag text-white text-xl relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#d97706;">{{ $totalMilestones }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#f59e0b;">Milestones</p>
                    </div>

                    {{-- Ext. Mappings --}}
                    <div class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                         style="background:linear-gradient(135deg,#fdf2f8,#fce7f3); border:1.5px solid rgba(236,72,153,0.18); box-shadow:0 4px 16px rgba(236,72,153,0.1);">
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                             style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                             style="background:linear-gradient(145deg,#ec4899,#db2777); box-shadow:0 8px 22px rgba(236,72,153,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                            <i class="fa-solid fa-share-nodes text-white text-xl relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#db2777;">{{ $totalExtMaps }}</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#ec4899;">Ext. Maps</p>
                    </div>

                    {{-- Avg. Progress --}}
                    <div class="relative overflow-hidden rounded-2xl p-5 flex flex-col items-center gap-3 group transition-all duration-300 hover:-translate-y-2 text-center"
                         style="background:linear-gradient(135deg,#f0fdf4,#dcfce7); border:1.5px solid rgba(16,185,129,0.18); box-shadow:0 4px 16px rgba(16,185,129,0.1);">
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                             style="background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,0.5) 50%,transparent 70%);"></div>
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center relative overflow-hidden flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-[-5deg]"
                             style="background:linear-gradient(145deg,#10b981,#059669); box-shadow:0 8px 22px rgba(16,185,129,0.4),inset 0 1px 0 rgba(255,255,255,0.3);">
                            <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-2xl" style="background:rgba(255,255,255,0.3);"></div>
                            <i class="fa-solid fa-circle-half-stroke text-white text-xl relative z-10"></i>
                        </div>
                        <h3 class="text-4xl font-black leading-none" style="color:#059669;">{{ $avgKpiProgress }}%</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#10b981;">Avg. Progress</p>
                    </div>

                </div>
            </div>
        </div>

        {{-- Theme Weight Distribution --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="premium-card p-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-5 flex items-center gap-2">
                    <i class="fa-solid fa-weight-hanging text-indigo-400"></i>
                    Theme Weight Distribution
                </h3>
                @forelse($plan->themes as $theme)
                    @php $pct = $totalWeight > 0 ? round(($theme->theme_weight / $totalWeight) * 100) : 0; @endphp
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-semibold text-slate-600 truncate max-w-[70%]">{{ $theme->theme_title }}</span>
                            <span class="text-xs font-bold" style="color:#004F68;">{{ $theme->theme_weight }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="h-2.5 rounded-full transition-all duration-700"
                                 style="width:{{ $pct }}%; background: linear-gradient(90deg, #004F68, #0088b3);"></div>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span class="text-[10px] text-slate-400">{{ $theme->objectives->count() }} objectives</span>
                            <span class="text-[10px] text-slate-400">{{ $pct }}% of total weight</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 italic text-center py-6">No themes defined.</p>
                @endforelse
            </div>

            <div class="premium-card p-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-5 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-blue-400"></i>
                    Plan Details
                </h3>
                <div class="space-y-3">
                    @foreach([
                        ['Plan Reference',   $plan->plan_ref,          'fa-hashtag',   'text-slate-500'],
                        ['Plan Period',       ($plan->plan_period ?: $plan->plan_from.' – '.$plan->plan_to), 'fa-calendar', 'text-indigo-500'],
                        ['Dept / Level',     $plan->department?->department_name ?? 'Organization-Wide', 'fa-building', 'text-emerald-500'],
                        ['Status',           $plan->is_published ? 'Published' : 'Draft', 'fa-circle-check', 'text-green-500'],
                        ['Total Objectives', $totalObjectives.' objectives across '.$totalThemes.' themes', 'fa-bullseye', 'text-rose-500'],
                        ['KPI Coverage',     $totalKpis.' KPIs · '.$totalMilestones.' milestones', 'fa-chart-line', 'text-blue-500'],
                    ] as [$lbl, $val, $ico, $clr])
                    <div class="flex items-start gap-3 pb-3 border-b border-slate-50 last:border-0 last:pb-0">
                        <i class="fa-solid {{ $ico }} {{ $clr }} text-xs mt-1 w-4 text-center flex-shrink-0"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">{{ $lbl }}</p>
                            <p class="text-sm font-semibold text-slate-700 truncate">{{ $val }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ─── TAB: PERFORMANCE (published only) ─── --}}
    <div x-show="activeTab === 'performance'" style="display:none;">

        @php
            $overallProgress = $totalKpis > 0 ? round($plan->kpis->avg('kpi_progress')) : 0;
        @endphp

        {{-- Overall Progress Banner --}}
        <div class="premium-card p-6 mb-6"
             style="background: linear-gradient(135deg, rgba(0,79,104,0.06) 0%, rgba(0,136,179,0.04) 100%);">
            <div class="flex flex-col md:flex-row items-center gap-6">
                {{-- Donut-style ring --}}
                <div class="relative w-28 h-28 flex-shrink-0">
                    <svg viewBox="0 0 36 36" class="w-28 h-28 -rotate-90">
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#e2e8f0" stroke-width="3"/>
                        <circle cx="18" cy="18" r="15.9" fill="none"
                                stroke="url(#progGrad)" stroke-width="3"
                                stroke-dasharray="{{ $overallProgress }}, 100"
                                stroke-linecap="round"/>
                        <defs>
                            <linearGradient id="progGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#004F68"/>
                                <stop offset="100%" stop-color="#0088b3"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xl font-bold" style="color:#004F68;">{{ $overallProgress }}%</span>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h3 class="text-xl font-display font-bold text-premium">Overall Plan Progress</h3>
                    <p class="text-sm text-slate-500 mt-1">Based on {{ $totalKpis }} KPI{{ $totalKpis != 1 ? 's' : '' }} across {{ $totalThemes }} theme{{ $totalThemes != 1 ? 's' : '' }}</p>
                    <div class="flex items-center gap-6 mt-4 flex-wrap justify-center md:justify-start">
                        @foreach([['On Track','bg-emerald-400'], ['In Progress','bg-amber-400'], ['Not Started','bg-slate-300']] as [$lbl,$clr])
                            <div class="flex items-center gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full {{ $clr }}"></div>
                                <span class="text-xs text-slate-500">{{ $lbl }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Per-Theme KPI Progress --}}
        @forelse($plan->themes as $theme)
            @php
                $themeKpis = $theme->objectives->flatMap(fn($o) => $o->kpis);
                $themeAvg  = $themeKpis->count() > 0 ? round($themeKpis->avg('kpi_progress')) : 0;
            @endphp
            <div class="premium-card mb-5 overflow-hidden">
                {{-- Theme Header --}}
                <div class="flex justify-between items-center p-5 border-b border-slate-50"
                     style="background: linear-gradient(135deg, rgba(0,79,104,0.04) 0%, #fff 100%);">
                    <div class="flex-1">
                        <p class="font-bold text-slate-700">{{ $theme->theme_title }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $themeKpis->count() }} KPI{{ $themeKpis->count() != 1 ? 's' : '' }}</p>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <div class="text-right">
                            <p class="text-xs text-slate-400">Theme Progress</p>
                            <p class="text-lg font-bold" style="color:#004F68;">{{ $themeAvg }}%</p>
                        </div>
                        <div class="w-12 h-12 relative flex-shrink-0">
                            <svg viewBox="0 0 36 36" class="w-12 h-12 -rotate-90">
                                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#e2e8f0" stroke-width="4"/>
                                <circle cx="18" cy="18" r="15.9" fill="none"
                                        stroke="{{ $themeAvg >= 70 ? '#10b981' : ($themeAvg >= 30 ? '#f59e0b' : '#94a3b8') }}"
                                        stroke-width="4"
                                        stroke-dasharray="{{ $themeAvg }}, 100"
                                        stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- KPI Progress Bars per Objective --}}
                <div class="p-5 space-y-5">
                    @forelse($theme->objectives as $obj)
                        @if($obj->kpis->count())
                            <div>
                                <p class="text-xs font-bold text-slate-500 mb-3 flex items-center gap-1.5">
                                    <i class="fa-solid fa-bullseye text-rose-400 text-xs"></i>
                                    {{ $obj->objective_title }}
                                </p>
                                <div class="space-y-3 pl-4 border-l-2 border-slate-100">
                                    @foreach($obj->kpis as $kpi)
                                        @php
                                            $prog  = (int)$kpi->kpi_progress;
                                            $color = $prog >= 70 ? '#10b981' : ($prog >= 30 ? '#f59e0b' : '#94a3b8');
                                            $status = $prog >= 70 ? 'On Track' : ($prog >= 30 ? 'In Progress' : 'Not Started');
                                        @endphp
                                        <div class="bg-slate-50 rounded-xl p-4">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs font-semibold text-slate-700 truncate">{{ $kpi->kpi_title }}</p>
                                                    <p class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $kpi->kpi_code }}</p>
                                                </div>
                                                <div class="text-right flex-shrink-0 ml-3">
                                                    <span class="text-sm font-bold" style="color:{{ $color }};">{{ $prog }}%</span>
                                                    <p class="text-[10px] font-bold mt-0.5" style="color:{{ $color }};">{{ $status }}</p>
                                                </div>
                                            </div>
                                            <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                                <div class="h-2 rounded-full transition-all duration-700"
                                                     style="width:{{ $prog }}%; background-color:{{ $color }};"></div>
                                            </div>
                                            @if($kpi->kpi_formula)
                                            <p class="text-[10px] text-slate-400 mt-1.5 italic">
                                                <i class="fa-solid fa-function text-[9px] mr-1"></i>{{ $kpi->kpi_formula }}
                                            </p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @empty
                        <p class="text-xs text-slate-400 italic text-center py-4">No objectives with KPIs in this theme.</p>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-slate-200">
                <i class="fa-solid fa-chart-line text-4xl text-slate-300 mb-4"></i>
                <p class="text-slate-500 font-bold">No Performance Data Yet</p>
                <p class="text-xs text-slate-400 mt-1">Add themes, objectives and KPIs first.</p>
            </div>
        @endforelse
    </div>

    {{-- ─── TAB: MATRIX / INTERNAL MAP (published only) ─── --}}
    <div x-show="activeTab === 'matrix'" style="display:none;">
        <div class="flex justify-between items-center mb-5">
            <div>
                <h3 class="text-base font-bold text-slate-700">Operational Matrix</h3>
                <p class="text-xs text-slate-400 mt-0.5">Department-level task assignments linked to KPIs &amp; Milestones</p>
            </div>
            <a href="{{ route('emp.ext.strategies.index') }}"
               class="text-xs text-slate-400 hover:text-indigo-600 transition-colors">
               {{-- Matrix rows are added via the old internal map service --}}
            </a>
        </div>

        @if($plan->internalMaps->count())
            {{-- Desktop Table --}}
            <div class="premium-card overflow-hidden hidden md:block">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                            <th class="text-left px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">#</th>
                            <th class="text-left px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Department</th>
                            <th class="text-left px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Task</th>
                            <th class="text-left px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Objective</th>
                            <th class="text-left px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Dates</th>
                            <th class="text-left px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($plan->internalMaps as $i => $map)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-4 text-slate-400 text-xs font-mono">{{ $i + 1 }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                                             style="background:rgba(0,79,104,0.1);">
                                            <i class="fa-solid fa-building text-[10px]" style="color:#004F68;"></i>
                                        </div>
                                        <span class="text-xs font-semibold text-slate-700">
                                            {{ $map->department?->department_name ?? 'N/A' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-xs font-semibold text-slate-700">{{ $map->task_title }}</p>
                                    @if($map->task_description)
                                        <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-2">{{ $map->task_description }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-1 rounded font-bold">
                                        {{ $map->objective?->objective_title ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-500 whitespace-nowrap">
                                    @if($map->start_date || $map->end_date)
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-calendar text-slate-300 text-[10px]"></i>
                                            {{ $map->start_date ? \Carbon\Carbon::parse($map->start_date)->format('d M Y') : '—' }}
                                            <i class="fa-solid fa-arrow-right text-[10px] text-slate-300"></i>
                                            {{ $map->end_date ? \Carbon\Carbon::parse($map->end_date)->format('d M Y') : '—' }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold
                                        {{ $map->is_task_assigned ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                        <i class="fa-solid {{ $map->is_task_assigned ? 'fa-check-circle' : 'fa-clock' }} text-[9px]"></i>
                                        {{ $map->is_task_assigned ? 'Assigned' : 'Pending' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="space-y-4 md:hidden">
                @foreach($plan->internalMaps as $map)
                    <div class="premium-card p-5">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-semibold text-slate-700 text-sm">{{ $map->task_title }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $map->department?->department_name ?? 'N/A' }}</p>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold
                                {{ $map->is_task_assigned ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                {{ $map->is_task_assigned ? 'Assigned' : 'Pending' }}
                            </span>
                        </div>
                        @if($map->objective)
                            <p class="text-[10px] bg-blue-50 text-blue-600 px-2 py-1 rounded font-bold inline-block">
                                {{ $map->objective->objective_title }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Summary strip --}}
            <div class="mt-4 flex items-center gap-4 text-xs text-slate-400 px-1">
                <span><strong class="text-slate-600">{{ $plan->internalMaps->count() }}</strong> total entries</span>
                <span>•</span>
                <span><strong class="text-emerald-600">{{ $plan->internalMaps->where('is_task_assigned', 1)->count() }}</strong> assigned</span>
                <span>•</span>
                <span><strong class="text-amber-600">{{ $plan->internalMaps->where('is_task_assigned', 0)->count() }}</strong> pending</span>
            </div>

        @else
            <div class="text-center py-20 bg-white rounded-2xl border-2 border-dashed border-slate-200">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                     style="background: rgba(0,79,104,0.08);">
                    <i class="fa-solid fa-table-cells text-2xl" style="color:#004F68;"></i>
                </div>
                <p class="text-slate-500 font-bold">No Matrix Entries Yet</p>
                <p class="text-xs text-slate-400 mt-1">Internal operational mappings will appear here once created.</p>
            </div>
        @endif
    </div>
    @endif {{-- end is_published check --}}

    {{-- ═══════════════════════════════════════════════════════
         MODALS
         ═══════════════════════════════════════════════════════ --}}

    {{-- ── Edit Plan Modal ── --}}
    <div x-show="editPlanOpen" class="modal active"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         style="display:none;">
        <div class="modal-backdrop" @click="editPlanOpen = false"></div>
        <div class="modal-content" @click.stop>
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-premium">Edit Strategic Plan</h3>
                <button @click="editPlanOpen = false" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('emp.ext.strategies.update', $plan->plan_id) }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Plan Title</label>
                    <input type="text" name="plan_title" class="premium-input w-full" value="{{ $plan->plan_title }}" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">From Year</label>
                        <input type="number" name="plan_from" class="premium-input w-full" value="{{ $plan->plan_from }}" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">To Year</label>
                        <input type="number" name="plan_to" class="premium-input w-full" value="{{ $plan->plan_to }}" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Period</label>
                        <input type="text" name="plan_period" class="premium-input w-full" value="{{ $plan->plan_period }}">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Org. Level</label>
                        <select name="plan_level" class="premium-input w-full">
                            @foreach($departments as $dept)
                                <option value="{{ $dept->department_id }}" {{ $plan->plan_level == $dept->department_id ? 'selected' : '' }}>
                                    {{ $dept->department_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Vision</label>
                    <textarea name="plan_vision" rows="3" class="premium-input w-full">{{ $plan->plan_vision }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Mission</label>
                    <textarea name="plan_mission" rows="3" class="premium-input w-full">{{ $plan->plan_mission }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Values</label>
                    <textarea name="plan_values" rows="3" class="premium-input w-full">{{ $plan->plan_values }}</textarea>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="premium-button px-8 py-2.5">
                        <i class="fa-solid fa-save text-xs"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Add Theme Modal ── --}}
    <div x-show="addThemeOpen" class="modal active" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display:none;">
        <div class="modal-backdrop" @click="addThemeOpen = false"></div>
        <div class="modal-content max-w-lg" @click.stop>
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-premium">Add Strategic Theme</h3>
                <button @click="addThemeOpen = false" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('emp.ext.strategies.themes.store', $plan->plan_id) }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Theme Title</label>
                    <input type="text" name="theme_title" class="premium-input w-full" required placeholder="e.g. Academic Excellence">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description</label>
                    <textarea name="theme_description" rows="3" class="premium-input w-full" placeholder="Brief description..."></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Weight (%)</label>
                    <input type="number" name="theme_weight" class="premium-input w-full" min="0" max="100" value="0">
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="premium-button px-8 py-2.5">
                        <i class="fa-solid fa-plus text-xs"></i> Add Theme
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Edit Theme Modal ── --}}
    <div x-show="editThemeOpen" class="modal active" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display:none;">
        <div class="modal-backdrop" @click="editThemeOpen = false"></div>
        <div class="modal-content max-w-lg" @click.stop>
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-premium">Edit Theme</h3>
                <button @click="editThemeOpen = false" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form :action="`{{ url('emp/ext/strategies/view/' . $plan->plan_id . '/themes') }}/${editThemeId}`" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Theme Title</label>
                    <input type="text" name="theme_title" class="premium-input w-full" :value="editThemeTitle" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description</label>
                    <textarea name="theme_description" rows="3" class="premium-input w-full" x-text="editThemeDesc"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Weight (%)</label>
                    <input type="number" name="theme_weight" class="premium-input w-full" :value="editThemeWeight" min="0" max="100">
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="premium-button px-8 py-2.5">
                        <i class="fa-solid fa-save text-xs"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Add Objective Modal ── --}}
    <div x-show="addObjOpen" class="modal active" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display:none;">
        <div class="modal-backdrop" @click="addObjOpen = false"></div>
        <div class="modal-content max-w-lg" @click.stop>
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-premium">Add Objective</h3>
                <button @click="addObjOpen = false" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('emp.ext.strategies.objectives.store', $plan->plan_id) }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="theme_id" :value="addObjThemeId">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Objective Title</label>
                    <input type="text" name="objective_title" class="premium-input w-full" required placeholder="e.g. Improve graduate outcomes">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description</label>
                    <textarea name="objective_description" rows="3" class="premium-input w-full"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Type</label>
                        <select name="objective_type_id" class="premium-input w-full">
                            <option value="">— Select Type —</option>
                            @foreach($objectiveTypes as $type)
                                <option value="{{ $type->objective_type_id }}">{{ $type->objective_type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Weight (%)</label>
                        <input type="number" name="objective_weight" class="premium-input w-full" min="0" max="100" value="0">
                    </div>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="premium-button px-8 py-2.5">
                        <i class="fa-solid fa-plus text-xs"></i> Add Objective
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Add KPI Modal ── --}}
    <div x-show="addKpiOpen" class="modal active" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display:none;">
        <div class="modal-backdrop" @click="addKpiOpen = false"></div>
        <div class="modal-content" @click.stop>
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-premium">Add KPI</h3>
                <button @click="addKpiOpen = false" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('emp.ext.strategies.kpis.store', $plan->plan_id) }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="theme_id" :value="addKpiThemeId">
                <input type="hidden" name="objective_id" :value="addKpiObjId">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">KPI Title</label>
                    <input type="text" name="kpi_title" class="premium-input w-full" required placeholder="e.g. Graduate Employment Rate">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description</label>
                    <textarea name="kpi_description" rows="2" class="premium-input w-full"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Measurement Frequency</label>
                        <select name="kpi_frequncy_id" class="premium-input w-full">
                            <option value="">— Select —</option>
                            @foreach($frequencies as $freq)
                                <option value="{{ $freq->kpi_frequncy_id }}">{{ $freq->kpi_frequncy_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Weight (%)</label>
                        <input type="number" name="kpi_weight" class="premium-input w-full" min="0" max="100" value="0">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Formula</label>
                    <input type="text" name="kpi_formula" class="premium-input w-full" placeholder="e.g. (Employed / Total Graduates) x 100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Data Source</label>
                    <input type="text" name="data_source" class="premium-input w-full" placeholder="e.g. Graduate Tracer Survey">
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="premium-button px-8 py-2.5">
                        <i class="fa-solid fa-plus text-xs"></i> Add KPI
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Add Milestone Modal ── --}}
    <div x-show="addMsOpen" class="modal active" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display:none;">
        <div class="modal-backdrop" @click="addMsOpen = false"></div>
        <div class="modal-content max-w-lg" @click.stop>
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-premium">Add Milestone</h3>
                <button @click="addMsOpen = false" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('emp.ext.strategies.milestones.store', $plan->plan_id) }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="theme_id" :value="addMsThemeId">
                <input type="hidden" name="objective_id" :value="addMsObjId">
                <input type="hidden" name="kpi_id" :value="addMsKpiId">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Milestone Title</label>
                    <input type="text" name="milestone_title" class="premium-input w-full" required placeholder="e.g. Q1 Baseline Assessment">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description</label>
                    <textarea name="milestone_description" rows="3" class="premium-input w-full"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Weight (%)</label>
                    <input type="number" name="milestone_weight" class="premium-input w-full" min="0" max="100" value="0">
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="premium-button px-8 py-2.5">
                        <i class="fa-solid fa-flag text-xs"></i> Add Milestone
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Add External Map Modal ── --}}
    <div x-show="addExtMapOpen" class="modal active" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display:none;">
        <div class="modal-backdrop" @click="addExtMapOpen = false"></div>
        <div class="modal-content max-w-lg" @click.stop>
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-premium">Add External Mapping</h3>
                <button @click="addExtMapOpen = false" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('emp.ext.strategies.external_maps.store', $plan->plan_id) }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">External Entity Name</label>
                    <input type="text" name="external_entity_name" class="premium-input w-full" required
                           placeholder="e.g. National Accreditation Authority / SDG Goal 4">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Linked Theme</label>
                    <select name="theme_id" class="premium-input w-full" required x-model="addExtThemeId">
                        <option value="">— Select Theme —</option>
                        @foreach($plan->themes as $theme)
                            <option value="{{ $theme->theme_id }}">{{ $theme->theme_title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Linked Objective</label>
                    <select name="objective_id" class="premium-input w-full" required>
                        <option value="">— Select Objective —</option>
                        @foreach($plan->themes as $theme)
                            @foreach($theme->objectives as $obj)
                                <option value="{{ $obj->objective_id }}">{{ $obj->objective_title }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description</label>
                    <textarea name="map_description" rows="3" class="premium-input w-full"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Start Date</label>
                        <input type="date" name="start_date" class="premium-input w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">End Date</label>
                        <input type="date" name="end_date" class="premium-input w-full">
                    </div>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="premium-button px-8 py-2.5">
                        <i class="fa-solid fa-link text-xs"></i> Add Mapping
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function confirmPublish() {
        Swal.fire({
            title: 'Publish Strategic Plan?',
            html: `<p class="text-slate-500 text-sm mt-1">Once published, this plan will be <strong>visible and committed</strong>.<br>This action is <strong>irreversible</strong>.</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fa-solid fa-upload mr-1"></i> Yes, Publish',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#059669',
            cancelButtonColor: '#94a3b8',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl shadow-2xl',
                title: 'font-display font-bold text-slate-800',
                confirmButton: 'rounded-xl font-semibold !px-6',
                cancelButton: 'rounded-xl font-semibold !px-6',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('publishPlanForm').submit();
            }
        });
    }
</script>
@endpush

@endsection
