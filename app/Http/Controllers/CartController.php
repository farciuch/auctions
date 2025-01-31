<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($id);
    
        if ($item->itemType->name !== 'Kup teraz') {
            return response()->json(['error' => 'Nie można dodać przedmiotu typu Aukcja do koszyka.'], 400);
        }
    
        $quantity = $request->input('quantity', 1);
    
        // Walidacja ilości
        if ($quantity < 1) {
            return response()->json(['error' => 'Ilość musi być co najmniej 1.'], 400);
        }
    
        $existingCartItem = CartItem::where('user_id', $user->id)->where('item_id', $id)->first();
    
        $totalQuantity = $quantity;
        if ($existingCartItem) {
            $totalQuantity += $existingCartItem->quantity;
        }
    
        if ($totalQuantity > $item->quantity) {
            return response()->json(['error' => 'Nie można dodać więcej produktów niż dostępnych.'], 400);
        }
    
        if ($existingCartItem) {
            $existingCartItem->increment('quantity', $quantity);
        } else {
            CartItem::create([
                'user_id' => $user->id,
                'item_id' => $id,
                'quantity' => $quantity,
            ]);
        }
    
        return response()->json(['success' => true, 'message' => 'Produkt dodany do koszyka.']);
    }

    public function showCart()
    {
        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)->with('item.user')->get();
        return view('cart.show', compact('cartItems'));
    }

    public function removeFromCart($id)
    {
        $user = Auth::user();
        $cartItem = CartItem::where('user_id', $user->id)->where('item_id', $id)->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return response()->json(['success' => true, 'message' => 'Produkt usunięty z koszyka.']);
    }

    public function removeMultiple(Request $request)
    {
        $user = Auth::user();
        $itemIds = $request->input('item_ids', []);
    
        CartItem::where('user_id', $user->id)->whereIn('item_id', $itemIds)->delete();
    
        return response()->json(['success' => true, 'message' => 'Produkty usunięte z koszyka.']);
    }
    
    public function updateQuantity(Request $request, $id)
    {
        $user = Auth::user();
        $quantity = $request->input('quantity', 1);
    
        if ($quantity < 1) {
            return response()->json(['error' => 'Ilość musi być co najmniej 1.'], 400);
        }
    
        $cartItem = CartItem::where('user_id', $user->id)->where('item_id', $id)->first();
    
        if (!$cartItem) {
            return response()->json(['error' => 'Produkt nie znaleziony w koszyku.'], 404);
        }
    
        $item = $cartItem->item;
    
        if ($quantity > $item->quantity) {
            return response()->json(['error' => 'Nie można dodać więcej produktów niż dostępnych.'], 400);
        }
    
        $cartItem->quantity = $quantity;
        $cartItem->save();
    
        $newTotal = $cartItem->quantity * $item->price;
    
        return response()->json(['success' => true, 'newTotal' => $newTotal]);
    }
    
}
