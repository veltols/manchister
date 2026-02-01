<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Fetch conversations where user is A or B
        $conversations = Conversation::with(['participantA', 'participantB'])
            ->where('a_id', $userId)
            ->orWhere('b_id', $userId)
            ->orderBy('chat_id', 'desc')
            ->get();
            
        // Process to find the "Other" user for display
        $conversations->each(function($chat) use ($userId) {
            if($chat->a_id == $userId){
                $chat->otherUser = $chat->participantB;
            } else {
                $chat->otherUser = $chat->participantA;
            }
            
            // Count unread
            $chat->unreadCount = Message::where('chat_id', $chat->chat_id)
                ->where('added_by', '!=', $userId)
                ->where('is_read', 0)
                ->count();
            
            // Last message
            $chat->lastMessage = Message::where('chat_id', $chat->chat_id)->latest('post_id')->first();
        });

        $employees = Employee::where('is_deleted', 0)
                   ->where('employee_id', '!=', $userId)
                   ->where('is_hidden', 0)
                   ->orderBy('first_name')
                   ->get();

        return view('emp.messages.index', compact('conversations', 'employees'));
    }

    public function show($id)
    {
        $userId = Auth::id();
        $conversation = Conversation::with(['participantA', 'participantB'])->findOrFail($id);

        if($conversation->a_id != $userId && $conversation->b_id != $userId){
            abort(403);
        }

        // Determine other user
        if($conversation->a_id == $userId){
            $otherUser = $conversation->participantB;
        } else {
            $otherUser = $conversation->participantA;
        }

        // Mark messages as read
        Message::where('chat_id', $id)
            ->where('added_by', '!=', $userId)
            ->update(['is_read' => 1]);

        $messages = Message::where('chat_id', $id)
            ->with('sender')
            ->orderBy('post_id', 'asc')
            ->get();

        return view('emp.messages.show', compact('conversation', 'messages', 'otherUser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees_list,employee_id',
            'message' => 'nullable|string', // Initial message optional if creating chat?
        ]);

        $userId = Auth::id();
        $targetId = $request->employee_id;

        // Check if chat exists
        $chat = Conversation::where(function($q) use ($userId, $targetId){
                $q->where('a_id', $userId)->where('b_id', $targetId);
            })->orWhere(function($q) use ($userId, $targetId){
                $q->where('a_id', $targetId)->where('b_id', $userId);
            })->first();

        if(!$chat){
            $chat = new Conversation();
            $chat->a_id = $userId;
            $chat->b_id = $targetId;
            $chat->added_by = $userId;
            $chat->added_date = now();
            $chat->save();
        }

        // If message provided, send it
        if($request->filled('message')){
            $msg = new Message();
            $msg->chat_id = $chat->chat_id;
            $msg->added_by = $userId;
            $msg->post_text = $request->message;
            $msg->post_type = 'text';
            $msg->added_date = now();
            $msg->save();
        }

        return redirect()->route('emp.messages.show', $chat->chat_id);
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $msg = new Message();
        $msg->chat_id = $id;
        $msg->added_by = Auth::id();
        $msg->post_text = $request->message;
        $msg->post_type = 'text';
        $msg->added_date = now();
        $msg->save();

        return redirect()->back();
    }
}
