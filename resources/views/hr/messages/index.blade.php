@extends('layouts.app')

@section('title', 'Messages')
@section('subtitle', 'Connect and collaborate with your team.')

@section('content')
<div class="messages-layout">
    <!-- Sidebar: Conversations List -->
    <div class="messages-sidebar">
        <div class="sidebar-header">
            <h2 class="text-xl font-bold text-premium">Chats</h2>
            <button onclick="openModal('newChatModal')"
                class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>

        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" class="premium-input w-full pl-11 pr-4 py-2.5 text-sm bg-white" placeholder="Search chats...">
            </div>
        </div>

        <div class="conversations-list space-y-1 p-2">
            @forelse($conversations as $conv)
            @php
                $myId = optional(Auth::user()->employee)->employee_id ?? 0;
                
                // Get participants safely, defaulting to null if relationship is broken
                $partA = $conv->participantA;
                $partB = $conv->participantB;
                
                // Determine 'other' user. If A is me, other is B. If relationships are missing, handled gracefully below.
                if ($partA && $partA->employee_id == $myId) {
                    $otherUser = $partB;
                } else {
                    $otherUser = $partA;
                }
                
                $isActive = isset($conversation) && $conversation->chat_id == $conv->chat_id;
            @endphp
            
            @if($otherUser)
            <a href="{{ route('hr.messages.show', $conv->chat_id) }}" 
               class="conversation-item p-3 rounded-xl flex items-center gap-3 hover:bg-slate-50 transition-all {{ $isActive ? 'bg-indigo-50/60 ring-1 ring-indigo-100' : '' }}">
                <div class="relative">
                    <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-lg overflow-hidden">
                        @if($otherUser->image_path) 
                            <img src="{{ asset('uploads/'.$otherUser->image_path) }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($otherUser->first_name, 0, 1) }}
                        @endif
                    </div>
                    @if($conv->unread_count > 0)
                    <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold flex items-center justify-center rounded-full border-2 border-white">
                        {{ $conv->unread_count }}
                    </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-baseline mb-0.5">
                        <h4 class="font-bold text-slate-800 truncate {{ $isActive ? 'text-indigo-700' : '' }}">
                            {{ $otherUser->first_name }} {{ $otherUser->last_name }}
                        </h4>
                        <!-- <span class="text-[10px] text-slate-400">12:30 PM</span> -->
                    </div>
                    <p class="text-xs text-slate-500 truncate">
                        Click to view conversation
                    </p>
                </div>
            </a>
            @endif
            @empty
            <div class="text-center py-10">
                <p class="text-slate-400 text-sm">No conversations yet</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Main Content: Chat Area -->
    <div class="messages-main">
        @if(isset($conversation) && $conversation)
            @php
                $currentUser = optional(Auth::user()->employee)->employee_id ?? 0;
                
                $partA = $conversation->participantA;
                $partB = $conversation->participantB;
                
                if ($partA && $partA->employee_id == $currentUser) {
                    $chatPartner = $partB;
                } else {
                    $chatPartner = $partA;
                }
            @endphp
            @if($chatPartner)
            <!-- Chat Header -->
            <div class="p-4 border-b border-slate-100 bg-white flex justify-between items-center shadow-sm z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold overflow-hidden">
                         @if($chatPartner->image_path) 
                            <img src="{{ asset('uploads/'.$chatPartner->image_path) }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($chatPartner->first_name, 0, 1) }}
                        @endif
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">{{ $chatPartner->first_name }} {{ $chatPartner->last_name }}</h3>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            <span class="text-xs text-slate-500">Online</span>
                        </div>
                    </div>
                </div>
                <!-- <div class="flex gap-2">
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
                        <i class="fa-solid fa-phone"></i>
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
                        <i class="fa-solid fa-video"></i>
                    </button> 
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                </div> -->
            </div>

            <!-- Messages Area -->
            <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50 scroll-smooth">
                @foreach($messages as $msg)
                    @php 
                        $isMe = $msg->sender->employee_id == $currentUser;
                    @endphp
                    <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[70%] {{ $isMe ? 'order-1' : 'order-2' }}">
                            <div class="px-5 py-3 rounded-2xl shadow-sm text-sm leading-relaxed {{ $isMe ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white text-slate-700 rounded-bl-none border border-slate-100' }}">
                                @if($msg->post_type == 'image')
                                    <img src="{{ asset('uploads/'.$msg->post_file_path) }}" class="rounded-lg mb-2 max-w-full">
                                    @if($msg->post_text != $msg->post_file_path)<p>{{ $msg->post_text }}</p>@endif
                                @elseif($msg->post_type == 'document')
                                    <a href="{{ asset('uploads/'.$msg->post_file_path) }}" target="_blank" class="flex items-center gap-2 p-2 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
                                        <i class="fa-solid fa-file-arrow-down"></i>
                                        <span class="truncate">{{ $msg->post_file_name ?? 'Download File' }}</span>
                                    </a>
                                    @if($msg->post_text)<p class="mt-2">{{ $msg->post_text }}</p>@endif
                                @else
                                    <p>{{ $msg->post_text }}</p>
                                @endif
                            </div>
                            <span class="text-[10px] text-slate-400 mt-1 block {{ $isMe ? 'text-right' : 'text-left' }}">
                                {{ \Carbon\Carbon::parse($msg->added_date)->format('h:i A') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t border-slate-100">
                <form onsubmit="sendMessage(event)" enctype="multipart/form-data" class="flex items-end gap-3">
                    @csrf
                    <button type="button" onclick="document.getElementById('file-input').click()" class="w-10 h-10 flex-shrink-0 flex items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                        <i class="fa-solid fa-paperclip"></i>
                    </button>
                    <input type="file" name="attachment" id="file-input" class="hidden" onchange="//show preview">
                    
                    <div class="flex-1 bg-slate-50 rounded-xl border border-slate-200 focus-within:border-indigo-300 focus-within:ring-2 focus-within:ring-indigo-100/50 transition-all">
                        <textarea name="post_text" rows="1" class="w-full bg-transparent border-none focus:ring-0 p-3 text-sm resize-none max-h-32" placeholder="Type a message..."></textarea>
                    </div>
                    
                    <button type="submit" class="w-12 h-10 flex-shrink-0 flex items-center justify-center rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:scale-105 transition-all">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>

            <script>
                // Auto scroll to bottom
                const container = document.getElementById('messages-container');
                container.scrollTop = container.scrollHeight;

                async function sendMessage(e) {
                    e.preventDefault();
                    const formData = new FormData(e.target);
                    const btn = e.target.querySelector('button[type="submit"]');
                    
                    // Optimistic UI (optional, skipping for simplicity)
                    
                    try {
                        const response = await fetch("{{ route('hr.messages.reply', $conversation->chat_id) }}", {
                            method: 'POST',
                            body: formData
                        });
                        const result = await response.json();
                        if(result.success) {
                            window.location.reload(); // Simple reload for now to fetch new message
                        }
                    } catch(err) { console.error(err); }
                }
            </script>
            @else
            <div class="h-full flex flex-col items-center justify-center p-12 text-center text-slate-400">
                <i class="fa-solid fa-user-slash text-4xl mb-4"></i>
                <p>Chat partner not found or account deleted.</p>
            </div>
            @endif
        @endif
    </div>
</div>

<!-- New Chat Modal -->
<div class="modal" id="newChatModal">
    <div class="modal-backdrop" onclick="closeModal('newChatModal')"></div>
    <div class="modal-content max-w-md p-0 border-none shadow-2xl">
        <div class="p-6 bg-slate-900 text-white flex justify-between items-center rounded-t-[24px]">
            <h2 class="text-xl font-bold">New Message</h2>
            <button onclick="closeModal('newChatModal')" class="text-white/60 hover:text-white"><i class="fa-solid fa-times"></i></button>
        </div>
        
        <form onsubmit="startChat(event)" class="p-6 space-y-6">
            @csrf
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Select Employee</label>
                <select name="employee_id" required class="premium-input w-full h-11 text-sm bg-white">
                    <option value="">Choose...</option>
                    @foreach($employees as $emp)
                    <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="w-full premium-button from-indigo-600 to-purple-600 text-white font-bold py-3 rounded-2xl shadow-lg shadow-indigo-100 justify-center">
                Start Conversation
            </button>
        </form>
    </div>
</div>

<style>
    .messages-layout {
        display: grid;
        grid-template-columns: 320px 1fr;
        height: calc(100vh - 145px);
        background: white;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    }

    .messages-sidebar {
        border-right: 1px solid #f1f5f9;
        background: #fbfcfd;
        display: flex;
        flex-direction: column;
    }

    .sidebar-header {
        padding: 20px;
        background: white;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .conversations-list {
        overflow-y: auto;
        flex: 1;
    }

    .messages-main {
        display: flex;
        flex-direction: column;
        height: 100%;
        background: url('{{ asset("assets/img/chat-bg-pattern.png") }}'); /* Optional pattern */
        background-color: #ffffff;
    }
</style>

<script>
    async function startChat(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        
        try {
            const response = await fetch("{{ route('hr.messages.store') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });
            const result = await response.json();
            if(result.success) {
                window.location.href = "{{ url('hr/messages') }}/" + result.chat_id;
            }
        } catch(err) { console.error(err); }
    }
</script>
@endsection
