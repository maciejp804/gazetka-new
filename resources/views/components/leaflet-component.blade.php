<div class="tred-pro image_container">
    <div class="tr-pro-img">
        <a href="#">
            <img class="img-fluid image img-height img_u9ttic"
                 src="{{asset('assets/media/promotions/zabka_UjzqnS0.png')}}"
                 alt="pro-img1">
        </a>
    </div>
    <div class="pro-icn desktop-only">

        <a href="javascript:void(0)" class="w-c-q-icn "
           id="Promotions-420-1"
           onclick="like('Promotions', 420, 1)"><i
                class="fa fa-solid fa-heart heart-icon"></i></a>
        <a href="/promotion/{{ $item->slug }}/"
           class="w-c-q-icn display-block btn-cart a_uk7a3s">Zobacz więcej</a>
    </div>
</div>
<div class="caption text-center">
    <img class="img-fluid img_s6n7ag"
         src="{{asset($item->store->leaflet_logo)}}"
         alt="pro-img1">
    <h3 class="text-black"><a href="/promotion/{{ $item->slug }}/"
                              class="a_ctv6kx">{{monthReplace($item->start_offer_date, 'd-m')}} - {{monthReplace($item->end_offer_date)}}</a></h3>
    <div class="pro-price div_7qkpqa"><span class="old-price">GAZETKA {{ strtoupper($item->store->name) }} </span></div>
</div>
<div class="icon-bgs">
    <a href="javascript:pt_share(`http://165.232.144.14//promotion/{{ $item->slug }}/`);"><i
            class="fa fa-pinterest-p icon-bg"></i></a>
    <a href="javascript:fb_share(`http://165.232.144.14//promotion/{{ $item->slug }}/`);"><i
            class="fa fa-facebook-f icon-bg"></i></a>
    <a href="javascript:tw_share('sample promotion19', `http://165.232.144.14//promotion/{{ $item->slug }}/`)"><i
            class="fa fa-twitter icon-bg"></i></a>
</div>
