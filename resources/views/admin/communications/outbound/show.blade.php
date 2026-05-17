@extends('layouts.app')

@section('title', 'GM Review: ' . $record->communication_code)
@section('subtitle', 'Assign action items and record final decision')

@section('content')
    <div class="space-y-8 animate-fade-in-up pb-20">

        <!-- Info Header -->
        <div class="premium-card p-8 bg-gradient-to-br from-slate-900 to-slate-800 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 p-12 opacity-10 rotate-12">
                <i class="fa-solid fa-paper-plane text-9xl"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-3 py-1 bg-white/10 rounded-full text-[10px] font-bold uppercase tracking-widest">{{ $record->type->communication_type_name }}</span>
                    <span class="px-3 py-1 {{ $record->priority == 'high' ? 'bg-red-500/20 text-red-300' : 'bg-green-500/20 text-green-300' }} rounded-full text-[10px] font-bold uppercase tracking-widest">{{ $record->priority }} Priority</span>
                </div>
                <h1 class="text-4xl font-display font-bold leading-tight mb-2">{{ $record->communication_subject }}</h1>
                <p class="text-slate-400 font-medium max-w-2xl">{{ $record->communication_description }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Source Data & LM Comments -->
            <div class="lg:col-span-2">
                
                <!-- Tabs -->
                <div class="flex items-center gap-2 border-b border-slate-200 mb-6">
                    <button type="button" onclick="switchTab('details')" id="tab-btn-details" class="px-6 py-3 text-sm font-bold uppercase tracking-widest text-brand border-b-2 border-brand transition-colors">
                        Details & Actions
                    </button>
                    <button type="button" onclick="switchTab('logs')" id="tab-btn-logs" class="px-6 py-3 text-sm font-bold uppercase tracking-widest text-slate-400 border-b-2 border-transparent hover:text-slate-700 transition-colors">
                        Activity Logs
                    </button>
                </div>

                <!-- Tab Content: Details -->
                <div id="tab-content-details" class="space-y-8 block">
                    
                    <!-- LM Review Log -->
                    <div class="premium-card p-8 border-l-4 border-green-500">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-user-tie text-green-500"></i>
                        Line Manager Review Notes
                    </h3>
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                        <p class="text-slate-700 italic font-medium leading-relaxed">
                            "{{ $record->approved_1_notes ?? 'No specific notes provided by the Line Manager.' }}"
                        </p>
                        <div class="mt-4 flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase">
                            <i class="fa-solid fa-calendar-check"></i>
                            Approved on {{ $record->approved_1_date ? $record->approved_1_date->format('Y-m-d H:i') : 'N/A' }}
                        </div>
                    </div>
                </div>

                <!-- Source Attachments -->
                <div class="premium-card p-8">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-paperclip text-brand"></i>
                        Original Attachments
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($record->attachments as $file)
                            <div onclick="window.previewRemoteFile('{{ asset('storage/' . $file->file_path) }}', '{{ $file->file_name }}')" class="flex items-center justify-between p-4 bg-white rounded-xl border border-slate-100 hover:border-brand hover:shadow-md transition-all group cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-brand/10 group-hover:text-brand transition-colors">
                                        <i class="fa-solid fa-file-pdf text-xl"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-700 truncate max-w-[150px]">{{ $file->file_name }}</span>
                                        <span class="text-[10px] text-slate-400 uppercase font-bold">{{ strtoupper($file->file_type) }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] text-brand font-bold uppercase opacity-0 group-hover:opacity-100 transition-opacity">Preview</span>
                                    <i class="fa-solid fa-eye text-slate-300 group-hover:text-brand"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Action Items Assigned -->
                <div class="premium-card p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-tasks text-brand"></i>
                            Assigned Action Items
                        </h3>
                        @if($record->is_approved_2 == 0)
                            @if($liaisonExists)
                                <button onclick="openModal('actionModal')" class="px-4 py-2 bg-brand text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:scale-105 transition-all">
                                    <i class="fa-solid fa-plus mr-1"></i> Add Task
                                </button>
                            @else
                                <button onclick="Swal.fire('Notice', 'No active Liaison Officer found in the system. Please create or activate a Liaison account first.', 'warning')" class="px-4 py-2 bg-slate-200 text-slate-500 rounded-xl text-xs font-bold uppercase tracking-wider cursor-not-allowed">
                                    <i class="fa-solid fa-plus mr-1"></i> Add Task
                                </button>
                            @endif
                        @endif
                    </div>

                    <div class="space-y-4">
                        @forelse($record->actionItems as $action)
                            <div class="flex items-center justify-between p-6 bg-slate-50 rounded-2xl border border-slate-100 relative group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-brand font-bold shadow-sm">
                                        {{ substr($action->assignedTo->employee_name ?? 'L', 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-black uppercase text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded">{{ $action->action_type }}</span>
                                            <span class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Assigned To: {{ $action->assignedTo->employee_name ?? 'Liaison Officer' }}</span>
                                        </div>
                                        <span class="text-sm font-bold text-slate-700 mt-1">{{ $action->action_required }}</span>
                                        <span class="text-[10px] font-bold text-brand mt-1 uppercase">Due: {{ \Carbon\Carbon::parse($action->due_date)->format('Y-m-d') }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-white border border-slate-200 text-slate-500 shadow-sm">{{ $action->status }}</span>
                                    <form action="{{ route('admin.communications.outbound.action.destroy', [$record->communication_id, $action->action_id]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-slate-50 text-brand flex items-center justify-center hover:bg-brand hover:text-white transition-all">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 text-slate-400 italic text-sm">
                                No action items assigned yet.
                            </div>
                        @endforelse
                    </div>

                    @if($record->is_approved_2 == 0)
                        <div class="mt-8 pt-8 border-t border-slate-100 flex justify-end">
                            <form action="{{ route('admin.communications.outbound.decide', $record->communication_id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="decision" value="approved">
                                <input type="hidden" name="notes" value="Approved via quick action.">
                                <button type="submit" class="premium-button">
                                    <i class="fa-solid fa-check-double"></i>
                                    Approve & Forward to Liaison
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                </div> <!-- End Details Tab -->

                <!-- Tab Content: Logs -->
                <div id="tab-content-logs" class="hidden space-y-6">
                    <div class="premium-card p-8">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-history text-slate-500"></i>
                            Communication Timeline & Logs
                        </h3>
                        
                        <div class="relative border-l border-slate-200 ml-3 space-y-8">
                            @forelse($logs as $log)
                                <div class="relative pl-6">
                                    <div class="absolute -left-1.5 top-1 w-3 h-3 rounded-full bg-indigo-500 ring-4 ring-indigo-50"></div>
                                    <div class="mb-1 text-xs text-slate-400 font-bold uppercase">{{ \Carbon\Carbon::parse($log->log_date)->format('M d, Y - h:i A') }}</div>
                                    <h4 class="text-sm font-bold text-slate-700 mb-1">{{ str_replace('_', ' ', $log->log_action) }}</h4>
                                    <p class="text-sm text-slate-600 mb-2">{{ $log->log_remark }}</p>
                                    <div class="text-[10px] text-slate-400 uppercase font-bold">
                                        <i class="fa-solid fa-user mr-1"></i> By: {{ $log->logger->employee_name ?? 'System' }}
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 text-slate-400 italic text-sm">
                                    No activity logs recorded yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div> <!-- End Logs Tab -->

            </div>

            <!-- Right Column: Final Decision -->
            <div class="space-y-8">
                
                <!-- Initiator Card -->
                <div class="premium-card p-6 bg-indigo-50/50">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4 italic">Requested By</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-brand font-bold text-xl shadow-sm">
                            {{ substr($record->employee->employee_name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-700">{{ $record->employee->employee_name }}</h4>
                            <p class="text-xs text-slate-400">{{ $record->employee->department->department_name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Final Decision Form -->
                <div class="premium-card p-8 border-t-4 border-brand">
                    <h3 class="text-sm font-bold text-premium uppercase tracking-widest mb-8 flex items-center gap-2">
                        <i class="fa-solid fa-gavel text-brand"></i>
                        GM Decision
                    </h3>

                    <form action="{{ route('admin.communications.outbound.decide', $record->communication_id) }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Final Decision</label>
                            <div class="grid grid-cols-1 gap-3">
                                <!-- <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 hover:bg-green-50 transition-all cursor-pointer group">
                                    <input type="radio" name="decision" value="approved" class="text-green-600 focus:ring-green-500" required>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-700 group-hover:text-green-700">Approve & Forward</span>
                                        <span class="text-[10px] text-slate-400">Moves to Liaison Officer</span>
                                    </div>
                                </label> -->
                                <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 hover:bg-orange-50 transition-all cursor-pointer group">
                                    <input type="radio" name="decision" value="modifications_required" class="text-orange-600 focus:ring-orange-500">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-700 group-hover:text-orange-700">Return to Employee</span>
                                        <span class="text-[10px] text-slate-400">Request changes/corrections</span>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 hover:bg-red-50 transition-all cursor-pointer group">
                                    <input type="radio" name="decision" value="rejected" class="text-red-600 focus:ring-red-500">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-700 group-hover:text-red-700">Reject Request</span>
                                        <span class="text-[10px] text-slate-400">Deny the communication request</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">GM Review Notes</label>
                            <textarea name="notes" rows="4" class="premium-input w-full" placeholder="Enter your review comments..."></textarea>
                        </div>

                        <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold text-xs uppercase shadow-xl hover:bg-brand transition-all">
                            Submit Final Decision
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Add Action Item Modal -->
    <div class="modal" id="actionModal">
        <div class="modal-backdrop" onclick="closeModal('actionModal')"></div>
        <div class="modal-content max-w-4xl p-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-display font-bold text-premium">Assign Action Items</h2>
                <button type="button" onclick="addActionRow()" class="px-4 py-2 bg-brand/10 text-brand rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-brand/20 transition-all">
                    <i class="fa-solid fa-plus mr-1"></i> Add Another Row
                </button>
            </div>
            
            <form action="{{ route('admin.communications.outbound.action.store', $record->communication_id) }}" method="POST">
                @csrf
                <div class="overflow-x-auto mb-8">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="pb-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Action Required</th>
                                <th class="pb-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Due Date</th>
                                <th class="pb-4 text-right"></th>
                            </tr>
                        </thead>
                        <tbody id="actionRows">
                            <tr class="action-row group">
                                <td class="py-4 pr-4">
                                    <input type="hidden" name="actions[0][action_type]" value="External">
                                    <select name="actions[0][action_required]" class="premium-input w-full" required>
                                        <option value="">Select Action...</option>
                                        <option value="Review">Review</option>
                                        <option value="Approve">Approve</option>
                                        <option value="Reject">Reject</option>
                                        <option value="Provide Info">Provide Info</option>
                                        <option value="Forward">Forward</option>
                                        <option value="Archive">Archive</option>
                                    </select>
                                </td>
                                <td class="py-4 pr-4">
                                    <input type="date" name="actions[0][due_date]" class="premium-input w-full" required>
                                </td>
                                <td class="py-4 text-right">
                                    <button type="button" onclick="removeActionRow(this)" class="w-10 h-10 rounded-xl bg-slate-50 text-brand flex items-center justify-center hover:bg-brand hover:text-white transition-all">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 mb-8">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 italic">Note</p>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        These action items will be automatically assigned to the **primary Liaison Officer** for execution.
                    </p>
                </div>

                <button type="submit" class="w-full py-4 bg-brand text-white rounded-2xl font-bold text-xs uppercase shadow-lg shadow-brand/20">
                    Assign All Tasks to Liaison
                </button>
            </form>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            if(tab === 'details') {
                document.getElementById('tab-content-details').classList.remove('hidden');
                document.getElementById('tab-content-logs').classList.add('hidden');
                
                document.getElementById('tab-btn-details').classList.add('text-brand', 'border-brand');
                document.getElementById('tab-btn-details').classList.remove('text-slate-400', 'border-transparent');
                
                document.getElementById('tab-btn-logs').classList.remove('text-brand', 'border-brand');
                document.getElementById('tab-btn-logs').classList.add('text-slate-400', 'border-transparent');
            } else {
                document.getElementById('tab-content-details').classList.add('hidden');
                document.getElementById('tab-content-logs').classList.remove('hidden');
                
                document.getElementById('tab-btn-logs').classList.add('text-brand', 'border-brand');
                document.getElementById('tab-btn-logs').classList.remove('text-slate-400', 'border-transparent');
                
                document.getElementById('tab-btn-details').classList.remove('text-brand', 'border-brand');
                document.getElementById('tab-btn-details').classList.add('text-slate-400', 'border-transparent');
            }
        }

        let actionRowCount = 1;
        function addActionRow() {
            const tbody = document.getElementById('actionRows');
            const newRow = document.createElement('tr');
            newRow.className = 'action-row group';
            newRow.innerHTML = `
                <td class="py-4 pr-4">
                    <input type="hidden" name="actions[${actionRowCount}][action_type]" value="External">
                    <select name="actions[${actionRowCount}][action_required]" class="premium-input w-full" required>
                        <option value="">Select Action...</option>
                        <option value="Review">Review</option>
                        <option value="Approve">Approve</option>
                        <option value="Reject">Reject</option>
                        <option value="Provide Info">Provide Info</option>
                        <option value="Forward">Forward</option>
                        <option value="Archive">Archive</option>
                    </select>
                </td>
                <td class="py-4 pr-4">
                    <input type="date" name="actions[${actionRowCount}][due_date]" class="premium-input w-full" required>
                </td>
                <td class="py-4 text-right">
                    <button type="button" onclick="removeActionRow(this)" class="w-10 h-10 rounded-xl bg-slate-50 text-brand flex items-center justify-center hover:bg-brand hover:text-white transition-all">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(newRow);
            actionRowCount++;
        }

        function removeActionRow(btn) {
            const rows = document.querySelectorAll('.action-row');
            if (rows.length > 1) {
                btn.closest('tr').remove();
            }
        }
    </script>
    <script src="{{ asset('js/attachment-preview.js') }}"></script>
@endsection
