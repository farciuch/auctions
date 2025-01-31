
<x-layout>

    <form method="POST" action="/create" enctype="multipart/form-data" class="max-w-2xl mx-auto space-y-6">
        @csrf
        @if ($errors->any())
        <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li class="text-red-600 text-xl">{{ $error }}</li>
            @endforeach
        </ul>
        </div>
        @endif
        <h1 class="font-bold text-xl px-3 mb-3">Dane o produkcie</h1>
        <div class="border-solid border-2 border-black/20 p-4" h-px>
            <x-forms.input type="file" label="Zdjęcia" name="images[]" id="images" required multiple></x-forms.input>
            <x-forms.input label="Nazwa przedmiotu" name="title" required></x-forms.input>
            <span class="w-2 h-2 bg-white inline-block mr-2 mb-0.5"></span>
            <label for="description" class="font-bold  py-4">Opis</label>
            <textarea name="description" id="description" 
            ></textarea>
            <span class="w-2 h-2 bg-white inline-block mr-2 mb-0.5"></span>
            <label for="condition_id" class="font-bold py-4">Stan produktu</label>
            <select name="condition_id" id="condition_id" class="form-control rounded-xl bg-black/10 border border-black/30 px-5 py-4 w-full" required>
                @foreach ($conditions as $condition)
                    <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                @endforeach
            </select>
            <span class="w-2 h-2 bg-white inline-block mr-2 mb-0.5"></span>

 
            <label for="deliveries" class="font-bold py-4">Typy wysyłki</label>
            <br>
            <button type="button" onclick="showDeliveryModal()" class="btn btn-secondary rounded-xl bg-black/10 border border-black/30 px-5 py-4 w-full">Typy wysyłki</button>

            <!-- Modal -->
            <div id="deliveryModal" class="modal" >
                <div class="modal-content rounded-xl bg-black/10 border border-black/30 px-5 py-4 w-full">
                    <h2 class="font-bold">Wybierz typy wysyłki</h2>
                    @foreach ($deliveries as $delivery)
                        <label>
                            <input type="checkbox" name="deliveries[]" value="{{ $delivery->id }}" >
                            {{ $delivery->name }} - {{ $delivery->price }} PLN
                        </label><br>
                    @endforeach
                    <button type="button" class="font-bold" onclick="closeDeliveryModal()">Zamknij</button>
                </div>
            </div>
            
        </div>
        <h1 class="font-bold text-xl px-3 mb-3">Kategorie</h1>
        <div class="border-solid border-2 border-black/20 p-4" h-px>
            <span class="w-2 h-2 bg-white inline-block mr-2 mb-0.5"></span>
            <label for="main_category_id" class="font-bold  py-4">Główna Kategoria</label>
            <select name="main_category_id" id="main_category_id" class="form-control rounded-xl bg-black/10 border border-black/30 px-5 py-4 w-full" required>
                <option value="">Wybierz główną kategorię</option>
                @foreach ($mainCategories as $mainCategory)
                    <option value="{{ $mainCategory->id }}">{{ $mainCategory->name }}</option>
                @endforeach
            </select>
            <span class="w-2 h-2 bg-white inline-block mr-2 mb-0.5"></span>
            <label for="sub_category_id" class="font-bold  py-4">Podkategoria</label>
            <select name="sub_category_id" id="sub_category_id" class="form-control rounded-xl bg-black/10 border border-black/30 px-5 py-4 w-full" required>
                <option value="">Wybierz podkategorię</option>
            </select>
            <span class="w-2 h-2 bg-white inline-block mr-2 mb-0.5"></span>
            <label for="category_id" class="font-bold  py-4">Podkategoria podkategorii</label>
            <select name="category_id" id="category_id" class="form-control rounded-xl bg-black/10 border border-black/30 px-5 py-4 w-full" required>
                <option value="">Wybierz podkategorię podkategorii</option>
            </select>
        </div>
        <h1 class="font-bold text-xl px-3 mb-3">Rodzaj sprzedaży</h1>
        <div class="border-solid border-2 border-black/20 p-4" h-px>
            <span class="w-2 h-2 bg-white inline-block mr-2 mb-0.5"></span>
            <label for="item_type_id" class="font-bold  py-4">Typ sprzedaży</label>
            <select name="item_type_id" id="item_type_id" class="form-control rounded-xl bg-black/10 border border-black/30 px-5 py-4 w-full" required>
                @foreach ($itemTypes as $itemType)
                    <option value="{{ $itemType->id }}" @if($itemType->name == 'Aukcja') selected @endif>{{ $itemType->name }}</option>
                @endforeach
            </select>
            <div id="auctionFields">
                <x-forms.input type="number" label="Cena wywoławcza" name="starting_price" id="starting_price" required></x-forms.input>
                <x-forms.input type="datetime-local" label="Data rozpoczęcia aukcji" name="start_time" id="start_time" required></x-forms.input>
                <x-forms.input type="datetime-local" label="Data zakończenia aukcji" name="end_time" id="end_time" required></x-forms.input>
            </div>
            <div id="fixedPriceFields" style="display: none;">
                <x-forms.input type="number" label="Cena" name="price" id="price"></x-forms.input>
                <x-forms.input type="number" label="Ilość" name="quantity" id="quantity" min="1"></x-forms.input>
            </div>
        </div>
        <button type="submit" class="btn btn-primary bg-black/90 rounded py-2 px-6 font-bold text-white">Dodaj przedmiot</button>
    </form>
    <script>
        function showDeliveryModal() {
            document.getElementById('deliveryModal').style.display = 'block';
        }

        function closeDeliveryModal() {
            document.getElementById('deliveryModal').style.display = 'none';
        }
    </script>
        
    <script>
        document.getElementById('item_type_id').addEventListener('change', function() {
            updateFieldsBasedOnItemType(this.value);
        });

        document.getElementById('main_category_id').addEventListener('change', function() {
            var mainCategoryId = this.value;
            var subCategorySelect = document.getElementById('sub_category_id');
            var subSubCategorySelect = document.getElementById('category_id');

            subCategorySelect.innerHTML = '<option value="">Wybierz podkategorię</option>';
            subSubCategorySelect.innerHTML = '<option value="">Wybierz podkategorię podkategorii</option>';

            if (mainCategoryId) {
                fetch(`/api/subcategories/${mainCategoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.subcategories.forEach(subcategory => {
                            var option = document.createElement('option');
                            option.value = subcategory.id;
                            option.text = subcategory.name;
                            subCategorySelect.appendChild(option);
                        });
                    });
            }
        });

        document.getElementById('sub_category_id').addEventListener('change', function() {
            var subCategoryId = this.value;
            var subSubCategorySelect = document.getElementById('category_id');

            subSubCategorySelect.innerHTML = '<option value="">Wybierz podkategorię podkategorii</option>';

            if (subCategoryId) {
                fetch(`/api/sub_subcategories/${subCategoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.sub_sub_categories.forEach(subSubCategory => {
                            var option = document.createElement('option');
                            option.value = subSubCategory.id;
                            option.text = subSubCategory.name;
                            subSubCategorySelect.appendChild(option);
                        });
                    });
            }
        });

        function updateFieldsBasedOnItemType(itemTypeId) {
            var auctionFields = document.getElementById('auctionFields');
            var fixedPriceFields = document.getElementById('fixedPriceFields');
            var startingPrice = document.getElementById('starting_price');
            var startTime = document.getElementById('start_time');
            var endTime = document.getElementById('end_time')
            var price = document.getElementById('price');
            var quantity = document.getElementById('quantity');

            if (itemTypeId == '1') {
                auctionFields.style.display = 'block';
                fixedPriceFields.style.display = 'none';
                price.required = false;
                quantity.required = false;
                startingPrice.required = true;
                startTime.required = true;
                endTime.required = true;

            } else if (itemTypeId == '2') {
                auctionFields.style.display = 'none';
                fixedPriceFields.style.display = 'block';
                price.required = true;
                quantity.required = true;
                startingPrice.required = false;
                startTime.required = false;
                endTime.required = false;
            } else {
                auctionFields.style.display = 'none';
                fixedPriceFields.style.display = 'none';
                price.required = false;
                quantity.required = false;
                startingPrice.required = false;
                startTime.required = false;
                endTime.required = false;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var itemTypeSelect = document.getElementById('item_type_id');
            updateFieldsBasedOnItemType(itemTypeSelect.value);
        });

        ClassicEditor
            .create(document.querySelector('#description'), {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 
                        'undo', 'redo'
                    ]
                },
                language: 'pl',
                licenseKey: '',  // W razie potrzeby podaj klucz licencyjny
            })
            .then(editor => {
                console.log('Editor initialized successfully');
            })
            .catch(error => {
                console.error('Error initializing editor:', error);
            });
    </script>
</x-layout>




