

<x-layout>
    <section>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-3 sm:px-6">
             
            </div>
            <div class="border-t border-gray-200 flex">
                <!-- Left Side: Carousel -->
                <div class="p-6 w-1/2">
                    @if ($item->images->isNotEmpty())
                        <div class="relative w-96" id="carousel">
                            <div class="overflow-hidden relative h-full rounded-lg">
                                @foreach ($item->images as $image)
                                    <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                        <img src="{{ asset($image->image_patch) }}" class="block w-full h-full object-contain" alt="...">
                                    </div>
                                @endforeach
                            </div>
                            <!-- Slider controls -->
                            <button type="button" class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 dark:bg-gray-800/30 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                    <svg class="w-6 h-6 text-white dark:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    <span class="sr-only">Previous</span>
                                </span>
                            </button>
                            <button type="button" class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 dark:bg-gray-800/30 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                    <svg class="w-6 h-6 text-white dark:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <span class="sr-only">Next</span>
                                </span>
                            </button>
                        </div>
                    @else
                        <img src="{{ asset('images/default.png') }}" alt="Brak obrazu" class="w-full h-96 object-cover mb-4">
                    @endif
                </div>
                <!-- Right Side: Seller Info and Item Details -->
                <div class="p-6 w-1/2">
                    <div class="mb-4 flex justify-between">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">{{ $item->title }}</h3>


                        @auth
                        @if (Auth::id() !== $item->user_id)
                            @php
                                $isFavorite = $item->favorites()->where('user_id', Auth::id())->exists();
                            @endphp
                            <a href="javascript:void(0);" class="favorite-toggle" data-item-id="{{ $item->id }}" data-favorite="{{ $isFavorite ? '1' : '0' }}">
                                <img class="h-10 w-10" src="{{ $isFavorite ? Vite::asset('resources/images/ulub2.svg') : Vite::asset('resources/images/ulub.svg') }}" alt="serce">
                            </a>
                        @endif
                        @endauth


                    </div>
                    @if (Auth::id() == $item->user_id)
                            @if ($item->itemType->name === 'Kup teraz')
                                <p class="text-green-500 font-bold text-2xl mb-2">{{ number_format($item->price, 2) }} PLN</p>
                                <p class="p-2">Ilość dostępnych: {{ $item->quantity }}</p>
                                <div class="flex space-x-4">


                                </div>
                            @elseif ($item->itemType->name === 'Aukcja')
                                <p class="text-yellow-500 font-bold text-2xl mb-2">{{ number_format($item->current_price, 2) }} PLN</p>
                                <p class="text-gray-500 text-m mb-4">Data rozpoczęcia aukcji: {{ \Carbon\Carbon::parse($item->start_time)->format('d-m-Y H:i') }}</p>
                                <p class="text-gray-500 text-m mb-4">Data zakończenia aukcji: {{ \Carbon\Carbon::parse($item->end_time)->format('d-m-Y H:i') }}</p>
                                <button id="bids-history-btn" class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Oferty >
                                </button>
                            @endif
                    @elseif (Auth::id() !== $item->user_id)
                            @if ($item->itemType->name === 'Kup teraz')
                                <p class="text-green-500 font-bold text-2xl mb-2">{{ number_format($item->price, 2) }} PLN</p>
                                <div class="flex space-x-4">
                                    @auth
                                    <p class="p-2">Ilość dostępnych: {{ $item->quantity }}</p>
                                    <input type="number" id="quantity" name="quantity" min="1" max="{{ $item->quantity }}" value="1" class="border rounded p-2">
                                   
                                    <form action="{{ route('items.checkout.show', ['itemId' => $item->id]) }}" method="GET">
                                        <input type="hidden" id="quantity" name="quantity" value="1">
                                        <button id="buy-now" type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Kup teraz</button>
                                    </form>
                                    <button id="add-to-cart-btn" class="bg-blue-500 text-white px-4 py-2 rounded">
                                        Dodaj do koszyka
                                    </button>
                                    @endauth
                          

                                </div>
                            @elseif ($item->itemType->name === 'Aukcja')
                                <p class="text-yellow-500 font-bold text-2xl mb-2">{{ number_format($item->current_price, 2) }} PLN</p>
                                <p class="text-gray-500 text-m mb-4">Data rozpoczęcia aukcji: {{ \Carbon\Carbon::parse($item->start_time)->format('d-m-Y H:i') }}</p>
                                <p class="text-gray-500 text-m mb-4">Data zakończenia aukcji: {{ \Carbon\Carbon::parse($item->end_time)->format('d-m-Y H:i') }}</p>
                                    
                                    @auth
                                    @if($item->isAuctionActive())
                               
                                    <form action="{{ route('items.placeBid', $item->id) }}" method="POST">
                                        @csrf
                                        <label for="bid_amount">Twoja oferta:</label>
                                        <input type="number" name="bid_amount" id="bid_amount" class="border rounded p-2" min="{{ $item->currentPrice() + 0.50 }}" step="0.50" required>
                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Złóż ofertę</button>
                                    </form>
                                @else
                                    <p>Aukcja zakończona lub jeszcze się nie rozpoczęła.</p>
                                @endif
                                
                                    @endauth
                                    <button id="bids-history-btn" class="bg-blue-500 text-white px-4 py-2 rounded">
                                        Oferty >
                                    </button>
                                                     
                            @endif
                    @endif
                    
                    @auth
                    @if (Auth::id() !== $item->user_id)
                    <a href="{{ route('chats.index', ['item_id' => $item->id, 'seller_id' => $item->user_id]) }}" class="inline-flex items-center p-2">
                        <img src="{{ Vite::asset('resources/images/czat.svg') }}" alt="Napisz do sprzedającego" class="w-6 h-6">
                        <span class="ml-2">Napisz do sprzedającego</span>
                    </a>
                    @endif
                    @endauth
                    

                </div>
            </div>
        </div>
        <!-- Product Details -->
        <div class="p-4 mt-3 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 pb-6 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Szczegóły produktu</h3>
            </div>
            <div class="border-t border-gray-200 px-4">
                <p class="mt-4 text-m text-gray-500 font-bold break-words whitespace-normal break-all w-full">Opis: {!! $item->description !!}</p>
                <p class="mt-4 text-m text-gray-500 font-bold">Stan: {{ $item->condition->name }}</p>
                <p class="mt-4 text-m text-gray-500 font-bold">Typ: 
                    <a href="{{ route('mainCategory.show', $item->category->subcategory->maincategory->id) }}" class="mt-4 text-m text-blue-300 hover:underline">{{ $item->category->subcategory->maincategory->name }}</a> >
                    <a href="{{ route('subCategory.show', [$item->category->subcategory->maincategory->id, $item->category->subcategory->id]) }}" class="mt-4 text-m text-blue-300 hover:underline">{{ $item->category->subcategory->name }}</a> >
                    <a href="{{ route('subSubCategory.show', [$item->category->subcategory->maincategory->id, $item->category->subcategory->id, $item->category->id]) }}" class="mt-4 text-m text-blue-300 hover:underline">{{ $item->category->name }}</a></p>
            </div>
        </div>
        <!-- Address Details -->
        <div class="p-4 mt-3 bg-white shadow overflow-hidden sm:rounded-lg ">
            <div class="px-4 pb-6 ">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Opcje dostawy</h3>
            </div>
            <div class="border-t border-gray-200 px-4">
                
                    <div class=" py-4 pb-6 sm:px-2 flex justify-between">
                        <button id="delivery-options-btn" class="bg-blue-500 text-white px-4 py-2 rounded">
                            Dostawy >
                        </button>
                    </div>
               
            </div>
        </div>
        <div class="p-4 mt-3 bg-white shadow overflow-hidden sm:rounded-lg ">
            <div class="px-4 pb-6 ">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Sprzedający</h3>
            </div>
            <div class="border-t border-gray-200 px-4">
                <a href="{{ route('profile', $item->user->id) }}" class=" mt-4 text-blue-500 hover:underline">
                    {{ $item->user->first_name }} {{ $item->user->last_name }}
                </a>
                <p class="mt-4 max-w-2xl text-m text-gray-500 font-bold">Adres: {{ $item->user->address->adres }}, {{ $item->user->address->wojewodztwo }}</p>
            </div>
        </div>
        <!-- Seller's Other Products -->
        <div class="p-4 mt-3 bg-white shadow overflow-hidden sm:rounded-lg">
 
            <div class="px-4 pb-6 sm:px-6 flex justify-between">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Inne produkty sprzedającego</h3>
                <a href="{{ route('profile', $item->user->id) }}" class="text-blue-500 hover:underline">
                    Zobacz wszystkie
                </a>
            </div>
            <div class="border-t border-gray-200 px-4 py-6">
                <div class="flex overflow-x-auto space-x-4">
                    @foreach ($sellerItems as $sellerItem)

                            <div class="flex-shrink-0 w-40 bg-white shadow rounded-lg p-4">
                                <a href="{{ route('items.show', $sellerItem->id) }}">
                                    
                                    <img src="{{ $sellerItem->images->first() ? asset($sellerItem->images->first()->image_patch) : asset('images/default.png') }}" alt="{{ $sellerItem->title }}" class="w-full h-32 object-cover mb-2">

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

                                </a>
                            </div>
                       
                    @endforeach
                </div>
            </div>
        </div>
        <div id="delivery-options-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded-lg w-1/2">
                <h2 class="text-xl font-bold mb-4">Opcje dostawy</h2>
                <div id="delivery-options-content" class="mb-4">
                    <!-- Zawartość opcji dostawy zostanie załadowana tutaj przez AJAX -->
                </div>
                <div class="flex justify-end">
                    <button id="close-delivery-options-btn" class="bg-gray-500 text-white px-4 py-2 rounded">Zamknij</button>
                </div>
            </div>
        </div>
        <div id="delivery-bids-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded-lg w-1/2">
                <h2 class="text-xl font-bold mb-4">Oferty</h2>
                <div id="bids-content" class="mb-4 max-h-96 overflow-y-auto">
                    <!-- Zawartość opcji dostawy zostanie załadowana tutaj przez AJAX -->
                </div>
                <div class="flex justify-end">
                    <button id="close-bids-btn" class="bg-gray-500 text-white px-4 py-2 rounded">Zamknij</button>
                </div>
            </div>
        </div>
    </section>
    <div id="cart-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg">
            <h2 class="text-xl font-bold mb-4">Przedmiot dodany do koszyka</h2>
            <div class="mb-4">
                @if ($item->images->isNotEmpty())
                    <img src="{{ asset($item->images->first()->image_patch) }}" alt="{{ $item->title }}" class="w-full h-48 object-cover mb-4">
                @else
                    <img src="{{ asset('images/default.png') }}" alt="Brak obrazu" class="w-full h-48 object-cover mb-4">
                @endif
                <h2 class="text-xl font-semibold">{{ $item->title }}</h2>
                <p class="text-gray-700 text-sm mt-2">{!! Str::limit(strip_tags($item->description), 50) !!}</p>
                <p class="text-green-500 font-bold mt-2">{{ number_format($item->price, 2) }} PLN</p>
            </div>
            <div class="flex justify-end">
                <button id="continue-shopping-btn" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Kupuj dalej</button>
                <a href="{{ route('cart.show') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Idź do koszyka</a>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
    $('#add-to-cart-btn').on('click', function() {
        var quantity = parseInt($('#quantity').val());
        var maxQuantity = {{ $item->quantity }};

        if (quantity > maxQuantity) {
            alert('Nie można dodać więcej produktów niż dostępnych.');
            return;
        }

        $.ajax({
            url: '{{ route('cart.add', $item->id) }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    $('#cart-modal').removeClass('hidden');
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    $('#continue-shopping-btn').on('click', function() {
        $('#cart-modal').addClass('hidden');
    });

 
});
    </script>
    <script>
        $(document).ready(function() {
            // Otwórz modalne okno z opcjami dostawy
            $('#delivery-options-btn').on('click', function() {
                $.ajax({
                    url: '{{ route('items.deliveryOptions', $item->id) }}',
                    method: 'GET',
                    success: function(response) {
                        $('#delivery-options-content').html(response);
                        $('#delivery-options-modal').removeClass('hidden');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });

            // Zamknij modalne okno z opcjami dostawy
            $('#close-delivery-options-btn').on('click', function() {
                $('#delivery-options-modal').addClass('hidden');
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('form[action*="placeBid"]').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    location.reload(); // Odśwież stronę po sukcesie
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message); // Pokaż błąd, jeśli wystąpi
                }
            });
        });
    });
    </script>
        <script>
            $(document).ready(function() {
                // Otwórz modalne okno z opcjami dostawy
                $('#bids-history-btn').on('click', function() {
                    $.ajax({
                        url: '{{ route('items.bidsHistory', $item->id) }}',
                        method: 'GET',
                        success: function(response) {
                            $('#bids-content').html(response);
                            $('#delivery-bids-modal').removeClass('hidden');
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                        }
                    });
                });
    
                // Zamknij modalne okno z opcjami dostawy
                $('#close-bids-btn').on('click', function() {
                    $('#delivery-bids-modal').addClass('hidden');
                });
            });
        </script>
    <script>
        $(document).ready(function() {
            $('#buy-now').on('click', function(event) {
                event.preventDefault(); // Zatrzymaj domyślne działanie przycisku
                var quantity = $('#quantity').val(); // Pobierz wartość z inputa quantity
                $('input[name="quantity"]').val(quantity); // Ustaw wartość ukrytego inputa quantity
    
                $(this).closest('form').submit(); // Wyślij formularz
            });
        });
    </script>
    
</x-layout>

