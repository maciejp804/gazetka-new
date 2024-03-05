var short_name = 'auchan';
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var infoWindow = new google.maps.InfoWindow;
var markers = [];
var markerCluster;
var map;

var marker;
var dane;
var start = 'https://gazetkapromocyjna.com.pl/images/material/my_location.png';
function daneDownload(city) {
    function downloadUrl(url, callback) {
        var request = new XMLHttpRequest();
        request.onreadystatechange = function() {
            if (request.readyState == 4 && request.status == 200) {
                callback(request);
            }
        };
        request.open('GET', url, true);
        request.send();
    }

    var url = "parse_xml_1m.php?siec=auchan&miasto=poznan"; // Ustaw odpowiedni URL do pobrania danych
    downloadUrl(url, function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        var markerArray = [];
        for (var i = 0; i < markers.length; i++) {
            var name = markers[i].getAttribute("name");
            var address = markers[i].getAttribute("address");
            var address_link = markers[i].getAttribute("address_link");
            var id = markers[i].getAttribute("id");
            var city = markers[i].getAttribute("city");
            var city_link = markers[i].getAttribute("city_link");
            var type = markers[i].getAttribute("type");
            var cover = markers[i].getAttribute("cover");
            var description = markers[i].getAttribute("description");
            var monOpen = markers[i].getAttribute("monOpen");
            var monClose = markers[i].getAttribute("monClose");
            var tueOpen = markers[i].getAttribute("tueOpen");
            var tueClose = markers[i].getAttribute("tueClose");
            var wedOpen = markers[i].getAttribute("wedOpen");
            var wedClose = markers[i].getAttribute("wedClose");
            var thuOpen = markers[i].getAttribute("thuOpen");
            var thuClose = markers[i].getAttribute("thuClose");
            var friOpen = markers[i].getAttribute("friOpen");
            var friClose = markers[i].getAttribute("friClose");
            var satOpen = markers[i].getAttribute("satOpen");
            var satClose = markers[i].getAttribute("satClose");
            var sunOpen = markers[i].getAttribute("sunOpen");
            var sunClose = markers[i].getAttribute("sunClose");
            var day = markers[i].getAttribute("day");
            var hour = markers[i].getAttribute("hour");
            var point = new google.maps.LatLng(parseFloat(markers[i].getAttribute("lat")), parseFloat(markers[i].getAttribute("lng")));

            var open, close;
            switch (day) {
                case 'Mon':
                    open = monOpen;
                    close = monClose;
                    break;
                case 'Tue':
                    open = tueOpen;
                    close = tueClose;
                    break;
                case 'Wed':
                    open = wedOpen;
                    close = wedClose;
                    break;
                case 'Thu':
                    open = thuOpen;
                    close = thuClose;
                    break;
                case 'Fri':
                    open = friOpen;
                    close = friClose;
                    break;
                case 'Sat':
                    open = satOpen;
                    close = satClose;
                    break;
                case 'Sun':
                    open = sunOpen;
                    close = sunClose;
                    break;
            }

            var isOpen = sunOpen !== '00:00'; // Sprawdzanie czy sklep jest otwarty w niedzielę

            // Tworzenie HTML do infowindow
            var html = "<div id='infobox'><a href='https://gazetkapromocyjna.com.pl/" + city_link + "/'><img src='https://gazetkapromocyjna.com.pl/images/logotyp-markers/" + short_name + ".png'/></a><a href='https://gazetkapromocyjna.com.pl/godziny-otwarcia/" + city_link + "-" + address_link + "," + id + "/'>" + name + "<br>" + address + ", " + city + "<br/></a><div class='open-houers'><span>Godziny otwarcia:</span><br/><span class='mon-" + day + "'>poniedziałek: <span class='open-houers-days'>" + monOpen + " - " + monClose + "</span></span><br/><span class='tue-" + day + "'>wtorek: <span class='open-houers-days'>" + tueOpen + " - " + tueClose + "</span></span><br/><span class='wed-" + day + "'>środa: <span class='open-houers-days'>" + wedOpen + " - " + wedClose + "</span></span><br/><span class='thu-" + day + "'>czwartek: <span class='open-houers-days'>" + thuOpen + " - " + thuClose + "</span></span><br/><span class='fri-" + day + "'>piątek: <span class='open-houers-days'>" + friOpen + " - " + friClose + "</span></span><br/><span class='sat-" + day + "'>sobota: <span class='open-houers-days'>" + satOpen + " - " + satClose + "</span></span><br/><span class='sun-" + day + "'>niedziela: <span class='open-houers-days'>" + (isOpen ? sunOpen + " - " + sunClose : "NIECZYNNE") + "</span></span></div>";

            var image = {
                url: 'https://gazetkapromocyjna.com.pl/images/markers/auchan.png',
                size: new google.maps.Size(50, 50),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(25, 50)
            };

            var shape = {
                coords: [1, 1, 50, 50],
                type: 'rect'
            };

            var clusterStyles =  [{
                url: "https://gazetkapromocyjna.com.pl/images/markers/image4255.png",
                height: 70,
                width: 70,
                textColor: 'white',
                textSize: 14,
            }];
            var mcOptions = {
                gridSize: 50,
                averageCenter: true,
                maxZoom: 14,
                styles: clusterStyles
            };

            var marker = new google.maps.Marker({
                map: map,
                position: point,
                icon: image,
                shape: shape
            });

            markerArray.push(marker);
            bindInfoWindow(marker, map, infoWindow, html);

            // Aktualizacja bocznego paska (sidebar)
            var li = document.createElement('li');
            li.className = 'list-sidebar ' + type + ' info info-' + i;
            li.innerHTML = '<a href="javascript:myclick(' + i + ')"  >' +
                name + ', ' + city + '<br/> ' + address + '</a><br/><i class="material-icons ' +
                description + '-no-bold">&#xE8B5;</i><span class="hours"> ' +
                open + ' - ' + close + ' </span>';
            pasekBoczny.appendChild(li);
        }
        // Dodanie markerów do markerClusterer
        markerCluster = new MarkerClusterer(map, markerArray, mcOptions);
    });
}



