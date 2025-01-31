<x-layout>
    <section>
        <h2 class="text-2xl font-semibold mb-4">Zakup produktów</h2>
        <div class="mb-6">
            <h3 class="text-xl font-semibold mb-2">Dane odbiorcy przesyłki</h3>
            <div class="border-solid border-2 border-black/20 p-4">
                <p>Imię: {{ $user->first_name }}</p>
                <p>Nazwisko: {{ $user->last_name }}</p>
                <p>Adres: {{ $user->address->adres }}</p>
                <p>Kod pocztowy: {{ $user->address->kod_pocztowy }}</p>
                <p>Miasto: {{ $user->address->miasto }}</p>
                <p>Województwo: {{ $user->address->wojewodztwo }}</p>
                <p>Numer telefonu: {{ $user->number }}</p>
                <a href="{{ route('auctions.edit', $item->id) }}" ><button class=" p-2 mt-2 bg-blue-500 text-white rounded">Zmień dane</button></a>
            </div>
        </div>
        <h3 class="text-xl font-semibold mb-2">Metody dostawy</h3>
        <div class="mb-6 border-solid border-2 border-black/20 p-4">
            <a href="{{ route('profile', $item->user->id) }}">
                <h4 class="font-semibold mt-2">Przesyłka od: {{$item->user->first_name}} {{$item->user->last_name}}</h4>
            </a>
            <p class="py-4 font-semibold">Produkty:</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('items.show', $item->id) }}">
                    <div class="border p-2 rounded-lg">
                        <div class="flex justify-between">
                            <div class="flex justify-center">
                                <img src="{{ asset($item->images->first()->image_patch) }}" alt="{{ $item->title }}" class="w-20 h-20 object-cover mb-2">
                                <h5 class="font-semibold ml-5">{{ $item->title }}</h5>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <div class="flex justify-center">
                                <p>Cena: {{ number_format($item->current_price, 2) }} PLN</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <p class="py-4 font-semibold">Wybierz metodę dostawy:</p>
            @foreach($item->deliveries as $delivery)
                <div class="flex items-center mb-2">
                    <input type="radio" name="delivery_methods" value="{{ $delivery->id }}" class="mr-2" data-price="{{ number_format($delivery->price, 2) }}">
                    <label>{{ $delivery->name }} ({{ number_format($delivery->price, 2) }} PLN)</label>
                </div>
            @endforeach
        </div>
        <div class="mb-6 border-solid border-2 border-black/20 p-4">
            <h3 class="text-xl font-semibold mb-2">Podsumowanie</h3>
            <p>Wartość produktów: {{ number_format($totalProductCost, 2) }} PLN</p>
            <p>Dostawa: <span id="total-delivery-cost">0.00</span> PLN</p>
            <p>Razem: <span id="total-cost">{{ number_format($totalProductCost, 2) }}</span> PLN</p>
            <form action="{{ route('auctions.auction-checkout.processSingle', $item->id) }}" method="POST">
                @csrf
                <button type="submit" class="mt-2 p-2 bg-blue-500 text-white rounded">Zamawiam i płacę</button>
            </form>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('input[name^="delivery_methods"]').on('change', function() {
                var totalDeliveryCost = 0;
                $('input[name^="delivery_methods"]:checked').each(function() {
                    totalDeliveryCost += parseFloat($(this).data('price'));
                });
                
                $('#total-delivery-cost').text(totalDeliveryCost.toFixed(2) + ' zł');
                var totalCost = parseFloat({{ $totalProductCost }}) + totalDeliveryCost;
                $('#total-cost').text(totalCost.toFixed(2) + ' zł');
            });
        });
    </script>
</x-layout>
