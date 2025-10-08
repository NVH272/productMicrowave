<?php



namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $admin = User::where('role', 'admin')->first();

        $messages = Message::where(function ($q) use ($admin) {
            $q->where('sender_id', auth()->id())
                ->where('receiver_id', $admin->id);
        })->orWhere(function ($q) use ($admin) {
            $q->where('sender_id', $admin->id)
                ->where('receiver_id', auth()->id());
        })->orderBy('created_at')->get();

        if ($request->ajax()) {
            return view('chat.partials.messages', compact('messages', 'admin'));
        }

        return view('chat.customer', compact('messages', 'admin'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message'     => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message'     => $request->message,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return back()->with('success', 'Tin nhắn đã gửi.');
    }


    public function adminIndex()
    {
        $users = User::where('role', 'user')->get();
        return view('chat.admin', compact('users'));
    }

    public function adminChat(User $user, Request $request)
    {
        $messages = Message::where(function ($q) use ($user) {
            $q->where('sender_id', auth()->id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
                ->where('receiver_id', auth()->id());
        })
            ->orderBy('created_at', 'asc')
            ->get();

        // Nếu request là AJAX thì chỉ render phần tin nhắn
        if ($request->ajax()) {
            $html = '';
            foreach ($messages as $msg) {
                $align = $msg->sender_id == auth()->id() ? 'text-end' : 'text-start';
                $color = $msg->sender_id == auth()->id() ? 'bg-primary' : 'bg-secondary';

                $html .= "<div class='mb-2 {$align}'>
                        <span class='badge px-3 py-2 {$color}' style='font-size:16px;'>
                            {$msg->message}
                        </span>
                      </div>";
            }
            if ($html === '') {
                $html = "<p class='text-muted text-center'>Chưa có tin nhắn nào.</p>";
            }
            return $html;
        }

        // Còn nếu load lần đầu thì trả về full view
        return view('chat.admin_chat', compact('user', 'messages'));
    }


    public function adminSend(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => auth()->id(), // admin
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return back();
    }
}
