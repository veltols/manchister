@extends('layouts.app')

@section('title', 'Inbound — ' . $record->reference_code)
@section('subtitle', 'Correspondence detail — Form A / B view')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Back + Title --}}
    <div class="flex items-center gap-4">
        <a href="{{ request()->routeIs('emp.*') ? route('emp.inbound-liaison.index') : route('hr.inbound.index') }}"
           class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-100 transition-all">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-display font-bold text-premium">{{ $record->reference_code }}</h2>
            <p class="text-sm text-slate-500 mt-0.5">Registered {{ \Carbon\Carbon::parse($record->created_at)->format('M d, Y g:i A') }}</p>
        </div>
        <div class="ml-auto flex items-center gap-3">
            @if($record->status === 'Modifications Required')
            <button onclick="openModal('modifyInboundModal')" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-indigo-600 text-white shadow hover:bg-indigo-700 transition-all">
                <i class="fa-solid fa-pen-to-square"></i> Modify & Resubmit
            </button>
            @endif
            @php
                $statusStyle = match($record->status) {
                    'Pending Approval'       => 'bg-amber-50 text-amber-700 border border-amber-200',
                    'Resubmitted'            => 'bg-indigo-50 text-indigo-700 border border-indigo-200',
                    'Under Review'           => 'bg-blue-50 text-blue-700 border border-blue-200',
                    'Approved'               => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                    'Rejected'               => 'bg-red-50 text-red-700 border border-red-200',
                    'Modifications Required' => 'bg-orange-50 text-orange-700 border border-orange-200',
                    default                  => 'bg-slate-100 text-slate-600',
                };
            @endphp
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold {{ $statusStyle }}">
                <i class="fa-solid fa-circle-dot text-xs"></i>
                {{ $record->status }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ─── Left: Form A Details ──────────────────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Form A Card --}}
            <div class="premium-card p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold shadow">A</div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Form A — Registration Details</h3>
                        <p class="text-xs text-slate-400">Submitted by Liaison Officer</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Reference Code</p>
                        <p class="font-mono font-bold text-indigo-700">{{ $record->reference_code }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Type</p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold uppercase">
                            {{ $record->correspondence_type }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Received From</p>
                        <p class="font-semibold text-slate-800">{{ $record->entity->entity_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Date of Receipt</p>
                        <p class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($record->date_of_receipt)->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Priority</p>
                        @php $pc = ['high'=>'red','medium'=>'amber','low'=>'emerald'][$record->priority] ?? 'slate'; @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-{{ $pc }}-50 text-{{ $pc }}-700 text-xs font-bold uppercase">
                            {{ $record->priority }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Category</p>
                        <p class="font-semibold text-slate-800 capitalize">{{ str_replace('_', ' ', $record->category) }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Subject</p>
                        <p class="font-semibold text-slate-800">{{ $record->subject }}</p>
                    </div>
                    @if($record->description)
                    <div class="col-span-2">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Brief Description</p>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $record->description }}</p>
                    </div>
                    @endif
                    <div class="col-span-2">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Purpose / Reason</p>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $record->purpose }}</p>
                    </div>
                </div>
            </div>

            {{-- Attachments Card --}}
            @if($record->attachments->count() > 0)
            <div class="premium-card p-6">
                <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-paperclip text-indigo-400"></i> Attachments
                    <span class="ml-1 text-xs font-bold bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded-full">{{ $record->attachments->count() }}</span>
                </h3>
                <div class="space-y-3">
                    @foreach($record->attachments as $att)
                    <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank"
                       class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition-all group">
                        <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-500 flex-shrink-0">
                            <i class="fa-solid fa-file text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-700 truncate">{{ $att->file_name }}</p>
                            <p class="text-xs text-slate-400">{{ $att->file_type ?? 'File' }}</p>
                        </div>
                        <i class="fa-solid fa-arrow-up-right-from-square text-slate-300 group-hover:text-indigo-500 transition-colors"></i>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Action Items Card (Form B Output) --}}
            @if($record->actionItems->count() > 0)
            <div class="premium-card p-6">
                <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-blue-400"></i> Action Items
                    <span class="ml-1 text-xs font-bold bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">{{ $record->actionItems->count() }}</span>
                </h3>
                <div class="space-y-3">
                    @foreach($record->actionItems as $action)
                    @php
                        $acSt = match($action->status) {
                            'Completed','Closed' => 'bg-emerald-50 text-emerald-700',
                            'In Progress'        => 'bg-blue-50 text-blue-700',
                            default              => 'bg-amber-50 text-amber-700',
                        };
                    @endphp
                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-bold text-slate-500 uppercase">{{ $action->action_required }}</span>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold {{ $acSt }}">{{ $action->status }}</span>
                                </div>
                                <p class="text-sm text-slate-600">
                                    <span class="font-semibold">Assigned to:</span>
                                    {{ $action->assignedTo->user_email ?? ('User #' . $action->assigned_to) }}
                                </p>
                                @if($action->due_date)
                                <p class="text-xs text-slate-400 mt-1">
                                    <i class="fa-solid fa-calendar text-xs mr-1"></i>
                                    Due: {{ \Carbon\Carbon::parse($action->due_date)->format('M d, Y') }}
                                </p>
                                @endif
                                @if($action->action_note)
                                <div class="mt-2 p-3 bg-white rounded-lg border border-slate-100">
                                    <p class="text-xs font-bold text-slate-400 mb-1">Action Note:</p>
                                    <p class="text-sm text-slate-600">{{ $action->action_note }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- GM Comments (if any) --}}
            @if($record->gm_comments)
            <div class="premium-card p-6 border-l-4 border-amber-400">
                <h3 class="text-base font-bold text-slate-800 mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-comment-dots text-amber-400"></i> GM Comments
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed">{{ $record->gm_comments }}</p>
            </div>
            @endif

        </div>

        {{-- ─── Right: Meta Info ──────────────────────────────────────────────── --}}
        <div class="space-y-6">

            {{-- Status Timeline --}}
            <div class="premium-card p-6">
                <h3 class="text-base font-bold text-slate-800 mb-4">Status Timeline</h3>
                <div class="space-y-3">
                    @php
                        $timeline = [
                            'Pending Approval'       => ['icon'=>'fa-clock',        'color'=>'amber'],
                            'Resubmitted'            => ['icon'=>'fa-rotate-right', 'color'=>'indigo'],
                            'Under Review'           => ['icon'=>'fa-eye',          'color'=>'blue'],
                            'Approved'               => ['icon'=>'fa-circle-check', 'color'=>'emerald'],
                            'Rejected'               => ['icon'=>'fa-times-circle', 'color'=>'red'],
                            'Modifications Required' => ['icon'=>'fa-pen',          'color'=>'orange'],
                        ];
                        $currentStatus = $record->status;
                    @endphp
                    @foreach($timeline as $st => $info)
                    @php $isActive = $currentStatus === $st; $isDone = false; @endphp
                    <div class="flex items-center gap-3 {{ $isActive ? '' : 'opacity-40' }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs flex-shrink-0
                            {{ $isActive ? 'bg-'.$info['color'].'-100 text-'.$info['color'].'-600' : 'bg-slate-100 text-slate-400' }}">
                            <i class="fa-solid {{ $info['icon'] }}"></i>
                        </div>
                        <p class="text-sm font-semibold {{ $isActive ? 'text-slate-800' : 'text-slate-400' }}">{{ $st }}</p>
                        @if($isActive)
                        <span class="ml-auto text-xs font-bold text-white bg-gradient-to-r from-indigo-500 to-purple-500 px-2 py-0.5 rounded-full">Current</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Digitization Status --}}
            @if($record->digitization_status)
            <div class="premium-card p-6">
                <h3 class="text-base font-bold text-slate-800 mb-3">Digitization Status</h3>
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-50 text-blue-700 text-sm font-bold">
                    <i class="fa-solid fa-database text-xs"></i>
                    {{ $record->digitization_status }}
                </span>
            </div>
            @endif

            {{-- Registered By --}}
            <div class="premium-card p-6">
                <h3 class="text-base font-bold text-slate-800 mb-3">Registered By</h3>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-sm font-bold">
                        {{ strtoupper(substr($record->registeredBy->user_email ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">{{ $record->registeredBy->user_email ?? 'Unknown' }}</p>
                        <p class="text-xs text-slate-400">Liaison Officer</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ── Modify Form Modal ── --}}
@if($record->status === 'Modifications Required')
<div id="modifyInboundModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modifyInboundModalBg"></div>
    
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300 relative flex flex-col max-h-[90vh]" id="modifyInboundModalContent">
            
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm shadow-sm">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Modify & Resubmit</h3>
                        <p class="text-xs text-slate-500">Update the correspondence details based on GM comments</p>
                    </div>
                </div>
                <button onclick="closeModal('modifyInboundModal')" class="w-8 h-8 rounded-lg text-slate-400 hover:bg-slate-200 hover:text-slate-600 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <form action="{{ request()->routeIs('emp.*') ? route('emp.inbound-liaison.update', $record->inbound_id) : route('hr.inbound.update', $record->inbound_id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col overflow-hidden">
                @csrf
                @method('PUT')
                
                <div class="p-6 overflow-y-auto custom-scrollbar">
                    @if($errors->any())
                    <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-100 text-red-600 text-sm font-semibold">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Received From --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Received From <span class="text-red-500">*</span></label>
                            <select name="entity_id" class="premium-input w-full px-4 py-3 text-sm" required>
                                <option value="">— Select External Entity —</option>
                                @foreach($entities as $entity)
                                    <option value="{{ $entity->entity_id }}" {{ $record->entity_id == $entity->entity_id ? 'selected' : '' }}>{{ $entity->entity_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date of Receipt --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Date of Receipt <span class="text-red-500">*</span></label>
                            <input type="date" name="date_of_receipt" class="premium-input w-full px-4 py-3 text-sm" value="{{ $record->date_of_receipt }}" required>
                        </div>

                        {{-- Priority --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Priority <span class="text-red-500">*</span></label>
                            <select name="priority" class="premium-input w-full px-4 py-3 text-sm" required>
                                <option value="Low" {{ $record->priority === 'Low' ? 'selected' : '' }}>Low</option>
                                <option value="Medium" {{ $record->priority === 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="High" {{ $record->priority === 'High' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        {{-- Confidentiality Level --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Confidentiality Level <span class="text-red-500">*</span></label>
                            <select name="confidentiality_level" class="premium-input w-full px-4 py-3 text-sm" required>
                                <option value="Open" {{ $record->confidentiality_level === 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="Confidential" {{ $record->confidentiality_level === 'Confidential' ? 'selected' : '' }}>Confidential</option>
                                <option value="Restricted" {{ $record->confidentiality_level === 'Restricted' ? 'selected' : '' }}>Restricted</option>
                            </select>
                        </div>

                        {{-- Mode of Receipt --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Mode of Receipt <span class="text-red-500">*</span></label>
                            <select name="mode_of_receipt" class="premium-input w-full px-4 py-3 text-sm" required>
                                @foreach(['Hard Copy','Email','Flash Drive','Scanned Copy','Email Attachment','Other'] as $mode)
                                    <option value="{{ $mode }}" {{ $record->mode_of_receipt === $mode ? 'selected' : '' }}>{{ $mode }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Subject --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Subject <span class="text-red-500">*</span></label>
                            <input type="text" name="subject" class="premium-input w-full px-4 py-3 text-sm" value="{{ $record->subject }}" required>
                        </div>

                        {{-- Brief Description --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Brief Description / Content Summary</label>
                            <textarea name="description" rows="3" class="premium-input w-full px-4 py-3 text-sm">{{ $record->description }}</textarea>
                        </div>

                        {{-- Purpose --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Purpose / Reason <span class="text-red-500">*</span></label>
                            <textarea name="purpose" rows="3" class="premium-input w-full px-4 py-3 text-sm" required>{{ $record->purpose }}</textarea>
                        </div>

                        {{-- Additional Attachments --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Add New Attachments (Optional)</label>
                            <input type="file" name="attachments[]" id="modify_attachments" multiple class="premium-input w-full px-4 py-3 text-sm file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <div id="modify-attachment-preview" class="mt-4 empty:hidden"></div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 px-6 py-4 border-t border-slate-100 bg-slate-50 flex-shrink-0">
                    <button type="button" onclick="closeModal('modifyInboundModal')" class="px-6 py-2.5 rounded-xl text-slate-600 hover:bg-slate-200 font-semibold transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow hover:shadow-lg transition-all">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Resubmit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if(session('success'))
<div id="success-toast" class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-4 bg-emerald-600 text-white rounded-xl shadow-2xl animate-fade-in-up">
    <i class="fa-solid fa-circle-check text-lg"></i>
    <span class="font-semibold text-sm">{{ session('success') }}</span>
    <button onclick="document.getElementById('success-toast').remove()" class="ml-2 text-white/70 hover:text-white"><i class="fa-solid fa-times"></i></button>
</div>
<script>setTimeout(() => { const t = document.getElementById('success-toast'); if(t) t.remove(); }, 5000);</script>
@endif

<script src="{{ asset('js/attachment-preview.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.initAttachmentPreview === 'function') {
            window.initAttachmentPreview({
                inputSelector: '#modify_attachments',
                containerSelector: '#modify-attachment-preview'
            });
        }

        @if($errors->any())
            openModal('modifyInboundModal');
        @endif
    });

    function openModal(id) {
        const modal = document.getElementById(id);
        const bg = document.getElementById(id + 'Bg');
        const content = document.getElementById(id + 'Content');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            bg.classList.remove('opacity-0');
            content.classList.remove('opacity-0', 'scale-95');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const bg = document.getElementById(id + 'Bg');
        const content = document.getElementById(id + 'Content');
        
        bg.classList.add('opacity-0');
        content.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }
</script>

@endsection
