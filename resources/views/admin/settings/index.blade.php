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
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ $type == 'tc' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-tags w-5"></i> Ticket Categories
                </a>
                <a href="{{ route('admin.settings.index', ['type' => 'ts']) }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ $type == 'ts' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-list-check w-5"></i> Ticket Status
                </a>
                <a href="{{ route('admin.settings.index', ['type' => 'lt']) }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ $type == 'lt' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-calendar-minus w-5"></i> Leave Types
                </a>
                 <a href="{{ route('admin.settings.index', ['type' => 'pp']) }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ $type == 'pp' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-flag w-5"></i> Priorities
                </a>

                <div class="border-t border-slate-100 my-2 pt-2"></div>
                <p class="px-4 py-2 text-xs font-bold text-slate-400 uppercase tracking-widest">Asset Config</p>
                 <a href="{{ route('admin.settings.index', ['type' => 'ac']) }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ $type == 'ac' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-boxes-stacked w-5"></i> Asset Categories
                </a>

                 <div class="border-t border-slate-100 my-2 pt-2"></div>
                <p class="px-4 py-2 text-xs font-bold text-slate-400 uppercase tracking-widest">Workflows</p>
                 <a href="{{ route('admin.settings.index', ['type' => 'ss']) }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ $type == 'ss' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-share-nodes w-5"></i> Service Categories
                </a>
                 <a href="{{ route('admin.settings.index', ['type' => 'ct']) }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ $type == 'ct' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-envelope-open-text w-5"></i> Comm. Types
                </a>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 space-y-6">
            
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-display font-bold text-premium">{{ $conf['title'] }}</h2>
                    <p class="text-sm text-slate-500">Manage list items</p>
                </div>
                <button onclick="openModal('addModal')" 
                    class="premium-button from-indigo-600 to-purple-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md hover:shadow-lg transition-all">
                    <i class="fa-solid fa-plus mr-2"></i> Add New
                </button>
            </div>

            <!-- List Table -->
            <div class="premium-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="premium-table w-full">
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
                        <tbody>
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
                                            @else
                                                <span class="text-slate-800 font-medium">{{ $record->$key }}</span>
                                            @endif
                                        </td>
                                    @endforeach

                                    <td class="text-center">
                                        <button onclick="openEditModal({{ json_encode($record) }}, {{ json_encode($conf['pk']) }})" 
                                            class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center">
                                            <i class="fa-solid fa-pen"></i>
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

        </div>

    </div>

    <!-- Add Modal -->
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
                            @else
                                <input type="text" name="{{ $key }}" class="premium-input w-full px-4 py-2.5 text-sm" required>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('addModal')" class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-100 font-medium">Cancel</button>
                    <button type="submit" class="premium-button from-indigo-600 to-purple-600 text-white px-6 py-2 rounded-lg shadow-md font-semibold">Save</button>
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
                    <button type="submit" class="premium-button from-indigo-600 to-purple-600 text-white px-6 py-2 rounded-lg shadow-md font-semibold">Update</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Config passed from PHP for JS use
        const fieldsConfig = @json($conf['fields']);
        const employees = @json($employees);

        function openEditModal(record, pkName) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            const container = document.getElementById('editFields');
            
            // Set update route
            form.action = "{{ route('admin.settings.store') }}".replace('store', 'update') + '/' + record[pkName];

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
                } else {
                    const inputType = meta === 'number' ? 'number' : 'text';
                    fieldHtml += `<input type="${inputType}" name="${key}" value="${value}" class="premium-input w-full px-4 py-2.5 text-sm" required>`;
                }
                
                fieldHtml += `</div>`;
                container.insertAdjacentHTML('beforeend', fieldHtml);
            }

            modal.classList.add('active');
        }
    </script>
    @endpush

@endsection
