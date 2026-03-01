@extends('layouts.app')

@section('title', 'Create Strategic Plan')
@section('subtitle', 'Define a new strategic plan')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up">

        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('emp.ext.strategies.index') }}"
                class="text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span class="font-bold text-sm uppercase tracking-wider">Back to List</span>
            </a>
        </div>

        <div class="premium-card p-10">
            <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-50">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center"
                     style="background: linear-gradient(135deg, rgba(0,79,104,0.1) 0%, rgba(0,106,138,0.15) 100%);">
                    <i class="fa-solid fa-chess-knight text-2xl" style="color: #004F68;"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">New Strategic Plan</h2>
                    <p class="text-sm text-slate-500">Define the vision, mission, and period</p>
                </div>
            </div>

            <form action="{{ route('emp.ext.strategies.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Strategic Plan Title</label>
                        <input type="text" name="plan_title" class="premium-input w-full" placeholder="e.g. 2026–2030 Institutional Strategy" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">From Year</label>
                        <input type="number" name="plan_from" id="plan_from" class="premium-input w-full"
                               placeholder="e.g. 2026" min="2000" max="2100" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">To Year</label>
                        <input type="number" name="plan_to" id="plan_to" class="premium-input w-full"
                               placeholder="e.g. 2030" min="2000" max="2100" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Period</label>
                        <input type="text" name="plan_period" id="plan_period"
                            class="premium-input w-full bg-slate-50 text-slate-500" readonly placeholder="Auto-calculated">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Organizational Level</label>
                        <div class="relative">
                            <select name="plan_level" class="premium-input w-full appearance-none" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Vision</label>
                        <textarea name="plan_vision" rows="4" class="premium-input w-full"
                            placeholder="Enter the vision statement..."></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Mission</label>
                        <textarea name="plan_mission" rows="4" class="premium-input w-full"
                            placeholder="Enter the mission statement..."></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Values</label>
                        <textarea name="plan_values" rows="4" class="premium-input w-full"
                            placeholder="Enter core values..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="submit" class="premium-button px-10 py-3">
                        <span>Create Plan</span>
                        <i class="fa-solid fa-check"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calculatePeriod() {
            const from = parseInt(document.getElementById('plan_from').value);
            const to   = parseInt(document.getElementById('plan_to').value);
            if (from && to && to > from) {
                const years = to - from;
                document.getElementById('plan_period').value = years + (years === 1 ? ' year' : ' years');
            } else {
                document.getElementById('plan_period').value = '';
            }
        }
        document.getElementById('plan_from').addEventListener('input', calculatePeriod);
        document.getElementById('plan_to').addEventListener('input', calculatePeriod);
    </script>
@endsection
