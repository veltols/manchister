<?php

namespace App\Http\Controllers\HR;

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
        $employeeId = optional(Auth::user()->employee)->employee_id ?? 0;

        // Fetch conversations where user is a participant
        $conversations = Conversation::where('a_id', $employeeId)
            ->orWhere('b_id', $employeeId)
            ->with(['participantA.status', 'participantB.status'])
            ->get();

        // Calculate unread counts for each conversation
        foreach ($conversations as $conv) {
            $conv->unread_count = Message::where('chat_id', $conv->chat_id)
                ->where('added_by', '!=', $employeeId)
                ->where('is_read', 0)
                ->count();
        }

        // Sort by unread or latest message (descending chat_id as proxy for recency if added_date invalid)
        $conversations = $conversations->sortByDesc(function ($conv) {
            return [$conv->unread_count > 0 ? 1 : 0, $conv->chat_id];
        });

        $employees = Employee::where('employee_id', '!=', $employeeId)
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->orderBy('first_name')
            ->get();

        return view('hr.messages.index', compact('conversations', 'employees'));
    }

    public function show($id)
    {
        $employeeId = optional(Auth::user()->employee)->employee_id ?? 0;

        $conversation = Conversation::where('chat_id', $id)
            ->where(function ($q) use ($employeeId) {
                $q->where('a_id', $employeeId)->orWhere('b_id', $employeeId);
            })
            ->with(['participantA.status', 'participantB.status'])
            ->firstOrFail();

        // Mark messages as read
        Message::where('chat_id', $id)
            ->where('added_by', '!=', $employeeId)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        $messages = Message::where('chat_id', $id)
            ->with('sender')
            ->orderBy('added_date', 'asc') // Assuming sequential
            ->get();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'messages' => $messages,
                'conversation' => $conversation,
                'current_user_id' => $employeeId
            ]);
        }

        // If not ajax, return full view (fallback)
        $conversations = Conversation::where('a_id', $employeeId)
            ->orWhere('b_id', $employeeId)
            ->with(['participantA.status', 'participantB.status'])
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

        return view('hr.messages.index', compact('conversation', 'messages', 'conversations', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees_list,employee_id'
        ]);

        $senderId = Auth::user()->employee->employee_id ?? 0;
        $receiverId = $request->employee_id;

        $existing = Conversation::where(function ($q) use ($senderId, $receiverId) {
            $q->where('a_id', $senderId)->where('b_id', $receiverId);
        })->orWhere(function ($q) use ($senderId, $receiverId) {
            $q->where('a_id', $receiverId)->where('b_id', $senderId);
        })->first();

        if ($existing) {
            return redirect()->route('hr.messages.show', $existing->chat_id);
        }

        $chat = new Conversation();
        $chat->a_id = $senderId;
        $chat->b_id = $receiverId;
        $chat->added_by = $senderId;
        $chat->added_date = now();
        $chat->is_archieve = 0;
        $chat->is_deleted = 0;
        $chat->save();

        return redirect()->route('hr.messages.show', $chat->chat_id);
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'post_text' => 'required_without:attachment|nullable|string',
            'attachment' => 'nullable|file|max:10240' // Max 10MB
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

            if (!$file->isValid()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'error' => 'File upload failed.'], 422);
                }
                return redirect()->back()->withErrors(['attachment' => 'File upload failed.']);
            }

            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads'), $fileName);

            $msg->post_text = $fileName;
            $msg->post_type = 'document'; // Default document

            // Check if image
            $extension = strtolower($file->getClientOriginalExtension());
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $msg->post_type = 'image';
            }
        }

        $msg->save();

        if ($request->ajax() || $request->wantsJson()) {
            $msg->load('sender');
            return response()->json([
                'success' => true,
                'message' => $msg
            ]);
        }

        return redirect()->back(); // Fallback for non-ajax
    }

    // Real-time messaging API endpoints
    public function fetchNewMessages(Request $request, $id)
    {
        $employeeId = optional(Auth::user()->employee)->employee_id ?? 0;
        $lastMessageId = $request->input('last_message_id', 0);

        // Verify user has access to this chat
        $chat = Conversation::where('chat_id', $id)
            ->where(function ($q) use ($employeeId) {
                $q->where('a_id', $employeeId)->orWhere('b_id', $employeeId);
            })
            ->first();

        if (!$chat) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        // Fetch new messages
        $messages = Message::where('chat_id', $id)
            ->where('post_id', '>', $lastMessageId)
            ->with('sender')
            ->orderBy('post_id', 'asc')
            ->get();

        // Mark new messages as read
        Message::where('chat_id', $id)
            ->where('post_id', '>', $lastMessageId)
            ->where('added_by', '!=', $employeeId)
            ->update(['is_read' => 1]);

        return response()->json([
            'success' => true,
            'messages' => $messages,
            'current_user_id' => $employeeId
        ]);
    }

    public function getUnreadCount()
    {
        $employeeId = optional(Auth::user()->employee)->employee_id ?? 0;

        // Count unread messages across all conversations
        $unreadCount = Message::whereHas('conversation', function ($q) use ($employeeId) {
            $q->where('a_id', $employeeId)->orWhere('b_id', $employeeId);
        })
            ->where('added_by', '!=', $employeeId)
            ->where('is_read', 0)
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount
        ]);
    }

    public function getConversationList()
    {
        $employeeId = optional(Auth::user()->employee)->employee_id ?? 0;

        // Fetch conversations with unread counts
        $conversations = Conversation::where('a_id', $employeeId)
            ->orWhere('b_id', $employeeId)
            ->with(['participantA.status', 'participantB.status'])
            ->get();

        // Calculate unread counts for each conversation
        foreach ($conversations as $conv) {
            $conv->unread_count = Message::where('chat_id', $conv->chat_id)
                ->where('added_by', '!=', $employeeId)
                ->where('is_read', 0)
                ->count();
        }

        return response()->json([
            'success' => true,
            'conversations' => $conversations,
            'current_user_id' => $employeeId
        ]);
    }
}
