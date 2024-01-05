<section class="h-t-products1">
    <div class="container">
        <div class="tabcontent" id="paris">
            <div class="home-pro-tab">
                <div class="d-flex filter-promotions" style="gap: 20px; flex-wrap: wrap;">
                    @foreach($leaflets as $leaflet)
                        <div class="happy_hover item">
                            <div class="tred-pro image_container">
                                <div class="tr-pro-img">
                                    <a href="#">
                                        <img class="img-fluid image img-height img_vistfv" src="{{asset('assets/media/promotions/zabka_UjzqnS0.png')}}" alt="pro-img1">
                                    </a>
                                </div>
                                <div class="pro-icn desktop-only">

                                    <a href="javascript:void(0)"
                                       class="w-c-q-icn "
                                       id="Promotions-420-2"
                                       onclick="like('Promotions', 420, 2)">
                                        <i class="fa fa-solid fa-heart heart-icon"></i>
                                    </a>
                                    <a href="/promotion/{{ $leaflet->slug }}/" class="w-c-q-icn display-block btn-cart a_h2wh8b">Zobacz
                                        więcej</a>
                                </div>
                            </div>
                            <div class="caption text-center">
                                <img class="img-fluid img_ls4hdo" src="{{asset('assets/image/shop/'.$leaflet->store->logo)}}" alt="pro-img1">
                                <h3 class="text-black"><a href="/promotion/{{ $leaflet->slug }}/"
                                                          class="a_ctv6kx">{{monthReplace($leaflet->start_offer_date, 'd-m')}} - {{monthReplace($leaflet->end_offer_date)}}</a></h3>
                                <div class="pro-price div_9c9yn8">
                                    <span class="old-price">GAZETKA {{ strtoupper($leaflet->store->name) }} </span>
                                </div>
                                <div class="icon-bgs">
                                    <a href="https://pinterest.com">
                                        <i class="fa fa-pinterest-p icon-bg"></i>
                                    </a>
                                    <a href="https://facebook.com">
                                        <i class="fa fa-facebook-f icon-bg"></i>
                                    </a>
                                    <a href="https://twitter.com">
                                        <i class="fa fa-twitter icon-bg"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
