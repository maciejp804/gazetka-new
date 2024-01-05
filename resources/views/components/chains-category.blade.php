<section class="category-img1 section-t-padding section-b-padding section_8axb4n">
    <div class="container">
        <div class="row">

            <div class="section-title3"><h2 class="desktop-only h2_otkss5"><span>Kategorie sieci handlowych</span></h2>
                <h2 class="mob-only">Kategorie sieci handlowych
                    <div class="border_color"></div>
                </h2>
            </div>

            <div class="col">
                <div class="owl-carousel home-category owl-theme">

                    <div class="items">
                        <div class="h-cate">
                            <div class="c-img">
                                <a href="/chains/?category=1" class="home-cate-img">
                                    <img class="img-fluid img_msvxb0"
                                         src="{{asset('assets/media/online_stores/1.png')}}"
                                         alt="cate-image">
                                </a>
                            </div>
                            <span class="cat-num">Wszystkie</span></div>
                    </div>
                    @foreach($categories as $category)
                        <div class="items">
                            <div class="h-cate">
                                <div class="c-img">
                                    <a href="sieci-handlowe-{{$category->slug}},{{$category->id}}/" class="home-cate-img">
                                        <img class="img-fluid img_msvxb0"
                                             src="{{asset('assets/media/online_stores/2.png')}}"
                                             alt="cate-image">
                                    </a>
                                </div>
                                <span class="cat-num">{{$category->name}}</span></div>
                        </div>
                    @endforeach
               </div>
            </div>
            <div class="all-blog text-center mt-4 mob-only"><a href="#" class="btn btn-style1 a_g015op">Zobacz więcej</a>
            </div>
        </div>
    </div>
</section>
