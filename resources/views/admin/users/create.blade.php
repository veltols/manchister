@extends('layouts.app')

@section('title', 'Add User')
@section('subtitle', 'Create a new system user')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 text-sm font-semibold mb-2">
                <i class="fa-solid fa-arrow-left"></i>Back to Users
            </a>
            <h1 class="text-2xl font-display font-bold text-premium">Add New User</h1>
        </div>
    </div>

    <!-- Form Card -->
    <div class="premium-card p-8">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- ID & Basic Info -->
                <div class="col-span-full">
                    <h3 class="text-lg font-semibold text-premium mb-4 border-b border-slate-100 pb-2">User Identification</h3>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">IQC ID <span class="text-red-500">*</span></label>
                    <input type="text" name="employee_no" value="{{ old('employee_no') }}" class="premium-input w-full px-4 py-3 text-sm" placeholder="e.g. 10025" required>
                    @error('employee_no')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="hidden md:block"></div> <!-- Spacer -->

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" class="premium-input w-full px-4 py-3 text-sm" required>
                    @error('first_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="premium-input w-full px-4 py-3 text-sm" required>
                     @error('last_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Access Info -->
                <div class="col-span-full mt-4">
                    <h3 class="text-lg font-semibold text-premium mb-4 border-b border-slate-100 pb-2">Access & Security</h3>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Department <span class="text-red-500">*</span></label>
                    <select name="department_id" class="premium-input w-full px-4 py-3 text-sm" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->department_id }}" {{ old('department_id') == $dept->department_id ? 'selected' : '' }}>
                                {{ $dept->department_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Login Identifier (Email or Name) <span class="text-red-500">*</span></label>
                    <input type="text" name="login_identifier" value="{{ old('login_identifier') }}" class="premium-input w-full px-4 py-3 text-sm" placeholder="e.g. name or email" required>
                    @error('login_identifier')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="text" name="password" class="premium-input w-full px-4 py-3 text-sm" placeholder="Enter password" required>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</a>
                <button type="submit" class="px-8 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                    Create User
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
