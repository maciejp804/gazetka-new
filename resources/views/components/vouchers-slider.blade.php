<section class="h-t-products1 section-t-padding section-b-padding">
    <div class="container">
        <div class="row">
            <div class="section-title3">
                <h2 class="desktop-only h2_67ex7q"><span>Kupony rabatowe</span></h2>
                <h2 class="mob-only">Kupony rabatowe
                    <div class="border_color"></div>
                </h2>
                <h2 class="desktop-only h2_f51bpo"><a class="more-link" href="/coupons/">Zobacz więcej</a></h2></div>
            <div class="col">
                <div class="owl-carousel trending-products123 owl-theme different">
                    @foreach($vouchers as $voucher)
                        <div class="items">
                            <div class="tab-product div_1w7dsh">
                                <div class="tred-pro">
                                    <div class="tr-pro-img"><a href="{{$voucher->landing_url}}" rel="nofollow"><img
                                                src="{{$voucher->offer_image}}"
                                                alt="pro-img1"
                                                class="img-fluid offer_img img_rbqjm8"></a></div>
                                </div>
                                <div class="tab-caption div_aou20y"><img src="{{ $voucher->voucherStore->logo_path}}"
                                                                         class="img_4wgk2k">
                                    <h3 class="h3_7t2a3w"><a href="{{$voucher->landing_url}}" rel="nofollow">{{$voucher->title}}</a></h3><span
                                        class="old-price span_7v5ebd">{{monthReplace($voucher->start_offer_date, 'd-m')}} - {{monthReplace($voucher->end_offer_date, 'd-m-Y')}} </span></div>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
            <div class="all-blog text-center mt-4 mob-only"><a href="/coupons/" class="btn btn-style1 a_a64n9j">Zobacz więcej</a>
            </div>
        </div>
    </div>
</section>
