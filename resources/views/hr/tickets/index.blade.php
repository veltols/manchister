@extends('layouts.app')

@section('title', 'Help Desk Tickets')
@section('subtitle', 'Manage support requests and technical issues.')

@section('content')
    <div class="flex flex-col h-full">
        <!-- Options Bar -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex gap-2">
                <button
                    class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium transition-all shadow-sm">
                    <i class="fa-solid fa-filter mr-2 text-slate-400"></i> Filter
                </button>
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" placeholder="Search tickets..."
                        class="pl-10 pr-4 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all outline-none text-sm w-64">
                </div>
            </div>
            <button onclick="openModal('newTicketModal')"
                class="premium-button from-indigo-600 to-purple-600 text-white px-6 py-2 rounded-xl shadow-lg hover:shadow-indigo-200 transition-all transform hover:-translate-y-0.5">
                <i class="fa-solid fa-plus mr-2"></i> New Ticket
            </button>
        </div>

        <!-- Tickets Table -->
        <div class="bg-white rounded-[20px] shadow-sm border border-slate-200 overflow-hidden flex-1">
            <div class="overflow-x-auto h-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50/50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="p-4">Ref</th>
                            <th class="p-4">Subject</th>
                            <th class="p-4">Category</th>
                            <th class="p-4">Requested By</th>
                            <th class="p-4">Date</th>
                            <th class="p-4">Priority</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($tickets as $ticket)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="p-4 font-mono text-xs text-slate-500">{{ $ticket->ticket_ref }}</td>
                                <td class="p-4">
                                    <span
                                        class="font-bold text-slate-700 block">{{ Str::limit($ticket->ticket_subject, 40) }}</span>
                                    <span
                                        class="text-xs text-slate-400">{{ Str::limit($ticket->ticket_description, 60) }}</span>
                                </td>
                                <td class="p-4 text-sm text-slate-600">{{ $ticket->category->category_name ?? '-' }}</td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                            {{ substr($ticket->addedBy->first_name ?? 'U', 0, 1) }}
                                        </div>
                                        <span
                                            class="text-sm font-medium text-slate-700">{{ $ticket->addedBy->first_name ?? 'Unknown' }}
                                            {{ $ticket->addedBy->last_name ?? '' }}</span>
                                    </div>
                                </td>
                                <td class="p-4 text-sm text-slate-500">
                                    {{ \Carbon\Carbon::parse($ticket->added_date)->format('M d, Y') }}
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold"
                                        style="background-color: #{{ $ticket->priority->priority_color ?? 'e2e8f0' }}20; color: #{{ $ticket->priority->priority_color ?? '64748b' }}">
                                        {{ $ticket->priority->priority_name ?? 'Normal' }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold"
                                        style="background-color: #{{ $ticket->status->status_color ?? 'e2e8f0' }}20; color: #{{ $ticket->status->status_color ?? '64748b' }}">
                                        {{ $ticket->status->status_name ?? 'Pending' }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('hr.tickets.show', $ticket->ticket_id) }}"
                                        class="w-9 h-9 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                        title="View Details">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-12 text-center text-slate-400">
                                    <i class="fa-solid fa-ticket-simple text-4xl mb-3 opacity-50"></i>
                                    <p>No tickets found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- New Ticket Modal -->
    <div class="modal" id="newTicketModal">
        <div class="modal-backdrop" onclick="closeModal('newTicketModal')"></div>
        <div class="modal-content max-w-2xl p-0 border-none shadow-2xl">
            <div class="p-6 bg-slate-900 text-white flex justify-between items-center rounded-t-[24px]">
                <div>
                    <h2 class="text-xl font-bold">Create New Ticket</h2>
                    <p class="text-white/60 text-sm mt-1">Submit a support request for assistance.</p>
                </div>
                <button onclick="closeModal('newTicketModal')" class="text-white/60 hover:text-white"><i
                        class="fa-solid fa-times"></i></button>
            </div>

            <form onsubmit="saveTicket(event)" class="p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-2 gap-6">
                    <!-- Requested By -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Requested
                            By</label>
                        <select name="added_by" class="premium-input w-full h-11 text-sm bg-slate-50">
                            @if(isset($employees) && count($employees) > 0)
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->employee_id }}" {{ (auth()->user()->employee->employee_id ?? 0) == $emp->employee_id ? 'selected' : '' }}>
                                        {{ $emp->first_name }} {{ $emp->last_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Category -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Category</label>
                        <select name="category_id" required class="premium-input w-full h-11 text-sm bg-slate-50">
                            <option value="">Select Category</option>
                            @if(isset($categories) && count($categories) > 0)
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <!-- Subject -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Subject</label>
                    <input type="text" name="ticket_subject" required class="premium-input w-full h-11 text-sm bg-slate-50"
                        placeholder="Brief summary of the issue">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Priority -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Priority</label>
                        <select name="priority_id" required class="premium-input w-full h-11 text-sm bg-slate-50">
                            @if(isset($priorities) && count($priorities) > 0)
                                @foreach($priorities as $pri)
                                    <option value="{{ $pri->priority_id ?? $pri->theme_id }}">{{ $pri->priority_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Attachment -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Attachment
                            (Optional)</label>
                        <input type="file" name="ticket_attachment"
                            class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Description</label>
                    <textarea name="ticket_description" required rows="4"
                        class="premium-input w-full p-4 text-sm bg-slate-50 resize-none"
                        placeholder="Detailed explanation of the problem..."></textarea>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit"
                        class="premium-button from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-xl shadow-lg shadow-indigo-100 font-bold tracking-wide transform active:scale-95 transition-all">
                        Submit Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        async function saveTicket(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            // Show loading state?

            try {
                const response = await fetch("{{ route('hr.tickets.store') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, // No Content-Type for FormData
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Success feedback
                    const btn = e.target.querySelector('button[type="submit"]');
                    const originalText = btn.innerText;
                    btn.innerText = 'Submitted!';
                    btn.classList.add('bg-green-600');

                    setTimeout(() => {
                        window.location.reload();
                    }, 800);
                } else {
                    alert('Error submitting ticket: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        }

        function viewTicket(id) {
            window.location.href = "{{ url('hr/tickets') }}/" + id;
        }
    </script>
@endsection