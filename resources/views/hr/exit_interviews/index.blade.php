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

        <!-- Filters -->
        <div class="premium-card p-4 animate-fade-in-up">
            <div class="flex flex-col gap-4">
                <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <select id="employee-filter" class="premium-input w-full pl-11 py-2.5 text-sm appearance-none">
                            <option value="">All Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" id="search-filter" placeholder="Ref No..." class="premium-input w-full pl-11 py-2.5 text-sm">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-building absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <select id="department-filter" class="premium-input w-full pl-11 py-2.5 text-sm appearance-none">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="date" id="start-date-filter" class="premium-input w-full pl-11 py-2.5 text-sm" placeholder="From Date">
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="date" id="end-date-filter" class="premium-input w-full pl-11 py-2.5 text-sm" placeholder="To Date">
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button onclick="applyFilters()" class="px-6 py-2.5 bg-slate-800 text-white font-bold rounded-xl shadow-lg hover:bg-slate-900 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-search text-xs"></i>
                        <span>Search</span>
                    </button>
                    <button onclick="resetFilters()" class="px-6 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                        <span>Reset</span>
                    </button>
                </div>
            </div>
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
                            <th class="text-center">Action</th>
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
                                        {{ $iv->employee->department->department_name ?? ($iv->department->department_name ?? '-') }}
                                    </span>
                                </td>
                                <td><span class="text-sm text-slate-600 truncate max-w-xs block"
                                         title="{{ $iv->interview_remarks }}">{{ $iv->interview_remarks }}</span></td>
                                <td class="text-center">
                                    <button onclick="viewInterviewDetails({{ $iv->interview_id }})"
                                        class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md mx-auto">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
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
            getAdditionalParams: () => ({
                employee_id: document.getElementById('employee-filter').value,
                search: document.getElementById('search-filter').value,
                department_id: document.getElementById('department-filter').value,
                start_date: document.getElementById('start-date-filter').value,
                end_date: document.getElementById('end-date-filter').value
            }),
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
                                    <p class="text-slate-500 font-medium">No exit interviews found matching filters</p>
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
                    const deptName = iv.department ? iv.department.department_name : ((iv.employee && iv.employee.department) ? iv.employee.department.department_name : '-');

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
                            <td class="text-center">
                                <button onclick="viewInterviewDetails(${iv.interview_id})"
                                    class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md mx-auto">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
                container.innerHTML = html;
            }
        });

        async function viewInterviewDetails(id) {
            try {
                const response = await fetch("{{ url('hr/exit-interviews') }}/" + id);
                const result = await response.json();
                
                if (result.success) {
                    const iv = result.data;
                    document.getElementById('detail_emp_name').textContent = (iv.employee ? `${iv.employee.first_name} ${iv.employee.last_name || ''}` : 'Unknown');
                    document.getElementById('detail_date').textContent = iv.interview_date;
                    document.getElementById('detail_dept').textContent = iv.department ? iv.department.department_name : (iv.employee ? (iv.employee.department ? iv.employee.department.department_name : '-') : '-');
                    document.getElementById('detail_remarks').textContent = iv.interview_remarks || 'No overall remarks provided.';

                    let answersHtml = '';
                    if (iv.answers && iv.answers.length > 0) {
                        iv.answers.forEach(ans => {
                            answersHtml += `
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">${ans.question ? ans.question.question_text : 'Question'}</h4>
                                    <p class="text-sm text-slate-700 leading-relaxed">${ans.answer_text || 'No answer provided.'}</p>
                                </div>
                            `;
                        });
                    } else {
                        answersHtml = '<p class="text-slate-500 italic text-center py-4">No questionnaire responses recorded.</p>';
                    }
                    document.getElementById('detail_answers').innerHTML = answersHtml;
                    
                    openModal('detailExitModal');
                }
            } catch (error) {
                console.error('Error fetching interview details:', error);
                alert('Could not load interview details.');
            }
        }

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

        function applyFilters() {
            window.ajaxPagination.loadPage(1);
        }

        function resetFilters() {
            window.location.href = "{{ route('hr.exit_interviews.index') }}";
        }
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
    </div>

    <!-- Detail Modal -->
    <div class="modal" id="detailExitModal">
        <div class="modal-backdrop" onclick="closeModal('detailExitModal')"></div>
        <div class="modal-content max-w-2xl p-6 max-h-[85vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-display font-bold text-premium">Interview Details</h2>
                <button onclick="closeModal('detailExitModal')"
                    class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <div class="space-y-6">
                <!-- Info Grid -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Employee</span>
                        <span id="detail_emp_name" class="text-sm font-semibold text-slate-800">-</span>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Date</span>
                        <span id="detail_date" class="text-sm font-semibold text-slate-800">-</span>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Department</span>
                        <span id="detail_dept" class="text-sm font-semibold text-slate-800">-</span>
                    </div>
                </div>

                <!-- Questionnaire -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 border-b pb-2">Questionnaire Responses</h3>
                    <div id="detail_answers" class="space-y-3">
                        <!-- Dynamic -->
                    </div>
                </div>

                <!-- Overall Remarks -->
                <div class="p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                    <h3 class="text-xs font-bold text-indigo-400 uppercase mb-2">Overall Remarks</h3>
                    <p id="detail_remarks" class="text-sm text-slate-700 leading-relaxed italic"></p>
                </div>
            </div>

            <div class="flex justify-end mt-8">
                <button onclick="closeModal('detailExitModal')"
                    class="px-8 py-3 bg-slate-800 text-white font-bold rounded-xl shadow-lg hover:bg-slate-900 transition-all">
                    Close
                </button>
            </div>
        </div>
    </div>
@endsection