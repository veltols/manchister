@extends('layouts.app')

@section('title', 'Groups')
@section('subtitle', 'Committees and groups')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-display font-bold text-premium">Committees & Groups</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $groups->count() }} total groups</p>
        </div>
        <button onclick="openModal('createGroupModal')" class="inline-flex items-center gap-2 px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
            <i class="fa-solid fa-plus"></i>
            <span>New Group</span>
        </button>
    </div>

    <!-- Groups Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($groups as $g)
        <a href="{{ route('hr.groups.show', $g->group_id) }}" class="block">
            <div class="premium-card p-6 hover:shadow-xl transition-all duration-200 h-full flex flex-col justify-between group">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center font-bold text-lg shadow-md">
                            {{ substr($g->group_name, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-premium group-hover:text-indigo-600 transition-colors">{{ $g->group_name }}</h3>
                            <span class="text-xs text-slate-500">{{ $g->added_date }}</span>
                        </div>
                    </div>
                   
                    <p class="text-slate-600 text-sm mb-4 line-clamp-3">{{ $g->group_desc }}</p>
                </div>
                
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <div class="flex -space-x-2">
                        @foreach($g->members->take(3) as $m)
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 border-2 border-white flex items-center justify-center text-xs font-bold text-white shadow-md" title="{{ $m->employee->first_name ?? '' }}">
                                {{ substr($m->employee->first_name ?? 'U', 0, 1) }}
                            </div>
                        @endforeach
                        @if($g->members->count() > 3)
                            <div class="w-10 h-10 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-xs text-slate-600 font-semibold shadow-md">
                                +{{ $g->members->count() - 3 }}
                            </div>
                        @endif
                    </div>
                    <span class="inline-flex items-center gap-2 text-indigo-600 text-sm font-semibold group-hover:gap-3 transition-all">
                        Open <i class="fa-solid fa-arrow-right"></i>
                    </span>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full premium-card p-12">
            <div class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                    <i class="fa-solid fa-users text-2xl text-slate-400"></i>
                </div>
                <p class="text-slate-500 font-medium">No groups found</p>
            </div>
        </div>
        @endforelse
    </div>

</div>

<!-- Create Modal -->
<div class="modal" id="createGroupModal">
    <div class="modal-backdrop" onclick="closeModal('createGroupModal')"></div>
    <div class="modal-content max-w-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-display font-bold text-premium">New Group / Committee</h2>
            <button onclick="closeModal('createGroupModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('hr.groups.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Group Name</label>
                    <input type="text" name="group_name" class="premium-input w-full px-4 py-3 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                    <textarea name="group_desc" rows="3" class="premium-input w-full px-4 py-3 text-sm"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                <button type="button" onclick="closeModal('createGroupModal')" class="px-6 py-3 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">Create</button>
            </div>
        </form>
    </div>
</div>

@endsection
