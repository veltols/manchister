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
                        @if($chatPartner && $chatPartner->status)
                            <div class="flex items-center gap-1.5">
                                <div class="w-2 h-2 rounded-full shadow-[0_0_8px_rgba(0,0,0,0.1)]" style="background-color: {{ $chatPartner->status->staus_color }}"></div>
                                <span class="text-xs text-slate-500 font-medium">{{ $chatPartner->status->staus_name }}</span>
                            </div>
                        @else
                             <div class="flex items-center gap-1.5">
                                <div class="w-2 h-2 rounded-full bg-slate-300"></div>
                                <span class="text-xs text-slate-500 font-medium">Offline</span>
                            </div>
                        @endif
                    </div>
                </div>
                
            </div>

            <!-- Messages Stream -->
            <div class="flex-1 overflow-hidden bg-slate-50/30">
                <div id="messagesContainer" style="overflow-y: auto !important; height: 100% !important; padding: 1.5rem; display: flex; flex-direction: column; gap: 1.5rem;">
                    @foreach($messages as $msg)
                        @php
                            $isMine = $msg->added_by == $adminId;
                        @endphp
                        <div id="msg-{{ $msg->post_id }}" class="flex w-full {{ $isMine ? 'justify-end' : 'justify-start' }}">
                            <div class="flex max-w-[70%] {{ $isMine ? 'flex-row-reverse' : 'flex-row' }} gap-3">
                                @if(!$isMine)
                                    <div class="w-8 h-8 rounded-full bg-brand/10 flex-shrink-0 flex items-center justify-center overflow-hidden self-end border border-brand/20">
                                        @if($msg->sender->employee_picture)
                                            <img src="{{ asset('uploads/' . $msg->sender->employee_picture) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-[10px] font-bold text-brand">{{ substr($msg->sender->first_name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="flex flex-col {{ $isMine ? 'items-end' : 'items-start' }}">
                                    <div class="px-5 py-3 rounded-2xl {{ $isMine ? 'bg-brand text-white rounded-br-none shadow-brand/10' : 'bg-white border border-slate-100 text-slate-700 rounded-bl-none shadow-sm' }} shadow-md">
                                        @if($msg->post_type == 'text')
                                            <p class="text-sm leading-relaxed">{{ $msg->post_text }}</p>
                                        @elseif($msg->post_type == 'image')
                                            <img src="{{ asset('uploads/'.$msg->post_text) }}" class="rounded-lg max-w-xs transition-opacity hover:opacity-90 cursor-pointer" onclick="window.open(this.src)">
                                        @elseif($msg->post_type == 'document')
                                            <a href="{{ asset('uploads/'.$msg->post_text) }}" target="_blank" class="flex items-center gap-3 p-2 rounded-xl {{ $isMine ? 'bg-white/10 text-white' : 'bg-slate-50 text-slate-700' }} no-underline hover:bg-white/20 transition-colors">
                                                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                                    <i class="fa-solid fa-file-invoice text-lg"></i>
                                                </div>
                                                <div class="flex flex-col min-w-0">
                                                    <span class="text-xs font-bold truncate max-w-[150px]">{{ $msg->post_text }}</span>
                                                    <span class="text-[10px] opacity-70">Document</span>
                                                </div>
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
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t border-slate-100">
                <div id="chat-attachment-preview" class="px-4 mb-2"></div>
                <form action="{{ route('admin.messages.store') }}" method="POST" enctype="multipart/form-data" class="flex items-end gap-3" id="admin-message-form">
                    @csrf
                    <input type="hidden" name="chat_id" value="{{ $activeChat->chat_id }}">
                    
                    <label class="w-10 h-10 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-400 transition-colors flex-shrink-0 flex items-center justify-center cursor-pointer group">
                        <i class="fa-solid fa-paperclip group-hover:text-slate-600 transition-colors" id="chat-paperclip-icon"></i>
                        <input type="file" name="attachment" id="chat_attachment" class="hidden">
                    </label>
                    
                    <div class="flex-1 bg-slate-50 rounded-xl border border-slate-200 transition-all">
                        <textarea name="message" rows="1" placeholder="Type your message..." class="w-full bg-transparent border-none focus:ring-0 px-4 py-3 text-sm text-slate-700 resize-none max-h-32" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                    </div>

                    <button type="submit" class="w-10 h-10 rounded-xl bg-gradient-to-r from-brand to-cyan-600 hover:from-cyan-500 hover:to-brand text-white shadow-lg shadow-brand/30 transition-all transform hover:scale-105 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
            <script src="{{ asset('js/attachment-preview.js') }}"></script>
            <script>
                // Initialize Attachment Preview for Chat
                console.log('Admin: Initializing Attachment Preview');
                window.attachmentPreviewInstance = window.initAttachmentPreview({
                    inputSelector: '#chat_attachment',
                    containerSelector: '#chat-attachment-preview',
                    onRemove: () => {
                        const icon = document.getElementById('chat-paperclip-icon');
                        if(icon) {
                            icon.classList.remove('text-brand');
                            icon.classList.add('text-slate-400');
                        }
                    }
                });

                // Add listener to show indicator when file is selected
                const fileInputInit = document.getElementById('chat_attachment');
                if(fileInputInit) {
                    fileInputInit.addEventListener('change', function() {
                        console.log('Admin: File input changed', this.files.length);
                        const icon = document.getElementById('chat-paperclip-icon');
                        if(this.files && this.files.length > 0) {
                            if(icon) {
                                icon.classList.remove('text-slate-400');
                                icon.classList.add('text-brand');
                            }
                        } else {
                            if(icon) {
                                icon.classList.remove('text-brand');
                                icon.classList.add('text-slate-400');
                            }
                        }
                    });
                }
            </script>
            
            <script>
                // Auto-scroll to bottom on page load
                const container = document.getElementById('messagesContainer');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }

                @if($activeChat)
                // Real-time messaging variables
                let lastMessageId = {{ $messages->last()->post_id ?? 0 }};
                const chatId = {{ $activeChat->chat_id }};
                const currentUserId = {{ $adminId }};
                let pollingInterval;

                // Poll for new messages every 3 seconds
                function pollNewMessages() {
                    fetch(`/admin/messages/${chatId}/fetch?last_message_id=${lastMessageId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.messages.length > 0) {
                                data.messages.forEach(msg => {
                                    appendMessage(msg);
                                    lastMessageId = msg.post_id;
                                });
                                
                                // Scroll to bottom
                                container.scrollTop = container.scrollHeight;
                            }
                        })
                        .catch(error => console.error('Error fetching messages:', error));
                }

                // Append new message to chat
                function appendMessage(msg) {
                    if (document.getElementById(`msg-${msg.post_id}`)) return;

                    const isMine = msg.added_by == currentUserId;
                    
                    let contentHtml = '';
                    if (msg.post_type === 'image') {
                        contentHtml = `<img src="/uploads/${msg.post_text}" class="rounded-lg max-w-xs transition-opacity hover:opacity-90 cursor-pointer" onclick="window.open(this.src)">`;
                    } else if (msg.post_type === 'document') {
                         contentHtml = `
                            <a href="/uploads/${msg.post_text}" target="_blank" class="flex items-center gap-3 p-2 rounded-xl ${isMine ? 'bg-white/10 text-white' : 'bg-slate-50 text-slate-700'} no-underline hover:bg-white/20 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                    <i class="fa-solid fa-file-invoice text-lg"></i>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-xs font-bold truncate max-w-[150px]">${msg.post_text}</span>
                                    <span class="text-[10px] opacity-70">Document</span>
                                </div>
                            </a>`;
                    } else {
                        contentHtml = `<p class="text-sm leading-relaxed">${msg.post_text}</p>`;
                    }

                    const messageHtml = `
                        <div id="msg-${msg.post_id}" class="flex w-full ${isMine ? 'justify-end' : 'justify-start'} animate-fade-in-up">
                            <div class="flex max-w-[70%] ${isMine ? 'flex-row-reverse' : 'flex-row'} gap-3">
                                ${!isMine ? `
                                    <div class="w-8 h-8 rounded-full bg-brand/10 flex-shrink-0 flex items-center justify-center overflow-hidden self-end border border-brand/20">
                                        ${msg.sender && msg.sender.employee_picture ? 
                                            `<img src="/uploads/${msg.sender.employee_picture}" class="w-full h-full object-cover">` :
                                            `<span class="text-[10px] font-bold text-brand">${msg.sender ? msg.sender.first_name.charAt(0) : '?'}</span>`
                                        }
                                    </div>
                                ` : ''}
                                
                                <div class="flex flex-col ${isMine ? 'items-end' : 'items-start'}">
                                    <div class="px-5 py-3 rounded-2xl ${isMine ? 'bg-brand text-white rounded-br-none shadow-brand/10' : 'bg-white border border-slate-100 text-slate-700 rounded-bl-none shadow-sm'} shadow-md">
                                        ${contentHtml}
                                    </div>
                                    <span class="text-[10px] text-slate-400 mt-1 font-medium px-1 uppercase tracking-tighter">
                                        ${new Date(msg.added_date).toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'})}
                                    </span>
                                </div>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', messageHtml);
                }

                // Start polling
                pollingInterval = setInterval(pollNewMessages, 3000);

                // Send message via Ajax
                const messageForm = document.querySelector('form[action="{{ route('admin.messages.store') }}"]');
                if (messageForm) {
                    messageForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        const formData = new FormData(this);
                        const messageText = formData.get('message');
                        const fileInput = document.getElementById('chat_attachment');
                        const hasFile = fileInput && fileInput.files.length > 0;
                        
                        if ((!messageText || !messageText.trim()) && !hasFile) return;
                        
                        // Disable submit button
                        const submitBtn = this.querySelector('button[type="submit"]');
                        if(submitBtn) submitBtn.disabled = true;
                        
                        fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Message sent successfully
                                appendMessage(data.message);
                                lastMessageId = data.message.post_id;
                                
                                // Scroll to bottom
                                container.scrollTop = container.scrollHeight;
                                
                                // Clear textarea
                                const textArea = this.querySelector('textarea[name="message"]');
                                if(textArea) {
                                    textArea.value = '';
                                    textArea.style.height = '';
                                }

                                if(fileInput) fileInput.value = '';
                                
                                // Reset preview
                                const previewContainer = document.getElementById('chat-attachment-preview');
                                if(previewContainer) previewContainer.innerHTML = '';
                                
                                const icon = document.getElementById('chat-paperclip-icon');
                                if(icon) {
                                    icon.classList.remove('text-brand');
                                    icon.classList.add('text-slate-400');
                                }
                                
                                if(window.attachmentPreviewInstance) {
                                    window.attachmentPreviewInstance.clearPreview();
                                }

                                // Re-enable submit button
                                if(submitBtn) submitBtn.disabled = false;
                            } else {
                                console.error('Error sending message:', data);
                                if(submitBtn) submitBtn.disabled = false;
                            }
                        })
                        .catch(error => {
                            console.error('Error sending message:', error);
                            if(submitBtn) submitBtn.disabled = false;
                        });
                    });
                }

                // Stop polling when leaving page
                window.addEventListener('beforeunload', function() {
                    clearInterval(pollingInterval);
                });
                @endif

                // Poll conversation list for unread count updates (every 5 seconds)
                function updateConversationList() {
                    fetch('/admin/messages-conversation-list')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Admin Conversation list data:', data);
                                const conversationContainer = document.querySelector('.flex-1.overflow-y-auto');
                                if (!conversationContainer) {
                                    console.error('Conversation container not found');
                                    return;
                                }

                                // Sort conversations: unread first, then by chat_id (most recent)
                                const sortedConversations = data.conversations.sort((a, b) => {
                                    if (a.unread_count > 0 && b.unread_count === 0) return -1;
                                    if (a.unread_count === 0 && b.unread_count > 0) return 1;
                                    return b.chat_id - a.chat_id;
                                });

                                // Update each conversation
                                sortedConversations.forEach((conv, index) => {
                                    const convLink = document.querySelector(`a[href*="/admin/messages?chat_id=${conv.chat_id}"]`);
                                    
                                    if (convLink) {
                                        const listItem = convLink;
                                        
                                        // Move to correct position
                                        const currentIndex = Array.from(conversationContainer.children).indexOf(listItem);
                                        if (currentIndex !== index && currentIndex !== -1) {
                                            conversationContainer.insertBefore(listItem, conversationContainer.children[index]);
                                        }
                                        
                                        // Update badge
                                        let badgeContainer = convLink.querySelector('.unread-badge');
                                        
                                        if (conv.unread_count > 0) {
                                            if (badgeContainer) {
                                                // Update existing badge
                                                if (badgeContainer.textContent !== conv.unread_count.toString()) {
                                                    badgeContainer.textContent = conv.unread_count;
                                                    badgeContainer.style.animation = 'pulse 0.5s';
                                                    setTimeout(() => badgeContainer.style.animation = '', 500);
                                                }
                                            } else {
                                                // Create new badge
                                                const avatarDiv = convLink.querySelector('.relative');
                                                if (avatarDiv) {
                                                    const newBadge = document.createElement('div');
                                                    newBadge.className = 'absolute -top-1 -right-1 w-5 h-5 rounded-full bg-red-500 border-2 border-white flex items-center justify-center text-[10px] font-bold text-white shadow-sm unread-badge';
                                                    newBadge.textContent = conv.unread_count;
                                                    avatarDiv.appendChild(newBadge);
                                                }
                                            }
                                        } else {
                                            // Remove badge if count is 0
                                            if (badgeContainer) {
                                                badgeContainer.remove();
                                            }
                                        }
                                    }
                                });
                            }
                        })
                        .catch(error => console.error('Error updating conversation list:', error));
                }

                // Poll every 5 seconds
                setInterval(updateConversationList, 5000);
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
    <div class="modal-content w-full max-w-md p-0 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-xl font-display font-bold text-slate-800">Start New Chat</h3>
            <button onclick="closeModal('newChatModal')" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <form action="{{ route('admin.messages.create') }}" method="POST" class="p-8">
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
