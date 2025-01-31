<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemsCheckoutController extends Controller
{
   
    public function showItemsCheckout(Request $request, $itemId)
    {
        $user = Auth::user();
        $item = Item::with('user', 'deliveries', 'images')->find($itemId);
        if (!$item) {
            return redirect()->back()->with('error', 'Item not found');
        }
    
        $quantity = $request->input('quantity', 1); // Pobierz ilość z requestu lub query parameters
        $totalProductCost = $item->price * $quantity;
    
        return view('items.checkout', [
            'user' => $user,
            'item' => $item,
            'quantity' => $quantity,
            'totalProductCost' => $totalProductCost,
        ]);
    }
    
    public function processSingleItemCheckout(Request $request, $itemId)
    {
        $user = Auth::user();
        $item = Item::findOrFail($itemId);

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $item->quantity,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'adres' => 'required|string|max:255',
            'wojewodztwo' => 'required|string|max:255',
            'kod_pocztowy' => 'required|string|max:20',
            'miasto' => 'required|string|max:255',
        ]);

        // Przetwarzanie zakupu, tworzenie zamówienia itp.
        // ...

        return redirect()->route('items.checkout', ['itemId' => $itemId])->with('success', 'Order processed successfully');
    }

    public function editItems(Request $request, $itemId)
    {
        $user = Auth::user();
        $item = Item::findOrFail($itemId);
        $quantity = $request->query('quantity', 1); // Domyślna wartość to 1
        return view('items.update', compact('user', 'item', 'quantity'));
    }

    public function updateItems(Request $request, $itemId)
    {
        $user = Auth::user();
        $item = Item::findOrFail($itemId);
    
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'adres' => 'required|string|max:255',
            'wojewodztwo' => 'required|string|max:255',
            'kod_pocztowy' => 'required|string|max:20',
            'miasto' => 'required|string|max:255',
        ]);
    
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->number = $request->input('number');
    
        $address = $user->address;
        if (!$address) {
            $address = new Address();
            $address->user_id = $user->id;
            $user->address()->save($address);
        }
        $address->adres = $request->input('adres');
        $address->wojewodztwo = $request->input('wojewodztwo');
        $address->kod_pocztowy = $request->input('kod_pocztowy');
        $address->miasto = $request->input('miasto');
    
        $address->save();
        $user->save();
    
        $quantity = $request->input('quantity', 1); // Dodaj ilość do parametrów zapytania
        return redirect()->route('items.checkout.show', ['itemId' => $itemId, 'quantity' => $quantity])->with('success', 'Profil został zaktualizowany.');
    }
}
