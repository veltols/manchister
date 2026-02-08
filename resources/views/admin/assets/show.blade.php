@extends('layouts.app')

@section('title', 'Asset Details')
@section('subtitle', 'View and manage asset #' . $asset->asset_ref)

@section('content')
    <div class="space-y-8 animate-fade-in-up" x-data="{ activeTab: 'details' }">

        <!-- Back Button & Tools -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.assets.index') }}"
                class="group flex items-center gap-2 text-slate-500 font-bold hover:text-indigo-600 transition-colors">
                <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                <span>Back to Assets</span>
            </a>
            
            <button onclick="openModal('manageAssetModal')"
                class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200 flex items-center gap-2">
                <i class="fa-solid fa-sliders text-sm"></i>
                <span>Manage Asset</span>
            </button>
        </div>

        <!-- Premium Hero Banner -->
        <div class="rounded-[2.5rem] bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 p-8 md:p-12 text-white shadow-2xl shadow-indigo-900/20 relative overflow-hidden isolate">
            <!-- Decorative Background Elements -->
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/5 rounded-full blur-3xl -z-10"></div>
            <div class="absolute bottom-0 left-1/3 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl -z-10"></div>
            <div class="absolute top-1/2 left-0 w-32 h-64 bg-indigo-400/10 rounded-full blur-2xl -z-10"></div>

            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-8">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-lg bg-white/10 border border-white/10 text-[10px] font-bold uppercase tracking-widest backdrop-blur-md">
                            {{ $asset->category->category_name ?? 'Asset' }}
                        </span>
                        <span class="text-white/40 text-xs">•</span>
                        <span class="text-xs font-mono font-medium text-white/60">
                            Added {{ $asset->added_date ? $asset->added_date->format('M d, Y') : 'N/A' }}
                        </span>
                    </div>
                    
                    <h1 class="text-3xl md:text-5xl font-display font-black tracking-tight text-white leading-tight">
                        {{ $asset->asset_name }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-6 pt-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/20">
                                <i class="fa-solid fa-user text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider">Assigned To</p>
                                <p class="text-sm font-bold">
                                    {{ $asset->assignee ? $asset->assignee->first_name . ' ' . $asset->assignee->last_name : 'In Stock' }}
                                </p>
                            </div>
                        </div>
                        <div class="w-px h-8 bg-white/10 hidden md:block"></div>
                        <div class="flex items-center gap-3">
                             <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/20">
                                <i class="fa-solid fa-barcode text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider">Reference</p>
                                <p class="text-sm font-bold">{{ $asset->asset_ref }}</p>
                            </div>
                        </div>
                        <div class="w-px h-8 bg-white/10 hidden md:block"></div>
                        <div class="flex items-center gap-3">
                             <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/20">
                                <i class="fa-solid fa-calendar-xmark text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-wider">Warranty Expiry</p>
                                <p class="text-sm font-bold">
                                    {{ $asset->expiry_date ? $asset->expiry_date->format('M d, Y') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-end gap-4">
                    @php
                        $statusName = $asset->status->status_name ?? 'Active';
                        $statusColorClass = 'text-emerald-500';
                        $statusBgClass = 'bg-emerald-500';
                        
                        $lowerName = strtolower($statusName);
                        if (str_contains($lowerName, 'repair') || str_contains($lowerName, 'progress')) {
                            $statusColorClass = 'text-amber-500';
                            $statusBgClass = 'bg-amber-500';
                        } elseif (str_contains($lowerName, 'lost') || str_contains($lowerName, 'damage') || str_contains($lowerName, 'broken')) {
                            $statusColorClass = 'text-rose-500';
                            $statusBgClass = 'bg-rose-500';
                        } elseif (str_contains($lowerName, 'stock') || str_contains($lowerName, 'available')) {
                            $statusColorClass = 'text-indigo-500';
                            $statusBgClass = 'bg-indigo-500';
                        }
                    @endphp
                    <div class="px-6 py-3 rounded-2xl bg-white text-slate-900 shadow-xl flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full animate-pulse {{ $statusBgClass }}"></div>
                        <span class="font-bold text-lg {{ $statusColorClass }}">
                            {{ $statusName }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Details & Timeline -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Navigation Tabs -->
                <div class="flex items-center gap-3 p-2 bg-white/50 backdrop-blur-sm rounded-2xl border border-slate-200 w-fit shadow-sm">
                    <button @click="activeTab = 'details'"
                        :class="activeTab === 'details' ? 'premium-button from-indigo-600 to-purple-700 text-white shadow-md' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600'"
                        class="px-8 py-3 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-xs"></i>
                        <span>Details</span>
                    </button>
                    <button @click="activeTab = 'logs'"
                        :class="activeTab === 'logs' ? 'premium-button from-indigo-600 to-purple-700 text-white shadow-md' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600'"
                        class="px-8 py-3 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                        <span>History</span>
                    </button>
                </div>

                <!-- Content Area -->
                <div class="space-y-8">
                    <!-- Details View -->
                    <div x-show="activeTab === 'details'" class="animate-fade-in-up space-y-8">
                        <div class="premium-card p-1">
                            <div class="bg-indigo-50/50 p-8 rounded-[1.25rem]">
                                <h3 class="flex items-center gap-2 text-sm font-bold text-slate-400 uppercase tracking-widest mb-6">
                                    <i class="fa-solid fa-align-left text-brand"></i>
                                    Asset Description
                                </h3>
                                <div class="prose prose-slate max-w-none prose-p:font-medium prose-p:text-slate-600">
                                    {!! nl2br(e($asset->asset_description)) !!}
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="premium-card p-6 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                                    <i class="fa-solid fa-hashtag text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Serial Number</p>
                                    <p class="font-mono font-bold text-slate-700">{{ $asset->asset_serial ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="premium-card p-6 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                                    <i class="fa-solid fa-tag text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">SKU</p>
                                    <p class="font-mono font-bold text-slate-700">{{ $asset->asset_sku ?: 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logs/History View -->
                    <div x-show="activeTab === 'logs'" class="animate-fade-in-up" style="display: none;">
                        <div class="premium-card p-8">
                            <h3 class="flex items-center gap-2 text-sm font-bold text-slate-400 uppercase tracking-widest mb-8">
                                <i class="fa-solid fa-clock-rotate-left text-brand"></i>
                                Activity Timeline
                            </h3>
                            
                            <div class="relative space-y-8 before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-indigo-100 before:via-slate-200 before:to-transparent">
                                @forelse($logs as $log)
                                    <div class="relative flex items-start gap-4 group">
                                        <div class="absolute left-0 mt-1 ml-5 w-4 h-0.5 bg-indigo-200 group-hover:bg-brand transition-colors"></div>
                                        
                                        <div class="relative z-10 flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-indigo-50 text-indigo-600 shadow-sm group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all duration-300">
                                            <i class="fa-solid fa-check text-[10px]"></i>
                                        </div>
                                        
                                        <div class="flex-1 bg-white rounded-2xl border border-slate-100 p-5 shadow-sm group-hover:shadow-md group-hover:border-indigo-100 transition-all">
                                            <div class="flex flex-wrap justify-between gap-2 mb-2">
                                                <span class="font-bold text-slate-800">{{ $log->log_action }}</span>
                                                <span class="text-xs font-mono text-slate-400 bg-slate-50 px-2 py-1 rounded-md border border-slate-100">
                                                    {{ \Carbon\Carbon::parse($log->log_date)->format('M d, H:i A') }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-slate-500 mb-3">{{ $log->log_remark }}</p>
                                            <div class="flex items-center gap-2 pt-2 border-t border-slate-50">
                                                <div class="w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center text-[9px] font-bold text-slate-500">
                                                    A
                                                </div>
                                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                                                    Administrator
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="pl-12 py-4">
                                        <p class="text-slate-400 italic font-medium">No activity recorded yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar Metadata -->
            <div class="space-y-6">
                <div class="premium-card p-6">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Asset Info</h4>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-slate-50">
                            <span class="text-sm text-slate-500 font-medium">Purchase Date</span>
                            <span class="text-sm font-bold text-slate-700">{{ $asset->purchase_date ? $asset->purchase_date->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-slate-50">
                            <span class="text-sm text-slate-500 font-medium">Last Assigned</span>
                            <span class="text-sm font-bold text-slate-700">{{ $asset->assigned_date ? $asset->assigned_date->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <span class="text-sm text-slate-500 font-medium">Department</span>
                            <span class="text-sm font-bold text-slate-700">General</span>
                        </div>
                    </div>
                </div>

                <div class="premium-card p-6 bg-gradient-to-br from-slate-800 to-slate-900 text-white">
                    <h4 class="text-xs font-bold text-white/40 uppercase tracking-widest mb-4">Quick Links</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.assets.index') }}" class="p-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors flex flex-col items-center gap-2 text-center text-white">
                            <i class="fa-solid fa-list-check opacity-50"></i>
                            <span class="text-[10px] font-bold uppercase tracking-wider">All Assets</span>
                        </a>
                        <button onclick="openModal('manageAssetModal')" class="p-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors flex flex-col items-center gap-2 text-center text-white">
                            <i class="fa-solid fa-arrows-rotate opacity-50"></i>
                            <span class="text-[10px] font-bold uppercase tracking-wider">Update</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Asset Modal (Combined Status & Assign) -->
    <div id="manageAssetModal" class="modal text-slate-900">
        <div class="modal-backdrop" onclick="closeModal('manageAssetModal')"></div>
        <div class="modal-content max-w-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-display font-bold text-premium">Manage Asset</h2>
                <button onclick="closeModal('manageAssetModal')"
                    class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <div class="mb-6 p-4 bg-amber-50 rounded-xl border border-amber-100">
                <p class="text-xs font-semibold text-amber-700 leading-relaxed">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    Update the asset's current state or change the person it's assigned to.
                </p>
            </div>

            <form action="{{ route('admin.assets.update_status', $asset->asset_id) }}" method="POST" id="manageForm">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Change Status</label>
                        <select name="status_id" class="premium-input w-full px-4 py-3 text-sm" required>
                             @foreach($statuses as $st)
                                <option value="{{ $st->status_id }}" {{ $asset->status_id == $st->status_id ? 'selected' : '' }}>
                                    {{ $st->status_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Assign To Employee</label>
                        <select name="assigned_to" class="premium-input w-full px-4 py-3 text-sm">
                            <option value="">-- Keep Current / In Stock --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}" {{ $asset->assigned_to == $emp->employee_id ? 'selected' : '' }}>
                                    {{ $emp->first_name }} {{ $emp->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Update Remarks</label>
                        <textarea name="remarks" class="premium-input w-full px-4 py-3 text-sm" rows="3"
                            placeholder="Briefly describe the change..." required></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('manageAssetModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modals JS Logic -->
    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    </script>
@endsection
