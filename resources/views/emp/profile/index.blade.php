@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="profile-container">

    <!-- Header -->
    <div class="glass-panel mb-4 p-4">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fa-solid fa-user-circle text-secondary"></i> My Profile
        </h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Profile Card -->
        <div class="glass-panel p-6 text-center">
            <div class="w-32 h-32 rounded-full bg-secondary text-white flex items-center justify-center text-4xl mx-auto mb-4 font-bold border-4 border-white shadow-lg">
                {{ substr($employee->first_name ?? 'U', 0, 1) }}
            </div>
            <h2 class="text-xl font-bold text-primary">{{ $employee->first_name ?? '' }} {{ $employee->last_name ?? '' }}</h2>
            <p class="text-gray-500 font-medium mb-4">{{ $employee->designation->designation_name ?? 'Employee' }}</p>
            
            <div class="flex justify-center gap-2">
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Active</span>
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">{{ $employee->department->department_name ?? 'General' }}</span>
            </div>
        </div>

        <!-- Details -->
        <div class="glass-panel p-6 md:col-span-2">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Personal Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-xs text-gray-400 uppercase font-semibold">Employee ID</label>
                    <p class="text-gray-800 font-medium">{{ $employee->employee_code ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-400 uppercase font-semibold">Email Address</label>
                    <p class="text-gray-800 font-medium">{{ $employee->email ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-400 uppercase font-semibold">Phone</label>
                    <p class="text-gray-800 font-medium">{{ $employee->phone ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-400 uppercase font-semibold">Join Date</label>
                    <p class="text-gray-800 font-medium">{{ $employee->join_date ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-400 uppercase font-semibold">Department</label>
                    <p class="text-gray-800 font-medium">{{ $employee->department->department_name ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-400 uppercase font-semibold">Line Manager</label>
                    <p class="text-gray-800 font-medium">To be implemented</p>
                </div>
            </div>

            <div class="mt-8">
                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-700">
                    <i class="fa-solid fa-info-circle mr-2"></i> To update your personal details, please contact HR.
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
