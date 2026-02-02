@extends('layouts.app')

@section('title', 'Project Details')
@section('subtitle', $project->project_name ?? 'Project Overview')

@section('content')
    <div x-data="{ activeTab: 'overview' }" class="space-y-6 animate-fade-in-up">

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                    <a href="{{ route('emp.ext.strategies.projects.index') }}"
                        class="hover:text-indigo-600 transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Back to Projects
                    </a>
                </div>
                <h1 class="text-3xl font-display font-bold text-premium">{{ $project->project_name }} <span
                        class="text-slate-400 text-lg font-normal">({{ $project->project_code }})</span></h1>
                <div class="flex items-center gap-2 mt-2">
                    <span
                        class="px-2 py-1 rounded text-[10px] uppercase font-bold tracking-wider {{ $project->project_status_id == 1 ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' }}">
                        {{ $project->project_status_id == 1 ? 'Draft' : 'Published' }}
                    </span>
                    <span class="text-slate-400 text-xs flex items-center gap-1 border-l pl-2 border-slate-300">
                        <i class="fa-solid fa-building"></i>
                        {{ $project->department->department_name ?? 'Department' }}
                    </span>
                    <span class="text-slate-400 text-xs flex items-center gap-1 border-l pl-2 border-slate-300">
                        <i class="fa-solid fa-chess-knight"></i>
                        {{ $project->plan->plan_title ?? 'Strategic Plan' }}
                    </span>
                </div>
            </div>
            <div class="flex gap-2">
                @if($project->project_status_id == 1)
                    <button class="premium-button-secondary px-4 py-2">
                        <i class="fa-solid fa-upload"></i>
                        <span>Publish Project</span>
                    </button>
                @endif
                <button class="premium-button px-4 py-2">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Edit Project</span>
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="border-b border-slate-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'border-brand-primary text-brand-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Overview
                </button>
                <button @click="activeTab = 'analysis'"
                    :class="activeTab === 'analysis' ? 'border-brand-primary text-brand-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Analysis & Recs
                </button>
                <button @click="activeTab = 'kpis'"
                    :class="activeTab === 'kpis' ? 'border-brand-primary text-brand-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    KPIs
                </button>
            </nav>
        </div>

        <!-- Tab Contents -->

        <!-- Overview Tab -->
        <div x-show="activeTab === 'overview'" class="space-y-6">
            <div class="premium-card p-6">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-3">Project Description</h3>
                <p class="text-slate-700 leading-relaxed">{{ $project->project_description }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="premium-card p-6 bg-indigo-50/50">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fa-solid fa-calendar text-indigo-400"></i>
                        <h3 class="font-bold text-slate-700">Timeline</h3>
                    </div>
                    <div class="space-y-2 text-sm text-slate-600">
                        <div class="flex justify-between">
                            <span>Start Date</span>
                            <span class="font-bold">{{ $project->project_start_date }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>End Date</span>
                            <span class="font-bold">{{ $project->project_end_date }}</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-indigo-100">
                            <span>Duration</span>
                            <span class="font-bold text-indigo-600">{{ $project->project_period }}</span>
                        </div>
                    </div>
                </div>

                <div class="premium-card p-6 bg-emerald-50/50">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fa-solid fa-link text-emerald-400"></i>
                        <h3 class="font-bold text-slate-700">Alignment</h3>
                    </div>
                    <div class="space-y-2 text-sm text-slate-600">
                        <div>
                            <span class="block text-xs uppercase text-slate-400">Strategic Plan</span>
                            <span class="font-bold text-emerald-800">{{ $project->plan->plan_title ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analysis Tab -->
        <div x-show="activeTab === 'analysis'" style="display: none;" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="premium-card p-6">
                <h3 class="text-sm font-bold text-amber-500 uppercase tracking-widest mb-3">Project Analysis</h3>
                <p class="text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $project->project_analysis ?? 'No analysis provided.' }}</p>
            </div>
            <div class="premium-card p-6">
                <h3 class="text-sm font-bold text-blue-500 uppercase tracking-widest mb-3">Recommendations</h3>
                <p class="text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $project->project_recommendations ?? 'No recommendations provided.' }}</p>
            </div>
        </div>

        <!-- KPIs Tab -->
        <div x-show="activeTab === 'kpis'" style="display: none;">
            <div class="text-center py-20 bg-slate-50 rounded-xl border-dashed border-2 border-slate-200">
                <i class="fa-solid fa-chart-line text-4xl text-slate-300 mb-4"></i>
                <p class="text-slate-500 font-bold">Project KPIs</p>
                <p class="text-slate-400 text-sm">KPI management coming soon.</p>
            </div>
        </div>

    </div>
@endsection
