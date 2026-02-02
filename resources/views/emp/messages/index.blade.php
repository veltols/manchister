@extends('layouts.app')

@section('title', 'Messages')
@section('subtitle', 'Internal messaging')

@section('content')
<div class="h-[calc(100vh-180px)] flex gap-6">

    <!-- Sidebar / Chat List -->
    <div class="w-1/3 premium-card flex flex-col overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h2 class="text-lg font-display font-bold text-premium">Inbox</h2>
            <button onclick="openModal('newChatModal')" class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-md">
                <i class="fa-solid fa-pen text-sm"></i>
            </button>
        </div>
        
        <div class="overflow-y-auto flex-1 p-3 space-y-2">
            @forelse($conversations as $chat)
            <a href="{{ route('emp.messages.show', $chat->chat_id) }}" 
               class="block p-4 rounded-xl hover:bg-slate-50 transition-all border border-transparent {{ request()->route('chat_id') == $chat->chat_id ? 'premium-button from-indigo-50 to-purple-50 border-indigo-200' : '' }}">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold shadow-md">
                            {{ substr($chat->otherUser->first_name ?? 'U', 0, 1) }}
                        </div>
                        @if($chat->unreadCount > 0)
                        <span class="absolute -top-1 -right-1 bg-gradient-to-r from-red-500 to-rose-600 text-white text-xs font-bold w-6 h-6 flex items-center justify-center rounded-full border-2 border-white shadow-md">
                            {{ $chat->unreadCount }}
                        </span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline">
                            <h3 class="text-sm font-bold text-premium truncate">{{ $chat->otherUser->first_name ?? 'Unknown' }} {{ $chat->otherUser->last_name ?? '' }}</h3>
                            <span class="text-xs text-slate-400 whitespace-nowrap ml-2">
                                {{ $chat->lastMessage ? $chat->lastMessage->added_date : '' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 truncate mt-1">
                            {{ $chat->lastMessage ? $chat->lastMessage->post_text : 'No messages yet' }}
                        </p>
                    </div>
                </div>
            </a>
            @empty
            <div class="p-8 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-regular fa-paper-plane text-2xl text-slate-400"></i>
                </div>
                <p class="text-slate-500 font-medium mb-3">No conversations yet</p>
                <button onclick="openModal('newChatModal')" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">
                    Start a new chat
                </button>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Message Area (Placeholder for Index) -->
    <div class="hidden md:flex flex-1 premium-card items-center justify-center">
        <div class="text-center">
            <div class="w-24 h-24 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-regular fa-comments text-4xl text-indigo-600"></i>
            </div>
            <h3 class="text-xl font-display font-bold text-premium mb-2">Select a conversation</h3>
            <p class="text-sm text-slate-500">Choose a chat from the left to start messaging</p>
        </div>
    </div>

</div>

<!-- New Chat Modal -->
<div class="modal" id="newChatModal">
    <div class="modal-backdrop" onclick="closeModal('newChatModal')"></div>
    <div class="modal-content max-w-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-display font-bold text-premium">New Message</h2>
            <button onclick="closeModal('newChatModal')" class="w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('emp.messages.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Select Employee</label>
                <div class="max-h-60 overflow-y-auto border border-slate-200 rounded-xl">
                    @foreach($employees as $emp)
                    <label class="flex items-center p-3 hover:bg-slate-50 cursor-pointer border-b last:border-0 border-slate-100 transition-colors">
                        <input type="radio" name="employee_id" value="{{ $emp->employee_id }}" class="mr-3 w-4 h-4 text-indigo-600" required>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold shadow-md mr-3">
                            {{ substr($emp->first_name, 0, 1) }}
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-800">{{ $emp->first_name }} {{ $emp->last_name }}</div>
                            <div class="text-xs text-slate-500">{{ $emp->department->department_name ?? 'Employee' }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Message (Optional)</label>
                <textarea name="message" rows="2" class="premium-input w-full px-4 py-3 text-sm" placeholder="Say hello..."></textarea>
            </div>

            <button type="submit" class="w-full px-6 py-3 premium-button from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                <i class="fa-solid fa-paper-plane mr-2"></i>Start Chat
            </button>
        </form>
    </div>
</div>

@endsection
