@extends('layouts.app')

@section('title', 'My Outbound Tasks')
@section('subtitle', 'Actions assigned to you by the General Manager')

@section('content')
    <div class="space-y-6 animate-fade-in-up">
        
        <div class="premium-card overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-lg font-bold text-premium flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-brand"></i>
                    Action Required
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left">Communication</th>
                            <th class="text-left">Type</th>
                            <th class="text-left">Action Required</th>
                            <th class="text-center">Due Date</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $task->communication->communication_code }}</span>
                                        <span class="text-sm font-bold text-slate-700 truncate max-w-xs">{{ $task->communication->communication_subject }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase">{{ $task->action_type }}</span>
                                </td>
                                <td>
                                    <p class="text-sm text-slate-600 font-medium">{{ $task->action_required }}</p>
                                </td>
                                <td class="text-center">
                                    <span class="text-xs font-bold {{ \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status != 'Completed' ? 'text-red-500' : 'text-slate-500' }}">
                                        {{ $task->due_date }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter shadow-sm
                                        {{ $task->status == 'Completed' ? 'bg-green-500 text-white' : ($task->status == 'In Progress' ? 'bg-indigo-500 text-white' : 'bg-slate-100 text-slate-400') }}">
                                        {{ $task->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button onclick="openStatusModal({{ json_encode($task) }})" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 rounded-lg text-[10px] font-bold uppercase hover:bg-brand hover:text-white hover:border-brand transition-all">
                                        Update
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-20 opacity-20">
                                    <i class="fa-solid fa-clipboard-check text-6xl mb-4"></i>
                                    <p class="font-bold uppercase tracking-widest text-sm">No tasks assigned to you</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal" id="statusModal">
        <div class="modal-backdrop" onclick="closeModal('statusModal')"></div>
        <div class="modal-content max-w-md p-8">
            <h2 class="text-2xl font-display font-bold text-premium mb-2">Update Task Status</h2>
            <p id="modalAction" class="text-slate-500 text-sm mb-8 italic"></p>

            <form id="statusForm" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Current Status</label>
                    <select name="status" class="premium-input w-full" required>
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Completion Note / Feedback</label>
                    <textarea name="completion_note" rows="4" class="premium-input w-full" placeholder="Enter any notes about the task completion..."></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold text-xs uppercase shadow-xl hover:bg-brand transition-all">
                        Update Task
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openStatusModal(task) {
            document.getElementById('modalAction').innerText = task.action_required;
            document.getElementById('statusForm').action = `/emp/outbound-tasks/${task.action_id}/status`;
            document.getElementById('statusForm').querySelector('[name="status"]').value = task.status;
            document.getElementById('statusForm').querySelector('[name="completion_note"]').value = task.completion_note || '';
            openModal('statusModal');
        }
    </script>
@endsection
