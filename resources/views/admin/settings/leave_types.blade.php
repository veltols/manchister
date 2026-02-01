@extends('layouts.app')

@section('title', 'Leave Types')
@section('subtitle', 'Manage leave types')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 text-sm font-semibold mb-2">
                <i class="fa-solid fa-arrow-left"></i>Back to Settings
            </a>
            <h1 class="text-2xl font-display font-bold text-slate-800">Leave Types</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Form -->
        <div class="premium-card p-6">
            <h3 class="text-lg font-display font-bold text-slate-800 mb-4">Add New Type</h3>
            <form action="{{ route('admin.settings.leave_types.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Type Name</label>
                        <input type="text" name="leave_type_name" class="premium-input w-full px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Days Per Year</label>
                        <input type="number" name="days_per_year" class="premium-input w-full px-4 py-3 text-sm" value="0">
                    </div>
                </div>
                <button type="submit" class="w-full mt-6 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-plus mr-2"></i>Add Type
                </button>
            </form>
        </div>

        <!-- List -->
        <div class="premium-card p-0 col-span-2 overflow-hidden">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Name</th>
                        <th class="text-center">Balance</th>
                        <th class="text-center">ID</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($types as $t)
                    <tr>
                        <td><span class="font-bold text-slate-800">{{ $t->leave_type_name }}</span></td>
                        <td class="text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-green-50 text-green-700 text-sm font-semibold">
                                <i class="fa-solid fa-calendar-check text-xs"></i>
                                {{ $t->days_per_year }} days
                            </span>
                        </td>
                        <td class="text-center"><span class="font-mono text-xs text-slate-400">#{{ $t->leave_type_id }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
