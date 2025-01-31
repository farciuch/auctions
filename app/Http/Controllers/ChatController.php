<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $chats = Chat::where('buyer_id', $user->id)
                    ->orWhere('seller_id', $user->id)
                    ->with(['item', 'seller', 'buyer'])
                    ->get();
    
        $selectedChat = null;
        if ($request->has('item_id') && $request->has('seller_id')) {
            $selectedChat = Chat::where('item_id', $request->item_id)
                                ->where('seller_id', $request->seller_id)
                                ->where(function ($query) use ($user) {
                                    $query->where('buyer_id', $user->id)
                                          ->orWhere('seller_id', $user->id);
                                })
                                ->with('messages.user')
                                ->first();
    
            // Jeśli czat nie istnieje, utwórz nowy
            if (!$selectedChat) {
                $selectedChat = Chat::create([
                    'buyer_id' => $user->id,
                    'seller_id' => $request->seller_id,
                    'item_id' => $request->item_id,
                ]);
            }
        }
    
        return view('chats.index', compact('chats', 'selectedChat'));
    }

    public function show(Chat $chat)
    {
        $user = Auth::user();
        if ($chat->buyer_id != $user->id && $chat->seller_id != $user->id) {
            abort(403);
        }
    
        $chat->load('messages.user', 'item');
    
        return view('chats.partials.chats_details', compact('chat'));
    }
    

    public function storeMessage(Request $request, Chat $chat)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
    
        $message = new Message();
        $message->chat_id = $chat->id;
        $message->user_id = Auth::id();
        $message->content = $request->content;
        $message->save();
    
        // Przekierowanie do odpowiedniego czatu po dodaniu wiadomości
        return redirect()->route('chats.index', ['chat_id' => $chat->id]);
    }


}
