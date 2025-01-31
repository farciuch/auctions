<x-layout>
    <section>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ $user->first_name }} {{ $user->last_name }} 
                    <span class="text-sm text-gray-500">({{ $itemCount }} ogłoszeń)</span>
                </h3>
                <!-- Możesz dodać tutaj więcej informacji o użytkowniku -->
            </div>
        </div>
        
        <!-- Seller's Products -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Produkty sprzedającego</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5">
                @if($sellerItems->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                        @foreach ($sellerItems as $sellerItem)
                        <div class="relative bg-gray-100 p-4 rounded-lg shadow hover:bg-gray-200">
                   
                            @auth
                            @if (Auth::id() !== $sellerItem->user_id)
                                @php
                                    $isFavorite = $sellerItem->favorites()->where('user_id', Auth::id())->exists();
                                @endphp
                                <a href="javascript:void(0);" class="favorite-toggle absolute top-2 right-2" data-item-id="{{ $sellerItem->id }}" data-favorite="{{ $isFavorite ? '1' : '0' }}">
                                    <img class="h-10 w-10" src="{{ $isFavorite ? Vite::asset('resources/images/ulub2.svg') : Vite::asset('resources/images/ulub.svg') }}" alt="serce">
                                </a>
                            @endif
                        @endauth
                            <a href="{{ route('items.show', $sellerItem->id) }}">

                            <div class="flex flex-col items-center">
                                <img src="{{ $sellerItem->images->first() ? asset($sellerItem->images->first()->image_patch) : asset('images/default.png') }}" alt="{{ $sellerItem->title }}" class="w-40 h-40 object-cover mb-2">
                                <h4 class="text-sm font-semibold text-gray-900">{{ $sellerItem->title }}</h4>
                                @if ($sellerItem->itemType->name === 'Kup teraz')
                                <p class="text-blue-500 font-bold mt-2">{{ ($sellerItem->itemType->name) }}</p>
                                <p class="text-green-500 font-bold mt-2">{{ number_format($sellerItem->price, 2) }} PLN</p>
                                @elseif ($sellerItem->itemType->name === 'Aukcja')
                                <p class="text-blue-500 font-bold mt-2">{{ ($sellerItem->itemType->name) }}</p>
                                <p class="text-yellow-500 font-bold mt-2">{{ number_format($sellerItem->current_price, 2) }} PLN</p>
                                <p class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($sellerItem->start_time)->format('d-m-Y H:i') }}</p>
                                <p class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($sellerItem->end_time)->format('d-m-Y H:i') }}</p>
                                @endif
                            </div>
                            </a>
                        </div>
                    @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $sellerItems->links() }}
                    </div>
                @else
                    <p class="text-gray-500">Brak produktów do wyświetlenia.</p>
                @endif
            </div>
        </div>
    </section>
</x-layout>
