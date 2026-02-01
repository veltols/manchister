@extends('layouts.app')

@section('title', 'My Tickets')
@section('subtitle', 'Support requests and issues')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-slate-800">Support Tickets</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $tickets->total() }} total tickets</p>
        </div>
        <button onclick="openModal('newTicketModal')" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
            <i class="fa-solid fa-plus"></i>
            <span>New Ticket</span>
        </button>
    </div>

    <!-- Filter Tabs -->
    <div class="premium-card p-2">
        <div class="flex gap-2">
            <a href="{{ route('emp.tickets.index') }}" class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $stt == 0 ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                All Tickets
            </a>
            <a href="{{ route('emp.tickets.index', ['stt' => 1]) }}" class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $stt == 1 ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                Open
            </a>
            <a href="{{ route('emp.tickets.index', ['stt' => 2]) }}" class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $stt == 2 ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                In Progress
            </a>
            <a href="{{ route('emp.tickets.index', ['stt' => 3]) }}" class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $stt == 3 ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                Resolved
            </a>
        </div>
    </div>

    <!-- Tickets Table Area -->
    <div class="space-y-4">
        <div class="overflow-x-auto px-1 pb-4">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">REF</th>
                        <th class="text-left">Subject</th>
                        <th class="text-left">Category</th>
                        <th class="text-center">Priority</th>
                        <th class="text-center">Status</th>
                        <th class="text-left">Created</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td>
                            <span class="font-mono text-sm font-semibold text-slate-600">{{ $ticket->ticket_ref }}</span>
                        </td>
                        <td class="max-w-xs">
                            <span class="font-semibold text-slate-800 block truncate" title="{{ $ticket->ticket_subject }}">{{ $ticket->ticket_subject }}</span>
                        </td>
                        <td>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-800 text-sm font-medium">
                                <i class="fa-solid fa-tag text-xs"></i>
                                {{ $ticket->category_name }}
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $priorityConfig = match($ticket->priority_name) {
                                    'High', 'Urgent' => ['bg' => 'from-red-500 to-rose-600', 'icon' => 'circle-exclamation'],
                                    'Medium' => ['bg' => 'from-amber-500 to-orange-600', 'icon' => 'circle'],
                                    default => ['bg' => 'from-slate-500 to-slate-600', 'icon' => 'circle']
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r {{ $priorityConfig['bg'] }} text-white text-xs font-bold shadow-md">
                                <i class="fa-solid fa-{{ $priorityConfig['icon'] }}"></i>
                                {{ $ticket->priority_name }}
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $statusConfig = match($ticket->status_name) {
                                    'Resolved', 'Closed' => ['bg' => 'from-green-500 to-emerald-600', 'icon' => 'check'],
                                    'In Progress' => ['bg' => 'from-blue-500 to-cyan-600', 'icon' => 'spinner'],
                                    default => ['bg' => 'from-yellow-500 to-amber-600', 'icon' => 'clock']
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r {{ $statusConfig['bg'] }} text-white text-xs font-bold shadow-md">
                                <i class="fa-solid fa-{{ $statusConfig['icon'] }}"></i>
                                {{ $ticket->status_name }}
                            </span>
                        </td>
                        <td>
                            <span class="text-sm text-slate-600">{{ \Carbon\Carbon::parse($ticket->ticket_added_date)->format('M d, Y') }}</span>
                        </td>
                        <td>
                            <div class="flex items-center justify-center">
                                <a href="#" class="w-9 h-9 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md" title="View Details">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                    <i class="fa-solid fa-ticket text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 font-medium">No tickets found</p>
                                <button onclick="openModal('newTicketModal')" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm">
                                    Create your first ticket
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($tickets->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $tickets->appends(['stt' => $stt])->links() }}
        </div>
        @endif
    </div>

</div>

<!-- New Ticket Modal -->
<div id="newTicketModal" class="modal">
    <div class="modal-backdrop" onclick="closeModal('newTicketModal')"></div>
    <div class="modal-content max-w-2xl p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-display font-bold text-slate-800">Create New Ticket</h2>
            <button onclick="closeModal('newTicketModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('emp.tickets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-heading text-indigo-600 mr-2"></i>Subject
                    </label>
                    <input type="text" name="ticket_subject" class="premium-input w-full px-4 py-3 text-sm" placeholder="Brief description of your issue" required>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-tag text-indigo-600 mr-2"></i>Category
                        </label>
                        <select name="category_id" class="premium-input w-full px-4 py-3 text-sm" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-flag text-indigo-600 mr-2"></i>Priority
                        </label>
                        <select name="priority_id" class="premium-input w-full px-4 py-3 text-sm" required>
                            @foreach($priorities as $pri)
                                <option value="{{ $pri->priority_id }}">{{ $pri->priority_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-align-left text-indigo-600 mr-2"></i>Description
                    </label>
                    <textarea name="ticket_description" class="premium-input w-full px-4 py-3 text-sm" rows="5" placeholder="Provide detailed information about your issue..." required></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-paperclip text-indigo-600 mr-2"></i>Attachment (Optional)
                    </label>
                    <input type="file" name="ticket_attachment" class="premium-input w-full px-4 py-3 text-sm">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('newTicketModal')" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-paper-plane mr-2"></i>Create Ticket
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
