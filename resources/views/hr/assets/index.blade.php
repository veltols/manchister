@extends('layouts.app')

@section('title', 'Assets')
@section('subtitle', 'Manage company assets')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-premium">Asset Management</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $assets->total() }} total assets</p>
        </div>
        <button onclick="openModal('addAssetModal')" class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
            <i class="fa-solid fa-plus"></i>
            <span>Add Asset</span>
        </button>
    </div>

    <!-- Assets Table -->
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="premium-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Ref</th>
                        <th class="text-left">Asset Name</th>
                        <th class="text-left">Category</th>
                        <th class="text-left">Assigned To</th>
                        <th class="text-left">Serial/SKU</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                    <tbody id="assets-container">
                        @forelse($assets as $asset)
                        <tr>
                            <td><span class="font-mono text-sm font-semibold text-indigo-600">{{ $asset->asset_ref }}</span></td>
                            <td><span class="font-semibold text-slate-800">{{ $asset->asset_name }}</span></td>
                            <td>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-sm font-medium">
                                    <i class="fa-solid fa-tag text-xs"></i>
                                    {{ $asset->category->category_name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td>
                                @if($asset->assignee)
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white text-xs font-semibold shadow-md">
                                            {{ substr($asset->assignee->first_name, 0, 1) }}
                                        </div>
                                        <span class="text-sm font-semibold text-slate-800">{{ $asset->assignee->first_name }} {{ $asset->assignee->last_name }}</span>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium">
                                        <i class="fa-solid fa-box text-xs"></i>
                                        In Stock
                                    </span>
                                @endif
                            </td>
                            <td><span class="text-sm text-slate-600">{{ $asset->asset_serial ?? '-' }}</span></td>
                            <td>
                                <div class="flex items-center justify-center">
                                    <button onclick="openAssignModal({{ $asset->asset_id }}, '{{ $asset->asset_name }}')" 
                                        class="w-9 h-9 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                        title="Assign">
                                        <i class="fa-solid fa-user-tag text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                        <i class="fa-solid fa-laptop text-2xl text-slate-400"></i>
                                    </div>
                                    <p class="text-slate-500 font-medium">No assets found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- AJAX Pagination -->
            <div id="assets-pagination"></div>
        </div>

    </div>

    @push('scripts')
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('hr.assets.data') }}",
            containerSelector: '#assets-container',
            paginationSelector: '#assets-pagination',
            renderCallback: function(assets) {
                const container = document.querySelector('#assets-container');
                if (assets.length === 0) {
                    container.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                        <i class="fa-solid fa-laptop text-2xl text-slate-400"></i>
                                    </div>
                                    <p class="text-slate-500 font-medium">No assets found</p>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }

                let html = '';
                assets.forEach(asset => {
                    let assigneeHtml = `
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium">
                            <i class="fa-solid fa-box text-xs"></i>
                            In Stock
                        </span>
                    `;

                    if (asset.assignee) {
                        assigneeHtml = `
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white text-xs font-semibold shadow-md">
                                    ${asset.assignee.first_name.charAt(0)}
                                </div>
                                <span class="text-sm font-semibold text-slate-800">${asset.assignee.first_name} ${asset.assignee.last_name}</span>
                            </div>
                        `;
                    }

                    html += `
                        <tr>
                            <td><span class="font-mono text-sm font-semibold text-indigo-600">${asset.asset_ref}</span></td>
                            <td><span class="font-semibold text-slate-800">${asset.asset_name}</span></td>
                            <td>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-sm font-medium">
                                    <i class="fa-solid fa-tag text-xs"></i>
                                    ${asset.category ? asset.category.category_name : 'Uncategorized'}
                                </span>
                            </td>
                            <td>${assigneeHtml}</td>
                            <td><span class="text-sm text-slate-600">${asset.asset_serial || '-'}</span></td>
                            <td>
                                <div class="flex items-center justify-center">
                                    <button onclick="openAssignModal(${asset.asset_id}, '${asset.asset_name}')" 
                                        class="w-9 h-9 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                        title="Assign">
                                        <i class="fa-solid fa-user-tag text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                container.innerHTML = html;
            }
        });

        // Initial pagination setup
        @if($assets->hasPages())
            window.ajaxPagination.renderPagination({
                current_page: {{ $assets->currentPage() }},
                last_page: {{ $assets->lastPage() }},
                from: {{ $assets->firstItem() }},
                to: {{ $assets->lastItem() }},
                total: {{ $assets->total() }}
            });
        @endif
    </script>
    @endpush

<!-- Add Asset Modal -->
<div class="modal" id="addAssetModal">
    <div class="modal-backdrop" onclick="closeModal('addAssetModal')"></div>
    <div class="modal-content max-w-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-display font-bold text-premium">Add New Asset</h2>
            <button onclick="closeModal('addAssetModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('hr.assets.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Asset Reference</label>
                    <input type="text" name="asset_ref" class="premium-input w-full px-4 py-3 text-sm" placeholder="e.g. LAP-001" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Asset Name</label>
                    <input type="text" name="asset_name" class="premium-input w-full px-4 py-3 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Category</label>
                    <select name="category_id" class="premium-input w-full px-4 py-3 text-sm" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Serial Number</label>
                    <input type="text" name="asset_serial" class="premium-input w-full px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                    <textarea name="asset_description" rows="2" class="premium-input w-full px-4 py-3 text-sm"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('addAssetModal')" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">Add Asset</button>
            </div>
        </form>
    </div>
</div>

<!-- Assign Modal -->
<div class="modal" id="assignModal">
    <div class="modal-backdrop" onclick="closeModal('assignModal')"></div>
    <div class="modal-content max-w-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-display font-bold text-premium">Assign Asset</h2>
            <button onclick="closeModal('assignModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="assignForm" method="POST">
            @csrf
            <p class="mb-4 text-sm text-slate-600">Assigning <strong id="assignAssetName" class="text-slate-800"></strong> to:</p>
            <div class="mb-4">
                <select name="assigned_to" class="premium-input w-full px-4 py-3 text-sm" required>
                    <option value="0">Unequip (Return to Stock)</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">Update Assignment</button>
        </form>
    </div>
</div>

<script>
    function openAssignModal(id, name) {
        document.getElementById('assignAssetName').innerText = name;
        document.getElementById('assignForm').action = "/hr/assets/" + id + "/update";
        openModal('assignModal');
    }
</script>
</script>
@endsection
