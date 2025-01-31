<x-layout>
    <x-page-heading>Rejestracja</x-page-heading>

    <x-forms.form method="POST" action="/register">
        @csrf

        <h1 class="font-bold text-xl px-3 mb-3">Dane podstawowe</h1>
            <div class="border-solid border-2 border-black/20 p-4"> 
                <x-forms.input label="Imię*" name="first_name"></x-forms.input>
                <x-forms.input label="Nazwisko*" name="last_name"></x-forms.input>
                <x-forms.input label="Email*" name="email" type="email"></x-forms.input>
                <x-forms.input label="Telefon" name="number"></x-forms.input>
            </div>
            <h1 class="font-bold text-xl px-3 mb-3">Hasło</h1>
            <div class="border-solid border-2 border-black/20 p-4"> 
                <x-forms.input label="Hasło*" name="password" type="password" placeholder="(min. 8 znaków)"></x-forms.input>
                <x-forms.input label="Potwierdź hasło*" name="password_confirmation" type="password"></x-forms.input>
            </div>
            <h1 class="font-bold text-xl px-3 mb-3">Adres</h1>
            <div class="border-solid border-2 border-black/20 p-4"> 
            
        
                <x-forms.input label="Adres*" name="adres"></x-forms.input>
                <x-forms.input label="Kod Pocztowy*" name="kod_pocztowy"></x-forms.input>
                <x-forms.input label="Miasto*" name="miasto"></x-forms.input>
                <x-forms.input label="Województwo*" name="wojewodztwo"></x-forms.input>
                <span class="w-2 h-2 bg-white inline-block mr-2 mb-0.5"></span>
                <label for="country_id" class="font-bold gap-x-2">Państwo*</label>
                <select name="country_id" id="country_id" class="form-control rounded-xl bg-black/10 border border-black/30 px-5 py-4 w-full" >
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach

                </select>

            </div>

        
        <x-forms.divider></x-forms.divider>

        <x-forms.button>Zarejestruj</x-forms.button>
    </x-forms.form>


   
</x-layout>

