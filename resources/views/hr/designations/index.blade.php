@extends('layouts.app')

@section('title', 'Designations')
@section('subtitle', 'Manage job titles and hierarchy')

@section('content')
    <div class="space-y-6 animate-fade-in-up">
        
        <!-- Structure Navigation -->
        @include('hr.partials.structure_nav')

        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h1 class="text-2xl font-bold font-display text-premium">Job Designations</h1>
            <a href="{{ route('hr.designations.create') }}" class="premium-button px-6 py-2">
                <i class="fa-solid fa-plus"></i>
                <span class="ml-2">Add New Designation</span>
            </a>
        </div>

        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-left">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Designation Name</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Options</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($designations as $designation)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ $designation->designation_code }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-slate-700">{{ $designation->designation_name }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $designation->department->department_name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('hr.designations.edit', $designation->designation_id) }}" class="text-slate-400 hover:text-indigo-600 transition-colors">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-briefcase text-4xl mb-3 opacity-20"></i>
                                    <p>No designations found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $designations->links() }}
            </div>
        </div>
    </div>
@endsection
