<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto&display=swap" rel="stylesheet">
<link href="https://db.onlinewebfonts.com/c/fbb68f41f3e57ff7ec1e70c0b5b49e4f?family=Lidl+Font+Cond+Pro+Semibold" rel="stylesheet">
<!--<link href="https://db.onlinewebfonts.com/c/7c26fffa083ab4be8067517492cba7fa?family=Lidl+Font+Cond+Pro" rel="stylesheet" type="text/css"/>-->
<link href="https://db.onlinewebfonts.com/c/567f8cfe7e25e3f543d3625cc26af5b4?family=Lidl+Font+Cond+Pro" rel="stylesheet" type="text/css"/>
<style>
    .leaflet-page-rtveuroagd, .leaflet-page-media-expert, .leaflet-page-home-you{
        width: 449px;
        height: 600px;
    }
    .leaflet-page-lidl{
        font-family: "Lidl Font Cond Pro", sans-serif;

        width: 382px;
        height: 600px;
    }

    .font-lidl-semibold {
        font-family: "Lidl Font Cond Pro Semibold", sans-serif;
    }

    .font-Poppins {
        text-align: center;
        font-family: serif;
    }
    .bg-media-expert {
        background-color: #fef200;
    }
    .bg-rtveuroagd {
        background-color: #ffffff;
    }
    .span {
        width: fit-content;
        color: #ffffff;
        background: red;
        font-size: 10px;
        font-weight: bold;
        max-width: 150px;
    }
    .price{
        font-family: fantasy;
        font-style: italic;

    }
    .price-rtveuroagd {
        font-family: Tahoma, Arial, sans-serif;
    }
     body{
         line-height: 0.5 !important;
     }

