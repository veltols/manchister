@extends('layouts.app')

@section('title', 'Collaboration Hub')
@section('subtitle', 'Internal collaboration and project teams')

@section('content')
    <div class="groups-layout">
        <!-- Sidebar: Groups List -->
        <div class="groups-sidebar">
            <div class="sidebar-header">
                <h2 class="text-xl font-bold text-premium">My Groups</h2>
                <div class="flex gap-2">
                    <button onclick="openModal('newGroupModal')" class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100" title="Create New Group">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <div class="relative group">
                        <button
                            class="w-10 h-10 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center hover:bg-slate-100 transition-all shadow-sm">
                            <i class="fa-solid fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="groups-list space-y-3 p-4">
                @forelse($groups as $group)
                    <div onclick="loadGroup({{ $group->group_id }})" id="group-item-{{ $group->group_id }}"
                        class="group-card p-4 rounded-2xl bg-white border border-slate-100 shadow-sm cursor-pointer hover:shadow-md hover:border-indigo-200 transition-all group">
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
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <div
                            class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <i class="fa-solid fa-user-group text-2xl"></i>
                        </div>
                        <p class="text-slate-400 text-sm">No groups found</p>
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
                <p class="text-slate-500 max-w-sm">Choose a group from the sidebar to view its activity, files, and members.</p>
            </div>

            <div id="group-content" class="hidden h-full flex flex-col animate-fade-in relative">
                <!-- Invitation Overlay -->
                <div id="invitation-overlay" class="absolute inset-0 z-50 bg-white/60 backdrop-blur-md flex flex-col items-center justify-center text-center p-12 hidden">
                    <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 mb-6 shadow-inner animate-bounce">
                        <i class="fa-solid fa-envelope-open-text text-4xl"></i>
                    </div>
                    <h3 class="text-3xl font-display font-bold text-premium mb-2">You're Invited!</h3>
                    <p class="text-slate-500 max-w-sm mb-8">You have been requested to participate in this group. Join the conversation and start collaborating with your team.</p>
                    <button onclick="acceptInvitation()" class="premium-button from-indigo-600 to-purple-600 text-white px-10 py-4 rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:scale-105 transition-all">
                        Accept & Join Team
                    </button>
                </div>

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
                        <!-- Settings usually restricted for employees -->
                    </div>
                </div>

                <!-- Tabs -->
                <div class="main-tabs px-6 bg-white border-b border-slate-100 flex gap-8">
                    <button onclick="switchGroupTab('wall')" class="group-tab active" data-tab="wall">The Wall</button>
                    <button onclick="switchGroupTab('files')" class="group-tab" data-tab="files">Resources</button>
                    <button onclick="switchGroupTab('members')" class="group-tab" data-tab="members">Team Members</button>
                    <button onclick="switchGroupTab('details')" class="group-tab" data-tab="details">Information</button>
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
                                <i class="fa-solid fa-user-plus mr-2"></i>Add Member
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="members-list">
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
                    <h2 class="text-2xl font-display font-bold leading-none">New Team Space</h2>
                    <p class="text-indigo-100/60 text-xs mt-1">Configure your new collaboration hub</p>
                </div>
                <button onclick="closeModal('newGroupModal')"
                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10 transition-colors">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <form id="newGroupForm" class="p-8 space-y-6" onsubmit="saveGroup(event)">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 p-4 rounded-2xl border-2 border-slate-100 cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 group">
                            <input type="radio" name="is_com" value="0" checked class="hidden">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 group-has-[:checked]:bg-indigo-600 group-has-[:checked]:text-white">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <span class="font-bold text-slate-600 group-has-[:checked]:text-indigo-900">Standard Group</span>
                        </label>
                        <label class="flex items-center gap-3 p-4 rounded-2xl border-2 border-slate-100 cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-purple-600 has-[:checked]:bg-purple-50 group">
                            <input type="radio" name="is_com" value="1" class="hidden">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 group-has-[:checked]:bg-purple-600 group-has-[:checked]:text-white">
                                <i class="fa-solid fa-award"></i>
                            </div>
                            <span class="font-bold text-slate-600 group-has-[:checked]:text-purple-900">Committee</span>
                        </label>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Team Name</label>
                        <input type="text" name="group_name" required class="premium-input w-full"
                            placeholder="e.g., Project Phoenix Taskforce">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Brand Color</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($colors as $color)
                                <div onclick="selectColor({{ $color->color_id }})"
                                    class="color-option w-10 h-10 rounded-xl cursor-pointer hover:scale-110 transition-transform shadow-sm flex items-center justify-center border-4 border-transparent"
                                    style="background: {{ $color->color_value }}" data-color-id="{{ $color->color_id }}">
                                    <i class="fa-solid fa-check text-white opacity-0 transition-opacity"></i>
                                </div>
                            @endforeach
                            <input type="hidden" name="group_color_id" id="selected-color-id" required>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Description</label>
                        <textarea name="group_desc" rows="3" class="premium-input w-full"
                            placeholder="What is the mission of this team?"></textarea>
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

    <!-- Add Member Modal -->
    <div class="modal" id="addMemberModal">
        <div class="modal-backdrop" onclick="closeModal('addMemberModal')"></div>
        <div class="modal-content max-w-md p-0 border-none">
            <div class="p-6 bg-slate-900 text-white flex justify-between items-center rounded-t-[24px]">
                <h2 class="text-xl font-bold">Invite Team Member</h2>
                <button onclick="closeModal('addMemberModal')" class="text-white/60 hover:text-white"><i
                        class="fa-solid fa-times"></i></button>
            </div>
            <form class="p-8 space-y-4" onsubmit="addNewMember(event)">
                <input type="hidden" name="group_id" class="active-group-id">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Member Search</label>
                    <select name="employee_id" required class="premium-input w-full h-11 text-sm">
                        <option value="">Select Colleague...</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Team Role</label>
                    <select name="group_role_id" required class="premium-input w-full h-11 text-sm">
                        @foreach($roles as $role)
                            <option value="{{ $role->group_role_id }}">{{ $role->group_role_name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="w-full premium-button from-indigo-600 to-purple-600 text-white font-bold py-3 rounded-2xl shadow-lg shadow-indigo-100 mt-4 justify-center">Add to Team</button>
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
                <input type="hidden" name="group_id" id="active-group-id-file" class="active-group-id">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">File Alias</label>
                    <input type="text" name="file_name" required class="premium-input w-full h-11 text-sm"
                        placeholder="Financial Report 2024">
                </div>
                <div class="p-8 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl text-center">
                    <input type="file" name="file_path" required id="file-input" class="hidden">
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

    <style>
        .groups-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
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

        async function loadGroup(id) {
            activeGroupId = id;

            // Update UI states
            document.querySelectorAll('.group-card').forEach(c => c.classList.remove('active'));
            const card = document.getElementById(`group-item-${id}`);
            if(card) card.classList.add('active');
            
            document.querySelectorAll('.active-group-id').forEach(i => i.value = id);

            document.getElementById('selection-placeholder').classList.add('hidden');
            document.getElementById('group-content').classList.remove('hidden');

            try {
                const response = await fetch(`{{ url('emp/groups') }}/${id}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const result = await response.json();

                if (result.success) {
                    const group = result.data;
                    
                    // Handle Invitation Status
                    const overlay = document.getElementById('invitation-overlay');
                    if (group.current_user_accepted == 0) {
                        overlay.classList.remove('hidden');
                    } else {
                        overlay.classList.add('hidden');
                    }

                    const initials = group.group_name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();

                    // Set Header
                    document.getElementById('header-name').innerText = group.group_name;
                    document.getElementById('header-type').innerText = group.is_commity ? 'Committee' : 'Internal Group';
                    const avatar = document.getElementById('header-avatar');
                    avatar.innerText = initials;
                    avatar.style.background = group.color ? group.color.color_value : '#6366f1';

                    // Set Details
                    document.getElementById('details-desc').innerText = group.group_desc || 'No description provided for this team.';
                    document.getElementById('details-id').innerText = `TEAM-${group.group_id.toString().padStart(4, '0')}`;

                    // Build Posts
                    renderPosts(group.posts);

                    // Build Members
                    renderMembers(group.members);

                    // Build Files
                    renderFiles(group.files);

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

        function renderMembers(members) {
            const list = document.getElementById('members-list');
            list.innerHTML = '';

            members.forEach(m => {
                const roleName = m.role ? m.role.group_role_name : 'Member';
                const roleId = m.role ? m.role.group_role_id : 0;
                const roleClass = roleId == 1 ? 'bg-amber-50 text-amber-700' : 'bg-slate-50 text-slate-600';
                
                const statusBadge = m.is_accepted == 0 ? '<span class="ml-2 px-2 py-0.5 rounded-md bg-slate-100 text-slate-400 text-[8px] font-bold uppercase">Pending</span>' : '';

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

        async function submitPost() {
            const text = document.getElementById('post-text').value;
            const fileInput = document.getElementById('group_attachment');
            const hasFile = fileInput.files.length > 0;

            if (!text.trim() && !hasFile) return;

            const formData = new FormData();
            formData.append('post_text', text);
            if(hasFile) formData.append('attachment', fileInput.files[0]);

            try {
                const response = await fetch(`{{ url('emp/groups') }}/${activeGroupId}/post`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });
                
                document.getElementById('post-text').value = '';
                wallPreview.clearPreview();
                loadGroup(activeGroupId);
            } catch (err) { console.error(err); }
        }

        function selectColor(colorId) {
            document.getElementById('selected-color-id').value = colorId;
            document.querySelectorAll('.color-option').forEach(opt => {
                opt.classList.remove('selected');
                if (opt.getAttribute('data-color-id') == colorId) opt.classList.add('selected');
            });
        }

        async function saveGroup(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            try {
                const response = await fetch("{{ route('emp.groups.store') }}", {
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

        async function addNewMember(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            try {
                const response = await fetch("{{ route('emp.groups.member.store') }}", {
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

        async function uploadGroupFile(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            try {
                const response = await fetch(`{{ url('emp/groups') }}/${activeGroupId}/upload`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                
                closeModal('uploadFileModal');
                loadGroup(activeGroupId);
            } catch (err) { console.error(err); }
        }
        async function acceptInvitation() {
            try {
                const response = await fetch(`{{ url('emp/groups') }}/${activeGroupId}/accept`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const result = await response.json();
                if (result.success) {
                    loadGroup(activeGroupId);
                }
            } catch (err) { console.error(err); }
        }
    </script>
@endsection
