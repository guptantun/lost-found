<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // เริ่มแชท (หรือไปที่แชทเดิมถ้ามีอยู่แล้ว)
    public function start(Item $item)
    {
        if (!Auth::check()) return redirect()->route('login');
        if ($item->user_id == Auth::id()) return back()->with('error', 'คุยกับตัวเองไม่ได้');

        // เช็คว่าเคยคุยกันเรื่องของชิ้นนี้ยัง (ไม่ว่าเราจะเป็นคนทัก หรือเขาเป็นคนทัก)
        $conversation = Conversation::where('item_id', $item->id)
            ->where(function($q) {
                $q->where(function($sub) {
                    $sub->where('sender_id', Auth::id());
                })->orWhere(function($sub) {
                    $sub->where('receiver_id', Auth::id());
                });
            })->first();

        // ถ้ายังไม่มีห้อง ให้สร้างใหม่
        if (!$conversation) {
            $conversation = Conversation::create([
                'item_id' => $item->id,
                'sender_id' => Auth::id(),
                'receiver_id' => $item->user_id,
            ]);
        }

        return redirect()->route('chat.show', $conversation->id);
    }

    // หน้ารายการแชท (Inbox)
    public function index()
    {
        $userId = Auth::id();
        $conversations = Conversation::with(['item', 'messages', 'sender', 'receiver'])
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->orderByDesc('updated_at') // เอาอันล่าสุดขึ้นก่อน
            ->get();

        return view('chat.index', compact('conversations'));
    }

    // หน้าห้องแชท (Room)
    public function show($id)
    {
        $conversation = Conversation::with(['messages.user', 'item', 'sender', 'receiver'])->findOrFail($id);
        
        // Security: ห้ามคนนอกแอบดู
        if (Auth::id() != $conversation->sender_id && Auth::id() != $conversation->receiver_id) {
            abort(403, 'Unauthorized');
        }

        return view('chat.show', compact('conversation'));
    }

    // ส่งข้อความ
    public function send(Request $request, $id)
    {
        $request->validate(['body' => 'required']);
        $conversation = Conversation::findOrFail($id);

        // Security
        if (Auth::id() != $conversation->sender_id && Auth::id() != $conversation->receiver_id) {
            abort(403);
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'body' => $request->body,
            'is_read' => false
        ]);

        $conversation->touch(); // อัปเดตเวลาล่าสุดของห้องแชท

        return back();
    }
}