

 <x-layout>
    <section>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Twój Koszyk</h3>
            </div>
            <div class="border-t border-gray-200">
                <div class="p-6">
                    @if($cartItems->isEmpty())
                        <p>Twój koszyk jest pusty.</p>
                    @else
                        <div class="mb-4">
                            <input type="checkbox" id="select-all" class="mr-2" checked> Zaznacz cały koszyk
                        </div>
                        @foreach($cartItems->groupBy('item.user.id') as $sellerId => $sellerCartItems)
                            <div class="mb-4">
                            
                                <input type="checkbox" class="seller-checkbox mr-2" data-seller-id="{{ $sellerId }}" checked> Produkty od: 
                                
                                    <h4 class="font-semibold mt-2">Przesyłka od: <a href="{{ route('profile', $sellerCartItems->first()->item->user->id) }}">{{ $sellerCartItems->first()->item->user->first_name }} {{ $sellerCartItems->first()->item->user->last_name }}</h4>
                                </a>
                                
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                                @foreach($sellerCartItems as $cartItem)
                                    <div class="relative bg-gray-100 p-4 rounded-lg shadow hover:bg-gray-200">
                                        <input type="checkbox" class="cart-item-checkbox mr-2" data-item-id="{{ $cartItem->item->id }}" data-seller-id="{{ $sellerId }}" checked>
                                        <a href="{{ route('items.show', $cartItem->item->id) }}">
                                            <div class="flex flex-col items-center">
                                                @if ($cartItem->item->images->isNotEmpty())
                                                    <img src="{{ asset($cartItem->item->images->first()->image_patch) }}" alt="{{ $cartItem->item->title }}" class="w-40 h-40 object-cover mb-2">
                                                @else
                                                    <img src="{{ asset('images/default.png') }}" alt="Brak obrazu" class="w-40 h-40 object-cover mb-2">
                                                @endif
                                                <h2 class="text-xl font-semibold text-center">{{ Str::limit($cartItem->item->title, 20) }}</h2>
                                                <p class="text-green-500 font-bold text-center mt-2">{{ number_format($cartItem->item->price, 2) }} PLN</p>
                                            </div>
                                        </a>
                                        <div class="flex items-center mt-2">
                                            <p class="mr-2">Ilość: </p>
                                            <input type="number" id="quantity-{{ $cartItem->item->id }}" value="{{ $cartItem->quantity }}" class="w-12 text-center border-t border-b" min="1" max="{{ $cartItem->item->quantity }}">
                                            
                                        </div>
                                        <p class="text-gray-500 text-sm">Suma: <span id="total-{{ $cartItem->item->id }}">{{ number_format($cartItem->item->price * $cartItem->quantity, 2) }} PLN</span></p>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                        <div class="mt-4">
                            <button id="remove-selected" class="bg-red-500 text-white py-2 px-4 rounded">Usuń zaznaczone</button>
                        </div>
                        <div class="mt-4 p-4 bg-gray-200 rounded-lg">
                            <p class="text-lg font-semibold">Całkowita wartość produktów: <span id="products-value">{{ number_format($cartItems->sum(fn($item) => $item->item->price * $item->quantity), 2) }} PLN</span></p>

                            <div class="mt-4">
                                <form id="checkout-form" action="{{ route('checkout.post') }}" method="POST">
                                    @csrf
                                    <input type="hidden" id="selected-items" name="selected_items">
                                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Przejdź do kasy</button>
                                </form>
                            </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <script>
        
        document.getElementById('checkout-form').addEventListener('submit', function(event) {
        const selectedItems = [];
        document.querySelectorAll('.cart-item-checkbox:checked').forEach(checkbox => {
            selectedItems.push(checkbox.getAttribute('data-item-id'));
        });
        document.getElementById('selected-items').value = selectedItems.join(',');
    });


            // Select all checkboxes functionality
            document.getElementById('select-all').addEventListener('change', function() {
                const isChecked = this.checked;
                document.querySelectorAll('.seller-checkbox, .cart-item-checkbox').forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
                updateCartSummary();
            });

            // Select all items from a seller functionality
            document.querySelectorAll('.seller-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const sellerId = this.getAttribute('data-seller-id');
                    const isChecked = this.checked;
                    document.querySelectorAll(`.cart-item-checkbox[data-seller-id="${sellerId}"]`).forEach(itemCheckbox => {
                        itemCheckbox.checked = isChecked;
                    });
                    updateCartSummary();
                });
            });

            // Update cart summary when individual item checkbox is changed
            document.querySelectorAll('.cart-item-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateCartSummary();
                });
            });


                        
            function updateCartSummary() {
                let totalValue = 0;
                document.querySelectorAll('.cart-item-checkbox:checked').forEach(checkbox => {
                    const itemId = checkbox.getAttribute('data-item-id');
                    const quantity = parseInt(document.getElementById(`quantity-${itemId}`).value);
                    const price = parseFloat(document.querySelector(`.cart-item-checkbox[data-item-id="${itemId}"]`).closest('.relative').querySelector('.text-green-500').textContent.replace(' PLN', ''));
                    totalValue += quantity * price;
                });

                document.getElementById('products-value').textContent = totalValue.toFixed(2) + ' PLN';
                document.getElementById('total-value').textContent = (totalValue + 15).toFixed(2) + ' PLN';
            }

            function updateQuantity(itemId, change, maxQuantity) {
                const quantityInput = document.getElementById(`quantity-${itemId}`);
                let currentQuantity = parseInt(quantityInput.value);
                currentQuantity += change;
                if (currentQuantity < 1) currentQuantity = 1;
                if (currentQuantity > maxQuantity) currentQuantity = maxQuantity;
                quantityInput.value = currentQuantity;

                // Update cart item quantity and total price via AJAX
                fetch(`/cart/updateQuantity/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        quantity: currentQuantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`total-${itemId}`).textContent = data.newTotal.toFixed(2) + ' PLN';
                        updateCartSummary();
                    } else {
                        alert(data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            document.getElementById('remove-selected').addEventListener('click', function() {
                const selectedItems = [];
                document.querySelectorAll('.cart-item-checkbox:checked').forEach(checkbox => {
                    selectedItems.push(checkbox.getAttribute('data-item-id'));
                });

                if (selectedItems.length > 0) {
                    fetch('/cart/removeMultiple', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            item_ids: selectedItems
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            selectedItems.forEach(itemId => {
                                document.querySelector(`.cart-item-checkbox[data-item-id="${itemId}"]`).closest('.relative').remove();
                            });
                            updateCartSummary();
                            if (document.querySelectorAll('.cart-item-checkbox').length === 0) {
                                document.querySelector('.p-6').innerHTML = '<p>Twój koszyk jest pusty.</p>';
                            }
                        } else {
                            alert(data.error);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });

            document.querySelectorAll('input[id^="quantity-"]').forEach(input => {
                input.addEventListener('change', function() {
                    const itemId = this.id.split('-')[1];
                    const maxQuantity = parseInt(this.getAttribute('max'));
                    let newQuantity = parseInt(this.value);
                    if (newQuantity > maxQuantity) {
                        newQuantity = maxQuantity;
                        this.value = maxQuantity;
                    } else if (newQuantity < 1) {
                        newQuantity = 1;
                        this.value = 1;
                    }
                    updateQuantity(itemId, newQuantity - parseInt(this.value), maxQuantity);
                });
            });
        
    </script>
</x-layout> 
 


