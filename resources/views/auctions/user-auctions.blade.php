<x-layout>
    <section>
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-6">Twoje Aukcje</h2>

            <h3 class="text-xl font-bold mb-4">Aktywne Aukcje</h3>
            @if($activeAuctions->isEmpty())
                <p>Brak aktywnych aukcji.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($activeAuctions as $auction)
                        <div class="bg-white shadow rounded-lg p-4">
                            <div class="flex justify-between">
                                <div class="flex justify-center">
                                    <img src="{{ asset($auction->item->images->first()->image_patch) }}" alt="{{ $auction->item->title }}" class="w-20 h-20 object-cover mb-2">

                                </div>
                            </div>
                            <h4 class="text-lg font-bold">{{ $auction->item->title }}</h4>
                            <p>Obecna cena: {{ number_format($auction->item->current_price, 2) }} PLN</p>
                            <p>Koniec: {{ \Carbon\Carbon::parse($auction->item->end_time)->format('d-m-Y H:i') }}</p>
                            <a href="{{ route('items.show', $auction->item->id) }}" class="text-blue-500 hover:underline">Zobacz aukcję</a>
                        </div>
                    @endforeach
                </div>
            @endif

            <h3 class="text-xl font-bold mt-6 mb-4">Zakończone Aukcje</h3>
            @if($endedAuctions->isEmpty())
                <p>Brak zakończonych aukcji.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($endedAuctions as $auction)
                        <div class="bg-white shadow rounded-lg p-4">
                            <div class="flex justify-between">
                                <div class="flex justify-center">
                                    <img src="{{ asset($auction->item->images->first()->image_patch) }}" alt="{{ $auction->item->title }}" class="w-20 h-20 object-cover mb-2">

                                </div>
                            </div>
                            <h4 class="text-lg font-bold">{{ $auction->item->title }}</h4>
                            <p>Ostateczna cena: {{ number_format($auction->item->current_price, 2) }} PLN</p>
                            <p>Koniec: {{ \Carbon\Carbon::parse($auction->item->end_time)->format('d-m-Y H:i') }}</p>
                            <a href="{{ route('items.show', $auction->item->id) }}" class="text-blue-500 hover:underline">Zobacz aukcję</a>
                        </div>
                    @endforeach
                </div>
            @endif

            <h3 class="text-xl font-bold mt-6 mb-4">Wygrane Aukcje</h3>
            @if($wonAuctions->isEmpty())
                <p>Brak wygranych aukcji.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($wonAuctions as $auction)
                        <div class="bg-white shadow rounded-lg p-4">
                            <div class="flex justify-between">
                                <div class="flex justify-center">
                                    <img src="{{ asset($auction->item->images->first()->image_patch) }}" alt="{{ $auction->item->title }}" class="w-20 h-20 object-cover mb-2">

                                </div>
                            </div>
                            <h4 class="text-lg font-bold">{{ $auction->item->title }}</h4>
                            <p>Wygrana cena: {{ number_format($auction->item->current_price, 2) }} PLN</p>
                            <p>Koniec: {{ \Carbon\Carbon::parse($auction->item->end_time)->format('d-m-Y H:i') }}</p>
                            <a href="{{ route('items.show', $auction->item->id) }}" class="text-blue-500 hover:underline">Zobacz aukcję</a>
                            <form action="{{ route('auctions.auction-checkout.show', ['item' => $auction->item->id]) }}" method="GET">

                                <button id="buy" type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Przejdź do zamówienia</button>
                            </form>
                        </div>
                        
    
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-layout>
