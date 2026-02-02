@extends('layouts.app')

@section('title', 'Create Operational Project')
@section('subtitle', 'Define a new project under the strategic plan')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up">

        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('emp.ext.strategies.projects.index') }}"
                class="text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span class="font-bold text-sm uppercase tracking-wider">Back to List</span>
            </a>
        </div>

        <div class="premium-card p-10">
            <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-50">
                <div class="w-14 h-14 rounded-2xl bg-brand-light/20 flex items-center justify-center text-brand-dark">
                    <i class="fa-solid fa-briefcase text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">New Operational Project</h2>
                    <p class="text-sm text-slate-500">Enter project details and alignment</p>
                </div>
            </div>

            <form action="{{ route('emp.ext.strategies.projects.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Project
                            Code</label>
                        <input type="text" name="project_code" class="premium-input w-full" placeholder="e.g. PRJ-2026-01"
                            required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Strategic
                            Plan</label>
                        <div class="relative">
                            <select name="plan_id" class="premium-input w-full appearance-none" required>
                                <option value="">Select Strategic Plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->plan_id }}">{{ $plan->plan_title }}</option>
                                @endforeach
                            </select>
                            <i
                                class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Project
                            Name</label>
                        <input type="text" name="project_name" class="premium-input w-full"
                            placeholder="e.g. IT Infrastructure Upgrade" required>
                    </div>

                    <div class="md:col-span-2">
                        <label
                            class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description</label>
                        <textarea name="project_description" rows="3" class="premium-input w-full"
                            placeholder="Describe the project scope..."></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">From
                            Date</label>
                        <input type="date" name="project_start_date" id="project_start_date" class="premium-input w-full"
                            required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">To Date</label>
                        <input type="date" name="project_end_date" id="project_end_date" class="premium-input w-full"
                            required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Period</label>
                        <input type="text" name="project_period" id="project_period"
                            class="premium-input w-full bg-slate-50 text-slate-500" readonly>
                    </div>
                    <div class="hidden md:block"></div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Project
                            Analysis</label>
                        <textarea name="project_analysis" rows="3" class="premium-input w-full"
                            placeholder="Analysis of the current situation..."></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label
                            class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Recommendations</label>
                        <textarea name="project_recommendations" rows="3" class="premium-input w-full"
                            placeholder="Recommendations and next steps..."></textarea>
                    </div>

                </div>

                <div class="flex justify-end pt-6">
                    <button type="submit" class="premium-button px-10 py-3">
                        <span>Save Project</span>
                        <i class="fa-solid fa-check"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calculatePeriod() {
            const startStr = document.getElementById('project_start_date').value;
            const endStr = document.getElementById('project_end_date').value;

            if (startStr && endStr) {
                const start = new Date(startStr);
                const end = new Date(endStr);

                if (start > end) return; // Invalid range

                let years = end.getFullYear() - start.getFullYear();
                let months = end.getMonth() - start.getMonth();
                let days = end.getDate() - start.getDate();

                if (days < 0) {
                    months--;
                    const prevMonthDate = new Date(end.getFullYear(), end.getMonth(), 0);
                    days += prevMonthDate.getDate();
                }

                if (months < 0) {
                    years--;
                    months += 12;
                }

                let result = [];
                if (years > 0) result.push(years + (years === 1 ? ' year' : ' years'));
                if (months > 0) result.push(months + (months === 1 ? ' month' : ' months'));
                if (days > 0) result.push(days + (days === 1 ? ' day' : ' days'));

                document.getElementById('project_period').value = result.join(', ') || '1 day';
            }
        }

        document.getElementById('project_start_date').addEventListener('change', calculatePeriod);
        document.getElementById('project_end_date').addEventListener('change', calculatePeriod);
    </script>
@endsection
