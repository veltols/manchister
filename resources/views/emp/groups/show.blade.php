@extends('layouts.app')

@section('title', $group->group_name)
@section('subtitle', 'Space for ' . $group->group_status)

@section('content')
    <div class="space-y-6 animate-fade-in-up">

        <!-- Group Header Card -->
        <div class="premium-card p-0 overflow-hidden">
            <div class="h-32 bg-gradient-to-r from-brand-dark to-indigo-900 p-8 flex items-end justify-between">
                <div class="flex items-center gap-6">
                    <div
                        class="w-20 h-20 rounded-2xl bg-white shadow-xl flex items-center justify-center text-brand-dark -mb-10 relative z-20 border-4 border-slate-50">
                        <i class="fa-solid fa-people-group text-3xl"></i>
                    </div>
                    <div class="text-white pb-2">
                        <h1 class="text-3xl font-display font-bold">{{ $group->group_name }}</h1>
                        <p class="text-white/70 text-sm font-medium">{{ $group->group_status }} workspace</p>
                    </div>
                </div>
                <div class="flex gap-3 pb-2">
                    <button onclick="openModal('uploadFileModal')"
                        class="px-6 py-2.5 bg-white/20 hover:bg-white/30 backdrop-blur-md rounded-xl text-white text-xs font-bold border border-white/30 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        Upload File
                    </button>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="bg-white px-8 pt-10 pb-0 border-b border-slate-100">
                <div class="flex gap-8">
                    <button onclick="switchTab('feed')"
                        class="tab-btn active border-b-2 border-brand-dark pb-4 font-bold text-sm text-slate-800 transition-all"
                        id="btn-feed">Discussion Feed</button>
                    <button onclick="switchTab('files')"
                        class="tab-btn border-b-2 border-transparent pb-4 font-bold text-sm text-slate-400 hover:text-slate-600 transition-all"
                        id="btn-files">Resources & Files</button>
                    <button onclick="switchTab('members')"
                        class="tab-btn border-b-2 border-transparent pb-4 font-bold text-sm text-slate-400 hover:text-slate-600 transition-all"
                        id="btn-members">Team Members</button>
                    <button onclick="switchTab('approvals')"
                        class="tab-btn border-b-2 border-transparent pb-4 font-bold text-sm text-slate-400 hover:text-slate-600 transition-all"
                        id="btn-approvals">Approvals</button>
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
                    <div class="premium-card p-6 border-brand-dark/10 bg-slate-50/50">
                        <form action="{{ route('emp.groups.post', $group->group_id) }}" method="POST">
                            @csrf
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
                                            {{ substr($post->sender->first_name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-premium">
                                                {{ $post->sender->first_name ?? 'Unknown' }}
                                                {{ $post->sender->last_name ?? '' }}</h4>
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
                                                class="w-10 h-10 rounded-full bg-white border border-slate-200 text-slate-400 flex items-center justify-center hover:bg-brand-dark hover:text-white transition-all shadow-sm">
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
                                                <i class="fa-solid fa-file-invoice text-brand-dark"></i>
                                                <span class="font-bold text-slate-700">{{ $file->file_name }}</span>
                                            </div>
                                        </td>
                                        <td><span
                                                class="text-sm font-medium text-slate-500">{{ $file->uploader->first_name ?? 'User' }}</span>
                                        </td>
                                        <td><span class="text-xs font-bold text-slate-400">{{ $file->added_date }}</span></td>
                                        <td class="text-center">
                                            <a href="{{ asset($file->file_path) }}" target="_blank"
                                                class="text-brand-dark hover:scale-110 transition-transform inline-block">
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
                                        {{ $member->role->role_name ?? 'Member' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Approvals Tab -->
                <div id="tab-approvals"
                    class="tab-content hidden py-20 text-center bg-white rounded-3xl border border-slate-100 shadow-sm border-dashed">
                    <i class="fa-solid fa-stamp text-5xl text-slate-100 mb-6"></i>
                    <h3 class="font-bold text-slate-400">No pending approvals for this group</h3>
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
                                <span class="text-sm font-bold text-green-600">{{ $group->group_status }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="premium-card p-6 border-brand-dark/20 border-2">
                    <h4 class="text-sm font-bold text-premium mb-4 uppercase tracking-widest">Team Stats</h4>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Active Members</span>
                            <span class="font-bold text-slate-800">{{ $group->members->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Shared Files</span>
                            <span class="font-bold text-brand-dark">{{ $group->files->count() }}</span>
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
            <form action="{{ route('emp.groups.upload', $group->group_id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Display
                        Name</label>
                    <input type="text" name="file_name" class="premium-input w-full" placeholder="e.g. Project Plan 2024"
                        required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Target File</label>
                    <input type="file" name="file_path" class="premium-input w-full text-xs" required>
                </div>
                <div class="flex justify-end gap-3 pt-6">
                    <button type="button" onclick="closeModal('uploadFileModal')"
                        class="px-6 py-2 text-slate-500 font-bold">Cancel</button>
                    <button type="submit" class="premium-button">Upload & Notify</button>
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
                btn.classList.remove('active', 'border-brand-dark', 'text-slate-800');
                btn.classList.add('border-transparent', 'text-slate-400');
            });
            document.getElementById('btn-' + tab).classList.add('active', 'border-brand-dark', 'text-slate-800');
            document.getElementById('btn-' + tab).classList.remove('border-transparent', 'text-slate-400');
        }
    </script>
@endsection
