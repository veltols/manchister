@extends('layouts.app')

@section('title', 'Strategic Plan Details')
@section('subtitle', $plan->plan_title ?? 'Plan Overview')

@section('content')
    <div x-data="{ activeTab: 'overview' }" class="space-y-6 animate-fade-in-up">

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                    <a href="{{ route('emp.ext.strategies.index') }}" class="hover:text-indigo-600 transition-colors">
                        <i class="fa-solid fa-arrow-left"></i> Back to Plans
                    </a>
                </div>
                <h1 class="text-3xl font-display font-bold text-premium">{{ $plan->plan_title }}</h1>
                <div class="flex items-center gap-2 mt-2">
                    <span
                        class="px-2 py-1 rounded text-[10px] uppercase font-bold tracking-wider {{ $plan->plan_status_id == 1 ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' }}">
                        {{ $plan->plan_status_id == 1 ? 'Draft' : 'Published' }}
                    </span>
                    <span class="text-slate-400 text-xs flex items-center gap-1 border-l pl-2 border-slate-300">
                        <i class="fa-solid fa-building"></i>
                        {{ $plan->department->department_name ?? 'Organization' }}
                    </span>
                    <span class="text-slate-400 text-xs flex items-center gap-1 border-l pl-2 border-slate-300">
                        <i class="fa-solid fa-calendar"></i>
                        {{ $plan->plan_period }}
                    </span>
                </div>
            </div>
            <div class="flex gap-2">
                @if($plan->plan_status_id == 1)
                    <button class="premium-button-secondary px-4 py-2">
                        <i class="fa-solid fa-upload"></i>
                        <span>Publish Plan</span>
                    </button>
                @endif
                <button class="premium-button px-4 py-2">
                    <i class="fa-solid fa-pen text-sm"></i>
                    <span>Edit Plan</span>
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="border-b border-slate-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'border-brand-primary text-brand-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Plan Overview
                </button>
                <button @click="activeTab = 'themes'"
                    :class="activeTab === 'themes' ? 'border-brand-primary text-brand-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Themes / Pillars
                </button>
                <button @click="activeTab = 'mapping'"
                    :class="activeTab === 'mapping' ? 'border-brand-primary text-brand-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    External Mapping
                </button>
            </nav>
        </div>

        <!-- Tab Contents -->

        <!-- Overview Tab -->
        <div x-show="activeTab === 'overview'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <!-- Vision -->
                <div class="premium-card p-6 border-l-4 border-indigo-400 bg-gradient-to-br from-indigo-50/50 to-white">
                    <h3 class="text-sm font-bold text-indigo-400 uppercase tracking-widest mb-3">Vision</h3>
                    <p class="text-slate-700 text-lg italic font-display leading-relaxed">
                        "{{ $plan->plan_vision ?? 'No vision defined.' }}"</p>
                </div>

                <!-- Mission -->
                <div class="premium-card p-6 border-l-4 border-emerald-400 bg-gradient-to-br from-emerald-50/50 to-white">
                    <h3 class="text-sm font-bold text-emerald-400 uppercase tracking-widest mb-3">Mission</h3>
                    <p class="text-slate-700 text-lg leading-relaxed">{{ $plan->plan_mission ?? 'No mission defined.' }}</p>
                </div>

                <!-- Values -->
                <div class="premium-card p-6">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-3">Core Values</h3>
                    <p class="text-slate-600 whitespace-pre-line">{{ $plan->plan_values ?? 'No values defined.' }}</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="premium-card p-6">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Plan Statistics</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b border-slate-50">
                            <span class="text-slate-500">From</span>
                            <span class="font-bold text-slate-700">{{ $plan->plan_from }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-slate-50">
                            <span class="text-slate-500">To</span>
                            <span class="font-bold text-slate-700">{{ $plan->plan_to }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-slate-50">
                            <span class="text-slate-500">Themes</span>
                            <span class="font-bold text-slate-700">{{ $plan->themes->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Themes Tab -->
        <div x-show="activeTab === 'themes'" style="display: none;">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-slate-700">Strategic Themes</h3>
                <button class="premium-button px-4 py-2 text-xs">
                    <i class="fa-solid fa-plus"></i> Add Theme
                </button>
            </div>

            <div class="space-y-4">
                @forelse($plan->themes as $theme)
                    <div class="premium-card p-5 hover:border-brand-primary transition-colors border border-transparent">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-premium text-lg">{{ $theme->theme_title }}</h4>
                                <p class="text-slate-500 mt-1 text-sm">{{ $theme->theme_description }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-bold">Weight:
                                    {{ $theme->theme_weight }}%</span>
                                <button
                                    class="w-8 h-8 rounded-full hover:bg-slate-50 text-slate-400 hover:text-indigo-600 transition-colors">
                                    <i class="fa-solid fa-pencil"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 bg-slate-50 rounded-xl border-dashed border-2 border-slate-200">
                        <p class="text-slate-400">No themes defined yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Mapping Tab -->
        <div x-show="activeTab === 'mapping'" style="display: none;">
            <div class="text-center py-20 bg-slate-50 rounded-xl border-dashed border-2 border-slate-200">
                <i class="fa-solid fa-share-nodes text-4xl text-slate-300 mb-4"></i>
                <p class="text-slate-500 font-bold">External Mapping</p>
                <p class="text-slate-400 text-sm">Mapping features coming soon.</p>
            </div>
        </div>

    </div>
@endsection
