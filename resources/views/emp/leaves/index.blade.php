@extends('layouts.app')

@section('title', 'My Leaves')
@section('subtitle', 'Leave requests and balance')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-premium">My Leaves</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $leaves->total() }} total requests</p>
        </div>
        <button onclick="openModal('requestLeaveModal')" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
            <i class="fa-solid fa-plus"></i>
            <span>Request Leave</span>
        </button>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="premium-card p-6 bg-gradient-to-br from-indigo-500 to-indigo-700 text-white border-0 shadow-indigo-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-xl backdrop-blur-md">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
                <div>
                    <h3 class="text-indigo-100 text-[10px] font-bold uppercase tracking-widest">Total Balance</h3>
                    <div class="text-3xl font-black mt-0.5">{{ $leaveStats['total'] }} <span class="text-sm font-medium opacity-80 uppercase">Days</span></div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between text-xs font-semibold text-indigo-100">
                <span>Available for request</span>
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="premium-card p-6 bg-gradient-to-br from-emerald-500 to-emerald-700 text-white border-0 shadow-emerald-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-xl backdrop-blur-md">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <h3 class="text-emerald-100 text-[10px] font-bold uppercase tracking-widest">Availed Leaves</h3>
                    <div class="text-3xl font-black mt-0.5">{{ $leaveStats['availed'] }} <span class="text-sm font-medium opacity-80 uppercase">Days</span></div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between text-xs font-semibold text-emerald-100">
                <span>Total approved this year</span>
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

        <div class="premium-card p-6 bg-gradient-to-br from-amber-500 to-amber-700 text-white border-0 shadow-amber-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-xl backdrop-blur-md">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div>
                    <h3 class="text-amber-100 text-[10px] font-bold uppercase tracking-widest">Pending Requests</h3>
                    <div class="text-3xl font-black mt-0.5">{{ $leaveStats['pending'] }} <span class="text-sm font-medium opacity-80 uppercase">Days</span></div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between text-xs font-semibold text-amber-100">
                <span>Awaiting HR review</span>
                <i class="fa-solid fa-spinner"></i>
            </div>
        </div>
    </div>

    <!-- Filters bar -->
    <div class="premium-card p-5 bg-white shadow-sm border border-slate-100 rounded-2xl">
        <form id="leaves-filter-form" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Search Ref</label>
                <div class="relative">
                    <i class="fa-solid fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" placeholder="Reference #" class="premium-input w-full pl-11 pr-4 py-2.5 text-sm" value="{{ request('search') }}">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Leave Type</label>
                <select name="type_id" class="premium-input w-full px-4 py-2.5 text-sm">
                    <option value="">All Types</option>
                    @foreach($leaveTypes as $type)
                        <option value="{{ $type->leave_type_id }}" {{ request('type_id') == $type->leave_type_id ? 'selected' : '' }}>{{ $type->leave_type_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Status</label>
                <select name="status" class="premium-input w-full px-4 py-2.5 text-sm">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st->leave_status_id }}" {{ (request('status') ?? $statusId) == $st->leave_status_id ? 'selected' : '' }}>{{ $st->leave_status_name }}</option>
                    @endforeach
                </select>
            </div>
           
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-6 py-2.5 bg-gradient-brand text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Filter</span>
                </button>
                <a href="{{ route('emp.leaves.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition-all flex items-center justify-center">
                    <i class="fa-solid fa-redo text-xs"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Leaves Table -->
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Ref</th>
                        <th class="text-left">Type</th>
                        <th class="text-left">Inclusive Dates</th>
                        <th class="text-center">Days</th>
                        <th class="text-center">Status</th>
                        <th class="text-left">Submitted On</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody id="leaves-container">
                    @forelse($leaves as $leave)
                    <tr>
                        <td><span class="font-mono text-sm font-semibold text-slate-600">#{{ $leave->leave_id }}</span></td>
                        <td>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-purple-50 text-purple-700 text-sm font-medium">
                                <i class="fa-solid fa-tag text-xs"></i>
                                {{ $leave->type->leave_type_name ?? 'Unknown' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <span>{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</span>
                                <i class="fa-solid fa-arrow-right text-xs text-slate-400"></i>
                                <span>{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 text-white font-bold shadow-md" title="Working Days (Excl. Weekends)">
                                {{ $leave->total_days }}
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $statusConfig = match($leave->leave_status_id) {
                                    \App\Models\HrLeave::STATUS_PENDING => ['bg' => 'from-yellow-400 to-amber-500', 'text' => 'Pending HR', 'icon' => 'clock'],
                                    \App\Models\HrLeave::STATUS_PENDING_APPROVAL => ['bg' => 'from-blue-500 to-cyan-600', 'text' => 'Pending Manager', 'icon' => 'user-check'],
                                    \App\Models\HrLeave::STATUS_APPROVED => ['bg' => 'from-green-500 to-emerald-600', 'text' => 'Approved', 'icon' => 'check-double'],
                                    \App\Models\HrLeave::STATUS_REJECTED => ['bg' => 'from-red-500 to-rose-600', 'text' => 'Rejected', 'icon' => 'times-circle'],
                                    \App\Models\HrLeave::STATUS_ACTION_REQUIRED => ['bg' => 'from-purple-500 to-indigo-600', 'text' => 'Action Required', 'icon' => 'user-edit'],
                                    default => ['bg' => 'from-slate-400 to-slate-500', 'text' => 'Unknown', 'icon' => 'question']
                                };
                            @endphp
                            <div class="flex flex-col items-center gap-1.5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r {{ $statusConfig['bg'] }} text-white text-xs font-bold shadow-md whitespace-nowrap">
                                    <i class="fa-solid fa-{{ $statusConfig['icon'] }}"></i>
                                    {{ $statusConfig['text'] }}
                                </span>
                                @if($leave->latestLog && $leave->latestLog->log_remark && $leave->latestLog->log_remark != '---')
                                    <span class="text-[10px] text-slate-500 italic max-w-[150px] truncate" title="{{ $leave->latestLog->log_remark }}">
                                        HR: {{ $leave->latestLog->log_remark }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td><span class="text-sm text-slate-600">{{ \Carbon\Carbon::parse($leave->submission_date)->format('M d, Y') }}</span></td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($leave->leave_status_id == \App\Models\HrLeave::STATUS_ACTION_REQUIRED)
                                    <button onclick="openResubmitModal({{ json_encode($leave) }})" 
                                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                        title="Edit & Resubmit">
                                        <i class="fa-solid fa-pen text-sm"></i>
                                    </button>
                                @endif

                                @if($leave->leave_attachment && $leave->leave_attachment != 'no-img.png')
                                    <a href="{{ asset('uploads/' . $leave->leave_attachment) }}" target="_blank"
                                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-slate-500 to-slate-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                        title="View Attachment">
                                        <i class="fa-solid fa-paperclip text-xs"></i>
                                    </a>
                                @endif
                                
                                <button onclick="openViewModal({{ json_encode($leave) }})" 
                                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-slate-400 to-slate-500 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                    title="View Details">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                    <i class="fa-solid fa-calendar-days text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 font-medium">No leave requests found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
                    <!-- AJAX Pagination -->
                    <div id="leaves-pagination"></div>

                    @if (false && $leaves->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $leaves->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

</div>

</div>

<!-- Request Leave Modal -->
<div class="modal" id="requestLeaveModal">
    <div class="modal-backdrop" onclick="closeModal('requestLeaveModal')"></div>
    <div class="modal-content max-w-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-display font-bold text-premium">Request Leave</h2>
            <button onclick="closeModal('requestLeaveModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('emp.leaves.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-tag text-indigo-600 mr-2"></i>Leave Type
                    </label>
                    <select name="leave_type_id" class="premium-input w-full px-4 py-3 text-sm" required>
                        <option value="">Select Type</option>
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->leave_type_id }}">{{ $type->leave_type_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-calendar text-indigo-600 mr-2"></i>Start Date
                        </label>
                        <input type="date" name="start_date" class="premium-input w-full px-4 py-3 text-sm leave-start" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-calendar-check text-indigo-600 mr-2"></i>End Date
                        </label>
                        <input type="date" name="end_date" class="premium-input w-full px-4 py-3 text-sm leave-end" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-paperclip text-indigo-600 mr-2"></i>Attachment (Optional)
                    </label>
                    <input type="file" name="leave_attachment" id="new_leave_attachment" class="premium-input w-full px-4 py-2 text-sm">
                    <div id="new-leave-attachment-preview"></div>
                </div>

                <!-- Duration Summary -->
                <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl text-center duration-summary" style="display:none;">
                    <p class="text-sm text-blue-800">
                        Total working days (excluding weekends): <span class="font-bold text-lg ml-1 total-days-count">0</span> days
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-comment text-indigo-600 mr-2"></i>Reason/Remarks
                    </label>
                    <textarea name="leave_remarks" rows="3" class="premium-input w-full px-4 py-3 text-sm" placeholder="Explain your reason..." required></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('requestLeaveModal')" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">Submit Request</button>
            </div>
        </form>
    </div>
</div>

<!-- Resubmit Modal -->
<div class="modal" id="resubmitModal">
    <div class="modal-backdrop" onclick="closeModal('resubmitModal')"></div>
    <div class="modal-content max-w-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-display font-bold text-premium">Edit & Resubmit</h2>
            <button onclick="closeModal('resubmitModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="resubmitForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-tag text-indigo-600 mr-2"></i>Leave Type
                    </label>
                    <select name="leave_type_id" id="edit_leave_type_id" class="premium-input w-full px-4 py-3 text-sm" required>
                        <option value="">Select Type</option>
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->leave_type_id }}">{{ $type->leave_type_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-calendar text-indigo-600 mr-2"></i>Start Date
                        </label>
                        <input type="date" name="start_date" id="edit_start_date" class="premium-input w-full px-4 py-3 text-sm leave-start" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-calendar-check text-indigo-600 mr-2"></i>End Date
                        </label>
                        <input type="date" name="end_date" id="edit_end_date" class="premium-input w-full px-4 py-3 text-sm leave-end" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-paperclip text-indigo-600 mr-2"></i>New Attachment (Optional)
                    </label>
                    <input type="file" name="leave_attachment" id="resubmit_leave_attachment" class="premium-input w-full px-4 py-2 text-sm">
                    <div id="resubmit-leave-attachment-preview"></div>
                </div>

                <!-- Duration Summary -->
                <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl text-center duration-summary" style="display:none;">
                    <p class="text-sm text-blue-800">
                        Total working days (excluding weekends): <span class="font-bold text-lg ml-1 total-days-count">0</span> days
                    </p>
                </div>

                <div id="hr_remark_container" class="bg-purple-50 border border-purple-100 p-4 rounded-xl" style="display:none;">
                    <p class="text-xs font-semibold text-purple-800 mb-1"><i class="fa-solid fa-comment-dots mr-1"></i> HR/Manager Remark:</p>
                    <p id="hr_remark_text" class="text-sm text-slate-600 italic"></p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-comment text-indigo-600 mr-2"></i>Reason/Remarks
                    </label>
                    <textarea name="leave_remarks" id="edit_leave_remarks" rows="3" class="premium-input w-full px-4 py-3 text-sm" placeholder="Explain your reason..." required></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('resubmitModal')" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">Resubmit Request</button>
            </div>
        </form>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal" id="viewLeaveModal">
    <div class="modal-backdrop" onclick="closeModal('viewLeaveModal')"></div>
    <div class="modal-content max-w-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Leave Details</h2>
                <p class="text-slate-500 text-xs mt-1 font-bold" id="view_ref_no">#---</p>
            </div>
            <button onclick="closeModal('viewLeaveModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>

        <div class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div class="info-item">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-tag text-indigo-600 mr-2"></i>Leave Type
                    </label>
                    <p class="font-bold text-slate-600 px-4" id="view_type">---</p>
                </div>
                <div class="info-item">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-signal text-indigo-600 mr-2"></i>Status
                    </label>
                    <div id="view_status_badge" class="px-4">
                        <!-- Badge injected by JS -->
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="info-item">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-calendar text-indigo-600 mr-2"></i>Start Date
                    </label>
                    <p class="font-bold text-slate-600 px-4" id="view_start_date">---</p>
                </div>
                <div class="info-item">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-calendar-check text-indigo-600 mr-2"></i>End Date
                    </label>
                    <p class="font-bold text-slate-600 px-4" id="view_end_date">---</p>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl text-center">
                <p class="text-sm text-blue-800">
                    Total working days: <span class="font-bold text-lg ml-1" id="view_total_days">0</span> days
                </p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    <i class="fa-solid fa-comment text-indigo-600 mr-2"></i>Reason/Remarks
                </label>
                <div class="p-4 bg-slate-50 border border-slate-100 rounded-xl text-sm text-slate-600 min-h-[60px]" id="view_reason">
                    ---
                </div>
            </div>

            <div id="view_hr_remark_box" style="display: none;">
                <label class="block text-sm font-semibold text-purple-700 mb-2">
                    <i class="fa-solid fa-comment-dots text-purple-600 mr-2"></i>Latest HR/Manager Remark
                </label>
                <div class="p-4 bg-purple-50 border border-purple-100 rounded-xl text-sm text-purple-900 italic font-medium" id="view_hr_remark">
                    ---
                </div>
            </div>

            <div class="pt-6 border-t border-slate-200 flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Submitted: <span class="text-slate-600" id="view_submission">---</span></span>
                <div id="view_attachment_container">
                    <!-- Button injected by JS -->
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-8 pt-6 border-t border-slate-200">
            <button onclick="closeModal('viewLeaveModal')" class="px-6 py-3 bg-slate-800 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all">
                Close
            </button>
        </div>
    </div>
</div>

<script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script src="{{ asset('libs/mammoth/mammoth.browser.min.js') }}"></script>
    <script src="{{ asset('js/attachment-preview.js') }}"></script>
    <script>
        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('emp.leaves.data') }}",
            containerSelector: '#leaves-container',
            paginationSelector: '#leaves-pagination',
            getAdditionalParams: () => ({
                search: document.querySelector('input[name="search"]').value,
                type_id: document.querySelector('select[name="type_id"]').value,
                status: document.querySelector('select[name="status"]').value
            }),
            renderCallback: function(data) {
                const container = document.querySelector('#leaves-container');
                if (data.length === 0) {
                    container.innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                        <i class="fa-solid fa-calendar-days text-2xl text-slate-400"></i>
                                    </div>
                                    <p class="text-slate-500 font-medium">No leave requests found matching your filters</p>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }

                let html = '';
                data.forEach(leave => {
                    // Matching server-side logic precisely
                    let statusConfig = {
                        1: {bg: 'from-yellow-400 to-amber-500', text: 'Pending HR', icon: 'clock'},
                        2: {bg: 'from-blue-500 to-cyan-600', text: 'Pending Manager', icon: 'user-check'},
                        3: {bg: 'from-green-500 to-emerald-600', text: 'Approved', icon: 'check-double'},
                        4: {bg: 'from-red-500 to-rose-600', text: 'Rejected', icon: 'times-circle'},
                        6: {bg: 'from-purple-500 to-indigo-600', text: 'Action Required', icon: 'user-edit'},
                        default: {bg: 'from-slate-400 to-slate-500', text: 'Unknown', icon: 'question'}
                    };
                    
                    let config = statusConfig[leave.leave_status_id] || statusConfig.default;
                    
                    let remarkHtml = '';
                    if (leave.latest_log && leave.latest_log.log_remark && leave.latest_log.log_remark !== '---') {
                        remarkHtml = `
                            <span class="text-[10px] text-slate-500 italic max-w-[150px] truncate" title="${leave.latest_log.log_remark}">
                                HR: ${leave.latest_log.log_remark}
                            </span>
                        `;
                    }

                    let resubmitBtn = '';
                    if (leave.leave_status_id == 6) {
                        resubmitBtn = `
                            <button onclick='openResubmitModal(${JSON.stringify(leave)})' 
                                class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                title="Edit & Resubmit">
                                <i class="fa-solid fa-pen text-sm"></i>
                            </button>
                        `;
                    }

                    let attachmentBtn = '';
                    if (leave.leave_attachment && leave.leave_attachment !== 'no-img.png') {
                        attachmentBtn = `
                            <a href="/uploads/${leave.leave_attachment}" target="_blank"
                                class="w-8 h-8 rounded-lg bg-gradient-to-br from-slate-500 to-slate-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                title="View Attachment">
                                <i class="fa-solid fa-paperclip text-xs"></i>
                            </a>
                        `;
                    }

                    let startDate = new Date(leave.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    let endDate = new Date(leave.end_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    let submissionDate = new Date(leave.submission_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

                    html += `
                        <tr>
                            <td><span class="font-mono text-sm font-semibold text-slate-600">#${leave.leave_id}</span></td>
                            <td>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-purple-50 text-purple-700 text-sm font-medium">
                                    <i class="fa-solid fa-tag text-xs"></i>
                                    ${leave.type ? leave.type.leave_type_name : 'Unknown'}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <span>${startDate}</span>
                                    <i class="fa-solid fa-arrow-right text-xs text-slate-400"></i>
                                    <span>${endDate}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 text-white font-bold shadow-md">
                                    ${leave.total_days}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="flex flex-col items-center gap-1.5">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r ${config.bg} text-white text-xs font-bold shadow-md whitespace-nowrap">
                                        <i class="fa-solid fa-${config.icon}"></i>
                                        ${config.text}
                                    </span>
                                    ${remarkHtml}
                                </div>
                            </td>
                            <td><span class="text-sm text-slate-600">${submissionDate}</span></td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    ${resubmitBtn}
                                    ${attachmentBtn}
                                    <button onclick='openViewModal(${JSON.stringify(leave)})'
                                         class="w-8 h-8 rounded-lg bg-gradient-to-br from-slate-400 to-slate-500 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                         title="View Details">
                                         <i class="fa-solid fa-eye text-xs"></i>
                                     </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                container.innerHTML = html;
            }
        });

        // Handle Filter Form Submission
        document.getElementById('leaves-filter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            window.ajaxPagination.loadPage(1);
        });

        // Initialize Attachment Previews
        window.initAttachmentPreview({
            inputSelector: '#new_leave_attachment',
            containerSelector: '#new-leave-attachment-preview'
        });

        window.initAttachmentPreview({
            inputSelector: '#resubmit_leave_attachment',
            containerSelector: '#resubmit-leave-attachment-preview'
        });

        // File Size Validation (Max 8MB)
        const leaveInputs = ['new_leave_attachment', 'resubmit_leave_attachment'];
        leaveInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const fileSize = this.files[0].size; // in bytes
                        const maxSize = 8 * 1024 * 1024; // 8MB

                        if (fileSize > maxSize) {
                            Swal.fire({
                                icon: 'error',
                                title: 'File Too Large',
                                text: 'The attachment size must not exceed 8MB.',
                                confirmButtonColor: '#4f46e5'
                            });
                            this.value = ''; // Clear the input
                            
                            // Clear preview
                            const previewId = inputId === 'new_leave_attachment' ? 'new-leave-attachment-preview' : 'resubmit-leave-attachment-preview';
                            const previewContainer = document.getElementById(previewId);
                            if (previewContainer) previewContainer.innerHTML = '';
                        }
                    }
                });
            }
        });
    </script>
<script>
        function openViewModal(leave) {
            document.getElementById('view_ref_no').innerText = 'Ref #' + leave.leave_id;
            document.getElementById('view_type').innerText = leave.type ? leave.type.leave_type_name : 'Unknown';
            document.getElementById('view_start_date').innerText = new Date(leave.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            document.getElementById('view_end_date').innerText = new Date(leave.end_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            document.getElementById('view_total_days').innerText = leave.total_days;
            document.getElementById('view_submission').innerText = new Date(leave.submission_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            document.getElementById('view_reason').innerText = leave.leave_remarks || 'No reason provided';

            // Status Badge
            const statusConfig = {
                1: {bg: 'from-yellow-400 to-amber-500', text: 'Pending HR', icon: 'clock'},
                2: {bg: 'from-blue-500 to-cyan-600', text: 'Pending Manager', icon: 'user-check'},
                3: {bg: 'from-green-500 to-emerald-600', text: 'Approved', icon: 'check-double'},
                4: {bg: 'from-red-500 to-rose-600', text: 'Rejected', icon: 'times-circle'},
                6: {bg: 'from-purple-500 to-indigo-600', text: 'Action Required', icon: 'user-edit'},
                default: {bg: 'from-slate-400 to-slate-500', text: 'Unknown', icon: 'question'}
            };
            const config = statusConfig[leave.leave_status_id] || statusConfig.default;
            document.getElementById('view_status_badge').innerHTML = `
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r ${config.bg} text-white text-xs font-bold shadow-md whitespace-nowrap">
                    <i class="fa-solid fa-${config.icon}"></i>
                    ${config.text}
                </span>
            `;

            // HR Remarks
            if (leave.latest_log && leave.latest_log.log_remark && leave.latest_log.log_remark !== '---') {
                document.getElementById('view_hr_remark_box').style.display = 'block';
                document.getElementById('view_hr_remark').innerText = leave.latest_log.log_remark;
            } else {
                document.getElementById('view_hr_remark_box').style.display = 'none';
            }

            // Attachment
            const attachContainer = document.getElementById('view_attachment_container');
            if (leave.leave_attachment && leave.leave_attachment !== 'no-img.png') {
                attachContainer.innerHTML = `
                    <a href="/uploads/${leave.leave_attachment}" target="_blank"
                        class="flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg font-bold text-xs hover:bg-indigo-100 transition-colors border border-indigo-100 shadow-sm">
                        <i class="fa-solid fa-paperclip"></i>
                        VIEW ATTACHMENT
                    </a>
                `;
            } else {
                attachContainer.innerHTML = '';
            }

            openModal('viewLeaveModal');
        }

    function openResubmitModal(leave) {
        document.getElementById('resubmitForm').action = "/emp/leaves/" + leave.leave_id + "/resubmit";
        document.getElementById('edit_leave_type_id').value = leave.leave_type_id;
        document.getElementById('edit_start_date').value = leave.start_date;
        document.getElementById('edit_end_date').value = leave.end_date;
        document.getElementById('edit_leave_remarks').value = leave.leave_remarks;
        
        // Handle HR Remarks
        const remarkCont = document.getElementById('hr_remark_container');
        const remarkText = document.getElementById('hr_remark_text');
        
        if (leave.latest_log && leave.latest_log.log_remark && leave.latest_log.log_remark !== '---') {
            remarkText.innerText = leave.latest_log.log_remark;
            remarkCont.style.display = 'block';
        } else {
            remarkCont.style.display = 'none';
        }

        calcDaysForContainer(document.getElementById('resubmitModal'));
        openModal('resubmitModal');
    }

    function calcDaysForContainer(container) {
        const startInp = container.querySelector('.leave-start');
        const endInp = container.querySelector('.leave-end');
        const summary = container.querySelector('.duration-summary');
        const countSpan = container.querySelector('.total-days-count');

        if(startInp.value && endInp.value) {
            let start = new Date(startInp.value);
            let end = new Date(endInp.value);
            
            if (start > end) {
                summary.style.display = 'none';
                return;
            }

            let days = 0;
            let current = new Date(start);
            
            while (current <= end) {
                let day = current.getDay();
                if (day !== 0 && day !== 6) { // Exclude Sat/Sun
                    days++;
                }
                current.setDate(current.getDate() + 1);
            }
            
            if(days >= 0) {
                countSpan.innerText = days;
                summary.style.display = 'block';
            } else {
                 summary.style.display = 'none';
            }
        } else {
            summary.style.display = 'none';
        }
    }

    // Attach listeners to all leave date inputs
    document.querySelectorAll('.leave-start, .leave-end').forEach(input => {
        input.addEventListener('change', (e) => {
            const container = e.target.closest('.modal');
            calcDaysForContainer(container);
        });
    });
</script>
@endsection
