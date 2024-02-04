<section class="h-t-products1 section-t-padding section-b-padding height-fixed zzz">
    <div class="container">
        <div class="row">
            <div class="section-title3">
                <h2 class="desktop-only h2_fwcjux">
                    <span>Najczęściej szukane produkty</span>
                </h2>
                <h2 class="mob-only">Najczęściej szukane produkty
                    <div class="border_color"></div>
                </h2>
            </div>
            <div class="col">
                <div class="owl-carousel trending-products rated_products  owl-theme  owl-loaded owl-drag">

                   @foreach($products as $product)
                        <div class="items">
                            <div class="tred-pro div_g4g8ic">
                                <div class="tr-pro-img">
                                    <a href="/product/12/">
                                        <img class="img-fluid obj-fit" src="{{asset('assets/media/products/'.$product->image_path)}}" alt="pro-img1">
                                    </a>
                                </div>
                                <a class="d-block pro-icn" href="/w-gazetce/{{$product->slug}},{{$product->id}}/">

                                    <a href="javascript:void(0)" class="w-c-q-icn " id="Product-12-1"
                                       onclick="like('Product', 12, 1)">
                                        <i class="fa fa-solid fa-heart heart-icon"></i>
                                    </a>
                                </a>
                            </div>
                            <div class="caption text-center">
                                <h3 class="text-black">
                                    <a href="/w-gazetce/{{$product->slug}},{{$product->id}}/" class="a_61txho">{{$product->name}}</a>
                                </h3>
                                <div class="pro-price">
                                    <span class="old-price">od 11.22 zł</span>
                                </div>
                            </div>
                        </div>
                   @endforeach
                    </div>
            </div>
            <div class="all-blog text-center mt-4 mob-only">
                <a href="#" class="btn btn-style1 a_ekllws">Zobacz więcej</a>
            </div>
        </div>
    </div>
</section>
