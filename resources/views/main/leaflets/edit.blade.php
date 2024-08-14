<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto&display=swap" rel="stylesheet">

<style>
    .leaflet-page{
        width: 449px;
        height: 600px;
    }
    .font-Poppins {
        text-align: center;
        font-family: serif;
    }
    .bg-media {
        background-color: #fef200;
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
            <x-nav-link :href="route('leaflets.clickableIndex')" :active="request()->routeIs('leaflets.clickableIndex')">
                {{ __('Klikane gazetki') }}
            </x-nav-link>

        </div>
    </x-slot>
    <!-- Navigation Links -->


    <div class="py-12">

       <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
           <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="flex h-max" >

                </div>
            </div>

        </div>
    </div>
    <script>

        document.getElementById('captureButton').addEventListener('click', function() {
            let filename = document.getElementById('filename').value;
            html2canvas(document.getElementById('leafletCanvas'), {
                scale: 2 // Podwaja rozmiar obrazu
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


            /*html2canvas(document.getElementById('leafletCanvas'), {
                scale: 2 // Podwaja rozmiar obrazu
            }).then(canvas => {
                // Tutaj możesz wykonać działania na obrazie
                document.body.appendChild(canvas);
            });*/

        document.getElementById('generator').addEventListener('click', function() {
            console.log("generator");
            generator();

        })
    function generator(){
        let data_generator = $('#data_generator').serialize();
        $.ajax({
            type: 'POST',
            url: '/generator',
            data: data_generator,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function(response) {
                console.log('sukces');
                $('#leafletGenerated').html(response);
                },
            error: function(xhr, status, error) {
                console.error(error + ' obsługa błędów'); // Obsługa błędów
            }
        });
    }
    </script>
</x-app-layout>
