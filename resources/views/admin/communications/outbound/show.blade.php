@extends('layouts.app')

@section('title', 'GM Review: ' . $record->communication_code)
@section('subtitle', 'Assign action items and record final decision (Form 1)')

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
            <div class="lg:col-span-2 space-y-8">
                
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
                        <button onclick="openModal('actionModal')" class="px-4 py-2 bg-brand text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:scale-105 transition-all">
                            <i class="fa-solid fa-plus mr-1"></i> Add Task
                        </button>
                    </div>

                    <div class="space-y-4">
                        @forelse($record->actionItems as $action)
                            <div class="flex items-center justify-between p-6 bg-slate-50 rounded-2xl border border-slate-100 relative group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-brand font-bold shadow-sm">
                                        {{ substr($action->assignedTo->employee_name ?? 'E', 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Assigned To: {{ $action->assignedTo->employee_name }}</span>
                                        <span class="text-sm font-bold text-slate-700">{{ $action->action_required }}</span>
                                        <span class="text-[10px] font-bold text-brand mt-1 uppercase">Due: {{ \Carbon\Carbon::parse($action->due_date)->format('Y-m-d') }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-white border border-slate-200 text-slate-500 shadow-sm">{{ $action->status }}</span>
                                    <form action="{{ route('admin.communications.outbound.action.destroy', [$record->communication_id, $action->action_id]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-400 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
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
                </div>

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
                        GM Decision (Form 1)
                    </h3>

                    <form action="{{ route('admin.communications.outbound.decide', $record->communication_id) }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Final Decision</label>
                            <div class="grid grid-cols-1 gap-3">
                                <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-100 hover:bg-green-50 transition-all cursor-pointer group">
                                    <input type="radio" name="decision" value="approved" class="text-green-600 focus:ring-green-500" required>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-700 group-hover:text-green-700">Approve & Forward</span>
                                        <span class="text-[10px] text-slate-400">Moves to Liaison Officer (Form 2)</span>
                                    </div>
                                </label>
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
        <div class="modal-content max-w-md p-8">
            <h2 class="text-2xl font-display font-bold text-premium mb-8">Assign Action Item</h2>
            <form action="{{ route('admin.communications.outbound.action.store', $record->communication_id) }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Assign To</label>
                    <select name="assigned_to_id" class="premium-input w-full select2" required>
                        <option value="">Select Employee...</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->employee_id }}">{{ $emp->employee_name }} ({{ $emp->department->department_name ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Action Required</label>
                    <textarea name="action_required" rows="3" class="premium-input w-full" placeholder="Describe the task..." required></textarea>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Due Date</label>
                    <input type="date" name="due_date" class="premium-input w-full" required>
                </div>
                <button type="submit" class="w-full py-4 bg-brand text-white rounded-2xl font-bold text-xs uppercase shadow-lg shadow-brand/20">
                    Assign Task
                </button>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/attachment-preview.js') }}"></script>
@endsection
