<section class="h-t-products1 bigort section-t-padding section-b-padding bigRow">
    <div class="container">
        <div class="shops-flexbox">
            @foreach($items as $store)
            <div class="items set_eff_hovers">
                <div class="tred-pro">
                    <div class="tr-pro-img member">
                        <a>
                            <img class="img-fluid img_zv1lu7" src="{{asset($store->logo)}}" alt="pro-img1">
                            <img class="img-fluid additional-image img_yydtz3" src="{{asset('assets/image/pro/solid-color-image.png')}}">
                        </a>
                    </div>
                    <div class="pro-icn">
                        <a href="/store/{{$store->id}}/" class="w-c-q-icn">
                            <i class="fa fa-search i_j3909u"></i>
                        </a>
                        <a href="/store/{{$store->id}}/" class="w-c-q-icn display-block a_hpcp1e">Gazetka promocyjna <br>{{$store->name}} </a>
                    </div>
                    <a href="javascript:void(0);" class="w-c-q-icn heart"
                       id="OnlineStore-93-1" onclick="like('OnlineStore', 93, 1)">
                        <i class="fa fa-solid fa-heart heart-icon"></i>
                    </a>
                    </div>
                <div class="caption text-center">
                    <h3 class="text-black">
                        <a href="/store/{{$store->id}}/" class="bold_hover_eff a_t24ult">{{$store->name}}</a>
                    </h3>
                    <div class="pro-price div_hyl23b">
                        <span class="old-price">{{$store->offers}} ofert</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
