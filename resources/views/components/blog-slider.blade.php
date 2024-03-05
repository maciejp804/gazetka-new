<section class="h-t-products1 section-t-padding section-b-padding blog">
    <div class="container">
        <div class="row">
            <div class="section-title3"><h2 class="desktop-only h2_vpfewn"><span>Ostatnie wpisy blogowe</span></h2>
                <h2 class="mob-only h2_z8fa18">Ostatnie wpisy blogowe
                    <div class="border_color"></div>
                </h2>
                <h2 class="desktop-only h2_tsdovv">Zobacz więcej</h2></div>
            <div class="col">
                <div class="owl-carousel trending-productss owl-theme owl-loaded owl-drag">
                    @foreach($blogs as $blog)
                    <div class="col-md-3 items hover_eff">
                        <div class="tred-pro image_container_s">
                            <div class="tr-pro-img member">
                                <a href="abc-zakupowicza/{{$blog->categories->slug}}/{{$blog->slug}}">
                                    <img class="img-fluid image_s"
                                         src="{{asset($blog->image_thumbnail)}}"
                                         alt="pro-img1">
                                </a>
                            </div>
                            <div class="pro-icn"></div>
                        </div>
                        <div class="caption">
                            <div class="pro-price div_3id9cs"><span class="old-price">{{monthReplace($blog->created_at,'d-m-Y',' ')}}</span></div>
                            <h3 class="text-black"><a class="hover_eff_title a_8okvwe" href="abc-zakupowicza/{{$blog->categories->slug}}/{{$blog->slug}}">{{$blog->title}}</a></h3>
                            <div class="pro-price div_j090ik"><span
                                    class="old-price span_doglm7">{!!$blog->excerpt!!}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
