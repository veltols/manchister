@extends('layouts.app')

@section('title', 'Messaging Center')
@section('subtitle', 'Stay connected with your team')

@section('content')
<div class="h-[calc(100vh-12rem)] flex gap-6 animate-fade-in-up" x-data="{ searchQuery: '' }">
    <!-- Chat Sidebar -->
    <div class="w-80 bg-white rounded-[2rem] shadow-lg shadow-slate-200/50 border border-slate-100 flex flex-col overflow-hidden">
        <!-- Sidebar Header -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-display font-bold text-slate-800">Direct Messages</h3>
                <button onclick="openModal('newChatModal')" class="w-8 h-8 rounded-lg bg-brand hover:brightness-110 text-white flex items-center justify-center transition-all shadow-sm shadow-brand/20">
                    <i class="fa-solid fa-plus text-sm"></i>
                </button>
            </div>
            <div class="relative group">
                 <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-brand transition-colors"></i>
                <input type="text" x-model="searchQuery" placeholder="Search conversations..." class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand transition-colors">
            </div>
        </div>

        <!-- Conversation List -->
        <div class="flex-1 overflow-y-auto">
            @foreach($conversations as $conv)
                @php
                    $otherUser = ($conv->participantA->employee_id ?? 0) == auth()->user()->employee->employee_id 
                                ? $conv->participantB 
                                : $conv->participantA;
                    $isActive = isset($conversation) && $conversation->chat_id == $conv->chat_id;
                @endphp
                <a href="{{ route('emp.messages.show', $conv->chat_id) }}" 
                   class="flex items-center gap-3 p-4 hover:bg-slate-50 transition-colors border-b border-slate-50 {{ $isActive ? 'bg-brand/5 border-l-4 border-l-brand' : 'border-l-4 border-l-transparent' }}">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-full bg-brand/10 text-brand flex items-center justify-center font-bold text-lg border border-brand/20 overflow-hidden">
                            @if($otherUser && $otherUser->employee_picture)
                                <img src="{{ asset('uploads/'.$otherUser->employee_picture) }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($otherUser->first_name ?? '?', 0, 1)) }}
                            @endif
                        </div>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></div>
                        @if($conv->unread_count > 0)
                            <div class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-red-500 border-2 border-white flex items-center justify-center text-[10px] font-bold text-white shadow-sm unread-badge">
                                {{ $conv->unread_count }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-1">
                            <h4 class="font-bold text-slate-700 truncate {{ $isActive ? 'text-brand' : '' }}">
                                {{ $otherUser->first_name ?? 'Unknown' }} {{ $otherUser->last_name ?? '' }}
                            </h4>
                            <span class="text-[10px] text-slate-400 font-medium whitespace-nowrap">
                                {{ $conv->messages->last() ? \Carbon\Carbon::parse($conv->messages->last()->added_date)->format('h:i A') : '' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 truncate">
                            {{ $conv->messages->last()->post_text ?? 'Start a conversation...' }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 bg-white rounded-[2rem] shadow-lg shadow-slate-200/50 border border-slate-100 flex flex-col overflow-hidden relative">
        @if(isset($conversation))
            @php
                $activeOtherUser = ($conversation->participantA->employee_id ?? 0) == auth()->user()->employee->employee_id 
                                ? $conversation->participantB 
                                : $conversation->participantA;
            @endphp
            
            <!-- Chat Header -->
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center font-bold border border-brand/20 overflow-hidden">
                        @if($activeOtherUser && $activeOtherUser->employee_picture)
                            <img src="{{ asset('uploads/'.$activeOtherUser->employee_picture) }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($activeOtherUser->first_name ?? '?', 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">{{ $activeOtherUser->first_name ?? 'Unknown' }} {{ $activeOtherUser->last_name ?? '' }}</h3>
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]"></div>
                            <span class="text-xs text-slate-500 font-medium">Online</span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 text-slate-400">
                    <button class="w-8 h-8 rounded-lg hover:bg-slate-100 transition-colors"><i class="fa-solid fa-phone"></i></button>
                    <button class="w-8 h-8 rounded-lg hover:bg-slate-100 transition-colors"><i class="fa-solid fa-video"></i></button>
                    <button class="w-8 h-8 rounded-lg hover:bg-slate-100 transition-colors"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                </div>
            </div>

            <!-- Messages Container -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/30 scrollbar-hide" id="messagesContainer">
                @foreach($messages as $msg)
                    @php $isMine = $msg->added_by == auth()->user()->employee->employee_id; @endphp
                    <div class="flex w-full {{ $isMine ? 'justify-end' : 'justify-start' }}">
                        <div class="flex max-w-[70%] {{ $isMine ? 'flex-row-reverse' : 'flex-row' }} gap-3">
                             @if(!$isMine)
                                <div class="w-8 h-8 rounded-full bg-brand/10 flex-shrink-0 flex items-center justify-center overflow-hidden self-end border border-brand/20">
                                    @if($msg->sender && $msg->sender->employee_picture)
                                         <img src="{{ asset('uploads/' . $msg->sender->employee_picture) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-[10px] font-bold text-brand">{{ substr($msg->sender->first_name ?? '?', 0, 1) }}</span>
                                    @endif
                                </div>
                            @endif
                            
                            <div class="flex flex-col {{ $isMine ? 'items-end' : 'items-start' }}">
                                <div class="px-5 py-3 rounded-2xl {{ $isMine ? 'bg-brand text-white rounded-br-none shadow-brand/10' : 'bg-white border border-slate-100 text-slate-700 rounded-bl-none shadow-sm' }} shadow-md">
                                    @if($msg->post_type == 'text')
                                        <p class="text-sm leading-relaxed">{{ $msg->post_text }}</p>
                                    @elseif($msg->post_type == 'image')
                                        <img src="{{ asset('uploads/'.$msg->post_text) }}" class="rounded-lg max-w-xs transition-opacity hover:opacity-90 cursor-pointer">
                                    @elseif($msg->post_type == 'document')
                                        <a href="{{ asset('uploads/'.$msg->post_text) }}" target="_blank" class="flex items-center gap-3 p-2 rounded-xl {{ $isMine ? 'bg-white/10 text-white' : 'bg-slate-50 text-slate-700' }} no-underline">
                                            <i class="fa-solid fa-file-invoice text-xl opacity-60"></i>
                                            <span class="text-xs font-bold truncate max-w-[120px]">{{ $msg->post_text }}</span>
                                        </a>
                                    @endif
                                </div>
                                <span class="text-[10px] text-slate-400 mt-1 font-medium px-1 uppercase tracking-tighter">
                                    {{ \Carbon\Carbon::parse($msg->added_date)->format('h:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t border-slate-100">
                <form action="{{ route('emp.messages.reply', $conversation->chat_id) }}" method="POST" enctype="multipart/form-data" class="flex items-end gap-3" x-data="{ hasFile: false }">
                    @csrf
                    <label class="w-10 h-10 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-400 transition-colors flex-shrink-0 flex items-center justify-center cursor-pointer">
                        <i class="fa-solid fa-paperclip" :class="{ 'text-brand': hasFile }"></i>
                        <input type="file" name="attachment" class="hidden" @change="hasFile = true">
                    </label>
                    
                    <div class="flex-1 bg-slate-50 rounded-xl border border-slate-200 focus-within:border-brand focus-within:ring-2 focus-within:ring-brand/10 transition-all">
                        <textarea name="post_text" rows="1" placeholder="Type your message..." class="w-full bg-transparent border-none focus:ring-0 px-4 py-3 text-sm text-slate-700 resize-none max-h-32 scrollbar-hide" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                    </div>

                    <button type="submit" class="w-10 h-10 rounded-xl bg-brand hover:brightness-110 text-white shadow-lg shadow-brand/20 transition-all transform hover:scale-105 active:scale-95 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
            
            <script>
                const container = document.getElementById('messagesContainer');
                container.scrollTop = container.scrollHeight;
            </script>

        @else
            <!-- Empty State -->
            <div class="flex-1 flex flex-col items-center justify-center text-slate-300 p-12 text-center">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6 shadow-sm">
                    <i class="fa-regular fa-paper-plane text-4xl text-brand/30"></i>
                </div>
                <h3 class="text-xl font-display font-bold text-slate-400">Messaging Hub</h3>
                <p class="text-sm text-slate-400 mt-2 max-w-xs">Connect with your team instantly. Select a conversation to start chatting.</p>
            </div>
        @endif
    </div>
</div>

<!-- New Chat Modal -->
<div id="newChatModal" class="modal">
    <div class="modal-backdrop" @click="closeModal('newChatModal')"></div>
    <div class="modal-content w-full max-w-md p-0 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-xl font-display font-bold text-slate-800">Start New Chat</h3>
            <button onclick="closeModal('newChatModal')" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <form action="{{ route('emp.messages.store') }}" method="POST" class="p-8">
            @csrf
            <div class="mb-8">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Target Colleague</label>
                <div class="relative group">
                    <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-brand transition-colors"></i>
                    <select name="employee_id" class="w-full premium-input pl-12 h-12 text-sm" required>
                        <option value="">Choose a colleague...</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeModal('newChatModal')" class="flex-1 px-6 py-3.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all">Cancel</button>
                <button type="submit" class="flex-1 px-6 py-3.5 rounded-xl bg-brand text-white font-bold shadow-xl shadow-brand/20 hover:scale-[1.02] active:scale-95 transition-all">
                    Start Chatting
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
