@extends('layouts.app')

@section('title', 'Employees')
@section('subtitle', 'Manage your workforce')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-premium">All Employees</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $employees->total() }} total employees</p>
        </div>
        <a href="{{ route('hr.employees.create') }}" class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
            <i class="fa-solid fa-plus"></i>
            <span>Add Employee</span>
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="premium-card p-6">
        <div class="flex flex-wrap gap-4 items-center">
            <div class="flex-1 min-w-[300px]">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" placeholder="Search employees..." class="premium-input pl-11 pr-4 py-2.5 w-full text-sm">
                </div>
            </div>
            <select class="premium-input px-4 py-2.5 text-sm min-w-[150px]">
                <option>All Departments</option>
                <option>Engineering</option>
                <option>HR</option>
                <option>Sales</option>
            </select>
            <select class="premium-input px-4 py-2.5 text-sm min-w-[150px]">
                <option>All Status</option>
                <option>Active</option>
                <option>Inactive</option>
            </select>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">IQC ID</th>
                        <th class="text-left">Employee</th>
                        <th class="text-left">Email</th>
                        <th class="text-left">Department</th>
                        <th class="text-left">Designation</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr>
                        <td>
                            <span class="font-mono text-sm font-semibold text-slate-600">{{ $employee->employee_no }}</span>
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold shadow-md">
                                    {{ strtoupper(substr($employee->first_name, 0, 1)) }}{{ strtoupper(substr($employee->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-slate-800">{{ $employee->first_name }} {{ $employee->last_name }}</span>
                                        @if($employee->is_new == 1)
                                            <span class="px-2 py-0.5 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 text-white text-xs font-bold">NEW</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-sm text-slate-600">{{ $employee->employee_email }}</span>
                        </td>
                        <td>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-800 text-sm font-medium">
                                <i class="fa-solid fa-building text-xs"></i>
                                {{ $employee->department->department_name ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-sm text-slate-600">{{ $employee->designation->designation_name ?? '-' }}</span>
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('hr.employees.show', $employee->employee_id) }}" 
                                   class="w-9 h-9 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                   title="View Details">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </a>
                                <button class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
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
                                    <i class="fa-solid fa-users text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 font-medium">No employees found</p>
                                <a href="{{ route('hr.employees.create') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm">
                                    Add your first employee
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($employees->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $employees->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

</div>
@endsection
