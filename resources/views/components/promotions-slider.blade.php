<section class="h-t-products1 first-section">
    <div class="container cont_set">
        <div class="row">
            <div class="section-title3 desktop-only">
                <h2 class="h2_7267fp"><span>Polecane gazetki promocyjne
                        @if($place !== null)
                            w {{$place->name_locative}}
                        @endif
                        </span></h2>
                   <a class="more-link desktop-only h2_io1e0s" href="/gazetki-promocyjne-wszystkie,0/">Zobacz więcej</a>
            </div>
            <h2 class="mob-only">Polecane gazetki promocyjne<div class="border_color"></div></h2>
            <div class="col">
                <div class="owl-carousel trending-products owl-theme first-product">
                    @foreach($items as $item)

                        <div class="items w-set">
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
                                    <a href="/promotion/{{ $item->id }}/"
                                       class="w-c-q-icn display-block btn-cart a_uk7a3s">Zobacz więcej</a>
                                </div>
                            </div>

                            <div class="caption text-center">
                                <img class="img-fluid img_s6n7ag"
                                     src="{{asset('assets/image/shop/'.$item->store->logo)}}"
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
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<div class="all-blog text-center mt-4 mob-only"><a href="/gazetki-promocyjne-wszystkie,0/" class="btn btn-style1 a_o8xtjv">Zobacz więcej</a></div>
