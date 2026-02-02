@extends('layouts.app')

@section('title', 'User Details')
@section('subtitle', 'View User Information')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">

        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-4">
                <div
                    class="w-16 h-16 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-2xl">
                    {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                </div>
                <div>
                    <h2 class="font-bold text-xl text-premium">{{ $user->first_name }} {{ $user->last_name }}</h2>
                    <p class="text-slate-500 text-sm">{{ $user->employee_no }} | {{ $user->department_name }}</p>
                </div>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800"><i
                    class="fa-solid fa-arrow-left"></i> Back to List</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Login Info -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-bold text-premium mb-4 uppercase text-xs tracking-widest border-b pb-2">Login Details</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500">Login Email</label>
                        <p class="font-medium text-slate-800">{{ $user->user_email }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500">Role / User Type</label>
                        <span
                            class="inline-block px-2 py-1 bg-slate-100 rounded text-xs font-bold text-slate-600 uppercase">{{ $user->user_type }}</span>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500">Status</label>
                        <span
                            class="inline-block px-2 py-1 rounded text-xs font-bold {{ $user->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                            {{ $user->is_active ? 'ACTIVE' : 'INACTIVE' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Employee Info -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-bold text-premium mb-4 uppercase text-xs tracking-widest border-b pb-2">Employee Details
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500">Full Name</label>
                        <p class="font-medium text-slate-800">{{ $user->first_name }} {{ $user->last_name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500">Email (Contact)</label>
                        <p class="font-medium text-slate-800">{{ $user->employee_email }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500">Department</label>
                        <p class="font-medium text-slate-800">{{ $user->department_name }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
