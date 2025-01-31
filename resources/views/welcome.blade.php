<x-layout>
    <section>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="border-t border-gray-200">
                <h1 class="text-2xl font-bold px-3 mb-3 mt-3">Najnowsze</h1>
                <div class="flex overflow-x-auto space-x-6 p-6">
                    @foreach ($items as $item)
                        @include('partials.item_card', ['item' => $item])
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @if($homeGardenCategory)
    <section>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="border-t border-gray-200">
                <h1 class="text-2xl font-bold px-3 mb-3 mt-3">
                    <p>Na czasie - <a href="{{ route('mainCategory.show', $homeGardenCategory->id) }}">{{ $homeGardenCategory->name }}</a></p>
                    
                </h1>
                <div class="flex overflow-x-auto space-x-6 p-6">
                    @foreach ($trendingItems as $item)
                        @include('partials.item_card', ['item' => $item])
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    @if($electronicsCategory)
    <section>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="border-t border-gray-200">
                <h1 class="text-2xl font-bold px-3 mb-3 mt-3">
                    <p>Coś dla graczy - <a href="{{ route('mainCategory.show', $electronicsCategory->id) }}">{{ $electronicsCategory->name }}</a></p>
                    
                </h1>
                <div class="flex overflow-x-auto space-x-6 p-6">
                    @foreach ($gamingItems as $item)
                        @include('partials.item_card', ['item' => $item])
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <section>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="border-t border-gray-200">
                <h1 class="text-2xl font-bold px-3 mb-3 mt-3">Przedmioty poniżej 100zł</h1>
                <div class="flex overflow-x-auto space-x-6 p-6">
                    @foreach ($below100Items as $item)
                        @include('partials.item_card', ['item' => $item])
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</x-layout>
