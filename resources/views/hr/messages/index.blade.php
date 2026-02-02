@extends('layouts.app')

@section('title', 'Messages')
@section('subtitle', 'Internal Communication')

@section('content')
    <div class="h-[calc(100vh-12rem)] flex gap-6 animate-fade-in-up">

        <!-- Sidebar: Conversation List -->
        <div class="w-1/3 flex flex-col gap-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold font-display text-premium">Chats</h2>
                <button onclick="document.getElementById('newChatModal').showModal()"
                    class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-100 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                @forelse($conversations as $conv)
                    <a href="{{ route('messages.show', $conv->chat_id) }}"
                        class="block p-4 rounded-xl bg-white border border-slate-100 hover:border-indigo-200 transition-all group">
                        @php
                            $user = Auth::user();
                            $myId = $user->employee_id ?? 0;
                            $other = ($conv->a_id == $myId) ? $conv->participantB : $conv->participantA;
                            $lastMsg = $conv->messages->last();
                        @endphp
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold overflow-hidden">
                                @if($other && $other->employee_profile_pic)
                                    <img src="{{ asset($other->employee_profile_pic) }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($other->first_name ?? 'U', 0, 1) }}{{ substr($other->last_name ?? 'U', 0, 1) }}
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-0.5">
                                    <h3 class="font-bold text-slate-700 truncate group-hover:text-indigo-600">
                                        {{ $other->first_name ?? 'Unknown' }} {{ $other->last_name ?? '' }}</h3>
                                    <span
                                        class="text-[10px] text-slate-400">{{ $lastMsg ? \Carbon\Carbon::parse($lastMsg->added_date)->shortAbsoluteDiffForHumans() : '' }}</span>
                                </div>
                                <p class="text-sm text-slate-500 truncate">
                                    {{ $lastMsg->post_content ?? 'Start a conversation' }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-10 text-slate-400">
                        <i class="fa-regular fa-comments text-3xl mb-3 opacity-30"></i>
                        <p>No conversations yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Main Area: Chat Window (Empty State) -->
        <div class="flex-1 premium-card flex items-center justify-center bg-slate-50/50 border-dashed">
            <div class="text-center text-slate-400">
                <div class="w-16 h-16 rounded-full bg-slate-100 mx-auto flex items-center justify-center mb-4">
                    <i class="fa-regular fa-paper-plane text-2xl text-slate-300"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-600 mb-2">Select a Conversation</h3>
                <p>Choose a chat from the left or start a new one.</p>
            </div>
        </div>

    </div>

    <!-- New Chat Modal -->
    <dialog id="newChatModal" class="modal rounded-2xl shadow-2xl p-0 w-full max-w-md backdrop:bg-slate-900/50">
        <div class="bg-white">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-premium">New Message</h3>
                <button onclick="document.getElementById('newChatModal').close()"
                    class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <form action="{{ route('messages.store') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Select
                            Employee</label>
                        <div class="relative">
                            <select name="employee_id" class="premium-input w-full appearance-none" required>
                                <option value="">Choose Recipient...</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->employee_id }}">{{ $emp->first_name }} {{ $emp->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            <i
                                class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <button class="premium-button w-full py-3">Start Chat</button>
                </form>
            </div>
        </div>
    </dialog>
@endsection
