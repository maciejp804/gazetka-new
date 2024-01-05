<section class="category-img1 section-t-padding section-b-padding section_9omfb6">
    <div class="container">
        <div class="section-title3"><h2 class="desktop-only"><span>Gazetki promocyjne w największych polskich miastach</span>
            </h2>
        </div>
        <div class="">
            <div class="d-flex">
                @foreach($places->split($places->count()/7) as $row)
                <div class="col">
                    <ul class="f-link-ul collapse show ul_jrkjha d-flex flex-column flex-md-wrap id="t-cate" data-bs-parent="#footer-accordian">
                        @foreach($row as $place)
                            <li class="f-link-ul-li ser_pad"><a href="/{{$place->slug}}/" class="a_41xate">{{$place->name}}</a></li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
            <div class="all-blog text-center mt-4 mob-only"><a href="#" class="btn btn-style1 a_a996f6">Zobacz więcej</a>
            </div>
        </div>
     </div>
</section>
