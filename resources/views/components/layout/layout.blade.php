<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex">
    <meta name="description" content="{{$meta_description ?? 'Przykładowy opis'}}">
    <meta name="author" content="Maciej Puchalski">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$title ?? 'Uwaga przykładowy tytuł'}}</title>


    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;700&display=swap" rel="stylesheet"/>

    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/simple-line-icons.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/ionicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/swiper.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style_va.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style_va1.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/responsive.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/inline.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/dark.css')}}">
    <link rel="stylesheet" href="{{asset('assets/libs/raty/jquery.raty.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/maps_2m.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/mapa.css')}}">


    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Leaflet.markercluster CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />

    <!-- Leaflet.markercluster JavaScript -->
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>



</head>
<body>
<div id="__next">
    <div class="App">

        {{$slot}}

    </div>
</div>





<script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('assets/js/swiper.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('assets/libs/raty/jquery.raty.js')}}"></script>
<script type="text/javascript" src=
    "https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js">
</script>
<script src="{{asset('assets/js/custom.js')}}"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>

    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + value + expires + "; path=/; secure; SameSite=Lax";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function toggleLang() {
        const lang_box = document.getElementById('lang-box');
        lang_box.classList.toggle('show');
    }

    function select_lang(lang) {
        toggleLang();
        if (lang == 'cn') {
            lang = 'zh-CN'
        }
        setCookie('googtrans', '/pl/' + lang);
        location.reload();
    }

    function toggleTheme() {
        const body = document.body;
        body.classList.toggle('dark');
    }

    function select_location() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: "/location",
            data: { parametr: '{{$route}}' }, // Przekazanie nazwy trasy
            success: function (data) {
                window.location.href = data.url;
            }
        });
    }


</script>

<script>
    var geo = getCookie('geolocation');
    if (!getCookie('local')) {
        if (navigator.geolocation) {
            navigator.permissions.query({ name: 'geolocation' }).then(permissionStatus => {
                if (permissionStatus.state === 'granted') {
                    // Użytkownik zezwolił na lokalizację
                    navigator.geolocation.getCurrentPosition(currentPosition, error_callback);
                } else if (permissionStatus.state === 'prompt') {
                    // Użytkownik nie podjął jeszcze decyzji, możesz wyświetlić prośbę o zezwolenie na lokalizację
                    // Możesz również wywołać funkcję getCurrentPosition() bez warunku, aby pokazać standardowe okno dialogowe przeglądarki
                    navigator.geolocation.getCurrentPosition(currentPosition, error_callback);
                } else {
                    error_callback(error);
                    // Użytkownik odmówił dostępu do lokalizacji, możesz obsłużyć tę sytuację odpowiednio
                }
            }).catch(console.error);
        } else {
            console.log("Twoja przeglądarka nie obsługuje Geolokalizacji HTML5.");
        }
    } else {
        console.log("Ciasteczko z danymi geolokalizacji już istnieje.");
    }

    function currentPosition(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var myObj = JSON.parse(this.responseText);
                var state = myObj.address.state.split(' ');
                var jsonData = {"place" : myObj.address.city, "district" : state[1], 'lat': myObj.lat, 'lng' : myObj.lon};
                var jsonString = JSON.stringify(jsonData);
                setCookie('local',jsonString, 30);
                setCookie('geolocation','on', 30);
            }
        };
        xmlhttp.open("GET", "https://eu1.locationiq.com/v1/reverse.php?key=1a3ae45052e8cd&lat=" + latitude + "&lon=" + longitude + "&format=json&normalizecity=1", true);
        xmlhttp.send();
    }

    function error_callback(error) {
        setCookie('geolocation','off', 1);
    }

</script>

