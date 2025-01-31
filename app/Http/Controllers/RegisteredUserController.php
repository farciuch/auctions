<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\Address;
use App\Models\Country;
use App\Models\UserFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create()
    {
        $countries = Country::all();
        return view('auth.register', compact('countries'));
    }
    public function showProfile($id)
    {
        $user = User::findOrFail($id);
        $itemCount = Item::where('user_id', $id)->count();
        $sellerItems = Item::where('user_id', $id)->paginate(10);
        return view('auth.profile', compact('user', 'itemCount', 'sellerItems'));
    }
    public function addToFavorites($id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($id);

        // Sprawdź, czy produkt jest już w ulubionych
        if (!$user->favorites->contains('item_id', $id)) {
            UserFavorite::create([
                'user_id' => $user->id,
                'item_id' => $id,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Produkt dodany do ulubionych.']);
    }
    public function myItems()
    {
        $user = Auth::user();
        $items = $user->items()->with(['itemType', 'images'])->get();

        return view('auth.my-items', compact('items','user'));
    }
    public function destroy(Item $item)
    {
        // Sprawdzenie, czy zalogowany użytkownik jest właścicielem przedmiotu
        if ($item->user_id !== Auth::id()) {
            return redirect()->route('my.items')->with('error', 'Nie masz uprawnień do usunięcia tego przedmiotu.');
        }

        $item->delete();

        return redirect()->route('my.items')->with('success', 'Przedmiot został usunięty.');
    }
    public function removeFromFavorites($id)
    {
        $user = Auth::user();
        $favorite = UserFavorite::where('user_id', $user->id)->where('item_id', $id)->first();

        if ($favorite) {
            $favorite->delete();
        }

        return response()->json(['success' => true, 'message' => 'Produkt usunięty z ulubionych.']);
    }

    
    public function store(Request $request)
    {
    // Walidacja danych
    $validatedData = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|confirmed|min:8',
        'adres' => 'required|string|max:255',
        'miasto' => 'required|string|max:255',
        'wojewodztwo' => 'required|string|max:255',
        'kod_pocztowy' => 'required|string|max:255',
        'country_id' => 'required|integer|exists:countries,id',
        'number' => 'required|string|max:255',
    ]);

    // Utwórz adres
    $address = Address::create([
        'panstwo_id' => $validatedData['country_id'],
        'adres' => $validatedData['adres'],
        'miasto' => $validatedData['miasto'],
        'wojewodztwo' => $validatedData['wojewodztwo'],
        'kod_pocztowy' => $validatedData['kod_pocztowy'],
    ]);

    // Utwórz użytkownika
    $user = User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'number' => $request->number,
        'adres_id' => $address->id,
    ]);

    // Zaloguj użytkownika
    auth()->login($user);

    // Przekieruj na stronę główną lub inną stronę
    return redirect('/')->with('success', 'Rejestracja zakończona sukcesem!');
    }

    
    public function edit()
    {
        $user = Auth::user();
        $countries = Country::all();
        return view('auth.edit', compact('user','countries'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'adres' => 'required|string|max:255',
            'wojewodztwo' => 'required|string|max:255',
            'kod_pocztowy' => 'required|string|max:20',
            'miasto' => 'required|string|max:255',
            'panstwo_id' => 'required|exists:countries,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->number = $request->input('number');


        $address = $user->address;
        if (!$address) {
            $address = new Address();
            $address->user_id = $user->id; // powiązanie adresu z użytkownikiem
            $user->address()->save($address);
        }
        $address->adres = $request->input('adres');
        $address->wojewodztwo = $request->input('wojewodztwo');
        $address->kod_pocztowy = $request->input('kod_pocztowy');
        $address->miasto = $request->input('miasto');

        $address->panstwo_id = $request->input('panstwo_id');
        $address->save();

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return redirect('/edit')->with('success', 'Profil został zauktalizowany.');
    }
}

