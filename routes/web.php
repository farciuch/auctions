<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FixedPriceItemController;
use App\Http\Controllers\AuctionController;

//strona startowa
Route::get('/', [CategoryController::class, 'index']);

//obsługa podstron kategorii oraz filtrów wyszukiwania
Route::get('/adsearch', [ItemController::class, 'index'])->name('items.index');
Route::get('/category/{mainCategory}', [ItemController::class, 'showMainCategory'])->name('mainCategory.show');
Route::get('/category/{mainCategory}/{subCategory}', [ItemController::class, 'showSubCategory'])->name('subCategory.show');
Route::get('/category/{mainCategory}/{subCategory}/{subSubCategory}', [ItemController::class, 'showSubSubCategory'])->name('subSubCategory.show');

//obsługa wyszukiwarki
Route::get('/search', [ItemController::class, 'search'])->name('items.search');

//widok przedmiotu
Route::get('/items/{item}', [CategoryController::class, 'show'])->name('items.show');

//opcje rejestracji i logowania
Route::middleware('guest')->group(function(){
    Route::get('/register', [RegisteredUserController::class,'create']);
    Route::post('/register', [RegisteredUserController::class,'store']);
    
    Route::get('/login', [SessionController::class,'create']);
    Route::post('/login', [SessionController::class,'store']);
    
});


//widok profilu sprzedawcy
Route::get('/seller/profile/{id}', [RegisteredUserController::class, 'showProfile'])->name('profile');

Route::get('/profile', [RegisteredUserController::class, 'myItems'])->middleware('auth')->name('my.items');
Route::delete('/profile/{item}', [RegisteredUserController::class, 'destroy'])->middleware('auth')->name('items.destroy');
//wylogowanie
Route::delete('/logout', [SessionController::class,'destroy'])->middleware('auth');

//edycja profilu 
Route::get('/edit', [RegisteredUserController::class,'edit'])->middleware('auth');
Route::patch('/edit', [RegisteredUserController::class,'update'])->middleware('auth');

//obsługa dodawania przedmiotu
Route::get('/create', [ItemController::class, 'create'])->middleware('auth');
Route::post('/create', [ItemController::class, 'store'])->middleware('auth');
Route::get('/api/subcategories/{mainCategoryId}', [ItemController::class, 'getSubCategories']);
Route::get('/api/sub_subcategories/{subCategoryId}', [ItemController::class, 'getSubSubCategories']);

//obsługa rodzajów dostawy
Route::get('/items/{id}/delivery-options', [CategoryController::class, 'getDeliveryOptions'])->name('items.deliveryOptions');






//obsługa ulubionych przedmiotów
Route::middleware('auth')->group(function () {
    Route::get('/favorites', [ItemController::class, 'favorites'])->name('favorites');
    Route::get('/favorites/add/{id}', [RegisteredUserController::class, 'addToFavorites'])->name('favorites.add');
    Route::get('/favorites/remove/{id}', [RegisteredUserController::class, 'removeFromFavorites'])->name('favorites.remove');
});



// obsługa czatu użytkowników
 use App\Http\Controllers\ChatController;

Route::middleware(['auth'])->group(function () {
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chats.show');
    Route::post('/chats/{chat}/messages', [ChatController::class, 'storeMessage'])->name('chats.storeMessage');
});



// obsługa koszyka
use App\Http\Controllers\CartController;

Route::middleware('auth')->group(function () {
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
    Route::get('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/removeMultiple', [CartController::class, 'removeMultiple'])->name('cart.removeMultiple');
    Route::post('/cart/updateQuantity/{id}', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
});

//obsługa kupowania przedmiotów znajdujących się w koszyku
use App\Http\Controllers\PurchaseController;

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [PurchaseController::class, 'showCheckout'])->name('checkout.show');
    Route::post('/checkout', [PurchaseController::class, 'showCheckout'])->name('checkout.post');
    Route::get('/checkout/edit', [PurchaseController::class, 'edit']);
    Route::patch('/checkout', [PurchaseController::class, 'update']);
    Route::post('/checkout/process', [PurchaseController::class, 'processCheckout'])->name('checkout.process');
});


//obsługa kupowania przedmiotu ze strony przedmiotu
use App\Http\Controllers\ItemsCheckoutController;

Route::middleware(['auth'])->group(function () {
    Route::get('/items/checkout/{itemId}', [ItemsCheckoutController::class, 'showItemsCheckout'])->name('items.checkout.show');
    Route::post('/items/checkout/{itemId}/process', [ItemsCheckoutController::class, 'processSingleItemCheckout'])->name('items.checkout.processSingle');
    Route::get('/items/update/{itemId}', [ItemsCheckoutController::class, 'editItems'])->name('items.edit');
    Route::patch('/items/checkout/{itemId}', [ItemsCheckoutController::class, 'updateItems']);
});


//obsługa aukcji

Route::get('/user-auctions', [CategoryController::class, 'userAuctions'])->name('user.auctions')->middleware('auth');
Route::post('/items/{id}/place-bid', [CategoryController::class, 'placeBid'])->name('items.placeBid')->middleware('auth');
Route::post('/bids', [CategoryController::class, 'store'])->name('bids.store')->middleware('auth');
Route::get('/items/{id}/bids-history', [CategoryController::class, 'getBidsHistory'])->name('items.bidsHistory');


//obsługa zamówienia wygranej aukcji
Route::middleware(['auth'])->group(function () {
    Route::get('/user-auctions/checkout/{item}', [AuctionController::class, 'showAuctionCheckout'])->name('auctions.auction-checkout.show');
    Route::post('/user-auctions/checkout/{item}/process', [AuctionController::class, 'processSingleAuctionCheckout'])->name('auctions.auction-checkout.processSingle');
    Route::get('/user-auctions/update/{item}', [AuctionController::class, 'editAuction'])->name('auctions.edit');
    Route::patch('/user-auctions/checkout/{item}', [AuctionController::class, 'updateAuction']);
});