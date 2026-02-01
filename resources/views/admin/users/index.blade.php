@extends('layouts.app')

@section('title', 'Users Management')
@section('subtitle', 'Manage system users')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-display font-bold text-slate-800">Users List</h1>
            <p class="text-slate-500 mt-1">View and manage all system users</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-800 text-white px-5 py-2.5 rounded-xl font-medium transition-colors shadow-lg shadow-indigo-500/20">
                <i class="fa-solid fa-plus"></i>
                Add New User
            </a>
        </div>
    </div>

    <!-- Users Table -->
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full premium-table">
                <thead>
                    <tr>
                        <th class="text-left">IQC ID</th>
                        <th class="text-left">User</th>
                        <th class="text-left">Email</th>
                        <th class="text-left">Type</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="font-medium text-slate-700">{{ $user->employee_no ?? 'N/A' }}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-800">{{ $user->first_name }} {{ $user->last_name }}</div>
                                    <div class="text-xs text-slate-500">{{ $user->employee_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-slate-600">{{ $user->login_email }}</td>
                        <td>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                {{ strtoupper($user->user_type) }}
                            </span>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all" title="View Details">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-all" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <i class="fa-solid fa-users-slash text-4xl mb-3"></i>
                                <p>No users found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
