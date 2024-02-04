<section class="h-t-products1 section-t-padding section-b-padding bio">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-4">
                <img class="img-fluid additional-image img_bmw" src="{{asset($place->image_path)}}">
            </div>

            <div class="col pr-3"><h3 class="mt-4 h3_bmw">{{$place->name}}</h3>
                <p class="old-price mt-4 p_bmw m-b-30">{!! $placeDescription->body !!}</p>
                <div class="specs_bmw"><p class="h32_bmw">Powierzchnia:</p>
                    <p class="p2_bmw">{{$place->surface}} km<sup>2</sup></p></div>
                <div class="specs_bmw"><h3 class="h32_bmw">Data założenia:</h3>
                    <p class="p2_bmw">{{$place->foundation}}</p></div>
                <div class="specs_bmw"><h3 class="h32_bmw">Liczba ludności:</h3>
                    <p class="p2_bmw">{{number_format($place->population, 0, ' ', ' ')}}</p></div>
                <div class="specs_bmw"><h3 class="h32_bmw">Województwo:</h3>
                    <p class="p2_bmw">{{$place->voivodeship->name}}</p></div>
            </div>
        </div>
    </div>
</section>
