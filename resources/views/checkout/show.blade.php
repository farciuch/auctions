<x-layout>
    <section>
        <h2 class="text-2xl font-semibold mb-4">Zakup produktów</h2>

        <!-- SEKCJA 1: Dane odbiorcy przesyłki -->
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
                <a href="/checkout/edit"><button id="edit-address" class="mt-2 p-2 bg-blue-500 text-white rounded">Zmień dane</button></a>   
            </div>
        </div>

        <!-- SEKCJA 2: Metody dostawy -->
        <h3 class="text-xl font-semibold mb-2">Metody dostawy</h3>
        <div class="mb-6 border-solid border-2 border-black/20 p-4">
            @foreach($groupedItems as $sellerId => $items)
                @php
                    $seller = $items->first()->item->user;
                @endphp
                <div class="mb-4">
                    <a href="{{ route('profile', $seller->id) }}">
                        <h4 class="font-semibold mt-2">Przesyłka od: {{ $seller->first_name }} {{ $seller->last_name }}</h4>
                    </a>
                    <p class="py-4 font-semibold">Produkty:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($items as $item)
                        <a href="{{ route('items.show', $item->item->id) }}">
                        <div class="border p-2 rounded-lg">
                            <div class="flex justify-between">
                                <div class="flex justify-center">
                                    <img src="{{ $item->item->images->first()->image_patch }}" alt="{{ $item->item->title }}" class="w-20 h-20 object-cover mb-2">
                                    <h5 class="font-semibold ml-5">{{ $item->item->title }}</h5>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <div class="flex justify-center">
                                    <p>Ilość: {{ $item->quantity }}</p>
                                </div>
                                <div class="flex justify-center">
                                    <p>Cena: {{ number_format($item->item->price, 2) }} PLN</p>
                                </div>
                            </div>
                        </div>
                        </a>
                        @endforeach
                    </div>
                    <p class="py-4 font-semibold">Wybierz metodę dostawy:</p>
                    @foreach($items->first()->item->deliveries as $delivery)
                        <div class="flex items-center mb-2">
                            <input type="radio" name="delivery_methods[{{ $sellerId }}]" value="{{ $delivery->id }}" class="mr-2" data-price="{{ number_format($delivery->price, 2) }}">
                            <label>{{ $delivery->name }} ({{ number_format($delivery->price, 2) }} PLN)</label>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <!-- SEKCJA 3: Podsumowanie -->
        <div class="mb-6 border-solid border-2 border-black/20 p-4">
            <h3 class="text-xl font-semibold mb-2">Podsumowanie</h3>
            <p>Wartość produktów: {{ number_format($totalProductCost, 2) }} PLN</p>
            <p>Dostawa: <span id="total-delivery-cost">0.00</span> PLN</p>
            <p>Razem: <span id="total-cost">{{ number_format($totalProductCost, 2) }}</span> PLN</p>
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <input type="hidden" name="adres" value="{{ $user->address->adres }}">
                <input type="hidden" name="kod_pocztowy" value="{{ $user->address->kod_pocztowy }}">
                <input type="hidden" name="miasto" value="{{ $user->address->miasto }}">
                <input type="hidden" name="wojewodztwo" value="{{ $user->address->wojewodztwo }}">
                <input type="hidden" name="number" value="{{ $user->number }}">
                <button type="submit" class="mt-4 p-2 bg-green-500 text-white rounded">Kupuję i płacę</button>
            </form>
        </div>
    </section>

    <script>
        const deliveryRadioButtons = document.querySelectorAll('input[name^="delivery_methods"]');
        const totalDeliveryCostElement = document.getElementById('total-delivery-cost');
        const totalCostElement = document.getElementById('total-cost');
        let totalDeliveryCost = 0;
        let selectedDeliveries = {};

        deliveryRadioButtons.forEach(radioButton => {
            radioButton.addEventListener('change', function () {
                const sellerId = this.name.match(/\d+/)[0];
                const deliveryCost = parseFloat(this.dataset.price.replace(',', '.'));

                if (selectedDeliveries[sellerId]) {
                    totalDeliveryCost -= selectedDeliveries[sellerId];
                }

                selectedDeliveries[sellerId] = deliveryCost;
                totalDeliveryCost += deliveryCost;

                totalDeliveryCostElement.textContent = totalDeliveryCost.toFixed(2).replace('.', ',');
                totalCostElement.textContent = ({{ $totalProductCost }} + totalDeliveryCost).toFixed(2).replace('.', ',');
            });
        });
    </script>
</x-layout>
