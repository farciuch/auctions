<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
    public function showAuctionCheckout(Request $request, $itemId)
    {
        $user = Auth::user();
        $item = Item::with('user', 'deliveries', 'images')->find($itemId);
        if (!$item) {
            return redirect()->back()->with('error', 'Item not found');
        }
    
        $totalProductCost = $item->current_price;
    
        return view('auctions.auction-checkout', [
            'user' => $user,
            'item' => $item,
            'totalProductCost' => $totalProductCost,
        ]);
    }
    
    public function processSingleAuctionCheckout(Request $request, $itemId)
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

        // Przetwarzanie zakupu, tworzenie zamówienia itp.
        // ...

        return redirect()->route('auctions.auction-checkout', ['itemId' => $itemId])->with('success', 'Order processed successfully');
    }

    public function editAuction(Request $request, $itemId)
    {
        $user = Auth::user();
        $item = Item::findOrFail($itemId);
        return view('auctions.update', compact('user', 'item'));
    }

    public function updateAuction(Request $request, $itemId)
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
    
     
        return redirect()->route('auctions.auction-checkout.show', ['item' => $itemId])->with('success', 'Profil został zaktualizowany.');
    }
}
