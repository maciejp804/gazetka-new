<section class="h-t-products1 section-t-padding section-b-padding commercial-networks">
    <div class="container">
        <div class="row">
            <div class="section-title3"><h2 class="desktop-only h2_p3aggy"><span>{{$title}}</span></h2>
                <h2 class="mob-only">{{$title}}<div class="border_color"></div>
                </h2>
                <h2 class="desktop-only h2_cxjma3"><a class="more-link" href="/sieci-handlowe-{{$slug}}/{{$placeSlug}}">Zobacz więcej</a></h2></div>
            <div class="col">
                <div class="owl-carousel trending-products owl-theme">
                    @foreach($items as $item)
                    <div class="items set_eff_hovers">
                        <div class="tred-pro div_sw5kup">
                            <div class="tr-pro-img member">
                                <a href="">
                                    <img class="img-fluid img_zv1lu7" src="{{asset($item->logo)}}" alt="pro-img1">
                                    <img class="img-fluid additional-image img_yydtz3"
                                         src="{{asset('assets/image/pro/solid-color-image.png')}}">
                                </a>
                            </div>
                            <div class="pro-icn">
                                <a href="/store/{{$item->id}}/" class="w-c-q-icn"><i
                                        class="fa fa-search i_j3909u"></i></a>
                                <a href="/store/{{$item->id}}/" class="w-c-q-icn display-block a_hpcp1e">Gazetka
                                    promocyjna<br>{{$item->name}}</a>
                            </div>
                            <!--{% check_like item request as liked %}-->
                            <a href="javascript:void(0);" class="w-c-q-icn "
                               id="OnlineStore-{{$item->id}}-1" onclick="like('OnlineStore', {{$item->id}}, 1)">
                                <i class="fa fa-solid fa-heart heart-icon"></i>
                            </a>
                        </div>
                        <div class="caption text-center">
                            <h3 class="text-black">
                                <a href="/store/{{$item->id}}/" class="bold_hover_eff a_t24ult">{{$item->name}}</a>
                            </h3>
                            <div class="pro-price div_hyl23b"><span class="old-price">{{$item->offers}} ofert</span></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="all-blog text-center mt-4 mob-only"><a href="/sieci-handlowe-{{$slug}}/" class="btn btn-style1 a_zy7dnt">Zobacz więcej</a>
            </div>
        </div>
    </div>
</section>
