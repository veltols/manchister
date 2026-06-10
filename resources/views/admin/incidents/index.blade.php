@extends('layouts.app')
@section('title', 'Incident Reporting')
@section('subtitle', 'Log and track workplace incidents.')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-display font-bold text-slate-900">Incidents Log</h1>
                <p class="text-sm text-slate-500 mt-1" id="incident-count">Total {{ $incidents->total() }} records found</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="exportIncidentsCsv()" class="premium-button">
                    <i class="fa-solid fa-file-csv"></i> Export CSV
                </button>
                <button onclick="openModal('reportIncidentModal')" class="premium-button">
                    <i class="fa-solid fa-plus"></i> Report Incident
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="premium-card p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="searchInput" class="premium-input pl-9 pr-4 py-2.5 w-full text-sm"
                        placeholder="Search by ref #, type or description...">
                </div>
                <div class="relative">
                    <i class="fa-solid fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="date" id="dateFilter" class="premium-input pl-9 pr-4 py-2.5 w-full text-sm">
                </div>
                <div class="relative">
                    <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="reporterFilter" class="premium-input pl-9 pr-4 py-2.5 w-full text-sm"
                        placeholder="Search by reporter name...">
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left first:pl-6">Ref #</th>
                            <th class="text-left">Created At</th>
                            <th class="text-left">Incident Date &amp; Time</th>
                            <th class="text-left">Type</th>
                            <th class="text-left">Description</th>
                            <th class="text-left">Status</th>
                            <th class="text-left">Assigned To</th>
                            <th class="text-left">Attachment</th>
                            <th class="text-left">Reported By</th>
                            <th class="text-center last:pr-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="incidents-container">
                        @forelse($incidents as $incident)
                            @php
                                $reporterName = optional(optional($incident->reporter)->employee)->first_name
                                    ?? optional($incident->reporter)->user_email
                                    ?? 'System';
                                $getName = fn($emp) => $emp ? trim($emp->first_name . ' ' . $emp->last_name) : null;
                                $ap1 = $incident->assigned_person_1 ? $getName(\App\Models\EmployeesList::find($incident->assigned_person_1)) : null;
                                $ap2 = $incident->assigned_person_2 ? $getName(\App\Models\EmployeesList::find($incident->assigned_person_2)) : null;
                                $ap3 = $incident->assigned_person_3 ? $getName(\App\Models\EmployeesList::find($incident->assigned_person_3)) : null;
                                $assignees = array_filter([$ap1, $ap2, $ap3]);
                            @endphp
                            <tr class="group hover:bg-slate-50 transition-colors">
                                <td class="first:pl-6">
                                    @php
                                        $year = \Carbon\Carbon::parse($incident->created_at)->format('Y');
                                        $refNo = 'INC-' . $year . '-' . str_pad($incident->incident_id, 5, '0', STR_PAD_LEFT);
                                    @endphp
                                    <span class="font-bold text-slate-700 text-sm">{{ $refNo }}</span>
                                </td>
                                <td>
                                    <span class="text-xs text-slate-600">{{ \Carbon\Carbon::parse($incident->created_at)->format('M d, Y h:i A') }}</span>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-700 text-sm">{{ \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y') }}</span>
                                        <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($incident->incident_date)->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium w-fit bg-indigo-50 text-indigo-700">
                                        {{ $incident->incident_type }}
                                    </span>
                                </td>
                                <td class="max-w-xs">
                                    <p class="text-sm text-slate-600 truncate" title="{{ $incident->description }}">
                                        {{ $incident->description }}
                                    </p>
                                </td>
                                <td>
                                    @if(($incident->status ?? 'pending') === 'resolved')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">
                                            <i class="fa-solid fa-circle-check text-[10px]"></i> Resolved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700">
                                            <i class="fa-solid fa-clock text-[10px]"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if(count($assignees) > 0)
                                        <div class="flex flex-col gap-0.5">
                                            @foreach($assignees as $name)
                                                <span class="text-xs text-slate-600 font-medium">{{ $name }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($incident->attachment)
                                        <a href="{{ asset($incident->attachment) }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                            <i class="fa-solid fa-paperclip"></i> View File
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 italic">None</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                            {{ substr($reporterName, 0, 1) }}
                                        </div>
                                        <span class="text-xs font-medium text-slate-600">{{ $reporterName }}</span>
                                    </div>
                                </td>
                                <td class="text-center last:pr-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <button class="edit-incident-btn w-8 h-8 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md" title="Edit"
                                            data-id="{{ $incident->incident_id }}"
                                            data-type="{{ e($incident->incident_type) }}"
                                            data-date="{{ \Carbon\Carbon::parse($incident->incident_date)->format('Y-m-d\TH:i') }}"
                                            data-description="{{ e($incident->description) }}"
                                            data-attachment="{{ $incident->attachment ? e(asset($incident->attachment)) : '' }}"
                                            data-ap1="{{ $incident->assigned_person_1 ?? '' }}"
                                            data-ap2="{{ $incident->assigned_person_2 ?? '' }}"
                                            data-ap3="{{ $incident->assigned_person_3 ?? '' }}"
                                            data-status="{{ $incident->status ?? 'pending' }}">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </button>
                                        <button class="delete-incident-btn w-8 h-8 rounded-lg bg-gradient-to-br from-rose-500 to-red-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md" title="Delete"
                                            data-id="{{ $incident->incident_id }}">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-12">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-shield-virus text-3xl text-slate-300"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">No incidents reported yet</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div id="incidents-pagination"></div>
        </div>
    </div>

    <!-- Create Incident Modal -->
    <div id="reportIncidentModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('reportIncidentModal')"></div>
        <div class="modal-content max-w-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">Report New Incident</h2>
                    <p class="text-slate-500 text-sm">Log a new workplace incident or issue.</p>
                </div>
                <button onclick="closeModal('reportIncidentModal')"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 flex items-center justify-center text-slate-400 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.incidents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Incident Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="incident_date_only" required class="premium-input w-full" value="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Incident Time <span class="text-rose-500">*</span></label>
                        <input type="time" name="incident_time_only" required class="premium-input w-full" value="{{ date('H:i') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Incident Type <span class="text-rose-500">*</span></label>
                        <select name="incident_type" required class="premium-input w-full appearance-none">
                            <option value="" disabled selected>Select Type...</option>
                            @foreach($types as $type)
                                <option value="{{ $type->type_name }}">{{ $type->type_name }}</option>
                            @endforeach
                            @if($types->isEmpty())
                                <option value="Security Breach">Security Breach</option>
                                <option value="Workplace Accident">Workplace Accident</option>
                                <option value="System Failure">System Failure</option>
                                <option value="Data Loss">Data Loss</option>
                                <option value="Misconduct">Misconduct</option>
                            @endif
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Status</label>
                    <select name="status" class="premium-input w-full appearance-none">
                        <option value="pending" selected>Pending</option>
                        <option value="resolved">Resolved</option>
                    </select>
                </div>

                <!-- Assignment -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                        <i class="fa-solid fa-users text-indigo-500 mr-1"></i> Assign Persons <span class="text-slate-400 font-normal normal-case">(Optional)</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs text-slate-500 mb-1">Assigned Person 1</label>
                            <select name="assigned_person_1" class="premium-input w-full appearance-none text-sm">
                                <option value="">— None —</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 mb-1">Assigned Person 2</label>
                            <select name="assigned_person_2" class="premium-input w-full appearance-none text-sm">
                                <option value="">— None —</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 mb-1">Assigned Person 3</label>
                            <select name="assigned_person_3" class="premium-input w-full appearance-none text-sm">
                                <option value="">— None —</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Description <span class="text-rose-500">*</span></label>
                    <textarea name="description" required rows="4" class="premium-input w-full" placeholder="Describe the incident in detail..."></textarea>
                </div>
                <div class="mb-8">
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                        <i class="fa-solid fa-paperclip text-indigo-500 mr-1"></i> Attachment <span class="text-slate-400 font-normal normal-case">(Optional)</span>
                    </label>
                    <input type="file" name="attachment" id="incident_attachment" class="premium-input w-full px-4 py-3 text-sm">
                    <div id="incident-attachment-preview"></div>
                    <p class="text-[10px] text-slate-400 mt-1">Images or Documents (PDF, DOCX) up to 10MB.</p>
                </div>
                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <button type="button" onclick="closeModal('reportIncidentModal')" class="px-6 py-2.5 rounded-xl font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</button>
                    <button type="submit" class="premium-button">Submit Report</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Incident Modal -->
    <div id="editIncidentModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('editIncidentModal')"></div>
        <div class="modal-content max-w-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">Edit Incident</h2>
                    <p class="text-slate-500 text-sm">Update incident details.</p>
                </div>
                <button onclick="closeModal('editIncidentModal')"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100 flex items-center justify-center text-slate-400 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form id="editIncidentForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Incident Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="incident_date_only" id="edit_incident_date_only" required class="premium-input w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Incident Time <span class="text-rose-500">*</span></label>
                        <input type="time" name="incident_time_only" id="edit_incident_time_only" required class="premium-input w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Incident Type <span class="text-rose-500">*</span></label>
                        <select name="incident_type" id="edit_incident_type" required class="premium-input w-full appearance-none">
                            <option value="" disabled>Select Type...</option>
                            @foreach($types as $type)
                                <option value="{{ $type->type_name }}">{{ $type->type_name }}</option>
                            @endforeach
                            @if($types->isEmpty())
                                <option value="Security Breach">Security Breach</option>
                                <option value="Workplace Accident">Workplace Accident</option>
                                <option value="System Failure">System Failure</option>
                                <option value="Data Loss">Data Loss</option>
                                <option value="Misconduct">Misconduct</option>
                            @endif
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Status</label>
                    <select name="status" id="edit_status" class="premium-input w-full appearance-none">
                        <option value="pending">Pending</option>
                        <option value="resolved">Resolved</option>
                    </select>
                </div>

                <!-- Assignment -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                        <i class="fa-solid fa-users text-indigo-500 mr-1"></i> Assign Persons <span class="text-slate-400 font-normal normal-case">(Optional)</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs text-slate-500 mb-1">Assigned Person 1</label>
                            <select name="assigned_person_1" id="edit_ap1" class="premium-input w-full appearance-none text-sm">
                                <option value="">— None —</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 mb-1">Assigned Person 2</label>
                            <select name="assigned_person_2" id="edit_ap2" class="premium-input w-full appearance-none text-sm">
                                <option value="">— None —</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 mb-1">Assigned Person 3</label>
                            <select name="assigned_person_3" id="edit_ap3" class="premium-input w-full appearance-none text-sm">
                                <option value="">— None —</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Description <span class="text-rose-500">*</span></label>
                    <textarea name="description" id="edit_description" required rows="4" class="premium-input w-full"></textarea>
                </div>
                <div id="edit_current_attachment_wrap" class="mb-4 hidden">
                    <p class="text-xs text-slate-500 mb-1">Current attachment:</p>
                    <a id="edit_current_attachment_link" href="#" target="_blank"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                        <i class="fa-solid fa-paperclip"></i> View Current File
                    </a>
                </div>
                <div class="mb-8">
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                        <i class="fa-solid fa-paperclip text-indigo-500 mr-1"></i> Replace Attachment <span class="text-slate-400 font-normal normal-case">(Optional)</span>
                    </label>
                    <input type="file" name="attachment" id="edit_incident_attachment" class="premium-input w-full px-4 py-3 text-sm">
                    <div id="edit-attachment-preview"></div>
                    <p class="text-[10px] text-slate-400 mt-1">Leave empty to keep the existing file.</p>
                </div>
                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <button type="button" onclick="closeModal('editIncidentModal')" class="px-6 py-2.5 rounded-xl font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</button>
                    <button type="submit" class="premium-button"><i class="fa-solid fa-save mr-2"></i>Save Changes</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('libs/mammoth/mammoth.browser.min.js') }}"></script>
    <script src="{{ asset('js/attachment-preview.js') }}"></script>
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        const incidentTypes = @json($types->pluck('type_name'));
        const employeeOptions = @json($employees->map(fn($e) => ['id' => $e->employee_id, 'name' => trim($e->first_name . ' ' . $e->last_name)]));
        const deleteUrl = "{{ url('admin/incidents') }}";
        const csrfToken = "{{ csrf_token() }}";

        // ─── Edit Modal ────────────────────────────────────────────────
        function openEditModal(id, type, date, description, attachmentUrl, ap1, ap2, ap3, status) {
            document.getElementById('editIncidentForm').action = deleteUrl + '/' + id + '/update';
            
            // Split Y-m-dTH:i into date and time
            if (date && date.includes('T')) {
                const parts = date.split('T');
                document.getElementById('edit_incident_date_only').value = parts[0];
                document.getElementById('edit_incident_time_only').value = parts[1];
            }
            
            document.getElementById('edit_description').value = description;

            // populate type select
            const sel = document.getElementById('edit_incident_type');
            for (let i = 0; i < sel.options.length; i++) {
                sel.options[i].selected = (sel.options[i].value === type);
            }

            // populate status
            const statusSel = document.getElementById('edit_status');
            for (let i = 0; i < statusSel.options.length; i++) {
                statusSel.options[i].selected = (statusSel.options[i].value === status);
            }

            // populate assigned persons
            ['edit_ap1', 'edit_ap2', 'edit_ap3'].forEach((selId, idx) => {
                const apVal = [ap1, ap2, ap3][idx];
                const apSel = document.getElementById(selId);
                for (let i = 0; i < apSel.options.length; i++) {
                    apSel.options[i].selected = (apSel.options[i].value == apVal);
                }
            });

            // current attachment
            const wrap = document.getElementById('edit_current_attachment_wrap');
            const link = document.getElementById('edit_current_attachment_link');
            if (attachmentUrl) {
                wrap.classList.remove('hidden');
                link.href = attachmentUrl;
            } else {
                wrap.classList.add('hidden');
            }

            openModal('editIncidentModal');
        }

        // Single delegated listener for both edit and delete on static + AJAX rows
        document.addEventListener('click', function (e) {
            const editBtn = e.target.closest('.edit-incident-btn');
            if (editBtn) {
                openEditModal(
                    editBtn.dataset.id,
                    editBtn.dataset.type,
                    editBtn.dataset.date,
                    editBtn.dataset.description,
                    editBtn.dataset.attachment || null,
                    editBtn.dataset.ap1 || '',
                    editBtn.dataset.ap2 || '',
                    editBtn.dataset.ap3 || '',
                    editBtn.dataset.status || 'pending'
                );
            }
            const delBtn = e.target.closest('.delete-incident-btn');
            if (delBtn) confirmDelete(delBtn.dataset.id);
        });

        // ─── Delete with SweetAlert ────────────────────────────────────
        function confirmDelete(id) {
            Swal.fire({
                icon: 'warning',
                title: 'Delete Incident?',
                text: 'This action cannot be undone. The incident record will be permanently removed.',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                scrollbarPadding: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(deleteUrl + '/' + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Toast.fire({ icon: 'success', title: data.message });
                            window.ajaxPagination.loadPage(1);
                        } else {
                            Toast.fire({ icon: 'error', title: 'Failed to delete incident.' });
                        }
                    })
                    .catch(() => Toast.fire({ icon: 'error', title: 'An error occurred.' }));
                }
            });
        }

        // ─── Helper: status badge HTML ───────────────────────────────────
        function statusBadge(status) {
            if (status === 'resolved') {
                return `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">
                            <i class="fa-solid fa-circle-check text-[10px]"></i> Resolved
                        </span>`;
            }
            return `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700">
                        <i class="fa-solid fa-clock text-[10px]"></i> Pending
                    </span>`;
        }

        // ─── Helper: build assigned persons cell HTML ─────────────────
        function assignedCell(inc) {
            const names = [inc.assigned_person_1_name, inc.assigned_person_2_name, inc.assigned_person_3_name].filter(Boolean);
            if (names.length === 0) return `<span class="text-xs text-slate-400 italic">Unassigned</span>`;
            return `<div class="flex flex-col gap-0.5">${names.map(n => `<span class="text-xs text-slate-600 font-medium">${n}</span>`).join('')}</div>`;
        }

        // ─── AJAX Pagination ──────────────────────────────────────────
        window.ajaxPagination = new AjaxPagination({
            endpoint: '{{ route('admin.incidents.data') }}',
            containerSelector: '#incidents-container',
            paginationSelector: '#incidents-pagination',
            perPage: 10,
            getAdditionalParams: function () {
                return {
                    search:   document.getElementById('searchInput')?.value || '',
                    date:     document.getElementById('dateFilter')?.value || '',
                    reporter: document.getElementById('reporterFilter')?.value || '',
                };
            },
            renderCallback: function (incidents) {
                const container = document.querySelector('#incidents-container');

                // Update count
                const total = window.ajaxPagination._lastTotal ?? 0;
                const countEl = document.getElementById('incident-count');
                if (countEl && total !== undefined) countEl.textContent = 'Total ' + total + ' records found';

                if (incidents.length === 0) {
                    container.innerHTML = `
                        <tr><td colspan="10" class="text-center py-12">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-shield-virus text-3xl text-slate-300"></i>
                                </div>
                                <p class="text-slate-500 font-medium">No incidents found</p>
                            </div>
                        </td></tr>`;
                    return;
                }

                let html = '';
                incidents.forEach(incident => {
                    const attachmentBtn = incident.attachment_url
                        ? `<a href="${incident.attachment_url}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors"><i class="fa-solid fa-paperclip"></i> View File</a>`
                        : `<span class="text-xs text-slate-400 italic">None</span>`;

                    const escapedDesc = incident.description.replace(/"/g, '&quot;').replace(/'/g, "&#39;");
                    const attachUrl   = incident.attachment_url ? JSON.stringify(incident.attachment_url) : 'null';
                    const escapedType = incident.incident_type.replace(/'/g, "\\'");

                    html += `
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="first:pl-6">
                                <span class="font-bold text-slate-700 text-sm">${incident.reference_number}</span>
                            </td>
                            <td>
                                <span class="text-xs text-slate-600">${incident.formatted_created_at}</span>
                            </td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="font-medium text-slate-700 text-sm">${incident.formatted_date}</span>
                                    <span class="text-xs text-slate-400">${incident.formatted_time}</span>
                                </div>
                            </td>
                            <td><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium w-fit bg-indigo-50 text-indigo-700">${incident.incident_type}</span></td>
                            <td class="max-w-xs"><p class="text-sm text-slate-600 truncate" title="${escapedDesc}">${incident.description}</p></td>
                            <td>${statusBadge(incident.status)}</td>
                            <td>${assignedCell(incident)}</td>
                            <td>${attachmentBtn}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">${incident.reporter_name.charAt(0)}</div>
                                    <span class="text-xs font-medium text-slate-600">${incident.reporter_name}</span>
                                </div>
                            </td>
                            <td class="text-center last:pr-6">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="edit-incident-btn w-8 h-8 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md" title="Edit"
                                        data-id="${incident.incident_id}"
                                        data-type="${incident.incident_type.replace(/&/g,'&amp;').replace(/"/g,'&quot;')}"
                                        data-date="${incident.raw_date}"
                                        data-description="${incident.description.replace(/&/g,'&amp;').replace(/"/g,'&quot;')}"
                                        data-attachment="${incident.attachment_url || ''}"
                                        data-ap1="${incident.assigned_person_1 || ''}"
                                        data-ap2="${incident.assigned_person_2 || ''}"
                                        data-ap3="${incident.assigned_person_3 || ''}"
                                        data-status="${incident.status || 'pending'}">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </button>
                                    <button class="delete-incident-btn w-8 h-8 rounded-lg bg-gradient-to-br from-rose-500 to-red-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md" title="Delete"
                                        data-id="${incident.incident_id}">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>`;
                });
                container.innerHTML = html;
            }
        });

        // Patch to capture total for count display
        const _origLoad = window.ajaxPagination.loadPage.bind(window.ajaxPagination);

        // ─── Initialize pagination on first load ───────────────────────
        @if($incidents->hasPages())
            window.ajaxPagination.renderPagination({
                current_page: {{ $incidents->currentPage() }},
                last_page:    {{ $incidents->lastPage() }},
                from:         {{ $incidents->firstItem() ?? 0 }},
                to:           {{ $incidents->lastItem() ?? 0 }},
                total:        {{ $incidents->total() }}
            });
        @endif

        // ─── Filter listeners ─────────────────────────────────────────
        let filterTimeout;
        ['searchInput', 'dateFilter', 'reporterFilter'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', function () {
                    clearTimeout(filterTimeout);
                    filterTimeout = setTimeout(() => window.ajaxPagination.loadPage(1), 400);
                });
            }
        });

        // ─── Attachment previews ─────────────────────────────────────────
        if (window.initAttachmentPreview) {
            window.initAttachmentPreview({
                inputSelector: '#incident_attachment',
                containerSelector: '#incident-attachment-preview'
            });
            window.initAttachmentPreview({
                inputSelector: '#edit_incident_attachment',
                containerSelector: '#edit-attachment-preview'
            });
        }

        // ─── Export CSV ───────────────────────────────────────────────
        function exportIncidentsCsv() {
            const search = document.getElementById('searchInput')?.value || '';
            const date = document.getElementById('dateFilter')?.value || '';
            const reporter = document.getElementById('reporterFilter')?.value || '';
            
            const params = new URLSearchParams({ search, date, reporter });
            window.location.href = `{{ route('admin.incidents.export') }}?${params.toString()}`;
        }
    </script>
@endpush