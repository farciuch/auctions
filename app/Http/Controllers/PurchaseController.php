<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function showCheckout(Request $request)
    {
        $user = Auth::user();
        $selectedItems = $request->input('selected_items') ? explode(',', $request->input('selected_items')) : [];
        $cartItemsQuery = CartItem::with('item.user', 'item.deliveries')->where('user_id', $user->id);
    
        if (!empty($selectedItems)) {
            $cartItemsQuery->whereIn('item_id', $selectedItems);
        }
    
        $cartItems = $cartItemsQuery->get();
        $groupedItems = $cartItems->groupBy('item.user.id');
    
        $totalProductCost = 0;
        foreach ($cartItems as $cartItem) {
            $totalProductCost += $cartItem->item->price * $cartItem->quantity;
        }
    
        return view('checkout.show', [
            'user' => $user,
            'groupedItems' => $groupedItems,
            'totalProductCost' => $totalProductCost,
        ]);
    }


    public function processCheckout(Request $request)
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
        ]);

 
        // Przetwarzanie zakupu, tworzenie zamówienia itp.
        // ...

        return redirect()->route('home')->with('success', 'Zamówienie zostało złożone.');
    }
   
    public function edit()
    {
        $user = Auth::user();
        return view('checkout.update', compact('user'));
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

        $address->save();

        $user->save();

        return redirect('/checkout')->with('success', 'Profil został zauktalizowany.');
    }
}
