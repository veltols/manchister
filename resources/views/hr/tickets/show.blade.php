@extends('layouts.app')

@section('title', 'Ticket Details')
@section('subtitle', $ticket->ticket_ref)

@section('content')
    <div class="max-w-3xl mx-auto space-y-6 animate-fade-in-up">

        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('hr.tickets.index') }}"
                class="text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span class="font-bold text-sm uppercase tracking-wider">Back to List</span>
            </a>
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                {{ $ticket->status->status_name ?? 'Open' }}
            </span>
        </div>

        <div class="premium-card p-8">
            <h1 class="text-2xl font-display font-bold text-premium mb-2">{{ $ticket->ticket_subject }}</h1>
            <div class="flex gap-4 text-xs text-slate-400 mb-6 pb-6 border-b border-slate-100">
                <span><i class="fa-solid fa-clock mr-1"></i> {{ $ticket->created_date }}</span>
                <span><i class="fa-solid fa-tag mr-1"></i> {{ $ticket->category->category_name ?? 'General' }}</span>
                <span><i class="fa-solid fa-flag mr-1"></i> {{ $ticket->priority->priority_name ?? 'Normal' }}</span>
            </div>

            <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed whitespace-pre-line">
                {{ $ticket->ticket_description }}
            </div>
        </div>

        <!-- Replies would go here -->
    </div>
@endsection
