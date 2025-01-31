<a href="{{ route('items.show', $chat->item->id) }}">
<div class="border-solid border-2 border-black/20 p-2 bg-gray-100">
    
    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $chat->seller->first_name }} {{ $chat->seller->last_name }}</h3>
    {{ $chat->item->title }}
    @if ($chat->item->itemType->name === 'Kup teraz')
        <p class="text-green-500 font-bold text-sm mb-2">{{ number_format($chat->item->price, 2) }} PLN</p>
    @elseif ($chat->item->itemType->name === 'Aukcja')
        <p class="text-yellow-500 font-bold text-sm mb-2">{{ number_format($chat->item->current_price, 2) }} PLN</p>
    @endif

</div>
</a>
<div>
    <div class="chat-messages overflow-y-auto h-96 p-4" id="chat-messages">
        @foreach ($chat->messages as $message)
            <div class="mb-4 {{ $message->user_id == Auth::id() ? 'text-right' : '' }}">
                
                <p class="text-xm font-bold text-gray-500">{{ $message->user->first_name }} {{ \Carbon\Carbon::parse($message->created_at)->format('H:i') }}</p>
                <p>{{ $message->content }}</p>
            
            </div>
        @endforeach
    </div>
    <form action="{{ route('chats.storeMessage', $chat->id) }}" method="POST" class="p-4 flex items-center">
        @csrf
        <input type="text" name="content" class="border p-2 rounded w-full" placeholder="Napisz wiadomość...">
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded ml-2">Wyślij</button>
    </form>
</div>

<script>
function scrollToBottom() {
    const chatMessages = document.querySelector('.chat-messages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

document.addEventListener('DOMContentLoaded', function () {
    scrollToBottom();
});

const chatForm = document.querySelector('form');
chatForm.addEventListener('submit', function () {
    setTimeout(scrollToBottom, 100);
});
</script>
