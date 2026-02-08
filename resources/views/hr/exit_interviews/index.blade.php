@extends('layouts.app')

@section('title', 'Exit Interviews')
@section('subtitle', 'Employee exit interviews')

@section('content')
    <div class="space-y-6">
        @include('hr.partials.requests_nav')

        <!-- Header with Action Button -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Exit Interviews</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $interviews->total() }} total interviews</p>
            </div>
            <button onclick="openModal('addExitModal')"
                class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                <i class="fa-solid fa-plus"></i>
                <span>New Interview</span>
            </button>
        </div>

        <!-- Filter -->
        <div>
            <form action="{{ route('hr.exit_interviews.index') }}" method="GET">
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

        <!-- Table -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left">Ref</th>
                            <th class="text-left">Employee</th>
                            <th class="text-left">Date</th>
                            <th class="text-left">Department</th>
                            <th class="text-left">Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="exit-container">
                        @forelse($interviews as $iv)
                            <tr>
                                <td><span class="font-mono text-sm font-semibold text-slate-600">#{{ $iv->interview_id }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold shadow-md">
                                            {{ substr($iv->employee->first_name ?? 'U', 0, 1) }}
                                        </div>
                                        <span class="font-semibold text-slate-800">{{ $iv->employee->first_name ?? 'Unknown' }}
                                            {{ $iv->employee->last_name ?? '' }}</span>
                                    </div>
                                </td>
                                <td><span class="text-sm text-slate-600">{{ $iv->interview_date }}</span></td>
                                <td>
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-purple-50 text-purple-700 text-sm font-medium">
                                        <i class="fa-solid fa-building text-xs"></i>
                                        {{ $iv->employee->department->department_name ?? '-' }}
                                    </span>
                                </td>
                                <td><span class="text-sm text-slate-600 truncate max-w-xs block"
                                        title="{{ $iv->interview_remarks }}">{{ $iv->interview_remarks }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                            <i class="fa-solid fa-person-walking-arrow-right text-2xl text-slate-400"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">No exit interviews recorded</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- AJAX Pagination -->
            <div id="exit-pagination"></div>
        </div>

    </div>

    @push('scripts')
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('hr.exit_interviews.data') }}",
            containerSelector: '#exit-container',
            paginationSelector: '#exit-pagination',
            renderCallback: function(interviews) {
                const container = document.querySelector('#exit-container');
                if (interviews.length === 0) {
                    container.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                        <i class="fa-solid fa-person-walking-arrow-right text-2xl text-slate-400"></i>
                                    </div>
                                    <p class="text-slate-500 font-medium">No exit interviews recorded</p>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }

                let html = '';
                interviews.forEach(iv => {
                    const initials = (iv.employee ? iv.employee.first_name : 'U').charAt(0);
                    const fullName = iv.employee ? `${iv.employee.first_name} ${iv.employee.last_name || ''}` : 'Unknown';
                    const deptName = (iv.employee && iv.employee.department) ? iv.employee.department.department_name : '-';

                    html += `
                        <tr>
                            <td><span class="font-mono text-sm font-semibold text-slate-600">#${iv.interview_id}</span></td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold shadow-md">
                                        ${initials}
                                    </div>
                                    <span class="font-semibold text-slate-800">${fullName}</span>
                                </div>
                            </td>
                            <td><span class="text-sm text-slate-600">${iv.interview_date || '-'}</span></td>
                            <td>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-purple-50 text-purple-700 text-sm font-medium">
                                    <i class="fa-solid fa-building text-xs"></i>
                                    ${deptName}
                                </span>
                            </td>
                            <td><span class="text-sm text-slate-600 truncate max-w-xs block" title="${iv.interview_remarks || ''}">${iv.interview_remarks || ''}</span></td>
                        </tr>
                    `;
                });
                container.innerHTML = html;
            }
        });

        // Initial pagination setup
        @if($interviews->hasPages())
            window.ajaxPagination.renderPagination({
                current_page: {{ $interviews->currentPage() }},
                last_page: {{ $interviews->lastPage() }},
                from: {{ $interviews->firstItem() }},
                to: {{ $interviews->lastItem() }},
                total: {{ $interviews->total() }}
            });
        @endif
    </script>
    @endpush

    <!-- Create Modal -->
    <div class="modal" id="addExitModal">
        <div class="modal-backdrop" onclick="closeModal('addExitModal')"></div>
        <div class="modal-content max-w-3xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6 sticky top-0 bg-white pb-4 border-b border-slate-100">
                <h2 class="text-2xl font-display font-bold text-premium">New Exit Interview</h2>
                <button onclick="closeModal('addExitModal')"
                    class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('hr.exit_interviews.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Employee</label>
                        <select name="employee_id" class="premium-input w-full px-4 py-3 text-sm" required>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="border-t pt-4 mt-4">
                        <h3 class="font-bold text-slate-700 mb-4">Questionnaire</h3>
                        @foreach($questions as $q)
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">{{ $q->question_text }}</label>
                                <input type="hidden" name="question_ids[]" value="{{ $q->question_id }}">
                                <textarea name="answer_texts[]" rows="2"
                                    class="premium-input w-full px-4 py-3 text-sm"></textarea>
                            </div>
                        @endforeach
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Overall Remarks</label>
                        <textarea name="interview_remarks" rows="3"
                            class="premium-input w-full px-4 py-3 text-sm"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('addExitModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                    <button type="submit"
                        class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">Submit</button>
                </div>
            </form>
        </div>
    </div>

@endsection