@extends('layouts.app')

@section('title', 'Employee Management')
@section('subtitle', 'View and manage employee profile')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Summary -->
    <div class="premium-card p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-2xl bg-gradient-primary flex items-center justify-center text-white text-3xl font-bold shadow-xl border-4 border-white/20">
                    {{ strtoupper(substr($employee->first_name, 0, 1)) }}{{ strtoupper(substr($employee->last_name, 0, 1)) }}
                </div>
                <div>
                    <a href="{{ route('hr.employees.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 mb-1 group">
                        <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> BACK TO LIST
                    </a>
                    <h1 class="text-3xl font-display font-bold text-premium">
                        {{ $titles[$employee->title_id] ?? '' }} {{ $employee->full_name }}
                    </h1>
                    <div class="flex items-center gap-3 mt-1 text-sm text-slate-500 font-medium">
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-building text-indigo-400"></i> {{ $employee->department->department_name ?? 'N/A' }}</span>
                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-id-badge text-indigo-400"></i> {{ $employee->designation->designation_name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <button onclick="openModal('editEmployeeModal')"
                    class="inline-flex items-center gap-2 px-6 py-2.5 premium-button bg-gradient-brand text-white font-semibold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-pen text-sm"></i>
                    <span>Update Profile</span>
                </button>
                <button onclick="openModal('editCredsModal')"
                    class="inline-flex items-center gap-2 px-6 py-2.5 premium-button bg-gradient-brand text-white font-semibold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Security & Creds</span>
                </button>
                <button onclick="openModal('permissionsModal')"
                    class="inline-flex items-center gap-2 px-6 py-2.5 premium-button bg-slate-800 text-white font-semibold rounded-xl shadow-lg shadow-slate-800/20 hover:shadow-slate-800/40 hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Services</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="premium-card p-5 border-l-4 border-indigo-500 bg-gradient-to-br from-white to-indigo-50/30">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">IQC ID</div>
            <div class="text-2xl font-display font-bold text-indigo-900 mt-1">{{ $employee->employee_no }}</div>
        </div>
        <div class="premium-card p-5 border-l-4 border-emerald-500 bg-gradient-to-br from-white to-emerald-50/30">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Leaves Balance</div>
            <div class="text-2xl font-display font-bold text-emerald-900 mt-1">{{ $employee->leaves_open_balance }} Days</div>
        </div>
        <div class="premium-card p-5 border-l-4 border-amber-500 bg-gradient-to-br from-white to-amber-50/30">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Permission Balance</div>
            <div class="text-2xl font-display font-bold text-amber-900 mt-1">
                {{ max(0, ($employee->allowed_permission_hours ?? 8) - ($employee->permission_hours_balance ?? 0)) }} / {{ $employee->allowed_permission_hours ?? 8 }} <span class="text-sm">Hrs</span>
            </div>
        </div>
        <div class="premium-card p-5 border-l-4 border-blue-500 bg-gradient-to-br from-white to-blue-50/30">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Service Duration</div>
            <div class="text-2xl font-display font-bold text-blue-900 mt-1">
  {{ $employee->employee_join_date 
    ? \Carbon\Carbon::parse($employee->employee_join_date)->diffForHumans() 
    : 'N/A' 
}}
            </div>
        </div>
        <div class="premium-card p-5 border-l-4 {{ $employee->systemUser && $employee->systemUser->is_active ? 'border-indigo-500 bg-gradient-to-br from-white to-indigo-50/30' : 'border-slate-400 bg-gradient-to-br from-white to-slate-50/30' }}">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Account Status</div>
            <div class="mt-1 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full {{ $employee->systemUser && $employee->systemUser->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                <span class="text-xl font-display font-bold {{ $employee->systemUser && $employee->systemUser->is_active ? 'text-indigo-900' : 'text-slate-600' }}">
                    {{ $employee->systemUser && $employee->systemUser->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Details Tabs Section -->
    <div x-data="{ tab: window.location.hash ? window.location.hash.substring(1) : 'details' }" class="space-y-4">
        
        <!-- Theme Navigation (Direct Replica of Structure Nav Style) -->
        <div class="premium-card p-2 mb-8 w-fit animate-fade-in max-w-full overflow-x-auto scrollbar-hide">
            <div class="flex flex-nowrap gap-2">
                <button @click="tab = 'details'; window.location.hash = 'details'" 
                    :class="tab === 'details' ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-4 py-2 rounded-lg font-medium text-sm transition-all whitespace-nowrap flex items-center">
                    <i class="fa-solid fa-circle-info mr-2"></i>Details
                </button>
                <button @click="tab = 'credentials'; window.location.hash = 'credentials'" 
                    :class="tab === 'credentials' ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-4 py-2 rounded-lg font-medium text-sm transition-all whitespace-nowrap flex items-center">
                    <i class="fa-solid fa-shield-halved mr-2"></i>Credentials
                </button>
                <button @click="tab = 'leaves'; window.location.hash = 'leaves'" 
                    :class="tab === 'leaves' ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-4 py-2 rounded-lg font-medium text-sm transition-all whitespace-nowrap flex items-center">
                    <i class="fa-solid fa-calendar-days mr-2"></i>Leaves
                </button>
                <button @click="tab = 'permissions'; window.location.hash = 'permissions'" 
                    :class="tab === 'permissions' ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-4 py-2 rounded-lg font-medium text-sm transition-all whitespace-nowrap flex items-center">
                    <i class="fa-solid fa-user-lock mr-2"></i>Permissions
                </button>
                <button @click="tab = 'attendance'; window.location.hash = 'attendance'" 
                    :class="tab === 'attendance' ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-4 py-2 rounded-lg font-medium text-sm transition-all whitespace-nowrap flex items-center">
                    <i class="fa-solid fa-clipboard-check mr-2"></i>Attendance
                </button>
                <button @click="tab = 'da'; window.location.hash = 'da'" 
                    :class="tab === 'da' ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-4 py-2 rounded-lg font-medium text-sm transition-all whitespace-nowrap flex items-center">
                    <i class="fa-solid fa-gavel mr-2"></i>Disciplinary
                </button>
                <button @click="tab = 'performance'; window.location.hash = 'performance'" 
                    :class="tab === 'performance' ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-4 py-2 rounded-lg font-medium text-sm transition-all whitespace-nowrap flex items-center">
                    <i class="fa-solid fa-star mr-2"></i>Performance
                </button>
                <button @click="tab = 'history'; window.location.hash = 'history'" 
                    :class="tab === 'history' ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-4 py-2 rounded-lg font-medium text-sm transition-all whitespace-nowrap flex items-center">
                    <i class="fa-solid fa-clock-rotate-left mr-2"></i>History
                </button>
                <button @click="tab = 'organization'; window.location.hash = 'organization'" 
                    :class="tab === 'organization' ? 'premium-button from-emerald-600 to-teal-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-4 py-2 rounded-lg font-medium text-sm transition-all whitespace-nowrap flex items-center">
                    <i class="fa-solid fa-sitemap mr-2"></i>Organization
                </button>
            </div>
        </div>

        <div class="premium-card p-8 min-h-[400px]">
            <!-- Details Panel -->
            <div x-show="tab === 'details'" class="animate-fade-in">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-y-8 gap-x-12">
                    <div class="info-group">
                        <label>Full Name</label>
                        <p>{{ $employee->full_name }}</p>
                    </div>
                    <div class="info-group">
                        <label>Email Address</label>
                        <p>{{ $employee->employee_email }}</p>
                    </div>
                    <div class="info-group">
                        <label>Date of Birth</label>
                        <p>{{ $employee->employee_dob ? \Carbon\Carbon::parse($employee->employee_dob)->format('d M Y') : 'N/A' }}</p>
                    </div>
                    <div class="info-group">
                        <label>Gender</label>
                        <p>{{ $genders[$employee->gender_id] ?? 'N/A' }}</p>
                    </div>
                    <div class="info-group">
                        <label>Nationality</label>
                        <p>{{ $nationalities[$employee->nationality_id] ?? 'N/A' }}</p>
                    </div>
                    <div class="info-group">
                        <label>Qualification</label>
                        <p>{{ $certificates[$employee->certificate_id] ?? 'N/A' }}</p>
                    </div>
                    <div class="info-group">
                        <label>Join Date</label>
                        <p>{{ $employee->employee_join_date ? \Carbon\Carbon::parse($employee->employee_join_date)->format('d M Y') : 'N/A' }}</p>
                    </div>
                    <div class="info-group">
                        <label>Employee Type</label>
                        <p class="inline-flex px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold uppercase tracking-wider">{{ str_replace('_', ' ', $employee->employee_type ?? 'N/A') }}</p>
                    </div>
                    <div class="info-group">
                        <label>Portal Role</label>
                        <p class="inline-flex px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold uppercase tracking-wider">{{ str_replace('_', ' ', $employee->systemUser->user_type ?? 'N/A') }}</p>
                    </div>
                    <div class="info-group">
                        <label>Probation Type</label>
                        @if($employee->probation_type)
                            <p class="inline-flex px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold uppercase tracking-wider">{{ str_replace('_', ' ', $employee->probation_type) }}</p>
                        @else
                            <p class="text-slate-400 text-sm">N/A</p>
                        @endif
                    </div>
                    <div class="info-group">
                        <label>Probation End Date</label>
                        @if($employee->probation_end_date)
                            @php $endDate = \Carbon\Carbon::parse($employee->probation_end_date); @endphp
                            <p class="{{ $endDate->isPast() ? 'text-rose-600 font-semibold' : 'text-emerald-700 font-semibold' }}">
                                {{ $endDate->format('d M Y') }}
                                <span class="text-xs font-normal text-slate-400 ml-1">({{ $endDate->diffForHumans() }})</span>
                            </p>
                        @else
                            <p class="text-slate-400 text-sm">N/A</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Organization Chart Panel -->
            <div x-show="tab === 'organization'" class="animate-fade-in overflow-x-auto p-4">
                <div class="org-tree-container py-8">
                    @if($orgRoot)
                        @include('hr.employees.partials.org_node', ['dept' => $orgRoot, 'targetId' => $employee->employee_id])
                    @else
                        <div class="flex flex-col items-center justify-center py-20 text-slate-400">
                            <i class="fa-solid fa-sitemap text-6xl mb-4 opacity-20"></i>
                            <p>Organization structure data not found.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Credentials Panel -->
            <div x-show="tab === 'credentials'" class="animate-fade-in">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @php $cred_items = [
                        ['Passport', 'fa-passport', 'indigo', optional($employee->credentials)->passport_no, optional($employee->credentials)->passport_issue_date, optional($employee->credentials)->passport_expiry_date],
                        ['Visa', 'fa-address-card', 'blue', optional($employee->credentials)->visa_no, optional($employee->credentials)->visa_issue_date, optional($employee->credentials)->visa_expiry_date],
                        ['Emirates ID', 'fa-id-card', 'emerald', optional($employee->credentials)->eid_no, optional($employee->credentials)->eid_issue_date, optional($employee->credentials)->eid_expiry_date]
                    ]; @endphp

                    @foreach($cred_items as $item)
                        <div class="cred-card group border-{{ $item[2] }}-100 hover:border-{{ $item[2] }}-300 transition-all">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-{{ $item[2] }}-50 text-{{ $item[2] }}-600 flex items-center justify-center text-lg">
                                    <i class="fa-solid {{ $item[1] }}"></i>
                                </div>
                                <h3 class="font-bold text-slate-700 uppercase tracking-wider text-sm">{{ $item[0] }}</h3>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-tighter">Document Number</span>
                                    <span class="text-sm font-bold text-slate-800">{{ $item[3] ?? 'Not Set' }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-tighter">Issued</span>
                                        <span class="text-xs font-semibold text-slate-600">{{ $item[4] ?? '---' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-slate-400 block uppercase font-bold tracking-tighter">Expiry</span>
                                        <span class="text-xs font-semibold {{ \Carbon\Carbon::parse($item[5] ?? '')->isPast() ? 'text-rose-600' : 'text-slate-600' }}">
                                            {{ $item[5] ?? '---' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Leaves Panel -->
            <div x-show="tab === 'leaves'" class="animate-fade-in space-y-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-700">Leave Records & Balances</h3>
                </div>

                <!-- Annual Balances Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                    @foreach($leaveBalances as $bal)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $bal->name }}</div>
                            <div class="flex justify-between items-end">
                                <div class="text-lg font-bold text-slate-800">{{ $bal->remaining }} <span class="text-[10px] text-slate-400">Days</span></div>
                                <div class="text-[10px] font-bold text-brand">{{ round($bal->used) }}/{{ round($bal->limit) }}</div>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-1 mt-2">
                                <div class="bg-brand h-1 rounded-full" style="width: {{ $bal->limit > 0 ? ($bal->used / $bal->limit) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Filters -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div class="relative">
                            <i class="fa-solid fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" id="leave-search-filter" placeholder="Ref No..." class="premium-input w-full pl-11 py-2 text-xs">
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-tag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <select id="leave-type-filter" class="premium-input w-full pl-11 py-2 text-xs appearance-none">
                                <option value="">All Types</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->leave_type_id }}">{{ $type->leave_type_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-signal absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <select id="leave-status-filter" class="premium-input w-full pl-11 py-2 text-xs appearance-none">
                                <option value="">All Statuses</option>
                                <option value="{{ \App\Models\HrLeave::STATUS_PENDING }}">Pending HR</option>
                                <option value="{{ \App\Models\HrLeave::STATUS_PENDING_APPROVAL }}">Pending Manager</option>
                                <option value="{{ \App\Models\HrLeave::STATUS_APPROVED }}">Approved</option>
                                <option value="{{ \App\Models\HrLeave::STATUS_REJECTED }}">Rejected</option>
                                <option value="{{ \App\Models\HrLeave::STATUS_ACTION_REQUIRED }}">Action Required</option>
                            </select>
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="date" id="leave-start-filter" class="premium-input w-full pl-11 py-2 text-xs" placeholder="From">
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="date" id="leave-end-filter" class="premium-input w-full pl-11 py-2 text-xs" placeholder="To">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button onclick="applyLeaveFilters()" class="px-4 py-2 bg-slate-800 text-white font-bold rounded-lg text-xs hover:bg-slate-900 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-search"></i> Search
                        </button>
                        <button onclick="resetLeaveFilters()" class="px-4 py-2 bg-white text-slate-600 font-bold rounded-lg text-xs hover:bg-slate-100 border border-slate-200 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="premium-table w-full">
                        <thead>
                            <tr>
                                <th>REF</th>
                                <th>Type</th>
                                <th>Duration</th>
                                <th>Days</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="employee-leaves-container">
                            <!-- Populated by AJAX -->
                            @foreach($employee->leaves->take(10) as $leave)
                                <tr>
                                    <td class="font-bold">#{{ $leave->leave_id }}</td>
                                    <td>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-medium">
                                            <i class="fa-solid fa-tag text-[10px]"></i>
                                            {{ $leave->type->leave_type_name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-[11px] font-medium text-slate-600">
                                            {{ $leave->start_date ? $leave->start_date->format('d M Y') : '---' }} - 
                                            {{ $leave->end_date ? $leave->end_date->format('d M Y') : '---' }}
                                        </div>
                                    </td>
                                    <td class="font-bold text-slate-700">{{ $leave->total_days }}</td>
                                    <td>
                                        @php
                                            $statusConfig = match ($leave->leave_status_id) {
                                                \App\Models\HrLeave::STATUS_PENDING => ['bg' => 'from-yellow-400 to-amber-500', 'text' => 'Pending HR', 'icon' => 'clock'],
                                                \App\Models\HrLeave::STATUS_PENDING_APPROVAL => ['bg' => 'from-blue-500 to-cyan-600', 'text' => 'Pending Manager', 'icon' => 'user-check'],
                                                \App\Models\HrLeave::STATUS_APPROVED => ['bg' => 'from-green-500 to-emerald-600', 'text' => 'Approved', 'icon' => 'check-double'],
                                                \App\Models\HrLeave::STATUS_REJECTED => ['bg' => 'from-red-500 to-rose-600', 'text' => 'Rejected', 'icon' => 'times-circle'],
                                                \App\Models\HrLeave::STATUS_ACTION_REQUIRED => ['bg' => 'from-purple-500 to-indigo-600', 'text' => 'Action Required', 'icon' => 'user-edit'],
                                                default => ['bg' => 'from-slate-400 to-slate-500', 'text' => 'Unknown', 'icon' => 'question']
                                            };
                                        @endphp
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-gradient-to-r {{ $statusConfig['bg'] }} text-white text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                            <i class="fa-solid fa-{{ $statusConfig['icon'] }}"></i>
                                            {{ $statusConfig['text'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="employee-leaves-pagination"></div>
            </div>

            <!-- Permissions Panel -->
            <div x-show="tab === 'permissions'" class="animate-fade-in space-y-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-700">Permission History</h3>
                </div>

                <!-- Filters -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="relative">
                            <i class="fa-solid fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" id="perm-search-filter" placeholder="Ref No..." class="premium-input w-full pl-11 py-2 text-xs">
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-signal absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <select id="perm-status-filter" class="premium-input w-full pl-11 py-2 text-xs appearance-none">
                                <option value="">All Statuses</option>
                                @foreach($permissionStatuses as $status)
                                    <option value="{{ $status->permission_status_id }}">{{ $status->permission_status_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="date" id="perm-start-filter" class="premium-input w-full pl-11 py-2 text-xs" placeholder="From">
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="date" id="perm-end-filter" class="premium-input w-full pl-11 py-2 text-xs" placeholder="To">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button onclick="applyPermFilters()" class="px-4 py-2 bg-slate-800 text-white font-bold rounded-lg text-xs hover:bg-slate-900 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-search"></i> Search
                        </button>
                        <button onclick="resetPermFilters()" class="px-4 py-2 bg-white text-slate-600 font-bold rounded-lg text-xs hover:bg-slate-100 border border-slate-200 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="premium-table w-full">
                        <thead>
                            <tr>
                                <th>REF</th>
                                <th>Date</th>
                                <th>Period</th>
                                <th>Submission</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="employee-perms-container">
                            <!-- Populated by AJAX -->
                            @foreach($employee->permissions->take(10) as $perm)
                                <tr>
                                    <td class="font-bold">#{{ $perm->permission_id }}</td>
                                    <td class="font-semibold text-slate-700">{{ $perm->start_date ? $perm->start_date->format('d M Y') : '---' }}</td>
                                    <td>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-medium">
                                            <i class="fa-solid fa-clock text-[10px]"></i>
                                            {{ $perm->start_time }} - {{ $perm->end_time }}
                                        </span>
                                    </td>
                                    <td class="text-xs text-slate-500">{{ $perm->submission_date ? $perm->submission_date->format('d M Y') : '---' }}</td>
                                    <td>
                                        <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider border border-emerald-100">
                                            <i class="fa-solid fa-check-circle mr-1"></i>
                                            {{ $perm->status->permission_status_name ?? 'Approved' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="employee-perms-pagination"></div>
            </div>

            <!-- Attendance Panel -->
            <div x-show="tab === 'attendance'" class="animate-fade-in space-y-6">
                <!-- Filters -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 mb-6 font-display">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="relative">
                            <i class="fa-solid fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" id="att-search-filter" placeholder="Ref No..." class="premium-input w-full pl-11 py-2 text-xs">
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-signal absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <select id="att-status-filter" class="premium-input w-full pl-11 py-2 text-xs appearance-none">
                                <option value="">All Statuses</option>
                                <option value="present">Present</option>
                                <option value="late">Late</option>
                                <option value="absent">Absent</option>
                                <option value="on leave">Leave</option>
                            </select>
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="date" id="att-date-filter" class="premium-input w-full pl-11 py-2 text-xs">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button onclick="applyAttFilters()" class="px-4 py-2 bg-slate-800 text-white font-bold rounded-lg text-xs hover:bg-slate-900 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-search"></i> Search
                        </button>
                        <button onclick="resetAttFilters()" class="px-4 py-2 bg-white text-slate-600 font-bold rounded-lg text-xs hover:bg-slate-100 border border-slate-200 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="premium-table w-full">
                        <thead>
                            <tr>
                                <th>REF</th>
                                <th>Date</th>
                                <th>Check-In</th>
                                <th>Check-Out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="employee-attendance-container">
                            @forelse($employee->attendance->take(15) as $att)
                                <tr>
                                    <td class="font-bold text-[11px] text-slate-400">#{{ $att->attendance_id }}</td>
                                    <td>{{ $att->checkin_date instanceof \Illuminate\Support\Carbon ? $att->checkin_date->format('d M Y') : $att->checkin_date }}</td>
                                    <td><span class="font-bold text-emerald-600 text-xs">{{ $att->checkin_time }}</span></td>
                                    <td><span class="font-bold text-rose-600 text-xs">{{ $att->checkout_time }}</span></td>
                                    <td>
                                        @php
                                            $attStatus = strtolower($att->attendance_status ?? 'present');
                                            $attConfig = match($attStatus) {
                                                'present' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-100', 'icon' => 'check-circle'],
                                                'late' => ['bg' => 'bg-amber-50 text-amber-700 border-amber-100', 'icon' => 'clock-rotate-left'],
                                                'absent' => ['bg' => 'bg-rose-50 text-rose-700 border-rose-100', 'icon' => 'user-xmark'],
                                                'leave' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-100', 'icon' => 'calendar-day'],
                                                default => ['bg' => 'bg-slate-50 text-slate-700 border-slate-100', 'icon' => 'circle-question']
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-lg {{ $attConfig['bg'] }} text-[10px] font-bold uppercase tracking-wider border">
                                            <i class="fa-solid fa-{{ $attConfig['icon'] }} mr-1"></i>
                                            {{ ucfirst($attStatus) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-8 text-slate-400 italic">No attendance records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div id="employee-attendance-pagination"></div>
            </div>

            <!-- Disciplinary Panel -->
            <div x-show="tab === 'da'" class="animate-fade-in space-y-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-700">Disciplinary History</h3>
                </div>

                <!-- Filters -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="relative">
                            <i class="fa-solid fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" id="da-search-filter" placeholder="Ref No..." class="premium-input w-full pl-11 py-2 text-xs">
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-triangle-exclamation absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <select id="da-warning-filter" class="premium-input w-full pl-11 py-2 text-xs appearance-none">
                                <option value="">All Warnings</option>
                                @foreach($warningLevels as $warning)
                                    <option value="{{ $warning->da_warning_id }}">{{ $warning->da_warning_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-signal absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <select id="da-status-filter" class="premium-input w-full pl-11 py-2 text-xs appearance-none">
                                <option value="">All Statuses</option>
                                @foreach($daStatuses as $status)
                                    <option value="{{ $status->da_status_id }}">{{ $status->da_status_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="date" id="da-date-filter" class="premium-input w-full pl-11 py-2 text-xs">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button onclick="applyDaFilters()" class="px-4 py-2 bg-slate-800 text-white font-bold rounded-lg text-xs hover:bg-slate-900 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-search"></i> Search
                        </button>
                        <button onclick="resetDaFilters()" class="px-4 py-2 bg-white text-slate-600 font-bold rounded-lg text-xs hover:bg-slate-100 border border-slate-200 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </button>
                    </div>
                </div>

                <div id="employee-da-container" class="grid grid-cols-1 gap-4">
                    <!-- Populated by AJAX -->
                    @foreach($employee->disciplinaryActions->take(5) as $da)
                        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md transition-all flex justify-between items-start group relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-rose-500"></div>
                            <div class="flex-1 pl-2">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-[10px] font-bold uppercase tracking-wider border border-slate-200">
                                        <span class="text-slate-400">Nature:</span> {{ $da->type->da_type_text ?? 'N/A' }}
                                    </div>
                                    <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 text-[10px] font-bold uppercase tracking-wider border border-rose-100 italic">
                                        <span class="text-rose-300">Level:</span> {{ $da->warning->da_warning_name ?? 'Warning' }}
                                    </div>
                                    <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-500 text-[9px] font-bold uppercase border border-indigo-100">{{ $da->status->da_status_name ?? 'Open' }}</span>
                                </div>
                                <div class="flex items-center gap-3 text-[11px] text-slate-400 font-medium mb-3">
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-calendar text-rose-400"></i> {{ \Carbon\Carbon::parse($da->added_date)->format('d M Y') }}</span>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed max-w-2xl bg-slate-50/50 p-2.5 rounded-xl">{{ $da->da_remark }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-black text-slate-200 group-hover:text-slate-300 transition-colors">#{{ $da->da_id }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="employee-da-pagination"></div>
            </div>

            <!-- Performance Panel -->
            <div x-show="tab === 'performance'" class="animate-fade-in">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($employee->performance as $perf)
                        <div class="premium-card p-6 border-l-4 border-indigo-400">
                            <div class="flex justify-between items-start mb-4 pb-3 border-b border-slate-50">
                                <div>
                                    <h4 class="font-bold text-slate-700 tracking-tight">Performance Record</h4>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                        <i class="fa-solid fa-calendar-day mr-1"></i> {{ \Carbon\Carbon::parse($perf->added_date)->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="text-xs font-black text-slate-200">#{{ $perf->performance_id }}</div>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Objectives</span>
                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $perf->performance_object }}</p>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Key Results / KPIs</span>
                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $perf->performance_kpi }}</p>
                                </div>
                                @if($perf->performance_remark)
                                    <div class="bg-indigo-50/50 p-3 rounded-xl border border-indigo-100/50">
                                        <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest block mb-1">HR Remarks</span>
                                        <p class="text-xs text-indigo-700 italic">"{{ $perf->performance_remark }}"</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-12 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-100">
                            <div class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-star-half-stroke text-2xl text-slate-300"></i>
                            </div>
                            <p class="text-slate-500 font-medium italic">No performance reviews found for this employee.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- History Panel -->
            <div x-show="tab === 'history'" class="animate-fade-in">
                <div class="relative pl-8 space-y-8 before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100">
                    @forelse($employee->logs as $log)
                        <div class="relative">
                            <div class="absolute -left-[28px] top-1 w-4 h-4 rounded-full bg-white border-4 border-indigo-500 shadow-sm z-10"></div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-bold text-indigo-600 uppercase">{{ str_replace('_', ' ', $log->log_action) }}</span>
                                    <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">{{ \Carbon\Carbon::parse($log->log_date)->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-slate-600">{{ $log->log_remark }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 italic">No activity history found.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

<!-- EDIT PROFILE MODAL -->
<div class="modal" id="editEmployeeModal">
    <div class="modal-backdrop" onclick="closeModal('editEmployeeModal')"></div>
    <div class="modal-content max-w-2xl p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Update Employee Profile</h2>
                <p class="text-slate-500 text-sm mt-1">Modify basic biographical and professional details.</p>
            </div>
            <button onclick="closeModal('editEmployeeModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ route('hr.employees.update', $employee->employee_id) }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-1">
                    <label class="premium-label">Title</label>
                    <select name="title_id" class="premium-input w-full px-4 py-2.5 text-sm">
                        @foreach($titles as $id => $name)
                            <option value="{{ $id }}" {{ $employee->title_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-1">
                    <label class="premium-label">Gender</label>
                    <select name="gender_id" class="premium-input w-full px-4 py-2.5 text-sm">
                        @foreach($genders as $id => $name)
                            <option value="{{ $id }}" {{ $employee->gender_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="premium-label">First Name</label>
                    <input type="text" name="first_name" value="{{ $employee->first_name }}" class="premium-input w-full px-4 py-2.5 text-sm" required>
                </div>
                <div>
                    <label class="premium-label">Last Name</label>
                    <input type="text" name="last_name" value="{{ $employee->last_name }}" class="premium-input w-full px-4 py-2.5 text-sm" required>
                </div>
                <div>
                    <label class="premium-label">Date of Birth</label>
                    <input type="date" name="employee_dob" value="{{ $employee->employee_dob }}" class="premium-input w-full px-4 py-2.5 text-sm" required>
                </div>
                <div>
                    <label class="premium-label">Join Date</label>
                    <input type="date" name="employee_join_date" value="{{ $employee->employee_join_date }}" class="premium-input w-full px-4 py-2.5 text-sm" required>
                </div>
                <div>
                    <label class="premium-label">Leaves Open Balance</label>
                    <input type="number" step="1" name="leaves_open_balance" value="{{ $employee->leaves_open_balance }}" class="premium-input w-full px-4 py-2.5 text-sm" required>
                </div>
                <div>
                    <label class="premium-label">Allowed Permission Hrs (Monthly)</label>
                    <input type="number" step="0.5" name="allowed_permission_hours" value="{{ $employee->allowed_permission_hours ?? 8 }}" class="premium-input w-full px-4 py-2.5 text-sm" required>
                </div>
                <div>
                    <label class="premium-label">Used Permission Hrs (This Month)</label>
                    <input type="number" step="0.5" name="permission_hours_balance" value="{{ $employee->permission_hours_balance ?? 0 }}" class="premium-input w-full px-4 py-2.5 text-sm" required>
                </div>
                <div>
                    <label class="premium-label">Nationality</label>
                    <select name="nationality_id" class="premium-input w-full px-4 py-2.5 text-sm">
                        <option value="">Select Nationality</option>
                        @foreach($nationalities as $id => $name)
                            <option value="{{ $id }}" {{ $employee->nationality_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="premium-label">Qualification / Certificate</label>
                    <select name="certificate_id" class="premium-input w-full px-4 py-2.5 text-sm">
                        <option value="">Select Qualification</option>
                        @foreach($certificates as $id => $name)
                            <option value="{{ $id }}" {{ $employee->certificate_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="premium-label">Employee Type</label>
                    <select name="employee_type" class="premium-input w-full px-4 py-2.5 text-sm">
                        @foreach(['full_time' => 'Full Time', 'part_time' => 'Part Time', 'contract' => 'Contract', 'intern' => 'Intern', 'probation' => 'Probation'] as $val => $lbl)
                            <option value="{{ $val }}" {{ $employee->employee_type == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="premium-label">Probation Type</label>
                    <select name="probation_type" class="premium-input w-full px-4 py-2.5 text-sm">
                        <option value="">-- None --</option>
                        <option value="initial" {{ $employee->probation_type == 'initial' ? 'selected' : '' }}>Initial Probation</option>
                        <option value="extended" {{ $employee->probation_type == 'extended' ? 'selected' : '' }}>Extended Probation</option>
                        <option value="completed" {{ $employee->probation_type == 'completed' ? 'selected' : '' }}>Probation Completed</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="premium-label">Probation End Date</label>
                    <input type="date" name="probation_end_date" value="{{ $employee->probation_end_date }}" class="premium-input w-full px-4 py-2.5 text-sm">
                    <p class="text-[10px] text-slate-400 mt-1">Set the date when probation period ends. Leave blank if not on probation.</p>
                </div>
                <div class="col-span-2">
                    <label class="premium-label">Portal Role (User Type) <span class="text-rose-500">*</span></label>
                    <select name="user_type" class="premium-input w-full px-4 py-2.5 text-sm" required>
                        <option value="emp" {{ ($employee->systemUser->user_type ?? '') == 'emp' ? 'selected' : '' }}>Employee (Standard)</option>
                        <option value="hr" {{ ($employee->systemUser->user_type ?? '') == 'hr' ? 'selected' : '' }}>HR Manager</option>
                        <option value="eqa" {{ ($employee->systemUser->user_type ?? '') == 'eqa' ? 'selected' : '' }}>EQA Officer</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="premium-label">Department</label>
                    <select name="department_id" class="premium-input w-full px-4 py-2.5 text-sm">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->department_id }}" {{ $employee->department_id == $dept->department_id ? 'selected' : '' }}>
                                {{ $dept->department_name }} {{ $dept->is_active ? '' : '(Inactive)' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="premium-label">Designation</label>
                    <select name="designation_id" class="premium-input w-full px-4 py-2.5 text-sm">
                        @foreach($designations as $desig)
                            <option value="{{ $desig->designation_id }}" {{ $employee->designation_id == $desig->designation_id ? 'selected' : '' }}>
                                {{ $desig->designation_name }} {{ $desig->is_active ? '' : '(Inactive)' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="premium-label">Review Remarks <span class="text-rose-500">*</span></label>
                    <textarea name="log_remark" rows="3" class="premium-input w-full px-4 py-2.5 text-sm" placeholder="Provide a brief reason for these changes (for audit logs)" required></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('editEmployeeModal')" class="px-6 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                <button type="submit" class="premium-button bg-gradient-brand text-white px-6 py-2.5 rounded-xl shadow-lg shadow-brand/20 font-semibold hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-check mr-2"></i>Update Profile
                </button>
            </div>
        </form>
    </div>
</div>

<!-- CREDENTIALS MODAL -->
<div class="modal" id="editCredsModal">
    <div class="modal-backdrop" onclick="closeModal('editCredsModal')"></div>
    <div class="modal-content max-w-3xl p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Manage Security Credentials</h2>
                <p class="text-slate-500 text-sm mt-1">Official identification and residency documents.</p>
            </div>
            <button onclick="closeModal('editCredsModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ route('hr.employees.update-credentials', $employee->employee_id) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Passport Group -->
                <div class="space-y-4 p-5 bg-indigo-50/50 rounded-2xl border border-indigo-100">
                    <h3 class="text-xs font-bold text-indigo-700 uppercase tracking-widest flex items-center gap-2">
                        <i class="fa-solid fa-passport"></i> PASSPORT DETAILS
                    </h3>
                    <div class="space-y-3">
                        <input type="text" name="passport_no" placeholder="Passport Number" value="{{ optional($employee->credentials)->passport_no ?? '' }}" class="premium-input w-full px-4 py-2.5 text-sm" required>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Issue Date</label>
                                <input type="date" name="passport_issue_date" value="{{ optional($employee->credentials)->passport_issue_date ?? '' }}" class="premium-input w-full px-4 py-2 text-xs" required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Expiry Date</label>
                                <input type="date" name="passport_expiry_date" value="{{ optional($employee->credentials)->passport_expiry_date ?? '' }}" class="premium-input w-full px-4 py-2 text-xs" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visa Group -->
                <div class="space-y-4 p-5 bg-blue-50/50 rounded-2xl border border-blue-100">
                    <h3 class="text-xs font-bold text-blue-700 uppercase tracking-widest flex items-center gap-2">
                        <i class="fa-solid fa-plane"></i> VISA DETAILS
                    </h3>
                    <div class="space-y-3">
                        <input type="text" name="visa_no" placeholder="Visa Number" value="{{ optional($employee->credentials)->visa_no ?? '' }}" class="premium-input w-full px-4 py-2.5 text-sm" required>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Issue Date</label>
                                <input type="date" name="visa_issue_date" value="{{ optional($employee->credentials)->visa_issue_date ?? '' }}" class="premium-input w-full px-4 py-2 text-xs" required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Expiry Date</label>
                                <input type="date" name="visa_expiry_date" value="{{ optional($employee->credentials)->visa_expiry_date ?? '' }}" class="premium-input w-full px-4 py-2 text-xs" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EID Group -->
                <div class="space-y-4 p-5 bg-emerald-50/50 rounded-2xl border border-emerald-100 col-span-1 md:col-span-2">
                    <h3 class="text-xs font-bold text-emerald-700 uppercase tracking-widest flex items-center gap-2">
                        <i class="fa-solid fa-id-card-clip"></i> EMIRATES ID DETAILS
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <input type="text" name="eid_no" placeholder="784-XXXX-XXXXXXX-X" 
                                pattern="784-\d{4}-\d{7}-\d{1}"
                                title="Format: 784-XXXX-XXXXXXX-X"
                                value="{{ optional($employee->credentials)->eid_no ?? '' }}" 
                                class="premium-input w-full px-4 py-2.5 text-sm self-center" required>
                            <p class="text-[9px] text-slate-400 mt-1 pl-1">Required Format: 784-XXXX-XXXXXXX-X</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Issue Date</label>
                                <input type="date" name="eid_issue_date" value="{{ optional($employee->credentials)->eid_issue_date ?? '' }}" class="premium-input w-full px-4 py-2 text-xs" required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Expiry Date</label>
                                <input type="date" name="eid_expiry_date" value="{{ optional($employee->credentials)->eid_expiry_date ?? '' }}" class="premium-input w-full px-4 py-2 text-xs" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2 space-y-1 mt-2">
                    <label class="premium-label">Security Audit Remark <span class="text-rose-500">*</span></label>
                    <textarea name="log_remark" rows="2" class="premium-input w-full px-4 py-2.5 text-sm" placeholder="Reason for updating identity documents?" required></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('editCredsModal')" class="px-6 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Discard</button>
                <button type="submit" class="premium-button bg-gradient-brand text-white px-6 py-2.5 rounded-xl shadow-lg shadow-brand/20 font-semibold hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-shield-halved mr-2"></i>Secure Documents
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Permissions Modal -->
<div id="permissionsModal" class="modal">
    <div class="modal-backdrop" onclick="closeModal('permissionsModal')"></div>
    <div class="modal-content max-w-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">System Services</h2>
                <p class="text-slate-500 text-sm">Manage group and committee access</p>
            </div>
            <button onclick="closeModal('permissionsModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ route('hr.employees.update-permissions', $employee->employee_id) }}" method="POST">
            @csrf
            <div class="space-y-6">
                <label class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100 cursor-pointer hover:bg-slate-100 transition-colors">
                    <div>
                        <span class="block font-bold text-slate-800">Groups Access</span>
                        <span class="text-xs text-slate-400 italic">Allow user to manage and view departmental groups</span>
                    </div>
                    <input type="checkbox" name="is_group" value="1" {{ $employee->is_group ? 'checked' : '' }} class="w-6 h-6 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                </label>

                <label class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100 cursor-pointer hover:bg-slate-100 transition-colors">
                    <div>
                        <span class="block font-bold text-slate-800">Committees Access</span>
                        <span class="text-xs text-slate-400 italic">Allow user to participate in organizational committees</span>
                    </div>
                    <input type="checkbox" name="is_committee" value="1" {{ $employee->is_committee ? 'checked' : '' }} class="w-6 h-6 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                </label>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Log Remark</label>
                    <textarea name="log_remark" required rows="3" class="premium-input w-full px-4 py-3" placeholder="Reason for change..."></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
                <button type="button" onclick="closeModal('permissionsModal')" class="px-6 py-2.5 rounded-xl font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-2.5 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:scale-105 transition-all border border-white/10">
                    Update Services
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .tab-pill {
        @apply px-4 py-2 rounded-lg font-medium text-sm transition-all text-slate-600 hover:bg-slate-100 flex items-center whitespace-nowrap;
    }
    .tab-pill.active {
        @apply premium-button from-indigo-600 to-purple-600 bg-gradient-to-r text-white shadow-md;
    }
    .info-group label {
        @apply block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1;
    }
    .info-group p {
        @apply text-sm font-bold text-slate-800 leading-relaxed;
    }
    .cred-card {
        @apply p-6 rounded-3xl bg-white border shadow-sm hover:shadow-md transition-all;
    }
    .premium-label {
        @apply block text-[11px] font-black text-slate-500 mb-2 uppercase tracking-wider;
    }
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>



@endsection

@push('scripts')
<script src="{{ asset('js/ajax-pagination.js') }}"></script>
<script>
    // Leave Tab Pagination
    window.leavePagination = new AjaxPagination({
        instanceName: 'leavePagination',
        endpoint: "{{ route('hr.employees.leaves_data', $employee->employee_id) }}",
        containerSelector: '#employee-leaves-container',
        paginationSelector: '#employee-leaves-pagination',
        getAdditionalParams: () => ({
            search: document.getElementById('leave-search-filter').value,
            type_id: document.getElementById('leave-type-filter').value,
            status_id: document.getElementById('leave-status-filter').value,
            start_date: document.getElementById('leave-start-filter').value,
            end_date: document.getElementById('leave-end-filter').value
        }),
        renderCallback: function(leaves) {
            const container = document.querySelector('#employee-leaves-container');
            if (leaves.length === 0) {
                container.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-slate-400 italic">No leave records found matching filters.</td></tr>';
                return;
            }

            let html = '';
            leaves.forEach(leave => {
                const startDate = leave.start_date ? new Date(leave.start_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '---';
                const endDate = leave.end_date ? new Date(leave.end_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '---';
                
                let statusConfig = { bg: 'from-slate-400 to-slate-500', text: 'Unknown', icon: 'question' };
                switch(parseInt(leave.leave_status_id)) {
                    case {{ \App\Models\HrLeave::STATUS_PENDING }}: statusConfig = { bg: 'from-yellow-400 to-amber-500', text: 'Pending HR', icon: 'clock' }; break;
                    case {{ \App\Models\HrLeave::STATUS_PENDING_APPROVAL }}: statusConfig = { bg: 'from-blue-500 to-cyan-600', text: 'Pending Manager', icon: 'user-check' }; break;
                    case {{ \App\Models\HrLeave::STATUS_APPROVED }}: statusConfig = { bg: 'from-green-500 to-emerald-600', text: 'Approved', icon: 'check-double' }; break;
                    case {{ \App\Models\HrLeave::STATUS_REJECTED }}: statusConfig = { bg: 'from-red-500 to-rose-600', text: 'Rejected', icon: 'times-circle' }; break;
                    case {{ \App\Models\HrLeave::STATUS_ACTION_REQUIRED }}: statusConfig = { bg: 'from-purple-500 to-indigo-600', text: 'Action Required', icon: 'user-edit' }; break;
                }

                html += `
                    <tr>
                        <td class="font-bold">#${leave.leave_id}</td>
                        <td>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-medium">
                                <i class="fa-solid fa-tag text-[10px]"></i>
                                ${leave.type ? leave.type.leave_type_name : 'N/A'}
                            </span>
                        </td>
                        <td>
                            <div class="text-[11px] font-medium text-slate-600">
                                ${startDate} - ${endDate}
                            </div>
                        </td>
                        <td class="font-bold text-slate-700">${leave.total_days}</td>
                        <td>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-gradient-to-r ${statusConfig.bg} text-white text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                <i class="fa-solid fa-${statusConfig.icon}"></i>
                                ${statusConfig.text}
                            </span>
                        </td>
                    </tr>
                `;
            });
            container.innerHTML = html;
        }
    });

    function applyLeaveFilters() {
        window.leavePagination.loadPage(1);
    }

    function resetLeaveFilters() {
        document.getElementById('leave-search-filter').value = '';
        document.getElementById('leave-type-filter').value = '';
        document.getElementById('leave-status-filter').value = '';
        document.getElementById('leave-start-filter').value = '';
        document.getElementById('leave-end-filter').value = '';
        window.leavePagination.loadPage(1);
    }

    // Permission Tab Pagination
    window.permPagination = new AjaxPagination({
        instanceName: 'permPagination',
        endpoint: "{{ route('hr.employees.permissions_data', $employee->employee_id) }}",
        containerSelector: '#employee-perms-container',
        paginationSelector: '#employee-perms-pagination',
        getAdditionalParams: () => ({
            search: document.getElementById('perm-search-filter').value,
            status_id: document.getElementById('perm-status-filter').value,
            start_date: document.getElementById('perm-start-filter').value,
            end_date: document.getElementById('perm-end-filter').value
        }),
        renderCallback: function(permissions) {
            const container = document.querySelector('#employee-perms-container');
            if (permissions.length === 0) {
                container.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-slate-400 italic">No permission records found matching filters.</td></tr>';
                return;
            }

            let html = '';
            permissions.forEach(perm => {
                const startDate = perm.start_date ? new Date(perm.start_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '---';
                const submissionDate = perm.submission_date ? new Date(perm.submission_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '---';
                
                html += `
                    <tr>
                        <td class="font-bold">#${perm.permission_id}</td>
                        <td class="font-semibold text-slate-700">${startDate}</td>
                        <td>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-medium">
                                <i class="fa-solid fa-clock text-[10px]"></i>
                                ${perm.start_time} - ${perm.end_time}
                            </span>
                        </td>
                        <td class="text-xs text-slate-500">${submissionDate}</td>
                        <td>
                            <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider border border-emerald-100">
                                <i class="fa-solid fa-check-circle mr-1"></i>
                                ${perm.status ? perm.status.permission_status_name : 'Approved'}
                            </span>
                        </td>
                    </tr>
                `;
            });
            container.innerHTML = html;
        }
    });

    function applyPermFilters() {
        window.permPagination.loadPage(1);
    }

    function resetPermFilters() {
        document.getElementById('perm-search-filter').value = '';
        document.getElementById('perm-status-filter').value = '';
        document.getElementById('perm-start-filter').value = '';
        document.getElementById('perm-end-filter').value = '';
        window.permPagination.loadPage(1);
    }

    // Disciplinary Tab Pagination
    window.daPagination = new AjaxPagination({
        instanceName: 'daPagination',
        endpoint: "{{ route('hr.employees.disciplinary_data', $employee->employee_id) }}",
        containerSelector: '#employee-da-container',
        paginationSelector: '#employee-da-pagination',
        getAdditionalParams: () => ({
            search: document.getElementById('da-search-filter').value,
            warning_id: document.getElementById('da-warning-filter').value,
            status_id: document.getElementById('da-status-filter').value,
            date: document.getElementById('da-date-filter').value
        }),
        renderCallback: function(actions) {
            const container = document.querySelector('#employee-da-container');
            if (actions.length === 0) {
                container.innerHTML = '<div class="text-center py-8 text-slate-400 italic">No disciplinary actions found matching filters.</div>';
                return;
            }

            let html = '';
            actions.forEach(da => {
                html += `
                    <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md transition-all flex justify-between items-start group relative overflow-hidden animate-fade-in">
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-rose-500"></div>
                        <div class="flex-1 pl-2">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 text-[10px] font-black uppercase tracking-widest border border-rose-100 italic"><span class="text-rose-300">Nature:</span> ${da.type ? da.type.da_type_text : 'N/A'}</span>
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-[10px] font-bold uppercase tracking-wider border border-slate-200 italic"><span class="text-slate-400">Level:</span> ${da.warning ? da.warning.da_warning_name : 'Warning'}</span>
                                <span class="px-2 py-0.5 rounded-md bg-white text-slate-500 text-[9px] font-bold uppercase border border-slate-200">${da.status ? da.status.da_status_name : 'Open'}</span>
                            </div>
                            <div class="flex items-center gap-3 text-[11px] text-slate-400 font-medium mb-3">
                                <span class="flex items-center gap-1"><i class="fa-solid fa-calendar"></i> ${da.added_date}</span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed max-w-2xl bg-slate-50/50 p-2.5 rounded-xl">${da.da_remark}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-black text-slate-200 group-hover:text-slate-300 transition-colors">#${da.da_id}</span>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        }
    });

    // Attendance Tab Pagination
    window.attPagination = new AjaxPagination({
        instanceName: 'attPagination',
        endpoint: "{{ route('hr.employees.attendance_data', $employee->employee_id) }}",
        containerSelector: '#employee-attendance-container',
        paginationSelector: '#employee-attendance-pagination',
        getAdditionalParams: () => ({
            search: document.getElementById('att-search-filter').value,
            status: document.getElementById('att-status-filter').value,
            date: document.getElementById('att-date-filter').value
        }),
        renderCallback: function(records) {
            const container = document.querySelector('#employee-attendance-container');
            if (records.length === 0) {
                container.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-slate-400 italic">No attendance records found matching filters.</td></tr>';
                return;
            }

            let html = '';
            records.forEach(att => {
                const checkinDate = att.checkin_date ? new Date(att.checkin_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '---';
                const status = (att.attendance_status || 'present').toLowerCase();
                
                let attConfig = { bg: 'bg-slate-50 text-slate-700 border-slate-100', icon: 'circle-question' };
                if (status === 'present') attConfig = { bg: 'bg-emerald-50 text-emerald-700 border-emerald-100', icon: 'check-circle' };
                else if (status === 'late') attConfig = { bg: 'bg-amber-50 text-amber-700 border-amber-100', icon: 'clock-rotate-left' };
                else if (status === 'absent') attConfig = { bg: 'bg-rose-50 text-rose-700 border-rose-100', icon: 'user-xmark' };
                else if (status === 'leave') attConfig = { bg: 'bg-blue-50 text-blue-700 border-blue-100', icon: 'calendar-day' };

                html += `
                    <tr>
                        <td class="font-bold text-[11px] text-slate-400">#${att.attendance_id}</td>
                        <td>${checkinDate}</td>
                        <td><span class="font-bold text-emerald-600 text-xs">${att.checkin_time || '---'}</span></td>
                        <td><span class="font-bold text-rose-600 text-xs">${att.checkout_time || '---'}</span></td>
                        <td>
                            <span class="px-2.5 py-1 rounded-lg ${attConfig.bg} text-[10px] font-bold uppercase tracking-wider border">
                                <i class="fa-solid fa-${attConfig.icon} mr-1"></i>
                                ${status.charAt(0).toUpperCase() + status.slice(1)}
                            </span>
                        </td>
                    </tr>
                `;
            });
            container.innerHTML = html;
        }
    });

    function applyAttFilters() {
        window.attPagination.loadPage(1);
    }

    function resetAttFilters() {
        document.getElementById('att-search-filter').value = '';
        document.getElementById('att-status-filter').value = '';
        document.getElementById('att-date-filter').value = '';
        window.attPagination.loadPage(1);
    }

    function applyDaFilters() {
        window.daPagination.loadPage(1);
    }

    function resetDaFilters() {
        document.getElementById('da-search-filter').value = '';
        document.getElementById('da-warning-filter').value = '';
        document.getElementById('da-status-filter').value = '';
        document.getElementById('da-date-filter').value = '';
        window.daPagination.loadPage(1);
    }

    // Initial render setup for partial load
    document.addEventListener('DOMContentLoaded', () => {
        // Leaves Initial
        const initialLeaves = @json($employee->leaves->take(10));
        if(initialLeaves.length > 0) {
            window.leavePagination.renderPagination({
                current_page: 1,
                last_page: Math.ceil({{ $employee->leaves->count() }} / 10),
                from: 1,
                to: Math.min(10, {{ $employee->leaves->count() }}),
                total: {{ $employee->leaves->count() }}
            });
        }

        // Permissions Initial
        const initialPerms = @json($employee->permissions->take(10));
        if(initialPerms.length > 0) {
            window.permPagination.renderPagination({
                current_page: 1,
                last_page: Math.ceil({{ $employee->permissions->count() }} / 10),
                from: 1,
                to: Math.min(10, {{ $employee->permissions->count() }}),
                total: {{ $employee->permissions->count() }}
            });
        }

        // Disciplinary Initial
        const initialDa = @json($employee->disciplinaryActions->take(5));
        if(initialDa.length > 0) {
            window.daPagination.renderPagination({
                current_page: 1,
                last_page: Math.ceil({{ $employee->disciplinaryActions->count() }} / 10),
                from: 1,
                to: Math.min(10, {{ $employee->disciplinaryActions->count() }}),
                total: {{ $employee->disciplinaryActions->count() }}
            });
        }

        // Attendance Initial
        const initialAtt = @json($employee->attendance->take(15));
        if(initialAtt.length > 0) {
            window.attPagination.renderPagination({
                current_page: 1,
                last_page: Math.ceil({{ $employee->attendance->count() }} / 10),
                from: 1,
                to: Math.min(10, {{ $employee->attendance->count() }}),
                total: {{ $employee->attendance->count() }}
            });
        }
    });
</script>
@endpush
@section('content-extra')
@endsection


@push('styles')
<style>
    .org-tree-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: #fdfdfd;
        border-radius: 24px;
        border: 1px dashed #e2e8f0;
    }

    .org-node {
        position: relative;
        width: 100%;
        max-width: 600px;
    }

    /* Connection Lines */
    .org-node::before {
        content: '';
        position: absolute;
        top: -20px;
        left: 50%;
        width: 2px;
        height: 20px;
        background: #e2e8f0;
    }

    .org-node:first-child::before {
        display: none;
    }

    /* Vertical Lines in children */
    .org-node .border-l-2 {
        border-color: #cbd5e1;
        margin-left: 24px;
        padding-left: 32px;
    }

    .info-group label {
        display: block;
        font-size: 10px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 4px;
    }

    .info-group p {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
    }
</style>
@endpush