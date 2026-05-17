@extends('layouts.app')

@section('title', 'External Entities Management')
@section('subtitle', 'Manage external entities for inbound correspondence')

@section('content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-premium">External Entities Management</h2>
            <p class="text-sm text-slate-500 mt-1">Manage external entities</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openEntityModal()"
                class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                <i class="fa-solid fa-plus"></i>
                <span>Add Entity</span>
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="premium-card p-6">
        <div class="overflow-x-auto">
            <table class="premium-table w-full" id="entitiesTable">
                <thead>
                    <tr>
                        <th class="text-left font-bold text-slate-400">Entity Name</th>
                        <th class="text-left font-bold text-slate-400">Code</th>
                        <th class="text-left font-bold text-slate-400">Contact Person</th>
                        <th class="text-left font-bold text-slate-400">Email</th>
                        <th class="text-left font-bold text-slate-400">Phone</th>
                        <th class="text-center font-bold text-slate-400">Status</th>
                        <th class="text-center font-bold text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50" id="entitiesTbody">
                    <tr>
                        <td colspan="7" class="text-center py-10">
                            <i class="fa-solid fa-spinner fa-spin text-slate-400 text-2xl"></i>
                            <p class="text-slate-500 mt-2">Loading entities...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        {{-- Pagination --}}
        <div class="mt-4 flex justify-between items-center" id="paginationControls"></div>
    </div>
</div>

{{-- ─── Modal: Add/Edit Entity ───────────────────────────────────────── --}}
<div class="modal" id="entityModal" style="display: none;">
    <div class="modal-backdrop" onclick="closeModal('entityModal')"></div>
    <div class="modal-content max-w-2xl p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-display font-bold text-premium" id="modalTitle">Add External Entity</h2>
            <button onclick="closeModal('entityModal')"
                class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>

        <form id="entityForm" method="POST" action="">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">External Entity Name <span class="text-red-500">*</span></label>
                    <input type="text" name="entity_name" id="entity_name" class="premium-input w-full px-4 py-3 text-sm" required>
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Entity Code <span class="text-red-500">*</span></label>
                    <input type="text" name="entity_code" id="entity_code" class="premium-input w-full px-4 py-3 text-sm uppercase" maxlength="10" required>
                </div>
                
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Contact Person</label>
                    <input type="text" name="contact_person" id="contact_person" class="premium-input w-full px-4 py-3 text-sm">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                    <input type="email" name="entity_email" id="entity_email" class="premium-input w-full px-4 py-3 text-sm">
                </div>
                
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Phone</label>
                    <input type="text" name="entity_phone" id="entity_phone" class="premium-input w-full px-4 py-3 text-sm">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Emirate</label>
                    <select name="emirate_id" id="emirate_id" class="premium-input w-full px-4 py-3 text-sm">
                        <option value="">— Select Emirate —</option>
                        @foreach($emirates as $emirate)
                            <option value="{{ $emirate->city_id }}">{{ $emirate->city_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Category</label>
                    <select name="category_id" id="category_id" class="premium-input w-full px-4 py-3 text-sm">
                        <option value="">— Select Category —</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->item_id }}">{{ $category->item_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Type</label>
                    <select name="type_id" id="type_id" class="premium-input w-full px-4 py-3 text-sm">
                        <option value="">— Select Type —</option>
                        @foreach($types as $type)
                            <option value="{{ $type->item_id }}">{{ $type->item_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer mt-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" class="w-5 h-5 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500" checked>
                        <span class="text-sm font-semibold text-slate-700">Is Active?</span>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('entityModal')"
                    class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                <button type="submit"
                    class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                    Save Entity
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentPage = 1;
    
    document.addEventListener('DOMContentLoaded', function() {
        loadEntities();
    });

    function loadEntities(page = 1) {
        currentPage = page;
        fetch(`{{ route('emp.external-entities.data') }}?page=${page}`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('entitiesTbody');
                tbody.innerHTML = '';
                
                if(data.data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="7" class="text-center py-10 text-slate-500">No entities found.</td></tr>`;
                } else {
                    data.data.forEach(entity => {
                        const activeBadge = entity.is_active 
                            ? '<span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Active</span>'
                            : '<span class="px-2 py-1 bg-rose-100 text-rose-700 text-xs font-bold rounded-full">Inactive</span>';
                            
                        tbody.innerHTML += `
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="font-semibold text-slate-700 text-sm">${entity.entity_name}</td>
                                <td><span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">${entity.entity_code}</span></td>
                                <td class="text-sm text-slate-600">${entity.contact_person || '-'}</td>
                                <td class="text-sm text-slate-600">${entity.entity_email || '-'}</td>
                                <td class="text-sm text-slate-600">${entity.entity_phone || '-'}</td>
                                <td class="text-center">${activeBadge}</td>
                                <td class="text-center">
                                    <button onclick='editEntity(${JSON.stringify(entity).replace(/'/g, "\\'")})' class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-100 transition-colors mx-auto">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                }
                
                renderPagination(data);
            });
    }

    function renderPagination(data) {
        const controls = document.getElementById('paginationControls');
        let html = `<div class="text-sm text-slate-500">Showing ${data.from || 0} to ${data.to || 0} of ${data.total} results</div>`;
        
        if (data.last_page > 1) {
            html += `<div class="flex items-center gap-1">`;
            for(let i=1; i<=data.last_page; i++) {
                const activeCls = i === data.current_page ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200';
                html += `<button onclick="loadEntities(${i})" class="w-8 h-8 rounded-lg ${activeCls} font-semibold text-sm flex items-center justify-center transition-colors">${i}</button>`;
            }
            html += `</div>`;
        }
        
        controls.innerHTML = html;
    }

    function openEntityModal() {
        document.getElementById('modalTitle').innerText = 'Add External Entity';
        document.getElementById('entityForm').reset();
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('entityForm').action = "{{ route('emp.external-entities.store') }}";
        document.getElementById('is_active').checked = true;
        document.getElementById('entityModal').style.display = 'flex';
    }

    function editEntity(entity) {
        document.getElementById('modalTitle').innerText = 'Edit External Entity';
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('entityForm').action = `/emp/external-entities/${entity.entity_id}`;
        
        document.getElementById('entity_name').value = entity.entity_name || '';
        document.getElementById('entity_code').value = entity.entity_code || '';
        document.getElementById('contact_person').value = entity.contact_person || '';
        document.getElementById('entity_email').value = entity.entity_email || '';
        document.getElementById('entity_phone').value = entity.entity_phone || '';
        document.getElementById('emirate_id').value = entity.emirate_id || '';
        document.getElementById('category_id').value = entity.category_id || '';
        document.getElementById('type_id').value = entity.type_id || '';
        document.getElementById('is_active').checked = entity.is_active == 1;
        
        document.getElementById('entityModal').style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>

@if(session('success') || session('error') || $errors->any())
<div id="toast-message" class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-4 {{ session('error') || $errors->any() ? 'bg-rose-600' : 'bg-emerald-600' }} text-white rounded-xl shadow-2xl animate-fade-in-up">
    <i class="fa-solid {{ session('error') || $errors->any() ? 'fa-circle-exclamation' : 'fa-circle-check' }} text-lg"></i>
    <div class="font-semibold text-sm">
        @if(session('success')) {{ session('success') }} @endif
        @if(session('error')) {{ session('error') }} @endif
        @if($errors->any())
            <ul class="list-disc pl-4 mt-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        @endif
    </div>
    <button onclick="document.getElementById('toast-message').remove()" class="ml-2 text-white/70 hover:text-white self-start">
        <i class="fa-solid fa-times"></i>
    </button>
</div>
<script>setTimeout(() => { const t = document.getElementById('toast-message'); if(t) t.remove(); }, 6000);</script>
@endif

@endsection
