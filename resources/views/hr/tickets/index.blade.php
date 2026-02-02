@extends('layouts.app')

@section('title', 'Support Tickets')
@section('subtitle', 'Manage help desk requests')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h1 class="text-2xl font-bold font-display text-premium">Tickets</h1>
            <a href="{{ route('hr.tickets.create') }}" class="premium-button px-6 py-2">
                <i class="fa-solid fa-plus"></i>
                <span class="ml-2">New Ticket</span>
            </a>
        </div>

        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-left">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Reference</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">
                                Options</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($tickets as $ticket)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-mono text-slate-500">{{ $ticket->ticket_ref }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-slate-700">{{ $ticket->ticket_subject }}</div>
                                    <div class="text-xs text-slate-400 mt-1">{{ $ticket->created_date }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $ticket->category->category_name ?? 'General' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-600">
                                        {{ $ticket->priority->priority_name ?? 'Normal' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700">
                                        {{ $ticket->status->status_name ?? 'Open' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('hr.tickets.show', $ticket->ticket_id) }}"
                                        class="text-slate-400 hover:text-indigo-600 transition-colors">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-ticket text-4xl mb-3 opacity-20"></i>
                                    <p>No tickets found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-100">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
@endsection
