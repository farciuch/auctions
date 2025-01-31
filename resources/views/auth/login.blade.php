<x-layout>
    <x-page-heading>Zaloguj się</x-page-heading>

    <x-forms.form method="POST" action="/login">
        @csrf



            <span class="w-2 h-2 bg-white inline-block mr-2 mb-0.5"></span>
            <label for="email" class="font-bold gap-x-2 ">Email</label>
            <input type="email" name="email" id="email" class="rounded-xl bg-black/10 border border-black/30 px-5 py-4 w-full" value="{{ old('email') }}" required>
            <span class="w-2 h-2 bg-white inline-block mr-2 mb-0.5"></span>
            <label for="password" class="font-bold gap-x-2">Hasło</label>
            <input type="password" name="password" class="rounded-xl bg-black/10 border border-black/30 px-5 py-4 w-full" id="password" required>
            @error('password')
                <div class="text-red-500 text-sm">E-mail lub hasło są nieprawidłowe</div>
            @enderror



        <x-forms.button>Zaloguj</x-forms.button>
    </x-forms.form>
</x-layout>