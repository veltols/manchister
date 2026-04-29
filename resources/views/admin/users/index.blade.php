@extends('layouts.app')

@section('title', 'Manage Users')
@section('subtitle', 'System user accounts and access control')

@section('content')
    <div class="space-y-6 animate-fade-in-up">
        
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">System Users</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $users->total() }} registered users</p>
            </div>
            <!-- Create Button -->
            <button onclick="openModal('newUserModal')" 
                class="inline-flex items-center gap-2 px-6 py-3 premium-button bg-brand text-white font-semibold rounded-xl shadow-lg hover:translate-y-[-2px] transition-all duration-200">
                <i class="fa-solid fa-plus text-sm"></i>
                <span>Add New User</span>
            </button>
        </div>

        <!-- Filters & Search (HR Style) -->
        <div class="premium-card p-6 border border-slate-100 shadow-sm">
            <div class="flex flex-wrap gap-4 items-center">
                <div class="flex-1 min-w-[300px]">
                    <div class="relative group">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors"></i>
                        <input type="text" id="userListSearch" placeholder="Search by name, ID or email..."
                            class="premium-input pl-11 pr-4 py-2.5 w-full text-sm focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400">
                    </div>
                </div>
                <select id="filterDepartment" class="premium-input px-4 py-2.5 text-sm min-w-[220px] focus:ring-4 focus:ring-indigo-100">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
                    @endforeach
                </select>
                <select id="filterStatus" class="premium-input px-4 py-2.5 text-sm min-w-[150px] focus:ring-4 focus:ring-indigo-100">
                    <option value="">All Status</option>
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                </select>
                <!-- <button onclick="window.ajaxPagination.loadPage(1)" 
                    class="px-5 py-2.5 bg-slate-900 text-white rounded-xl text-sm font-bold shadow-lg hover:shadow-slate-200 transition-all active:scale-95">
                    Search
                </button> -->
            </div>
        </div>

        <!-- Users List -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left w-24">IQC ID</th>
                            <th class="text-left">Employee Name</th>
                            <th class="text-left">Email / Login ID</th>
                            <th class="text-left">Role</th>
                            <th class="text-left">Department</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Feedback</th>
                            <th class="text-center"><i class="fa-solid fa-crown text-amber-500 mr-1"></i>GM</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-container">
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <span class="font-mono text-sm font-semibold text-slate-600">{{ $user->employee_no }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $user->first_name }} {{ $user->last_name }}</p>
                                            <p class="text-xs text-slate-400">{{ $user->designation->designation_name ?? 'Employee' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-slate-600">{{ $user->employee_email }}</td>
                                <td>
                                    @php
                                        $type = $user->systemUser?->user_type ?? 'emp';
                                        $badgeClass = match($type) {
                                            'root', 'sys_admin' => 'bg-slate-900 text-white',
                                            'admin_hr', 'hr' => 'bg-indigo-100 text-indigo-700',
                                            'eqa' => 'bg-amber-100 text-amber-700',
                                            default => 'bg-slate-100 text-slate-600'
                                        };
                                        $typeName = match($type) {
                                            'root' => 'Root',
                                            'sys_admin' => 'Admin',
                                            'admin_hr' => 'Manage HR',
                                            'hr' => 'HR User',
                                            'eqa' => 'EQA',
                                            default => 'Employee'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                                        {{ $typeName }}
                                    </span>
                                </td>
                                <td>
                                    @if($user->department)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-purple-50 text-purple-700 text-xs font-medium border border-purple-100/50">
                                            <i class="fa-solid fa-building text-[10px] opacity-70"></i>
                                            {{ $user->department->department_name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-50 border border-slate-100 text-slate-400 text-xs font-medium italic">
                                            Unassigned
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ ($user->systemUser?->is_active ?? 1) ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }} text-xs font-bold">
                                        <i class="fa-solid fa-circle text-[8px]"></i>
                                        {{ ($user->systemUser?->is_active ?? 1) ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php $fbEnabled = $user->systemUser?->feedback_enabled ?? 1; @endphp
                                    <button class="feedback-toggle-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold transition-all hover:scale-105
                                        {{ $fbEnabled ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}"
                                        data-id="{{ $user->employee_id }}"
                                        data-enabled="{{ $fbEnabled }}"
                                        title="{{ $fbEnabled ? 'Click to disable feedback' : 'Click to enable feedback' }}">
                                        <i class="fa-solid {{ $fbEnabled ? 'fa-comment-dots' : 'fa-comment-slash' }} text-[10px]"></i>
                                        {{ $fbEnabled ? 'Enabled' : 'Disabled' }}
                                    </button>
                                </td>
                                <td class="text-center">
                                    @php $isGm = $user->systemUser?->is_gm ?? 0; @endphp
                                    <button class="gm-toggle-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold transition-all hover:scale-105
                                        {{ $isGm ? 'bg-amber-100 text-amber-700 border border-amber-300' : 'bg-slate-100 text-slate-400 border border-slate-200' }}"
                                        data-id="{{ $user->employee_id }}"
                                        data-is-gm="{{ $isGm }}"
                                        title="{{ $isGm ? 'Click to remove GM role' : 'Click to designate as General Manager' }}">
                                        <i class="fa-solid fa-crown text-[10px]"></i>
                                        {{ $isGm ? 'GM' : 'Not GM' }}
                                    </button>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.users.show', $user->employee_id) }}" 
                                           class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md"
                                           title="View Details">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </a>
                                       
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-500">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- AJAX Pagination Container -->
            <div id="users-pagination"></div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/ajax-pagination.js') }}"></script>
        <script>
            function closeModal(id) {
                document.getElementById(id).classList.remove('active');
            }
            function openModal(id) {
                document.getElementById(id).classList.add('active');
            }
            function togglePasswordVisibility(inputId, btn) {
                const input = document.getElementById(inputId);
                const icon = btn.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                }
            }

            // Status Badge Helper
            function getStatusBadge(isActive) {
                if (isActive) {
                    return `
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                            <i class="fa-solid fa-circle text-[8px]"></i>
                            Active
                        </span>
                    `;
                }
                return `
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-bold">
                        <i class="fa-solid fa-circle text-[8px]"></i>
                        Inactive
                    </span>
                `;
            }

            // Department Badge Helper
            function getDeptBadge(dept) {
                if (dept) {
                    return `
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-purple-50 text-purple-700 text-xs font-medium border border-purple-100/50">
                            <i class="fa-solid fa-building text-[10px] opacity-70"></i>
                            ${dept.department_name}
                        </span>
                    `;
                }
                return `
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-50 border border-slate-100 text-slate-400 text-xs font-medium italic">
                        Unassigned
                    </span>
                `;
            }

            // Feedback Toggle Badge Helper
            function getFeedbackToggle(userId, enabled) {
                const cls = enabled
                    ? 'bg-emerald-100 text-emerald-700 border-emerald-200'
                    : 'bg-slate-100 text-slate-400 border-slate-200';
                const icon = enabled ? 'fa-comment-dots' : 'fa-comment-slash';
                const label = enabled ? 'Enabled' : 'Disabled';
                const title = enabled ? 'Click to disable feedback' : 'Click to enable feedback';
                return `<button class="feedback-toggle-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border transition-all hover:scale-105 ${cls}" data-id="${userId}" data-enabled="${enabled ? 1 : 0}" title="${title}"><i class="fa-solid ${icon} text-[10px]"></i> ${label}</button>`;
            }

            // GM Toggle Badge Helper
            function getGmToggle(userId, isGm) {
                const cls = isGm
                    ? 'bg-amber-100 text-amber-700 border-amber-300'
                    : 'bg-slate-100 text-slate-400 border-slate-200';
                const label = isGm ? 'GM' : 'Not GM';
                const title = isGm ? 'Click to remove GM role' : 'Click to designate as General Manager';
                return `<button class="gm-toggle-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border transition-all hover:scale-105 ${cls}" data-id="${userId}" data-is-gm="${isGm ? 1 : 0}" title="${title}"><i class="fa-solid fa-crown text-[10px]"></i> ${label}</button>`;
            }

            // Initialize AJAX Pagination
            const prefix = "{{ route('admin.users.index') }}".replace('/users', ''); // Get base admin URL
            
            window.ajaxPagination = new AjaxPagination({
                endpoint: "{{ route('admin.users.data') }}",
                containerSelector: '#users-container',
                paginationSelector: '#users-pagination',
                perPage: 15,
                getAdditionalParams: function() {
                    return {
                        search: document.getElementById('userListSearch').value,
                        department_id: document.getElementById('filterDepartment').value,
                        status: document.getElementById('filterStatus').value
                    };
                },
                renderCallback: function(users) {
                    const container = document.querySelector('#users-container');
                    
                    if (users.length === 0) {
                        container.innerHTML = `
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-500">
                                    No users found.
                                </td>
                            </tr>
                        `;
                        return;
                    }
                    
                    let html = '';
                    users.forEach(user => {
                        const firstInitial = user.first_name ? user.first_name.substring(0, 1) : '';
                        const lastInitial = user.last_name ? user.last_name.substring(0, 1) : '';
                        const designationName = user.designation ? user.designation.designation_name : 'Employee';
                        const showUrl = `{{ route('admin.users.show', ':id') }}`.replace(':id', user.employee_id);
                        const sysUser = user.systemUser || user.system_user;
                        const isActive = sysUser ? sysUser.is_active : 1; // Default to 1 if not joined

                        html += `
                            <tr>
                                <td>
                                    <span class="font-mono text-sm font-semibold text-slate-600">${user.employee_no || ''}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                                            ${firstInitial}${lastInitial}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">${user.first_name} ${user.last_name}</p>
                                            <p class="text-xs text-slate-400">${designationName}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-slate-600">${user.employee_email}</td>
                                <td>
                                    ${(() => {
                                        const type = (user.systemUser || user.system_user)?.user_type || 'emp';
                                        let badgeClass = 'bg-slate-100 text-slate-600';
                                        let typeName = 'Employee';
                                        
                                        if(['root', 'sys_admin'].includes(type)) {
                                            badgeClass = 'bg-slate-900 text-white';
                                            typeName = type === 'root' ? 'Root' : 'Admin';
                                        } else if(['admin_hr', 'hr'].includes(type)) {
                                            badgeClass = 'bg-indigo-100 text-indigo-700';
                                            typeName = type === 'admin_hr' ? 'Manage HR' : 'HR User';
                                        } else if(type === 'eqa') {
                                            badgeClass = 'bg-amber-100 text-amber-700';
                                            typeName = 'EQA';
                                        }
                                        
                                        return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${badgeClass}">${typeName}</span>`;
                                    })()}
                                </td>
                                <td>
                                    ${getDeptBadge(user.department)}
                                </td>
                                <td class="text-center">
                                    ${getStatusBadge(isActive)}
                                </td>
                                <td class="text-center">
                                    ${getFeedbackToggle(user.employee_id, user.system_user?.feedback_enabled ?? 1)}
                                </td>
                                <td class="text-center">
                                    ${getGmToggle(user.employee_id, user.system_user?.is_gm ?? 0)}
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="${showUrl}" 
                                           class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md"
                                           title="View Details">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    
                    container.innerHTML = html;
                }
            });

            // Render initial pagination on page load
            @if($users->hasPages())
                window.ajaxPagination.renderPagination({
                    current_page: {{ $users->currentPage() }},
                    last_page: {{ $users->lastPage() }},
                    from: {{ $users->firstItem() ?? 0 }},
                    to: {{ $users->lastItem() ?? 0 }},
                    total: {{ $users->total() }}
                });
            @endif

            // Feedback Toggle — delegated click handler
            const toggleFbBase = "{{ url('admin/users') }}";
            const csrfToken    = "{{ csrf_token() }}";

            document.addEventListener('click', function (e) {
                // Feedback toggle
                const fbBtn = e.target.closest('.feedback-toggle-btn');
                if (fbBtn) {
                    const userId  = fbBtn.dataset.id;
                    fetch(`${toggleFbBase}/${userId}/toggle-feedback`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            const isNowEnabled = data.feedback_enabled;
                            fbBtn.dataset.enabled = isNowEnabled ? '1' : '0';
                            fbBtn.className = `feedback-toggle-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border transition-all hover:scale-105 ${isNowEnabled ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-400 border-slate-200'}`;
                            fbBtn.title = isNowEnabled ? 'Click to disable feedback' : 'Click to enable feedback';
                            fbBtn.innerHTML = `<i class="fa-solid ${isNowEnabled ? 'fa-comment-dots' : 'fa-comment-slash'} text-[10px]"></i> ${isNowEnabled ? 'Enabled' : 'Disabled'}`;
                            Toast.fire({ icon: isNowEnabled ? 'success' : 'info', title: data.message });
                        } else {
                            Toast.fire({ icon: 'error', title: 'Failed to update feedback setting.' });
                        }
                    })
                    .catch(() => Toast.fire({ icon: 'error', title: 'An error occurred.' }));
                    return;
                }

                // GM toggle
                const gmBtn = e.target.closest('.gm-toggle-btn');
                if (gmBtn) {
                    const userId = gmBtn.dataset.id;
                    const isCurrentlyGm = parseInt(gmBtn.dataset.isGm);
                    const action = isCurrentlyGm ? 'remove the GM role from' : 'designate';
                    const empName = gmBtn.closest('tr')?.querySelector('p.font-semibold')?.textContent?.trim() || 'this user';

                    Swal.fire({
                        icon: 'question',
                        title: isCurrentlyGm ? 'Remove GM Role?' : 'Designate as GM?',
                        text: isCurrentlyGm
                            ? `Remove GM designation from ${empName}?`
                            : `Designate ${empName} as General Manager? Any existing GM will be unset.`,
                        showCancelButton: true,
                        confirmButtonColor: isCurrentlyGm ? '#ef4444' : '#f59e0b',
                        confirmButtonText: isCurrentlyGm ? 'Yes, Remove' : 'Yes, Designate',
                    }).then(result => {
                        if (!result.isConfirmed) return;
                        fetch(`${toggleFbBase}/${userId}/toggle-gm`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                // Reset all GM buttons first (since only one GM at a time)
                                document.querySelectorAll('.gm-toggle-btn').forEach(btn => {
                                    btn.dataset.isGm = '0';
                                    btn.className = 'gm-toggle-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border transition-all hover:scale-105 bg-slate-100 text-slate-400 border-slate-200';
                                    btn.title = 'Click to designate as General Manager';
                                    btn.innerHTML = '<i class="fa-solid fa-crown text-[10px]"></i> Not GM';
                                });
                                // Update clicked button if now GM
                                if (data.is_gm) {
                                    gmBtn.dataset.isGm = '1';
                                    gmBtn.className = 'gm-toggle-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border transition-all hover:scale-105 bg-amber-100 text-amber-700 border-amber-300';
                                    gmBtn.title = 'Click to remove GM role';
                                    gmBtn.innerHTML = '<i class="fa-solid fa-crown text-[10px]"></i> GM';
                                }
                                Toast.fire({ icon: 'success', title: data.message });
                            } else {
                                Toast.fire({ icon: 'error', title: data.message || 'Failed to update GM status.' });
                            }
                        })
                        .catch(() => Toast.fire({ icon: 'error', title: 'An error occurred.' }));
                    });
                }
            });
            // Filter Event Listeners
            const searchInput = document.getElementById('userListSearch');
            const deptFilter = document.getElementById('filterDepartment');

            let searchTimeout;
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    window.ajaxPagination.loadPage(1);
                }, 500);
            });

            deptFilter.addEventListener('change', () => window.ajaxPagination.loadPage(1));
            document.getElementById('filterStatus').addEventListener('change', () => window.ajaxPagination.loadPage(1));

            // Password Validation Logic
            const passInput = document.getElementById('user-password');
            const passCriteria = document.getElementById('pass-criteria');
            const createForm = document.querySelector('form[action="{{ route("admin.users.store") }}"]');

            if (passInput) {
                const crits = {
                    length: document.getElementById('crit-length'),
                    upper: document.getElementById('crit-upper'),
                    lower: document.getElementById('crit-lower'),
                    number: document.getElementById('crit-num'),
                    special: document.getElementById('crit-special')
                };

                const validate = (val) => {
                    const checks = {
                        length: val.length >= 8,
                        upper: /[A-Z]/.test(val),
                        lower: /[a-z]/.test(val),
                        number: /[0-9]/.test(val),
                        special: /[^A-Za-z0-9]/.test(val)
                    };

                    Object.keys(checks).forEach(k => {
                        const el = crits[k];
                        if (checks[k]) {
                            el.classList.remove('text-slate-400');
                            el.classList.add('text-emerald-500', 'font-bold');
                            el.querySelector('i').classList.replace('fa-circle-check', 'fa-check');
                        } else {
                            el.classList.add('text-slate-400');
                            el.classList.remove('text-emerald-500', 'font-bold');
                            el.querySelector('i').classList.replace('fa-check', 'fa-circle-check');
                        }
                    });

                    return Object.values(checks).every(v => v);
                };

                passInput.addEventListener('focus', () => passCriteria.classList.remove('hidden'));
                passInput.addEventListener('input', (e) => validate(e.target.value));

                if (createForm) {
                    createForm.addEventListener('submit', (e) => {
                        if (!validate(passInput.value)) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'warning',
                                title: 'Insecure Password',
                                text: 'Please ensure your password meets all the security requirements.',
                                confirmButtonColor: '#4f46e5'
                            });
                        }
                    });
                }
            }

            // GM Checkbox Alert for New User Modal
            const newGmCheckbox = document.getElementById('modal_is_gm');
            if (newGmCheckbox) {
                newGmCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        Swal.fire({
                            title: 'Designate as GM?',
                            text: "Assigning this user as General Manager will automatically unset any existing GM in the system. Are you sure?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#f59e0b',
                            confirmButtonText: 'Yes, I am sure',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (!result.isConfirmed) {
                                this.checked = false;
                            }
                        });
                    }
                });
            }
        </script>
    @endpush

    <!-- New User Modal -->
    <div id="newUserModal" class="modal {{ $errors->has('employee_no') || $errors->has('employee_email') || $errors->has('first_name') ? 'active' : '' }}">
        <div class="modal-backdrop" onclick="closeModal('newUserModal')"></div>
        <div class="modal-content max-w-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">Add New User</h2>
                    <p class="text-slate-500 text-sm">Create a new system user account</p>
                </div>
                <button onclick="closeModal('newUserModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">IQC ID</label>
                            <input type="text" name="employee_no" value="{{ old('employee_no') }}" required class="premium-input w-full px-4 py-3 text-sm {{ $errors->has('employee_no') ? 'border-rose-500' : '' }}" placeholder="e.g. 1045">
                            @error('employee_no')
                                <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">User Type</label>
                            <select name="user_type" required class="premium-input w-full px-4 py-3 text-sm">
                                <option value="emp">Employee (emp)</option>
                                <option value="hr">HR (hr)</option>
                                <option value="eqa">EQA (eqa)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Department</label>
                        <select name="department_id" required class="premium-input w-full px-4 py-3 text-sm">
                            <option value="">Select Department...</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-indigo-50 border border-indigo-100">
                            <input type="checkbox" name="is_line_manager" id="modal_is_line_manager" value="1" class="w-5 h-5 rounded border-indigo-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="modal_is_line_manager" class="text-xs font-bold text-indigo-900 cursor-pointer leading-tight">
                                Set as Line Manager for this Department
                            </label>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-amber-50 border border-amber-100">
                            <input type="checkbox" name="is_gm" id="modal_is_gm" value="1" class="w-5 h-5 rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                            <label for="modal_is_gm" class="text-xs font-bold text-amber-900 cursor-pointer leading-tight">
                                Designate as General Manager (GM)
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required class="premium-input w-full px-4 py-3 text-sm" placeholder="John">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required class="premium-input w-full px-4 py-3 text-sm" placeholder="Doe">
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-4 mt-2">
                        <h3 class="text-sm font-bold text-indigo-900 mb-4">Login Credentials</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Login ID</label>
                                <input type="email" name="employee_email" value="{{ old('employee_email') }}" class="premium-input w-full px-4 py-3 text-sm {{ $errors->has('employee_email') ? 'border-rose-500' : '' }}" placeholder="e.g. email@example.com" required>
                                @error('employee_email')
                                    <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative group">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                                <div class="relative">
                                    <input type="password" id="user-password" name="password" required 
                                           class="premium-input w-full pl-4 pr-11 py-3 text-sm transition-all duration-200" 
                                           placeholder="••••••••">
                                    <button type="button" onclick="togglePasswordVisibility('user-password', this)" 
                                            class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center text-slate-400 hover:text-indigo-600 transition-colors">
                                        <i class="fa-solid fa-eye-slash text-sm"></i>
                                    </button>
                                </div>
                                <div id="pass-criteria" class="mt-3 space-y-1.5 p-3 rounded-lg bg-slate-50/50 border border-slate-100 hidden">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Security Requirements:</p>
                                    <div class="grid grid-cols-1 gap-1">
                                        <div id="crit-length" class="flex items-center gap-2 text-[10px] text-slate-400 transition-colors">
                                            <i class="fa-solid fa-circle-check text-[8px]"></i> At least 8 characters
                                        </div>
                                        <div id="crit-upper" class="flex items-center gap-2 text-[10px] text-slate-400 transition-colors">
                                            <i class="fa-solid fa-circle-check text-[8px]"></i> One uppercase letter
                                        </div>
                                        <div id="crit-lower" class="flex items-center gap-2 text-[10px] text-slate-400 transition-colors">
                                            <i class="fa-solid fa-circle-check text-[8px]"></i> One lowercase letter
                                        </div>
                                        <div id="crit-num" class="flex items-center gap-2 text-[10px] text-slate-400 transition-colors">
                                            <i class="fa-solid fa-circle-check text-[8px]"></i> One number
                                        </div>
                                        <div id="crit-special" class="flex items-center gap-2 text-[10px] text-slate-400 transition-colors">
                                            <i class="fa-solid fa-circle-check text-[8px]"></i> One symbol (!@#$%^&*)
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('newUserModal')" class="px-6 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                        <i class="fa-solid fa-user-plus mr-2"></i>Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection