@extends('layouts.app')

@section('title', 'Strategic Plans')
@section('subtitle', 'Manage and view strategic plans')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <div class="flex justify-end">
            <button class="premium-button px-6 py-2">
                <i class="fa-solid fa-plus"></i>
                <span>Create Strategic Plan</span>
            </button>
        </div>

        @forelse($plans as $plan)
            <div
                class="premium-card p-6 hover:shadow-lg transition-shadow border-l-4 {{ $plan->plan_status_id == 1 ? 'border-amber-400' : 'border-indigo-500' }}">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-display font-bold text-premium">{{ $plan->plan_title }}</h2>
                        <div class="flex items-center gap-2 mt-2">
                            <span
                                class="px-2 py-1 rounded text-[10px] uppercase font-bold tracking-wider {{ $plan->plan_status_id == 1 ? 'bg-amber-50 text-amber-600' : 'bg-indigo-50 text-indigo-600' }}">
                                {{ $plan->plan_status_id == 1 ? 'Draft' : 'Published' }}
                            </span>
                            <span class="text-slate-400 text-xs flex items-center gap-1">
                                <i class="fa-solid fa-building"></i>
                                {{ $plan->department->department_name ?? 'Organization' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <a href="#" class="premium-button-secondary px-4 py-2 text-xs">
                            {{ $plan->plan_status_id == 1 ? 'Manage Plan' : 'View Overview' }}
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-4 border-y border-slate-50">
                    <div class="text-center">
                        <span class="block text-2xl font-bold text-slate-700">{{ $plan->themes_count }}</span>
                        <span class="text-[10px] uppercase tracking-widest text-slate-400">Themes</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-2xl font-bold text-slate-700">{{ $plan->objectives_count }}</span>
                        <span class="text-[10px] uppercase tracking-widest text-slate-400">Objectives</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-xl font-bold text-slate-700">{{ $plan->plan_from }}</span>
                        <span class="text-[10px] uppercase tracking-widest text-slate-400">Start Year</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-xl font-bold text-slate-700">{{ $plan->plan_to }}</span>
                        <span class="text-[10px] uppercase tracking-widest text-slate-400">End Year</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <i class="fa-solid fa-chess-knight text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-500">No Strategic Plans Found</h3>
                <p class="text-slate-400 text-sm mt-1">Get started by creating a new plan.</p>
            </div>
        @endforelse

        <div class="mt-4">
            {{ $plans->links() }}
        </div>
    </div>
@endsection
