@extends('layouts.app')

@section('title', 'My Calendar')
@section('subtitle', 'Manage your schedule and task deadlines')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <!-- Calendar Controls -->
        <div
            class="flex flex-col md:flex-row items-center justify-between gap-6 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center gap-4">
                <h2 class="text-3xl font-display font-bold text-premium">{{ $carbonDate->format('F Y') }}</h2>
                <div class="flex bg-slate-100 p-1 rounded-xl">
                    <a href="?date={{ $carbonDate->copy()->subMonth()->format('Y-m-d') }}&view={{ $view }}"
                        class="w-10 h-10 flex items-center justify-center text-slate-500 hover:text-brand-dark transition-colors">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                    <a href="?date={{ now()->format('Y-m-d') }}&view={{ $view }}"
                        class="px-4 flex items-center justify-center text-xs font-bold text-slate-700 hover:text-brand-dark transition-colors uppercase tracking-widest">
                        Today
                    </a>
                    <a href="?date={{ $carbonDate->copy()->addMonth()->format('Y-m-d') }}&view={{ $view }}"
                        class="w-10 h-10 flex items-center justify-center text-slate-500 hover:text-brand-dark transition-colors">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
            </div>

            <div class="flex bg-slate-100 p-1.5 rounded-2xl shadow-inner border border-slate-200/50">
                <a href="?view=day&date={{ $date }}"
                    class="px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest transition-all {{ $view == 'day' ? 'bg-brand-dark text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">Day</a>
                <a href="?view=week&date={{ $date }}"
                    class="px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest transition-all {{ $view == 'week' ? 'bg-brand-dark text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">Week</a>
                <a href="?view=month&date={{ $date }}"
                    class="px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest transition-all {{ $view == 'month' ? 'bg-brand-dark text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">Month</a>
            </div>
        </div>

        <!-- Month View -->
        @if($view == 'month')
            <div class="premium-card p-0 overflow-hidden shadow-2xl">
                <div class="grid grid-cols-7 border-b border-slate-50 bg-slate-50/50">
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div
                            class="py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest border-r border-slate-50 last:border-r-0">
                            {{ $day }}
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-7 grid-rows-5 h-[700px]">
                    @php
                        $startOfMonth = $carbonDate->copy()->startOfMonth();
                        $endOfMonth = $carbonDate->copy()->endOfMonth();
                        $calendarStartDate = $startOfMonth->copy()->startOfWeek();
                        $calendarEndDate = $endOfMonth->copy()->endOfWeek();
                        $day = $calendarStartDate->copy();
                    @endphp

                    @while($day <= $calendarEndDate)
                        <div
                            class="relative p-3 border-r border-b border-slate-50 group hover:bg-slate-50 transition-colors {{ $day->month != $carbonDate->month ? 'bg-slate-50/30' : '' }} {{ $day->isToday() ? 'bg-indigo-50/50' : '' }}">
                            <span
                                class="text-xs font-bold {{ $day->month != $carbonDate->month ? 'text-slate-300' : 'text-slate-600' }} {{ $day->isToday() ? 'text-indigo-600' : '' }} mb-2 block">
                                {{ $day->day }}
                            </span>

                            <div class="space-y-1.5 overflow-y-auto max-h-[100px] scrollbar-hide">
                                @foreach($tasks->filter(fn($t) => \Carbon\Carbon::parse($t->task_assigned_date)->isSameDay($day)) as $task)
                                    <div class="px-2 py-1 rounded-md text-[10px] font-bold truncate transition-all cursor-pointer shadow-sm hover:scale-105"
                                        style="background: #e0f2fe; color: #0369a1; border-left: 3px solid #0ea5e9;"
                                        title="{{ $task->task_title }}">
                                        {{ $task->task_title }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @php $day->addDay(); @endphp
                    @endwhile
                </div>
            </div>
        @else
            <div class="premium-card p-20 text-center">
                <i class="fa-solid fa-compass-drafting text-6xl text-slate-100 mb-6"></i>
                <h3 class="text-xl font-bold text-slate-400">{{ ucfirst($view) }} view is coming in next release.</h3>
                <p class="text-slate-300 text-sm mt-2">Please use the Month view for full scheduling capabilities.</p>
            </div>
        @endif

    </div>
@endsection
