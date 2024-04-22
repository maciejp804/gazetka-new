<x-layout.layout>
    <x-slot:title>
        {{$metaTitle}}
    </x-slot:title>
    <x-slot:meta_description>
        {{$metaDescription}}
    </x-slot:meta_description>
    <x-header :placeSlug="$placeSlug"/>

    <section class="slider mt-4 desktop-only set_center">
        <div class="container cont_set">
            <h1 class="margins_set_title h1_g29nbe">{!! $h1Title !!}</h1>
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
        <h1 class="box-fonts h1_uzsvpc">{!! $h1Title !!}</h1>
    </section>

    <div class="col-md-3 mob-only box-gray"><span class="align_centers">REKLAMA</span></div>
    <section>
        <div class="right_box" style="position: absolute; left: 0"><span>REKLAMA</span></div>
        <div class="right_box" style="position: absolute; right: 0;"><span>REKLAMA</span></div>
        <div class="container desktop-only" style="margin: 60px auto 20px;">
            <div class="set_center center_box d-flex justify-content-center align-items-center" style="margin: auto"><span>REKLAMA</span></div>
        </div>
    </section>
    <x-dropdown-header/>
    <x-dropdown-url-filter :categories="$leafletCategories" :category="$leafletCategory" :categoryPath="$leafletCategoryPath" :placeSlug="$placeSlug" :route="$route"/>
    <div id="promotions-box">
        <x-promotions-box-filter :items="$leaflets" :route="$route"/>
    </div>
    <x-slider-category :items="$leafletCategories" :slug="$leafletCategoryPath" :placeSlug="$placeSlug"/>
    <x-most-products-slider :products="$products"/>

@if($pageDescription !== null)
   <x-description :items="$pageDescription"/>
@endif

@if($descCollection->questions->isNotEmpty())
   <x-faq :items="$descCollection->questions"/>
@endif

<x-newsletter/>
<x-footer/>
<x-slot:route>
   {{$route}}
</x-slot:route>
</x-layout.layout>
