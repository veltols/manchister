@extends('layouts.app')

@section('title', 'System Users')
@section('subtitle', 'Manage Employee Access')

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold font-display text-premium">Users</h2>
            <button onclick="openModal('newUserModal')" class="premium-button px-6 py-2.5">
                <i class="fa-solid fa-plus mr-2"></i> Add User
            </button>
        </div>

        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-xs">
                        <tr>
                            <th class="p-4 border-b border-slate-100">IQC ID</th>
                            <th class="p-4 border-b border-slate-100">Name</th>
                            <th class="p-4 border-b border-slate-100">Email</th>
                            <th class="p-4 border-b border-slate-100">Type</th>
                            <th class="p-4 border-b border-slate-100 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($users as $user)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-4 font-mono text-slate-600">{{ $user->employee_no }}</td>
                                <td class="p-4 font-bold text-slate-700">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                        </div>
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </div>
                                </td>
                                <td class="p-4 text-slate-500">{{ $user->login_email }}</td>
                                <td class="p-4">
                                    <span
                                        class="px-2 py-1 rounded text-xs font-bold {{ $user->user_type == 'admin' ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }}">
                                        {{ strtoupper($user->user_type) }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('admin.users.show', $user->user_id) }}"
                                        class="inline-block w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 hover:text-indigo-600 transition-colors flex items-center justify-center">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- New User Modal -->
    <div id="newUserModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal('newUserModal')"></div>
        <div class="modal-content w-full max-w-2xl">
            <div class="bg-white">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="font-bold text-lg text-premium">Add New User</h3>
                    <button onclick="closeModal('newUserModal')" class="text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div class="p-8">
                    @if($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
                            <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
                            <div class="flex-1">
                                <ul class="text-sm font-medium text-red-800 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <form action="{{ route('admin.users.store') }}" method="POST"
                        class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">IQC ID /
                                Employee No</label>
                            <input type="text" name="employee_no" class="premium-input w-full" required>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Department</label>
                            <select name="department_id" class="premium-input w-full" required>
                                @foreach(\App\Models\Department::orderBy('department_name')->get() as $dept)
                                    <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">User
                                Type/Role</label>
                            <select name="user_type" class="premium-input w-full" required>
                                <option value="emp">Employee</option>
                                <option value="hr">HR Manager</option>
                                <option value="admin_hr">Admin HR</option>
                                <option value="sys_admin">System Admin</option>
                                <option value="eqa">EQA Officer</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">First
                                Name</label>
                            <input type="text" name="first_name" class="premium-input w-full" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Last
                                Name</label>
                            <input type="text" name="last_name" class="premium-input w-full" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email
                                (Login ID)</label>
                            <input type="email" name="email" class="premium-input w-full" required>
                        </div>

                        <div class="md:col-span-2">
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Password</label>
                            <input type="text" name="password" class="premium-input w-full" required
                                placeholder="Enter temporary password">
                        </div>

                        <div class="md:col-span-2 pt-4 flex gap-4">
                            <button type="submit" class="premium-button flex-1 py-3">Create User</button>
                            <button type="button" onclick="closeModal('newUserModal')"
                                class="py-3 px-6 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-colors">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <script>
            @if($errors->any())
                openModal('newUserModal');
            @endif
        </script>
    @endsection
@endsection