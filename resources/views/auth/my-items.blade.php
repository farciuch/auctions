<x-layout>
    <section>
        <div class="container">
            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-2">Dane profilu</h3>
    
                <div">
                    <p>Imię: {{ $user->first_name }}</p>
                    <p>Nazwisko: {{ $user->last_name }}</p>
                    <p>Adres: {{ $user->address->adres }}</p>
                    <p>Kod pocztowy: {{ $user->address->kod_pocztowy }}</p>
                    <p>Miasto: {{ $user->address->miasto }}</p>
                    <p>Województwo: {{ $user->address->wojewodztwo }}</p>
                    <p>Numer telefonu: {{ $user->number }}</p>
                    <a href="/edit" class=" text-blue-500 hover:underline"> Zmień dane  </a>
                </div>
            </div>
            
            <h1 class="text-2xl font-bold px-3 mb-3 mt-3">
                <p>Moje Przedmioty</p>
                
            </h1>
            
            @if($items->isEmpty())
                <p>Nie masz żadnych przedmiotów.</p>
            @else
            <div class="flex overflow-x-auto space-x-6 p-6">
                    @foreach($items as $item)
                    @include('partials.item_card', ['item' => $item])
                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger bg-red-500 p-2 rounded-sm" onclick="return confirm('Czy na pewno chcesz usunąć ten przedmiot?')">Usuń</button>
                    </form>
                    @endforeach
                </div>
            @endif
    </section>
</x-layout>