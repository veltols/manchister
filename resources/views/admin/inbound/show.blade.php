@extends('layouts.app')

@section('title', 'GM Review — ' . $record->reference_code)
@section('subtitle', 'Form B — GM Decision & Action Items')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Back + Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ request()->routeIs('emp.*') ? route('emp.inbound-gm.index') : route('admin.inbound.index') }}"
           class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-100 transition-all">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-display font-bold text-premium">{{ $record->reference_code }}</h2>
            <p class="text-sm text-slate-500 mt-0.5">{{ $record->entity->entity_name ?? '-' }} — {{ \Carbon\Carbon::parse($record->date_of_receipt)->format('M d, Y') }}</p>
        </div>
        <div class="ml-auto flex items-center gap-3">
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
                {{ $record->status }}
            </span>
            @if(in_array($record->status, ['Pending Approval', 'Resubmitted', 'Under Review']))
            <button onclick="openModal('gmDecisionModal')"
                class="inline-flex items-center gap-2 px-5 py-2.5 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all">
                <i class="fa-solid fa-gavel"></i> Make Decision
            </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ─── Left: Form A Read-Only + Form B Actions ───────────────────────── --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Form A Summary (Read-Only for GM) --}}
            <div class="premium-card p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold">A</div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Form A — Registration Details</h3>
                        <p class="text-xs text-slate-400">Read-only — submitted by Liaison Officer</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><p class="text-xs text-slate-400 uppercase font-bold mb-0.5">Subject</p><p class="font-semibold text-slate-800">{{ $record->subject }}</p></div>
                    <div><p class="text-xs text-slate-400 uppercase font-bold mb-0.5">Priority</p>
                        @php $pc = ['high'=>'red','medium'=>'amber','low'=>'emerald'][$record->priority] ?? 'slate'; @endphp
                        <span class="inline-flex px-2.5 py-0.5 rounded bg-{{ $pc }}-50 text-{{ $pc }}-700 text-xs font-bold uppercase">{{ $record->priority }}</span>
                    </div>
                    <div><p class="text-xs text-slate-400 uppercase font-bold mb-0.5">Category</p><p class="font-semibold text-slate-800 capitalize">{{ str_replace('_',' ',$record->category) }}</p></div>
                    <div><p class="text-xs text-slate-400 uppercase font-bold mb-0.5">Date Received</p><p class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($record->date_of_receipt)->format('M d, Y') }}</p></div>
                    @if($record->description)
                    <div class="col-span-2"><p class="text-xs text-slate-400 uppercase font-bold mb-0.5">Description</p><p class="text-slate-600">{{ $record->description }}</p></div>
                    @endif
                    <div class="col-span-2"><p class="text-xs text-slate-400 uppercase font-bold mb-0.5">Purpose</p><p class="text-slate-600">{{ $record->purpose }}</p></div>
                </div>

                {{-- Attachments inline --}}
                @if($record->attachments->count() > 0)
                <div class="mt-5 pt-4 border-t border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase mb-3">Attachments ({{ $record->attachments->count() }})</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($record->attachments as $att)
                        <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank"
                           class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-indigo-50 text-slate-600 hover:text-indigo-700 text-xs font-semibold transition-all">
                            <i class="fa-solid fa-file text-xs"></i> {{ $att->file_name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Form B: Action Items --}}
            <div class="premium-card p-6">
                <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold">B</div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Form B — Action Items</h3>
                            <p class="text-xs text-slate-400">Assign tasks to Line Managers</p>
                        </div>
                    </div>
                    <button onclick="openModal('addActionModal')"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-indigo-200 bg-indigo-50 text-indigo-700 text-sm font-semibold hover:bg-indigo-100 transition-all">
                        <i class="fa-solid fa-plus text-xs"></i> Add Action
                    </button>
                </div>

                @forelse($record->actionItems as $action)
                @php
                    $acSt = match($action->status) {
                        'Completed','Closed' => 'bg-emerald-50 text-emerald-700',
                        'In Progress'        => 'bg-blue-50 text-blue-700',
                        default              => 'bg-amber-50 text-amber-700',
                    };
                @endphp
                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 mb-3 last:mb-0">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">
                                    <i class="fa-solid fa-arrow-right text-xs"></i> {{ $action->action_required }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $acSt }}">{{ $action->status }}</span>
                                <span class="text-xs text-slate-400 font-semibold">{{ $action->action_type }}</span>
                            </div>
                            <p class="text-sm text-slate-600">
                                <i class="fa-solid fa-user text-xs mr-1 text-slate-400"></i>
                                <strong>Assigned to:</strong> {{ $action->assignedTo->user_email ?? ('User #'.$action->assigned_to) }}
                            </p>
                            @if($action->due_date)
                            <p class="text-xs text-slate-400 mt-1">
                                <i class="fa-solid fa-calendar text-xs mr-1"></i>
                                Due: {{ \Carbon\Carbon::parse($action->due_date)->format('M d, Y') }}
                            </p>
                            @endif
                            @if($action->action_note)
                            <div class="mt-2 p-3 bg-white rounded-lg border border-slate-100">
                                <p class="text-xs font-bold text-slate-400 mb-1">Line Manager Note:</p>
                                <p class="text-sm text-slate-700">{{ $action->action_note }}</p>
                            </div>
                            @endif
                        </div>
                        <form action="{{ request()->routeIs('emp.*') ? route('emp.inbound-gm.action.destroy', [$record->inbound_id, $action->action_id]) : route('admin.inbound.action.destroy', [$record->inbound_id, $action->action_id]) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Remove this action item?')"
                                class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 flex items-center justify-center transition-all">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <i class="fa-solid fa-list-check text-4xl text-slate-100 mb-3 block"></i>
                    <p class="text-slate-400 text-sm">No action items assigned yet.</p>
                    <p class="text-slate-300 text-xs mt-1">Click "Add Action" to assign a Line Manager.</p>
                </div>
                @endforelse
            </div>

            {{-- GM Comments (if exists) --}}
            @if($record->gm_comments)
            <div class="premium-card p-6 border-l-4 border-amber-400">
                <h3 class="text-base font-bold text-slate-800 mb-2"><i class="fa-solid fa-comment-dots text-amber-400 mr-2"></i>GM Comments</h3>
                <p class="text-sm text-slate-600">{{ $record->gm_comments }}</p>
            </div>
            @endif
        </div>

        {{-- ─── Right: Sidebar ─────────────────────────────────────────────────── --}}
        <div class="space-y-6">

            {{-- Digitization Status --}}
            <div class="premium-card p-6">
                <h3 class="text-sm font-bold text-slate-700 mb-3 uppercase tracking-wider">Digitization Status</h3>
                @if($record->digitization_status)
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-50 text-blue-700 text-sm font-bold">
                    <i class="fa-solid fa-database text-xs"></i> {{ $record->digitization_status }}
                </span>
                @else
                <p class="text-sm text-slate-400">Not set — use Make Decision to set.</p>
                @endif
            </div>

            {{-- Summary Stats --}}
            <div class="premium-card p-6">
                <h3 class="text-sm font-bold text-slate-700 mb-4 uppercase tracking-wider">Summary</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">Total Actions</span>
                        <span class="font-bold text-slate-800">{{ $record->actionItems->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">Pending</span>
                        <span class="font-bold text-amber-600">{{ $record->actionItems->where('status','Pending')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">In Progress</span>
                        <span class="font-bold text-blue-600">{{ $record->actionItems->where('status','In Progress')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">Completed</span>
                        <span class="font-bold text-emerald-600">{{ $record->actionItems->whereIn('status',['Completed','Closed'])->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">Attachments</span>
                        <span class="font-bold text-slate-800">{{ $record->attachments->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ─── Modal: GM Decision (Form B) ──────────────────────────────────────── --}}
<div class="modal" id="gmDecisionModal">
    <div class="modal-backdrop" onclick="closeModal('gmDecisionModal')"></div>
    <div class="modal-content max-w-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-display font-bold text-premium">GM Decision</h2>
                <p class="text-xs text-slate-400">Form B — {{ $record->reference_code }}</p>
            </div>
            <button onclick="closeModal('gmDecisionModal')"
                class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ request()->routeIs('emp.*') ? route('emp.inbound-gm.decide', $record->inbound_id) : route('admin.inbound.decide', $record->inbound_id) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Decision <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="decision" value="approved" class="sr-only peer">
                            <div class="text-center p-3 rounded-xl border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 text-slate-500 transition-all">
                                <i class="fa-solid fa-check-circle text-xl mb-1 block"></i>
                                <p class="text-xs font-bold">Approve</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="decision" value="rejected" class="sr-only peer">
                            <div class="text-center p-3 rounded-xl border-2 border-slate-200 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 text-slate-500 transition-all">
                                <i class="fa-solid fa-times-circle text-xl mb-1 block"></i>
                                <p class="text-xs font-bold">Reject</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="decision" value="modifications_required" class="sr-only peer">
                            <div class="text-center p-3 rounded-xl border-2 border-slate-200 peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-700 text-slate-500 transition-all">
                                <i class="fa-solid fa-pen text-xl mb-1 block"></i>
                                <p class="text-xs font-bold">Modify</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Digitization Status</label>
                    <select name="digitization_status" class="premium-input w-full px-4 py-3 text-sm">
                        <option value="">— Not Set —</option>
                        @foreach($digitizationStatus as $ds)
                        <option value="{{ $ds }}" {{ $record->digitization_status == $ds ? 'selected' : '' }}>{{ $ds }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Comments / Notes</label>
                    <textarea name="gm_comments" rows="4" class="premium-input w-full px-4 py-3 text-sm"
                              placeholder="Add comments for the Liaison Officer or team…">{{ $record->gm_comments }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('gmDecisionModal')"
                    class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                <button type="submit"
                    class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:scale-105 transition-all">
                    <i class="fa-solid fa-gavel mr-2"></i> Submit Decision
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ─── Modal: Add Action Items (Multi-row dynamic) ────────────────────────── --}}
<div class="modal" id="addActionModal">
    <div class="modal-backdrop" onclick="closeModal('addActionModal')"></div>
    <div class="modal-content max-w-3xl p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-display font-bold text-premium">Assign Action Items</h2>
                <p class="text-xs text-slate-400 mt-0.5">Add one or more action items — click <strong>+ Add Another</strong> to add more rows</p>
            </div>
            <button onclick="closeModal('addActionModal')"
                class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ request()->routeIs('emp.*') ? route('emp.inbound-gm.action.store', $record->inbound_id) : route('admin.inbound.action.store', $record->inbound_id) }}" method="POST" id="actionItemsForm">
            @csrf

            {{-- Column Headers --}}
            <div class="grid grid-cols-12 gap-3 mb-2 px-1">
                <div class="col-span-4"><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Assigned To <span class="text-red-400">*</span></p></div>
                <div class="col-span-3"><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Action Required <span class="text-red-400">*</span></p></div>
                <div class="col-span-3"><p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Due Date <span class="text-red-400">*</span></p></div>
                <div class="col-span-2"></div>
            </div>

            {{-- Dynamic Rows Container --}}
            <div id="actionRowsContainer" class="space-y-3">
                {{-- First row (always visible) --}}
                <div class="action-row grid grid-cols-12 gap-3 items-center p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="col-span-4">
                        <select name="items[0][assigned_to]" class="premium-input w-full px-3 py-2.5 text-sm" required>
                            <option value="">— Select —</option>
                            @foreach($lineManagers as $lm)
                            <option value="{{ $lm->user_id }}">{{ $lm->user_email }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-3">
                        <select name="items[0][action_required]" class="premium-input w-full px-3 py-2.5 text-sm" required>
                            <option value="">— Select —</option>
                            @foreach($actionRequired as $ar)
                            <option value="{{ $ar }}">{{ $ar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-3">
                        <input type="date" name="items[0][due_date]" class="premium-input w-full px-3 py-2.5 text-sm"
                               min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-span-2 flex justify-center">
                        <span class="w-8 h-8 rounded-lg bg-slate-200 flex items-center justify-center text-slate-400 text-xs font-bold">1</span>
                    </div>
                </div>
            </div>

            {{-- Add Another Row Button --}}
            <button type="button" id="addRowBtn"
                class="mt-4 w-full flex items-center justify-center gap-2 py-3 rounded-xl border-2 border-dashed border-indigo-200 text-indigo-500 text-sm font-semibold hover:border-indigo-400 hover:bg-indigo-50 transition-all">
                <i class="fa-solid fa-plus"></i> Add Another Action Item
            </button>

            <div class="flex items-center justify-between mt-6 pt-5 border-t border-slate-200">
                <p class="text-xs text-slate-400">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    <span id="rowCountLabel">1 action item</span> will be assigned
                </p>
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('addActionModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 premium-button from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:scale-105 transition-all">
                        <i class="fa-solid fa-user-check"></i>
                        <span>Assign All Actions</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function () {
    // Line managers & action options from PHP — passed as JS vars
    const lineManagers = @json($lineManagers->map(fn($lm) => ['id' => $lm->user_id, 'label' => $lm->user_email . ' (' . $lm->user_type . ')']));
    const actionOptions = @json($actionRequired);
    const minDate = '{{ date('Y-m-d') }}';

    let rowIndex = 1; // 0 already exists

    function buildManagerOptions(selectedId = '') {
        return lineManagers.map(lm =>
            `<option value="${lm.id}" ${lm.id == selectedId ? 'selected' : ''}>${lm.label}</option>`
        ).join('');
    }

    function buildActionOptions(selectedVal = '') {
        return actionOptions.map(a =>
            `<option value="${a}" ${a === selectedVal ? 'selected' : ''}>${a}</option>`
        ).join('');
    }

    function updateRowCount() {
        const rows = document.querySelectorAll('.action-row');
        const count = rows.length;
        document.getElementById('rowCountLabel').textContent =
            count === 1 ? '1 action item' : `${count} action items`;
    }

    function addRow() {
        const container = document.getElementById('actionRowsContainer');
        const idx = rowIndex++;
        const div = document.createElement('div');
        div.className = 'action-row grid grid-cols-12 gap-3 items-center p-3 rounded-xl bg-slate-50 border border-slate-100 animate-fade-in-up';
        div.innerHTML = `
            <div class="col-span-4">
                <select name="items[${idx}][assigned_to]" class="premium-input w-full px-3 py-2.5 text-sm" required>
                    <option value="">— Select —</option>
                    ${buildManagerOptions()}
                </select>
            </div>
            <div class="col-span-3">
                <select name="items[${idx}][action_required]" class="premium-input w-full px-3 py-2.5 text-sm" required>
                    <option value="">— Select —</option>
                    ${buildActionOptions()}
                </select>
            </div>
            <div class="col-span-3">
                <input type="date" name="items[${idx}][due_date]"
                       class="premium-input w-full px-3 py-2.5 text-sm"
                       min="${minDate}" required>
            </div>
            <div class="col-span-2 flex items-center justify-center gap-1">
                <span class="w-8 h-8 rounded-lg bg-slate-200 flex items-center justify-center text-slate-400 text-xs font-bold">${idx + 1}</span>
                <button type="button" onclick="removeRow(this)"
                    class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 flex items-center justify-center transition-all">
                    <i class="fa-solid fa-trash text-xs"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
        updateRowCount();
    }

    window.removeRow = function (btn) {
        const row = btn.closest('.action-row');
        row.style.opacity = '0';
        row.style.transform = 'translateX(8px)';
        row.style.transition = 'all 0.2s ease';
        setTimeout(() => {
            row.remove();
            // Re-number badges
            document.querySelectorAll('.action-row').forEach((r, i) => {
                const badge = r.querySelector('span.w-8');
                if (badge) badge.textContent = i + 1;
            });
            updateRowCount();
        }, 200);
    };

    document.getElementById('addRowBtn').addEventListener('click', addRow);
    updateRowCount();
})();
</script>
@endpush


@if(session('success'))
<div id="success-toast" class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-4 bg-emerald-600 text-white rounded-xl shadow-2xl">
    <i class="fa-solid fa-circle-check text-lg"></i>
    <span class="font-semibold text-sm">{{ session('success') }}</span>
</div>
<script>setTimeout(() => { const t = document.getElementById('success-toast'); if(t) t.remove(); }, 5000);</script>
@endif

@endsection
