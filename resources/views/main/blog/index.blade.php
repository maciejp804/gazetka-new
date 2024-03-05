<x-layout.layout>
    <x-slot:title>
        {{$metaTitle}}
    </x-slot:title>
    <x-slot:meta_description>
        {{$metaDescription}}
    </x-slot:meta_description>


    <section class="slider mt-4 desktop-only set_center">
        <div class="container cont_set">
            <h1 class="margins_set_title h1_g29nbe"><b>ABC zakupowicza</b></h1>
            <div class="breadcumb">
                <a href="/"><span>Strona główna</span></a>
                <div class="rounded-1"></div>
                <span><b>ABC zakupowicza</b></span>
            </div>
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
        <h1 class="box-fonts h1_uzsvpc"><b>ABC zakupowicza</b></h1>
        <div class="breadcumb">
            <a href="/"><span>Strona główna</span></a>
            <div class="rounded-1"></div>
            <span><b>ABC zakupowicza</b></span>
        </div>
    </section>
    <div class="col-md-3 mob-only box-gray"><span class="align_centers">REKLAMA</span></div>

    <section>
        <div class="right_box" style="position: absolute; left: 0"><span>REKLAMA</span></div>
        <div class="right_box" style="position: absolute; right: 0;"><span>REKLAMA</span></div>
        <div class="container desktop-only" style="margin: 60px auto 20px;">
            <div class="set_center center_box d-flex justify-content-center align-items-center" style="margin: auto"><span>REKLAMA</span></div>
        </div>
    </section>

    <div class="h-t-products1 section-t-padding section-b-padding">
        <div class="container">

            <div class="row space-between desktop-only">
                <div class="section-title3 desktop-only"><h2 class="h2_7267fp"><span>Kategorie</span></h2></div>

                <x-blog-category :items="$blogCategories" :slug="$slug ?? ''"/>

                <x-blog-excerpt :blogs="$blogs"/>
                @if(!isset($slug))
                <x-blog-excerpt :blogs="$blogs_recenzje"/>
                <x-blog-excerpt :blogs="$blogs_porownania"/>
                <x-blog-excerpt :blogs="$blogs_gazetki"/>
                <x-blog-excerpt :blogs="$blogs_przepisy"/>
                <x-blog-excerpt :blogs="$blogs_aktualnosic"/>
                @endif
                <div class="col-12 d-flex align-items justify-content-center mt-3">
                    @if(isset($slug))
                        {{ $blogs->links() }}
                    @endif
                </div>
            </div>
            <div class="row space-between mob-only">
                <div class="makeitScroll">
                    <div class="w-140p d-flex space-between">
                        <div class="tagged active"><span>Wszystkie</span></div>
                        <div class="tagged"><span>Porady (13)</span></div>
                        <div class="tagged"><span>Recenzje (22)</span></div>
                        <div class="tagged"><span>Porównania (11)</span></div>
                        <div class="tagged"><span>Gazetki (47)</span></div>
                    </div>
                </div>
                <div class="col-12 padding-0 mt-3x"><img src="{{asset('assets/image/pro/mujercest.png')}}" class="w-100"></div>
                <div class="col-12 mt-3x big-blue2 fixed-height mb-3"><span class="white-cont">10.05.2022</span>
                    <h2 class="mt-3 deepTittle">Lorem ipsum dolor sit amet consectetur</h2>
                    <p class="whiteParagraph">Fusce nec dictum lorem. Class aptent taciti sociosqu ad litora torquent per conubia
                        nostra, per inceptos himenaeos. Cras auctor gravida turpis a dapibus. Maecenas non urna pretium nulla
                        lacinia euismod. </p>
                    <div class="author mt-20"><img class="person-round" src="{{asset('assets/image/pro/dave.png')}}"><span class="white-cont ">Jan Kowalski</span>
                    </div>
                </div>
                <div class="pg-0 mb-3">

                    <div class="col-12 mt-3"><img class="imageq" src="{{asset('assets/image/pro/mercao.png')}}">
                        <p class="fast-p">10.05.2022</p>
                        <h2 class="card-title">Lorem ipsum dolor sit amet consectetur</h2><span class="paragraphCard">Consectetur adipiscing elit. Ut posuere, urna nec vehicula. Nullam vitae venenatis tortor. </span>
                        <div class="author blackness mt-20"><img class="person-round" src="{{asset('assets/image/pro/dave.png')}}"><span
                                class="white-cont ">Jan Kowalski</span></div>
                    </div>
                    <div class="col-12 mt-3"><img class="imageq" src="{{asset('assets/image/pro/mercao2.png')}}">
                        <p class="fast-p">10.05.2022</p>
                        <h2 class="card-title">Lorem ipsum dolor sit amet consectetur</h2><span class="paragraphCard">Consectetur adipiscing elit. Ut posuere, urna nec vehicula. Nullam vitae venenatis tortor. </span>
                        <div class="author blackness mt-20"><img class="person-round" src="{{asset('assets/image/pro/diva.png')}}"><span
                                class="white-cont ">Jan Kowalski</span></div>
                    </div>
                </div>
                <div class="col-12 mt-3x p-0"><img src="{{asset('assets/image/pro/mujercest.png')}}" class="w-100"></div>
                <div class="col-12 mt-3x big-bluew fixed-height"><span class="white-cont">10.05.2022</span>
                    <h2 class="mt-3 deepTittle black">Lorem ipsum dolor sit amet consectetur</h2>
                    <p class="whiteParagraph">Fusce nec dictum lorem. Class aptent taciti sociosqu ad litora torquent per conubia
                        nostra, per inceptos himenaeos. Cras auctor gravida turpis a dapibus. Maecenas non urna pretium nulla
                        lacinia euismod. </p>
                    <div class="author mt-20"><img class="person-round" src="{{asset('assets/image/pro/dave.png')}}"><span class="white-cont ">Jan Kowalski</span>
                    </div>
                </div>
                <div class="pg-0">
                    <div class="col-12 mt-3"><img class="imageq" src="{{asset('assets/image/pro/mercao.png')}}">
                        <p class="fast-p">10.05.2022</p>
                        <h2 class="card-title">Lorem ipsum dolor sit amet consectetur</h2><span class="paragraphCard">Consectetur adipiscing elit. Ut posuere, urna nec vehicula. Nullam vitae venenatis tortor. </span>
                        <div class="author blackness mt-20"><img class="person-round" src="{{asset('assets/image/pro/dave.png')}}"><span
                                class="white-cont ">Jan Kowalski</span></div>
                    </div>
                    <div class="col-12 mt-3"><img class="imageq" src="{{asset('assets/image/pro/mercao2.png')}}">
                        <p class="fast-p">10.05.2022</p>
                        <h2 class="card-title">Lorem ipsum dolor sit amet consectetur</h2><span class="paragraphCard">Consectetur adipiscing elit. Ut posuere, urna nec vehicula. Nullam vitae venenatis tortor. </span>
                        <div class="author blackness mt-20"><img class="person-round" src="{{asset('assets/image/pro/diva.png')}}"><span
                                class="white-cont ">Jan Kowalski</span></div>
                    </div>

                </div>
                <div class="col-12 d-flex align-items justify-content-center mt-3"><span class="azulito">Pokaż więcej</span>
                </div>
            </div>
        </div>
    </div>
</x-layout.layout>
