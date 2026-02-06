@extends('layouts.app')

@section('title', 'User Profile')
@section('subtitle', 'View system user details')

@section('content')
    <div class="space-y-6 animate-fade-in-up md:grid md:grid-cols-3 md:gap-6 md:space-y-0">
        
        <!-- Left Column: Profile Card -->
        <div class="md:col-span-1 space-y-6">
            <div class="premium-card p-6 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                
                <div class="relative z-10 mt-12 mb-4">
                    <div class="w-24 h-24 rounded-full bg-white p-1 mx-auto shadow-xl">
                        <div class="w-full h-full rounded-full bg-slate-100 flex items-center justify-center text-3xl font-bold text-indigo-600">
                             {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </div>
                    </div>
                </div>

                <h2 class="text-xl font-bold text-slate-800">{{ $user->first_name }} {{ $user->last_name }}</h2>
                <p class="text-slate-500 font-medium mb-1">{{ $user->designation->designation_name ?? 'N/A' }}</p>
                <div class="flex justify-center mt-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }} text-xs font-bold">
                        <i class="fa-solid fa-circle text-[8px]"></i>
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-100 text-left space-y-3">
                    <div class="flex items-center gap-3 text-sm text-slate-600">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase">IQC ID</p>
                            <p class="font-mono font-medium">{{ $user->employee_no }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 text-sm text-slate-600">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase">Email</p>
                            <p class="font-medium truncate max-w-[180px]" title="{{ $user->employee_email }}">{{ $user->employee_email }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-sm text-slate-600">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase">Department</p>
                            <p class="font-medium">{{ $user->department->department_name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-sm text-slate-600">
                         <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <i class="fa-solid fa-calendar"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase">Joined Date</p>
                            <p class="font-medium">{{ $user->joined_date ? \Carbon\Carbon::parse($user->joined_date)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="premium-card p-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Account Actions</h3>
                <div class="space-y-3">
                    <button onclick="openModal('permissionsModal')" class="w-full py-2.5 px-4 rounded-xl bg-slate-50 text-slate-600 font-semibold hover:bg-slate-100 hover:text-slate-800 transition-colors text-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-shield-halved"></i> Permissions
                    </button>
                    <button onclick="openModal('resetPasswordModal')" class="w-full py-2.5 px-4 rounded-xl bg-amber-50 text-amber-600 font-semibold hover:bg-amber-100 transition-colors text-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-key"></i> Reset Password
                    </button>
                    <!-- Toggle Status -->
                    <form id="statusToggleForm" action="{{ route('admin.users.update-status', $user->employee_id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="{{ $user->is_active ? 0 : 1 }}">
                        <button type="button" onclick="confirmStatusToggle()" class="w-full py-2.5 px-4 rounded-xl text-white font-semibold transition-colors text-sm flex items-center justify-center gap-2 {{ $user->is_active ? 'bg-rose-500 hover:bg-rose-600' : 'bg-emerald-500 hover:bg-emerald-600' }}">
                            @if($user->is_active)
                                <i class="fa-solid fa-ban"></i> Deactivate User
                            @else
                                <i class="fa-solid fa-check"></i> Activate User
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Tabs/Details -->
        <div class="md:col-span-2 space-y-6">
            
            <div x-data="{ activeTab: 'assets' }" class="premium-card overflow-hidden min-h-[500px]">
                <div class="flex border-b border-slate-100">
                    <button @click="activeTab = 'assets'"
                        :class="activeTab === 'assets' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="px-6 py-4 text-sm font-semibold border-b-2 transition-all">
                        Assets
                    </button>
                    <button @click="activeTab = 'logs'"
                        :class="activeTab === 'logs' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="px-6 py-4 text-sm font-semibold border-b-2 transition-all">
                        Activity Logs
                    </button>
                </div>

                <!-- Assets Tab -->
                <div x-show="activeTab === 'assets'" class="p-0 animate-fade-in-up">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-slate-800">Assigned Assets</h3>
                            <button onclick="openModal('assignAssetModal')" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg text-sm font-bold hover:bg-indigo-100 transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-plus"></i> Assign New Asset
                            </button>
                        </div>

                        @if($assets->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="premium-table w-full">
                                    <thead>
                                        <tr>
                                            <th>Ref</th>
                                            <th>Asset Name</th>
                                            <th>Category</th>
                                            <th>Assigned Date</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assets as $asset)
                                            <tr>
                                                <td class="font-mono text-xs font-bold text-slate-500">#{{ $asset->asset_ref }}</td>
                                                <td class="font-semibold text-slate-700">{{ $asset->asset_name }}</td>
                                                <td>
                                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-bold uppercase">
                                                        {{ $asset->category->category_name ?? 'General' }}
                                                    </span>
                                                </td>
                                                <td class="text-sm text-slate-500 italic">{{ \Carbon\Carbon::parse($asset->assigned_date)->format('M d, Y') }}</td>
                                                <td class="text-center">
                                                    <button onclick="revokeAsset({{ $asset->asset_id }}, '{{ $asset->asset_name }}')" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all shadow-sm">
                                                        <i class="fa-solid fa-unlink text-xs"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 rounded-full bg-slate-50 mx-auto flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-laptop text-2xl text-slate-300"></i>
                                </div>
                                <h3 class="text-slate-800 font-semibold mb-1">No Assets Assigned</h3>
                                <p class="text-sm text-slate-500">This user currently has no assets assigned.</p>
                            </div>
                        @endif
                    </div>
                </div>

                 <!-- Logs Tab -->
                <div x-show="activeTab === 'logs'" class="p-0 animate-fade-in-up" style="display: none;">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-slate-800 mb-6">Recent Activity</h3>
                        
                        @if($logs->count() > 0)
                            <div class="space-y-4">
                                @foreach($logs as $log)
                                    <div class="flex gap-4 p-4 rounded-xl bg-slate-50/50 border border-slate-100 hover:border-indigo-100 transition-colors">
                                        <div class="w-10 h-10 shrink-0 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shadow-sm">
                                            <i class="fa-solid fa-clock-rotate-left"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start mb-1">
                                                <h4 class="font-bold text-slate-800">{{ $log->log_action }}</h4>
                                                <span class="text-[10px] font-bold text-slate-400 uppercase">{{ \Carbon\Carbon::parse($log->log_date)->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-slate-600 truncate mb-1">{{ $log->log_remark }}</p>
                                            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                                <i class="fa-solid fa-user text-[8px] opacity-70"></i>
                                                {{ $log->logger->first_name ?? 'System' }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                 <div class="w-16 h-16 rounded-full bg-slate-50 mx-auto flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-clock-rotate-left text-2xl text-slate-300"></i>
                                </div>
                                 <h3 class="text-slate-800 font-semibold mb-1">No Activity Logs</h3>
                                <p class="text-sm text-slate-500">No recent activity found for this user.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->

    <!-- Reset Password Modal -->
    <div id="resetPasswordModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('resetPasswordModal')"></div>
        <div class="modal-content max-w-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">Reset Password</h2>
                    <p class="text-slate-500 text-sm">Update login credentials for {{ $user->first_name }}</p>
                </div>
                <button onclick="closeModal('resetPasswordModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.users.reset-password', $user->employee_id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">New Password</label>
                        <input type="text" name="password" required class="premium-input w-full px-4 py-3" placeholder="Enter new password">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Log Remark</label>
                        <textarea name="log_remark" required rows="3" class="premium-input w-full px-4 py-3" placeholder="Reason for password reset..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
                    <button type="button" onclick="closeModal('resetPasswordModal')" class="px-6 py-2.5 rounded-xl font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 premium-button from-amber-500 to-orange-600 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Permissions Modal -->
    <div id="permissionsModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('permissionsModal')"></div>
        <div class="modal-content max-w-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">System Permissions</h2>
                    <p class="text-slate-500 text-sm">Manage group and committee access</p>
                </div>
                <button onclick="closeModal('permissionsModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.users.update-permissions', $user->employee_id) }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <label class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100 cursor-pointer hover:bg-slate-100 transition-colors">
                        <div>
                            <span class="block font-bold text-slate-800">Groups Access</span>
                            <span class="text-xs text-slate-400 italic">Allow user to manage and view departmental groups</span>
                        </div>
                        <input type="checkbox" name="is_group" value="1" {{ $user->is_group ? 'checked' : '' }} class="w-6 h-6 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    </label>

                    <label class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100 cursor-pointer hover:bg-slate-100 transition-colors">
                        <div>
                            <span class="block font-bold text-slate-800">Committees Access</span>
                            <span class="text-xs text-slate-400 italic">Allow user to participate in organizational committees</span>
                        </div>
                        <input type="checkbox" name="is_committee" value="1" {{ $user->is_committee ? 'checked' : '' }} class="w-6 h-6 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    </label>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Log Remark</label>
                        <textarea name="log_remark" required rows="3" class="premium-input w-full px-4 py-3" placeholder="Reason for permission change..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
                    <button type="button" onclick="closeModal('permissionsModal')" class="px-6 py-2.5 rounded-xl font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 premium-button from-indigo-600 to-purple-600 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all">
                        Update Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Assign Asset Modal -->
    <div id="assignAssetModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('assignAssetModal')"></div>
        <div class="modal-content max-w-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">Assign New Asset</h2>
                    <p class="text-slate-500 text-sm">Select an available asset for assignment</p>
                </div>
                <button onclick="closeModal('assignAssetModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.users.assign-asset', $user->employee_id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Select Asset</label>
                        <select name="asset_id" required class="premium-input w-full px-4 py-3">
                            <option value="">Choose an available asset...</option>
                            @foreach($availableAssets as $aa)
                                <option value="{{ $aa->asset_id }}">{{ $aa->asset_ref }} - {{ $aa->asset_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Log Remark</label>
                        <textarea name="log_remark" required rows="3" class="premium-input w-full px-4 py-3" placeholder="Assignment notes..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
                    <button type="button" onclick="closeModal('assignAssetModal')" class="px-6 py-2.5 rounded-xl font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 premium-button from-indigo-600 to-purple-600 text-white font-bold rounded-xl shadow-lg hover:scale-105 transition-all">
                        Assign Asset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Revoke Asset Modal -->
    <div id="revokeAssetModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('revokeAssetModal')"></div>
        <div class="modal-content max-w-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-display font-bold text-rose-600 uppercase italic">Revoke Asset?</h2>
                    <p class="text-slate-500 text-sm">This will disconnect the asset from the user.</p>
                </div>
                <button onclick="closeModal('revokeAssetModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.users.revoke-asset', $user->employee_id) }}" method="POST">
                @csrf
                <input type="hidden" name="asset_id" id="revoke_asset_id">
                <div class="space-y-4">
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-100">
                        <p class="text-rose-700 text-sm font-bold" id="revoke_asset_display"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Reason for Revocation</label>
                        <textarea name="log_remark" required rows="3" class="premium-input w-full px-4 py-3" placeholder="Mandatory remark..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
                    <button type="button" onclick="closeModal('revokeAssetModal')" class="px-6 py-2.5 rounded-xl font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-rose-500 text-white font-bold rounded-xl shadow-lg hover:bg-rose-600 hover:scale-105 transition-all">
                        Confirm Revoke
                    </button>
                </div>
            </form>
        </div>
    </div>


    @push('scripts')
        <script>
            function confirmStatusToggle() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to {{ $user->is_active ? 'deactivate' : 'activate' }} this user account.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '{{ $user->is_active ? "#f43f5e" : "#10b981" }}',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Yes, {{ $user->is_active ? "deactivate" : "activate" }} it!',
                    cancelButtonText: 'Cancel',
                    padding: '2rem',
                    borderRadius: '1.5rem',
                    customClass: {
                        popup: 'rounded-3xl',
                        confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                        cancelButton: 'rounded-xl px-6 py-2.5 font-bold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('statusToggleForm').submit();
                    }
                });
            }

            function revokeAsset(id, name) {
                Swal.fire({
                    title: 'Revoke Asset?',
                    text: `Are you sure you want to revoke ${name}? You will need to provide a reason in the resulting log.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f43f5e',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Yes, Open Revoke Form',
                    customClass: {
                        popup: 'rounded-3xl',
                        confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                        cancelButton: 'rounded-xl px-6 py-2.5 font-bold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('revoke_asset_id').value = id;
                        document.getElementById('revoke_asset_display').textContent = "Revoking: " + name;
                        openModal('revokeAssetModal');
                    }
                });
            }
        </script>
        <!-- Alpine.js -->
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @endpush
@endsection
