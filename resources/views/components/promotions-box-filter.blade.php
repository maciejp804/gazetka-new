<section class="h-t-products1">
    <div class="container">
        <div class="tabcontent" id="paris">
            <div class="home-pro-tab">
                <div class="d-flex filter-promotions" style="gap: 20px; flex-wrap: wrap;">
                    @foreach($items as $item)

                            @if($route == 'leafletLocal' or $route == 'leaflet' or $route == 'home'  or $route == 'homeLocal')
                            <div class="happy_hover item ">
                            <x-leaflet-component :item="$item"/>
                            </div>
                            @endif

                            @if($route == 'chainLocal' or $route == 'chain')
                                    <div class="items set_eff_hovers">
                                    <x-chain-compopnent :item="$item"/>
                                    </div>
                                @endif

                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
