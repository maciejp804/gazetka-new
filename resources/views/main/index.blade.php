<x-layout.layout>
    <section class="slider mt-4 desktop-only set_center">
        <div class="container cont_set">
            <h1 class="margins_set_title h1_g29nbe">Wszystkie gazetki promocyjne w <b>jednym miejscu</b></h1>
        </div>
    </section>
    <div class="container mob-only">
        <div class="header-main-area div_eu6gya">
            <div class="header-main">
                <div class="header-element search-wrap mob-only div_6tg40f">
                    <input type="text" name="search" placeholder="Szukaj produktów w gazetkach" class="input_erjdsx">
                    <a href="#" class="search-btn"><i
                            class="fa fa-search"></i></a></div>
            </div>
        </div>
    </div>

    <section class="slider mt-4 mob-only box-blue">
        <h1 class="box-fonts h1_uzsvpc">Wszystkie gazetki promocyjne w <b>jednym miejscu</b></h1>
    </section>

    <div class="col-md-3 mob-only box-gray"><span class="align_centers">REKLAMA</span></div>

    <section>
        <div class="right_box" style="position: absolute; left: 0"><span>REKLAMA</span></div>
        <div class="right_box" style="position: absolute; right: 0;"><span>REKLAMA</span></div>
        <div class="container desktop-only" style="margin: 60px auto 20px;">
            <div class="set_center center_box d-flex justify-content-center align-items-center" style="margin: auto"><span>REKLAMA</span></div>
        </div>
    </section>

    <x-promotions-slider :leaflets="$leaflets_promo"/>

    <x-info-slider/>

    <x-commercial-networks-slider :stores="$stores"/>

    <x-most-products-slider :products="$products"/>

    <x-commercial-online :online="$online"/>


    <x-chains-category :categories="$category_stores"/>

    <x-largest-cities :places="$places"/>


    <x-vouchers-slider :vouchers="$vouchers"/>


    <div class="col-md-6 mt-4 rek-box"><span class="span_gftdgc">REKLAMA</span></div>

    <x-leaflets-filter  :leaflets="$leaflets" :categories="$category_stores"/>

    <x-blog-slider :blogs="$blogs"/>

    <div class="col-md-6 mt-4 rek-box set_rek"><span class="rek_margin">REKLAMA</span></div>

    <x-description/>

    <x-faq/>

    <x-newsletter/>
</x-layout.layout>

