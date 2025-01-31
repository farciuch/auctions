<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serwis</title>
    <link rel="shortcut icon" href="{{ Vite::asset('resources/images/logo.svg') }}">
    <script src="https://cdn.ckeditor.com/ckeditor5/35.2.0/classic/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        .hidden {
            display: none;
        }
        .block-container {
            display: flex;
            flex-direction: row;
            width: 100%;
        }
        .block {
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
            
            
        }
        .block-item {
            width: 100%;
            
        }
        .sub-category-link {
            font-size: 0.9em;
            font-weight: bold;
        }
        .sub-sub-category-link {
            font-size: 0.7em;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md py-3">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
            <div class="flex justify-between h-16 mb-10">
                <div class="flex">
                    <a href="/" class="flex-shrink-0 flex items-center">
                        <img class="h-20 w-20" src="{{ Vite::asset('resources/images/logo.svg') }}" alt="Logo">
                        <span class="ml-2 font-semibold text-2xl">Serwis Aukcyjny</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class=" md:flex space-x-4">
                        
                        @auth

                        <a href="{{ route('user.auctions') }}">
                            <img src="{{ Vite::asset('resources/images/auction.svg') }}" alt="Aukcje" class="h-8 w-8">
                        </a>
                            <a href="/create" class="text-gray-900 hover:text-gray-700 text-xl">Dodaj przedmiot</a>
                            <a href="{{ route('chats.index') }}" class="mr-4"> 
                                {{-- nazwa w web --}}
                                <img src="{{ Vite::asset('resources/images/czat.svg') }}" alt="Czaty" class="h-8 w-8">
                            </a>

                        @endauth
                        
                    </div>
                </div>
                <div class="flex items-center">
                    @auth
                        
                        <a href="{{ route('my.items') }}" class="text-gray-900 hover:text-gray-700 text-xl">
                            Profil
                         </a>
                        <form method="POST" action="/logout">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ml-4 mr-4 text-gray-900 hover:text-gray-700 text-xl">Wyloguj</button>
                        </form>
                        <a href="/favorites" >
                            <img class="h-10 w-10" src="{{ Vite::asset('resources/images/ulub.svg') }}" alt="serce">
                        </a> 
                        <a href="/cart" >
                            <img class="h-10 w-10" src="{{ Vite::asset('resources/images/cart.svg') }}" alt="cart">
                        </a> 
                    @endauth
                    @guest
                        <a href="/login" class="text-gray-900 hover:text-gray-700 text-xl">Logowanie</a>
                        <a href="/register" class="ml-4 text-gray-900 hover:text-gray-700 text-xl">Rejestracja</a>
                    @endguest
                </div>
            </div>

            <div class="flex justify-between">
                <div class="relative">
                    <button id="categoriesButton" class="text-white hover:text-gray-700 text-xl bg-black/70 p-2 ">Kategorie</button>
                    <!-- Dropdown -->
                    <div id="categoriesMenu" class="absolute left-0 mt-2 w-64 bg-white shadow-lg hidden dropdown-menu">
                        <ul>
                            <a href="{{ route('items.index') }}" class="px-4 py-2">
                             Wszystkie 
                            </a>
                            @foreach($mainCategories as $mainCategory)
                                <div class="px-4 py-2 hover:bg-gray-100 relative group">
                                    
                                    <a href="{{ route('mainCategory.show', $mainCategory->id) }}" class="main-category-button">{{ $mainCategory->name }}</a>
                                    <!-- Subcategories and sub-subcategories here -->
                                    <div class="absolute left-full  top-0 mt-2 w-fill bg-white shadow-lg hidden sub-category-menu">
                                        <div class="block-container">
                                            <div class="block">
                                                @foreach($mainCategory->subCategories->slice(0, 2) as $subCategory)
                                                    <div class="block-item px-4 py-1 hover:bg-gray-100 mb-5">
                                                        <a href="{{ route('subCategory.show', [$mainCategory->id, $subCategory->id]) }}" class="sub-category-link">{{ $subCategory->name }}</a>
                                                        <ul>
                                                            @foreach($subCategory->subSubCategories as $subSubCategory)
                                                                <li class="px-4 py-1 hover:bg-gray-100">
                                                                    <a href="{{ route('subSubCategory.show', [$mainCategory->id, $subCategory->id, $subSubCategory->id]) }}" class="sub-sub-category-link">{{ $subSubCategory->name }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="block">
                                                @foreach($mainCategory->subCategories->slice(2, 2) as $subCategory)
                                                    <div class="block-item px-4 py-1 hover:bg-gray-100 mb-5">
                                                        <a href="{{ route('subCategory.show', [$mainCategory->id, $subCategory->id]) }}" class="sub-category-link">{{ $subCategory->name }}</a>
                                                        <ul>
                                                            @foreach($subCategory->subSubCategories as $subSubCategory)
                                                                <li class="px-4 py-1 hover:bg-gray-100">
                                                                    <a href="{{ route('subSubCategory.show', [$mainCategory->id, $subCategory->id, $subSubCategory->id]) }}" class="sub-sub-category-link">{{ $subSubCategory->name }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="block">
                                                @foreach($mainCategory->subCategories->slice(4, 2) as $subCategory)
                                                    <div class="block-item px-4 py-1 hover:bg-gray-100 mb-5">
                                                        <a href="{{ route('subCategory.show', [$mainCategory->id, $subCategory->id]) }}" class="sub-category-link">{{ $subCategory->name }}</a>
                                                        <ul>
                                                            @foreach($subCategory->subSubCategories as $subSubCategory)
                                                                <li class="px-4 py-1 hover:bg-gray-100">
                                                                    <a href="{{ route('subSubCategory.show', [$mainCategory->id, $subCategory->id, $subSubCategory->id]) }}" class="sub-sub-category-link">{{ $subSubCategory->name }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="block">
                                                @foreach($mainCategory->subCategories->slice(6, 2) as $subCategory)
                                                    <div class="block-item px-4 py-1 hover:bg-gray-100 mb-5">
                                                        <a href="{{ route('subCategory.show', [$mainCategory->id, $subCategory->id]) }}" class="sub-category-link">{{ $subCategory->name }}</a>
                                                        <ul>
                                                            @foreach($subCategory->subSubCategories as $subSubCategory)
                                                                <li class="px-4 py-1 hover:bg-gray-100">
                                                                    <a href="{{ route('subSubCategory.show', [$mainCategory->id, $subCategory->id, $subSubCategory->id]) }}" class="sub-sub-category-link">{{ $subSubCategory->name }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </ul>
                    </div>
                </div>   
                <div class="flex" >
                    <form action="{{ route('items.search') }}" method="GET" class="flex">
                        <input type="text" name="query" placeholder="Szukaj..." class="border-2 border-black ">
                        <button type="submit" class="bg-black/70 text-white px-4 py-1 ml-2">Szukaj</button>
                    </form>
                </div>
            </div>

            
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-md mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center">
            <p class="text-gray-700">&copy; 2024 Serwis Aukcyjny. Wszystkie prawa zastrzeżone.</p>
        </div>
    </footer>
  
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoriesButton = document.getElementById('categoriesButton');
            const categoriesMenu = document.getElementById('categoriesMenu');
            let activeSubMenu = null;

            categoriesButton.addEventListener('click', function () {
                categoriesMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', function (event) {
                if (!categoriesButton.contains(event.target) && !categoriesMenu.contains(event.target)) {
                    categoriesMenu.classList.add('hidden');
                }
            });

            const mainCategoryButtons = document.querySelectorAll('.main-category-button');
            mainCategoryButtons.forEach(button => {
                button.addEventListener('mouseenter', function () {
                    const subMenu = this.nextElementSibling;
                    if (activeSubMenu && activeSubMenu !== subMenu) {
                        activeSubMenu.classList.add('hidden');
                    }
                    subMenu.classList.remove('hidden');
                    activeSubMenu = subMenu;
                });

                button.addEventListener('mouseleave', function () {
                    const subMenu = this.nextElementSibling;
                    setTimeout(() => {
                        if (!subMenu.matches(':hover')) {
                            subMenu.classList.add('hidden');
                        }
                    }, 300);
                });
            });

            document.querySelectorAll('.sub-category-menu').forEach(menu => {
                menu.addEventListener('mouseleave', function () {
                    this.classList.add('hidden');
                });
            });
        });
    </script>
        <script>
            $(document).ready(function() {
                $('.favorite-toggle').on('click', function() {
                    var itemId = $(this).data('item-id');
                    var isFavorite = $(this).data('favorite') === 1;
    
                    var url = isFavorite ? '{{ route('favorites.remove', '') }}/' + itemId : '{{ route('favorites.add', '') }}/' + itemId;
    
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: (response) => {
                            if (isFavorite) {
                                $(this).find('img').attr('src', '{{ Vite::asset('resources/images/ulub.svg') }}');
                                $(this).data('favorite', 0);
                            } else {
                                $(this).find('img').attr('src', '{{ Vite::asset('resources/images/ulub2.svg') }}');
                                $(this).data('favorite', 1);
                            }
                        },
                        error: (xhr) => {
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        </script>
</body>
</html>



