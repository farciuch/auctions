<x-layout>
    <section>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg flex">
            <!-- Lewa kolumna: Lista czatów -->
            <div class="w-1/4 border-r border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900 p-4">Czat</h3>
                <ul class="overflow-y-auto h-full">
                    @foreach ($chats as $chat)
                        <li class="p-4 hover:bg-gray-100 cursor-pointer" data-chat-id="{{ $chat->id }}">
                            <div class="flex items-center">
                                <img src="{{ $chat->item->images->first()->image_patch ?? asset('images/default.png') }}" alt="{{ $chat->item->title }}" class="w-10 h-10 object-cover mr-2">
                                <div>
                                    <p class="font-bold">{{ $chat->seller->first_name }} {{ $chat->seller->last_name }}</p>
                                    @if (Auth::id() == $chat->item->user_id)
                                    <p class="text-sm text-gray-500">Sprzedajesz: {{ $chat->item->title }}</p>
                                    @elseif (Auth::id() !== $chat->item->user_id)
                                    <p class="text-sm text-gray-500">Kupujesz: {{ $chat->item->title }}</p>
                                    @endif
                                    @if ($chat->item->itemType->name === 'Kup teraz')
                                        <p class="text-gray-500 font-bold text-sm mb-2">{{ number_format($chat->item->price, 2) }} PLN</p>
                                    @elseif ($chat->item->itemType->name === 'Aukcja')
                                        <p class="text-gray-500 font-bold text-sm mb-2">{{ number_format($chat->item->current_price, 2) }} PLN</p>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Środkowa kolumna: Szczegóły czatu -->
            <div class="w-2/4  border-r" id="chat-details">
                @if ($selectedChat)
                    @include('chats.partials.chats_details', ['chat' => $selectedChat])
                @else
                    <p class="text-gray-500">Wybierz czat z lewej listy, aby zobaczyć szczegóły rozmowy.</p>
                @endif
            </div>

            <div class="w-1/4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 p-4">Ogłoszenie</h3>
                <ul class="overflow-y-auto h-full">
                    @foreach ($chats as $chat)
                        <li class="p-4 hover:bg-gray-100 cursor-pointer">
                            
                            <div class="flex items-center">
                                <a href="{{ route('items.show', $chat->item->id) }}" >
                                    <img src="{{ $chat->item->images->first()->image_patch ?? asset('images/default.png') }}" alt="{{ $chat->item->title }}" class="w-10 h-10 object-cover mr-2">
                                    <div>
                                        <p class="font-bold text-sm">{{ $chat->item->title }}</p>
                                    </a>
                                    @if (Auth::id() == $chat->item->user_id)
                                        @if ($chat->item->itemType->name === 'Kup teraz')
                                        <p class="text-green-500 font-bold text-sm mb-2">{{ number_format($chat->item->price, 2) }} PLN</p>
                                        <div class="flex space-x-4">

                                        </div>
                                        @elseif ($chat->item->itemType->name === 'Aukcja')
                                        <p class="text-yellow-500 font-bold text-sm mb-2">{{ number_format($chat->item->current_price, 2) }} PLN</p>

                                        @endif
                                    @elseif (Auth::id() !== $chat->item->user_id)
                                        @if ($chat->item->itemType->name === 'Kup teraz')
                                        <p class="text-green-500 font-bold text-sm mb-2">{{ number_format($chat->item->price, 2) }} PLN</p>
                                        <div class="flex space-x-4">
                                            <button class="bg-blue-500 text-white p-1 rounded">Kup teraz</button>
                                        </div>
                                        @elseif ($chat->item->itemType->name === 'Aukcja')
                                        <p class="text-yellow-500 font-bold text-sm mb-2">{{ number_format($chat->item->current_price, 2) }} PLN</p>
                                        <input type="text" placeholder="Twoja propozycja" class="border p-1 rounded mb-4 w-full">
                                        <button class="bg-blue-500 text-white p-1 rounded">Licytuj</button>
                                        @endif
                                    @endif

                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
</x-layout>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatItems = document.querySelectorAll('[data-chat-id]');
    chatItems.forEach(item => {
        item.addEventListener('click', function () {
            const chatId = this.getAttribute('data-chat-id');
            fetch(`/chats/${chatId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('chat-details').innerHTML = html;
                    scrollToBottom();
                });
        });
    });

    const selectedChatId = "{{ request('chat_id') }}";
    if (selectedChatId) {
        const selectedChatItem = document.querySelector(`[data-chat-id='${selectedChatId}']`);
        if (selectedChatItem) {
            selectedChatItem.click();
        }
    }

    function scrollToBottom() {
        const chatMessages = document.querySelector('.chat-messages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }
});
</script>
