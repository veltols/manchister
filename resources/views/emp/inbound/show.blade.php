@extends('layouts.app')

@section('title', 'Action Item — ' . $actionItem->action_required)
@section('subtitle', 'Form C — Line Manager Action View')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Back + Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('emp.inbound.index') }}"
           class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-100 transition-all">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-display font-bold text-premium">{{ $actionItem->action_required }}</h2>
            <p class="text-sm text-slate-500 mt-0.5">
                {{ $actionItem->correspondence->reference_code }} ·
                {{ $actionItem->correspondence->entity->entity_name ?? 'Unknown Entity' }}
            </p>
        </div>
        <div class="ml-auto">
            @php
                $acSt = match($actionItem->status) {
                    'Completed','Closed' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                    'In Progress'        => 'bg-blue-50 text-blue-700 border border-blue-200',
                    default              => 'bg-amber-50 text-amber-700 border border-amber-200',
                };
            @endphp
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold {{ $acSt }}">
                {{ $actionItem->status }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ─── Left: Correspondence Info + Form C ──────────────────────────── --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Correspondence Summary (Form A info for Line Manager) --}}
            <div class="premium-card p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold">A</div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Correspondence Summary</h3>
                        <p class="text-xs text-slate-400">From Form A — Reference Information</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold mb-0.5">Reference Code</p>
                        <p class="font-mono font-bold text-indigo-700">{{ $actionItem->correspondence->reference_code }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold mb-0.5">Received From</p>
                        <p class="font-semibold text-slate-800">{{ $actionItem->correspondence->entity->entity_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold mb-0.5">Date Received</p>
                        <p class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($actionItem->correspondence->date_of_receipt)->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold mb-0.5">Priority</p>
                        @php $pc = ['high'=>'red','medium'=>'amber','low'=>'emerald'][$actionItem->correspondence->priority] ?? 'slate'; @endphp
                        <span class="inline-flex px-2.5 py-0.5 rounded bg-{{ $pc }}-50 text-{{ $pc }}-700 text-xs font-bold uppercase">
                            {{ $actionItem->correspondence->priority }}
                        </span>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs text-slate-400 uppercase font-bold mb-0.5">Subject</p>
                        <p class="font-semibold text-slate-800">{{ $actionItem->correspondence->subject }}</p>
                    </div>
                    @if($actionItem->correspondence->description)
                    <div class="col-span-2">
                        <p class="text-xs text-slate-400 uppercase font-bold mb-0.5">Description</p>
                        <p class="text-slate-600">{{ $actionItem->correspondence->description }}</p>
                    </div>
                    @endif
                    <div class="col-span-2">
                        <p class="text-xs text-slate-400 uppercase font-bold mb-0.5">Purpose</p>
                        <p class="text-slate-600">{{ $actionItem->correspondence->purpose }}</p>
                    </div>
                </div>

                {{-- Attachments --}}
                @if($actionItem->correspondence->attachments->count() > 0)
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase mb-3">Attachments</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($actionItem->correspondence->attachments as $att)
                        <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank"
                           class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-indigo-50 text-slate-600 hover:text-indigo-700 text-xs font-semibold transition-all">
                            <i class="fa-solid fa-file text-xs"></i> {{ $att->file_name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- My Action Assignment (Form B info) --}}
            <div class="premium-card p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold">B</div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">My Assignment</h3>
                        <p class="text-xs text-slate-400">From Form B — GM assigned to you</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold mb-0.5">Action Required</p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">
                            {{ $actionItem->action_required }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold mb-0.5">Due Date</p>
                        @php
                            $isOverdue = $actionItem->due_date && \Carbon\Carbon::parse($actionItem->due_date)->isPast()
                                && !in_array($actionItem->status, ['Completed','Closed']);
                        @endphp
                        <p class="font-semibold {{ $isOverdue ? 'text-red-600' : 'text-slate-800' }}">
                            {{ $actionItem->due_date ? \Carbon\Carbon::parse($actionItem->due_date)->format('M d, Y') : '-' }}
                            @if($isOverdue) <span class="text-xs font-bold text-red-500">(OVERDUE)</span> @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold mb-0.5">Action Type</p>
                        <p class="font-semibold text-slate-800 capitalize">{{ $actionItem->action_type }}</p>
                    </div>
                </div>
            </div>

            {{-- Form C: Submit Action Note --}}
            <div class="premium-card p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white text-sm font-bold">C</div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Form C — Action Note</h3>
                        <p class="text-xs text-slate-400">Your response and updated status</p>
                    </div>
                </div>

                @if(!in_array($actionItem->status, ['Completed','Closed']))
                <form action="{{ route('emp.inbound.note', $actionItem->action_id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Action Note <span class="text-red-500">*</span>
                            </label>
                            <textarea name="action_note" rows="5"
                                class="premium-input w-full px-4 py-3 text-sm"
                                placeholder="Describe what you have done or will do for this action item…"
                                required>{{ $actionItem->action_note }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Update Status <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                @foreach(['Pending','In Progress','Completed','Closed'] as $st)
                                <label class="cursor-pointer">
                                    <input type="radio" name="status" value="{{ $st }}"
                                           class="sr-only peer"
                                           {{ $actionItem->status == $st ? 'checked' : '' }}>
                                    <div class="text-center p-2.5 rounded-xl border-2 border-slate-200
                                        peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700
                                        text-slate-500 text-xs font-bold transition-all cursor-pointer">
                                        {{ $st }}
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-6 pt-4 border-t border-slate-100">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-3 premium-button from-emerald-600 to-teal-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all">
                            <i class="fa-solid fa-paper-plane"></i> Submit Action Note
                        </button>
                    </div>
                </form>
                @else
                <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        <p class="text-sm font-bold text-emerald-700">Action {{ $actionItem->status }}</p>
                    </div>
                    @if($actionItem->action_note)
                    <p class="text-sm text-emerald-600">{{ $actionItem->action_note }}</p>
                    @endif
                </div>
                @endif
            </div>
        </div>

        {{-- ─── Right: Sidebar ─────────────────────────────────────────────────── --}}
        <div class="space-y-6">
            {{-- Quick Info --}}
            <div class="premium-card p-6">
                <h3 class="text-sm font-bold text-slate-700 mb-4 uppercase tracking-wider">Quick Info</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">Status</span>
                        <span class="inline-flex px-2.5 py-0.5 rounded text-xs font-bold {{ $acSt }}">{{ $actionItem->status }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">Due</span>
                        <span class="text-sm font-semibold {{ $isOverdue ? 'text-red-500' : 'text-slate-700' }}">
                            {{ $actionItem->due_date ? \Carbon\Carbon::parse($actionItem->due_date)->format('M d, Y') : '—' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">Corr. Status</span>
                        <span class="text-xs font-bold text-slate-600">{{ $actionItem->correspondence->status }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">Total Actions</span>
                        <span class="font-bold text-slate-700">{{ $actionItem->correspondence->actionItems->count() }}</span>
                    </div>
                </div>
            </div>

            {{-- Other Action Items on Same Correspondence --}}
            @if($actionItem->correspondence->actionItems->count() > 1)
            <div class="premium-card p-6">
                <h3 class="text-sm font-bold text-slate-700 mb-4 uppercase tracking-wider">All Actions on This Correspondence</h3>
                <div class="space-y-2">
                    @foreach($actionItem->correspondence->actionItems as $ai)
                    @php
                        $aiStyle = match($ai->status) {
                            'Completed','Closed' => 'bg-emerald-50 text-emerald-700',
                            'In Progress'        => 'bg-blue-50 text-blue-700',
                            default              => 'bg-amber-50 text-amber-700',
                        };
                    @endphp
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 {{ $ai->action_id == $actionItem->action_id ? 'ring-2 ring-indigo-300' : '' }}">
                        <span class="text-xs font-semibold text-slate-600">{{ $ai->action_required }}</span>
                        <span class="text-xs font-bold px-2 py-0.5 rounded {{ $aiStyle }}">{{ $ai->status }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
<div id="success-toast" class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-4 bg-emerald-600 text-white rounded-xl shadow-2xl">
    <i class="fa-solid fa-circle-check text-lg"></i>
    <span class="font-semibold text-sm">{{ session('success') }}</span>
</div>
<script>setTimeout(() => { const t = document.getElementById('success-toast'); if(t) t.remove(); }, 5000);</script>
@endif

@endsection
