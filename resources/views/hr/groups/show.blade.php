@extends('layouts.app')

@section('title', $group->group_name)
@section('subtitle', 'Space for ' . ($group->is_commity == 1 ? 'Committee' : 'Group'))

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <!-- Group Header Card -->
        <div class="premium-card p-0 overflow-hidden">
            <div class="h-32 bg-gradient-to-r from-indigo-900 to-purple-900 p-8 flex items-end justify-between">
                <div class="flex items-center gap-6">
                    <div
                        class="w-20 h-20 rounded-2xl bg-white shadow-xl flex items-center justify-center text-indigo-900 -mb-10 relative z-20 border-4 border-slate-50">
                        <i class="fa-solid fa-people-group text-3xl"></i>
                    </div>
                    <div class="text-white pb-2">
                        <h1 class="text-3xl font-display font-bold">{{ $group->group_name }}</h1>
                        <p class="text-white/70 text-sm font-medium">{{ $group->is_commity == 1 ? 'Committee' : 'Internal Group' }} workspace</p>
                    </div>
                </div>
                <div class="flex gap-3 pb-2">
                    <button onclick="openModal('uploadFileModal')"
                        class="px-6 py-2.5 bg-white/20 hover:bg-white/30 backdrop-blur-md rounded-xl text-white text-xs font-bold border border-white/30 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        Upload File
                    </button>
                    <a href="{{ route('hr.groups.index') }}" class="px-6 py-2.5 bg-white/20 hover:bg-white/30 backdrop-blur-md rounded-xl text-white text-xs font-bold border border-white/30 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back to List
                    </a>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="bg-white px-8 pt-10 pb-0 border-b border-slate-100">
                <div class="flex gap-8">
                    <button onclick="switchTab('feed')"
                        class="tab-btn active border-b-2 border-indigo-600 pb-4 font-bold text-sm text-slate-800 transition-all"
                        id="btn-feed">Discussion Feed</button>
                    <button onclick="switchTab('files')"
                        class="tab-btn border-b-2 border-transparent pb-4 font-bold text-sm text-slate-400 hover:text-slate-600 transition-all"
                        id="btn-files">Resources & Files</button>
                    <button onclick="switchTab('members')"
                        class="tab-btn border-b-2 border-transparent pb-4 font-bold text-sm text-slate-400 hover:text-slate-600 transition-all"
                        id="btn-members">Team Members</button>
                    <button onclick="switchTab('agenda')"
                        class="tab-btn border-b-2 border-transparent pb-4 font-bold text-sm text-slate-400 hover:text-slate-600 transition-all"
                        id="btn-agenda">Agenda</button>
                </div>
            </div>
        </div>

        <!-- Tab Contents -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <!-- Main Content (Feed / Files / Members) -->
            <div class="lg:col-span-2">

                <!-- Feed Tab -->
                <div id="tab-feed" class="tab-content space-y-6">
                    <!-- Post Box -->
                    <div class="premium-card p-6 border-indigo-600/10 bg-slate-50/50">
                        <form action="{{ route('hr.groups.post.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="group_id" value="{{ $group->group_id }}">
                            <textarea name="post_text" rows="3"
                                class="premium-input w-full bg-white border-slate-200 focus:bg-white transition-all shadow-sm"
                                placeholder="Share something with the group..." required></textarea>
                            <div class="mt-4 flex justify-end">
                                <button type="submit" class="premium-button">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    <span>Post Message</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Posts List -->
                    <div class="space-y-6">
                        @forelse($group->posts as $post)
                            <div class="premium-card p-0 overflow-hidden border-slate-50">
                                <div class="p-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-bold uppercase text-xs">
                                            {{ substr($post->author->first_name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-premium">
                                                {{ $post->author->first_name ?? 'Unknown' }}
                                                {{ $post->author->last_name ?? '' }}</h4>
                                            <span
                                                class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $post->added_date }}</span>
                                        </div>
                                    </div>
                                    <div class="text-slate-700 leading-relaxed">
                                        {!! nl2br(e($post->post_text)) !!}
                                    </div>

                                    @if($post->post_type == 'document')
                                        <div
                                            class="mt-6 p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between group/file">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-red-500">
                                                    <i class="fa-solid fa-file-pdf"></i>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-sm font-bold text-slate-700">{{ $post->post_file_name }}</span>
                                                    <span
                                                        class="text-[10px] text-slate-400 uppercase font-bold tracking-tighter">Attachment</span>
                                                </div>
                                            </div>
                                            <a href="{{ asset($post->post_file_path) }}" target="_blank"
                                                class="w-10 h-10 rounded-full bg-white border border-slate-200 text-slate-400 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                                <i class="fa-solid fa-download text-xs"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="py-20 text-center text-slate-400 italic">
                                No messages posted in this group yet.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Files Tab -->
                <div id="tab-files" class="tab-content hidden space-y-6">
                    <div class="premium-card overflow-hidden">
                        <table class="premium-table w-full">
                            <thead>
                                <tr>
                                    <th class="text-left font-bold text-slate-400">File Name</th>
                                    <th class="text-left font-bold text-slate-400">Uploaded By</th>
                                    <th class="text-left font-bold text-slate-400">Date</th>
                                    <th class="text-center font-bold text-slate-400">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($group->files as $file)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <i class="fa-solid fa-file-invoice text-indigo-600"></i>
                                                <span class="font-bold text-slate-700">{{ $file->file_name }}</span>
                                            </div>
                                        </td>
                                        <td><span
                                                class="text-sm font-medium text-slate-500">{{ $file->uploader->first_name ?? 'User' }}</span>
                                        </td>
                                        <td><span class="text-xs font-bold text-slate-400">{{ $file->added_date }}</span></td>
                                        <td class="text-center">
                                            <a href="{{ asset($file->file_path) }}" target="_blank"
                                                class="text-indigo-600 hover:scale-110 transition-transform inline-block">
                                                <i class="fa-solid fa-circle-down text-xl"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-20 text-slate-400">No resources shared yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Members Tab -->
                <div id="tab-members" class="tab-content hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($group->members as $member)
                        <div
                            class="premium-card p-6 flex items-center justify-between hover:bg-slate-50 transition-all border-slate-50">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold uppercase">
                                    {{ substr($member->employee->first_name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-premium">{{ $member->employee->first_name ?? '' }}
                                        {{ $member->employee->last_name ?? '' }}</h4>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        {{ $member->role->group_role_name ?? 'Member' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="premium-card p-6 flex items-center justify-center border-dashed cursor-pointer hover:bg-slate-50 transition-all" onclick="openModal('addMemberModal')">
                        <div class="text-center">
                            <i class="fa-solid fa-plus text-slate-300 text-xl mb-2"></i>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Add Member</p>
                        </div>
                    </div>
                </div>

                <!-- Agenda Tab -->
                <div id="tab-agenda" class="tab-content hidden space-y-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-premium">Agenda</h3>
                        <button onclick="openModal('addAgendaModal')" class="premium-button py-2 px-4 text-xs">
                            <i class="fa-solid fa-plus"></i> New Agenda
                        </button>
                    </div>

                    <div class="space-y-4">
                        @forelse($group->agendas as $agenda)
                            <div class="premium-card p-6 border-l-4 border-l-indigo-600 cursor-pointer hover:shadow-md transition-shadow" onclick="openAgendaDetails({{ $agenda->toJson() }})">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-lg text-slate-800">{{ $agenda->title }}</h4>
                                    <div class="flex gap-2">
                                        <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider
                                            @if($agenda->priority == 'High' || $agenda->priority == 'Critical') bg-red-100 text-red-700
                                            @elseif($agenda->priority == 'Medium') bg-amber-100 text-amber-700
                                            @else bg-blue-100 text-blue-700 @endif">
                                            {{ $agenda->priority }}
                                        </span>
                                        <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider
                                            @if($agenda->status == 'Completed') bg-green-100 text-green-700
                                            @elseif($agenda->status == 'In Discussion') bg-amber-100 text-amber-700
                                            @else bg-slate-100 text-slate-700 @endif">
                                            {{ $agenda->status }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex gap-4 text-xs text-slate-500 font-medium">
                                    @if($agenda->start_date)
                                        <span><i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($agenda->start_date)->format('M d, Y h:i A') }}</span>
                                    @endif
                                    @if($agenda->time_duration)
                                        <span><i class="fa-regular fa-clock mr-1"></i> {{ $agenda->time_duration }}</span>
                                    @endif
                                    <span><i class="fa-regular fa-user mr-1"></i> {{ $agenda->creator->first_name ?? 'User' }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center bg-white rounded-3xl border border-slate-100 shadow-sm border-dashed">
                                <i class="fa-regular fa-calendar-check text-4xl text-slate-200 mb-4"></i>
                                <h3 class="font-bold text-slate-400">No agendas created yet</h3>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <div class="premium-card p-8 bg-slate-50">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6">About Workspace</h3>
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                {{ $group->group_description ?: 'No official description provided.' }}
                            </p>
                        </div>
                        <div class="pt-6 border-t border-slate-200 grid grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-slate-300 uppercase">Created On</span>
                                <span class="text-sm font-bold text-slate-700">{{ $group->added_date }}</span>
                            </div>
                            <div class="flex flex-col text-right">
                                <span class="text-[10px] font-bold text-slate-300 uppercase">Status</span>
                                <span class="text-sm font-bold text-green-600">Active</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="premium-card p-6 border-indigo-600/20 border-2">
                    <h4 class="text-sm font-bold text-premium mb-4 uppercase tracking-widest">Team Stats</h4>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Active Members</span>
                            <span class="font-bold text-slate-800">{{ $group->members->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Shared Files</span>
                            <span class="font-bold text-indigo-600">{{ $group->files->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Upload Modal -->
    <div class="modal" id="uploadFileModal">
        <div class="modal-backdrop" onclick="closeModal('uploadFileModal')"></div>
        <div class="modal-content max-w-lg p-8">
            <h2 class="text-2xl font-display font-bold text-premium mb-6">Share Resource</h2>
            <form action="{{ route('hr.groups.file.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                <input type="hidden" name="group_id" value="{{ $group->group_id }}">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Display
                        Name</label>
                    <input type="text" name="file_name" class="premium-input w-full" placeholder="e.g. Project Plan 2024"
                        required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Target File</label>
                    <input type="file" name="uploaded_file" class="premium-input w-full text-xs" required>
                </div>
                <div class="flex justify-end gap-3 pt-6">
                    <button type="button" onclick="closeModal('uploadFileModal')"
                        class="px-6 py-2 text-slate-500 font-bold">Cancel</button>
                    <button type="submit" class="premium-button">Upload & Notify</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Member Modal -->
    <div class="modal" id="addMemberModal">
        <div class="modal-backdrop" onclick="closeModal('addMemberModal')"></div>
        <div class="modal-content max-w-md p-0 border-none">
            <div class="p-6 bg-slate-900 text-white flex justify-between items-center rounded-t-[24px]">
                <h2 class="text-xl font-bold">Add Team Member</h2>
                <button onclick="closeModal('addMemberModal')" class="text-white/60 hover:text-white"><i
                        class="fa-solid fa-times"></i></button>
            </div>
            <form class="p-8 space-y-4" action="{{ route('hr.groups.member.store') }}" method="POST">
                @csrf
                <input type="hidden" name="group_id" value="{{ $group->group_id }}">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Select
                        Employee</label>
                    <select name="employee_id" required class="premium-input w-full h-11 text-sm">
                        <option value="">Search Employee...</option>
                        @foreach($employees as $emp)
                            @if(!$group->members->contains('employee_id', $emp->employee_id))
                                <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Assign Role</label>
                    <select name="group_role_id" required class="premium-input w-full h-11 text-sm">
                        @foreach($roles as $role)
                            <option value="{{ $role->group_role_id }}">{{ $role->group_role_name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="w-full premium-button from-indigo-600 to-purple-600 text-white font-bold py-3 rounded-2xl shadow-lg shadow-indigo-100 mt-4 justify-center">Add
                    to Team</button>
            </form>
        </div>
    </div>

    <!-- Add Agenda Modal -->
    <div class="modal" id="addAgendaModal">
        <div class="modal-backdrop" onclick="closeModal('addAgendaModal')"></div>
        <div class="modal-content max-w-2xl p-8 max-h-[90vh] overflow-y-auto">
            <h2 class="text-2xl font-display font-bold text-premium mb-6">Create New Agenda</h2>
            <form action="{{ route('hr.groups.agenda.store', $group->group_id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Agenda Title</label>
                    <input type="text" name="title" class="premium-input w-full" required>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Priority</label>
                        <select name="priority" class="premium-input w-full">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                            <option value="Critical">Critical</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Start Date/Time</label>
                        <input type="datetime-local" name="start_date" class="premium-input w-full">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description / Context</label>
                    <textarea name="description" rows="3" class="premium-input w-full"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100 mt-6">
                    <button type="button" onclick="closeModal('addAgendaModal')" class="px-6 py-2 text-slate-500 font-bold">Cancel</button>
                    <button type="submit" class="premium-button">Create Agenda</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit/View Agenda Modal -->
    <div class="modal" id="editAgendaModal">
        <div class="modal-backdrop" onclick="closeModal('editAgendaModal')"></div>
        <div class="modal-content max-w-2xl p-8 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-display font-bold text-premium">Agenda Details</h2>
                <form id="deleteAgendaForm" method="POST" onsubmit="return confirm('Are you sure you want to delete this agenda?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 w-8 h-8 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </form>
            </div>
            
            <form id="editAgendaForm" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Agenda Title</label>
                    <input type="text" name="title" id="edit_title" class="premium-input w-full" required>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Priority</label>
                        <select name="priority" id="edit_priority" class="premium-input w-full">
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                            <option value="Critical">Critical</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Status</label>
                        <select name="status" id="edit_status" class="premium-input w-full" onchange="toggleCompletionFields()">
                            <option value="Pending">Pending</option>
                            <option value="In Discussion">In Discussion</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Start Date/Time</label>
                        <input type="datetime-local" name="start_date" id="edit_start_date" class="premium-input w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Time Duration</label>
                        <input type="text" name="time_duration" id="edit_time_duration" class="premium-input w-full">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description / Context</label>
                    <textarea name="description" id="edit_description" rows="3" class="premium-input w-full"></textarea>
                </div>

                <div id="completion_fields" class="space-y-4 mt-6 pt-6 border-t border-slate-100 hidden">
                    <h3 class="text-sm font-bold text-premium mb-4 uppercase tracking-widest">Completion Details</h3>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">End Date/Time</label>
                        <input type="datetime-local" name="end_date" id="edit_end_date" class="premium-input w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Decision / Outcome</label>
                        <textarea name="decision_outcome" id="edit_decision_outcome" rows="3" class="premium-input w-full" placeholder="What was decided?"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Action Items</label>
                        <div id="edit_action_items_container" class="space-y-2">
                            <!-- Action item rows will be added here -->
                        </div>
                        <button type="button" onclick="addActionItemRow('edit_action_items_container')" class="mt-3 text-xs font-bold text-indigo-600 flex items-center gap-2 hover:text-indigo-800 transition-all">
                            <i class="fa-solid fa-plus-circle"></i> Add Action Item
                        </button>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100 mt-6">
                    <button type="button" onclick="closeModal('editAgendaModal')" class="px-6 py-2 text-slate-500 font-bold">Cancel</button>
                    <button type="submit" class="premium-button">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            // Toggle contents
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('tab-' + tab).classList.remove('hidden');

            // Toggle buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active', 'border-indigo-600', 'text-slate-800');
                btn.classList.add('border-transparent', 'text-slate-400');
            });
            document.getElementById('btn-' + tab).classList.add('active', 'border-indigo-600', 'text-slate-800');
            document.getElementById('btn-' + tab).classList.remove('border-transparent', 'text-slate-400');
        }

        function openAgendaDetails(agenda) {
            document.getElementById('edit_title').value = agenda.title || '';
            document.getElementById('edit_priority').value = agenda.priority || 'Medium';
            document.getElementById('edit_status').value = agenda.status || 'Pending';
            
            if(agenda.start_date) {
                document.getElementById('edit_start_date').value = agenda.start_date.substring(0, 16);
            } else {
                document.getElementById('edit_start_date').value = '';
            }
            
            document.getElementById('edit_time_duration').value = agenda.time_duration || '';
            document.getElementById('edit_description').value = agenda.description || '';
            
            if(agenda.end_date) {
                document.getElementById('edit_end_date').value = agenda.end_date.substring(0, 16);
            } else {
                document.getElementById('edit_end_date').value = '';
            }
            
            document.getElementById('edit_decision_outcome').value = agenda.decision_outcome || '';
            
            // Populate Dynamic Action Items
            const itemsContainer = document.getElementById('edit_action_items_container');
            itemsContainer.innerHTML = '';
            const items = (agenda.action_items || '').split('\n').filter(i => i.trim() !== '');
            if (items.length > 0) {
                items.forEach(item => addActionItemRow('edit_action_items_container', item));
            } else {
                addActionItemRow('edit_action_items_container');
            }
            
            document.getElementById('editAgendaForm').action = "{{ url('hr/groups/'.$group->group_id.'/agenda') }}/" + agenda.agenda_id;
            document.getElementById('deleteAgendaForm').action = "{{ url('hr/groups/'.$group->group_id.'/agenda') }}/" + agenda.agenda_id;
            
            toggleCompletionFields();
            openModal('editAgendaModal');
        }

        function toggleCompletionFields() {
            const status = document.getElementById('edit_status').value;
            const completionFields = document.getElementById('completion_fields');
            if(status === 'Completed') {
                completionFields.classList.remove('hidden');
                if(!document.getElementById('edit_end_date').value) {
                    const now = new Date();
                    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                    document.getElementById('edit_end_date').value = now.toISOString().slice(0,16);
                }
            } else {
                completionFields.classList.add('hidden');
            }
        }

        function addActionItemRow(containerId, value = '') {
            const container = document.getElementById(containerId);
            const rowId = 'action-item-' + Date.now() + Math.floor(Math.random() * 1000);
            
            const div = document.createElement('div');
            div.id = rowId;
            div.className = 'flex items-center gap-2 mb-2 animate-fade-in';
            div.innerHTML = `
                <input type="text" class="premium-input flex-1 py-2 text-sm action-item-input" value="${value}" placeholder="Add action item...">
                <button type="button" onclick="removeActionItemRow('${rowId}')" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm">
                    <i class="fa-solid fa-times text-xs"></i>
                </button>
            `;
            container.appendChild(div);
        }

        function removeActionItemRow(rowId) {
            const row = document.getElementById(rowId);
            if (row) row.remove();
        }

        document.getElementById('editAgendaForm').addEventListener('submit', function(e) {
            const container = document.getElementById('edit_action_items_container');
            if (container) {
                const actionItems = Array.from(container.querySelectorAll('.action-item-input'))
                    .map(input => input.value.trim())
                    .filter(val => val !== '')
                    .join('\n');
                
                let hiddenInput = document.getElementById('final_action_items');
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'action_items';
                    hiddenInput.id = 'final_action_items';
                    this.appendChild(hiddenInput);
                }
                hiddenInput.value = actionItems;
            }
        });
    </script>
@endsection
