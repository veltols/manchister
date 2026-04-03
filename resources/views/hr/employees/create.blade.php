@extends('layouts.app')

@section('title', 'Add Employee')
@section('subtitle', 'Create a new employee record')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('hr.employees.index') }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 text-sm font-semibold mb-2">
                <i class="fa-solid fa-arrow-left"></i>Back to List
            </a>
            <h1 class="text-2xl font-display font-bold text-premium">Add New Employee</h1>
        </div>
    </div>

    <!-- Form Card -->
    <div class="premium-card p-8">
        <form action="{{ route('hr.employees.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Info -->
                <div class="col-span-full">
                    <h3 class="text-lg font-semibold text-premium mb-4 border-b border-slate-100 pb-2">Personal Information</h3>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">First Name</label>
                    <input type="text" name="first_name" class="premium-input w-full px-4 py-3 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Last Name</label>
                    <input type="text" name="last_name" class="premium-input w-full px-4 py-3 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                    <input type="email" name="employee_email" class="premium-input w-full px-4 py-3 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Date of Birth</label>
                    <input type="date" name="employee_dob" class="premium-input w-full px-4 py-3 text-sm">
                </div>

                <!-- Professional Info -->
                <div class="col-span-full mt-4">
                    <h3 class="text-lg font-semibold text-premium mb-4 border-b border-slate-100 pb-2">Professional Details</h3>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Department</label>
                        <select name="department_id" class="premium-input w-full px-4 py-3 text-sm" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-3 p-3 rounded-xl bg-indigo-50 border border-indigo-100">
                        <input type="checkbox" name="is_line_manager" id="is_line_manager" value="1" class="w-5 h-5 rounded border-indigo-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="is_line_manager" class="text-sm font-bold text-indigo-900 cursor-pointer">
                             Set as Line Manager for this Department
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Designation</label>
                    <select name="designation_id" class="premium-input w-full px-4 py-3 text-sm" required>
                        <option value="">Select Designation</option>
                        @foreach($designations as $desg)
                            <option value="{{ $desg->designation_id }}">{{ $desg->designation_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Joining Date</label>
                    <input type="date" name="employee_join_date" class="premium-input w-full px-4 py-3 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Employee Type</label>
                    <select name="employee_type" class="premium-input w-full px-4 py-3 text-sm" required>
                        <option value="full_time">Full Time</option>
                        <option value="part_time">Part Time</option>
                        <option value="contract">Contract</option>
                        <option value="intern">Intern</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Portal Role (User Type) <span class="text-rose-500">*</span></label>
                    <select name="user_type" class="premium-input w-full px-4 py-3 text-sm" required>
                        <option value="emp">Employee (Standard)</option>
                        <option value="hr">HR Manager</option>
                        <option value="eqa">EQA Officer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Initial Password <span class="text-rose-500">*</span></label>
                    <input type="text" name="password" class="premium-input w-full px-4 py-3 text-sm" placeholder="e.g. Pass123!" required>
                    <p class="text-[10px] text-slate-400 mt-1">Min 8 chars, mixed case, numbers & symbols.</p>
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-slate-100">
                <a href="{{ route('hr.employees.index') }}" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</a>
                <button type="submit" class="px-8 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                    Create Employee
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
