@extends('layouts.app')

@section('title', $isCom ? 'Committees' : 'Groups')
@section('subtitle', 'Internal collaboration and project teams')

@section('content')
@php
$authUser = Auth::user(); 
@endphp
    <div class="groups-layout">
        <!-- Sidebar: Groups List -->
        <div class="groups-sidebar">
            <div class="sidebar-header">
                <h2 class="text-xl font-bold text-premium">{{ $isCom == 2 ? 'PMO (Ad-hoc)' : ($isCom == 1 ? 'Committees' : 'Groups') }}</h2>
                <div class="flex gap-2">
                    @if($authUser && $authUser->is_gm)
                    <button onclick="openModal('newGroupModal')"
                        class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    @endif
                    <a href="{{ request('archived') ? route('hr.groups.index', ['c' => request('c')]) : route('hr.groups.index', ['c' => request('c'), 'archived' => 1]) }}"
                        class="w-10 h-10 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center hover:bg-slate-100 transition-all shadow-sm"
                        title="{{ request('archived') ? 'Show Active Groups' : 'Show Archived Groups' }}">
                        <i class="fa-solid {{ request('archived') ? 'fa-folder-open text-indigo-600' : 'fa-box-archive' }}"></i>
                    </a>
                </div>
            </div>

            <div class="px-4 pt-4">
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="groupSearch" placeholder="Search teams..." class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl pl-9 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-slate-600" onkeyup="filterGroups()">
                </div>
            </div>

            <div class="groups-list space-y-3 p-4">
                @forelse($groups as $group)
                    <div onclick="loadGroup({{ $group->group_id }})" id="group-item-{{ $group->group_id }}"
                        class="group-card relative p-4 rounded-2xl bg-white border border-slate-100 shadow-sm cursor-pointer hover:shadow-md hover:border-indigo-200 transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-sm"
                                style="background: {{ $group->color->color_value ?? '#6366f1' }}">
                                {{ $group->initials }}
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                                    {{ $group->group_name }}
                                </h3>
                                <p class="text-xs text-slate-500 line-clamp-1">{{ $group->group_desc }}</p>
                            </div>
                            <div class="active-indicator w-1.5 h-8 rounded-full bg-indigo-600 opacity-0 transition-opacity">
                            </div>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2" onclick="event.stopPropagation()">
                                <button onclick="toggleGroupMenu(event, {{ $group->group_id }})" class="w-8 h-8 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 flex items-center justify-center transition-colors relative z-10">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <div id="group-menu-{{ $group->group_id }}" class="hidden absolute right-0 top-full mt-1 w-36 bg-white rounded-xl shadow-xl border border-slate-100 z-50 py-1">
                                    @if(request('archived'))
                                    <button onclick="restoreGroupList(event, {{ $group->group_id }})" class="w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-emerald-600 flex items-center gap-2">
                                        <i class="fa-solid fa-trash-arrow-up w-4"></i> Restore
                                    </button>
                                    @else
                                    <button onclick="archiveGroupList(event, {{ $group->group_id }})" class="w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-amber-600 flex items-center gap-2">
                                        <i class="fa-solid fa-box-archive w-4"></i> Archive
                                    </button>
                                    @endif
                                    @if($group->added_by == (Auth::user()->employee->employee_id ?? 0))
                                    <button onclick="duplicateGroupList(event, {{ $group->group_id }})" class="w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 flex items-center gap-2">
                                        <i class="fa-solid fa-copy w-4"></i> Duplicate
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <div
                            class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <i class="fa-solid fa-user-group text-2xl"></i>
                        </div>
                        <p class="text-slate-400 text-sm">No {{ $isCom == 2 ? 'PMO (Ad-hoc)' : ($isCom == 1 ? 'committees' : 'groups') }} found</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Main Content: Group Details -->
        <div class="groups-main">
            <div id="selection-placeholder"
                class="h-full flex flex-col items-center justify-center p-12 text-center animate-fade-in">
                <div
                    class="w-32 h-32 bg-indigo-50 rounded-full flex items-center justify-center mb-8 text-indigo-500 shadow-inner">
                    <i class="fa-solid fa-comments-question text-5xl"></i>
                </div>
                <h2 class="text-2xl font-display font-bold text-premium mb-4">Select a Team</h2>
                <p class="text-slate-500 max-w-sm">Choose a {{ $isCom ? 'committee' : 'group' }} from the sidebar to view
                    its activity, files, and members.</p>
            </div>

            <div id="group-content" class="hidden h-full flex flex-col animate-fade-in">
                <!-- Group Header -->
                <div class="main-header p-6 bg-white border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div id="header-avatar"
                            class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-bold text-2xl shadow-indigo-200 shadow-lg">
                        </div>
                        <div>
                            <h2 id="header-name" class="text-2xl font-display font-bold text-premium"></h2>
                            <span id="header-type"
                                class="px-2 py-0.5 rounded-lg bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-wider"></span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button
                            class="px-4 py-2 bg-slate-50 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-100 transition-all border border-slate-100">
                            <i class="fa-solid fa-gear mr-2"></i>Settings
                        </button>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="main-tabs px-6 bg-white border-b border-slate-100 flex gap-8">
                    <button onclick="switchGroupTab('wall')" class="group-tab active" data-tab="wall">The Wall</button>
                    <button onclick="switchGroupTab('files')" class="group-tab" data-tab="files">Resources</button>
                    <button onclick="switchGroupTab('members')" class="group-tab" data-tab="members">Team Members</button>
                    <button onclick="switchGroupTab('details')" class="group-tab" data-tab="details">Information</button>
                    <button onclick="switchGroupTab('agenda')" class="group-tab hidden" data-tab="agenda" id="btn-tab-agenda">Agenda</button>
                </div>

                <!-- Tab Content Area -->
                <div class="flex-1 overflow-y-auto p-6 bg-slate-50/30">
                    <!-- Wall Tab -->
                    <div id="tab-wall" class="tab-pane active flex flex-col h-full  mx-auto">
                        <!-- Posts Stream -->
                        <div id="posts-stream" class="flex-1 space-y-6 overflow-y-auto mb-6">
                            <!-- Dynamic content -->
                        </div>

                        <!-- Post Composer -->
                        <div class="premium-card p-6 border-none shadow-lg mt-auto sticky bottom-0 bg-white/80 backdrop-blur-md z-10">
                            <!-- Attachment Preview Container -->
                            <div id="group-attachment-preview" class="mb-4"></div>
                            
                            <div class="flex gap-4">
                                <div
                                    class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                    {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}</div>
                                <div class="flex-1 space-y-4">
                                    <textarea id="post-text" placeholder="Share something with the team..."
                                        class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-slate-700 focus:ring-2 focus:ring-indigo-500 transition-all resize-none h-20"></textarea>
                                    <div class="flex justify-between items-center">
                                        <div class="flex gap-2">
                                            <label class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all cursor-pointer">
                                                <i class="fa-solid fa-paperclip"></i>
                                                <input type="file" id="group_attachment" class="hidden">
                                            </label>
                                        </div>
                                        <button onclick="submitPost()"
                                            class="premium-button from-indigo-600 to-purple-600 text-white px-6 py-2 rounded-xl font-bold text-sm shadow-md">
                                            Post Update
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Files Tab -->
                    <div id="tab-files" class="tab-pane hidden">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-display font-bold text-premium">Team Library</h3>
                            <button onclick="openModal('uploadFileModal')"
                                class="premium-button from-cyan-500 to-blue-600 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-md">
                                <i class="fa-solid fa-cloud-arrow-up mr-2"></i>Upload File
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="files-grid">
                            <!-- Dynamic content -->
                        </div>
                    </div>

                    <!-- Members Tab -->
                    <div id="tab-members" class="tab-pane hidden">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-display font-bold text-premium">Member Directory</h3>
                            <button onclick="openModal('addMemberModal')"
                                class="premium-button from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-md">
                                <i class="fa-solid fa-plus mr-2"></i>Add Member
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="members-list">
                            <!-- Dynamic content -->
                        </div>
                    </div>

                    <!-- Agendas Tab -->
                    <div id="tab-agenda" class="tab-pane hidden">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-display font-bold text-premium">Agenda</h3>
                            <button onclick="openModal('addAgendaModal')"
                                class="premium-button from-emerald-500 to-teal-600 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-md">
                                <i class="fa-solid fa-plus mr-2"></i>New Agenda
                            </button>
                        </div>
                        <div class="space-y-4" id="agendas-list">
                            <!-- Dynamic content -->
                        </div>
                    </div>

                    <!-- Details Tab -->
                    <div id="tab-details" class="tab-pane hidden max-w-2xl mx-auto">
                        <div class="premium-card p-8 border-none shadow-lg">
                            <h3 class="text-2xl font-display font-bold text-premium mb-6">About this Team</h3>
                            <div class="space-y-6">
                                <div>
                                    <label
                                        class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Description</label>
                                    <p id="details-desc" class="text-slate-700 leading-relaxed"></p>
                                </div>
                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Created
                                            By</label>
                                        <p id="details-creator" class="font-bold text-slate-800">Admin Account</p>
                                    </div>
                                    <div>
                                        <label
                                            class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Team
                                            ID</label>
                                        <p id="details-id" class="font-mono text-slate-500 font-bold"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- New Group Modal -->
    <div class="modal" id="newGroupModal">
        <div class="modal-backdrop" onclick="closeModal('newGroupModal')"></div>
        <div class="modal-content max-w-lg p-0 border-none shadow-2xl">
            <div
                class="p-6 bg-gradient-to-r from-indigo-900 to-purple-900 text-white flex justify-between items-center rounded-t-[24px]">
                <div>
                    <h2 class="text-2xl font-display font-bold leading-none">New {{ $isCom == 2 ? 'PMO (Ad-hoc)' : ($isCom == 1 ? 'Committee' : 'Group') }}</h2>
                    <p class="text-indigo-100/60 text-xs mt-1">Configure your new team space</p>
                </div>
                <button onclick="closeModal('newGroupModal')"
                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10 transition-colors">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <form id="newGroupForm" class="p-8 space-y-6" onsubmit="saveGroup(event)">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <label class="flex items-center gap-3 p-4 rounded-2xl border-2 border-slate-100 cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 group">
                            <input type="radio" name="is_com" value="0" {{ $isCom == 0 ? 'checked' : '' }} class="hidden">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 group-has-[:checked]:bg-indigo-600 group-has-[:checked]:text-white">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <span class="font-bold text-slate-600 group-has-[:checked]:text-indigo-900">Standard Group</span>
                        </label>
                        <label class="flex items-center gap-3 p-4 rounded-2xl border-2 border-slate-100 cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-purple-600 has-[:checked]:bg-purple-50 group">
                            <input type="radio" name="is_com" value="1" {{ $isCom == 1 ? 'checked' : '' }} class="hidden">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 group-has-[:checked]:bg-purple-600 group-has-[:checked]:text-white">
                                <i class="fa-solid fa-award"></i>
                            </div>
                            <span class="font-bold text-slate-600 group-has-[:checked]:text-purple-900">Committee</span>
                        </label>
                        <label class="flex items-center gap-3 p-4 rounded-2xl border-2 border-slate-100 cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50 group">
                            <input type="radio" name="is_com" value="2" {{ $isCom == 2 ? 'checked' : '' }} class="hidden">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 group-has-[:checked]:bg-emerald-600 group-has-[:checked]:text-white">
                                <i class="fa-solid fa-project-diagram"></i>
                            </div>
                            <span class="font-bold text-slate-600 group-has-[:checked]:text-emerald-900">PMO (Ad-hoc)</span>
                        </label>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Team Name</label>
                        <input type="text" name="group_name" required class="premium-input w-full"
                            placeholder="e.g., Marketing Strategy Unit">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Brand Color</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($colors as $color)
                                <div onclick="selectColor({{ $color->color_id }}, '{{ $color->color_value }}')"
                                    class="color-option w-10 h-10 rounded-xl cursor-pointer hover:scale-110 transition-transform shadow-sm flex items-center justify-center border-4 border-transparent"
                                    style="background: {{ $color->color_value }}" data-color-id="{{ $color->color_id }}">
                                    <i class="fa-solid fa-check text-white opacity-0 transition-opacity"></i>
                                </div>
                            @endforeach
                            <input type="hidden" name="group_color_id" id="selected-color-id" required>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Purpose &
                            Description</label>
                        <textarea name="group_desc" rows="4" class="premium-input w-full"
                            placeholder="What is the objective of this team?"></textarea>
                    </div>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeModal('newGroupModal')"
                        class="flex-1 px-6 py-3 rounded-2xl border-2 border-slate-100 text-slate-500 font-bold hover:bg-slate-50 transition-all">Cancel</button>
                    <button type="submit"
                        class="flex-[2] premium-button from-indigo-600 to-purple-600 text-white font-bold rounded-2xl shadow-xl shadow-indigo-200 justify-center">Initialize Team Space</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Upload File Modal -->
    <div class="modal" id="uploadFileModal">
        <div class="modal-backdrop" onclick="closeModal('uploadFileModal')"></div>
        <div class="modal-content max-w-md p-0 border-none">
            <div class="p-6 bg-slate-900 text-white flex justify-between items-center rounded-t-[24px]">
                <h2 class="text-xl font-bold">Add Resource</h2>
                <button onclick="closeModal('uploadFileModal')" class="text-white/60 hover:text-white"><i
                        class="fa-solid fa-times"></i></button>
            </div>
            <form class="p-8 space-y-4" onsubmit="uploadGroupFile(event)">
                <input type="hidden" name="group_id" class="active-group-id">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">File Alias</label>
                    <input type="text" name="file_name" required class="premium-input w-full h-11 text-sm"
                        placeholder="Financial Report 2024">
                </div>
                <div class="p-8 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl text-center">
                    <input type="file" name="uploaded_file" required id="file-input" class="hidden">
                    <label for="file-input" class="cursor-pointer group flex flex-col items-center">
                        <div
                            class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-slate-300 shadow-sm border border-slate-100 mb-3 group-hover:text-cyan-500 transition-colors">
                            <i class="fa-solid fa-file-export text-2xl"></i>
                        </div>
                        <span class="text-sm font-bold text-slate-600">Choose file or drag here</span>
                    </label>
                </div>
                <button type="submit"
                    class="w-full premium-button from-cyan-500 to-blue-600 text-white font-bold py-3 rounded-2xl shadow-lg shadow-cyan-100 justify-center">Upload
                    to Library</button>
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
            <form class="p-8 space-y-4" onsubmit="addNewMember(event)">
                <input type="hidden" name="group_id" class="active-group-id">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Select
                        Employee</label>
                    <select name="employee_id" required class="premium-input w-full h-11 text-sm">
                        <option value="">Search Employee...</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
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
            <form id="addAgendaForm" onsubmit="submitAgenda(event, 'hr')" class="space-y-4">
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
                <div class="flex gap-2">
                    <button type="button" onclick="exportSingleAgendaToPDF()" class="text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1 rounded-lg flex items-center text-xs font-bold transition-all">
                        <i class="fa-solid fa-file-pdf mr-2"></i>Export PDF
                    </button>
                    <button type="button" onclick="deleteAgenda()" class="text-red-500 hover:text-red-700 bg-red-50 w-8 h-8 rounded-full flex items-center justify-center transition-all">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </div>
            </div>
            
            <form id="editAgendaForm" onsubmit="updateAgenda(event)" class="space-y-4">
                @csrf
                <input type="hidden" id="edit_agenda_id" value="">
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
                        <select name="status" id="edit_status" class="premium-input w-full" onchange="toggleCompletionFieldsHR()">
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

                <div id="hr_completion_fields" class="space-y-4 mt-6 pt-6 border-t border-slate-100 hidden">
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
                        <textarea name="action_items" id="edit_action_items" rows="3" class="premium-input w-full" placeholder="List action items assigned..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100 mt-6">
                    <button type="button" onclick="closeModal('editAgendaModal')" class="px-6 py-2 text-slate-500 font-bold">Cancel</button>
                    <button type="submit" class="premium-button">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Print-only container for PDF export -->
    <div id="print-agenda-container" class="hidden"></div>

    <style>
        .groups-layout {
            display: grid;
            grid-template-columns: 320px 1fr;
            height: calc(100vh - 145px);
            background: white;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .groups-sidebar {
            border-right: 1px solid #f1f5f9;
            background: #fbfcfd;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar-header {
            padding: 20px;
            background: white;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .groups-list {
            overflow-y: auto;
            flex: 1;
        }

        .group-card.active {
            background-color: white;
            border-color: #e0e7ff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .group-card.active .active-indicator {
            opacity: 1;
        }

        .group-card.active h3 {
            color: #4f46e5;
        }

        .groups-main {
            background: white;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .group-tab {
            padding: 1rem 0.5rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: #64748b;
            transition: all 0.2s;
            position: relative;
            border-bottom: 2px solid transparent;
            cursor: pointer;
        }

        .group-tab.active {
            color: #4f46e5;
            border-bottom-color: #4f46e5;
        }

        .color-option.selected {
            border-color: white;
            box-shadow: 0 0 0 2px #4f46e5;
        }

        .color-option.selected i {
            opacity: 1;
        }

        .animate-scale-in {
            animation: scaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>

    <script src="{{ asset('libs/mammoth/mammoth.browser.min.js') }}"></script>
    <script src="{{ asset('js/attachment-preview.js') }}"></script>
    <script>
        let activeGroupId = null;

        // Initialize Attachment Preview
        const wallPreview = window.initAttachmentPreview({
            inputSelector: '#group_attachment',
            containerSelector: '#group-attachment-preview'
        });

        // Use global openModal and closeModal from app.blade.php
        // These are just wrappers if needed, but the ones in app.blade.php work by toggling .active

        function selectColor(colorId, colorValue) {
            document.getElementById('selected-color-id').value = colorId;
            document.querySelectorAll('.color-option').forEach(opt => {
                opt.classList.remove('selected');
                if (opt.getAttribute('data-color-id') == colorId) opt.classList.add('selected');
            });
        }

        async function loadGroup(id) {
            activeGroupId = id;

            // Update UI states
            document.querySelectorAll('.group-card').forEach(c => c.classList.remove('active'));
            document.getElementById(`group-item-${id}`).classList.add('active');
            document.querySelectorAll('.active-group-id').forEach(i => i.value = id);

            document.getElementById('selection-placeholder').classList.add('hidden');
            document.getElementById('group-content').classList.remove('hidden');

            try {
                const response = await fetch(`{{ url('hr/groups') }}/${id}`);
                const result = await response.json();

                if (result.success) {
                    const group = result.data;
                    const initials = group.group_name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();

                    // Handle Agenda Tab Visibility (Only for Committees)
                    const agendaTabBtn = document.getElementById('btn-tab-agenda');
                    if (group.is_commity == 1) {
                        agendaTabBtn.classList.remove('hidden');
                    } else {
                        agendaTabBtn.classList.add('hidden');
                    }

                    // Set Header
                    document.getElementById('header-name').innerText = group.group_name;
                    document.getElementById('header-type').innerText = group.is_commity == 2 ? 'PMO (Ad-hoc)' : (group.is_commity == 1 ? 'Committee' : 'Internal Group');
                    const avatar = document.getElementById('header-avatar');
                    avatar.innerText = initials;
                    avatar.style.background = group.color ? group.color.color_value : '#6366f1';

                    // Set Details
                    document.getElementById('details-desc').innerText = group.group_desc || 'No description provided for this team.';
                    document.getElementById('details-id').innerText = `TEAM-${group.group_id.toString().padStart(4, '0')}`;
                    const creatorLabel = document.getElementById('details-creator');
                    if(creatorLabel) creatorLabel.innerText = group.creator ? group.creator.first_name + ' ' + group.creator.last_name : 'System';

                    // Build Posts
                    renderPosts(group.posts);

                    // Determine Current User
                    const currentUserId = {{ Auth::user()->employee->employee_id ?? 0 }};

                    // Build Members
                    renderMembers(group.members, currentUserId);

                    // Build Files
                    renderFiles(group.files);

                    // Build Agendas
                    renderAgendas(group.agendas);

                    switchGroupTab('wall');
                }
            } catch (error) {
                console.error('Error loading group:', error);
            }
        }

        function switchGroupTab(tabName) {
            document.querySelectorAll('.group-tab').forEach(t => {
                t.classList.remove('active');
                if (t.getAttribute('data-tab') === tabName) t.classList.add('active');
            });

            document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
            document.getElementById(`tab-${tabName}`).classList.remove('hidden');
        }

        function renderAgendas(agendas) {
            window.currentAgendas = agendas;
            const list = document.getElementById('agendas-list');
            list.innerHTML = '';
            
            if (!agendas || agendas.length === 0) {
                list.innerHTML = `
                    <div class="py-12 text-center bg-white rounded-3xl border border-slate-100 shadow-sm border-dashed">
                        <i class="fa-regular fa-calendar-check text-4xl text-slate-200 mb-4"></i>
                        <h3 class="font-bold text-slate-400">No agendas created yet</h3>
                    </div>`;
                return;
            }

            agendas.forEach(agenda => {
                let priorityClass = 'bg-blue-100 text-blue-700';
                if(agenda.priority === 'High' || agenda.priority === 'Critical') priorityClass = 'bg-red-100 text-red-700';
                else if(agenda.priority === 'Medium') priorityClass = 'bg-amber-100 text-amber-700';

                let statusClass = 'bg-slate-100 text-slate-700';
                if(agenda.status === 'Completed') statusClass = 'bg-green-100 text-green-700';
                else if(agenda.status === 'In Discussion') statusClass = 'bg-amber-100 text-amber-700';

                const html = `
                    <div class="premium-card p-6 border-l-4 border-l-brand-dark cursor-pointer hover:shadow-md transition-shadow" onclick='openAgendaDetails(${agenda.agenda_id})'>
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-bold text-lg text-slate-800">${agenda.title}</h4>
                            <div class="flex items-center gap-2">
                                <button onclick="event.stopPropagation(); exportSingleAgendaToPDFById(${agenda.agenda_id})" class="w-8 h-8 rounded-lg bg-slate-50 text-red-500 hover:bg-red-50 flex items-center justify-center transition-all mr-2" title="Export PDF">
                                    <i class="fa-solid fa-file-pdf text-sm"></i>
                                </button>
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider ${priorityClass}">
                                    ${agenda.priority}
                                </span>
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider ${statusClass}">
                                    ${agenda.status}
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-4 text-xs text-slate-500 font-medium">
                            ${agenda.start_date ? `<span><i class="fa-regular fa-calendar mr-1"></i> Start: ${new Date(agenda.start_date).toLocaleString()}</span>` : ''}
                            ${agenda.status === 'Completed' && agenda.end_date ? `<span class="text-emerald-600"><i class="fa-solid fa-flag-checkered mr-1"></i> End: ${new Date(agenda.end_date).toLocaleString()}</span>` : ''}
                            ${agenda.time_duration ? `<span><i class="fa-regular fa-clock mr-1"></i> ${agenda.time_duration}</span>` : ''}
                        </div>
                    </div>
                `;
                list.insertAdjacentHTML('beforeend', html);
            });
        }

        function openAgendaDetails(agendaId) {
            const agenda = window.currentAgendas.find(a => a.agenda_id == agendaId);
            if (!agenda) return;
            document.getElementById('edit_agenda_id').value = agenda.agenda_id;
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
            document.getElementById('edit_action_items').value = agenda.action_items || '';
            
            toggleCompletionFieldsHR();
            openModal('editAgendaModal');
        }

        function toggleCompletionFieldsHR() {
            const status = document.getElementById('edit_status').value;
            const completionFields = document.getElementById('hr_completion_fields');
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

        async function submitAgenda(e, context) {
            e.preventDefault();
            const formData = new FormData(e.target);
            try {
                const url = context === 'emp' ? `{{ url('emp/groups') }}/${activeGroupId}/agenda` : `{{ url('hr/groups') }}/${activeGroupId}/agenda`;
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    closeModal('addAgendaModal');
                    e.target.reset();
                    loadGroup(activeGroupId);
                }
            } catch (err) { console.error(err); }
        }

        async function updateAgenda(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const agendaId = document.getElementById('edit_agenda_id').value;
            try {
                const response = await fetch(`${window.location.origin}/hr/groups/${activeGroupId}/agenda/${agendaId}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    closeModal('editAgendaModal');
                    loadGroup(activeGroupId);
                }
            } catch (err) { console.error(err); }
        }

        async function deleteAgenda() {
            if(!confirm('Are you sure you want to delete this agenda?')) return;
            const agendaId = document.getElementById('edit_agenda_id').value;
            try {
                const response = await fetch(`${window.location.origin}/hr/groups/${activeGroupId}/agenda/${agendaId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                const result = await response.json();
                if (result.success) {
                    closeModal('editAgendaModal');
                    loadGroup(activeGroupId);
                }
            } catch (err) { console.error(err); }
        }

        function exportSingleAgendaToPDFById(id) {
            const agenda = window.currentAgendas.find(a => a.agenda_id == id);
            if (!agenda) return;
            generateSingleAgendaPDF(agenda);
        }

        function exportSingleAgendaToPDF() {
            const agendaId = document.getElementById('edit_agenda_id').value;
            const agenda = window.currentAgendas.find(a => a.agenda_id == agendaId);
            if (!agenda) return;
            generateSingleAgendaPDF(agenda);
        }

        function generateSingleAgendaPDF(agenda) {
            const container = document.getElementById('print-agenda-container');
            const groupName = document.getElementById('header-name').innerText;

            let html = `
                <div style="padding: 40px; font-family: 'Inter', sans-serif; color: #1e293b;">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 30px;">
                        <div>
                            <h1 style="margin: 0; font-size: 24px; color: #1e1b4b;">Agenda Item Details</h1>
                            <p style="margin: 5px 0 0; color: #64748b;">Team: ${groupName}</p>
                        </div>
                        <div style="text-align: right; font-size: 12px; color: #94a3b8;">
                            Generated on: ${new Date().toLocaleString()}
                        </div>
                    </div>

                    <div style="margin-bottom: 30px; border: 1px solid #f1f5f9; border-radius: 12px; overflow: hidden;">
                        <div style="background: #f8fafc; padding: 15px 20px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                            <h2 style="margin: 0; font-size: 18px; color: #1e1b4b;">${agenda.title}</h2>
                            <span style="font-size: 12px; font-weight: bold; text-transform: uppercase; padding: 4px 10px; border-radius: 6px; background: ${agenda.status === 'Completed' ? '#dcfce7' : '#fef9c3'}; color: ${agenda.status === 'Completed' ? '#166534' : '#854d0e'};">
                                ${agenda.status}
                            </span>
                        </div>
                        <div style="padding: 30px;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 30px; font-size: 14px;">
                                <div>
                                    <p style="margin: 0 0 8px; font-weight: bold; color: #64748b; text-transform: uppercase; font-size: 11px;">Timeline</p>
                                    <p style="margin: 0;"><strong>Start Date:</strong> ${agenda.start_date ? new Date(agenda.start_date).toLocaleString() : 'N/A'}</p>
                                    ${agenda.status === 'Completed' && agenda.end_date ? `<p style="margin: 8px 0 0;"><strong>End Date:</strong> ${new Date(agenda.end_date).toLocaleString()}</p>` : ''}
                                    <p style="margin: 8px 0 0;"><strong>Duration:</strong> ${agenda.time_duration || 'N/A'}</p>
                                </div>
                                <div>
                                    <p style="margin: 0 0 8px; font-weight: bold; color: #64748b; text-transform: uppercase; font-size: 11px;">Priority & Context</p>
                                    <p style="margin: 0;"><strong>Priority Level:</strong> ${agenda.priority}</p>
                                    <p style="margin: 8px 0 0;"><strong>Agenda ID:</strong> #AG-${agenda.agenda_id}</p>
                                </div>
                            </div>

                            <div style="margin-bottom: 30px;">
                                <p style="margin: 0 0 8px; font-weight: bold; color: #64748b; text-transform: uppercase; font-size: 11px;">Description & Context</p>
                                <div style="margin: 0; font-size: 14px; line-height: 1.6; color: #334155; white-space: pre-line;">${agenda.description || 'No description provided.'}</div>
                            </div>

                            ${agenda.status === 'Completed' ? `
                                <div style="margin-top: 30px; padding-top: 30px; border-top: 2px solid #f1f5f9;">
                                    <div style="margin-bottom: 25px;">
                                        <p style="margin: 0 0 10px; font-weight: bold; color: #166534; text-transform: uppercase; font-size: 11px;">Final Decision / Outcome</p>
                                        <div style="margin: 0; font-size: 14px; line-height: 1.6; color: #334155; white-space: pre-line;">${agenda.decision_outcome || 'N/A'}</div>
                                    </div>
                                    <div>
                                        <p style="margin: 0 0 10px; font-weight: bold; color: #166534; text-transform: uppercase; font-size: 11px;">Assigned Action Items</p>
                                        <div style="margin: 0; font-size: 14px; line-height: 1.6; color: #334155; white-space: pre-line;">${agenda.action_items || 'N/A'}</div>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    <div style="margin-top: 50px; text-align: center; font-size: 11px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 20px;">
                        This is an official record generated from the Collaboration Hub.
                    </div>
                </div>
            `;
            container.innerHTML = html;
            window.print();
        }

        function renderPosts(posts) {
            const stream = document.getElementById('posts-stream');
            stream.innerHTML = '';

            if (!posts || posts.length === 0) {
                stream.innerHTML = '<div class="text-center py-10 opacity-40"><p>No activity yet in this channel.</p></div>';
                return;
            }

            posts.sort((a, b) => new Date(a.added_date) - new Date(b.added_date)).forEach(post => {
                const date = new Date(post.added_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
                const authorInitials = post.author ? post.author.first_name[0] + post.author.last_name[0] : '??';
                const authorName = post.author ? post.author.first_name + ' ' + post.author.last_name : 'System User';
                
                let contentHtml = '';
                if (post.post_type === 'image') {
                    contentHtml = `<img src="{{ asset('') }}${post.post_file_path}" class="rounded-2xl max-w-sm shadow-lg hover:brightness-95 transition-all cursor-pointer" onclick="window.previewRemoteFile(this.src, '${post.post_file_name}', 'Image')">`;
                } else if (post.post_type === 'document') {
                    contentHtml = `
                        <div onclick="window.previewRemoteFile('{{ asset('') }}${post.post_file_path}', '${post.post_file_name}', 'Document')" class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md transition-all cursor-pointer group/file">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl group-hover/file:bg-indigo-600 group-hover/file:text-white transition-colors">
                                <i class="fa-solid fa-file-lines"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-800 text-sm truncate">${post.post_file_name}</p>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-0.5">Click to Preview</p>
                            </div>
                            <a href="{{ asset('') }}${post.post_file_path}" download class="w-9 h-9 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-brand hover:text-white transition-all">
                                <i class="fa-solid fa-download text-xs"></i>
                            </a>
                        </div>
                    `;
                } else {
                    contentHtml = `<div class="text-slate-700 leading-relaxed whitespace-pre-line text-sm border-l-4 border-indigo-100 pl-4 py-1">${post.post_text}</div>`;
                }

                const html = `
                    <div class="premium-card p-6 border-none shadow-sm group">
                        <div class="flex gap-4 mb-4">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs uppercase shadow-inner">
                                ${authorInitials}
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-bold text-slate-800">${authorName}</h4>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">${date}</span>
                                </div>
                                <p class="text-xs text-slate-400 font-medium">shared a team update</p>
                            </div>
                        </div>
                        ${contentHtml}
                    </div>
                `;
                stream.innerHTML += html;
            });

            // Auto-scroll to bottom of stream
            setTimeout(() => {
                stream.scrollTop = stream.scrollHeight;
            }, 100);
        }

        function renderMembers(members, currentUserId) {
            const list = document.getElementById('members-list');
            list.innerHTML = '';

            members.forEach(m => {
                const roleName = m.role ? m.role.group_role_name : 'Member';
                const roleId = m.role ? m.role.group_role_id : 0;
                const roleClass = roleId == 1 ? 'bg-amber-50 text-amber-700' : 'bg-slate-50 text-slate-600';

                const statusBadge = m.is_accepted == 0 ? '<span class="ml-2 px-2 py-0.5 rounded-md bg-slate-100 text-slate-400 text-[8px] font-bold uppercase">Pending</span>' : '';

                const removeButton = (m.employee_id != currentUserId) 
                    ? `<button onclick="removeGroupMember(${m.employee_id})" class="text-rose-500 hover:text-rose-700 ml-2" title="Remove Member"><i class="fa-solid fa-trash"></i></button>` 
                    : '';

                const html = `
                    <div class="p-4 bg-white border border-slate-100 rounded-2xl flex items-center gap-4 hover:shadow-md transition-shadow ${m.is_accepted == 0 ? 'opacity-60' : ''}">
                        <div class="w-12 h-12 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center overflow-hidden">
                            <i class="fa-solid fa-user text-slate-300"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-800 text-sm flex items-center">${m.employee ? m.employee.first_name + ' ' + m.employee.last_name : 'Unknown'} ${statusBadge}</h4>
                            <p class="text-xs text-slate-500">${m.employee ? m.employee.employee_code : '---'}</p>
                        </div>
                        <div class="px-3 py-1 rounded-lg ${roleClass} text-[10px] font-black uppercase tracking-wider">
                            ${roleName}
                        </div>
                        ${removeButton}
                    </div>
                `;
                list.innerHTML += html;
            });
        }

        function renderFiles(files) {
            const grid = document.getElementById('files-grid');
            grid.innerHTML = '';

            if (!files || files.length === 0) {
                grid.innerHTML = '<div class="col-span-full text-center py-10 opacity-40"><p>No documents found in library.</p></div>';
                return;
            }

            files.forEach(f => {
                const ext = f.file_path.split('.').pop().toLowerCase();
                const icon = ['pdf', 'doc', 'docx'].includes(ext) ? 'fa-file-pdf text-rose-500' : (['xls', 'xlsx'].includes(ext) ? 'fa-file-excel text-emerald-500' : 'fa-file-lines text-blue-500');

                const html = `
                    <div class="premium-card p-4 border-none shadow-sm flex items-center gap-4 group hover:shadow-md transition-all">
                        <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-xl cursor-pointer" onclick="window.previewRemoteFile('{{ asset('') }}${f.file_path}', '${f.file_name}', 'Resource')">
                            <i class="fa-solid ${icon}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-800 text-sm truncate" title="${f.file_name}">${f.file_name}</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">${ext} file</p>
                        </div>
                        <a href="{{ asset('') }}${f.file_path}" target="_blank" class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center opacity-0 group-hover:opacity-100 hover:bg-indigo-600 hover:text-white transition-all">
                            <i class="fa-solid fa-download text-xs"></i>
                        </a>
                    </div>
                `;
                grid.innerHTML += html;
            });
        }

        async function saveGroup(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            try {
                const response = await fetch("{{ route('hr.groups.store') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    closeModal('newGroupModal');
                    window.location.reload();
                }
            } catch (err) { console.error(err); }
        }

        async function submitPost() {
            const text = document.getElementById('post-text').value;
            const fileInput = document.getElementById('group_attachment');
            const hasFile = fileInput.files.length > 0;

            if (!text.trim() && !hasFile) return;

            const formData = new FormData();
            formData.append('group_id', activeGroupId);
            formData.append('post_text', text);
            if(hasFile) formData.append('attachment', fileInput.files[0]);

            try {
                const response = await fetch("{{ route('hr.groups.post.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    document.getElementById('post-text').value = '';
                    wallPreview.clearPreview();
                    loadGroup(activeGroupId);
                }
            } catch (err) { console.error(err); }
        }

        async function uploadGroupFile(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            try {
                const response = await fetch("{{ route('hr.groups.file.store') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    closeModal('uploadFileModal');
                    loadGroup(activeGroupId);
                }
            } catch (err) { console.error(err); }
        }

        async function addNewMember(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            try {
                const response = await fetch("{{ route('hr.groups.member.store') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    closeModal('addMemberModal');
                    loadGroup(activeGroupId);
                }
            } catch (err) { console.error(err); }
        }

        async function removeGroupMember(memberId) {
            Swal.fire({
                title: 'Remove Member?',
                text: "Are you sure you want to remove this member?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`{{ url('hr/groups') }}/${activeGroupId}/member/${memberId}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });
                        const res = await response.json();
                        if(res.success) loadGroup(activeGroupId);
                    } catch(e) { console.error(e); }
                }
            });
        }

        function toggleGroupMenu(event, id) {
            event.stopPropagation();
            document.querySelectorAll('.group-card').forEach(card => card.style.zIndex = '1');
            document.querySelectorAll('[id^="group-menu-"]').forEach(menu => {
                if(menu.id !== `group-menu-${id}`) menu.classList.add('hidden');
            });
            const menu = document.getElementById(`group-menu-${id}`);
            menu.classList.toggle('hidden');
            if (!menu.classList.contains('hidden')) {
                const card = document.getElementById(`group-item-${id}`);
                if(card) card.style.zIndex = '50';
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            document.querySelectorAll('.group-card').forEach(card => card.style.zIndex = '1');
            document.querySelectorAll('[id^="group-menu-"]').forEach(menu => {
                if (!menu.contains(event.target) && !menu.previousElementSibling.contains(event.target)) {
                    menu.classList.add('hidden');
                }
            });
        });

        async function archiveGroupList(event, id) {
            event.stopPropagation();
            toggleGroupMenu(event, id);
            Swal.fire({
                title: 'Archive Team?',
                text: "It will be removed from your active lists.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, archive it'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`{{ url('hr/groups') }}/${id}/archive`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });
                        const res = await response.json();
                        if(res.success) window.location.reload();
                    } catch(e) { console.error(e); }
                }
            });
        }

        async function restoreGroupList(event, id) {
            event.stopPropagation();
            toggleGroupMenu(event, id);
            Swal.fire({
                title: 'Restore Team?',
                text: "It will be moved back to your active lists.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, restore it'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`{{ url('hr/groups') }}/${id}/restore`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });
                        const res = await response.json();
                        if(res.success) window.location.href = "{{ route('hr.groups.index', ['c' => request('c')]) }}";
                    } catch(e) { console.error(e); }
                }
            });
        }

        async function duplicateGroupList(event, id) {
            event.stopPropagation();
            toggleGroupMenu(event, id);
            Swal.fire({
                title: 'Duplicate Team',
                input: 'text',
                inputLabel: 'Enter the name for the new duplicated team:',
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to write something!'
                    }
                }
            }).then(async (result) => {
                if (result.isConfirmed && result.value) {
                    try {
                        const formData = new FormData();
                        formData.append('new_name', result.value);
                        
                        const response = await fetch(`{{ url('hr/groups') }}/${id}/copy`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: formData
                        });
                        const res = await response.json();
                        if(res.success) window.location.reload();
                    } catch(e) { console.error(e); }
                }
            });
        }

        async function saveGroup(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const isCom = formData.get('is_com');
            try {
                const response = await fetch("{{ route('hr.groups.store') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    window.location.href = `{{ url('hr/groups') }}?c=${isCom}`;
                }
            } catch (err) { console.error(err); }
        }


        function filterGroups() {
            const query = document.getElementById('groupSearch').value.toLowerCase();
            const items = document.querySelectorAll('[id^="group-item-"]');
            
            items.forEach(item => {
                const name = item.querySelector('h3').innerText.toLowerCase();
                const desc = item.querySelector('p').innerText.toLowerCase();
                if (name.includes(query) || desc.includes(query)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
@endsection