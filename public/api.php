<?php
// Your OpenAI API key
$apiKey = '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';
$shop = 'Dino';
// The prompt you want to send

// Data array
$messages = [
    [
        'role' => 'user',
        'content' => 'napisz artykuł opisujący gazetkę promocyjną w sklepach '.$shop.' zawierającą produkty almette
andruty
babeczki instant
bagietka z masłem czosnkowym
baterie
baton Milky Way
biszkopty
bita śmietana w proszku
bita śmietana w sprayu
boczek wieprzowy
bombonierka
borówka amerykańska
brzoskwinie w syropie
budyń
bulionówka
bułka pszenno-żytnia
buraki gotowane
butelka filtrująca
cebula
chipsy
chleb pszenny
chleb wieloziarnisty
chrzan tarty
ciastka
ciasto
cukier wanilinowy
cukierki
czekolada Goplana
czekolada mleczna
czekoladki
danie dla dzieci
danie gotowe
danie z makaronem
danio
dracena
dzbanek filtrujący
dżem
erytrytol
filet z dorsza
filet z mintaja
filety śledziowe w sosie
flaki po zamojsku
frankfurterki
frytki
galaretka
goździki
granat
grapefruit
groch łuskany
groszek konserwowy
herbata Lipton
herbata owocowa
herbata zielona
herbata ziołowa
herbatniki
indyk w galarecie
jabłka
jaja
jogurt Jogobella
jogurt naturalny
kaki
kaktus
kapcie damskie
kapsułki do prania
kapusta czerwona
kapusta kiszona
karma dla kota
karma dla psa
kasza manna
kaszka ryżowa
kawa cappuccino
kawa inka
kawa mielona
kawa rozpuszczalna
kawa ziarnista
kefir
kiełbasa krakowska
kiełbasa zwyczajna
kiełbasa żywiecka
kisiel
klej w sztyfcie
kluski na parze
kluski śląskie
kolorowanka
koncentrat do płukania
koncentrat pomidorowy
konfitura
konserwa mięsna
kosz plastikowy
krem do twarzy
kwasek cytrynowy
lizak
lody
majonez
makaron
makrela w sosie
mandarynki
margaryna do smarowania
margaryna Kasia
margaryna Optima
masło
maszynka do golenia
mata termiczna na szybę
mąka krupczatka
mąka tortowa
mieszanka bakaliowa
mięso wołowe
miód
mleko Nan
musli
musztarda
napój czekoladowy
napój energetyczny
napój gazowany
napój roślinny
ogórki konserwowe
olej roślinny
orzeszki ziemne
Oshee
paluszki
paluszki rybne
paprykarz szczeciński
parówki
passata pomidorowa
pasztet
pesto
pieluszki
piwo Budweiser
piwo cortes
piwo Desperados
piwo Książ
piwo lech
piwo Staropramen
piwo Żubr
płyn do wc
płyn uniwersalny
polędwica wędzona
pomidory
popcorn
proszek do prania
przekąski dla kota
przyprawa
przyprawa w płynie
puzzle
rajstopy
Red Bull
ręcznik papierowy
rolmopsy
rozgałęźnik
ryż biały
sałata masłowa
sałatka
sałatka warzywna
ser Cheddar
ser Edamski
ser feta
ser twarogowy
serek wiejski
sok Hortex
sok Kubuś
sos instant
suplement diety
surówka
syrop malinowy
szampon do włosów
szpinak mrożony
szynka eksportowa
szynka konserwowa
szynka z indyka
ściereczka
śledzik na raz
śmietana
śmietanka
talerz
teczka z gumką
twaróg sernikowy
wafel
wafle
wafle ryżowe
warzywa konserwowe
warzywa mrożone
wiśnie drylowane
wiśnie mrożone
wkład filtrujący
wkład parafinowy do znicza
wkładki higieniczne
włoszczyzna
woda mineralna
woda źródlana
woreczki śniadaniowe
worki na śmieci
zapiekanka
zeszyt
żelki
żurawina suszona
żurek'
    ]
    // Add more messages as needed
];

// Data array
$data = [
    'model' => 'gpt-3.5-turbo', // Use the correct model for chat
    'messages' => $messages
];
// API endpoint for ChatGPT
$apiUrl = 'https://api.openai.com/v1/chat/completions';

// Initialize cURL
$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

// Execute the POST request and get the response
$response = curl_exec($ch);

// Close cURL
curl_close($ch);

// Decode the JSON response
$result = json_decode($response, true);

// Print the response (or handle it as per your need)
echo '<pre>'; print_r($result); echo '</pre>';
?>
