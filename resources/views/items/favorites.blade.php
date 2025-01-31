<x-layout>
    <section>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Twoje ulubione przedmioty</h3>
            </div>
            <div class="border-t border-gray-200">
                @if ($favorites->isEmpty())
                    <p class="px-4 py-5 sm:px-6">Nie masz jeszcze żadnych ulubionych przedmiotów.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                        @foreach ($favorites as $favorite)
                        
                        <div class="relative bg-gray-100 p-4 rounded-lg shadow hover:bg-gray-200">
                 
                        @auth
                        @if (Auth::id() !== $favorite->item->user_id)
                            @php
                                $isFavorite = $favorite->item->favorites()->where('user_id', Auth::id())->exists();
                            @endphp
                            <a href="javascript:void(0);" class="favorite-toggle absolute top-2 right-2" data-item-id="{{ $favorite->item->id }}" data-favorite="{{ $isFavorite ? '1' : '0' }}">
                                <img class="h-10 w-10" src="{{ $isFavorite ? Vite::asset('resources/images/ulub2.svg') : Vite::asset('resources/images/ulub.svg') }}" alt="serce">
                            </a>
                        @endif
                        @endauth
                            <a href="{{ route('items.show', $favorite->item->id) }}" >
                                <div class="flex flex-col items-center">
                                    @if ($favorite->item->images->isNotEmpty())
                                        <img src="{{ asset($favorite->item->images->first()->image_patch) }}" alt="{{ $favorite->item->title }}" class="w-40 h-40 object-cover mb-2">
                                    @else
                                        <img src="{{ asset('images/default.png') }}" alt="Brak obrazu" class="w-full h-48 object-cover mb-4">
                                    @endif
                                    <h2 class="text-xl font-semibold text-center">{{ Str::limit($favorite->item->title, 20) }}</h2>
                                    <p class="text-gray-700 text-sm mt-2 text-center description">{!! Str::limit(strip_tags($favorite->item->description), 25) !!}</p>
                                    @if ($favorite->item->itemType->name === 'Kup teraz')
                                        <p class="text-blue-500 font-bold mt-2">{{ ($favorite->item->itemType->name) }}</p>
                                        <p class="text-green-500 font-bold mt-2">{{ number_format($favorite->item->price, 2) }} PLN</p>
                                    @elseif ($favorite->item->itemType->name === 'Aukcja')
                                        <p class="text-blue-500 font-bold mt-2">{{ ($favorite->item->itemType->name) }}</p>
                                        <p class="text-yellow-500 font-bold mt-2">{{ number_format($favorite->item->current_price, 2) }} PLN</p>
                                        <p class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($favorite->item->start_time)->format('d-m-Y H:i') }}</p>
                                        <p class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($favorite->item->end_time)->format('d-m-Y H:i') }}</p>
                                    @endif
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-layout>