function initMap() {

// Geolocation

    if ((navigator.geolocation)) {
        navigator.geolocation.getCurrentPosition(function(position) {
            geocoder = new google.maps.Geocoder();
            directionsDisplay = new google.maps.DirectionsRenderer();
            var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
           // var pos = new google.maps.LatLng(lat,lng);
                var mapOptions = {
                        zoom: 13,
                        maxZoom: 18,
                        scrollwheel:false,
                        center: pos
                                };
                    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
                    directionsDisplay.setMap(map);

                    function geocodeLatLng(geocoder, map) {
                            geocoder.geocode({'location': pos}, function(results, status) {
                            if (status === google.maps.GeocoderStatus.OK) {
                                		//Check result 0
				var result = results[0];

                //look for locality tag and administrative_area_level_1
				var city = "";
				var state = "";
                var street = "";
                dane = result.formatted_address;
                var address = dane.split(",");
				for(var i=0, len=result.address_components.length; i<len; i++) {
					var ac = result.address_components[i];
                    if(ac.types.indexOf("locality") >= 0) city = ac.long_name;
					if(ac.types.indexOf("administrative_area_level_1") >= 0) state = ac.long_name;
				}
				//only report if we got Good Stuff
				if(city != '' && state != '') {
					$("#search").attr('placeholder', city+", "+state);
                    $(".location-icon img").attr('src', '');
                var data = new Date();
                var days = 30;
                data.setTime(data.getTime() + (days * 24*60*60*1000));
                var expires = "; expires="+data.toGMTString();
                document.cookie = "city=" + city + expires+"; domain=.gazetkapromocyjna.com.pl; path=/";
                document.cookie = "province=" +state + expires+"; domain=.gazetkapromocyjna.com.pl; path=/";
                document.cookie = "street=" +address[0] + expires+"; domain=.gazetkapromocyjna.com.pl; path=/";
				}



                            if (result) {


                            var marker_start = new google.maps.Marker({
                                        position: pos,
                                        map: map,
                                        animation: google.maps.Animation.DROP,
                                        icon: start,
                                        });

                            //document.getElementById('search').placeholder  = city+", "+state;

    //START


daneDownload(city);

//KONIEC


                        } else {
                            window.alert('Domyślna lokalizacja');
                                }
                        } else {
                            window.alert('Geocoder failed due to: ' + status);
                                }
                                });
                                }


            geocodeLatLng(geocoder, map);
            map.setCenter(pos);
            }, function() {
            handleNoGeolocation(true);
                        });
            } else {
            // Browser doesn't support Geolocation
            handleNoGeolocation(false);
            }
            // end geoloction
}

