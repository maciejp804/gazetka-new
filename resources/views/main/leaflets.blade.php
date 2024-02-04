<x-layout.layout>
    <x-slot:title>
        {{$metaTitle}}
    </x-slot:title>
    <x-slot:meta_description>
        {{$metaDescription}}
    </x-slot:meta_description>
    <section class="slider mt-4 desktop-only set_center">
        <div class="container cont_set">
            <h1 class="margins_set_title h1_g29nbe">Gazetki <b>promocyjne</b> z kategorii {{$leafletCategory->name}}</h1>
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
        <h1 class="box-fonts h1_uzsvpc">Gazetki <b>promocyjne</b> z kategorii {{$leafletCategory->name}}</h1>
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
    <x-dropdown-url-filter :categories="$leafletCategories" :category="$leafletCategory" :categoryPath="$leafletCategoryPath"/>
    <div id="promotions-box">
        <x-promotions-box-filter :items="$leaflets"/>
    </div>
    <x-slider-category :categories="$leafletCategories" :leafletCategoryPath="$leafletCategoryPath"/>
    <x-most-products-slider :products="$products"/>

    @if(!$pageDescriptions->isEmpty())
        <x-description :items="$pageDescriptions"/>
    @endif
    @if(!$pageQuestions->isEmpty())
        <x-faq :items="$pageQuestions"/>
    @endif
    <x-newsletter/>
</x-layout.layout>
