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
                class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                <i class="fa-solid fa-plus"></i>
                <span>Add New User</span>
            </button>
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
                            <th class="text-left">Department</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-50 border border-slate-100 text-slate-600 text-xs font-medium">
                                        {{ $user->department->department_name ?? 'Unassigned' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }} text-xs font-bold">
                                        <i class="fa-solid fa-circle text-[8px]"></i>
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.users.show', $user->employee_id) }}" 
                                           class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:bg-indigo-600 hover:text-white flex items-center justify-center transition-all"
                                           title="View Details">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <!-- Edit button placeholder -->
                                        <button class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:bg-amber-500 hover:text-white flex items-center justify-center transition-all">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-500">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
                <div class="p-4 border-t border-slate-100 flex justify-center">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- New User Modal -->
    <div id="newUserModal" class="modal">
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">IQC ID</label>
                            <input type="text" name="employee_no" required class="premium-input w-full px-4 py-3 text-sm" placeholder="e.g. 1045">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Department</label>
                            <select name="department_id" required class="premium-input w-full px-4 py-3 text-sm">
                                <option value="">Select Department...</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">First Name</label>
                            <input type="text" name="first_name" required class="premium-input w-full px-4 py-3 text-sm" placeholder="John">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Last Name</label>
                            <input type="text" name="last_name" required class="premium-input w-full px-4 py-3 text-sm" placeholder="Doe">
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-4 mt-2">
                        <h3 class="text-sm font-bold text-indigo-900 mb-4">Login Credentials</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Email / Login ID</label>
                                <input type="email" name="employee_email" required class="premium-input w-full px-4 py-3 text-sm" placeholder="john.doe@company.com">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                                <input type="password" name="password" required class="premium-input w-full px-4 py-3 text-sm" placeholder="••••••••">
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