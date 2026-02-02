@extends('layouts.app')

@section('title', 'Collaboration Hub')
@section('subtitle', 'Groups, Committees, and Private Workspaces')

@section('content')
    <div class="space-y-8 animate-fade-in-up">

        <!-- Action Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-display font-bold text-premium">My Groups</h2>
                <p class="text-sm text-slate-500 mt-1">Participate in discussions and share resources with your teams</p>
            </div>
            <button class="premium-button opacity-50 cursor-not-allowed" title="Group creation is managed by Admin">
                <i class="fa-solid fa-plus"></i>
                <span>Create Group</span>
            </button>
        </div>

        <!-- Groups Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($groups as $group)
                <a href="{{ route('emp.groups.show', $group->group_id) }}"
                    class="premium-card group hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                    <div class="h-3 bg-gradient-brand"></div>
                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 shadow-sm">
                                <i class="fa-solid fa-people-group text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-premium group-hover:text-brand-dark transition-colors">
                                    {{ $group->group_name }}</h3>
                                <span
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $group->group_status }}</span>
                            </div>
                        </div>

                        <p class="text-slate-500 text-sm leading-relaxed mb-8 line-clamp-2">
                            {{ $group->group_description ?: 'No description provided for this collaboration workspace.' }}
                        </p>

                        <div class="flex items-center justify-between border-t border-slate-50 pt-6">
                            <div class="flex -space-x-3">
                                @foreach($group->members->take(4) as $member)
                                    <div class="w-8 h-8 rounded-full bg-white p-0.5 shadow-sm"
                                        title="{{ $member->employee->first_name ?? 'Member' }}">
                                        <div
                                            class="w-full h-full rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 border border-slate-100 uppercase">
                                            {{ substr($member->employee->first_name ?? '?', 0, 1) }}
                                        </div>
                                    </div>
                                @endforeach
                                @if($group->members->count() > 4)
                                    <div
                                        class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-[10px] font-bold text-white border-2 border-white shadow-sm">
                                        +{{ $group->members->count() - 4 }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-4 text-slate-300 text-xs">
                                <div class="flex items-center gap-1">
                                    <i class="fa-solid fa-comment-dots"></i>
                                    <span
                                        class="font-bold text-slate-400">{{ $group->posts_count ?? $group->posts->count() }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <i class="fa-solid fa-file-invoice"></i>
                                    <span
                                        class="font-bold text-slate-400">{{ $group->files_count ?? $group->files->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <i class="fa-solid fa-layer-group text-6xl text-slate-100 mb-6"></i>
                    <h3 class="text-xl font-bold text-premium">No active groups</h3>
                    <p class="text-slate-500 mt-2">You are not a member of any collaboration groups or committees yet.</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection
