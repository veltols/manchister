@extends('layouts.app')

@section('title', 'Calendar')
@section('subtitle', 'Schedule and Events')

@section('content')
    <div class="space-y-6 animate-fade-in-up">
        <div class="premium-card p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold font-display text-premium">My Calendar</h2>
                <div class="flex bg-slate-100 rounded-lg p-1">
                    <button
                        class="px-4 py-1 text-sm font-medium rounded-md bg-white shadow-sm text-slate-700">Month</button>
                    <button
                        class="px-4 py-1 text-sm font-medium rounded-md text-slate-500 hover:text-slate-700">Week</button>
                    <button
                        class="px-4 py-1 text-sm font-medium rounded-md text-slate-500 hover:text-slate-700">Day</button>
                </div>
            </div>

            <div
                class="h-[600px] border border-slate-100 rounded-xl bg-slate-50/50 flex items-center justify-center text-slate-400">
                <!-- Placeholder for actual Calendar Component (e.g. FullCalendar) -->
                <div class="text-center">
                    <i class="fa-solid fa-calendar-days text-4xl mb-3"></i>
                    <p>Calendar Component will be rendered here.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