function bindInfoWindow(marker, map, infoWindow, html) {
    google.maps.event.addListener(marker, 'click', function() {
        map.panTo(marker.getPosition());
        map.setZoom(16);
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
    });
    markers.push(marker);
}

function myclick(i) {
    google.maps.event.trigger(markers[i], "click");
}

function doNothing() {}

function calcRoute() {
        var rendererOptions = {
      map: map,
      suppressMarkers : true
    }
    directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);



    var start = document.getElementById('search').value;
    var end = document.getElementById('end').name;
    var distanceInput = document.getElementById("distance");
    var request = {
        origin: start,
        destination: end,
        travelMode: google.maps.TravelMode.DRIVING
    };
    directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK) {

            distanceInput.value = "Odległość: " + response.routes[0]
                .legs[0].distance.text;
        }
    });
}

function handleNoGeolocation(errorFlag) {
    if (errorFlag == true) {
    city = "Warszawa";
    state = 'mazowieckie';
    directionsDisplay = new google.maps.DirectionsRenderer();
    var pos = new google.maps.LatLng(52.22983447, 21.01173326);
                var mapOptions = {
                        zoom: 14,
                        maxZoom: 18,
                        scrollwheel:false,
                        center: pos
                                };

                    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
                    directionsDisplay.setMap(map);
                    document.getElementById('search').placeholder  = city+", "+state;

          var marker = new google.maps.Marker({
                                        position: pos,
                                        map: map,
                                        animation: google.maps.Animation.DROP,
                                        icon: start,
                                        });
    //START

daneDownload(city);

//KONIEC
    } else {

    var pos = new google.maps.LatLng(lat, lng);
     directionsDisplay = new google.maps.DirectionsRenderer();
                var mapOptions = {
                        zoom: 13,
                        maxZoom: 18,
                        scrollwheel:false,
                        center: pos
                                };
                    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
                    directionsDisplay.setMap(map);
                    document.getElementById('search').placeholder  = city+", "+province;


                    var marker = new google.maps.Marker({
                                        position: pos,
                                        map: map,
                                        animation: google.maps.Animation.DROP,
                                        icon: start,
                                        });
    //START

daneDownload(city);

//KONIEC
    }
    map.setCenter(pos);
}
filterMarkers = function(category) {
    for (i = 0; i < markers.length; i++) {
        marker = markers[i];
        if (marker.category == category || category.length === 0) {
            marker.setVisible(true);
            markerCluster.addMarker(marker);
        }
        else {
            marker.setVisible(false);
            markerCluster.removeMarker(marker);
        }
    }
    markerCluster.redraw();
};

function showVisibleMarkers() {
    var bounds = map.getBounds(),
        count = 0;
    for (var i = 0; i < markers.length; i++) {
        var marker = markers[i],
            infoPanel = $('.info-' + (i));
        if (bounds.contains(marker.getPosition()) === true) {
            infoPanel.show();
            count++;
        } else {
            infoPanel.hide();
        }
    }
    $('#infos h2 span').html(count);
}
google.maps.event.addDomListener(window, 'load', initMap);
