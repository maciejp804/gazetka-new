<section class="h-t-products1 section-t-padding section-b-padding corsa">
    <div class="container">
        <div class="row">
            <div class="section-title3"><h2 class="desktop-only h2_fwcjux"><span>Sieci w pobliżu Twojej lokalizacji</span>
                </h2>
                <h2 class="mob-only">Sieci w pobliżu Twojej lokalizacji
                    <div class="border_color"></div>
                </h2>
            </div>

            <div class="corsatable">
                @foreach($markers as $marker)
                <div class="corsa-line">
                    <p class="firstElement elem-corsa">{{$marker->stores->name ?? ''}}, {{$marker->places->name}}, {{$marker->address}}</p>
                    <p class="secondElement elem-corsa">

                        @if(date('N') <= 5)
                            {{$marker->weekdays}}
                        @elseif(date('N') == 6)
                            {{$marker->saturday}}
                        @elseif(date('N') == 7)
                            {{$marker->sunday}}
                        @endif</p>
                    <p class="thirdElement elem-corsa">Sprawdź na mapie</p></div>
                @endforeach

            </div>
        </div>
    </div>
</section>
