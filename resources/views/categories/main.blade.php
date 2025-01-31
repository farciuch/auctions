<x-layout>
    <section>
        <p class="mt-4 text-m text-gray-500 font-bold">
            <a href="{{ route('items.index') }}" class="mt-4 text-m text-blue-300 hover:underline">Kategorie </a> >
            <a href="{{ route('mainCategory.show', $mainCategory->id) }}" class="mt-4 text-m text-blue-300 hover:underline">{{ $mainCategory->name }}</a>
          </p>
        <h1 class="text-3xl font-bold px-3 mb-3 mt-3 text-center">{{ $mainCategory->name }}</h1>
        <div class="border-solid border-2 border-black/20 p-4 text-center"> 
            @foreach($mainCategory->subCategories as $subCategory)
            <ul>
                <a href="{{ route('subCategory.show', [$mainCategory->id, $subCategory->id]) }}">{{ $subCategory->name }}</a>
            </ul>
        @endforeach
        </div>
        <div class="border-solid border-2 border-black/20 p-4 text-center mt-2"> 
            <h1 class="text-xl leading-6 font-medium text-gray-900">Filtry</h1>
            <form method="GET" action="{{ route('mainCategory.show', $mainCategory->id) }}">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="min_price" class="text-lg leading-6 font-medium text-gray-900">Cena od</label>
                        <input type="number" name="min_price" class="form-control bg-black/10 border border-black/30 rounded p-2 mt-2" value="{{ request('min_price') }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="max_price" class="text-lg leading-6 font-medium text-gray-900">Cena do</label>
                        <input type="number" name="max_price" class="form-control bg-black/10 border border-black/30 rounded p-2 mt-2" value="{{ request('max_price') }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="item_type" class="text-lg leading-6 font-medium text-gray-900">Typ aukcji</label>
                        <select name="item_type" class="form-control rounded-xl bg-black/10 border border-black/30 px-5 py-4 mt-2">
                            <option value="">Wybierz typ</option>
                            <option value="1" {{ request('item_type') == 1 ? 'selected' : '' }}>Aukcja</option>
                            <option value="2" {{ request('item_type') == 2 ? 'selected' : '' }}>Kup teraz</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="sort_by" class="text-lg leading-6 font-medium text-gray-900">Sortuj</label>
                        <select name="sort_by" class="form-control rounded-xl bg-black/10 border border-black/30 px-5 py-4 mt-2">
                            <option value="">Wybierz sortowanie</option>
                            <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Cena: Od najniższej do najwyższej</option>
                            <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Cena: Od najwyższej do najniższej</option>
                            <option value="title_asc" {{ request('sort_by') == 'title_asc' ? 'selected' : '' }}>A-Z</option>
                            <option value="title_desc" {{ request('sort_by') == 'title_desc' ? 'selected' : '' }}>Z-A</option>
                            <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Najnowsze</option>
                            <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>Najstarsze</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded mt-2">Wyszukaj</button>
                <a href="{{ route('mainCategory.show', $mainCategory->id) }}">
                    <button type="button" class=" bg-blue-500 text-white px-4 py-2 rounded mt-2">Reset</button>
                </a>
            </form>
            </div>
            <div class="border-t border-gray-200 ">
                
                @if ($items->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    @foreach ($items as $item)
                        <div class="relative bg-gray-100 p-4 rounded-lg shadow hover:bg-gray-200">
              

                        
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
                    @endforeach
                </div>
            </div>
            <div class="mt-4">
                {{$items->links()}}
            </div>
            @else
            <p class="text-red-500 py-5 text-xl">Brak wyników dla tej kategorii</p>
            
            
           
            @endif
        </div>
    </section>
</x-layout>