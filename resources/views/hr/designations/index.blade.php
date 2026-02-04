@extends('layouts.app')

@section('title', 'Designations')
@section('subtitle', 'Manage job titles and hierarchy')

@section('content')
    <div class="space-y-6">

        <!-- Structure Navigation -->
        @include('hr.partials.structure_nav')

        <!-- Header with Action Button -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-premium">Designations</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $designations->total() }} total designations</p>
            </div>
            <button onclick="openModal('addDesignationModal')"
                class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                <i class="fa-solid fa-plus"></i>
                <span>Add Designation</span>
            </button>
        </div>

        <!-- Designations Table -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="premium-table w-full">
                    <thead>
                        <tr>
                            <th class="text-left">Ref</th>
                            <th class="text-left">Code</th>
                            <th class="text-left">Name</th>
                            <th class="text-left">Department</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($designations as $designation)
                            <tr>
                                <td><span
                                        class="font-mono text-sm font-semibold text-slate-600">#{{ $designation->designation_id }}</span>
                                </td>
                                <td><span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-800 text-sm font-bold">{{ $designation->designation_code }}</span>
                                </td>
                                <td><span class="font-semibold text-slate-800">{{ $designation->designation_name }}</span></td>
                                <td>
                                    @if($designation->department)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-purple-50 text-purple-700 text-sm font-medium">
                                            <i class="fa-solid fa-building text-xs"></i>
                                            {{ $designation->department->department_name }}
                                        </span>
                                    @else
                                        <span class="text-red-400 text-sm italic">No Department</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-center">
                                        <button
                                            onclick="editDesignation({{ $designation->designation_id }}, '{{ addslashes($designation->designation_code) }}', '{{ addslashes($designation->designation_name) }}', {{ $designation->department_id ?? 0 }})"
                                            class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md"
                                            title="Edit">
                                            <i class="fa-solid fa-pen text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                            <i class="fa-solid fa-briefcase text-2xl text-slate-400"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">No designations found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($designations->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $designations->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Add Designation Modal -->
    <div class="modal" id="addDesignationModal">
        <div class="modal-backdrop" onclick="closeModal('addDesignationModal')"></div>
        <div class="modal-content p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-display font-bold text-premium">Add New Designation</h2>
                <button onclick="closeModal('addDesignationModal')"
                    class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route(request()->is('admin*') ? 'admin.designations.store' : 'hr.designations.store') }}"
                method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Code</label>
                        <input type="text" name="designation_code" class="premium-input w-full px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Name</label>
                        <input type="text" name="designation_name" class="premium-input w-full px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Department</label>
                        <select name="department_id" class="premium-input w-full px-4 py-3 text-sm" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->department_id }}">{{ $d->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Remarks</label>
                        <textarea name="log_remark" class="premium-input w-full px-4 py-3 text-sm" rows="3"
                            placeholder="Optional remarks..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('addDesignationModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                    <button type="submit"
                        class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Designation Modal -->
    <div class="modal" id="editDesignationModal">
        <div class="modal-backdrop" onclick="closeModal('editDesignationModal')"></div>
        <div class="modal-content p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-display font-bold text-premium">Edit Designation</h2>
                <button onclick="closeModal('editDesignationModal')"
                    class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form id="editDesignationForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Code</label>
                        <input type="text" name="designation_code" id="edit_designation_code"
                            class="premium-input w-full px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Name</label>
                        <input type="text" name="designation_name" id="edit_designation_name"
                            class="premium-input w-full px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Department</label>
                        <select name="department_id" id="edit_department_id" class="premium-input w-full px-4 py-3 text-sm"
                            required>
                            <option value="">Select Department</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->department_id }}">{{ $d->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Remarks <span
                                class="text-red-500">*</span></label>
                        <textarea name="log_remark" id="edit_designation_remark"
                            class="premium-input w-full px-4 py-3 text-sm" rows="3" required
                            placeholder="Reason for update..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal('editDesignationModal')"
                        class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                    <button type="submit"
                        class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">Update</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function editDesignation(id, code, name, deptId) {
                document.getElementById('edit_designation_code').value = code;
                document.getElementById('edit_designation_name').value = name;
                document.getElementById('edit_department_id').value = deptId;
                document.getElementById('edit_designation_remark').value = "";

                let prefix = window.location.pathname.startsWith('/admin') ? '/admin' : '/hr';
                document.getElementById('editDesignationForm').action = prefix + "/designations/" + id;
                openModal('editDesignationModal');
            }
        </script>
    @endpush
@endsection