<section class="h-t-products1 section-t-padding section-b-padding bsb">
    <div class="container">
        <div class="row">
            <div class="section-title3"><h2 class="desktop-only h2_bo12hr"><span>Sklepy online</span></h2>
                <h2 class="mob-only">Sklepy online
                    <div class="border_color"></div>
                </h2>
                <h2 class="desktop-only h2_r9fzzv"><a class="more-link" href="/sieci-handlowe-online,8/">Zobacz więcej</a></h2>
            </div>
            <div class="col">
                <div class="owl-carousel trending-products rated_products owl-theme">

                    @foreach($online as $online_store)
                        <div class="items">
                            <div class="tred-pro div_21j57g">
                                <div class="tr-pro-img member">
                                    <a href="#">
                                        <img class="img-fluid img_rkb1s"
                                             src="{{asset($online_store->logo)}}"
                                             alt="pro-img1"></a>
                                </div>
                                <a href="/chains/" class="pro-icn d-block">

                                    <a href="javascript:void(0)" class="w-c-q-icn "
                                       id="OnlineStore-2-1"
                                       onclick="like('OnlineStore', 2, 1)">
                                        <i class="fa fa-solid fa-heart heart-icon"></i>
                                    </a>
                                </a>
                            </div>
                            <div class="caption text-center"><h3 class="text-black"><a href="/chains/"
                                                                                       class="a_zff5b1">{{ $online_store->name }}</a>
                                </h3>
                                <div class="pro-price div_fupn4y"><span class="old-price">ofert {{ $online_store->offers }}</span></div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
            <div class="all-blog text-center mt-4 mob-only"><a href="/sieci-handlowe-online,8/" class="btn btn-style1 a_lp84hd">Zobacz więcej</a>
            </div>
        </div>
    </div>
</section>
