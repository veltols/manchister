<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->employee_id ?? 0;

        // Fetch conversations where user is A or B
        $conversations = Conversation::where('a_id', $userId)
            ->orWhere('b_id', $userId)
            ->with(['participantA', 'participantB'])
            ->orderBy('last_updated', 'desc') // Assuming last_updated exists, else order by chat_id
            ->get();

        // Pass available employees for new chat
        $employees = Employee::where('employee_id', '!=', $userId)
            ->where('is_deleted', 0)
            ->orderBy('first_name')
            ->get();

        return view('hr.messages.index', compact('conversations', 'employees'));
    }

    public function show($id)
    {
        $userId = Auth::user()->employee_id ?? 0;

        $conversation = Conversation::where('chat_id', $id)
            ->where(function ($q) use ($userId) {
                $q->where('a_id', $userId)->orWhere('b_id', $userId);
            })
            ->with(['messages.sender'])
            ->firstOrFail();

        // Mark messages as read logic would go here (update is_seen = 1)

        // Pass available employees for sidebar if needed, or AJAX load
        $employees = Employee::where('employee_id', '!=', $userId)
            ->where('is_deleted', 0)
            ->orderBy('first_name')
            ->get();

        $conversations = Conversation::where('a_id', $userId)
            ->orWhere('b_id', $userId)
            ->with(['participantA', 'participantB'])
            ->orderBy('chat_id', 'desc')
            ->get();

        return view('hr.messages.show', compact('conversation', 'conversations', 'employees'));
    }

    public function store(Request $request)
    {
        // Start new chat
        $request->validate([
            'employee_id' => 'required|exists:employees_list,employee_id'
        ]);

        $senderId = Auth::user()->employee_id ?? 0;
        $receiverId = $request->employee_id;

        // Check existing chat
        $existing = Conversation::where(function ($q) use ($senderId, $receiverId) {
            $q->where('a_id', $senderId)->where('b_id', $receiverId);
        })->orWhere(function ($q) use ($senderId, $receiverId) {
            $q->where('a_id', $receiverId)->where('b_id', $senderId);
        })->first();

        if ($existing) {
            return redirect()->route('messages.show', $existing->chat_id);
        }

        $chat = new Conversation();
        $chat->a_id = $senderId;
        $chat->b_id = $receiverId;
        $chat->start_time = now();
        $chat->last_updated = now();
        $chat->save();

        return redirect()->route('messages.show', $chat->chat_id);
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message_text' => 'required|string'
        ]);

        $msg = new Message();
        $msg->chat_id = $id;
        $msg->post_content = $request->message_text; // Assuming post_content is the field
        $msg->added_by = Auth::user()->employee_id ?? 0;
        $msg->added_date = now();
        $msg->is_seen = 0;
        $msg->save();

        // Update conversation last_updated
        $conv = Conversation::find($id);
        if ($conv) {
            $conv->last_updated = now(); // Update timestamp if possible
            $conv->save();
        }

        return redirect()->route('messages.show', $id);
    }
}
