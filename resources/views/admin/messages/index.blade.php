@extends('layouts.app')

@section('title', 'Messages')
@section('subtitle', 'Your Conversations')

@section('content')
<div class="h-[calc(100vh-12rem)] flex gap-6">
    <!-- Chat Sidebar -->
    <div class="w-80 bg-white rounded-[2rem] shadow-lg shadow-slate-200/50 border border-slate-100 flex flex-col overflow-hidden">
        <!-- Sidebar Header -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-display font-bold text-slate-800">Chats</h3>
                <button onclick="openModal('newChatModal')" class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#004F68] to-[#006a8a] hover:brightness-110 text-white flex items-center justify-center transition-all shadow-sm shadow-cyan-900/20">
                    <i class="fa-solid fa-plus text-sm"></i>
                </button>
            </div>
            <div class="relative">
                 <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" placeholder="Search chats..." class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 transition-colors">
            </div>
        </div>

        <!-- Chat List -->
        <div class="flex-1 overflow-y-auto">
            @forelse($conversations as $chat)
                @php
                    $isMeA = $chat->a_id == $adminId;
                    $otherUser = $isMeA ? $chat->participantB : $chat->participantA;
                    $isActive = $activeChat && $activeChat->chat_id == $chat->chat_id;
                @endphp
                <a href="{{ route('admin.messages.index', ['chat_id' => $chat->chat_id]) }}" 
                   class="flex items-center gap-3 p-4 hover:bg-slate-50 transition-colors border-b border-slate-50 {{ $isActive ? 'bg-indigo-50/50 border-l-4 border-l-indigo-500' : 'border-l-4 border-l-transparent' }}">
                    <div class="relative">
                        @if($otherUser->employee_picture)
                            <img src="{{ asset('uploads/' . $otherUser->employee_picture) }}" alt="{{ $otherUser->first_name }}" class="w-12 h-12 rounded-full object-cover border border-slate-200">
                        @else
                             <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg border border-indigo-200">
                                {{ strtoupper(substr($otherUser->first_name, 0, 1)) }}
                            </div>
                        @endif
                        <!-- Status Indicator (Mockup) -->
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-1">
                            <h4 class="font-bold text-slate-700 truncate {{ $isActive ? 'text-indigo-700' : '' }}">{{ $otherUser->first_name }} {{ $otherUser->last_name }}</h4>
                            <span class="text-[10px] text-slate-400 font-medium">12:30 PM</span>
                        </div>
                        <p class="text-xs text-slate-500 truncate">Click to view conversation...</p>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center text-slate-400">
                    <i class="fa-regular fa-comments text-4xl mb-3 opacity-50"></i>
                    <p class="text-sm">No conversations found.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 bg-white rounded-[2rem] shadow-lg shadow-slate-200/50 border border-slate-100 flex flex-col overflow-hidden relative">
        @if($activeChat)
            @php
                $isMeA = $activeChat->a_id == $adminId;
                $chatPartner = $isMeA ? $activeChat->participantB : $activeChat->participantA;
            @endphp
            
            <!-- Chat Header -->
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                     @if($chatPartner->employee_picture)
                        <img src="{{ asset('uploads/' . $chatPartner->employee_picture) }}" alt="{{ $chatPartner->first_name }}" class="w-10 h-10 rounded-full object-cover">
                    @else
                         <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold border border-indigo-200">
                            {{ strtoupper(substr($chatPartner->first_name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h3 class="font-bold text-slate-800">{{ $chatPartner->first_name }} {{ $chatPartner->last_name }}</h3>
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                            <span class="text-xs text-slate-500 font-medium">Online</span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
                        <i class="fa-solid fa-phone"></i>
                    </button>
                    <button class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
                        <i class="fa-solid fa-video"></i>
                    </button>
                    <button class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                </div>
            </div>

            <!-- Messages Stream -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/30" id="messagesContainer">
                @foreach($messages as $msg)
                    @php
                        $isMine = $msg->added_by == $adminId;
                    @endphp
                    <div class="flex w-full {{ $isMine ? 'justify-end' : 'justify-start' }}">
                        <div class="flex max-w-[70%] {{ $isMine ? 'flex-row-reverse' : 'flex-row' }} gap-3">
                             @if(!$isMine)
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex-shrink-0 flex items-center justify-center overflow-hidden self-end">
                                    @if($msg->sender->employee_picture)
                                         <img src="{{ asset('uploads/' . $msg->sender->employee_picture) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-[10px] font-bold text-indigo-600">{{ substr($msg->sender->first_name, 0, 1) }}</span>
                                    @endif
                                </div>
                            @endif
                            
                            <div class="flex flex-col {{ $isMine ? 'items-end' : 'items-start' }}">
                                <div class="px-5 py-3 rounded-2xl {{ $isMine ? 'bg-indigo-600 text-white rounded-br-none shadow-indigo-100' : 'bg-white border border-slate-100 text-slate-700 rounded-bl-none shadow-sm' }} shadow-md">
                                    <p class="text-sm leading-relaxed">{{ $msg->post_text }}</p>
                                </div>
                                <span class="text-[10px] text-slate-400 mt-1 font-medium px-1">
                                    {{ \Carbon\Carbon::parse($msg->added_date)->format('h:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t border-slate-100">
                <form action="{{ route('admin.messages.store') }}" method="POST" class="flex items-end gap-3">
                    @csrf
                    <input type="hidden" name="chat_id" value="{{ $activeChat->chat_id }}">
                    
                    <button type="button" class="w-10 h-10 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-400 transition-colors flex-shrink-0">
                        <i class="fa-solid fa-paperclip"></i>
                    </button>
                    
                    <div class="flex-1 bg-slate-50 rounded-xl border border-slate-200 focus-within:border-indigo-300 focus-within:ring-2 focus-within:ring-indigo-100 transition-all">
                        <textarea name="message" rows="1" placeholder="Type your message..." class="w-full bg-transparent border-none focus:ring-0 px-4 py-3 text-sm text-slate-700 resize-none max-h-32" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                    </div>

                    <button type="submit" class="w-10 h-10 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white shadow-lg shadow-indigo-200 transition-all transform hover:scale-105 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
            
            <script>
                // Auto-scroll to bottom
                const container = document.getElementById('messagesContainer');
                container.scrollTop = container.scrollHeight;
            </script>

        @else
            <!-- Empty State -->
            <div class="flex-1 flex flex-col items-center justify-center text-slate-300">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                    <i class="fa-regular fa-paper-plane text-4xl text-slate-200"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-400">Select a Conversation</h3>
                <p class="text-sm">Choose a chat from the sidebar to start messaging</p>
            </div>
        @endif
    </div>
</div>

<!-- New Chat Modal -->
<div id="newChatModal" class="modal">
    <div class="modal-backdrop" onclick="closeModal('newChatModal')"></div>
    <div class="modal-content w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-display font-bold text-slate-800">Start New Chat</h3>
            <button onclick="closeModal('newChatModal')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form action="{{ route('admin.messages.create') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label for="employee_id" class="block text-sm font-medium text-slate-700 mb-2">Select Employee</label>
                <div class="relative">
                    <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <select name="employee_id" id="employee_id" class="w-full premium-input pl-10 h-11" required>
                        <option value="">Choose a colleague...</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('newChatModal')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-medium hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="premium-button">
                    <i class="fa-regular fa-paper-plane"></i>
                    Start Chat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
