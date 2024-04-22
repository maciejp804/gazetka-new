
<section class="h-t-products1 section-t-padding section-b-padding pop">
    <div class="container">
        <div class="row">
            <div class="col" id="map">
            </div>
        </div>
    </div>
</section>

<script>
    // Inicjalizacja mapy Leaflet
    var map = L.map('map').setView([{{$place->lat}}, {{$place->lng}}], 14);
    map.scrollWheelZoom.disable(); // Wyłączenie zoomowania za pomocą kółka myszki



    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 20,
    }).addTo(map);
    // Stworzenie grupy dla markerów
    var markerCluster = L.markerClusterGroup();

    var customCenterIcon = L.divIcon({
        className: 'center-marker', // Ustawienie klasy CSS dla markerów
        html: '<i class="material-icons my_location">my_location</i>',
        //html: '<img src="{{asset('assets/image/store/center-icon.png')}}"/>', // Definicja HTML dla markera
        iconSize: [40, 40], // Rozmiar markera
        iconAnchor: [20, 0] // Punkt zakotwiczenia markera
    });


    var centerMarker = L.marker([{{$place->lat}}, {{$place->lng}}], {icon: customCenterIcon}).addTo(map);
    // Dodanie markerów do grupy
    @foreach ($markers as $marker)

    var customIcon = L.divIcon({
        className: 'price-tag', // Ustawienie klasy CSS dla markerów
        html: '<img src="' +
            '@if(isset($marker->stores->name) && !empty($marker->stores->name))' +
            '{{asset('assets/image/store/'.$marker->stores->subdomain)}}-marker.png' +
            '@endif" /><div class="pulse"></div>', // Definicja HTML dla markera
        iconSize: [40, 40], // Rozmiar markera
        iconAnchor: [20, 40] // Punkt zakotwiczenia markera
    });

    // Tworzenie markera z niestandardową ikoną

    var marker = L.marker([{{$marker->lat}}, {{$marker->lng}}], { icon: customIcon });


    var contentString = '<div id="infobox">' +
        '@if(isset($marker->stores->name) && !empty($marker->stores->name))' +
        '<div class="infobox-image"><a href="https://{{$marker->stores->subdomain}}.gazetkapromocyjna.com.pl/{{$place->slug}}/">' +
        '<img src="{{asset('assets/image/store/'.$marker->stores->subdomain)}}-69.png"/></a></div>' +
        '<a href="https://{{$marker->stores->subdomain}}.'+
        'gazetkapromocyjna.com.pl/godziny-otwarcia/{{$place->slug}}-{{$marker->slug}},{{$marker->id}}/">{{$marker->address}} <br/><br/>{{$marker->places->name ?? ''}}<br/><br/>{{round($marker->distance, 2)}} km od Ciebie <br/></a>' +
        '@endif' +
        '<div class="open-houers"><span>Godziny otwarcia:</span><br/>' +
        '<span class="' +
        '@if(date("D") == "Mon")' +
        'active-day'+
        '@endif'
        +'">poniedziałek: <span class="open-houers-days">{{$marker->weekdays}}</span></span><br/>' +
        '<span class="' +
        '@if(date("D") == "Tue")' +
        'active-day'+
        '@endif'
        +'">wtorek: <span class="open-houers-days">{{$marker->weekdays}}</span></span><br/>' +
        '<span class="' +
        '@if(date("D") == "Wed")' +
        'active-day'+
        '@endif'
        +'">środa: <span class="open-houers-days">{{$marker->weekdays}}</span></span><br/>' +
        '<span class="' +
        '@if(date("D") == "Thu")' +
        'active-day'+
        '@endif'
        +'">czwartek: <span class="open-houers-days">{{$marker->weekdays}}</span></span><br/>' +
        '<span class="' +
        '@if(date("D") == "Fri")' +
        'active-day'+
        '@endif'
        +'">piątek: <span class="open-houers-days">{{$marker->weekdays}}</span></span><br/>' +
        '<span class="' +
        '@if(date("D") == "Sat")' +
        'active-day'+
        '@endif'
        +'">sobota: <span class="open-houers-days">{{$marker->saturday}}</span></span><br/>' +
        '<span class="' +
        '@if(date("D") == "Sun")' +
        'active-day'+
        '@endif'
        +'">niedziela handlowa: <span class="open-houers-days">{{$marker->sunday}}</span></span></div>';


    // Stworzenie popupu dla markera
    marker.bindPopup(contentString);

    // Dodanie markera do grupy
    markerCluster.addLayer(marker);
    @endforeach

    // Dodanie grupy markerów do mapy
    map.addLayer(markerCluster);
</script>


