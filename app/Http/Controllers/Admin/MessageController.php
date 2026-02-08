<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $adminId = Auth::id() ?? 550; // Fallback for dev

        // Fetch Conversations
        // Logic from serv_list.php: `a_id` = USER OR `b_id` = USER
        // Also fetch last message or similar for preview if needed, but legacy just lists them.
        
        $conversations = Conversation::where('a_id', $adminId)
            ->orWhere('b_id', $adminId)
            ->with(['participantA', 'participantB'])
            ->orderBy('chat_id', 'desc') // Legacy order
            ->get();

        // Fetch Employees for New Chat Modal
        // Excluding current user
        $employees = Employee::where('employee_id', '!=', $adminId)
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->orderBy('first_name')
            ->get();

        // Pass active conversation if ID provided
        $activeChat = null;
        $messages = [];
        
        if ($request->has('chat_id')) {
            $chatId = $request->chat_id;
            $activeChat = Conversation::with(['participantA', 'participantB'])
                ->where('chat_id', $chatId)
                ->where(function($q) use ($adminId) {
                    $q->where('a_id', $adminId)->orWhere('b_id', $adminId);
                })
                ->first();

            if ($activeChat) {
                // Fetch Messages
                $messages = Message::where('chat_id', $chatId)
                    ->with('sender')
                    ->orderBy('post_id', 'asc')
                    ->get();
                
                // Mark as read (optional, based on legacy logic)
                Message::where('chat_id', $chatId)
                    ->where('added_by', '!=', $adminId)
                    ->update(['is_read' => 1]);
            }
        }

        return view('admin.messages.index', compact('conversations', 'activeChat', 'messages', 'adminId', 'employees'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees_list,employee_id',
        ]);

        $adminId = Auth::id() ?? 550;
        $targetEmployeeId = $request->employee_id;

        // Check if conversation already exists
        $existingChat = Conversation::where(function($q) use ($adminId, $targetEmployeeId) {
                $q->where('a_id', $adminId)->where('b_id', $targetEmployeeId);
            })
            ->orWhere(function($q) use ($adminId, $targetEmployeeId) {
                $q->where('a_id', $targetEmployeeId)->where('b_id', $adminId);
            })
            ->first();

        if ($existingChat) {
            return redirect()->route('admin.messages.index', ['chat_id' => $existingChat->chat_id]);
        }

        // Create new conversation
        $newChat = Conversation::create([
            'a_id' => $adminId,
            'b_id' => $targetEmployeeId,
            'added_by' => $adminId,
            'added_date' => now(), // Assuming timestamp handling or exact format needed
            // Legacy might need explicit string format if not casting
        ]);

        return redirect()->route('admin.messages.index', ['chat_id' => $newChat->chat_id]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:z_messages_list,chat_id',
            'message' => 'required|string',
        ]);

        $adminId = Auth::id() ?? 550;

        Message::create([
            'chat_id' => $request->chat_id,
            'added_by' => $adminId,
            'post_text' => $request->message,
            'post_type' => 'text', // Default to text
            'added_date' => now(),
            'is_read' => 0,
        ]);

        return redirect()->route('admin.messages.index', ['chat_id' => $request->chat_id]);
    }

    // Real-time messaging API endpoints
    public function fetchNewMessages(Request $request, $chatId)
    {
        $adminId = Auth::id() ?? 550;
        $lastMessageId = $request->input('last_message_id', 0);

        // Verify user has access to this chat
        $chat = Conversation::where('chat_id', $chatId)
            ->where(function($q) use ($adminId) {
                $q->where('a_id', $adminId)->orWhere('b_id', $adminId);
            })
            ->first();

        if (!$chat) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        // Fetch new messages
        $messages = Message::where('chat_id', $chatId)
            ->where('post_id', '>', $lastMessageId)
            ->with('sender')
            ->orderBy('post_id', 'asc')
            ->get();

        // Mark new messages as read
        Message::where('chat_id', $chatId)
            ->where('post_id', '>', $lastMessageId)
            ->where('added_by', '!=', $adminId)
            ->update(['is_read' => 1]);

        return response()->json([
            'success' => true,
            'messages' => $messages,
            'current_user_id' => $adminId
        ]);
    }

    public function getUnreadCount()
    {
        $adminId = Auth::id() ?? 550;

        // Count unread messages across all conversations
        $unreadCount = Message::whereHas('conversation', function($q) use ($adminId) {
                $q->where('a_id', $adminId)->orWhere('b_id', $adminId);
            })
            ->where('added_by', '!=', $adminId)
            ->where('is_read', 0)
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount
        ]);
    }

    public function getConversationList()
    {
        $adminId = Auth::id() ?? 550;

        // Fetch conversations with unread counts
        $conversations = Conversation::where('a_id', $adminId)
            ->orWhere('b_id', $adminId)
            ->with(['participantA', 'participantB'])
            ->get();
            
        // Calculate unread counts for each conversation
        foreach ($conversations as $conv) {
            $conv->unread_count = Message::where('chat_id', $conv->chat_id)
                ->where('added_by', '!=', $adminId)
                ->where('is_read', 0)
                ->count();
        }

        return response()->json([
            'success' => true,
            'conversations' => $conversations,
            'current_user_id' => $adminId
        ]);
    }
}
