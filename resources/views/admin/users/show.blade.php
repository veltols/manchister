@extends('layouts.app')

@section('title', 'User Profile')
@section('subtitle', 'View system user details')

@section('content')
    <div class="space-y-6 animate-fade-in-up md:grid md:grid-cols-3 md:gap-6 md:space-y-0">
        
        <!-- Left Column: Profile Card -->
        <div class="md:col-span-1 space-y-6">
            <div class="premium-card p-6 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-brand"></div>
                
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
                    <button onclick="openModal('resetPasswordModal')" class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 text-white font-semibold shadow-md hover:shadow-amber-200/50 hover:scale-[1.02] transition-all text-sm flex items-center justify-center gap-2 border border-white/10">
                        <i class="fa-solid fa-key"></i> Reset Password
                    </button>
                    <!-- Toggle Status -->
                    <!-- Toggle Status -->
                    @if($user->is_active)
                        <!-- Deactivate Button (Opens Modal) -->
                        <button onclick="openModal('deactivateUserModal')" class="w-full py-2.5 px-4 rounded-xl bg-rose-500 text-white font-semibold hover:bg-rose-600 transition-colors text-sm flex items-center justify-center gap-2 shadow-md hover:shadow-rose-200">
                            <i class="fa-solid fa-ban"></i> Deactivate User
                        </button>
                    @else
                        <!-- Activate Form (Direct) -->
                        <form id="activateUserForm" action="{{ route('admin.users.update-status', $user->employee_id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="1">
                            <button type="button" onclick="confirmActivation()" class="w-full py-2.5 px-4 rounded-xl bg-emerald-500 text-white font-semibold hover:bg-emerald-600 transition-colors text-sm flex items-center justify-center gap-2 shadow-md hover:shadow-emerald-200">
                                <i class="fa-solid fa-check"></i> Activate User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Tabs/Details -->
        <div class="md:col-span-2 space-y-6">
            
            <div x-data="{ activeTab: 'assets' }" class="premium-card overflow-hidden min-h-[500px]">
                <div class="flex items-center gap-4 p-4 border-b border-slate-100 bg-slate-50/30">
                    <button @click="activeTab = 'assets'"
                        :class="activeTab === 'assets' ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'bg-white text-slate-500 hover:bg-slate-100'"
                        class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300">
                        Assets
                    </button>
                    <button @click="activeTab = 'services'"
                        :class="activeTab === 'services' ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'bg-white text-slate-500 hover:bg-slate-100'"
                        class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300">
                        <i class="fa-solid fa-shield-halved mr-1"></i> Permissions
                    </button>
                    <button @click="activeTab = 'logs'"
                        :class="activeTab === 'logs' ? 'premium-button from-indigo-600 to-purple-600 text-white shadow-md' : 'bg-white text-slate-500 hover:bg-slate-100'"
                        class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300">
                        Activity Logs
                    </button>
                </div>

                <!-- Assets Tab -->
                <div x-show="activeTab === 'assets'" class="p-0 animate-fade-in-up">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-slate-800">Assigned Assets</h3>
                             <button onclick="openModal('assignAssetModal')" class="px-5 py-2.5 bg-gradient-brand text-white rounded-xl text-sm font-bold shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all flex items-center gap-2 border border-white/10">
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

                <!-- Services / Permissions Tab -->
                <div x-show="activeTab === 'services'" class="p-0 animate-fade-in-up" style="display: none;">
                    <div class="p-6">

                        <style>
                            /* === Toggle Switch === */
                            .srv-toggle-wrap { display: flex; align-items: center; gap: 12px; }
                            .srv-toggle { position: relative; width: 52px; height: 28px; flex-shrink: 0; cursor: pointer; }
                            .srv-toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
                            .srv-slider {
                                position: absolute; inset: 0;
                                background: #e2e8f0;
                                border-radius: 99px;
                                transition: background 0.3s ease, box-shadow 0.3s ease;
                                box-shadow: inset 0 2px 4px rgba(0,0,0,0.08);
                            }
                            .srv-slider::before {
                                content: '';
                                position: absolute;
                                left: 3px; top: 3px;
                                width: 22px; height: 22px;
                                border-radius: 50%;
                                background: white;
                                box-shadow: 0 2px 6px rgba(0,0,0,0.20);
                                transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s ease;
                            }
                            .srv-toggle input:checked + .srv-slider {
                                background: linear-gradient(135deg, #004F68 0%, #006a8a 100%);
                                box-shadow: 0 0 0 3px rgba(0,106,138,0.18), inset 0 2px 4px rgba(0,0,0,0.1);
                            }
                            .srv-toggle input:checked + .srv-slider::before {
                                transform: translateX(24px);
                                box-shadow: 0 3px 10px rgba(0,79,104,0.35);
                            }
                            .srv-toggle input:not(:checked) + .srv-slider:hover { background: #cbd5e1; }
                            /* Saving spinner overlay */
                            .srv-toggle.saving .srv-slider { opacity: 0.55; pointer-events: none; }

                            /* === Service Cards === */
                            .srv-card {
                                background: white;
                                border-radius: 16px;
                                border: 1.5px solid #e2e8f0;
                                padding: 18px 20px;
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                                gap: 16px;
                                transition: border-color 0.3s, box-shadow 0.3s, transform 0.2s;
                            }
                            .srv-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.06); }
                            .srv-card.is-enabled { border-color: rgba(0,106,138,0.3); background: linear-gradient(135deg, #f0f9ff 0%, #e7f4f8 100%); }
                            .srv-card.is-enabled:hover { box-shadow: 0 8px 20px rgba(0,79,104,0.12); }

                            .srv-icon {
                                width: 42px; height: 42px;
                                border-radius: 12px;
                                display: flex; align-items: center; justify-content: center;
                                font-size: 17px;
                                flex-shrink: 0;
                                transition: all 0.3s;
                            }
                            .srv-icon.off { background: #f1f5f9; color: #94a3b8; }
                            .srv-icon.on  { background: linear-gradient(135deg,#004F68,#006a8a); color: white; box-shadow: 0 4px 12px rgba(0,79,104,0.3); }

                            .srv-badge {
                                font-size: 10px; font-weight: 700; letter-spacing: .06em;
                                padding: 3px 8px; border-radius: 99px; text-transform: uppercase;
                            }
                            .srv-badge.on  { background: #dcf5e7; color: #15803d; }
                            .srv-badge.off { background: #f1f5f9; color: #94a3b8; }

                            .srv-saving-dot {
                                width: 8px; height: 8px; border-radius: 50%;
                                background: #0088b3; display: none;
                                animation: srvPulse 0.7s infinite alternate;
                            }
                            @keyframes srvPulse {
                                from { transform: scale(0.8); opacity: 0.5; }
                                to   { transform: scale(1.3); opacity: 1; }
                            }
                        </style>

                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">System Service Permissions</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Toggle a switch to instantly enable or disable a service for this user.</p>
                            </div>
                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">
                                <i class="fa-solid fa-circle-info text-brand-accent"></i>
                                Changes save automatically
                            </div>
                        </div>

                        @if($allServices->count() > 0)
                            <div class="grid grid-cols-1 gap-3" id="servicesGrid">
                                @foreach($allServices as $service)
                                    @php
                                        $isEnabled = in_array($service->service_id, $enabledServiceIds);
                                        $sid = $service->service_id;
                                    @endphp
                                    <div class="srv-card {{ $isEnabled ? 'is-enabled' : '' }}" id="srv-card-{{ $sid }}">

                                        {{-- Left: Icon + Text --}}
                                        <div class="flex items-center gap-3 flex-1 min-w-0">
                                            <div class="srv-icon {{ $isEnabled ? 'on' : 'off' }}" id="srv-icon-{{ $sid }}">
                                                <i class="fa-solid fa-layer-group"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-bold text-slate-800 text-sm truncate">{{ $service->service_title }}</p>
                                                <div class="flex items-center gap-2 mt-0.5">
                                                    <span class="srv-badge {{ $isEnabled ? 'on' : 'off' }}" id="srv-badge-{{ $sid }}">
                                                        {{ $isEnabled ? 'Active' : 'Inactive' }}
                                                    </span>
                                                    <div class="srv-saving-dot" id="srv-dot-{{ $sid }}"></div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Right: Toggle --}}
                                        <label class="srv-toggle" id="srv-toggle-{{ $sid }}" title="{{ $isEnabled ? 'Click to disable' : 'Click to enable' }}">
                                            <input
                                                type="checkbox"
                                                id="srv-chk-{{ $sid }}"
                                                {{ $isEnabled ? 'checked' : '' }}
                                                onchange="toggleService({{ $sid }}, this.checked)">
                                            <span class="srv-slider"></span>
                                        </label>

                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-16">
                                <div class="w-20 h-20 rounded-full bg-slate-50 mx-auto flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-layer-group text-3xl text-slate-300"></i>
                                </div>
                                <h3 class="text-slate-800 font-semibold mb-1">No Services Defined</h3>
                                <p class="text-sm text-slate-500">No system services found in the database.</p>
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
                                    @php
                                        // Parse attachment from remark
                                        $remark = $log->log_remark;
                                        $attachment = null;
                                        if (preg_match('/\[Attachment: (.*?)\]/', $remark, $matches)) {
                                            $attachment = $matches[1];
                                            $remark = str_replace($matches[0], '', $remark);
                                        }
                                    @endphp
                                    <div class="flex gap-4 p-4 rounded-xl bg-slate-50/50 border border-slate-100 hover:border-indigo-100 transition-colors">
                                        <div class="w-10 h-10 shrink-0 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shadow-sm">
                                            <i class="fa-solid fa-clock-rotate-left"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start mb-1">
                                                <h4 class="font-bold text-slate-800">{{ $log->log_action }}</h4>
                                                <span class="text-[10px] font-bold text-slate-400 uppercase">{{ \Carbon\Carbon::parse($log->log_date)->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-slate-600 mb-1 break-words">{{ trim($remark) }}</p>
                                            
                                            @if($attachment)
                                                <div class="mt-2">
                                                    <a href="{{ asset(trim($attachment)) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-indigo-600 text-xs font-bold hover:bg-indigo-50 hover:border-indigo-200 transition-all shadow-sm group">
                                                        <span class="w-6 h-6 rounded bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                                                            <i class="fa-solid fa-paperclip"></i>
                                                        </span>
                                                        View Attachment
                                                    </a>
                                                </div>
                                            @endif

                                            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-2">
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
                    <button type="submit" class="px-6 py-2.5 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:scale-105 transition-all border border-white/10">
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
                    <button type="submit" class="px-6 py-2.5 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:scale-105 transition-all border border-white/10">
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
                    <button type="submit" class="px-6 py-2.5 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:scale-105 transition-all border border-white/10">
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
    <!-- Deactivate User Modal -->
    <div id="deactivateUserModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('deactivateUserModal')"></div>
        <div class="modal-content max-w-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-display font-bold text-rose-600">Deactivate User</h2>
                    <p class="text-slate-500 text-sm">Disable access for {{ $user->first_name }}</p>
                </div>
                <button onclick="closeModal('deactivateUserModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.users.update-status', $user->employee_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="status" value="0">
                
                <div class="space-y-4">
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-100 flex gap-3 text-rose-700">
                        <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                        <p class="text-sm">This action will prevent the user from logging in. You must provide a reason.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Reason / Comment <span class="text-rose-500">*</span></label>
                        <textarea name="log_remark" required rows="3" class="premium-input w-full px-4 py-3" placeholder="Reason for deactivation..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                            <i class="fa-solid fa-paperclip text-indigo-500 mr-1"></i> Attachment <span class="text-slate-400 font-normal normal-case">(Optional)</span>
                        </label>
                        <input type="file" name="log_attachment" id="log_attachment" class="premium-input w-full px-4 py-3 text-sm">
                        <div id="log-attachment-preview"></div>
                        <p class="text-[10px] text-slate-400 mt-1">Files will be saved to system logs.</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
                    <button type="button" onclick="closeModal('deactivateUserModal')" class="px-6 py-2.5 rounded-xl font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-rose-600 text-white font-bold rounded-xl shadow-lg hover:bg-rose-700 hover:scale-105 transition-all">
                        Confirm Deactivation
                    </button>
                </div>
            </form>
        </div>
    </div>


    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
    <script src="{{ asset('js/attachment-preview.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.initAttachmentPreview) {
                window.initAttachmentPreview({
                    inputSelector: '#log_attachment',
                    containerSelector: '#log-attachment-preview'
                });
            }

            // File Size Validation
            const attachmentInput = document.getElementById('log_attachment');
            if (attachmentInput) {
                attachmentInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const fileSize = this.files[0].size; 
                        const maxSize = 8 * 1024 * 1024; // 8MB

                        if (fileSize > maxSize) {
                            Swal.fire({
                                icon: 'error',
                                title: 'File Too Large',
                                text: 'The attachment size must not exceed 8MB.',
                                confirmButtonColor: '#f43f5e'
                            });
                            this.value = ''; 
                            const previewContainer = document.getElementById('log-attachment-preview');
                            if (previewContainer) previewContainer.innerHTML = '';
                        }
                    }
                });
            }
        });

        function confirmActivation() {
                Swal.fire({
                    title: 'Activate User?',
                    text: "You are about to reactivate this user account.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Yes, Activate it!',
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
                        document.getElementById('activateUserForm').submit();
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

            function toggleService(serviceId, isChecked) {
                const newVal  = isChecked ? 1 : 0;
                const card    = document.getElementById('srv-card-'  + serviceId);
                const icon    = document.getElementById('srv-icon-'  + serviceId);
                const badge   = document.getElementById('srv-badge-' + serviceId);
                const dot     = document.getElementById('srv-dot-'   + serviceId);
                const toggle  = document.getElementById('srv-toggle-'+ serviceId);
                const chk     = document.getElementById('srv-chk-'   + serviceId);

                // Show saving state
                dot.style.display = 'block';
                toggle.classList.add('saving');

                fetch('{{ route('admin.users.update-service', $user->employee_id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ service_id: serviceId, new_val: newVal })
                })
                .then(res => res.json())
                .then(data => {
                    dot.style.display = 'none';
                    toggle.classList.remove('saving');

                    if (data.success) {
                        // Update card visuals
                        if (newVal === 1) {
                            card.classList.add('is-enabled');
                            icon.classList.replace('off', 'on');
                            badge.classList.replace('off', 'on');
                            badge.textContent = 'Active';
                            toggle.title = 'Click to disable';
                        } else {
                            card.classList.remove('is-enabled');
                            icon.classList.replace('on', 'off');
                            badge.classList.replace('on', 'off');
                            badge.textContent = 'Inactive';
                            toggle.title = 'Click to enable';
                        }

                        // Toast
                        Swal.fire({
                            toast: true, position: 'top-end',
                            icon: 'success',
                            title: newVal === 1 ? '✓ Service Enabled' : '✓ Service Disabled',
                            showConfirmButton: false,
                            timer: 2200, timerProgressBar: true
                        });
                    } else {
                        // Revert toggle on error
                        chk.checked = !isChecked;
                        Swal.fire({ icon: 'error', title: 'Failed', text: data.message ?? 'Something went wrong.' });
                    }
                })
                .catch(() => {
                    dot.style.display = 'none';
                    toggle.classList.remove('saving');
                    // Revert toggle on network error
                    chk.checked = !isChecked;
                    Swal.fire({ icon: 'error', title: 'Network Error', text: 'Please try again.' });
                });
            }
        </script>
        <!-- Alpine.js -->
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @endpush
@endsection
