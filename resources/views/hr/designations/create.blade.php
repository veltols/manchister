@extends('layouts.app')

@section('title', 'New Designation')
@section('subtitle', 'Create a new job title')

@section('content')
    <div class="max-w-xl mx-auto space-y-8 animate-fade-in-up">

        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('hr.designations.index') }}"
                class="text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span class="font-bold text-sm uppercase tracking-wider">Back to List</span>
            </a>
        </div>

        <div class="premium-card p-10">
            <h2 class="text-2xl font-display font-bold text-premium mb-6">Details</h2>

            <form action="{{ route('hr.designations.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Designation
                        Code</label>
                    <input type="text" name="designation_code" class="premium-input w-full" placeholder="e.g. ENG-SENIOR"
                        required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Designation
                        Name</label>
                    <input type="text" name="designation_name" class="premium-input w-full"
                        placeholder="e.g. Senior Software Engineer" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Department</label>
                    <div class="relative">
                        <select name="department_id" class="premium-input w-full appearance-none" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
                            @endforeach
                        </select>
                        <i
                            class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="premium-button w-full py-3">
                        Create Designation
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
