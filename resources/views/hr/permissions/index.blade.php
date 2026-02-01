@extends('layouts.app')

@section('title', 'Permissions')
@section('subtitle', 'Short leave requests')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-slate-800">Short Leave Permissions</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $permissions->total() }} total requests</p>
        </div>
        <button onclick="openModal('permissionModal')" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
            <i class="fa-solid fa-plus"></i>
            <span>Request Permission</span>
        </button>
    </div>

    <!-- Permissions Table -->
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Employee</th>
                        <th class="text-left">Date</th>
                        <th class="text-left">Time</th>
                        <th class="text-center">Status</th>
                        <th class="text-left">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $p)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold shadow-md">
                                    {{ strtoupper(substr($p->employee->first_name ?? 'M', 0, 1)) }}
                                </div>
                                <span class="font-semibold text-slate-800">{{ $p->employee->first_name ?? 'Me' }}</span>
                            </div>
                        </td>
                        <td><span class="text-sm text-slate-600">{{ $p->start_date }}</span></td>
                        <td>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-sm font-medium font-mono">
                                <i class="fa-solid fa-clock text-xs"></i>
                                {{ $p->start_time }} - {{ $p->end_time }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r from-yellow-500 to-amber-600 text-white text-xs font-bold shadow-md">
                                <i class="fa-solid fa-clock"></i>
                                {{ $p->status->permission_status_name ?? 'Pending' }}
                            </span>
                        </td>
                        <td><span class="text-sm text-slate-600">{{ $p->permission_remarks }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                    <i class="fa-solid fa-clock text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 font-medium">No permission requests found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($permissions->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $permissions->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

</div>

<!-- Permission Modal -->
<div id="permissionModal" class="modal">
    <div class="modal-backdrop" onclick="closeModal('permissionModal')"></div>
    <div class="modal-content max-w-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-display font-bold text-slate-800">Request Short Leave</h2>
            <button onclick="closeModal('permissionModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('hr.permissions.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-calendar text-indigo-600 mr-2"></i>Date
                    </label>
                    <input type="date" name="start_date" class="premium-input w-full px-4 py-3 text-sm" required>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-clock text-indigo-600 mr-2"></i>From
                        </label>
                        <input type="time" name="start_time" class="premium-input w-full px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fa-solid fa-clock text-indigo-600 mr-2"></i>To
                        </label>
                        <input type="time" name="end_time" class="premium-input w-full px-4 py-3 text-sm" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fa-solid fa-comment text-indigo-600 mr-2"></i>Reason
                    </label>
                    <textarea name="permission_remarks" class="premium-input w-full px-4 py-3 text-sm" rows="3" placeholder="Explain your reason..." required></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('permissionModal')" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
