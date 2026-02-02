@extends('layouts.app')

@section('title', 'Disciplinary Actions')
@section('subtitle', 'Manage disciplinary records')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-premium">Disciplinary Actions</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $actions->total() }} total records</p>
        </div>
        <button onclick="openModal('addDAModal')" class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
            <i class="fa-solid fa-plus"></i>
            <span>Add Record</span>
        </button>
    </div>

    <!-- Filter -->
    <div>
        <form action="{{ route('hr.disciplinary.index') }}" method="GET">
            <select name="employee_id" onchange="this.form.submit()" class="premium-input px-4 py-3 text-sm">
                <option value="">Filter by Employee</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->employee_id }}" {{ request('employee_id') == $emp->employee_id ? 'selected' : '' }}>
                        {{ $emp->first_name }} {{ $emp->last_name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Table -->
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Ref</th>
                        <th class="text-left">Employee</th>
                        <th class="text-left">Date</th>
                        <th class="text-center">Warning Level</th>
                        <th class="text-left">Type</th>
                        <th class="text-center">Status</th>
                        <th class="text-left">Remarks</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actions as $da)
                    <tr>
                        <td><span class="font-mono text-sm font-semibold text-slate-600">#{{ $da->da_id }}</span></td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold shadow-md">
                                    {{ substr($da->employee->first_name ?? 'U', 0, 1) }}
                                </div>
                                <span class="font-semibold text-slate-800">{{ $da->employee->first_name ?? 'Unknown' }} {{ $da->employee->last_name ?? '' }}</span>
                            </div>
                        </td>
                        <td><span class="text-sm text-slate-600">{{ $da->added_date }}</span></td>
                        <td class="text-center">
                            @php
                                $isFinal = Str::contains(strtolower($da->warning->da_warning_name ?? ''), 'final');
                                $warningConfig = $isFinal 
                                    ? ['bg' => 'from-red-500 to-rose-600', 'icon' => 'exclamation-triangle']
                                    : ['bg' => 'from-yellow-500 to-amber-600', 'icon' => 'exclamation-circle'];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r {{ $warningConfig['bg'] }} text-white text-xs font-bold shadow-md">
                                <i class="fa-solid fa-{{ $warningConfig['icon'] }}"></i>
                                {{ $da->warning->da_warning_name ?? 'N/A' }}
                            </span>
                        </td>
                        <td><span class="text-sm text-slate-600">{{ $da->type->da_type_text ?? '-' }}</span></td>
                        <td class="text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-medium">
                                {{ $da->status->da_status_name ?? 'Unknown' }}
                            </span>
                        </td>
                        <td><span class="text-sm text-slate-600 truncate max-w-xs block" title="{{ $da->da_remark }}">{{ $da->da_remark }}</span></td>
                        <td>
                            <div class="flex items-center justify-center">
                                <button onclick="openEditModal({{ $da->da_id }}, {{ $da->da_status_id }}, '{{ addslashes($da->da_remark) }}')" 
                                    class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                    <i class="fa-solid fa-gavel text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 font-medium">No disciplinary records found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($actions->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $actions->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

</div>

<!-- Create Modal -->
<div class="modal" id="addDAModal">
    <div class="modal-backdrop" onclick="closeModal('addDAModal')"></div>
    <div class="modal-content max-w-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-display font-bold text-premium">Add Disciplinary Record</h2>
            <button onclick="closeModal('addDAModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('hr.disciplinary.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Employee</label>
                    <select name="employee_id" class="premium-input w-full px-4 py-3 text-sm" required>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Type</label>
                    <select name="da_type_id" class="premium-input w-full px-4 py-3 text-sm" required>
                        @foreach($types as $t)
                            <option value="{{ $t->da_type_id }}">{{ $t->da_type_code }} - {{ $t->da_type_text }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Warning Level</label>
                    <select name="da_warning_id" class="premium-input w-full px-4 py-3 text-sm" required>
                        @foreach($warnings as $w)
                            <option value="{{ $w->da_warning_id }}">{{ $w->da_warning_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Remarks</label>
                    <textarea name="da_remark" rows="3" class="premium-input w-full px-4 py-3 text-sm"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('addDAModal')" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">Create</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Status Modal -->
<div class="modal" id="editDAModal">
    <div class="modal-backdrop" onclick="closeModal('editDAModal')"></div>
    <div class="modal-content max-w-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-display font-bold text-premium">Update Status</h2>
            <button onclick="closeModal('editDAModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="editDAForm" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <select name="da_status_id" id="edit_status_id" class="premium-input w-full px-4 py-3 text-sm">
                        @foreach($statuses as $s)
                            <option value="{{ $s->da_status_id }}">{{ $s->da_status_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Remarks</label>
                    <textarea name="da_remark" id="edit_remark" rows="3" class="premium-input w-full px-4 py-3 text-sm"></textarea>
                </div>
            </div>
            
            <button type="submit" class="w-full mt-6 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">Update</button>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, statusId, remark) {
        document.getElementById('editDAForm').action = "/hr/disciplinary/" + id + "/update";
        document.getElementById('edit_status_id').value = statusId;
        document.getElementById('edit_remark').value = remark;
        openModal('editDAModal');
    }
</script>

@endsection