</style>
<script src="{{asset('assets/js/html2canvas.min.js')}}"></script>


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-2">
            {{ __('Sieci handlowe') }}
        </h2>
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
            <x-nav-link :href="route('leaflets.clickableIndex', $slug = 'mediaexpert')" :active="request()->routeIs('leaflets.clickableIndex')">
                {{ __('Klikane gazetki') }}
            </x-nav-link>

        </div>
    </x-slot>
    <!-- Navigation Links -->


    <div class="py-12">

       <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
           <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="flex h-max" >
                    <div id="leafletGenerated">
                        <div id="leafletCanvas" class="flex flex-col leaflet-page-{{$chain}} {{'bg-'.$chain}} relative">
                            @if($chain == 'media-expert')
                                <x-media-expert.one :data="$data" :image="$image"/>
                            @endif
                            @if($chain == 'rtveuroagd')
                                <x-rtveuroagd.one :data="$data" :image="$image"/>
                            @endif
                                @if($chain == 'home-you')
                                    <x-home-you.one :data="$data" :image="$image"/>
                                @endif
                                @if($chain == 'lidl')
                                    <x-lidl.one :data="$data" :image="$image" :chain="$chain"/>
                                @endif
                        </div>
                    </div>
                    <div class="w-full pl-2 flex flex-col ">

                        <form id="data_generator" class="flex" enctype="multipart/form-data">
                            <div class="flex flex-col gap-4 w-full">
                                <input type="hidden" id="store" name="store" required="required" value="{{$chain}}" />
                                <label class="text-white" for="filename">Numer strony:</label>
                                <input type="text" id="filename" name="filename" class="text-black w-auto" placeholder="Wprowadź numer strony">
                                <div id="error-filename"></div>
                                <label class="text-white" for="url">Link do strony:</label>
                                <input type="url" id="url" name="url" class="text-black" placeholder="Wprowadź link strony">
                                <div id="error-url"></div>
                                <label class="text-white" for="affUrl">Link strony afiliacyjnej:</label>
                                <input type="url" id="affUrl" name="affUrl" required class="text-black w-auto" placeholder="Wprowadź link afiliacyjny">
                                <label class="text-white" for="productId">ID produktu (RTVEUROAGD):</label>
                                <input type="text" id="productId" name="productId" required class="text-black w-auto" placeholder="Wprowadź ID produktu">
                                <div id="error-productId"></div>
                                <label for="photoInput" id="photoLabel" class="cursor-pointer text-white flex leading-3 p-4 border-2 mb-2 hover:bg-green-600 hover:text-red-600 hover:font-extrabold hover:border-green-200 hover:border-b-green-950 hover:border-r-pink-950 w-40 justify-center transition-all duration-700">Wybierz zdjęcie</label>
                                <input type="file" id="photoInput" name="photo" class="hidden">
                                <div id="error-photoInput"></div>
                                <div id="inputPhoto" class="text-white">Nie wybrano pliku</div>
                            </div>
                        </form>
                        <div class="w-full">
                            <button class="text-white flex leading-3 p-4 border-2 mb-2 hover:bg-green-600 hover:text-red-600 hover:font-extrabold hover:border-green-200 hover:border-b-green-950 hover:border-r-pink-950 w-40 justify-center transition-all duration-700" id="generator">Generator</button>
                            <button class="text-white flex leading-3 p-4 border-2 mb-2 hover:bg-yellow-400 w-40 justify-center transition-all duration-700 hover:text-black hover:border-red-500" id="captureButton">Pobierz obraz</button>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
    <script>

        document.getElementById('photoInput').addEventListener('change', function() {
            var label = document.getElementById('inputPhoto');
            if (this.files && this.files[0]) {
                label.textContent = this.files[0].name;
            }
        });

        document.getElementById('captureButton').addEventListener('click', function() {
            let errorStatus = 0;
            let filename = document.getElementById('filename').value;
            if (filename === ''){
                document.getElementById('error-filename').innerHTML = '<p class="text-red-600 text-xs">*Wprowadź numer strony</p>';
                errorStatus = 1;
            }

            if(errorStatus === 1){
                return false;
            }

            html2canvas(document.getElementById('leafletCanvas'), {
                scale: 2,
                useCORS: true,
                proxy: 'http://127.0.0.1:8000/proxy'
            }).then(canvas => {
                // Konwertuj obraz na dane URL
                var imageDataURL = canvas.toDataURL('image/jpeg');

                // Utwórz element linku pobierania
                var downloadLink = document.createElement('a');
                downloadLink.href = imageDataURL;
                downloadLink.download = filename + '.jpg'; // Nazwa pliku do pobrania

                // Kliknij link, aby pobrać obraz
                downloadLink.click();
            });
        });

        document.getElementById('generator').addEventListener('click', function() {
            console.log("generator");
            let errorStatus = 0;
            let filename = document.getElementById('filename').value;
            if (filename === ''){
                document.getElementById('error-filename').innerHTML = '<p class="text-red-600 text-xs">*Wprowadź numer strony</p>';
                errorStatus = 1;
            }
            let store = document.getElementById('store').value;
            let productId = document.getElementById('productId').value;
            let url = document.getElementById('url').value;
            let photoInput = document.getElementById('photoInput').value;
            console.log('url' + url + 'productId' + productId);
            if (url === '' && store === 'media-expert' ){
                document.getElementById('error-url').innerHTML = '<p class="text-red-600 text-xs">*Wprowadź link strony</p>';
                errorStatus = 1;
            } else if (productId === '' && store === 'rtveuroagd' ){
                document.getElementById('error-productId').innerHTML = '<p class="text-red-600 text-xs">*Wprowadź ID produktu</p>';
                errorStatus = 1;
            }

            if(photoInput === '' && store === 'rtveuroagd'){
                document.getElementById('error-photoInput').innerHTML = '<p class="text-red-600 text-xs">*Wczytaj plik</p>';
                errorStatus = 1;
            }

            if(errorStatus === 1){
                return false;
            }

            generator();

        })
    function generator(){
        let formData = new FormData(); // Utwórz obiekt FormData

// Dodaj wszystkie pola formularza do obiektu FormData
        formData.append('store', $('#store').val());
        formData.append('filename', $('#filename').val());
        formData.append('url', $('#url').val());
        formData.append('affUrl', $('#affUrl').val());
        formData.append('productId', $('#productId').val());
        formData.append('photo', $('#photoInput')[0].files[0]); // Dodaj przesłany plik

        $.ajax({
            type: 'POST',
            url: '/generator',
            data: formData,
            processData: false, // Ustawienie processData na false jest konieczne, aby uniknąć przetwarzania danych formularza przez jQuery
            contentType: false, // Ustawienie contentType na false jest konieczne, aby przesłać dane w formie FormData
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function(response) {
                console.log('sukces');
                $('#leafletGenerated').html(response);
                },
            error: function(xhr, status, error) {
                console.error(error + ' ' + xhr + ' obsługa błędów'); // Obsługa błędów
            }
        });
    }

    </script>
</x-app-layout>
