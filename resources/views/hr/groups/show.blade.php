@extends('layouts.app')

@section('title', $group->group_name)

@section('content')
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="glass-panel p-4 flex justify-between items-center mb-4 shrink-0">
        <div>
             <a href="{{ route('hr.groups.index') }}" class="text-gray-500 hover:text-gray-700 text-sm"><i class="fa-solid fa-arrow-left"></i> Back</a>
             <h1 class="text-xl font-bold text-gray-800 mt-1">{{ $group->group_name }}</h1>
             <p class="text-sm text-gray-500">{{ $group->group_desc }}</p>
        </div>
        <div class="flex -space-x-2">
              @foreach($group->members->take(5) as $m)
                 <div class="w-8 h-8 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center text-xs font-bold text-gray-700" title="{{ $m->employee->first_name ?? '' }}">
                    {{ substr($m->employee->first_name ?? 'U', 0, 1) }}
                </div>
             @endforeach
             <button onclick="openModal('addMemberModal')" class="w-8 h-8 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-xs hover:bg-gray-200" title="Add Member">
                 <i class="fa-solid fa-plus"></i>
             </button>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="glass-panel flex-1 flex flex-col overflow-hidden relative">
        <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chatContainer">
            @forelse($group->posts as $post)
                @php $isMe = ($post->added_by == (Auth::user()->employee_id ?? Auth::id())); @endphp
                <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[70%] {{ $isMe ? 'bg-blue-100' : 'bg-gray-100' }} rounded-lg p-3">
                        <div class="flex justify-between items-center mb-1 gap-4">
                            <span class="text-xs font-bold text-gray-700">{{ $post->sender->first_name ?? 'Unknown' }}</span>
                            <span class="text-[10px] text-gray-400">{{ $post->added_date }}</span>
                        </div>
                        <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $post->post_text }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-400 mt-10">Start the conversation...</div>
            @endforelse
        </div>

        <!-- Input -->
        <div class="p-4 bg-white border-t shrink-0">
            <form action="{{ route('hr.groups.post', $group->group_id) }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="post_text" class="flex-1 border rounded-full px-4 py-2 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-secondary" placeholder="Type a message..." required autocomplete="off">
                <button type="submit" class="bg-secondary text-white w-10 h-10 rounded-full hover:bg-secondary-hover flex items-center justify-center">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Add Member Modal -->
<div class="modal" id="addMemberModal" style="display: none;">
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-sm mx-4">
            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="text-lg font-bold text-gray-800">Add Member</h2>
                <button onclick="closeModal('addMemberModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('hr.groups.add_member', $group->group_id) }}" method="POST" class="p-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Employee</label>
                    <select name="employee_id" class="w-full border rounded-md p-2 bg-gray-50 outline-none" required>
                        @foreach($employees as $emp)
                            @if(!$group->members->contains('employee_id', $emp->employee_id))
                                <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-secondary text-white py-2 rounded hover:bg-secondary-hover">Add</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).style.display = 'block'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
    
    // Auto scroll to bottom
    const chatContainer = document.getElementById('chatContainer');
    chatContainer.scrollTop = chatContainer.scrollHeight;
</script>
@endsection
