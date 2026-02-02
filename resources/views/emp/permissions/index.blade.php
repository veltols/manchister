@extends('layouts.app')

@section('title', 'My Permissions')
@section('subtitle', 'Short time-off requests')

@section('content')
    <div class="space-y-6">

        <!-- Action Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Permission Requests</h2>
                <p class="text-sm text-slate-500 mt-1">Submit and track your short-term leave requests</p>
            </div>
            <button onclick="openModal('requestPermissionModal')" class="premium-button">
                <i class="fa-solid fa-plus"></i>
                <span>New Request</span>
            </button>
        </div>

        <!-- Permissions List -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left">Date</th>
                            <th class="text-left">Time Range</th>
                            <th class="text-center">Hours</th>
                            <th class="text-left">Remarks</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $perm)
                            <tr>
                                <td class="font-bold text-slate-700">
                                    {{ $perm->permission_date ? $perm->permission_date->format('M d, Y') : '-' }}
                                </td>
                                <td>
                                    <div class="flex items-center gap-2 text-sm text-slate-600">
                                        <span class="px-2 py-1 bg-slate-100 rounded-lg">{{ $perm->start_time }}</span>
                                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-400"></i>
                                        <span class="px-2 py-1 bg-slate-100 rounded-lg">{{ $perm->end_time }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="font-bold text-indigo-600">{{ $perm->total_hours }}h</span>
                                </td>
                                <td class="max-w-xs">
                                    <p class="text-sm text-slate-500 truncate" title="{{ $perm->permission_remarks }}">
                                        {{ $perm->permission_remarks }}
                                    </p>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-sm"
                                        style="background: #{{ $perm->status->status_color ?? '64748b' }};">
                                        {{ $perm->status->status_name ?? 'Pending' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                                            <i class="fa-solid fa-clock text-2xl"></i>
                                        </div>
                                        <p class="text-slate-400 font-medium">No permissions found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($permissions->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $permissions->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Request Permission Modal -->
    <div class="modal" id="requestPermissionModal">
        <div class="modal-backdrop" onclick="closeModal('requestPermissionModal')"></div>
        <div class="modal-content max-w-lg p-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-display font-bold text-premium">New Permission Request</h2>
                <button onclick="closeModal('requestPermissionModal')"
                    class="w-10 h-10 rounded-full hover:bg-slate-100 flex items-center justify-center text-slate-400">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <form action="{{ route('emp.permissions.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest text-[10px]">Date of
                        Permission</label>
                    <input type="date" name="permission_date" class="premium-input w-full" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest text-[10px]">Start
                            Time</label>
                        <input type="time" name="start_time" class="premium-input w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest text-[10px]">End
                            Time</label>
                        <input type="time" name="end_time" class="premium-input w-full" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest text-[10px]">Reason
                        / Remarks</label>
                    <textarea name="permission_remarks" rows="3" class="premium-input w-full"
                        placeholder="Briefly explain why you need this permission..." required></textarea>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('requestPermissionModal')"
                        class="px-6 py-3 font-bold text-slate-500 hover:bg-slate-50 rounded-xl transition-all">Cancel</button>
                    <button type="submit" class="premium-button">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
