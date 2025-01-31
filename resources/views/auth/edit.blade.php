<x-layout>
 
        <x-page-heading>Edycja profilu</x-page-heading>
        @if(session('success'))
        <div class="alert alert-success text-green-400 font-bold text-center text-xs">     
             {{ session('success') }}
        </div>
        @endif
        <x-forms.form action="/edit" method="POST">
            @csrf
            @method('PATCH')
            <h1 class="font-bold text-xl px-3 mb-3">Dane podstawowe</h1>
            <div class="border-solid border-2 border-black/20 p-4"> 
                <x-forms.input label="Imię" name="first_name" value="{{ $user->first_name }}" required></x-forms.input>
                <x-forms.input label="Nazwisko" name="last_name" value="{{ $user->last_name }}" required></x-forms.input>
                <x-forms.input label="Numer telefonu" name="number" value="{{ $user->number }}" required></x-forms.input>

            </div>
            <h1 class="font-bold text-xl px-3 mb-3">Hasło</h1>
            <div class="border-solid border-2 border-black/20 p-4"> 
                <x-forms.input label="Hasło (zostaw puste, jeśli nie chcesz zmieniać hasła)" type="password" name="password" ></x-forms.input>
                <x-forms.input label="Powtórz hasło" type="password" name="password_confirmation"></x-forms.input>
            </div>
            <h1 class="font-bold text-xl px-3 mb-3">Adres</h1>
            <div class="border-solid border-2 border-black/20 p-4"> 
                <x-forms.input label="Adres" name="adres" value="{{ $user->address->adres }}" required></x-forms.input>
                <x-forms.input label="Kod pocztowy" name="kod_pocztowy" value="{{ $user->address->kod_pocztowy }}" required></x-forms.input>
                <x-forms.input label="Miasto" name="miasto" value="{{ $user->address->miasto }}" required></x-forms.input>
                <x-forms.input label="Województwo" name="wojewodztwo" value="{{ $user->address->wojewodztwo }}" required></x-forms.input>
                <span class="w-2 h-2 bg-white inline-block mr-2 mb-0.5"></span>
                <label for="panstwo_id" class="font-bold w-2 h-2 ">Państwo</label>
                <select name="panstwo_id" id="panstwo_id" class="form-control rounded-xl bg-black/10 border border-black/30 px-5 py-4 w-full" required>
                    @foreach($countries as $country)
                    <option value="{{ $country->id }}" {{ old('panstwo_id', optional($user->address)->panstwo_id) == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>

            <x-forms.button>Zapisz zmiany</x-forms.button>
        </x-forms.form>

</x-layout>