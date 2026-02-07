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
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="premium-card p-5 border-l-4 border-indigo-500 bg-gradient-to-br from-white to-indigo-50/30">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Employee Code</div>
            <div class="text-2xl font-display font-bold text-indigo-900 mt-1">{{ $employee->employee_code }}</div>
        </div>
        <div class="premium-card p-5 border-l-4 border-emerald-500 bg-gradient-to-br from-white to-emerald-50/30">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Leaves Balance</div>
            <div class="text-2xl font-display font-bold text-emerald-900 mt-1">{{ $employee->leaves_open_balance }} Days</div>
        </div>
        <div class="premium-card p-5 border-l-4 border-blue-500 bg-gradient-to-br from-white to-blue-50/30">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Service Duration</div>
            <div class="text-2xl font-display font-bold text-blue-900 mt-1">
                {{ $employee->employee_join_date ? \Carbon\Carbon::parse($employee->employee_join_date)->diffInYears(now()) . ' Years' : 'N/A' }}
            </div>
        </div>
        <div class="premium-card p-5 border-l-4 border-purple-500 bg-gradient-to-br from-white to-purple-50/30">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Account Status</div>
            <div class="mt-1 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xl font-display font-bold text-slate-700">Active</span>
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
                        <p class="inline-flex px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold uppercase tracking-wider">{{ $employee->employee_type }}</p>
                    </div>
                </div>
            </div>

            <!-- Credentials Panel -->
            <div x-show="tab === 'credentials'" class="animate-fade-in">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @php $cred_items = [
                        ['Passport', 'fa-passport', 'indigo', $employee->credentials->passport_no, $employee->credentials->passport_issue_date, $employee->credentials->passport_expiry_date],
                        ['Visa', 'fa-address-card', 'blue', $employee->credentials->visa_no, $employee->credentials->visa_issue_date, $employee->credentials->visa_expiry_date],
                        ['Emirates ID', 'fa-id-card', 'emerald', $employee->credentials->eid_no, $employee->credentials->eid_issue_date, $employee->credentials->eid_expiry_date]
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
            <div x-show="tab === 'leaves'" class="animate-fade-in">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-700">Leave Records</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="premium-table w-full">
                        <thead>
                            <tr>
                                <th>REF</th>
                                <th>Type</th>
                                <th>Submission</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Days</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->leaves as $leave)
                                <tr>
                                    <td class="font-bold">#{{ $leave->leave_id }}</td>
                                    <td>{{ $leave->type->leave_type_name ?? 'N/A' }}</td>
                                    <td>{{ $leave->submission_date ? $leave->submission_date->format('d M Y') : '---' }}</td>
                                    <td>{{ $leave->start_date ? $leave->start_date->format('d M Y') : '---' }}</td>
                                    <td>{{ $leave->end_date ? $leave->end_date->format('d M Y') : '---' }}</td>
                                    <td>{{ $leave->total_days }}</td>
                                    <td>
                                        <span class="px-2 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider">Approved</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center py-8 text-slate-400 italic">No leave records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Permissions Panel -->
            <div x-show="tab === 'permissions'" class="animate-fade-in">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-700">Permission History</h3>
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
                        <tbody>
                            @forelse($employee->permissions as $perm)
                                <tr>
                                    <td class="font-bold">#{{ $perm->permission_id }}</td>
                                    <td>{{ $perm->start_date ? $perm->start_date->format('d M Y') : '---' }}</td>
                                    <td>{{ $perm->start_time }} - {{ $perm->end_time }}</td>
                                    <td>{{ $perm->submission_date ? $perm->submission_date->format('d M Y') : '---' }}</td>
                                    <td>
                                        <span class="px-2 py-1 rounded-lg bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider">{{ $perm->status->permission_status_name ?? 'Approved' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-8 text-slate-400 italic">No permission records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Attendance Panel -->
            <div x-show="tab === 'attendance'" class="animate-fade-in">
                <div class="overflow-x-auto">
                    <table class="premium-table w-full">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Check-In</th>
                                <th>Check-Out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->attendance as $att)
                                <tr>
                                    <td>{{ $att->checkin_date instanceof \Illuminate\Support\Carbon ? $att->checkin_date->format('d M Y') : $att->checkin_date }}</td>
                                    <td><span class="font-bold text-emerald-600">{{ $att->checkin_time }}</span></td>
                                    <td><span class="font-bold text-rose-600">{{ $att->checkout_time }}</span></td>
                                    <td>
                                        <span class="px-2 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider">Present</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-8 text-slate-400 italic">No attendance records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Disciplinary Panel -->
            <div x-show="tab === 'da'" class="animate-fade-in">
                <div class="grid grid-cols-1 gap-4">
                    @forelse($employee->disciplinaryActions as $da)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex justify-between items-center group">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 rounded-lg bg-rose-100 text-rose-700 text-[10px] font-bold uppercase">{{ $da->type->da_type_name ?? 'DA' }}</span>
                                    <h4 class="font-bold text-slate-700">{{ $da->warning->da_warning_name ?? 'Warning' }}</h4>
                                </div>
                                <p class="text-xs text-slate-500">{{ $da->da_date }} - {{ $da->da_description }}</p>
                            </div>
                            <span class="text-sm font-bold text-slate-400 group-hover:text-slate-600 transition-colors">#{{ $da->da_id }}</span>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 italic">No disciplinary actions recorded.</div>
                    @endforelse
                </div>
            </div>

            <!-- Performance Panel -->
            <div x-show="tab === 'performance'" class="animate-fade-in">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($employee->performance as $perf)
                        <div class="premium-card p-6 border-l-4 border-indigo-400">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="font-bold text-slate-700">Review Period: {{ $perf->review_period }}</h4>
                                    <p class="text-xs text-slate-400">{{ $perf->review_date }}</p>
                                </div>
                                <div class="text-2xl font-display font-bold text-indigo-600">{{ $perf->overall_score }}/100</div>
                            </div>
                            <p class="text-sm text-slate-600 italic">"{{ $perf->manager_comments }}"</p>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-8 text-slate-400 italic">No performance reviews found.</div>
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
                                    <span class="text-[10px] font-bold text-slate-400">{{ \Carbon\Carbon::parse($log->log_date)->format('d M Y, h:i A') }}</span>
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
                <div class="col-span-2">
                    <label class="premium-label">Department</label>
                    <select name="department_id" class="premium-input w-full px-4 py-2.5 text-sm">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->department_id }}" {{ $employee->department_id == $dept->department_id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="premium-label">Designation</label>
                    <select name="designation_id" class="premium-input w-full px-4 py-2.5 text-sm">
                        @foreach($designations as $desig)
                            <option value="{{ $desig->designation_id }}" {{ $employee->designation_id == $desig->designation_id ? 'selected' : '' }}>{{ $desig->designation_name }}</option>
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
                        <input type="text" name="passport_no" placeholder="Passport Number" value="{{ $employee->credentials->passport_no ?? '' }}" class="premium-input w-full px-4 py-2.5 text-sm">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Issue Date</label>
                                <input type="date" name="passport_issue_date" value="{{ $employee->credentials->passport_issue_date ?? '' }}" class="premium-input w-full px-4 py-2 text-xs">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Expiry Date</label>
                                <input type="date" name="passport_expiry_date" value="{{ $employee->credentials->passport_expiry_date ?? '' }}" class="premium-input w-full px-4 py-2 text-xs">
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
                        <input type="text" name="visa_no" placeholder="Visa Number" value="{{ $employee->credentials->visa_no ?? '' }}" class="premium-input w-full px-4 py-2.5 text-sm">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Issue Date</label>
                                <input type="date" name="visa_issue_date" value="{{ $employee->credentials->visa_issue_date ?? '' }}" class="premium-input w-full px-4 py-2 text-xs">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Expiry Date</label>
                                <input type="date" name="visa_expiry_date" value="{{ $employee->credentials->visa_expiry_date ?? '' }}" class="premium-input w-full px-4 py-2 text-xs">
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
                        <input type="text" name="eid_no" placeholder="Emirates ID (784-XXXX-XXXXXXX-X)" value="{{ $employee->credentials->eid_no ?? '' }}" class="premium-input w-full px-4 py-2.5 text-sm self-center">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Issue Date</label>
                                <input type="date" name="eid_issue_date" value="{{ $employee->credentials->eid_issue_date ?? '' }}" class="premium-input w-full px-4 py-2 text-xs">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Expiry Date</label>
                                <input type="date" name="eid_expiry_date" value="{{ $employee->credentials->eid_expiry_date ?? '' }}" class="premium-input w-full px-4 py-2 text-xs">
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