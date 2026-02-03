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
        $employeeId = Auth::user()->employee->employee_id ?? 0;

        // Fetch conversations where user is a participant
        $conversations = Conversation::where('a_id', $employeeId)
            ->orWhere('b_id', $employeeId)
            ->with(['participantA', 'participantB'])
            ->get();
            
        // Calculate unread counts for each conversation
        foreach ($conversations as $conv) {
            $conv->unread_count = Message::where('chat_id', $conv->chat_id)
                ->where('added_by', '!=', $employeeId)
                ->where('is_read', 0)
                ->count();
        }

        // Sort by unread or latest message
        $conversations = $conversations->sortByDesc(function($conv) {
            return $conv->unread_count > 0 ? 1 : 0;
        });

        $employees = Employee::where('employee_id', '!=', $employeeId)
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->orderBy('first_name')
            ->get();

        return view('emp.messages.index', compact('conversations', 'employees'));
    }

    public function show($id)
    {
        $employeeId = Auth::user()->employee->employee_id ?? 0;

        $conversation = Conversation::where('chat_id', $id)
            ->where(function ($q) use ($employeeId) {
                $q->where('a_id', $employeeId)->orWhere('b_id', $employeeId);
            })
            ->with(['participantA', 'participantB'])
            ->firstOrFail();

        // Mark messages as read
        Message::where('chat_id', $id)
            ->where('added_by', '!=', $employeeId)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        $messages = Message::where('chat_id', $id)
            ->with('sender')
            ->orderBy('added_date', 'asc')
            ->get();

        $conversations = Conversation::where('a_id', $employeeId)
            ->orWhere('b_id', $employeeId)
            ->with(['participantA', 'participantB'])
            ->get();
            
        foreach ($conversations as $conv) {
            $conv->unread_count = Message::where('chat_id', $conv->chat_id)
                ->where('added_by', '!=', $employeeId)
                ->where('is_read', 0)
                ->count();
        }

        $employees = Employee::where('employee_id', '!=', $employeeId)
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->orderBy('first_name')
            ->get();

        return view('emp.messages.index', compact('conversation', 'messages', 'conversations', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees_list,employee_id'
        ]);

        $senderId = Auth::user()->employee->employee_id ?? 0;
        $receiverId = $request->employee_id;

        // Check legacy: z_messages_list uses a_id and b_id
        $existing = Conversation::where(function ($q) use ($senderId, $receiverId) {
            $q->where('a_id', $senderId)->where('b_id', $receiverId);
        })->orWhere(function ($q) use ($senderId, $receiverId) {
            $q->where('a_id', $receiverId)->where('b_id', $senderId);
        })->first();

        if ($existing) {
            return redirect()->route('emp.messages.show', $existing->chat_id);
        }

        $chat = new Conversation();
        $chat->a_id = $senderId;
        $chat->b_id = $receiverId;
        $chat->added_by = $senderId;
        $chat->added_date = now();
        $chat->is_archieve = 0;
        $chat->is_deleted = 0;
        $chat->save();

        return redirect()->route('emp.messages.show', $chat->chat_id);
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'post_text' => 'required_without:attachment|nullable|string',
            'attachment' => 'nullable|file|max:10240'
        ]);

        $employeeId = Auth::user()->employee->employee_id ?? 0;

        $msg = new Message();
        $msg->chat_id = $id;
        $msg->post_text = $request->post_text ?? '';
        $msg->added_by = $employeeId;
        $msg->added_date = now();
        $msg->is_read = 0;
        $msg->post_type = 'text';

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $msg->post_text = $fileName; // Legacy sometimes stores filename in text or specific field
            $msg->post_type = 'document';
            // If it's an image
            if (str_contains($file->getMimeType(), 'image')) {
                $msg->post_type = 'image';
            }
        }

        $msg->save();

        return redirect()->back();
    }
}
