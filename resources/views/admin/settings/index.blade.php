@extends('layouts.app')

@section('title', 'System Settings')
@section('subtitle', 'Manage system lists and configurations')

@section('content')
    <div class="flex flex-col md:flex-row gap-6 animate-fade-in-up">
        
        <!-- Sidebar Navigation for Settings -->
        <div class="w-full md:w-64 flex-shrink-0">
            <div class="premium-card p-4 space-y-1">
                <p class="px-4 py-2 text-xs font-bold text-slate-400 uppercase tracking-widest">General Lists</p>
                
                <a href="{{ route('admin.settings.index', ['type' => 'tc']) }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ $type == 'tc' ? 'bg-brand/10 text-brand' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-tags w-5"></i> Ticket Categories
                </a>
                <a href="{{ route('admin.settings.index', ['type' => 'ts']) }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ $type == 'ts' ? 'bg-brand/10 text-brand' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-list-check w-5"></i> Ticket Status
                </a>
                <a href="{{ route('admin.settings.index', ['type' => 'lt']) }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ $type == 'lt' ? 'bg-brand/10 text-brand' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-calendar-minus w-5"></i> Leave Types
                </a>
                 <a href="{{ route('admin.settings.index', ['type' => 'pp']) }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ $type == 'pp' ? 'bg-brand/10 text-brand' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-flag w-5"></i> Priorities
                </a>

                <div class="border-t border-slate-100 my-2 pt-2"></div>
                <p class="px-4 py-2 text-xs font-bold text-slate-400 uppercase tracking-widest">Asset Config</p>
                 <a href="{{ route('admin.settings.index', ['type' => 'ac']) }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ $type == 'ac' ? 'bg-brand/10 text-brand' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-boxes-stacked w-5"></i> Asset Categories
                </a>

                 <div class="border-t border-slate-100 my-2 pt-2"></div>
                <p class="px-4 py-2 text-xs font-bold text-slate-400 uppercase tracking-widest">Workflows</p>
                 <a href="{{ route('admin.settings.index', ['type' => 'ss']) }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ $type == 'ss' ? 'bg-brand/10 text-brand' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-share-nodes w-5"></i> Service Categories
                </a>
                 <a href="{{ route('admin.settings.index', ['type' => 'ct']) }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ $type == 'ct' ? 'bg-brand/10 text-brand' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-envelope-open-text w-5"></i> Comm. Types
                </a>
                 <div class="border-t border-slate-100 my-2 pt-2"></div>
                <p class="px-4 py-2 text-xs font-bold text-slate-400 uppercase tracking-widest">Theming</p>
                <a href="{{ route('admin.settings.index', ['type' => 'ult']) }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ $type == 'ult' ? 'bg-brand/10 text-brand' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-palette w-5"></i> Themes
                </a>
                <a href="{{ route('admin.settings.index', ['type' => 'branding']) }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ $type == 'branding' ? 'bg-brand/10 text-brand' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-copyright w-5"></i> Branding & Logo
                </a>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 space-y-6 min-w-0">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">{{ $type === 'branding' ? 'Branding & Logo' : $conf['title'] }}</h2>
                    <p class="text-sm text-slate-500">{{ $type === 'branding' ? 'Manage application branding and appearance' : 'Manage list items' }}</p>
                </div>
                @if($type !== 'branding')
                <button onclick="openModal('addModal')" 
                    class="premium-button bg-gradient-brand text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md shadow-brand/20 hover:shadow-brand/40 hover:scale-105 transition-all w-full md:w-auto">
                    <i class="fa-solid fa-plus mr-2"></i> Add New
                </button>
                @endif
            </div>

             <!-- List Table (Responsive) -->
            @if($type === 'branding')
                <div class="premium-card p-6">
                    <form action="{{ route('admin.settings.branding') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Logo Section -->
                            <div>
                                <h3 class="text-lg font-bold text-slate-800 mb-2">Application Logo</h3>
                                <p class="text-sm text-slate-500 mb-4">Recommended size: 200x50px. Max size: 2MB.</p>
                                
                                <div class="relative group">
                                    <div class="h-32 w-full bg-slate-50 rounded-xl border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden relative">
                                        <img id="logo-preview" src="{{ isset($logo) && $logo ? asset('uploads/' . $logo) : '' }}" class="{{ isset($logo) && $logo ? '' : 'hidden' }} h-16 w-auto object-contain">
                                        
                                        <div id="logo-placeholder" class="{{ isset($logo) && $logo ? 'hidden' : '' }} text-slate-400">Default Logo</div>

                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">Change Logo</span>
                                        </div>
                                    </div>
                                    <input type="file" name="logo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" onchange="previewImage(this, 'logo-preview', 'logo-placeholder')">
                                </div>
                            </div>

                            <!-- Favicon Section -->
                            <div>
                                <h3 class="text-lg font-bold text-slate-800 mb-2">Favicon</h3>
                                <p class="text-sm text-slate-500 mb-4">Recommended size: 32x32px or 64x64px. Max size: 1MB.</p>
                                
                                    <div class="relative group">
                                        <div class="h-32 w-full bg-slate-50 rounded-xl border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden relative">
                                            <img id="favicon-preview" src="{{ isset($favicon) && $favicon ? asset('uploads/' . $favicon) : '' }}" class="{{ isset($favicon) && $favicon ? '' : 'hidden' }} h-16 w-16 object-contain">
                                            
                                            <div id="favicon-placeholder" class="{{ isset($favicon) && $favicon ? 'hidden' : '' }} w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center">
                                                <i class="fa-solid fa-cube text-indigo-500 text-2xl"></i>
                                            </div>

                                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <span class="text-white text-sm font-medium">Change Favicon</span>
                                            </div>
                                        </div>
                                        <input type="file" name="favicon" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" onchange="previewImage(this, 'favicon-preview', 'favicon-placeholder')">
                                    </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6">
                            <button type="submit" class="premium-button bg-gradient-brand text-white px-8 py-3 rounded-xl shadow-lg shadow-brand/20 font-bold hover:scale-105 transition-all">
                                <i class="fa-solid fa-save mr-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            @else
             <!-- List Table (Responsive) -->
            <div class="premium-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="premium-table w-full whitespace-nowrap">
                        <thead>
                            <tr>
                                <th class="text-left w-16">ID</th>
                                @foreach($conf['fields'] as $key => $label)
                                    @php $l = explode('|', $label)[0]; @endphp
                                    <th class="text-left px-4">{{ $l }}</th>
                                @endforeach
                                <th class="text-center w-24">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="settings-container-desktop">
                            @forelse($records as $record)
                                <tr>
                                    <td class="text-slate-500 font-mono text-xs">{{ $record->{$conf['pk']} }}</td>
                                    
                                    @foreach($conf['fields'] as $key => $label)
                                        @php
                                            $parts = explode('|', $label);
                                            $typeMeta = $parts[1] ?? 'text';
                                        @endphp
                                        <td class="px-4">
                                            @if($typeMeta == 'employee')
                                                @php 
                                                    $empId = $record->$key;
                                                    $emp = $employees->firstWhere('employee_id', $empId);
                                                @endphp
                                                @if($emp)
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-xs text-slate-500 font-bold">
                                                            {{ substr($emp->first_name, 0, 1) }}
                                                        </div>
                                                        <span class="text-sm text-slate-700">{{ $emp->first_name }} {{ $emp->last_name }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-slate-400 italic text-xs">Unassigned</span>
                                                @endif
                                            @elseif($typeMeta == 'number')
                                                <span class="font-mono text-slate-600">{{ $record->$key }}</span>
                                            @elseif($typeMeta == 'color')
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-md shadow-sm border border-slate-200" style="background-color: #{{ str_replace('#', '', $record->$key) }}"></div>
                                                    <span class="font-mono text-xs text-slate-500">#{{ str_replace('#', '', $record->$key) }}</span>
                                                </div>
                                            @else
                                                <span class="text-slate-800 font-medium">{{ $record->$key }}</span>
                                            @endif
                                        </td>
                                    @endforeach

                                    <td class="text-center">
                                        <button onclick="openEditModal({{ json_encode($record) }}, {{ json_encode($conf['pk']) }})" 
                                            class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md"
                                            title="Edit Record">
                                            <i class="fa-solid fa-pen text-sm"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($conf['fields']) + 2 }}" class="text-center py-8 text-slate-500">
                                        No records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Shared Pagination -->
            @if($type !== 'branding' && isset($records))
                <div id="settings-pagination">
                    @if($records->hasPages())
                        <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                            {{ $records->links() }}
                        </div>
                    @endif
                </div>
            @endif

        </div>

    </div>

    <!-- Add Modal -->
    @if($type !== 'branding')
    <div id="addModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('addModal')"></div>
        <div class="modal-content max-w-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-slate-800">Add New {{ rtrim($conf['title'], 's') }}</h3>
                <button onclick="closeModal('addModal')" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-times text-lg"></i>
                </button>
            </div>
            <form action="{{ route('admin.settings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_type" value="{{ $type }}">
                
                <div class="space-y-4">
                    @foreach($conf['fields'] as $key => $label)
                        @php
                            $parts = explode('|', $label);
                            $l = $parts[0];
                            $meta = $parts[1] ?? 'text';
                        @endphp
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">{{ $l }}</label>
                            
                            @if($meta == 'employee')
                                <select name="{{ $key }}" class="premium-input w-full px-4 py-2.5 text-sm" required>
                                    <option value="0">Select Employee...</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                    @endforeach
                                </select>
                            @elseif($meta == 'number')
                                                <input type="number" name="{{ $key }}" class="premium-input w-full px-4 py-2.5 text-sm" required>
                            @elseif($meta == 'color')
                                <div class="flex items-center gap-2">
                                    <input type="color" name="{{ $key }}" class="h-10 w-20 p-1 rounded-lg border border-slate-200 cursor-pointer" required>
                                    <input type="text" oninput="this.previousElementSibling.value = this.value" placeholder="#HEXCODE" class="premium-input w-32 px-4 py-2.5 text-sm uppercase">
                                </div>
                            @else
                                <input type="text" name="{{ $key }}" class="premium-input w-full px-4 py-2.5 text-sm" required>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('addModal')" class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100 font-medium">Cancel</button>
                    <button type="submit" class="premium-button bg-gradient-brand text-white px-6 py-2 rounded-lg shadow-md shadow-brand/20 font-semibold hover:scale-105 transition-all">Save</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('editModal')"></div>
        <div class="modal-content max-w-lg p-6">
             <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-slate-800">Edit Record</h3>
                <button onclick="closeModal('editModal')" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-times text-lg"></i>
                </button>
            </div>
            
            <form id="editForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_type" value="{{ $type }}">
                <div id="editFields" class="space-y-4">
                    <!-- Dynamic Fields populated by JS -->
                </div>
                 <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100 font-medium">Cancel</button>
                    <button type="submit" class="premium-button bg-gradient-brand text-white px-6 py-2 rounded-lg shadow-md shadow-brand/20 font-semibold hover:scale-105 transition-all">Update</button>
                </div>
            </form>
        </div>
    </div>
    @endif


    @push('scripts')
    <script src="{{ asset('js/ajax-pagination.js') }}"></script>
    <script>
        function previewImage(input, previewId, placeholderId) {
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/x-icon', 'image/vnd.microsoft.icon'];
                const maxSize = 2 * 1024 * 1024; // 2MB default

                // Validate Type
                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Please upload a valid image file (JPG, PNG, GIF, ICO).',
                        confirmButtonColor: '#4f46e5'
                    });
                    input.value = '';
                    return;
                }

                // Validate Size
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'The image size must not exceed 2MB.',
                        confirmButtonColor: '#4f46e5'
                    });
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if(placeholder) placeholder.classList.add('hidden');
                }
                
                reader.readAsDataURL(file);
            }
        }
        // Config passed from PHP for JS use
        @if($type !== 'branding')
            const fieldsConfig = @json($conf['fields']);
            const pkName = @json($conf['pk']);
        @else
            const fieldsConfig = {};
            const pkName = '';
        @endif
        const employees = @json($employees);
        const settingType = "{{ $type }}";

        function openEditModal(record, pkName) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            const container = document.getElementById('editFields');
            
            // Set update route
            form.action = "{{ route('admin.settings.index') }}" + '/' + record[pkName];

            // Clear previous fields
            container.innerHTML = '';

            // Generate fields
            for (const [key, labelInfo] of Object.entries(fieldsConfig)) {
                const parts = labelInfo.split('|');
                const label = parts[0];
                const meta = parts[1] || 'text';
                const value = record[key];

                let fieldHtml = `<div><label class="block text-sm font-semibold text-slate-700 mb-2">${label}</label>`;

                if (meta === 'employee') {
                    fieldHtml += `<select name="${key}" class="premium-input w-full px-4 py-2.5 text-sm" required>`;
                    fieldHtml += `<option value="0">Select Employee...</option>`;
                     employees.forEach(emp => {
                        const selected = emp.employee_id == value ? 'selected' : '';
                        fieldHtml += `<option value="${emp.employee_id}" ${selected}>${emp.first_name} ${emp.last_name}</option>`;
                    });
                    fieldHtml += `</select>`;
                } else if (meta === 'color') {
                    fieldHtml += `<div class="flex items-center gap-2">`;
                    fieldHtml += `<input type="color" name="${key}" value="#${(value || '').replace('#', '')}" class="h-10 w-20 p-1 rounded-lg border border-slate-200 cursor-pointer" required>`;
                    fieldHtml += `<input type="text" oninput="this.previousElementSibling.value = this.value" value="${value || ''}" class="premium-input w-32 px-4 py-2.5 text-sm uppercase">`;
                    fieldHtml += `</div>`;
                } else {
                    const inputType = meta === 'number' ? 'number' : 'text';
                    fieldHtml += `<input type="${inputType}" name="${key}" value="${value || ''}" class="premium-input w-full px-4 py-2.5 text-sm" required>`;
                }
                
                fieldHtml += `</div>`;
                container.insertAdjacentHTML('beforeend', fieldHtml);
            }

            modal.classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        window.ajaxPagination = new AjaxPagination({
            endpoint: "{{ route('admin.settings.data', ['type' => $type]) }}",
            containerSelector: '#settings-container-desktop', // Primary container
            paginationSelector: '#settings-pagination',
            perPage: 15,
            renderCallback: function(records) {
                const desktopContainer = document.querySelector('#settings-container-desktop');
                
                if (records.length === 0) {
                    desktopContainer.innerHTML = `
                        <tr>
                            <td colspan="${Object.keys(fieldsConfig).length + 2}" class="text-center py-8 text-slate-500">
                                No records found.
                            </td>
                        </tr>
                    `;
                    return;
                }
                
                let desktopHtml = '';

                records.forEach(record => {
                    const recordJson = JSON.stringify(record).replace(/"/g, '&quot;');

                    // --- Desktop Row Construction ---
                    desktopHtml += `<tr>`;
                    desktopHtml += `<td class="text-slate-500 font-mono text-xs">${record[pkName]}</td>`;
                    
                    for (const [key, labelInfo] of Object.entries(fieldsConfig)) {
                         const meta = labelInfo.split('|')[1] || 'text';
                        const value = record[key];
                        
                        desktopHtml += `<td class="px-4">`;
                        if (meta === 'employee') {
                            const emp = employees.find(e => e.employee_id == value);
                            if (emp) {
                                desktopHtml += `
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-xs text-slate-500 font-bold">
                                            ${emp.first_name.charAt(0)}
                                        </div>
                                        <span class="text-sm text-slate-700">${emp.first_name} ${emp.last_name}</span>
                                    </div>
                                `;
                            } else {
                                desktopHtml += `<span class="text-slate-400 italic text-xs">Unassigned</span>`;
                            }
                        } else if (meta === 'number') {
                             desktopHtml += `<span class="font-mono text-slate-600">${value}</span>`;
                        } else if (meta === 'color') {
                            desktopHtml += `
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-md shadow-sm border border-slate-200" style="background-color: #${(value||'').replace('#','')}"></div>
                                    <span class="font-mono text-xs text-slate-500">#${(value||'').replace('#','')}</span>
                                </div>
                            `;
                        } else {
                             desktopHtml += `<span class="text-slate-800 font-medium">${value || ''}</span>`;
                        }
                        desktopHtml += `</td>`;
                    }
                    
                    desktopHtml += `
                        <td class="text-center">
                            <button onclick="openEditModal(${recordJson}, '${pkName}')" 
                                class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center hover:scale-110 transition-all shadow-md"
                                title="Edit Record">
                                <i class="fa-solid fa-pen text-sm"></i>
                            </button>
                        </td>
                    `;
                    desktopHtml += `</tr>`;
                });
                
                desktopContainer.innerHTML = desktopHtml;
            }

        });

        // Initialize pagination helper with server-side data for first load
        @if($type !== 'branding' && isset($records) && $records->hasPages())
            window.ajaxPagination.renderPagination({
                current_page: {{ $records->currentPage() }},
                last_page: {{ $records->lastPage() }},
                from: {{ $records->firstItem() ?? 0 }},
                to: {{ $records->lastItem() ?? 0 }},
                total: {{ $records->total() }}
            });
        @endif
    </script>
    @endpush
@endsection
