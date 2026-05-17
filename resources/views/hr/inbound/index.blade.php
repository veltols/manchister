@extends('layouts.app')

@section('title', 'Inbound Correspondence')
@section('subtitle', 'Register & manage inbound external correspondence')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-premium">Inbound Correspondence</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $records->total() }} total records</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- <button onclick="openModal('addEntityModal')"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
                <i class="fa-solid fa-building"></i> Add Entity
            </button> -->
            <button onclick="openModal('addInboundModal')"
                class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                <i class="fa-solid fa-plus"></i>
                <span>Register Inbound</span>
            </button>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        @php
        $statusColors = [
            'Pending Approval'       => ['bg'=>'from-amber-400 to-amber-500',   'icon'=>'fa-clock'],
            'Resubmitted'            => ['bg'=>'from-indigo-400 to-indigo-500', 'icon'=>'fa-rotate-right'],
            'Under Review'           => ['bg'=>'from-blue-500 to-indigo-500',   'icon'=>'fa-eye'],
            'Approved'               => ['bg'=>'from-emerald-500 to-green-500', 'icon'=>'fa-circle-check'],
            'Rejected'               => ['bg'=>'from-red-500 to-rose-500',      'icon'=>'fa-times-circle'],
            'Modifications Required' => ['bg'=>'from-orange-400 to-orange-500', 'icon'=>'fa-pen'],
        ];
        $allStatuses = array_keys($statusColors);
        $statusCounts = $records->groupBy('status');
    @endphp
    @foreach($allStatuses as $st)
    @php $sc = $statusColors[$st]; @endphp
    <div class="premium-card p-5 flex items-center gap-4 cursor-pointer hover:shadow-md transition-all group"
         onclick="document.getElementById('inbound-status').value='{{ $st }}'; document.getElementById('inbound-filter-form').submit()">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-sm flex-shrink-0 bg-gradient-to-br {{ $sc['bg'] }}">
            <i class="fa-solid {{ $sc['icon'] }}"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $st }}</p>
            <p class="text-xl font-black text-slate-800 leading-none mt-0.5">
                {{ $statusCounts->has($st) ? $statusCounts[$st]->count() : 0 }}
            </p>
        </div>
    </div>
    @endforeach
    </div>

    {{-- Filters --}}
    <div class="premium-card p-4">
        <form id="inbound-filter-form" action="{{ request()->routeIs('emp.*') ? route('emp.inbound-liaison.index') : route('hr.inbound.index') }}" method="GET">
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[200px]">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                    <input type="text" name="search" id="inbound-search" value="{{ request('search') }}"
                           placeholder="Search by ref code or subject…"
                           class="premium-input w-full pl-9 py-2 text-sm">
                </div>
                <select name="status" id="inbound-status" class="premium-input py-2 text-sm" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    @foreach($allStatuses as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
                <select name="priority" class="premium-input py-2 text-sm" onchange="this.form.submit()">
                    <option value="">All Priorities</option>
                    <option value="high" {{ request('priority')=='high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority')=='medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('priority')=='low' ? 'selected' : '' }}>Low</option>
                </select>
                <a href="{{ request()->routeIs('emp.*') ? route('emp.inbound-liaison.index') : route('hr.inbound.index') }}"
                   class="px-4 py-2 rounded-xl border border-slate-200 text-slate-500 text-sm font-semibold hover:bg-slate-50 transition-all">
                    <i class="fa-solid fa-times mr-1"></i> Clear
                </a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-left font-bold text-slate-400">Ref Code</th>
                        <th class="text-left font-bold text-slate-400">Entity</th>
                        <th class="text-left font-bold text-slate-400">Subject</th>
                        <th class="text-center font-bold text-slate-400">Priority</th>
                        <th class="text-center font-bold text-slate-400">Mode of Receipt</th>
                        <th class="text-left font-bold text-slate-400">Date</th>
                        <th class="text-center font-bold text-slate-400">Actions</th>
                        <th class="text-center font-bold text-slate-400">Status</th>
                        <th class="text-center font-bold text-slate-400"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($records as $rec)
                    @php
                        $priorityColor = ['high'=>'red','medium'=>'amber','low'=>'emerald'][$rec->priority] ?? 'slate';
                        $statusStyle = match($rec->status) {
                            'Pending Approval'       => 'bg-amber-50 text-amber-700',
                            'Resubmitted'            => 'bg-indigo-50 text-indigo-700',
                            'Under Review'           => 'bg-blue-50 text-blue-700',
                            'Approved'               => 'bg-emerald-50 text-emerald-700',
                            'Rejected'               => 'bg-red-50 text-red-700',
                            'Modifications Required' => 'bg-orange-50 text-orange-700',
                            default                  => 'bg-slate-100 text-slate-600',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td>
                            <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">
                                {{ $rec->reference_code }}
                            </span>
                        </td>
                        <td>
                            <span class="font-semibold text-slate-700 text-sm">{{ $rec->entity->entity_name ?? '-' }}</span>
                        </td>
                        <td class="max-w-xs">
                            <p class="text-sm text-slate-600 truncate" title="{{ $rec->subject }}">{{ $rec->subject }}</p>
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-{{ $priorityColor }}-50 text-{{ $priorityColor }}-700 uppercase">
                                {{ $rec->priority }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="text-xs text-slate-500 font-medium">{{ $rec->mode_of_receipt }}</span>
                        </td>
                        <td>
                            <span class="text-xs text-slate-400 font-mono">{{ \Carbon\Carbon::parse($rec->date_of_receipt)->format('M d, Y') }}</span>
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-600">
                                <i class="fa-solid fa-list-check text-xs"></i>
                                {{ $rec->actionItems->count() }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold {{ $statusStyle }}">
                                {{ $rec->status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ request()->routeIs('emp.*') ? route('emp.inbound-liaison.show', $rec->inbound_id) : route('hr.inbound.show', $rec->inbound_id) }}"
                               class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md mx-auto"
                               title="View Details">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-20">
                            <i class="fa-solid fa-envelope-open text-5xl text-slate-100 mb-4 block"></i>
                            <p class="text-slate-400 font-medium">No inbound correspondences found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($records->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $records->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ─── Modal: Register New Inbound (Form A) ─────────────────────────────── --}}
<div class="modal" id="addInboundModal">
    <div class="modal-backdrop" onclick="closeModal('addInboundModal')"></div>
    <div class="modal-content max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Register Inbound Correspondence</h2>
                <p class="text-xs text-slate-400 mt-1">Form A — Liaison Officer</p>
            </div>
            <button onclick="closeModal('addInboundModal')"
                class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ request()->routeIs('emp.*') ? route('emp.inbound-liaison.store') : route('hr.inbound.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-5">

                {{-- Reference Code (auto) --}}
                <div class="p-3 bg-indigo-50 rounded-xl border border-indigo-100 flex items-center gap-3">
                    <i class="fa-solid fa-tag text-indigo-400"></i>
                    <div>
                        <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider">Reference Code</p>
                        <p class="text-sm font-bold text-indigo-700">Will be auto-generated upon save</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Received From --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Received From <span class="text-red-500">*</span>
                        </label>
                        <select name="entity_id" class="premium-input w-full px-4 py-3 text-sm" required>
                            <option value="">— Select External Entity —</option>
                            @foreach($entities as $entity)
                                <option value="{{ $entity->entity_id }}">{{ $entity->entity_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date of Receipt --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Date of Receipt <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date_of_receipt" class="premium-input w-full px-4 py-3 text-sm"
                               value="{{ date('Y-m-d') }}" required>
                    </div>

                    {{-- Priority --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" class="premium-input w-full px-4 py-3 text-sm" required>
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>

                    {{-- Confidentiality Level --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Confidentiality Level <span class="text-red-500">*</span>
                        </label>
                        <select name="confidentiality_level" class="premium-input w-full px-4 py-3 text-sm" required>
                            <option value="Open" selected>Open</option>
                            <option value="Confidential">Confidential</option>
                            <option value="Restricted">Restricted</option>
                        </select>
                    </div>

                    {{-- Mode of Receipt --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Mode of Receipt <span class="text-red-500">*</span>
                        </label>
                        <select name="mode_of_receipt" class="premium-input w-full px-4 py-3 text-sm" required>
                            <option value="Hard Copy">Hard Copy</option>
                            <option value="Email">Email</option>
                            <option value="Flash Drive">Flash Drive</option>
                            <option value="Scanned Copy">Scanned Copy</option>
                            <option value="Email Attachment">Email Attachment</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    {{-- Subject --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Subject <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="subject" class="premium-input w-full px-4 py-3 text-sm"
                               placeholder="Brief subject of this correspondence" required>
                    </div>

                    {{-- Brief Description --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Brief Description / Content Summary</label>
                        <textarea name="description" rows="3" class="premium-input w-full px-4 py-3 text-sm"
                                  placeholder="Optional summary of the correspondence content…"></textarea>
                    </div>

                    {{-- Purpose --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Purpose / Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea name="purpose" rows="3" class="premium-input w-full px-4 py-3 text-sm"
                                  placeholder="Why was this correspondence received? What action is expected?" required></textarea>
                    </div>

                    {{-- Attachments --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Attachments</label>
                        <input type="file" name="attachments[]" id="inbound_attachments" multiple
                               class="premium-input w-full px-4 py-3 text-sm file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="text-xs text-slate-400 mt-1">Multiple files allowed. Max 20MB each.</p>
                        <div id="inbound-attachment-preview" class="mt-4 empty:hidden"></div>
                    </div>
                </div>

                {{-- Hidden fields --}}
                <input type="hidden" name="correspondence_type" value="inbound">
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('addInboundModal')"
                    class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                <button type="submit"
                    class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-save mr-2"></i> Register Correspondence
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ─── Modal: Add External Entity ───────────────────────────────────────── --}}
<div class="modal" id="addEntityModal">
    <div class="modal-backdrop" onclick="closeModal('addEntityModal')"></div>
    <div class="modal-content max-w-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-display font-bold text-premium">Add External Entity</h2>
            <button onclick="closeModal('addEntityModal')"
                class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ request()->routeIs('emp.*') ? route('emp.inbound-liaison.entity.store') : route('hr.inbound.entity.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Entity Name <span class="text-red-500">*</span></label>
                        <input type="text" name="entity_name" class="premium-input w-full px-4 py-3 text-sm"
                               placeholder="e.g. Ministry of Labour" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Entity Code <span class="text-red-500">*</span>
                            <span class="text-xs text-slate-400 font-normal">(2-letter prefix for ref code)</span>
                        </label>
                        <input type="text" name="entity_code" class="premium-input w-full px-4 py-3 text-sm uppercase"
                               placeholder="e.g. ML" maxlength="10" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Phone</label>
                        <input type="text" name="entity_phone" class="premium-input w-full px-4 py-3 text-sm" placeholder="+971…">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                        <input type="email" name="entity_email" class="premium-input w-full px-4 py-3 text-sm" placeholder="info@entity.gov">
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('addEntityModal')"
                    class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                <button type="submit"
                    class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                    Save Entity
                </button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
<div id="success-toast" class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-4 bg-emerald-600 text-white rounded-xl shadow-2xl animate-fade-in-up">
    <i class="fa-solid fa-circle-check text-lg"></i>
    <span class="font-semibold text-sm">{{ session('success') }}</span>
    <button onclick="document.getElementById('success-toast').remove()" class="ml-2 text-white/70 hover:text-white">
        <i class="fa-solid fa-times"></i>
    </button>
</div>
<script>setTimeout(() => { const t = document.getElementById('success-toast'); if(t) t.remove(); }, 5000);</script>
@endif

<script src="{{ asset('js/attachment-preview.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.initAttachmentPreview === 'function') {
            window.initAttachmentPreview({
                inputSelector: '#inbound_attachments',
                containerSelector: '#inbound-attachment-preview'
            });
        }
    });
</script>

@endsection
