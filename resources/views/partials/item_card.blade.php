<div class="flex-shrink-0 bg-gray-100 p-4 rounded-lg shadow hover:bg-gray-200 w-60">
    <div class="relative">
    @auth
        @if (Auth::id() !== $item->user_id)
            @php
                $isFavorite = $item->favorites()->where('user_id', Auth::id())->exists();
            @endphp
            <a href="javascript:void(0);" class="favorite-toggle absolute top-2 right-2" data-item-id="{{ $item->id }}" data-favorite="{{ $isFavorite ? '1' : '0' }}">
                <img class="h-10 w-10" src="{{ $isFavorite ? Vite::asset('resources/images/ulub2.svg') : Vite::asset('resources/images/ulub.svg') }}" alt="serce">
            </a>
        @endif
    @endauth

    <a href="{{ route('items.show', $item->id) }}">
        <div class="flex flex-col items-center">
            @if ($item->images->isNotEmpty())
                <img src="{{ asset($item->images->first()->image_patch) }}" alt="{{ $item->title }}" class="w-40 h-40 object-cover mb-2">
                
            @else
                <img src="{{ asset('images/default.png') }}" alt="Brak obrazu" class="w-40 h-40 object-cover mb-2">
            @endif
            <h2 class="text-xl font-semibold text-center">{{ Str::limit($item->title, 20) }}</h2>
            <p class="text-gray-700 text-sm mt-2 text-center description">{!! Str::limit(strip_tags($item->description), 25) !!}</p>
            @if ($item->itemType->name === 'Kup teraz')
                <p class="text-blue-500 font-bold mt-2">{{ ($item->itemType->name) }}</p>
                <p class="text-green-500 font-bold mt-2">{{ number_format($item->price, 2) }} PLN</p>
            @elseif ($item->itemType->name === 'Aukcja')
                <p class="text-blue-500 font-bold mt-2">{{ ($item->itemType->name) }}</p>
                <p class="text-yellow-500 font-bold mt-2">{{ number_format($item->current_price, 2) }} PLN</p>
                <p class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($item->start_time)->format('d-m-Y H:i') }}</p>
                <p class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($item->end_time)->format('d-m-Y H:i') }}</p>
            @endif
        </div>
    </a>
    </div>
</div>
