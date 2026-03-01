@extends('layouts.app')

@section('title', 'Strategic Plans')
@section('subtitle', 'Manage and view organizational strategic plans')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        {{-- Header Actions --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                         style="background: linear-gradient(135deg, #004F68 0%, #006a8a 100%);">
                        <i class="fa-solid fa-chess-knight text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-700">All Strategic Plans</h2>
                        <p class="text-xs text-slate-400">{{ $plans->total() }} plan(s) found</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('emp.ext.strategies.create') }}" class="premium-button px-6 py-2.5">
                <i class="fa-solid fa-plus text-sm"></i>
                <span>New Strategic Plan</span>
            </a>
        </div>

        {{-- Plans List --}}
        @forelse($plans as $plan)
            <div class="premium-card p-6 border-l-4 {{ $plan->is_published ? 'border-emerald-400' : 'border-amber-400' }} hover:shadow-xl transition-all duration-300">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-5">
                    <div class="flex-1">
                        <div class="flex items-center flex-wrap gap-2 mb-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono bg-slate-50 px-2 py-0.5 rounded">
                                {{ $plan->plan_ref }}
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-[10px] uppercase font-bold tracking-wider
                                {{ $plan->is_published ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                <i class="fa-solid {{ $plan->is_published ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                                {{ $plan->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </div>
                        <h2 class="text-xl font-display font-bold text-premium mt-1">{{ $plan->plan_title }}</h2>
                        <div class="flex items-center gap-3 mt-2 flex-wrap">
                            <span class="text-slate-400 text-xs flex items-center gap-1">
                                <i class="fa-solid fa-building"></i>
                                {{ $plan->department->department_name ?? 'Organization-Wide' }}
                            </span>
                            <span class="text-slate-300">•</span>
                            <span class="text-slate-400 text-xs flex items-center gap-1">
                                <i class="fa-solid fa-calendar-range"></i>
                                {{ $plan->plan_from }} – {{ $plan->plan_to }}
                            </span>
                            @if($plan->plan_period)
                                <span class="text-slate-300">•</span>
                                <span class="text-slate-400 text-xs flex items-center gap-1">
                                    <i class="fa-solid fa-hourglass-half"></i>
                                    {{ $plan->plan_period }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('emp.ext.strategies.show', $plan->plan_id) }}"
                           class="premium-button px-5 py-2 text-sm">
                            <i class="fa-solid {{ $plan->is_published ? 'fa-eye' : 'fa-pen-to-square' }} text-xs"></i>
                            <span>{{ $plan->is_published ? 'View Plan' : 'Manage Plan' }}</span>
                        </a>
                    </div>
                </div>

                {{-- Stats Row --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-5 border-t border-slate-50">
                    <div class="text-center">
                        <span class="block text-2xl font-bold text-slate-700">{{ $plan->themes_count }}</span>
                        <span class="text-[10px] uppercase tracking-widest text-slate-400 mt-0.5 block">Themes</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-2xl font-bold text-slate-700">{{ $plan->objectives_count }}</span>
                        <span class="text-[10px] uppercase tracking-widest text-slate-400 mt-0.5 block">Objectives</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-xl font-bold text-slate-700">{{ $plan->plan_from }}</span>
                        <span class="text-[10px] uppercase tracking-widest text-slate-400 mt-0.5 block">Start Year</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-xl font-bold text-slate-700">{{ $plan->plan_to }}</span>
                        <span class="text-[10px] uppercase tracking-widest text-slate-400 mt-0.5 block">End Year</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-24 bg-white rounded-2xl border-2 border-dashed border-slate-200">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5"
                     style="background: linear-gradient(135deg, rgba(0,79,104,0.08) 0%, rgba(0,106,138,0.08) 100%);">
                    <i class="fa-solid fa-chess-knight text-3xl" style="color: #004F68;"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-500 mb-1">No Strategic Plans Yet</h3>
                <p class="text-slate-400 text-sm mb-6">Create your first strategic plan to get started.</p>
                <a href="{{ route('emp.ext.strategies.create') }}" class="premium-button px-8 py-2.5 inline-flex">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create Strategic Plan</span>
                </a>
            </div>
        @endforelse

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $plans->links() }}
        </div>
    </div>
@endsection
