@extends('layouts.app')

@section('title', 'My Action Items')
@section('subtitle', 'Inbound correspondence tasks assigned to you')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-premium">My Inbound Action Items</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $actionItems->total() }} items assigned to you</p>
        </div>
    </div>

    {{-- Status Filter --}}
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('emp.inbound.index') }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ !request('status') ? 'bg-indigo-600 text-white shadow-md' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            All
        </a>
        @foreach(['Pending','In Progress','Completed','Closed'] as $st)
        <a href="{{ route('emp.inbound.index', ['status'=>$st]) }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request('status')==$st ? 'bg-indigo-600 text-white shadow-md' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            {{ $st }}
        </a>
        @endforeach
    </div>

    {{-- Action Items Grid --}}
    @forelse($actionItems as $item)
    @php
        $acSt = match($item->status) {
            'Completed','Closed' => ['bg'=>'bg-emerald-50 text-emerald-700', 'dot'=>'bg-emerald-400'],
            'In Progress'        => ['bg'=>'bg-blue-50 text-blue-700',       'dot'=>'bg-blue-400'],
            default              => ['bg'=>'bg-amber-50 text-amber-700',     'dot'=>'bg-amber-400'],
        };
        $isOverdue = $item->due_date && \Carbon\Carbon::parse($item->due_date)->isPast() && !in_array($item->status, ['Completed','Closed']);
    @endphp
    <div class="premium-card p-6 {{ $isOverdue ? 'border-l-4 border-red-400' : '' }}">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                {{-- Status + Action type --}}
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg {{ $acSt['bg'] }} text-xs font-bold">
                        <span class="w-1.5 h-1.5 rounded-full {{ $acSt['dot'] }} inline-block"></span>
                        {{ $item->status }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">
                        <i class="fa-solid fa-arrow-right text-xs"></i> {{ $item->action_required }}
                    </span>
                    @if($isOverdue)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-50 text-red-600 text-xs font-bold">
                        <i class="fa-solid fa-exclamation-triangle text-xs"></i> Overdue
                    </span>
                    @endif
                </div>

                {{-- Correspondence Summary --}}
                <h3 class="text-base font-bold text-slate-800 mb-1">{{ $item->correspondence->subject }}</h3>
                <p class="text-sm text-slate-500">
                    <span class="font-medium text-indigo-600">{{ $item->correspondence->reference_code }}</span>
                    · {{ $item->correspondence->entity->entity_name ?? 'Unknown Entity' }}
                </p>

                {{-- Due Date --}}
                @if($item->due_date)
                <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                    <i class="fa-solid fa-calendar text-xs"></i>
                    Due: <span class="{{ $isOverdue ? 'text-red-500 font-bold' : '' }}">{{ \Carbon\Carbon::parse($item->due_date)->format('M d, Y') }}</span>
                    <span class="ml-1 text-slate-300">({{ \Carbon\Carbon::parse($item->due_date)->diffForHumans() }})</span>
                </p>
                @endif

                {{-- Action Note Preview --}}
                @if($item->action_note)
                <div class="mt-3 p-3 bg-slate-50 rounded-lg border border-slate-100">
                    <p class="text-xs font-bold text-slate-400 mb-1">Your Action Note:</p>
                    <p class="text-sm text-slate-600 line-clamp-2">{{ $item->action_note }}</p>
                </div>
                @endif
            </div>

            {{-- View Button --}}
            <a href="{{ route('emp.inbound.show', $item->action_id) }}"
               class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 text-white text-sm font-semibold shadow-lg hover:shadow-xl hover:scale-105 transition-all">
                <i class="fa-solid fa-eye"></i>
                <span>View & Act</span>
            </a>
        </div>
    </div>
    @empty
    <div class="premium-card p-20 text-center">
        <i class="fa-solid fa-check-double text-5xl text-slate-100 mb-4 block"></i>
        <p class="text-lg font-bold text-slate-400">No action items assigned to you</p>
        <p class="text-sm text-slate-300 mt-1">You're all caught up!</p>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($actionItems->hasPages())
    <div>{{ $actionItems->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
