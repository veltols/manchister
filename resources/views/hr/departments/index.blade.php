@extends('layouts.app')

@section('title', 'Departments')
@section('subtitle', 'Manage organizational structure')

@section('content')
    <div class="space-y-6">

        <!-- Structure Navigation -->
        @include('hr.partials.structure_nav')

        <!-- Header with Action Button -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Departments</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $departments->total() }} total departments</p>
            </div>
            <button onclick="openModal('addDeptModal')"
                class="inline-flex items-center gap-2 px-6 py-3 premium-button bg-gradient-brand text-white font-semibold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                <i class="fa-solid fa-plus"></i>
                <span>Add Department</span>
            </button>
        </div>

        <!-- Departments Table -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left">Ref</th>
                            <th class="text-left">Code</th>
                            <th class="text-left">Name</th>
                            <th class="text-left">User Type</th>
                            <th class="text-left">Main Department</th>
                            <th class="text-left">Line Manager</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="departments-container">
                        @forelse($departments as $dept)
                            <tr>
                                <td><span class="font-mono text-sm font-semibold text-slate-600">#{{ $dept->department_id }}</span></td>
                                <td><span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-800 text-sm font-bold">{{ $dept->department_code }}</span></td>
                                <td><span class="font-semibold text-slate-800">{{ $dept->department_name }}</span></td>
                                <td>
                                    @php
                                        $typeLabels = ['emp' => ['label' => 'Employee', 'color' => 'bg-blue-50 text-blue-700'], 'hr' => ['label' => 'HR', 'color' => 'bg-purple-50 text-purple-700'], 'eqa' => ['label' => 'EQA', 'color' => 'bg-green-50 text-green-700']];
                                        $typeInfo = $typeLabels[$dept->user_type] ?? ['label' => ucfirst($dept->user_type ?? 'emp'), 'color' => 'bg-slate-50 text-slate-600'];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold {{ $typeInfo['color'] }}">{{ $typeInfo['label'] }}</span>
                                </td>
                                <td>
                                    @if($dept->main_department_id != 0 && $dept->mainDepartment)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-purple-50 text-purple-700 text-sm font-medium">
                                            <i class="fa-solid fa-building text-xs"></i>
                                            {{ $dept->mainDepartment->department_name }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-sm italic">Main Department</span>
                                    @endif
                                </td>
                                <td>
                                    @if($dept->lineManager)
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white text-xs font-semibold shadow-md">
                                                {{ substr($dept->lineManager->first_name, 0, 1) }}
                                            </div>
                                            <span class="text-sm text-slate-600">{{ $dept->lineManager->first_name }}
                                                {{ $dept->lineManager->last_name }}</span>
                                        </div>
                                    @else
                                        <span class="text-red-400 text-sm">Not Assigned</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-center">
                                        <button
                                            onclick="editDepartment({{ $dept->department_id }}, '{{ addslashes($dept->department_code) }}', '{{ addslashes($dept->department_name) }}', {{ $dept->main_department_id }}, {{ $dept->line_manager_id ?? 0 }}, '{{ $dept->user_type ?? 'emp' }}')"
                                            class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md"
                                            title="Edit">
                                            <i class="fa-solid fa-pen text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                            <i class="fa-solid fa-building text-2xl text-slate-400"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">No departments found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- AJAX Pagination Container -->
            <div id="departments-pagination"></div>
        </div>

    </div>

    <!-- Add Department Modal -->
    <div class="modal" id="addDeptModal">
        <div class="modal-backdrop" onclick="closeModal('addDeptModal')"></div>
        <div class="modal-content p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-display font-bold text-premium">Add New Department</h2>
                <button onclick="closeModal('addDeptModal')"
                    class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route(request()->is('admin*') ? 'admin.departments.store' : 'hr.departments.store') }}"
                method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Code</label>
                        <input type="text" name="department_code" class="premium-input w-full px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Name</label>
                        <input type="text" name="department_name" class="premium-input w-full px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Main Department</label>
                        <select name="main_department_id" class="premium-input w-full px-4 py-3 text-sm">
                            <option value="0">Not Applicable (Main)</option>
                            @foreach($allDepartments as $d)
                                <option value="{{ $d->department_id }}">{{ $d->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Line Manager</label>
                        <select name="line_manager_id" class="premium-input w-full px-4 py-3 text-sm">
                            <option value="">Select Manager</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">User Type</label>
                        <select name="user_type" class="premium-input w-full px-4 py-3 text-sm" required>
                            <option value="emp">Employee (emp)</option>
                            <option value="hr">HR (hr)</option>
                            <option value="eqa">EQA (eqa)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Remarks</label>
                        <textarea name="log_remark" class="premium-input w-full px-4 py-3 text-sm" rows="3"
                            placeholder="Optional remarks..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('addDeptModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                    <button type="submit"
                        class="px-6 py-3 premium-button bg-gradient-brand text-white font-semibold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Department Modal -->
    <div class="modal" id="editDeptModal">
        <div class="modal-backdrop" onclick="closeModal('editDeptModal')"></div>
        <div class="modal-content p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-display font-bold text-premium">Edit Department</h2>
                <button onclick="closeModal('editDeptModal')"
                    class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form id="editForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Code</label>
                        <input type="text" name="department_code" id="edit_code"
                            class="premium-input w-full px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Name</label>
                        <input type="text" name="department_name" id="edit_name"
                            class="premium-input w-full px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Main Department</label>
                        <select name="main_department_id" id="edit_main" class="premium-input w-full px-4 py-3 text-sm">
                            <option value="0">Not Applicable (Main)</option>
                            @foreach($allDepartments as $d)
                                <option value="{{ $d->department_id }}">{{ $d->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Line Manager</label>
                        <select name="line_manager_id" id="edit_manager" class="premium-input w-full px-4 py-3 text-sm">
                            <option value="">Select Manager</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">User Type</label>
                        <select name="user_type" id="edit_user_type" class="premium-input w-full px-4 py-3 text-sm" required>
                            <option value="emp">Employee (emp)</option>
                            <option value="hr">HR (hr)</option>
                            <option value="eqa">EQA (eqa)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Remarks <span class="text-red-500">*</span></label>
                        <textarea name="log_remark" id="edit_remark" class="premium-input w-full px-4 py-3 text-sm" rows="3"
                            required placeholder="Reason for update..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('editDeptModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                    <button type="submit"
                        class="px-6 py-3 premium-button bg-gradient-brand text-white font-semibold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">Update</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/ajax-pagination.js') }}"></script>
        <script>
            function editDepartment(id, code, name, mainId, managerId, userType) {
                document.getElementById('edit_code').value = code;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_main').value = mainId;
                document.getElementById('edit_manager').value = managerId || "";
                document.getElementById('edit_user_type').value = userType || 'emp';
                document.getElementById('edit_remark').value = "";

                let prefix = window.location.pathname.startsWith('/admin') ? '/admin' : '/hr';
                document.getElementById('editForm').action = prefix + "/departments/" + id + "/update";
                openModal('editDeptModal');
            }

            // Initialize AJAX Pagination
            let prefix = window.location.pathname.startsWith('/admin') ? '/admin' : '/hr';
            window.ajaxPagination = new AjaxPagination({
                endpoint: prefix + '/departments/data',
                containerSelector: '#departments-container',
                paginationSelector: '#departments-pagination',
                perPage: 15,
                renderCallback: function(departments) {
                    const container = document.querySelector('#departments-container');
                    
                    if (departments.length === 0) {
                        container.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                            <i class="fa-solid fa-building text-2xl text-slate-400"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">No departments found</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                        return;
                    }
                    
                    let html = '';
                    departments.forEach(dept => {
                        const mainDept = dept.main_department;
                        const lineManager = dept.line_manager;
                        
                        html += `
                            <tr>
                                <td><span class="font-mono text-sm font-semibold text-slate-600">#${dept.department_id}</span></td>
                                <td><span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-800 text-sm font-bold">${dept.department_code}</span></td>
                                <td><span class="font-semibold text-slate-800">${dept.department_name}</span></td>
                                <td>
                                    ${dept.main_department_id != 0 && mainDept ? `
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-purple-50 text-purple-700 text-sm font-medium">
                                            <i class="fa-solid fa-building text-xs"></i>
                                            ${mainDept.department_name}
                                        </span>
                                    ` : '<span class="text-slate-400 text-sm italic">Main Department</span>'}
                                </td>
                                <td>
                                    ${lineManager ? `
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white text-xs font-semibold shadow-md">
                                                ${lineManager.first_name.charAt(0)}
                                            </div>
                                            <span class="text-sm text-slate-600">${lineManager.first_name} ${lineManager.last_name}</span>
                                        </div>
                                    ` : '<span class="text-red-400 text-sm">Not Assigned</span>'}
                                </td>
                                <td>
                                    <div class="flex items-center justify-center">
                                        <button onclick="editDepartment(${dept.department_id}, '${dept.department_code.replace(/'/g, "\\'")}', '${dept.department_name.replace(/'/g, "\\'")}', ${dept.main_department_id}, ${dept.line_manager_id || 0}, '${dept.user_type || 'emp'}')"
                                                class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md"
                                                title="Edit">
                                            <i class="fa-solid fa-pen text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    
                    container.innerHTML = html;
                }
            });

            // Render initial pagination on page load
            @if($departments->hasPages())
                window.ajaxPagination.renderPagination({
                    current_page: {{ $departments->currentPage() }},
                    last_page: {{ $departments->lastPage() }},
                    from: {{ $departments->firstItem() ?? 0 }},
                    to: {{ $departments->lastItem() ?? 0 }},
                    total: {{ $departments->total() }}
                });
            @endif
        </script>
    @endpush
@endsection