<script>

    $('#id_category').on('change', () => {
        console.log("filter change");
        filter();
    })


    $('#id_sort').on('change', () => {
        console.log("sort change");
        filter();
    })


    $('#id_search').on('input', () => {
        console.log("search change");
        filter();
    })


    $(document).on("keydown", "form", function (event) {
        return event.key !== "Enter";
    });

    function filter() {
        let form_data = $('#id_filter').serialize();
        $.ajax({
            type: 'post',
            url: "/filter",
            data: form_data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function(){
                $('#promotions-box').html('....Please wait');
            },
            success: function (data) {
                $('#promotions-box').html(data);
            }
        });
    }

</script>






<script>
    window.fbAsyncInit = function () {
        FB.init({
            appId: '489155746447522',
            autoLogAppEvents: true,
            xfbml: true,
            version: 'v17.0'
        });
    };
</script>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>


<script type="text/javascript">

    $(document).ready(() => {
        let message = $.cookie('message');
        if (message !== '' && message !== undefined) {
            Swal.fire(
                'Success!',
                message,
                'success'
            );
            $.cookie('message', '', {path: '/'});
        }

        $('.btn-more').click((event) => {
            $(event.currentTarget).parents('.product-box').find('.hidden').removeClass('hidden');
            $(event.currentTarget).hide();
        })
    });

    function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'pl'}, 'google_translate_element');
    }

    function toggleLikedClass(name, id, type) {
        const element = document.getElementById(`${name}-${id}-${type}`);
        if (element) {
            element.classList.toggle('liked');
        }
    }

    function like(name, id, type1) {
        const login = "False";
        if (login == 'False') {
            document.location.href = '/accounts/login/'
        }

        for (let type = 1; type <= 2; type++) {
            toggleLikedClass(name, id, type);
        }

        $.ajax({
            headers: {
                "X-CSRFToken": "5FBEJRr0dG5wLJ6zZiDHJLemaLX5kKUgXzEOhnzR0NoKJQdztYx6mzhKHhAFN7Fn"
            },
            type: 'post',
            url: "/obj_like/",
            data: {
                name: name,
                id: id,
            },
            success: function (data) {
            }
        });
    }

    function fb_share(link) {
        FB.ui({
            method: 'share',
            href: link,
            title: 'adsfadsfa'
        }, function (response) {
        });
    }

    function pt_share(link) {
        window.open('http://pinterest.com/pin/create/link/?url=' + link, '_blank');
    }

    function tw_share(name, link) {
        window.open(`http://twitter.com/share?text=${name}&url=${link}`, '_blank');
    }


    $('#subscribe-form').on('submit', (e) => {
        e.preventDefault();
        const formData = $('#subscribe-form').serializeArray();
        $.ajax({
            headers: {
                "X-CSRFToken": "5FBEJRr0dG5wLJ6zZiDHJLemaLX5kKUgXzEOhnzR0NoKJQdztYx6mzhKHhAFN7Fn"
            },
            type: 'post',
            url: "/subscribe/",
            data: formData,
            success: function (data) {
                if (data.message == 'ok') {
                    Swal.fire(
                        'Success!',
                        'You subscribed successfully!',
                        'success'
                    )
                }
            }
        });
    })

    function updateScore(name, id, score, option) {
        $.ajax({
            headers: {
                "X-CSRFToken": "5FBEJRr0dG5wLJ6zZiDHJLemaLX5kKUgXzEOhnzR0NoKJQdztYx6mzhKHhAFN7Fn"
            },
            type: 'post',
            url: "/vote/",
            data: {
                name: name,
                id: id,
                score: score
            },
            success: function (data) {
                if (data.message === 'ok') {
                    const rate_votes = data.rate + ' / ' + data.votes + '(głosów)';
                    $('#rate-votes').text(rate_votes);
                    option.score = data.rate;
                    option.readOnly = true;
                    $('#rate-score').data('raty').score(option.score);
                    $('#rate-score').data('raty').readOnly(true);
                }
            }
        });
    }



</script>


<script type="text/javascript"
        src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>

</html>

