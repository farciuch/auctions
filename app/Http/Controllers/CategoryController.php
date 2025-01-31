<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\SubSubCategories;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        // Pobierz najnowsze przedmioty
        $items = Item::with(['itemType', 'images'])
            ->orderBy('created_at', 'desc')
            ->take(9)
            ->get();

        // Pobierz kategorię "Dom i Ogród" i przedmioty na czasie
        $homeGardenCategory = MainCategory::where('name', 'Dom i Ogród')->first();
        $trendingItems = Item::with(['itemType', 'images'])
            ->whereHas('category.subCategory.mainCategory', function ($query) use ($homeGardenCategory) {
                $query->where('id', $homeGardenCategory->id);
            })
            ->take(9)
            ->get();

        // Pobierz kategorię "Elektronika" i przedmioty dla graczy
        $electronicsCategory = MainCategory::where('name', 'Elektronika')->first();
        $gamingItems = Item::with(['itemType', 'images'])
            ->whereHas('category.subCategory.mainCategory', function ($query) use ($electronicsCategory) {
                $query->where('id', $electronicsCategory->id);
            })
            ->take(9)
            ->get();

        // Przedmioty poniżej 100zł z kategorii "Kup teraz"
        $below100Items = Item::with(['itemType', 'images'])
            ->where('item_type_id', 2) // Assuming '2' is the ID for 'Kup teraz'
            ->where('price', '<', 100)
            ->take(9)
            ->get();

        return view('welcome', compact('items', 'homeGardenCategory', 'trendingItems', 'electronicsCategory', 'gamingItems', 'below100Items'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'bids_amount' => 'required|numeric|min:0.01',
            'auction_id' => 'required|exists:auctions,id',
        ]);

        Bid::create([
            'bids_amount' => $request->bids_amount,
            'bid_date' => now(),
            'auction_id' => $request->auction_id,
            'bidder_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Your bid has been placed successfully.');
    }
    public function placeBid(Request $request, $itemId)
{
    $item = Item::with('auction')->findOrFail($itemId);
    
    if (!$item->isAuctionActive()) {
        return back()->with('error', 'Aukcja nie jest aktywna.');
    }

    $request->validate([
        'bid_amount' => 'required|numeric|min:' . ($item->currentPrice() + 0.01),
    ]);

    Bid::create([
        'bids_amount' => $request->input('bid_amount'),
        'bid_date' => Carbon::now(),
        'auction_id' => $item->auction->id,
        'bidder_id' => auth()->id(),
    ]);
       // Aktualizacja ceny bieżącej
       $item->update(['current_price' => $request->input('bid_amount')]);
    return back()->with('success', 'Oferta została złożona.');
}
public function userAuctions()
{
    $userId = Auth::id();
    $now = Carbon::now();

    // Aktywne aukcje (te, które jeszcze się nie zakończyły)
    $activeAuctions = Auction::whereHas('bids', function ($query) use ($userId) {
        $query->where('bidder_id', $userId);
    })->whereHas('item', function ($query) use ($now) {
        $query->where('end_time', '>', $now);
    })->get();

    // Zakończone aukcje (te, które się już zakończyły)
    $endedAuctions = Auction::whereHas('bids', function ($query) use ($userId) {
        $query->where('bidder_id', $userId);
    })->whereHas('item', function ($query) use ($now) {
        $query->where('end_time', '<=', $now);
    })->get();

    // Wygrane aukcje
    $wonAuctions = Auction::whereHas('bids', function ($query) use ($userId) {
        $query->where('bidder_id', $userId);
    })->whereHas('item', function ($query) use ($now) {
        $query->where('end_time', '<=', $now);
    })->whereHas('bids', function ($query) use ($userId) {
        $query->where('bidder_id', $userId)
              ->whereRaw('bids_amount = (SELECT MAX(bids_amount) FROM bids WHERE auction_id = auctions.id)');
    })->get();

    return view('auctions.user-auctions', compact('activeAuctions', 'endedAuctions', 'wonAuctions'));
}

    public function show($id)
    {
        $item = Item::with(['images', 'user.address.country', 'itemType', 'condition', 'deliveries', 'category', 'category.subCategory', 'category.subCategory.mainCategory'])->findOrFail($id);
        
        $sellerItems = Item::where('user_id', $item->user_id)->where('id', '!=', $id)->get();
        $currentDateTime = Carbon::now();
    
        return view('items.show', compact('item', 'sellerItems', 'currentDateTime'));
    }
    public function getBidsHistory($id)
    {
        $item = Item::with('auction.bids.bidder')->findOrFail($id);

        $responseHtml = '';
        foreach ($item->auction->bids as $bid) {
            $responseHtml .= '
                
                <div class="p-4 border-b border-gray-200">
                    <p class="text-gray-700">Kwota: '. number_format($bid->bids_amount, 2) .' PLN</p>
                     <p class="text-gray-500">Oferent: '. $bid->bidder->first_name .' '.  $bid->bidder->last_name .'</p>
                      <p class="text-gray-500">Data: '. $bid->bid_date .'</p>
                                                       
                </div>';
        }

        return response()->json($responseHtml);
    }
    public function getDeliveryOptions($id)
    {
        $item = Item::with('deliveries')->findOrFail($id);

        $responseHtml = '';
        foreach ($item->deliveries as $delivery) {
            $responseHtml .= '
                <div class="p-4 border-b border-gray-200">
                    <p class="text-lg font-semibold">' . $delivery->name . '</p>
                    <p class="text-gray-700">' . number_format($delivery->price, 2) . ' PLN</p>
                </div>';
        }

        return response()->json($responseHtml);
    }
 

    
}
