<div class="modal" id="addLeaveModal">
    <div class="modal-backdrop" onclick="closeModal('addLeaveModal')"></div>
    <div class="modal-content max-w-2xl p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-display font-bold text-premium">Add New Leave</h2>
            <button onclick="closeModal('addLeaveModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('hr.leaves.store') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <!-- Employee Select -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-user text-indigo-600 mr-2"></i>Employee
                    </label>
                    <select name="employee_id" class="premium-input w-full px-4 py-3 text-sm" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_no }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Leave Type -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-tag text-indigo-600 mr-2"></i>Leave Type
                    </label>
                    <select name="leave_type_id" class="premium-input w-full px-4 py-3 text-sm" required>
                        <option value="">Select Type</option>
                        @foreach($types as $type)
                            <option value="{{ $type->leave_type_id }}">{{ $type->leave_type_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-calendar text-indigo-600 mr-2"></i>Start Date
                        </label>
                        <input type="date" name="start_date" id="start_date" class="premium-input w-full px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-calendar-check text-indigo-600 mr-2"></i>End Date
                        </label>
                        <input type="date" name="end_date" id="end_date" class="premium-input w-full px-4 py-3 text-sm" required>
                    </div>
                </div>

                <!-- Summary -->
                <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl text-center" id="days-summary" style="display:none;">
                    <p class="text-sm text-blue-800">
                        Total leave duration: <span id="total-days-count" class="font-bold text-lg ml-1">0</span> days
                    </p>
                </div>

                <!-- Remarks -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-comment text-indigo-600 mr-2"></i>Remarks
                    </label>
                    <textarea name="leave_remarks" rows="3" class="premium-input w-full px-4 py-3 text-sm" placeholder="Reason for leave..."></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('addLeaveModal')" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-8 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                    <i class="fa-solid fa-paper-plane mr-2"></i>Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
   
    // Use the existing global script functions if available, or define locally if needed
    // Assuming openModal/closeModal are global from app schema

    // Simple date diff calc
    const startInp = document.getElementById('start_date');
    const endInp = document.getElementById('end_date');
    const summary = document.getElementById('days-summary');
    const countSpan = document.getElementById('total-days-count');

    function calcDays() {
        if(startInp.value && endInp.value) {
            const start = new Date(startInp.value);
            const end = new Date(endInp.value);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; 
            
            if(!isNaN(diffDays) && diffDays > 0) {
                countSpan.innerText = diffDays;
                summary.style.display = 'block';
            } else {
                 summary.style.display = 'none';
            }
        }
    }

    startInp.addEventListener('change', calcDays);
    endInp.addEventListener('change', calcDays);
</script>
