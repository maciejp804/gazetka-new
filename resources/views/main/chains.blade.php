<x-layout.layout>
    <x-slot:title>
        {{$metaTitle}}
    </x-slot:title>
    <x-slot:meta_description>
        {{$metaDescription}}
    </x-slot:meta_description>
    <section class="slider mt-4 set_center mb-4 desktop-only">
        <div class="container cont_set">
            <h1 class="margins_set_title h1_g29nbe">Sieci <b> handlowe</b>
            </h1>
            <div class="breadcumb">
                <span>Strona główna</span>
                <div class="rounded-1"></div>
                <span>Sieci handlowe</span>
            </div>
        </div>
    </section>

    <section class="slider mt-4 box-blue mob-only">
        <h1 class="box-fonts h1_uzsvpc">Sieci <b> handlowe</b>
        </h1>
        <div class="breadcumb">
            <span>Strona główna</span>
            <div class="rounded-1"></div>
            <span>Sieci handlowe</span>
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
        <h1 class="box-fonts h1_uzsvpc">Sieci <b>handlowe</b></h1>
    </section>

    <div class="col-md-3 mob-only box-gray"><span class="align_centers">REKLAMA</span></div>
    <section>
        <div class="right_box" style="position: absolute; left: 0"><span>REKLAMA</span></div>
        <div class="right_box" style="position: absolute; right: 0;"><span>REKLAMA</span></div>
        <div class="container desktop-only" style="margin: 60px auto 20px;">
            <div class="set_center center_box d-flex justify-content-center align-items-center" style="margin: auto"><span>REKLAMA</span></div>
        </div>
    </section>
    <x-dropdown-url-filter :categories="$storeCategories" :category="$storeCategory" :categoryPath="$storeCategoryPath"/>
    <div id="promotions-box">
    <x-store-box-filter :items="$stores"/>
    </div>
    <section>
        <div class="container desktop-only" style="margin: 60px auto 20px;">
            <div class="set_center center_box d-flex justify-content-center align-items-center" style="margin: auto"><span>REKLAMA</span></div>
        </div>
    </section>
    <x-promotions-slider :items="$leaflets_promo" :place="$place ?? null" :title="$leafletsPromoTitle"/>

    @if(!$pageDescriptions->isEmpty())
        <x-description :items="$pageDescriptions"/>
    @endif
    @if(!$pageQuestions->isEmpty())
        <x-faq :items="$pageQuestions"/>
    @endif

    <x-newsletter/>
</x-layout.layout>

