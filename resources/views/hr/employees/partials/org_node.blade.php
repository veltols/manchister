<div class="org-node mb-10 last:mb-0">
    <!-- Department Node -->
    <div class="flex items-center gap-4 mb-4">
        <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-200">
            <i class="fa-solid fa-building text-lg"></i>
        </div>
        <div>
            <h4 class="text-lg font-display font-bold text-premium leading-tight">{{ $dept->department_name }}</h4>
            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Department</p>
        </div>
    </div>

    <!-- Managers & Employees -->
    <div class="ml-6 pl-10 border-l-2 border-slate-100 space-y-4">
        <!-- Line Manager -->
        @if($dept->lineManager)
            <div class="flex items-center gap-3 p-3 rounded-2xl {{ $dept->line_manager_id == $targetId ? 'bg-indigo-50 border-2 border-indigo-100 shadow-sm' : 'bg-slate-50' }} group hover:bg-white hover:shadow-md transition-all">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center shadow-md">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div>
                    <h5 class="text-sm font-bold text-premium group-hover:text-indigo-600">{{ $dept->lineManager->full_name }}</h5>
                    <p class="text-[10px] text-slate-400 font-bold uppercase">HOD / Line Manager</p>
                </div>
                @if($dept->line_manager_id == $targetId)
                    <span class="ml-auto px-2 py-0.5 rounded-full bg-indigo-600 text-white text-[9px] font-bold">CURRENT</span>
                @endif
            </div>
        @endif

        <!-- Other Employees (Exclude manager if already listed) -->
        @foreach($dept->employees as $emp)
            @if(!$dept->lineManager || $emp->employee_id != $dept->line_manager_id)
                <div class="flex items-center gap-3 p-2.5 rounded-xl {{ $emp->employee_id == $targetId ? 'bg-indigo-50 border border-indigo-100 shadow-sm' : 'hover:bg-slate-50' }} transition-all group">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center group-hover:bg-white shadow-sm transition-colors">
                        <i class="fa-solid fa-user text-xs"></i>
                    </div>
                    <div>
                        <h6 class="text-xs font-semibold text-slate-700">{{ $emp->full_name }}</h6>
                        <p class="text-[10px] text-slate-400">{{ $emp->designation->designation_name ?? 'Employee' }}</p>
                    </div>
                    @if($emp->employee_id == $targetId)
                        <span class="ml-auto px-2 py-0.5 rounded-full bg-indigo-600 text-white text-[9px] font-bold uppercase">You</span>
                    @endif
                </div>
            @endif
        @endforeach

        <!-- Children Departments -->
        @if($dept->children && $dept->children->count() > 0)
            <div class="mt-6 pt-2 space-y-8">
                @foreach($dept->children as $child)
                    @include('hr.employees.partials.org_node', ['dept' => $child, 'targetId' => $targetId])
                @endforeach
            </div>
        @endif
    </div>
</div>
