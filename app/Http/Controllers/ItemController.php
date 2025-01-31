<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Conditions;
use App\Models\Delivery;
use App\Models\FixedPriceItem;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\ItemType;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\SubSubCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $categories = MainCategory::with('subCategories.subSubCategories')->get();
        
        $query = Item::query();
        $this->applyFiltersAndSorting($query, $request);

        $items = $query->simplePaginate(9);

        return view('items.index', compact('categories', 'items'));
    }

    private function applyFiltersAndSorting($query, Request $request)
    {
        // Filtry
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('min_price')) {
            $minPrice = $request->min_price;
            $query->where(function($q) use ($minPrice) {
                $q->where('price', '>=', $minPrice)
                  ->orWhere('current_price', '>=', $minPrice);
            });
        }

        if ($request->filled('max_price')) {
            $maxPrice = $request->max_price;
            $query->where(function($q) use ($maxPrice) {
                $q->where('price', '<=', $maxPrice)
                  ->orWhere('current_price', '<=', $maxPrice);
            });
        }

        if ($request->filled('item_type')) {
            $query->where('item_type_id', $request->item_type);
        }

        // Sortowanie
        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'price_asc':
                    $query->orderByRaw('COALESCE(current_price, price) ASC');
                    break;
                case 'price_desc':
                    $query->orderByRaw('COALESCE(current_price, price) DESC');
                    break;
                case 'title_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'title_desc':
                    $query->orderBy('title', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
            }
        }
    }

    public function showMainCategory(Request $request, MainCategory $mainCategory)
    {
        $mainCategory->load('subCategories');
        $query = Item::whereHas('category.subCategory', function ($query) use ($mainCategory) {
            $query->where('main_category_id', $mainCategory->id);
        });

        $this->applyFiltersAndSorting($query, $request);
        $items = $query->simplePaginate(9);

        return view('categories.main', compact('mainCategory', 'items'));
    }

    public function showSubCategory(Request $request, MainCategory $mainCategory, SubCategory $subCategory)
    {
        $subCategory->load('subSubCategories');
        $query = Item::whereHas('category', function ($query) use ($subCategory) {
            $query->where('sub_category_id', $subCategory->id);
        });

        $this->applyFiltersAndSorting($query, $request);
        $items = $query->simplePaginate(9);

        return view('categories.sub', compact('mainCategory', 'subCategory', 'items'));
    }

    public function showSubSubCategory(Request $request, MainCategory $mainCategory, SubCategory $subCategory, SubSubCategories $subSubCategory)
    {
        $query = Item::where('category_id', $subSubCategory->id);

        $this->applyFiltersAndSorting($query, $request);
        $items = $query->simplePaginate(9);

        return view('categories.subsub', compact('mainCategory', 'subCategory', 'subSubCategory', 'items'));
    }

    public function search(Request $request)
{
    $query = $request->input('query');
    
    $items = Item::where(function($q) use ($query) {
        $q->where('title', 'LIKE', "%{$query}%")
          ->orWhere('description', 'LIKE', "%{$query}%");
    });
    if ($request->filled('min_price')) {
        $minPrice = $request->min_price;
        $items->where(function($q) use ($minPrice) {
            $q->where('price', '>=', $minPrice)
              ->orWhere('current_price', '>=', $minPrice);
        });
    }

    if ($request->filled('max_price')) {
        $maxPrice = $request->max_price;
        $items->where(function($q) use ($maxPrice) {
            $q->where('price', '<=', $maxPrice)
              ->orWhere('current_price', '<=', $maxPrice);
        });
    }


    if ($request->filled('item_type')) {
        $items->whereHas('itemType', function($q) use ($request) {
            $q->where('name', $request->input('item_type'));
        });
    }
       // Sortowanie
       if ($request->filled('sort_by')) {
        $sort_by = $request->input('sort_by');
        switch ($sort_by) {
            case 'price_asc':
                $items->orderByRaw('COALESCE(current_price, price) ASC');
                break;
            case 'price_desc':
                $items->orderByRaw('COALESCE(current_price, price) DESC');
                break;
            case 'title_asc':
                $items->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $items->orderBy('title', 'desc');
                break;
            case 'newest':
                $items->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $items->orderBy('created_at', 'asc');
                break;
        }
    }

    $items = $items->simplePaginate(9);
    
    return view('search_results', compact('items', 'query'));
}


        public function create()
    {
        $itemTypes = ItemType::all();
        $mainCategories = MainCategory::all();
        $conditions = Conditions::all();
        $deliveries = Delivery::all();
        return view('items.create', compact('itemTypes', 'mainCategories','conditions','deliveries'));
    }
    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|integer|exists:sub_sub_categories,id',
            'item_type_id' => 'required|integer|exists:item_types,id',
            'condition_id' => 'required|integer|exists:conditions,id',
            'deliveries' => 'required|array|min:1',
            'deliveries.*' => 'integer|exists:deliveries,id',
            'starting_price' => 'nullable|numeric',
            'current_price' => 'nullable|numeric',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'price' => 'nullable|numeric',
            'quantity' => 'nullable|integer|min:1',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ],
        [
            'category_id.required' => 'Musisz wybrać kategorie',
            'description.required' => 'Pole opis jest wymagane',
            'deliveries.required' => 'Musisz wybrać przynajmniej jeden typ wysyłki.',
            'deliveries.min' => 'Musisz wybrać przynajmniej jeden typ wysyłki.',
        ]);

        // Custom validation for start_time and end_time
        $validator = Validator::make($request->all(), [
            'start_time' => 'nullable|date|after_or_equal:now',
            'end_time' => 'nullable|date|after:start_time|after:start_time +1 hour',
        ], [
            'start_time.after_or_equal' => 'Czas rozpoczęcia musi być przynajmniej aktualnym czasem.',
            'end_time.after' => 'Czas zakończenia musi być później niż czas rozpoczęcia.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $item = new Item();
        $item->title = $validated['title'];
        $item->description = $validated['description'];
        $item->category_id = $validated['category_id'];
        $item->item_type_id = $validated['item_type_id'];
        $item->condition_id = $validated['condition_id'];
        $item->user_id = Auth::id();
        $item->status = true;

        if ($item->item_type_id == 1) { // Assuming '1' is the ID for 'Aukcja'
            $item->starting_price = $validated['starting_price'];
            $item->start_time = $validated['start_time'];
            $item->end_time = $validated['end_time'];
            $item->current_price = $validated['starting_price'];
        } elseif ($item->item_type_id == 2) { // Assuming '2' is the ID for 'Kup teraz'
            $item->price = $validated['price'];
            $item->quantity = $validated['quantity'];
        }

        $item->save();

        // Attach selected deliveries to the item
        $item->deliveries()->attach($validated['deliveries']);

        if ($item->item_type_id == 1) { // Adding to auctions table
            Auction::create(['item_id' => $item->id]);
        } elseif ($item->item_type_id == 2) { // Adding to fixed_price_items table
            FixedPriceItem::create(['item_id' => $item->id]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
                ItemImage::create([
                    'item_id' => $item->id,
                    'image_patch' => $path
                ]);
            }
        }

        return redirect()->back()->with('success', 'Item added successfully');
    }

    public function getSubCategories($mainCategoryId)
{
    $subCategories = SubCategory::where('main_category_id', $mainCategoryId)->get();
    return response()->json(['subcategories' => $subCategories]);
}

public function getSubSubCategories($subCategoryId)
{
    $subSubCategories = SubSubCategories::where('sub_category_id', $subCategoryId)->get();
    return response()->json(['sub_sub_categories' => $subSubCategories]);
}
public function favorites()
{
    $user = Auth::user();
    $favorites = $user->favorites()->with('item')->get();

    return view('items.favorites', compact('favorites'));
}

}
