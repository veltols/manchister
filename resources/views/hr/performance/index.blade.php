@extends('layouts.app')

@section('title', 'Performance')
@section('subtitle', 'Performance management and KPIs')

@section('content')
    <div class="space-y-6">
        @include('hr.partials.requests_nav')

        <!-- Header with Action Button -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Performance & KPIs</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $records->total() }} total records</p>
            </div>
            <button onclick="openModal('addPerfModal')"
                class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                <i class="fa-solid fa-plus"></i>
                <span>New Record</span>
            </button>
        </div>

        <!-- Filter -->
        <div>
            <form action="{{ route('hr.performance.index') }}" method="GET">
                <select name="employee_id" onchange="this.form.submit()" class="premium-input px-4 py-3 text-sm">
                    <option value="">Filter by Employee</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->employee_id }}" {{ request('employee_id') == $emp->employee_id ? 'selected' : '' }}>
                            {{ $emp->first_name }} {{ $emp->last_name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($records as $rec)
                <div class="premium-card p-6 relative group">
                    <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick="editPerformance({{ $rec }})"
                            class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 flex items-center justify-center text-blue-600 transition-colors">
                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                        </button>
                    </div>

                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-slate-100">
                        <div
                            class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold shadow-md">
                            {{ substr($rec->employee->first_name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-premium">{{ $rec->employee->first_name ?? 'Unknown' }}
                                {{ $rec->employee->last_name ?? '' }}</h3>
                            <span class="text-xs text-slate-500">{{ $rec->added_date }}</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Objectives</span>
                            <p class="text-sm text-slate-700 mt-1">{{ $rec->performance_object }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">KPIs</span>
                            <p class="text-sm text-slate-700 mt-1">{{ $rec->performance_kpi }}</p>
                        </div>
                        @if($rec->performance_remark)
                            <div class="bg-blue-50 p-3 rounded-lg text-xs text-blue-700 italic border border-blue-100">
                                "{{ $rec->performance_remark }}"
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full premium-card p-12">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                            <i class="fa-solid fa-chart-simple text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500 font-medium">No performance records found</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($records->hasPages())
            <div class="flex justify-center">
                {{ $records->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>

    <!-- Create/Edit Modal -->
    <div class="modal" id="addPerfModal">
        <div class="modal-backdrop" onclick="closeModal('addPerfModal')"></div>
        <div class="modal-content max-w-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-display font-bold text-premium" id="modalTitle">New Performance Record</h2>
                <button onclick="closeModal('addPerfModal')"
                    class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form id="perfForm" action="{{ route('hr.performance.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div id="empSelectDiv">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Employee</label>
                        <select name="employee_id" class="premium-input w-full px-4 py-3 text-sm" required>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Objectives</label>
                        <textarea name="performance_object" id="p_object" rows="3"
                            class="premium-input w-full px-4 py-3 text-sm" required></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">KPIs</label>
                        <textarea name="performance_kpi" id="p_kpi" rows="3" class="premium-input w-full px-4 py-3 text-sm"
                            required></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Remarks</label>
                        <textarea name="performance_remark" id="p_remark" rows="2"
                            class="premium-input w-full px-4 py-3 text-sm"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('addPerfModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                    <button type="submit"
                        class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).style.display = 'block';
            document.getElementById('perfForm').action = "{{ route('hr.performance.store') }}";
            document.getElementById('modalTitle').innerText = "New Performance Record";
            document.getElementById('empSelectDiv').style.display = 'block';
            document.getElementById('perfForm').reset();
        }

        function closeModal(id) { document.getElementById(id).style.display = 'none'; }

        function editPerformance(data) {
            openModal('addPerfModal');
            document.getElementById('modalTitle').innerText = "Edit Performance Record";
            document.getElementById('perfForm').action = "/hr/performance/" + data.performance_id + "/update";

            document.getElementById('p_object').value = data.performance_object;
            document.getElementById('p_kpi').value = data.performance_kpi;
            document.getElementById('p_remark').value = data.performance_remark;

            document.getElementById('empSelectDiv').style.display = 'none';
        }
    </script>

@endsection