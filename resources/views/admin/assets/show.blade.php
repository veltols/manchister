@extends('layouts.app')

@section('title', 'Asset Details')
@section('subtitle', 'View and manage asset #' . $asset->asset_ref)

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <!-- Summary Badges -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="premium-card p-4 flex items-center justify-between group">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Asset Ref</p>
                    <p class="text-2xl font-bold text-slate-700">{{ $asset->asset_ref }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-barcode"></i>
                </div>
            </div>

            <div class="premium-card p-4 flex items-center justify-between group">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status</p>
                    <p class="text-xl font-bold text-emerald-600">Active</p> 
                    {{-- Status Name logic if relationship existed would go here --}}
                </div>
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            <div class="premium-card p-4 flex items-center justify-between group">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Assigned To</p>
                    <p class="text-xl font-bold text-slate-700">
                        {{ $asset->assignee ? $asset->assignee->first_name : 'In Stock' }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-user"></i>
                </div>
            </div>

             <div class="premium-card p-4 flex items-center justify-between group">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Expiry</p>
                    <p class="text-xl font-bold text-slate-700">
                         {{ $asset->expiry_date ? $asset->expiry_date->format('M d, Y') : 'N/A' }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-rose-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-calendar-xmark"></i>
                </div>
            </div>

        </div>

        <!-- Tabs Container -->
        <div x-data="{ activeTab: 'details' }" class="premium-card overflow-hidden">
            <div class="flex border-b border-slate-100">
                <button @click="activeTab = 'details'"
                    :class="activeTab === 'details' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-6 py-4 text-sm font-semibold border-b-2 transition-all">
                    Asset Details
                </button>
                <button @click="activeTab = 'logs'"
                    :class="activeTab === 'logs' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="px-6 py-4 text-sm font-semibold border-b-2 transition-all">
                    History & Logs
                </button>
            </div>

            <!-- Details Tab -->
            <div x-show="activeTab === 'details'" class="p-6 space-y-8 animate-fade-in-up">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-2">Asset Name</h3>
                        <p class="text-lg font-medium text-slate-800">{{ $asset->asset_name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-2">Category</h3>
                        <p class="text-lg font-medium text-slate-800">{{ $asset->category->category_name ?? 'N/A' }}</p>
                    </div>
                </div>

                 <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-2">Serial Number</h3>
                        <p class="text-lg font-mono text-slate-600">{{ $asset->asset_serial }}</p>
                    </div>
                     <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-2">SKU</h3>
                        <p class="text-lg font-mono text-slate-600">{{ $asset->asset_sku }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-slate-400 uppercase mb-2">Description</h3>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $asset->asset_description }}</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 border-t border-slate-100 pt-6">
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-1">Purchase Date</h3>
                        <p class="text-slate-700 mt-2">{{ $asset->purchase_date ? $asset->purchase_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-1">Assigned Date</h3>
                        <p class="text-slate-700 mt-2">{{ $asset->assigned_date ? $asset->assigned_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                     <div>
                        <h3 class="text-sm font-bold text-slate-400 uppercase mb-1">Added Date</h3>
                        <p class="text-slate-700 mt-2">{{ $asset->added_date ? $asset->added_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                </div>

                <!-- Quick Actions Block -->
                <div class="border-t border-slate-100 pt-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap gap-4">
                        
                        <!-- Assign Action -->
                        <button onclick="openAssignModal()" class="premium-button from-amber-500 to-orange-600 text-white shadow-lg">
                            <i class="fa-solid fa-user-plus mr-2"></i>
                            <span>{{ $asset->assigned_to ? 'Reassign Asset' : 'Assign Asset' }}</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Logs Tab -->
            <div x-show="activeTab === 'logs'" class="p-0 animate-fade-in-up" style="display: none;">
                <div class="overflow-x-auto">
                    <table class="premium-table w-full">
                        <thead>
                            <tr>
                                <th class="text-left pl-6">Action</th>
                                <th class="text-left">Remark</th>
                                <th class="text-left">Date</th>
                                <th class="text-left pr-6">By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td class="pl-6 font-medium text-slate-800">{{ $log->log_action }}</td>
                                    <td class="text-slate-600">{{ $log->log_remark }}</td>
                                    <td class="text-slate-500 text-sm whitespace-nowrap">{{ \Carbon\Carbon::parse($log->log_date)->format('M d, Y h:i A') }}</td>
                                    <td class="pr-6">
                                        <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-semibold">
                                            Admin
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-slate-400">
                                        <i class="fa-regular fa-folder-open text-3xl mb-2"></i>
                                        <p>No history found for this asset.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <!-- Assign Modal -->
    <div id="assignModal" class="modal">
        <div class="modal-backdrop" onclick="closeAssignModal()"></div>
        <div class="modal-content max-w-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-display font-bold text-premium">Assign Asset</h2>
                <button onclick="closeAssignModal()" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <form id="assignForm" method="POST" action="{{ route('admin.assets.assign', $asset->asset_id) }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Assign To Employee</label>
                        <select name="assigned_to" required class="premium-input w-full px-4 py-3 text-sm">
                            <option value="">Select Employee...</option>
                             @foreach($employees as $emp)
                                <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Remarks</label>
                        <textarea name="remarks" rows="3" required class="premium-input w-full px-4 py-3 text-sm" placeholder="Assignment notes..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeAssignModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 premium-button from-amber-500 to-orange-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl">Confirm Assignment</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    @push('scripts')
    <script>
        function openAssignModal() {
            document.getElementById('assignModal').classList.add('active');
        }

        function closeAssignModal() {
             document.getElementById('assignModal').classList.remove('active');
        }
    </script>
    @endpush
@endsection
