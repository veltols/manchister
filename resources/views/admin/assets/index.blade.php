@extends('layouts.app')

@section('title', 'Manage Assets')
@section('subtitle', 'Track and assign company assets')

@section('content')
    <div class="space-y-6 animate-fade-in-up">
        
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Company Assets</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $assets->total() }} total assets</p>
            </div>
            <!-- Create Asset Button -->
            <button onclick="openModal('newAssetModal')" 
                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-brand text-white font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all duration-200">
                <i class="fa-solid fa-plus"></i>
                <span>Add New Asset</span>
            </button>
        </div>

        <!-- Filter Tabs -->
        <div class="premium-card p-2">
            <div class="flex gap-2">
                @php $stt = request('stt', 0); @endphp
                <a href="{{ route('admin.assets.index', ['stt' => 0]) }}" 
                   class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 0 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    All Assets
                </a>
                <a href="{{ route('admin.assets.index', ['stt' => 1]) }}" 
                   class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 1 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    About to Expire
                </a>
                <a href="{{ route('admin.assets.index', ['stt' => 2]) }}" 
                   class="px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ $stt == 2 ? 'bg-gradient-brand text-white shadow-lg' : 'text-slate-500 hover:text-brand-dark' }}">
                    Expired
                </a>
            </div>
        </div>

        <!-- Assets List -->
        <div class="space-y-4">
            <div class="overflow-x-auto px-1 pb-4">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left">REF</th>
                            <th class="text-left">Asset Details</th>
                            <th class="text-left">Category</th>
                            <th class="text-left">Assigned To</th>
                            <th class="text-left">Expiry</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="assets-container">
                        @forelse($assets as $asset)
                            <tr>
                                <td>
                                    <span class="font-mono text-sm font-semibold text-slate-600">{{ $asset->asset_ref }}</span>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                         <span class="font-semibold text-slate-800">{{ $asset->asset_name }}</span>
                                         <span class="text-xs text-slate-500 truncate max-w-[200px]">{{ $asset->asset_description }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 text-slate-700 text-sm font-medium">
                                        {{ $asset->category->category_name ?? 'General' }}
                                    </span>
                                </td>
                                <td>
                                    @if($asset->assigned_to && $asset->assignee)
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-indigo-50 flex items-center justify-center text-[10px] font-bold text-indigo-600 border border-indigo-100">
                                                {{ substr($asset->assignee->first_name, 0, 1) }}
                                            </div>
                                            <span class="text-sm font-medium text-indigo-700">{{ $asset->assignee->first_name }} {{ $asset->assignee->last_name }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider bg-slate-100 px-2 py-1 rounded">In Stock</span>
                                    @endif
                                </td>
                                <td>
                                    @if($asset->expiry_date)
                                        @php
                                            $isExpired = $asset->expiry_date->isPast();
                                            $isSoon = $asset->expiry_date->diffInDays(now()) < 30 && !$isExpired;
                                        @endphp
                                        <span class="text-sm font-medium {{ $isExpired ? 'text-rose-600' : ($isSoon ? 'text-amber-600' : 'text-slate-600') }}">
                                            {{ $asset->expiry_date->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 italic">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusName = $asset->status->status_name ?? 'Active';
                                        $gradient = 'from-emerald-500 to-green-600';
                                        $icon = 'fa-circle-check';
                                        
                                        $lowerName = strtolower($statusName);
                                        if (str_contains($lowerName, 'repair') || str_contains($lowerName, 'progress')) {
                                            $gradient = 'from-amber-500 to-orange-600';
                                            $icon = 'fa-screwdriver-wrench';
                                        } elseif (str_contains($lowerName, 'lost') || str_contains($lowerName, 'damage') || str_contains($lowerName, 'broken')) {
                                            $gradient = 'from-rose-500 to-red-600';
                                            $icon = 'fa-circle-xmark';
                                        } elseif (str_contains($lowerName, 'stock') || str_contains($lowerName, 'available')) {
                                            $gradient = 'from-indigo-500 to-purple-600';
                                            $icon = 'fa-box-archive';
                                        }
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r {{ $gradient }} text-white text-xs font-bold shadow-md whitespace-nowrap">
                                        <i class="fa-solid {{ $icon }}"></i> {{ $statusName }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        
                                        <!-- Assign -->
                                        <button onclick="openAssignModal('{{ $asset->asset_id }}')" 
                                                class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm"
                                                title="Assign Asset">
                                            <i class="fa-solid fa-user-plus text-sm"></i>
                                        </button>

                                        <!-- Status -->
                                        <button onclick="openStatusModal('{{ $asset->asset_id }}', '{{ $asset->status_id }}')" 
                                                class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center hover:bg-amber-600 hover:text-white transition-all shadow-sm"
                                                title="Update Status">
                                            <i class="fa-solid fa-arrows-rotate text-sm"></i>
                                        </button>

                                        <!-- View -->
                                        <a href="{{ route('admin.assets.show', $asset->asset_id) }}" 
                                           class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md"
                                           title="View Details">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                            <i class="fa-solid fa-box-open text-2xl text-slate-400"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">No assets found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- AJAX Pagination Container -->
            <div id="assets-pagination"></div>
        </div>
    </div>

    <!-- Modals -->

    <!-- New Asset Modal -->
    <div id="newAssetModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('newAssetModal')"></div>
        <div class="modal-content max-w-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-display font-bold text-premium">Add New Asset</h2>
                <button onclick="closeModal('newAssetModal')"
                    class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.assets.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Asset Name</label>
                            <input type="text" name="asset_name" class="premium-input w-full px-4 py-3 text-sm" required placeholder="e.g. MacBook Pro M2">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Category</label>
                            <select name="category_id" class="premium-input w-full px-4 py-3 text-sm" required>
                                <option value="">Select Category...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                        <textarea name="description" class="premium-input w-full px-4 py-3 text-sm" rows="3" required placeholder="Detailed description of the asset..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Serial Number</label>
                            <input type="text" name="asset_serial" class="premium-input w-full px-4 py-3 text-sm" required placeholder="S/N: XXXXXXXX">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                            <select name="status_id" class="premium-input w-full px-4 py-3 text-sm" required>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->status_id }}">{{ $status->status_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Purchase Date</label>
                            <input type="date" name="purchase_date" class="premium-input w-full px-4 py-3 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Expiry Date</label>
                            <input type="date" name="expiry_date" class="premium-input w-full px-4 py-3 text-sm">
                        </div>
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('newAssetModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                        <i class="fa-solid fa-save mr-2"></i>Save Asset
                    </button>
                </div>
            </form>
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
            <form id="assignForm" method="POST">
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
                    <button type="submit" class="px-5 py-2.5 premium-button from-amber-500 to-orange-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl">Assign Asset</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Status Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-backdrop" onclick="closeStatusModal()"></div>
        <div class="modal-content max-w-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-display font-bold text-premium">Update Asset Status</h2>
                <button onclick="closeStatusModal()" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">New Status</label>
                        <select name="status_id" id="modalStatusId" required class="premium-input w-full px-4 py-3 text-sm">
                            @foreach($statuses as $status)
                                <option value="{{ $status->status_id }}">{{ $status->status_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Remarks</label>
                        <textarea name="remarks" rows="3" required class="premium-input w-full px-4 py-3 text-sm" placeholder="Enter status update notes..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeStatusModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 premium-button from-amber-500 to-orange-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl">Update Status</button>
                </div>
            </form>
        </div>
    </div>


    @push('scripts')
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        function openModal(id) { document.getElementById(id).classList.add('active'); }
        function closeModal(id) { document.getElementById(id).classList.remove('active'); }

        function openAssignModal(assetId) {
            openModal('assignModal');
            document.getElementById('assignForm').action = "/admin/assets/" + assetId + "/assign";
        }
        function closeAssignModal() { closeModal('assignModal'); }

        function openStatusModal(assetId, currentStatusId) {
            openModal('statusModal');
            document.getElementById('modalStatusId').value = currentStatusId;
            document.getElementById('statusForm').action = "/admin/assets/" + assetId + "/status";
        }
        function closeStatusModal() { closeModal('statusModal'); }

        // Initialize AJAX Pagination
        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('admin.assets.data', ['stt' => $stt]) }}",
            containerSelector: '#assets-container',
            paginationSelector: '#assets-pagination',
            perPage: 15,
            renderCallback: function(assets, response) {
                if (assets.length === 0) {
                    return `<tr>
                        <td colspan="8" class="text-center py-12">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                    <i class="fa-solid fa-box-open text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 font-medium">No assets found</p>
                            </div>
                        </td>
                    </tr>`;
                }

                return assets.map(asset => {
                    let expiryHtml = '<span class="text-xs text-slate-400 italic">N/A</span>';
                    if (asset.expiry_date) {
                        const expiryDate = new Date(asset.expiry_date);
                        const now = new Date();
                        const diffTime = expiryDate - now;
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        const isExpired = diffTime < 0;
                        const isSoon = diffDays < 30 && !isExpired;
                        
                        const colorClass = isExpired ? 'text-rose-600' : (isSoon ? 'text-amber-600' : 'text-slate-600');
                        const formattedDate = expiryDate.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
                        expiryHtml = `<span class="text-sm font-medium ${colorClass}">${formattedDate}</span>`;
                    }

                    let assigneeHtml = '<span class="text-xs font-bold text-slate-400 uppercase tracking-wider bg-slate-100 px-2 py-1 rounded">In Stock</span>';
                    if (asset.assigned_to && asset.assignee) {
                        assigneeHtml = `<div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-indigo-50 flex items-center justify-center text-[10px] font-bold text-indigo-600 border border-indigo-100">
                                ${asset.assignee.first_name.charAt(0)}
                            </div>
                            <span class="text-sm font-medium text-indigo-700">${asset.assignee.first_name} ${asset.assignee.last_name}</span>
                        </div>`;
                    }

                    const showUrl = "{{ route('admin.assets.show', ':id') }}".replace(':id', asset.asset_id);
                    const statusName = asset.status ? asset.status.status_name : 'Active';

                    const getStatusBadge = (status) => {
                        const name = status ? status.status_name : 'Active';
                        let gradient = 'from-emerald-500 to-green-600'; // Default: Active/Success
                        let icon = 'fa-circle-check';

                        if (name.toLowerCase().includes('repair') || name.toLowerCase().includes('progress')) {
                            gradient = 'from-amber-500 to-orange-600';
                            icon = 'fa-screwdriver-wrench';
                        } else if (name.toLowerCase().includes('lost') || name.toLowerCase().includes('damage') || name.toLowerCase().includes('broken')) {
                            gradient = 'from-rose-500 to-red-600';
                            icon = 'fa-circle-xmark';
                        } else if (name.toLowerCase().includes('stock') || name.toLowerCase().includes('available')) {
                            gradient = 'from-indigo-500 to-purple-600';
                            icon = 'fa-box-archive';
                        }

                        return `<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r ${gradient} text-white text-xs font-bold shadow-md whitespace-nowrap">
                            <i class="fa-solid ${icon}"></i> ${name}
                        </span>`;
                    };

                    return `<tr>
                        <td><span class="font-mono text-sm font-semibold text-slate-600">${asset.asset_ref}</span></td>
                        <td>
                            <div class="flex flex-col">
                                 <span class="font-semibold text-slate-800">${asset.asset_name}</span>
                                 <span class="text-xs text-slate-500 truncate max-w-[200px]">${asset.asset_description || ''}</span>
                            </div>
                        </td>
                        <td>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 text-slate-700 text-sm font-medium">
                                ${asset.category ? asset.category.category_name : 'General'}
                            </span>
                        </td>
                        <td>${assigneeHtml}</td>
                        <td>${expiryHtml}</td>
                        <td class="text-center">
                            ${getStatusBadge(asset.status)}
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openAssignModal('${asset.asset_id}')" 
                                        class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm"
                                        title="Assign Asset">
                                    <i class="fa-solid fa-user-plus text-sm"></i>
                                </button>
                                <button onclick="openStatusModal('${asset.asset_id}', '${asset.status_id}')" 
                                        class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center hover:bg-amber-600 hover:text-white transition-all shadow-sm"
                                        title="Update Status">
                                    <i class="fa-solid fa-arrows-rotate text-sm"></i>
                                </button>
                                <a href="${showUrl}" 
                                   class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md"
                                   title="View Details">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>`;
                }).join('');
            }
        });

        // Use server-side rendered data for initial page load
        @if($assets->hasPages())
            window.ajaxPagination.renderPagination({
                current_page: {{ $assets->currentPage() }},
                last_page: {{ $assets->lastPage() }},
                from: {{ $assets->firstItem() ?? 0 }},
                to: {{ $assets->lastItem() ?? 0 }},
                total: {{ $assets->total() }}
            });
        @endif
    </script>
    @endpush
@endsection

