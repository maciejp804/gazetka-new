<section class="category-img1 section-t-padding section-b-padding section_8axb4n">
    <div class="container">

        <div class="row">
            @if(isset($title))
            <div class="section-title3">
                <h2 class="desktop-only h2_otkss5">
                    <span>{!! $title !!}</span>
                </h2>
                <h2 class="mob-only">{!! $title !!}
                    <div class="border_color"></div>
                </h2>
            </div>
            @endif
            <div class="col">
                <div class="owl-carousel home-category owl-theme">
                    @foreach($items as $item)
                        <div class="items">
                            <div class="h-cate">
                                <div class="c-img">
                                    <a href="/{{$slug.'-'.$item->slug.','.$item->category_index.'/'.$placeSlug}}" class="home-cate-img">
                                        <img class="img-fluid img_msvxb0"
                                             src="{{asset($item->image_path)}}"
                                             alt="cate-image">
                                    </a>
                                </div>
                                <span class="cat-num">{{$item->name}}</span></div>
                        </div>
                    @endforeach
               </div>
            </div>
            <div class="all-blog text-center mt-4 mob-only">
                <a href="/{{$slug}}-wszystkie,0/" class="btn btn-style1 a_g015op">Zobacz więcej</a>
            </div>
        </div>
    </div>
</section>
