@extends('layouts.app')

@section('title', 'Employee Details')
@section('subtitle', 'View employee information')

@section('content')
<div class="space-y-6">

    <!-- Header with Actions -->
    <div class="premium-card p-6">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('hr.employees.index') }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 text-sm font-semibold mb-3">
                    <i class="fa-solid fa-arrow-left"></i>Back to List
                </a>
                <h1 class="text-3xl font-display font-bold text-premium">
                    {{ $employee->first_name }} {{ $employee->last_name }}
                    @if($employee->is_new == 1)
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-bold shadow-md ml-3">
                            <i class="fa-solid fa-star text-xs"></i>NEW
                        </span>
                    @endif
                </h1>
            </div>
            <div class="flex gap-3">
                <!-- Edit button removed -->
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="premium-card p-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-hashtag text-white text-xl"></i>
                </div>
                <div>
                    <div class="text-sm text-slate-500 font-medium">System ID</div>
                    <div class="text-2xl font-bold text-slate-800 mt-1">{{ $employee->employee_id }}</div>
                </div>
            </div>
        </div>
        <div class="premium-card p-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-building text-white text-xl"></i>
                </div>
                <div>
                    <div class="text-sm text-slate-500 font-medium">Department</div>
                    <div class="text-2xl font-bold text-slate-800 mt-1">{{ $employee->department->department_name ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
        <div class="premium-card p-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-id-badge text-white text-xl"></i>
                </div>
                <div>
                    <div class="text-sm text-slate-500 font-medium">IQC ID</div>
                    <div class="text-2xl font-bold text-slate-800 mt-1">{{ $employee->employee_no }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="premium-card overflow-hidden">
        <div class="flex border-b border-slate-200 px-6">
            <button class="tab-btn active px-6 py-4 font-semibold text-sm transition-all border-b-2 border-transparent" data-tab="details">
                <i class="fa-solid fa-user mr-2"></i>Details
            </button>
            <button class="tab-btn px-6 py-4 font-semibold text-sm transition-all border-b-2 border-transparent" data-tab="credentials">
                <i class="fa-solid fa-id-card mr-2"></i>Credentials
            </button>
            <button class="tab-btn px-6 py-4 font-semibold text-sm transition-all border-b-2 border-transparent" data-tab="leaves">
                <i class="fa-solid fa-calendar-days mr-2"></i>Leaves
            </button>
            <button class="tab-btn px-6 py-4 font-semibold text-sm transition-all border-b-2 border-transparent" data-tab="attendance">
                <i class="fa-solid fa-clock mr-2"></i>Attendance
            </button>
        </div>

        <div class="p-6">
            
            <!-- Details Tab -->
            <div class="tab-content active" id="details">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="info-row">
                        <span class="label">Full Name:</span>
                        <span class="value">{{ $employee->first_name }} {{ $employee->second_name }} {{ $employee->third_name }} {{ $employee->last_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Email:</span>
                        <span class="value">{{ $employee->employee_email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Employee Code:</span>
                        <span class="value">{{ $employee->employee_code }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Employee Type:</span>
                        <span class="value">{{ ucfirst($employee->employee_type) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Date of Birth:</span>
                        <span class="value">{{ $employee->employee_dob ? \Carbon\Carbon::parse($employee->employee_dob)->format('M d, Y') : 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Join Date:</span>
                        <span class="value">{{ $employee->employee_join_date ? \Carbon\Carbon::parse($employee->employee_join_date)->format('M d, Y') : 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Department:</span>
                        <span class="value">{{ $employee->department->department_name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Designation:</span>
                        <span class="value">{{ $employee->designation->designation_name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Leaves Balance:</span>
                        <span class="value">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-green-50 text-green-700 text-sm font-semibold">
                                <i class="fa-solid fa-calendar-check text-xs"></i>
                                {{ $employee->leaves_open_balance }} days
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="label">Company:</span>
                        <span class="value">{{ $employee->company_name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Credentials Tab -->
            <div class="tab-content" id="credentials">
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-id-card text-2xl text-slate-400"></i>
                    </div>
                    <p class="text-slate-500 font-medium">Credentials information (Passport, Visa, EID, etc.) will be displayed here</p>
                </div>
            </div>

            <!-- Leaves Tab -->
            <div class="tab-content" id="leaves">
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-calendar-days text-2xl text-slate-400"></i>
                    </div>
                    <p class="text-slate-500 font-medium">Leave history will be displayed here</p>
                </div>
            </div>

            <!-- Attendance Tab -->
            <div class="tab-content" id="attendance">
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-clock text-2xl text-slate-400"></i>
                    </div>
                    <p class="text-slate-500 font-medium">Attendance records will be displayed here</p>
                </div>
            </div>

        </div>
    </div>

</div>

<style>
    .tab-btn {
        color: #64748b;
    }
    
    .tab-btn:hover {
        color: #4f46e5;
    }
    
    .tab-btn.active {
        color: #4f46e5;
        border-bottom-color: #4f46e5;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .info-row {
        padding: 1.25rem;
        background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(248,250,252,0.9));
        border-radius: 12px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        transition: all 0.2s;
    }
    
    .info-row:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .info-row .label {
        display: block;
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .info-row .value {
        display: block;
        color: #1e293b;
        font-weight: 600;
        font-size: 0.95rem;
    }
</style>

<script>
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Remove active from all
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            // Add active to clicked
            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
    
    function openModal(id) {
        alert('Edit functionality will be implemented');
    }
</script>
@endsection
