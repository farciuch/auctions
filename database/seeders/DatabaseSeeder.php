<?php

namespace Database\Seeders;

use App\Models\Conditions;
use App\Models\Country;
use App\Models\Delivery;
use App\Models\ItemType;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\SubSubCategories;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $countries = [
            'Afganistan',
            'Albania',
            'Algieria',
            'Andora',
            'Angola',
            'Antigua i Barbuda',
            'Argentyna',
            'Armenia',
            'Australia',
            'Austria',
            'Azerbejdżan',
            'Bahamy',
            'Bahrajn',
            'Bangladesz',
            'Barbados',
            'Białoruś',
            'Belgia',
            'Belize',
            'Benin',
            'Bhutan',
            'Boliwia',
            'Bośnia i Hercegowina',
            'Botswana',
            'Brazylia',
            'Brunei',
            'Bułgaria',
            'Burkina Faso',
            'Burundi',
            'Cabo Verde',
            'Kambodża',
            'Kamerun',
            'Kanada',
            'Republika Środkowoafrykańska',
            'Czad',
            'Chile',
            'Chiny',
            'Kolumbia',
            'Komory',
            'Kongo',
            'Kostaryka',
            'Chorwacja',
            'Kuba',
            'Cypr',
            'Czechy',
            'Demokratyczna Republika Konga',
            'Dania',
            'Dżibuti',
            'Dominika',
            'Dominikana',
            'Ekwador',
            'Egipt',
            'Salwador',
            'Gwinea Równikowa',
            'Erytrea',
            'Estonia',
            'Eswatini (dawniej Suazi)',
            'Etiopia',
            'Fidżi',
            'Finlandia',
            'Francja',
            'Gabon',
            'Gambia',
            'Gruzja',
            'Niemcy',
            'Ghana',
            'Grecja',
            'Grenada',
            'Gwatemala',
            'Gwinea',
            'Gwinea-Bissau',
            'Gujana',
            'Haiti',
            'Honduras',
            'Węgry',
            'Islandia',
            'Indie',
            'Indonezja',
            'Iran',
            'Irak',
            'Irlandia',
            'Izrael',
            'Włochy',
            'Jamajka',
            'Japonia',
            'Jordania',
            'Kazachstan',
            'Kenia',
            'Kiribati',
            'Kuwejt',
            'Kirgistan',
            'Laos',
            'Łotwa',
            'Liban',
            'Lesotho',
            'Liberia',
            'Libia',
            'Liechtenstein',
            'Litwa',
            'Luksemburg',
            'Madagaskar',
            'Malawi',
            'Malezja',
            'Malediwy',
            'Mali',
            'Malta',
            'Wyspy Marshalla',
            'Mauretania',
            'Mauritius',
            'Meksyk',
            'Mikronezja',
            'Mołdawia',
            'Monako',
            'Mongolia',
            'Czarnogóra',
            'Maroko',
            'Mozambik',
            'Mjanma (Birma)',
            'Namibia',
            'Nauru',
            'Nepal',
            'Holandia',
            'Nowa Zelandia',
            'Nikaragua',
            'Niger',
            'Nigeria',
            'Korea Północna',
            'Macedonia Północna',
            'Norwegia',
            'Oman',
            'Pakistan',
            'Palau',
            'Palestyna',
            'Panama',
            'Papua-Nowa Gwinea',
            'Paragwaj',
            'Peru',
            'Filipiny',
            'Polska',
            'Portugalia',
            'Katar',
            'Rumunia',
            'Rosja',
            'Rwanda',
            'Saint Kitts i Nevis',
            'Saint Lucia',
            'Saint Vincent i Grenadyny',
            'Samoa',
            'San Marino',
            'Wyspy Świętego Tomasza i Książęca',
            'Arabia Saudyjska',
            'Senegal',
            'Serbia',
            'Seszele',
            'Sierra Leone',
            'Singapur',
            'Słowacja',
            'Słowenia',
            'Wyspy Salomona',
            'Somalia',
            'Republika Południowej Afryki',
            'Korea Południowa',
            'Sudan Południowy',
            'Hiszpania',
            'Sri Lanka',
            'Sudan',
            'Surinam',
            'Szwecja',
            'Szwajcaria',
            'Syria',
            'Tadżykistan',
            'Tanzania',
            'Tajlandia',
            'Timor Wschodni',
            'Togo',
            'Tonga',
            'Trynidad i Tobago',
            'Tunezja',
            'Turcja',
            'Turkmenistan',
            'Tuvalu',
            'Uganda',
            'Ukraina',
            'Zjednoczone Emiraty Arabskie',
            'Wielka Brytania',
            'Stany Zjednoczone',
            'Urugwaj',
            'Uzbekistan',
            'Vanuatu',
            'Watykan',
            'Wenezuela',
            'Wietnam',
            'Jemen',
            'Zambia',
            'Zimbabwe',
        ];

        foreach ($countries as $country) {
            Country::create(['name' => $country]);
        }


         $mainCategories = [
            'Elektronika' => [
                'Telefony i Akcesoria' => ['Smartfony', 'Ładowarki', 'Etui', 'Powerbanki', 'Kable', 'Zestawy Słuchawkowe', 'Karty Pamięci', 'Uchwyty Samochodowe'],
                'Komputery' => ['Laptopy', 'Komputery Stacjonarne', 'Monitory', 'Drukarki', 'Akcesoria', 'Oprogramowanie', 'Pamięć', 'Dyski Twarde'],
                'RTV i AGD' => ['Telewizory', 'Sprzęt Audio', 'Odkurzacze', 'Pralki', 'Lodówki', 'Mikrofalówki', 'Kuchenki', 'Zmywarki'],
                'Fotografia' => ['Aparaty Cyfrowe', 'Lustrzanki', 'Obiektywy', 'Statywy', 'Torby', 'Akcesoria', 'Karty Pamięci', 'Filtry'],
                'Gry i Konsole' => ['Konsole', 'Gry', 'Kontrolery', 'Akcesoria', 'VR', 'Kabel HDMI', 'Pamięć Zewnętrzna', 'Ładowarki'],
                'Audio' => ['Słuchawki', 'Głośniki', 'Wieże Audio', 'Radia', 'Soundbary', 'Mikrofony', 'Akcesoria', 'Kable'],
                'Smartwatche' => ['Smartwatche', 'Akcesoria', 'Ładowarki', 'Paski', 'Ekrany Ochronne', 'Kable', 'Pamięć', 'Aplikacje'],
                'Drony' => ['Drony', 'Akcesoria', 'Ładowarki', 'Baterie', 'Torby', 'Śmigła', 'Kamery', 'Ekrany Ochronne']
            ],
            'Moda' => [
                'Odzież Damska' => ['Sukienki', 'Bluzki', 'Spodnie', 'Kurtki', 'Swetry', 'Buty', 'Torebki', 'Biżuteria'],
                'Odzież Męska' => ['Koszule', 'Spodnie', 'Kurtki', 'Marynarki', 'Swetry', 'Buty', 'Akcesoria', 'Bielizna'],
                'Odzież Dziecięca' => ['Koszulki', 'Spodnie', 'Sukienki', 'Kurtki', 'Buty', 'Akcesoria', 'Bielizna', 'Nakrycia Głowy'],
                'Obuwie' => ['Buty Sportowe', 'Buty Eleganckie', 'Buty Zimowe', 'Sandały', 'Klapki', 'Kapcie', 'Akcesoria', 'Pielęgnacja Obuwia'],
                'Akcesoria' => ['Torebki', 'Plecaki', 'Paski', 'Okulary Przeciwsłoneczne', 'Biżuteria', 'Kapelusze', 'Rękawiczki', 'Szale'],
                'Biżuteria' => ['Naszyjniki', 'Bransoletki', 'Kolczyki', 'Pierścionki', 'Broszki', 'Zegarki', 'Akcesoria', 'Pudełka na Biżuterię'],
                'Torebki' => ['Torebki Skórzane', 'Torebki Materiałowe', 'Plecaki', 'Teczki', 'Torebki Wieczorowe', 'Nerki', 'Torby na Zakupy', 'Torby Sportowe'],
                'Kosmetyki' => ['Makijaż', 'Pielęgnacja Twarzy', 'Pielęgnacja Ciała', 'Perfumy', 'Akcesoria Kosmetyczne', 'Pielęgnacja Włosów', 'Manicure', 'Pedicure']
            ],
            'Dziecko' => [
                'Zabawki' => ['Klocki', 'Lalki', 'Puzzle', 'Gry Planszowe', 'Maskotki', 'Zabawki Edukacyjne', 'Zabawki Interaktywne', 'Kolejki'],
                'Ubrania Dziecięce' => ['Koszulki', 'Spodnie', 'Sukienki', 'Kurtki', 'Buty', 'Czapki', 'Szale', 'Rękawiczki'],
                'Akcesoria dla Niemowląt' => ['Wózki', 'Foteliki', 'Łóżeczka', 'Nosidełka', 'Smoczki', 'Butelki', 'Śliniaki', 'Kocyki'],
                'Gry i Zabawki Edukacyjne' => ['Książki', 'Puzzlo-Książki', 'Zestawy Doświadczalne', 'Gry Planszowe', 'Układanki', 'Tablice Magnetyczne', 'Karty Edukacyjne', 'Muzyczne Zabawki'],
                'Higiena i Pielęgnacja' => ['Pieluchy', 'Chusteczki', 'Kremy', 'Szampony', 'Płyny do Kąpieli', 'Gąbki', 'Nożyczki', 'Szczoteczki'],
                'Meble Dziecięce' => ['Łóżeczka', 'Komody', 'Szafki', 'Krzesła', 'Stoliki', 'Regały', 'Półki', 'Zabawki Meblowe'],
                'Wyprawka' => ['Kocyki', 'Pościel', 'Poduszki', 'Śpiwory', 'Pieluchy Tetrowe', 'Śliniaki', 'Butelki', 'Termometry'],
                'Wózki i Foteliki' => ['Wózki Spacerowe', 'Gondole', 'Foteliki Samochodowe', 'Nosidełka', 'Bazy do Fotelików', 'Akcesoria do Wózków', 'Torby do Wózków', 'Ochraniacze']
            ],
            'Sport i turystyka' => [
                'Odzież Sportowa' => ['Koszulki', 'Spodnie', 'Buty', 'Bluzy', 'Kurtki', 'Czapki', 'Rękawiczki', 'Skarpety'],
                'Sprzęt Sportowy' => ['Piłki', 'Rakiety', 'Hantle', 'Gumy', 'Maty', 'Sztangi', 'Ławki', 'Bieżnie'],
                'Akcesoria Turystyczne' => ['Plecaki', 'Śpiwory', 'Namioty', 'Kije Trekkingowe', 'Termosy', 'Latarki', 'Mapy', 'Apteczki'],
                'Fitness' => ['Maty', 'Gumy', 'Hantle', 'Rowery Stacjonarne', 'Orbitreki', 'Steppery', 'Bieżnie', 'Ławki'],
                'Rowery i Akcesoria' => ['Rowery', 'Kaski', 'Rękawiczki', 'Pompki', 'Łańcuchy', 'Bagażniki', 'Bidony', 'Oświetlenie'],
                'Sporty Zimowe' => ['Narty', 'Deski Snowboardowe', 'Buty Narciarskie', 'Kaski', 'Gogle', 'Odzież Zimowa', 'Rękawice', 'Kije Narciarskie'],
                'Wspinaczka' => ['Liny', 'Uprzęże', 'Kaski', 'Buty', 'Karabinki', 'Czekany', 'Raki', 'Plecy'],
                'Sporty Wodne' => ['Deski Surfingowe', 'Kajaki', 'Wiosła', 'Kombinezony', 'Kapoki', 'Fins', 'Maski', 'Rurki']
            ],
            'Supermarket' => [
                'Spożywcze' => ['Nabiał', 'Mięso', 'Ryby', 'Owoce', 'Warzywa', 'Napoje', 'Słodycze', 'Pieczywo'],
                'Środki Czystości' => ['Proszki do Prania', 'Płyny do Naczyń', 'Środki do Toalet', 'Płyny Uniwersalne', 'Środki do Szyb', 'Środki do Podłóg', 'Środki do Mebli', 'Środki do Prania'],
                'Artykuły dla Zwierząt' => ['Karma dla Psów', 'Karma dla Kotów', 'Zabawki', 'Legowiska', 'Klatki', 'Smycze', 'Obroże', 'Kuwety'],
                'Artykuły Higieniczne' => ['Papier Toaletowy', 'Ręczniki Papierowe', 'Chusteczki Higieniczne', 'Podpaski', 'Tampony', 'Pieluchy', 'Kosmetyki Higieniczne', 'Płyny do Higieny'],
                'Napoje' => ['Soki', 'Napoje Gazowane', 'Napoje Energetyczne', 'Woda', 'Kawa', 'Herbata', 'Napoje Alkoholowe', 'Syropy'],
                'Słodycze i Przekąski' => ['Czekolady', 'Batoniki', 'Chipsy', 'Ciastka', 'Gumy do Żucia', 'Cukierki', 'Orzeszki', 'Popcorn'],
                'Artykuły dla Dzieci' => ['Pieluchy', 'Chusteczki', 'Butelki', 'Smoczki', 'Kremy', 'Szampony', 'Płyny do Kąpieli', 'Zabawki'],
                'Artykuły Kuchenne' => ['Garnki', 'Patelnie', 'Noże', 'Sztućce', 'Talerze', 'Kubki', 'Słoiki', 'Deski do Krojenia']
            ],
            'Dom i ogród' => [
                'Meble' => ['Stoły', 'Krzesła', 'Sofy', 'Łóżka', 'Szafy', 'Komody', 'Regały', 'Biurka'],
                'Wyposażenie Wnętrz' => ['Dywany', 'Firany', 'Poduszki', 'Koce', 'Zasłony', 'Lustra', 'Obrazy', 'Świece'],
                'Ogród' => ['Rośliny', 'Doniczki', 'Nawozy', 'Narzędzia Ogrodowe', 'Grille', 'Meble Ogrodowe', 'Baseny', 'Dekoracje Ogrodowe'],
                'Oświetlenie' => ['Lampy Wiszące', 'Lampy Stojące', 'Kinkiety', 'Lampki Nocne', 'Żyrandole', 'Lampy Ogrodowe', 'Lampy LED', 'Świetlówki'],
                'Narzędzia' => ['Młotki', 'Wkrętarki', 'Śrubokręty', 'Piły', 'Miary', 'Poziomice', 'Kombinerki', 'Klucze'],
                'Dekoracje' => ['Świece', 'Wazony', 'Obrazy', 'Poduszki Dekoracyjne', 'Figurki', 'Zasłony', 'Obrusy', 'Zasłonki'],
                'Akcesoria Kuchenne' => ['Garnki', 'Patelnie', 'Noże', 'Deski do Krojenia', 'Kubki', 'Talerze', 'Miski', 'Łyżki'],
                'Sprzęt AGD' => ['Odkurzacze', 'Pralki', 'Lodówki', 'Mikrofalówki', 'Kuchenki', 'Zmywarki', 'Żelazka', 'Suszarki']
            ],
            'Uroda' => [
                'Makijaż' => ['Podkłady', 'Pudry', 'Cienie do Powiek', 'Tusz do Rzęs', 'Pomadki', 'Błyszczyki', 'Kredki do Oczu', 'Lakiery do Paznokci'],
                'Pielęgnacja Twarzy' => ['Kremy', 'Toniki', 'Maseczki', 'Peelingi', 'Serum', 'Płyny Micelarne', 'Oczyszczacze', 'Zestawy'],
                'Pielęgnacja Ciała' => ['Balsamy', 'Oleje', 'Żele pod Prysznic', 'Peelingi', 'Dezodoranty', 'Antyperspiranty', 'Masła', 'Kremy'],
                'Perfumy' => ['Perfumy Damskie', 'Perfumy Męskie', 'Wody Toaletowe', 'Wody Perfumowane', 'Wody Kolońskie', 'Zestawy Perfum', 'Dezodoranty', 'Żele pod Prysznic'],
                'Akcesoria Kosmetyczne' => ['Pędzle', 'Gąbki', 'Pilniki', 'Nożyczki', 'Pęsety', 'Lusterka', 'Organizery', 'Podgrzewacze'],
                'Pielęgnacja Włosów' => ['Szampony', 'Odżywki', 'Maski', 'Serum', 'Olejki', 'Spraye', 'Loki', 'Prostownice'],
                'Manicure' => ['Lakiery', 'Zmywacze', 'Pilniki', 'Polerki', 'Odtłuszczacze', 'Lampy UV', 'Pędzle', 'Tipsy'],
                'Pedicure' => ['Pilniki', 'Frezarki', 'Zmywacze', 'Pumeks', 'Kremy', 'Lakiery', 'Olejki', 'Zestawy']
            ],
            'Zdrowie' => [
                'Leki' => ['Leki Przeciwbólowe', 'Leki na Przeziębienie', 'Leki na Ból Gardła', 'Leki na Ból Stawów', 'Leki na Alergię', 'Leki na Kaszel', 'Leki na Żołądek', 'Leki na Wzdęcia'],
                'Suplementy' => ['Witaminy', 'Mineraly', 'Preparaty Ziołowe', 'Odżywki Białkowe', 'Preparaty na Odporność', 'Preparaty na Stawy', 'Preparaty na Włosy', 'Preparaty na Skórę'],
                'Sprzęt Medyczny' => ['Ciśnieniomierze', 'Termometry', 'Inhalatory', 'Glukometry', 'Pulsometry', 'Wagi', 'Aparaty Słuchowe', 'Defibrylatory'],
                'Higiena' => ['Pasty do Zębów', 'Szczoteczki do Zębów', 'Nici Dentystyczne', 'Płyny do Płukania Ust', 'Dezodoranty', 'Antyperspiranty', 'Chusteczki', 'Pieluchy'],
                'Kosmetyki' => ['Kremy', 'Szampony', 'Żele pod Prysznic', 'Balsamy', 'Dezodoranty', 'Maseczki', 'Peelingi', 'Olejki'],
                'Dieta' => ['Diety Odchudzające', 'Diety na Masę', 'Diety Specjalistyczne', 'Diety Bezglutenowe', 'Diety Wegetariańskie', 'Diety Wegańskie', 'Diety dla Diabetyków', 'Diety Antyalergiczne'],
                'Urazy i Kontuzje' => ['Bandaże', 'Plastry', 'Opaski Elastyczne', 'Opatrunki', 'Ortezy', 'Temblaki', 'Leki Przeciwbólowe', 'Leki na Stawy'],
                'Zioła' => ['Herbaty Ziołowe', 'Napary', 'Kapsułki', 'Tabletki', 'Oleje', 'Maści', 'Kremy', 'Syropy']
            ],
            'Motoryzacja' => [
                'Samochody' => ['Sedany', 'Kombi', 'SUV', 'Crossovery', 'Kabriolety', 'Coupe', 'Minivany', 'Hatchbacki'],
                'Motocykle' => ['Choppery', 'Cruisery', 'Enduro', 'Motocrossy', 'Skutery', 'Sportowe', 'Touring', 'Naked'],
                'Części Samochodowe' => ['Silniki', 'Zawieszenia', 'Hamulec', 'Układ Wydechowy', 'Układ Paliwowy', 'Układ Elektryczny', 'Skrzynie Biegów', 'Filtry'],
                'Części Motocyklowe' => ['Silniki', 'Zawieszenia', 'Hamulec', 'Układ Wydechowy', 'Układ Paliwowy', 'Układ Elektryczny', 'Skrzynie Biegów', 'Filtry'],
                'Akcesoria Samochodowe' => ['Pokrowce', 'Dywaniki', 'Bagażniki', 'Łańcuchy', 'Kamery', 'Nawigacje', 'Oświetlenie', 'Czujniki'],
                'Akcesoria Motocyklowe' => ['Kaski', 'Kombinezony', 'Rękawice', 'Buty', 'Gogle', 'Kamizelki', 'Ochraniacze', 'Torby'],
                'Opony' => ['Opony Letnie', 'Opony Zimowe', 'Opony Całoroczne', 'Opony do Motocykli', 'Felgi', 'Koła Zapasowe', 'Łańcuchy', 'Akcesoria'],
                'Płyny i Oleje' => ['Olej Silnikowy', 'Płyn Hamulcowy', 'Płyn Chłodniczy', 'Płyn do Spryskiwaczy', 'Płyn Wspomagania', 'Smary', 'AdBlue', 'Dodatek do Paliwa']
            ],
            'Kultura i rozrywka' => [
                'Książki' => ['Kryminały', 'Fantastyka', 'Literatura Piękna', 'Komiksy', 'Podręczniki', 'Poradniki', 'Albumy', 'Przewodniki'],
                'Muzyka' => ['CD', 'Winyle', 'Instrumenty Muzyczne', 'Akcesoria Muzyczne', 'Nagłośnienie', 'Słuchawki', 'Głośniki', 'Mikrofony'],
                'Filmy' => ['DVD', 'Blu-ray', 'Filmy Akcji', 'Komedia', 'Dramat', 'Horror', 'Sci-Fi', 'Animowane'],
                'Gry' => ['Gry Planszowe', 'Gry Karciane', 'Gry RPG', 'Gry Edukacyjne', 'Puzzle', 'Szachy', 'Warhammer', 'Zabawki Kreatywne'],
                'Koncerty' => ['Rock', 'Pop', 'Jazz', 'Klasyczna', 'Hip-Hop', 'Elektroniczna', 'Country', 'Reggae'],
                'Teatr' => ['Dramat', 'Komedia', 'Musical', 'Opera', 'Balet', 'Spektakle dla Dzieci', 'Teatr Uliczny', 'Kabaret'],
                'Wystawy' => ['Sztuka Współczesna', 'Fotografia', 'Rzeźba', 'Malarstwo', 'Grafika', 'Instalacje', 'Wideo', 'Multimedia'],
                'Festiwale' => ['Filmowe', 'Muzyczne', 'Teatralne', 'Literackie', 'Sztuki', 'Taniec', 'Kulinarne', 'Kultura Ludowa']
            ],

        ];

        foreach ($mainCategories as $mainCategoryName => $subCategories) {
            $mainCategory = MainCategory::create(['name' => $mainCategoryName]);

            foreach ($subCategories as $subCategoryName => $subSubCategories) {
                $subCategory = SubCategory::create([
                    'main_category_id' => $mainCategory->id,
                    'name' => $subCategoryName,
                ]);

                foreach ($subSubCategories as $subSubCategoryName) {
                    SubSubCategories::create([
                        'sub_category_id' => $subCategory->id,
                        'name' => $subSubCategoryName,
                    ]);
                }
            }
        }

        ItemType::create(['name' => 'Aukcja']);
        ItemType::create(['name' => 'Kup teraz']);

        Conditions::create(['name'=> 'Nowy']);
        Conditions::create(['name'=> 'Bardzo dobry']);
        Conditions::create(['name'=> 'Ze śladami użytkowania']);
        Conditions::create(['name'=> 'Uszkodzony']);

        Delivery::create(['name'=> 'Przesyłka kurierska - przedpłata', 'price' => '17.00']);
        Delivery::create(['name'=> 'Przesyłka kurierska - pobranie', 'price' => '19.00']);
        Delivery::create(['name'=> 'Paczka pocztowa', 'price' => '14.00']);
        Delivery::create(['name'=> 'Odbiór w punkcie: Paczkomaty 24/7 InPost - przedpłata', 'price' => '16.99']);
        Delivery::create(['name'=> 'Odbiór w punkcie: Paczkomaty 24/7 InPost - pobranie', 'price' => '20.00']);
        Delivery::create(['name'=> 'Odbiór osobisty - przedpłata', 'price' => '0.00']);
        Delivery::create(['name'=> 'Odbiór osobisty - płatność przy odbiorze', 'price' => '0.00']);
        Delivery::create(['name'=> 'Dostawa przez sprzedającego - przedpłata', 'price' => '14.00']);

    }
}
