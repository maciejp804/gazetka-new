<section class="h-t-products1 first-section">
    <div class="container">
        <div class="row">
            @if($title != '')
            <x-slider-title :title="$title" :placeSlug="$placeSlug"/>
            @endif
            <div class="col">
                <div class="owl-carousel
                @if(isset($class))
                {{$class}}
                @else
                trending-products
                 @endif
                 owl-theme first-product">
                    @foreach($items as $item)
                        <div class="items w-set">
                            <x-leaflet-component :item="$item"/>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<div class="all-blog text-center mt-4 mob-only"><a href="/gazetki-promocyjne-wszystkie,0/{{$placeSlug}}" class="btn btn-style1 a_o8xtjv">Zobacz więcej</a></div>
