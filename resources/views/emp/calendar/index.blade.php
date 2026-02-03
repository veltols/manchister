@extends('layouts.app')

@section('title', 'My Calendar')
@section('subtitle', 'Manage your schedule and task deadlines')

@section('content')
    <div class="space-y-6 animate-fade-in-up" x-data="{ 
        view: '{{ $view }}',
        date: '{{ $date }}',
        openTaskModal(day, hour) {
            document.getElementById('task_assigned_date').value = day;
            if (hour) {
                document.getElementById('start_time').value = hour + ':00:00';
            }
            openModal('addTaskModal');
        }
    }">

        <!-- Calendar Controls -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-6 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="flex flex-col">
                    <h2 class="text-3xl font-display font-bold text-premium">
                        @if($view == 'day')
                            {{ $carbonDate->format('M d, Y') }}
                        @elseif($view == 'week')
                            {{ $startDate->format('M d') }} - {{ $endDate->format('M d, Y') }}
                        @else
                            {{ $carbonDate->format('F Y') }}
                        @endif
                    </h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
                        @if($view == 'day') {{ $carbonDate->format('l') }} @else {{ $view }} View @endif
                    </p>
                </div>
                
                <div class="flex bg-slate-100 p-1 rounded-xl ml-4">
                    @php
                        $prevDate = $carbonDate->copy();
                        $nextDate = $carbonDate->copy();
                        if($view == 'day') { $prevDate->subDay(); $nextDate->addDay(); }
                        elseif($view == 'week') { $prevDate->subWeek(); $nextDate->addWeek(); }
                        else { $prevDate->subMonth(); $nextDate->addMonth(); }
                    @endphp
                    <a href="?date={{ $prevDate->format('Y-m-d') }}&view={{ $view }}"
                        class="w-10 h-10 flex items-center justify-center text-slate-500 hover:text-brand transition-colors">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                    <a href="?date={{ now()->format('Y-m-d') }}&view={{ $view }}"
                        class="px-4 flex items-center justify-center text-xs font-bold text-slate-700 hover:text-brand transition-colors uppercase tracking-widest border-x border-slate-200">
                        Today
                    </a>
                    <a href="?date={{ $nextDate->format('Y-m-d') }}&view={{ $view }}"
                        class="w-10 h-10 flex items-center justify-center text-slate-500 hover:text-brand transition-colors">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
            </div>

            <div class="flex bg-slate-100 p-1.5 rounded-2xl shadow-inner border border-slate-200/50">
                <a href="?view=day&date={{ $date }}"
                    class="px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest transition-all {{ $view == 'day' ? 'bg-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand' }}">Day</a>
                <a href="?view=week&date={{ $date }}"
                    class="px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest transition-all {{ $view == 'week' ? 'bg-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand' }}">Week</a>
                <a href="?view=month&date={{ $date }}"
                    class="px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest transition-all {{ $view == 'month' ? 'bg-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand' }}">Month</a>
            </div>
        </div>

        <!-- Content Area -->
        <div class="premium-card p-0 overflow-hidden shadow-2xl">
            @if($view == 'month')
                <!-- Month View Details -->
                <div class="grid grid-cols-7 border-b border-slate-50 bg-slate-50/50">
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                        <div class="py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest border-r border-slate-50 last:border-r-0">
                            {{ $dayName }}
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-7 grid-rows-5 h-[800px]">
                    @php
                        $day = $startDate->copy();
                    @endphp

                    @while($day <= $endDate)
                        <div @click="openTaskModal('{{ $day->format('Y-m-d') }}', null)"
                            class="relative p-3 border-r border-b border-slate-50 group hover:bg-slate-50 transition-colors cursor-pointer {{ $day->month != $carbonDate->month ? 'bg-slate-50/30' : '' }} {{ $day->isToday() ? 'bg-teal-50/50' : '' }}">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs font-bold {{ $day->month != $carbonDate->month ? 'text-slate-300' : 'text-slate-600' }} {{ $day->isToday() ? 'text-brand' : '' }}">
                                    {{ $day->day }}
                                </span>
                                @if($day->isToday())
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand shadow-[0_0_8px_rgba(0,79,104,0.5)] animate-pulse"></span>
                                @endif
                            </div>

                            <div class="space-y-1.5 overflow-y-auto max-h-[120px] scrollbar-hide">
                                @foreach($tasks->filter(fn($t) => \Carbon\Carbon::parse($t->task_assigned_date)->isSameDay($day)) as $task)
                                    <a href="{{ route('emp.tasks.show', $task->task_id) }}" 
                                        @click.stop
                                        class="px-2 py-1 rounded-md text-[9px] font-bold truncate block transition-all hover:scale-105 border-l-2"
                                        style="background: #{{ $task->status->status_color ?? 'e0f2fe' }}20; color: #{{ $task->status->status_color ?? '0369a1' }}; border-color: #{{ $task->status->status_color ?? '0ea5e9' }};"
                                        title="{{ $task->task_title }}">
                                        {{ $task->task_title }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @php $day->addDay(); @endphp
                    @endwhile
                </div>

            @elseif($view == 'week')
                <!-- Week View Details -->
                <div class="grid grid-cols-[100px_repeat(7,1fr)] border-b border-slate-50 bg-slate-50/50">
                    <div class="py-4 border-r border-slate-50"></div>
                    @php $day = $startDate->copy(); @endphp
                    @while($day <= $endDate)
                        <div class="py-4 text-center border-r border-slate-50 last:border-r-0 {{ $day->isToday() ? 'bg-teal-50/50' : '' }}">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $day->format('D') }}</p>
                            <p class="text-sm font-bold {{ $day->isToday() ? 'text-brand' : 'text-slate-700' }}">{{ $day->day }}</p>
                        </div>
                        @php $day->addDay(); @endphp
                    @endwhile
                </div>

                <div class="overflow-y-auto h-[700px] scrollbar-hide relative bg-white">
                    @for($hour = 6; $hour <= 20; $hour++)
                        @php $hourStr = str_pad($hour, 2, '0', STR_PAD_LEFT); @endphp
                        <div class="grid grid-cols-[100px_repeat(7,1fr)] border-b border-slate-100 group min-h-[80px]">
                            <div class="flex items-center justify-center text-[10px] font-bold text-slate-400 bg-slate-50/30 border-r border-slate-100 italic">
                                {{ $hourStr }}:00
                            </div>
                            @php $day = $startDate->copy(); @endphp
                            @while($day <= $endDate)
                                <div @click="openTaskModal('{{ $day->format('Y-m-d') }}', '{{ $hourStr }}')"
                                    class="relative border-r border-slate-100 last:border-r-0 hover:bg-slate-50/50 transition-colors cursor-pointer group/cell {{ $day->isToday() ? 'bg-teal-50/10' : '' }}">
                                    <!-- Add button on hover -->
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover/cell:opacity-100 transition-opacity">
                                        <div class="w-6 h-6 rounded-full bg-brand text-white flex items-center justify-center scale-75 group-hover/cell:scale-100 transition-transform shadow-lg">
                                            <i class="fa-solid fa-plus text-[10px]"></i>
                                        </div>
                                    </div>

                                    <!-- Tasks for this day and hour -->
                                    <div class="p-2 space-y-1 relative z-10">
                                        @foreach($tasks->filter(function($t) use ($day, $hour) {
                                            $taskDate = \Carbon\Carbon::parse($t->task_assigned_date);
                                            return $taskDate->isSameDay($day) && $taskDate->hour == $hour;
                                        }) as $task)
                                            <a href="{{ route('emp.tasks.show', $task->task_id) }}" 
                                                @click.stop
                                                class="px-2 py-1.5 rounded-lg text-[9px] font-bold block transition-all hover:scale-105 border-l-4 shadow-sm"
                                                style="background: white; color: #{{ $task->status->status_color ?? '0369a1' }}; border-color: #{{ $task->status->status_color ?? '0ea5e9' }};"
                                                title="{{ $task->task_title }}">
                                                {{ $task->task_title }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                @php $day->addDay(); @endphp
                            @endwhile
                        </div>
                    @endfor
                </div>

            @elseif($view == 'day')
                <!-- Day View Details -->
                <div class="flex flex-col h-[700px] overflow-hidden">
                    <div class="grid grid-cols-[120px_1fr] border-b border-slate-50 bg-slate-50/50">
                        <div class="py-4 border-r border-slate-50"></div>
                        <div class="py-4 px-8 {{ $carbonDate->isToday() ? 'bg-teal-50/50' : '' }}">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $carbonDate->format('l') }}</p>
                            <p class="text-xl font-bold {{ $carbonDate->isToday() ? 'text-brand' : 'text-slate-700' }}">
                                {{ $carbonDate->format('M d, Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto scrollbar-hide bg-white">
                        @for($hour = 6; $hour <= 20; $hour++)
                            @php $hourStr = str_pad($hour, 2, '0', STR_PAD_LEFT); @endphp
                            <div class="grid grid-cols-[120px_1fr] border-b border-slate-100 min-h-[100px] group">
                                <div class="flex items-center justify-center text-[11px] font-bold text-slate-400 bg-slate-50/30 border-r border-slate-100 italic">
                                    {{ $hourStr }}:00
                                </div>
                                <div @click="openTaskModal('{{ $carbonDate->format('Y-m-d') }}', '{{ $hourStr }}')"
                                    class="relative p-4 hover:bg-slate-50/50 transition-colors cursor-pointer group/day">
                                    
                                    <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-0 group-hover/day:opacity-100 transition-opacity">
                                        <div class="px-4 py-2 rounded-xl bg-brand text-white text-[10px] font-bold flex items-center gap-2 shadow-lg">
                                            <i class="fa-solid fa-plus"></i> New Task
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($tasks->filter(function($t) use ($carbonDate, $hour) {
                                            $taskDate = \Carbon\Carbon::parse($t->task_assigned_date);
                                            return $taskDate->isSameDay($carbonDate) && $taskDate->hour == $hour;
                                        }) as $task)
                                            <a href="{{ route('emp.tasks.show', $task->task_id) }}" 
                                                @click.stop
                                                class="flex items-center gap-3 p-3 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all border-l-4"
                                                style="border-left-color: #{{ $task->status->status_color ?? '0ea5e9' }}">
                                                <div class="w-2 h-2 rounded-full animate-pulse" style="background-color: #{{ $task->priority->priority_color ?? 'indigo' }}"></div>
                                                <div class="flex-1">
                                                    <p class="text-sm font-bold text-slate-800">{{ $task->task_title }}</p>
                                                    <p class="text-[10px] font-medium text-slate-400">{{ $task->status->status_name ?? 'In Progress' }}</p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            @endif
        </div>

    </div>

    <!-- Create Task Modal -->
    <div class="modal" id="addTaskModal">
        <div class="modal-backdrop" onclick="closeModal('addTaskModal')"></div>
        <div class="modal-content max-w-xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-display font-bold text-premium">Create New Task</h2>
                <button onclick="closeModal('addTaskModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('emp.tasks.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Assign To</label>
                        <select name="assigned_to" class="premium-input w-full px-4 py-3 text-sm">
                            <option value="{{ auth()->user()->employee ? auth()->user()->employee->employee_id : 0 }}">Me (Self)</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Priority</label>
                        <select name="priority_id" class="premium-input w-full px-4 py-3 text-sm" required>
                            @foreach($priorities as $p)
                                <option value="{{ $p->priority_id }}">{{ $p->priority_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Task Title</label>
                    <input type="text" name="task_title" class="premium-input w-full px-4 py-3 text-sm" required placeholder="What needs to be done?">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                    <textarea name="task_description" rows="3" class="premium-input w-full px-4 py-3 text-sm" placeholder="Additional details..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-6 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Start Schedule</label>
                        <input type="date" name="task_assigned_date" id="task_assigned_date" class="premium-input w-full text-sm" value="{{ date('Y-m-d') }}" required>
                        <select name="start_time" id="start_time" class="premium-input w-full text-sm">
                            <option value="">Start Time (Optional)</option>
                            @for($i=6; $i<=22; $i++)
                                @php $h = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                <option value="{{ $h }}:00:00">{{ $h }}:00</option>
                            @endfor
                        </select>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Due Deadline</label>
                        <input type="date" name="task_due_date" class="premium-input w-full text-sm" required value="{{ date('Y-m-d') }}">
                        <select name="end_time" class="premium-input w-full text-sm">
                            <option value="">End Time (Optional)</option>
                            @for($i=6; $i<=22; $i++)
                                @php $h = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                <option value="{{ $h }}:00:00" {{ $i == 14 ? 'selected' : '' }}>{{ $h }}:00</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('addTaskModal')" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-3 premium-button from-brand to-brand-light text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
